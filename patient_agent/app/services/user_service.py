
import os
import logging
from app.services.dal.repositories.schemas import PatientProfile
from app.modules.ai.ai_assistant_module import run_query_by_model
from app.services.dal.schemas import UserTypes, SystemAgents, UserPreference, PatientRemindersResponse, SendReminderRequest
from app.modules.messanger_module import Message, MessageResponse
from app.modules.user_module import User
from app.modules.utils_obs import repository_handler, messanger, ProgramError

logger = logging.getLogger(__name__)

async def update_patient_management_plan(pip_request):
    print("pip request", pip_request)
    patient_phone_id = f"PAT_233{(pip_request.patient_phone_number[-9:])}"
    new_management_plan = pip_request.new_management_plan
    patient_name = pip_request.patient_name

    required_assistant = "set_reminders_assistant"

    repository_handler.set_repository_strategy("patient_profile")
    old_patient_profile: PatientProfile = await repository_handler.get(patient_phone_id)

    new_patient_profile = PatientProfile(
        user_id=patient_phone_id,
        patient_name=patient_name,
        patient_phone_number=pip_request.patient_phone_number,
        latest_management_plans=new_management_plan,
        business_phone_number=old_patient_profile.business_phone_number if old_patient_profile.business_phone_number else None,
        patient_preference=old_patient_profile.patient_preference
    )

    repository_handler.set_repository_strategy("patient_profile")
    await repository_handler.save(new_patient_profile)
    
    print("pmpmp", new_patient_profile.model_dump())

    ehr_user: User = User("EHR_1234", repository_handler=repository_handler, user_type=UserTypes.EHR_SYSTEM, user_assisting_agent=SystemAgents(required_assistant))
    await ehr_user.init()

    success, result = await run_query_by_model(ehr_user, new_management_plan, mode="text")

    if not success:
        raise ProgramError("Your request couldn't be completed")

    if not isinstance(result, PatientRemindersResponse):
        raise ProgramError("Unexpected output from model")

    result.patient_phone_number = new_patient_profile.patient_phone_number
    result = result.model_dump()

    print("data", result)
    return result

async def process_cds_request(body):
    print("body", body)

    required_assistant = body.get("required_assistant")
    patient_data = body.get("patient_data", None)
    doctor_data = body.get("doctor_data", None)

    phone_number = f"PAT_233{(patient_data['patient_phone_number'][-9:])}"

    if SystemAgents(required_assistant) is SystemAgents.ISSUES_ASSISTANT:
        patient_user: User = User(user_id=phone_number,
                                  repository_handler=repository_handler,
                                  user_type=UserTypes.PATIENT_DATA)
        await patient_user.init()

        ehr_user: User = User("EHR_1234",
                              repository_handler=repository_handler,
                              user_type=UserTypes.EHR_SYSTEM,
                              user_assisting_agent=SystemAgents(required_assistant))
        await ehr_user.init()

        data = patient_user.conversation
        user = ehr_user
    else:
        if patient_data and not doctor_data:
            raise ValueError("missing values: 'patient_data' or 'doctor_data'")

        doctor_user: User = User(f"DOC_{doctor_data['doctor_id']}", repository_handler, UserTypes.DOCTOR, SystemAgents(required_assistant))
        await doctor_user.init()

        user = doctor_user

        if patient_data:
            patient_user: User = User(f"PAT_{patient_data['patient_phone_number']}", repository_handler, UserTypes.PATIENT_DATA)
            await patient_user.init()

            patient_data = dict(patient_data)
            patient_data["user_ai_conversations"] = patient_user.conversation

        data = patient_data if patient_data else str(doctor_data["question"])

    success, ai_response = await run_query_by_model(user, message=data, mode="text")
    print(ai_response)
    
    return success, ai_response

async def process_webhook_message(body, headers):
    print("request body", body)

    if "entry" in body:
        message_object = Message.from_whatsapp(
            request_body=body
        ) 
        user_id = f"PAT_{message_object.messenger_phone_number}"
        mark_seen = True
    elif headers.get("X-Sender") == "laravel-job-queue":
        message_object = Message.from_ehr_request(
            request_payload=SendReminderRequest(**body)
        )
        user_id = f"EHR_{message_object.messenger_phone_number}"
        mark_seen = False
    else:
        raise ValueError("Unknown sender")

    message_text = await message_object.get_message()

    user = User(user_id=user_id, repository_handler=repository_handler, user_type=UserTypes("patient"))
    await user.init()

    print(f"[INFO] User {user}")

    if not user.business_phone_number:
        user.business_phone_number = message_object.business_phone_number_id

        if user.validated: 
            async with repository_handler as repository:
                repository.set_repository_strategy(new_repository_strategy="patient_profile")
                patient_profile: PatientProfile = await repository.get(user_id=user_id)
                print(f"[INFO] Profile {patient_profile}")
                patient_profile.business_phone_number = message_object.business_phone_number_id
                print(f"[INFO] Profile after {patient_profile}")

                await repository.save(patient_profile)

    success, ai_response = await run_query_by_model(user, message_text, message_object.message_type)

    _, ai_response = await user.lingual_support_strategy.translate(text=ai_response, source_language="en", target_language=user.user_preferred_language)
    message_response = MessageResponse(message_type="text", message_body=ai_response, recipient_business_id=user.business_phone_number)

    if not os.getenv("IS_RUNNING_LOCALLY"):
        success = await messanger.send_response(message_response=message_response, user_input_message_object=message_object, mark_seen=mark_seen)
        return success, "Reminder successfully sent to patient", ai_response

    return success, ai_response, None

async def get_patient_preferences(phone_number):
    user_id = "PAT_233" + str(phone_number)[-9:]

    async with repository_handler as repository:
        repository.set_repository_strategy("preferences")
        pref = await repository.get(patient_id=user_id)
        print("pref", pref)

    return {
        "user_id": user_id,
        "modality": pref.modality,
        "language": pref.language
    }
