import os
from dataclasses import dataclass, field
from pathlib import Path

from pydantic import BaseModel

from app.config.constants import BASE_DIR


@dataclass(frozen=True)
class MessageConfig:
    DOWNLOADED_MEDIA_MESSAGES_DIR: Path = BASE_DIR / Path("messages/{}_messages/")
    SYSTEM_MEDIA_MESSAGES_DIR: Path = BASE_DIR / Path("messages/system_response_messages/")
    WHATSAPP_MEDIA_URL: str = 'https://graph.facebook.com/v23.0/{media_id}'
    WHATSAPP_HEADERS: dict = field(default_factory= lambda:
        {'Authorization': f'Bearer {os.getenv("WHATSAPP_CLOUD_ACCESS_TOKEN")}'})
    WHATSAPP_MESSAGE_URL = "https://graph.facebook.com/v23.0/{}/messages"
    WHATSAPP_UPLOAD_MEDIA_URL = "https://graph.facebook.com/v23.0/{user_phone_number_id}/media"


class MedicalFlag(BaseModel):
    patient_phone: str
    reason: str