import asyncio
import os

import httpx

from app.services.dal.repositories.schemas import PatientProfile
from app.services.dal.schemas import UserPreference, SystemAgents, UserTypes
# from core.dal.data_repositories import Repository
from app.modules.multilingual_support_module import MultiLingualSupportStrategy, LocalDialectSupportStrategy, \
    OfficialDialectSupportStrategy


class User:

    def __init__(self, user_id: str, repository_handler, user_type: UserTypes, user_assisting_agent: SystemAgents=None):
        self.user_id = user_id # this at the moment is their phone numbers
        self.preference = None
        self.name = None
        self.business_phone_number = None
        self.user_type = user_type
        self.user_preferred_language = None
        self.lingual_support_strategy = None
        self.conversation = []
        self.user_preferred_modality = None
        self.user_assisting_agent = user_assisting_agent
        self.repository_handler = repository_handler  # dal -> data access layer
        self.validated = False
        self.treatment_plan = None

    async def _validate_patient(self) -> bool:

        if self.user_type is not UserTypes.PATIENT:
            return False

        async with self.repository_handler as repository:
            repository.set_repository_strategy(new_repository_strategy="patient_profile")
            try:
                patient_profile: PatientProfile = await repository.get(user_id=self.user_id)
                print("PROFILE", patient_profile)
            except ModuleNotFoundError:
                print("[INFO] The patient was not found in the database")
                return False
            except Exception as e:
                print("An error", e)
                raise

        self.business_phone_number = patient_profile.business_phone_number
        self.name = patient_profile.patient_name
        self.treatment_plan = patient_profile.latest_management_plans
        self.preference = patient_profile.patient_preference

        print("TREATMENT", self.treatment_plan)

        return True

    async def _validate_patient_on_remote_ehr(self) -> bool:

        # if os.getenv("IS_RUNNING_LOCALLY"):
        #     return True
        print("Validating patient remotely")

        ehr_backend_endpoint = os.getenv("EHR_BACKEND_URL") + "/patients/search"
        ehr_backend_endpoint_api_key = os.getenv("EHR_API_KEY")

        headers = {
            "Api-Key": f"{ehr_backend_endpoint_api_key}",
            "Accept": "application/json"
        }
        params = {
            "patient_phone_number": "0" + self.user_id[-9:] # rewrite digit into recognisable format with ehr backend
        }

        async with httpx.AsyncClient() as client:
            response = await client.get(ehr_backend_endpoint,
                                         headers=headers,
                                         params=params)

        response.raise_for_status()

        # if response.status_code == 200:
        patient_info = response.json()['data']
        print("patient info", patient_info)
        self.name = f'{patient_info["first_name"]} {patient_info["last_name"]}'

        patient_records = patient_info.get('patient_records', None)

        if patient_records and len(patient_records) > 0:
            self.treatment_plan = patient_records[-1].get('plan', "No treatment plan provided yet")  # pick the latest recommendations from doctor
        else:
            self.treatment_plan = "No treatment plan provided yet"

        new_patient_profile = PatientProfile(
            user_id=self.user_id,
            patient_name=self.name,
            patient_phone_number="0" + self.user_id[-9:],
            latest_management_plans=self.treatment_plan
        )

        async with self.repository_handler as repository:
            repository.set_repository_strategy(new_repository_strategy="patient_profile")
            await repository.save(data=new_patient_profile)

        return True
        # else:
        #     print(f"Failed: {response.status_code}, {response.text}")
        #     return False

    async def init(self):
        self.preference = UserPreference(user_id=self.user_id) # begin with default

        self.validated = await self._validate_patient()

        if self.user_type is UserTypes.PATIENT and not self.validated: #and not os.getenv("IS_RUNNING_LOCALLY"):
            try:
                self.validated = await self._validate_patient_on_remote_ehr()
            except httpx.HTTPStatusError as err:
                if err.response.status_code == 404:
                    print("[INFO] Patient not found.")
                elif err.response.status_code >= 400 :
                    print(f"[INFO] Remote EHR platform failed to process request: {err}")
                else:
                    raise Exception(err)

            except Exception as err:
                print(f"[ERROR] Couldn't connect to remote EHR. Assuming patient is not registered. {err}")
                self.validated = False
            self.user_assisting_agent: SystemAgents = SystemAgents.VISITOR_ASSISTANT if (
                not self.validated) else SystemAgents.GDM_ASSISTANT_AGENT
        else:
            # skip validation check
            self.user_assisting_agent: SystemAgents = SystemAgents(self.user_assisting_agent) if self.user_assisting_agent is not None else SystemAgents(self.preference.agent)


        self.lingual_support_strategy: MultiLingualSupportStrategy = LocalDialectSupportStrategy() \
            if self.user_preferred_language in list(LocalDialectSupportStrategy.ghana_nlp_asr_available_languages.values())[:-1] \
            else OfficialDialectSupportStrategy()  # Store the strategy

        self.user_preferred_modality: str = self.preference.modality
        self.user_preferred_language = self.preference.language

        async with self.repository_handler as repository:
            repository.set_repository_strategy(new_repository_strategy="conversations")
            conversation_obj = await repository.get(user_id=self.user_id)

        self.conversation: list = conversation_obj.conversations
        print("[INFO] Conversation list at user init ", self.conversation)

    def __repr__(self):
        return (
            f"User(user_id={self.user_id}, "
            f"preferred_language={self.user_preferred_language}, "
            f"lingual_support_strategy={self.lingual_support_strategy.__class__.__name__ if self.lingual_support_strategy else None}, "
            f"preferred_modality={self.user_preferred_modality}, "
            f"assisting_agent={self.user_assisting_agent})"
            f"is_validated={self.validated}"
        )

# if __name__ == "__main__":
#     test_repository_handler = Repository()
#     mock_user = User(user_id="PAT_233562857121", repository_handler=test_repository_handler, user_type=UserTypes.PATIENT)
#     asyncio.run(mock_user.init())
#
#     print(mock_user)

    # asyncio.run(mock_user.__validate_patient("233557211558"))
