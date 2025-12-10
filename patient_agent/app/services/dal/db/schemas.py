from enum import Enum


class MongoDBCollectionNames(Enum):
    PATIENT_PROFILE_COLLECTION_NAME = "patient_profile_collection"
    PATIENT_AI_CONVERSATION_COLLECTION_NAME = "chat_conversation_collection"