from abc import abstractmethod, ABC
from typing import List, Dict

from core.dal.db.mongodb import MongoDB
from core.dal.repositories.schemas import UserAIConversationsDTO, PatientProfile


class IConversationsRepository(ABC, MongoDB):
    @abstractmethod
    def save_conversation_turn(self, entry: UserAIConversationsDTO):
        raise NotImplementedError

    @abstractmethod
    def get_all_user_conversation_turns(self, user_id: str) -> List[Dict[str, str]]:
        raise NotImplementedError


class IPMPMPRepository(ABC, MongoDB):
    @abstractmethod
    def save_pmpmp(self, entry: PatientProfile):
        raise NotImplementedError

    @abstractmethod
    def get_pmpmp(self, patient_id: str):
        raise NotImplementedError
