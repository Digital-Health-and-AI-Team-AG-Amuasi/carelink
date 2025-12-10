import asyncio
from typing import Any

from dotenv import load_dotenv

from core.dal.repositories.abstracts import IConversationsRepository
from core.dal.repositories.schemas import UserAIConversationsDTO

load_dotenv()


class ConversationsRepository(IConversationsRepository):

    """A repository implementation for managing user-ai conversations using mongodb storage"""

    def __init__(self):
        super().__init__()
        self._conv_collection = self.db["conversation_collection"]


    async def save_conversation_turn(self, entry: UserAIConversationsDTO):

        try:
            self._conv_collection.insert_one(entry.model_dump())
            print("[INFO] Conversations successfully saved to storage")
        except Exception as e:
            print(f"Could not save conversations to storage: {e}")
            raise


    async def get_all_user_conversation_turns(self, user_id: str) -> list[Any] | None:
        try:
            results = self._conv_collection.find({"user_id": user_id}).sort("timestamp", 1)
            conversation_history = []

            for result in results:
                conversation_history.extend([("human", result["user_question"]), ("ai", result["ai_response"])])

            return conversation_history

        except Exception as e:
            print(e)
            raise


if __name__ == "__main__":
    mongo_store = ConversationsRepository()
    convo = UserAIConversationsDTO(
        user_id="1111",
        user_question="How are you?",
        ai_response="I am fine!",
    )
    print(asyncio.run(mongo_store.save_conversation_turn(convo)))

    print(asyncio.run(mongo_store.get_all_user_conversation_turns(user_id="1111")))