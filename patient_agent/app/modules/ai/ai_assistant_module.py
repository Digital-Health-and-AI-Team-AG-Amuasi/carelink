import asyncio
from pathlib import Path

from dotenv import load_dotenv
from langchain_core.prompts import ChatPromptTemplate, MessagesPlaceholder
from langchain_groq import ChatGroq
from langchain_openai import ChatOpenAI
from pydantic import BaseModel

from app.modules import utils_obs
from app.services.dal.schemas import Models, UserAIConversations, PatientUpdateIssues, UserTypes, \
    ClinicalAssessmentResponseStructure, GDMAssistantResponse
from app.services.dal.schemas import PatientRemindersResponse
from app.modules.ai.ai_prompts import *
from app.modules.ai.llm_tools import tools_list
from app.modules.module_utils import raise_flag_for_patient
from app.modules.multilingual_support_module import LocalDialectSupportStrategy
from app.modules.schemas import MedicalFlag
from app.modules.user_module import User
from app.modules.utils_obs import repository_handler, ocr_handler

load_dotenv()


# Initial Prompt
initial_prompt = ChatPromptTemplate.from_messages(
    [
        ("system", "{system_prompt}"),
        ("human", "{question}")
    ]
)

# Create a chat prompt template with the system and human messages
prompt_with_history = ChatPromptTemplate.from_messages(
    [
        ("system", "{system_prompt}"),
        MessagesPlaceholder("history"),
        ("human", "{question}")
    ]
)


CURRENT_MODEL_CHOICE = Models.LLAMA_3370
print(f"[INFO] Loading {CURRENT_MODEL_CHOICE} model")
# logging.info(f"[INFO] Loading {CURRENT_MODEL_CHOICE} model")

if CURRENT_MODEL_CHOICE == Models.CHATGPT:
    model = ChatOpenAI(model=CURRENT_MODEL_CHOICE, temperature=0)
else:
    raw_model = ChatGroq(model=CURRENT_MODEL_CHOICE)
    if tools_list is not None:
        model = raw_model.bind_tools(tools_list)
    else:
        model = raw_model

model_patient_issues_update = model.with_structured_output(PatientUpdateIssues)
model_impressions_plans = model.with_structured_output(ClinicalAssessmentResponseStructure)
model_set_reminder = model.with_structured_output(PatientRemindersResponse)
model_gdm_assistant = model.with_structured_output(GDMAssistantResponse)


async def parse_prompt(user: User, message: str|Path|list, mode: str):
    """Use the strategy to send a notification."""
    text = None
    user.user_preferred_language = user.user_preferred_language

    if message is None:
        raise ValueError("Message cannot be empty")

    if isinstance(user.lingual_support_strategy, LocalDialectSupportStrategy) \
        and user.user_type is not UserTypes.PATIENT:
        raise ValueError(f"Current user type '{user.user_type}' is not supported for local language translation")

    if mode == "audio":
        success, text = await user.lingual_support_strategy.speech_to_text(file_path=message, language=user.user_preferred_language)  # STTx

        if not success:
            raise ValueError(text)

    elif mode == "image":
        user.user_assisting_agent = SystemAgents.USER_INFO_RETRIEVAL_AGENT
        success, text = await ocr_handler.perform_ocr(downloaded_image_message_file_path=message)

        if not success:
            raise ValueError(text)

    if text is None:
        text = message

    text = await user.lingual_support_strategy.translate(text=text, source_language=user.user_preferred_language, target_language="en")  # Translate if required

    return text


