
from enum import Enum
from typing import List, Literal, Optional
from datetime import datetime, timezone

from pydantic import BaseModel, Field, model_validator

from app.modules.multilingual_support_module import LocalDialectSupportStrategy

class SystemAgents(Enum):
    SET_REMINDERS_ASSISTANT = "set_reminders_assistant"
    GDM_ASSISTANT_AGENT = "gdm-assistant"
    USER_INFO_RETRIEVAL_AGENT = "retrieve-information"
    CDS_ASSISTANT = "cds_assistant"
    VISITOR_ASSISTANT = "visitor-assistant"
    MANAGEMENT_PLAN_ASSISTANT = "management_plan_assistant"
    ISSUES_ASSISTANT = "cds_patient_issues_assistant"


class UserTypes(Enum):
    PATIENT = "patient"
    DOCTOR = "doctor"
    PATIENT_DATA = "patient_data"
    EHR_SYSTEM = "ehr_system"
    TEST_PATIENT = "test_patient"


class Models(Enum):
    LLAMA_3370 = "llama-3.3-70b-versatile"
    LLAMA_3290 = "llama-3.2-90b-vision-preview"
    MIXTRAL_8X7 = "mixtral-8x7b-32768"
    CHATGPT = "gpt-4o-2024-08-06"

class UserPreference(BaseModel):
    user_id: str
    language: str = Field(default=LocalDialectSupportStrategy.ghana_nlp_asr_available_languages["English"])
    modality: str = Field(default="text")
    agent: Optional[str] = None

    @model_validator(mode="after")
    def set_agent(self):
        if self.user_id.startswith('PAT'):
            self.agent = SystemAgents.GDM_ASSISTANT_AGENT.value
        elif self.user_id.startswith('DOC'):
            self.agent = SystemAgents.CDS_ASSISTANT.value
        elif self.user_id.startswith('EHR'):
            self.agent = SystemAgents.ISSUES_ASSISTANT.value
        return self


class UserAIConversations(BaseModel):
    user_id: str
    conversations: list

class PatientUpdateIssues(BaseModel):
    """Patient issues and updates for doctor."""
    patient_updates: str = Field(description="Detailed description of any symptoms or complications the pregnant mother is experiencing")
    patient_issues: str = Field(description="updates on pregnancy progress, including test results, fetal behavior, or other relevant observations")

class GDMAssistantResponse(BaseModel):
    response_text: str = Field(
        ...,
        description="The AI assistant's natural-language response."
    )

    flag: Literal["normal", "escalate"] = Field(
        ...,
        description="A binary flag indicating whether the conversation should be escalated."
    )

    reason: Optional[str] = Field(
        None,
        description="An optional explanation describing why the escalation flag was set."
    )


class Citation(BaseModel):
    source_id: int = Field(..., description="The integer ID of a SPECIFIC source which justifies the answer.")
    quote: str = Field(..., description="The VERBATIM quote from the specified source that justifies the answer.")

class ClinicalAssessmentResponseStructure(BaseModel):
    impressions: str = Field(..., description="A summary of the clinical impression.")
    plans: str = Field(..., description="The proposed management plan or recommendations.")
    citations: List[Citation] = Field(..., description="Citations from the given sources that justify the answer.")

class PatientPreferenceRequest(BaseModel):
    phone: str
    language: Literal['en', 'tw']
    modality: Literal['audio', 'text']

class UpdatePIPRequest(BaseModel):
    patient_phone_number: str
    new_management_plan: str
    patient_name: str

class Reminder(BaseModel):
    reminder_text: str
    reminder_time: Literal["morning", "afternoon", "evening"]
    is_active: bool = True

class PatientRemindersResponse(BaseModel):
    patient_phone_number: str|None
    reminders: List[Reminder]

class ReminderMessage(BaseModel):
    body: str

class SendReminderRequest(BaseModel):
    recipient_phone_number: str
    reminder: ReminderMessage

class UserAIConversationsDTO(BaseModel):
    user_id: str
    user_question: str
    ai_response: str
    timestamp: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class PatientProfile(BaseModel):
    user_id: str
    patient_name: str
    patient_phone_number: str
    latest_management_plans: str
    business_phone_number: Optional[str] = None
    patient_preference: Optional[UserPreference] = None

    @model_validator(mode="after")
    def set_preference(self):
        if self.patient_preference is None:
            self.patient_preference = UserPreference(user_id=self.user_id)
        return self