async def run_query_by_model(user: User, message: str|dict|list, mode: str):

    # print("[Warning] default response is being returned without hitting the llm resource")
    # logging.warn("[Warning] default response is being returned without hitting the llm resource")
    # return True, {'patient_phone_number': '0537287636', 'reminders': [{'reminder_text': 'Remember to eat a healthy lunch as part of your GDM diet plan', 'reminder_time': 'afternoon', 'is_active': True}]}

    _, parsed_prompt = await parse_prompt(user, message, mode) # parse the message into an appropriate format. input could audio, a different language, etc

    formatted_data = format_patient_data(parsed_prompt)

    prompt = get_prompt_template(formatted_data, user.conversation,
                                 user.user_assisting_agent,
                                 patient_name=user.name,
                                 treatment_plan=user.treatment_plan)

    success, llm_response = get_llm_response(prompt, user.user_assisting_agent)

    if isinstance(llm_response, GDMAssistantResponse) and success:
        if llm_response.flag == "escalate":
            task = asyncio.create_task(
                raise_flag_for_patient(
                    MedicalFlag(
                        patient_phone="0" + user.user_id[-9:],
                        reason=llm_response.reason
                    )
                )
            )

            task.add_done_callback(
                lambda t: print(f"[TASK ERROR] {t.exception()}") if t.exception() else print("Done")
            )

        repository_handler.set_repository_strategy("conversations")
        user.conversation.extend([("human", formatted_data), ("ai", llm_response.response_text)])

        await repository_handler.save(UserAIConversations(user_id=user.user_id,
                                                          conversations=user.conversation))
        llm_response = llm_response.response_text

     # data = llm_response
    # if success:  # If the operation was successful, save the conversation to memory
    #
    #     # TODO: return models instead of first transforming it
    #     if isinstance(llm_response, BaseModel):
    #         data = llm_response.model_dump()
    #     else:
    #         repository_handler.set_repository_strategy("conversations")
    #         await repository_handler.save(UserAIConversations(user_id=user.user_id,
    #                                                           conversations=[("human", formatted_data),
    #                                                                          ("ai", llm_response)]))
        # if isinstance(llm_response, PatientUpdateIssues):
        #     data = {
        #         "data": {
        #             "patient_issues": llm_response.issues,
        #             "patient_updates": llm_response.updates
        #         }
        #     }
        # elif isinstance(llm_response, ClinicalAssessmentResponseStructure):
        #     data = {
        #         "data": {
        #             "impressions": llm_response.impression,
        #             "plans": llm_response.plan,
        #             "citations": [c.model_dump() for c in llm_response.citations]
        #         }
        #     }
        # elif isinstance(llm_response, PatientRemindersResponse):
        #     data = llm_response.model_dump()
        # else:
        #     repository_handler.set_repository_strategy("conversations")
        #     await repository_handler.save(UserAIConversations(user_id=user.user_id, conversations=[("human", formatted_data), ("ai", llm_response)]))

    return success, llm_response


def format_patient_data(patient_payload: dict|str) -> str:
    if isinstance(patient_payload, (str, list)):
        return patient_payload

    formatted_strings = ["Please review the following patient data. You will be asked follow-up questions based on this information."]

    patient_data = patient_payload['patient_records']

    print("patient_data", patient_data)

    # Patient Section
    patient = patient_data.get("patient", {})
    formatted_strings.append("**Patient Information**")
    formatted_strings.append(f"ID: {patient.get('id', 'N/A')}")
    formatted_strings.append(f"Name: {patient.get('first_name', 'N/A')} {patient.get('last_name', 'N/A')}")
    formatted_strings.append(f"Date of Birth: {patient.get('dob', 'N/A')}")
    formatted_strings.append(f"Gender: {patient.get('gender', 'N/A')}")
    formatted_strings.append(f"More Diagnostic and Background Information: {patient.get('notes', 'N/A')}")
    if patient_payload['send_chat_as_context']:
        print("Adding chat as context")
        formatted_strings.append(f"conversations patient had with ai gdm assistant: {patient_data.get('user_ai_conversations', 'N/A')}")
    formatted_strings.append("")

    # Encounter Section
    encounters = patient_data.get("visits", [])
    for idx, encounter in enumerate(encounters, start=1):
        formatted_strings.append(f"**Visits {idx}**")
        formatted_strings.append(f"Visit ID: {encounter.get('id', 'N/A')}")
        formatted_strings.append(f"Date: {encounter.get('created_at', 'N/A')}")
        formatted_strings.append(f"Reason: {encounter.get('reason', 'N/A')}")
        formatted_strings.append("")

        # Observations
        if "vitals" in encounter:
            formatted_strings.append("**Vitals**")
            for vits in encounter["vitals"]:
                formatted_strings.append(
                    f"{vits['vital_type']['name']}: {vits['value']} {vits['unit_of_measurement']} (Recorded: {vits['created_at']})"
                )
            formatted_strings.append("")

        # Medications
        if "medications" in encounter:
            formatted_strings.append("**Medications**")
            for med in encounter["medications"]:
                formatted_strings.append(
                    f"{med['drug']['name']}, {med['frequency']} for {med['duration']} (Prescribed on: {med['created_at']})"
                )
            formatted_strings.append("")

    # Pregnancies
    pregnancies = patient_data.get("pregnancies", [])
    for idx, pregnancy in enumerate(pregnancies, start=1):
        formatted_strings.append(f"**pregnancy {idx}**")
        formatted_strings.append(f"Estimated Date of Delivery: {pregnancy.get('edd', 'N/A')}")

    # Conditions Section
    conditions = patient_data.get("conditions", [])
    for idx, condition in enumerate(conditions, start=1):
        formatted_strings.append(f"**Condition {idx}**")
        formatted_strings.append(f"Diagnosis: {condition.get('diagnosis', 'N/A')}")
        formatted_strings.append(f"Description: {condition.get('description', 'N/A')}")
        formatted_strings.append(f"Status: {condition.get('is_active', 'N/A')}")
        formatted_strings.append(f"Doctor's notes: {condition.get('notes', 'N/A')}")
        formatted_strings.append(f"Started at: {condition.get('started_at', 'N/A')}")
        formatted_strings.append(f"Ended at: {condition.get('ended_at', 'N/A')}")
        formatted_strings.append("")

    patient_records = patient_data.get("records", [])
    for idx, record in enumerate(patient_records, start=1):
        formatted_strings.append(f"**Patient Record {idx}**")
        formatted_strings.append(f"Chief Complaints: {record.get('current_complains', 'N/A')}")
        formatted_strings.append(f"Direct Questions: {record.get('on_direct_questions', 'N/A')}")
        formatted_strings.append(f"Issues: {record.get('issues', 'N/A')}")
        formatted_strings.append(f"Updates: {record.get('updates', 'N/A')}")
        formatted_strings.append(f"Examinations: {record.get('on_examinations', 'N/A')}")
        formatted_strings.append(f"Vitals: {record.get('vitals', 'N/A')}")
        formatted_strings.append(f"Labs: {record.get('labs', 'N/A')}")
        formatted_strings.append(f"Impression: {record.get('impression', 'N/A')}")
        formatted_strings.append(f"Plan: {record.get('plan', 'N/A')}")
        formatted_strings.append("")
    # formatted_strings.append(f"Relevant Health Records: {patient.get('patient_records', 'N/A')}")

    return "\n".join(formatted_strings)

def get_prompt_template(user_query: str|dict, user_conversations_history: list,
                        agent: SystemAgents = SystemAgents.GDM_ASSISTANT_AGENT,
                        patient_name=None,
                        treatment_plan=None):

    if agent not in agent_prompts:
        print(f"[WARNING] Unknown agent '{agent}'.")
        raise ValueError(f"[WARNING] Unknown agent '{agent}'.")

    if agent == SystemAgents.ISSUES_ASSISTANT:
        # in this case, the query is specifically the patient AI conversations
        return cds_patient_issues_assistant.format(patient_ai_conversation_history=user_query)
    elif agent == SystemAgents.MANAGEMENT_PLAN_ASSISTANT:
        # in this case, user query is specifically the patient reviews and medical history
        return user_query # management_plan_assistant.format(patient_review_information=user_query)

    system_prompt = agent_prompts[agent]

    if agent == SystemAgents.GDM_ASSISTANT_AGENT:
        if patient_name is None:
            raise ValueError("'patient_name was not provided'")

        system_prompt = system_prompt.format(
            patient_name=patient_name,
            doctor_proposed_treatment_plan=treatment_plan
        )

    if agent == SystemAgents.USER_INFO_RETRIEVAL_AGENT:
        # Always uses initial_prompt, no history
        return initial_prompt.invoke({
            "system_prompt": system_prompt,
            "question": user_query
        })

    # For agents that may include history
    if user_conversations_history:
        return prompt_with_history.invoke({
            "system_prompt": system_prompt,
            "history": user_conversations_history,
            "question": user_query
        })
    else:
        return initial_prompt.invoke({
            "system_prompt": system_prompt,
            "question": user_query
        })

def get_llm_response(prompt, required_assistant) -> tuple[bool, str|BaseModel]:

    try:
        if required_assistant == SystemAgents.ISSUES_ASSISTANT:
            model_response = model_patient_issues_update.invoke(prompt)

            return True, model_response
        elif required_assistant == SystemAgents.MANAGEMENT_PLAN_ASSISTANT:

            rag_response, citations = utils_obs.rag_handler.query(prompt)
            # model_response = model_impressions_plans.invoke(prompt)
            print("response inside doctor", rag_response)

            return True, rag_response
        elif required_assistant == SystemAgents.SET_REMINDERS_ASSISTANT:
            model_response = model_set_reminder.invoke(prompt)

            return True, model_response

        else:
            rag_response = utils_obs.rag_handler.query_for_patient(prompt)

            return True, rag_response
            # model_response = model.invoke(prompt)  # Invoke the model with the prompt
            # tool_calls = model_response.tool_calls
            #
            # for tool_call in tool_calls:
            #     selected_tool = available_tools_dict[tool_call["name"].lower()]
            #     selected_tool.invoke(tool_call["args"])
            #
            # model_response_json = model_response.model_dump_json()  # Invoke the model with the prompt and get the response in JSON format
            # return True, json.loads(model_response_json)[
            #     "content"]  # Parse the JSON response and return it as a Python dictionary

    except Exception as error:
        print(f"[ERROR] There was an error: {error}")
        return False, str(error)



