import asyncio
from abc import abstractmethod

from app.services.dal.repositories.schemas import PatientProfile
from app.services.dal.schemas import UserAIConversations, SystemAgents, UserPreference
from app.services.dal.db_factory import Database


class Repository:

    user_preferences = {}
    user_conversations: dict[
        str, UserAIConversations] = {}  # store conversation data as dictionary to allow for neat data retrieval

    def __init__(self, storage=Database("mongo").selected_database):
        self.__repository_strategy = None
        self.lock = asyncio.Lock()
        self.storage = storage

    async def __aenter__(self):
        print("[INFO] Lock acquired for repository")
        await self.lock.acquire()
        return self  # allow `as data` to be used

    async def __aexit__(self, exc_type, exc_val, exc_tb):
        print("[INFO] Lock released for repository")
        self.lock.release()

    """ Define data mappers"""
    @abstractmethod
    def _to_dict_data_mapper(self, data):
        raise NotImplementedError("this method has not been implemented yet")

    @abstractmethod
    def _from_dict_data_mapper(self, data: dict) -> UserPreference | UserAIConversations:
        raise NotImplementedError("this method has not been implemented yet")

    def set_storage_strategy(self, new_storage_strategy):
        """returns the relevant storage class"""
        self.storage = Database.get_storage(new_storage_strategy)
        return self.storage

    def get_storage_strategy(self):
        return self.storage

    def set_repository_strategy(self, new_repository_strategy: str):

        if new_repository_strategy == "conversations":
            self.__repository_strategy = UserAIConversationsRepository()
        elif new_repository_strategy == "preferences":
            self.__repository_strategy = UserPreferenceRepository()
        elif new_repository_strategy == "patient_profile":
            self.__repository_strategy = PatientProfileRepository()
        else:
            raise ModuleNotFoundError("The requested module has not been implemented")

    @abstractmethod
    def save(self, data):
        return self.__repository_strategy.save(data)

    @abstractmethod
    def delete(self, user_id):
        return self.__repository_strategy.delete(patient_id=user_id)

    @abstractmethod
    async def get(self, user_id):
        """Retrieve data from storage"""
        return await self.__repository_strategy.get(user_id=user_id)


class UserPreferenceRepository(Repository):

    def __init__(self):
        super().__init__()

    def _to_dict_data_mapper(self, user_prefs: UserPreference) -> dict:
        """Converts a UserPreferences object to a dictionary for storage."""
        return {
            "user_id": user_prefs.user_id,
            "preferences": {
                "language": user_prefs.language,
                "modality": user_prefs.modality,
                "agent": user_prefs.agent
            }
        }

    def _from_dict_data_mapper(self, data: dict) -> UserPreference:
        """Converts a dictionary from the database into a UserPreferences object."""

        if isinstance(data, UserPreference):
            return data

        pref = data.get("preferences")

        return UserPreference(
            user_id=data.get("user_id"),
            language=pref.get("language"),
            modality=pref.get("modality"),
            agent=pref.get("agent")
        )

    async def save(self, preference: UserPreference):
        # data = self.__to_dict_data_mapper(preference)
        # print("pref data", data)
        # data = self.__to_dict_data_mapper(preference)
        await self.storage.insert(data=preference, collection_name="preferences")

    async def delete(self, user_id):
        self.storage.delete(user_id)

    async def get(self, user_id) -> UserPreference:

        # data = self.user_preferences.get(user_id)  # Directly fetch from cache
        # print("data", "data", data)
        # if data:
        #     return data  # Ensure we always return a mapped object

        # Attempt fetching from persistent storage
        data = await self.storage.fetch(user_id=user_id, collection_name="preferences")

        # raise ValueError ("data gof", data, type(data))

        if not data.get("preferences"):  # If no data, create a default value
            print("not not data", data)
            # agent = (SystemAgents.CDS_ASSISTANT if user_id.startswith("DOC_")
            #          else SystemAgents.GDM_ASSISTANT_AGENT)
            # modality = ("audio" if user_id.startswith("PAT_")
            #             else "text")
            # language = ("tw" if user_id.startswith("PAT_")
            #             else "en")
            # data = {"user_id": user_id, "agent": agent, "modality": modality, "language": language}
            modality = ("text" if user_id.startswith("PAT_")
                     else "text")
            # language = ("tw" if user_id.startswith("PAT_")
            #          else "en")
            agent = (SystemAgents.CDS_ASSISTANT.value if user_id.startswith("DOC_")
                     else SystemAgents.GDM_ASSISTANT_AGENT.value)

            language = "en"

            data = {
                "user_id": user_id,
                "preferences": {
                    "agent": agent, "modality": modality, "language": language
                }
            }

        # Map and store in cache
        print("dara ob", data)
        data_object = self._from_dict_data_mapper(data)
        self.user_preferences[data_object.user_id] = data_object

        return data_object


class PatientProfileRepository(Repository):

    collection_name = "patient_profile"

    def __init__(self):
        super().__init__()


    def _to_dict_data_mapper(self, patient_profile: PatientProfile) -> dict:
        """Converts a UserPreferences object to a dictionary for storage."""
        return patient_profile.model_dump()


    def _from_dict_data_mapper(self, data: dict) -> PatientProfile:
        """Converts a dictionary from the database into a UserPreferences object."""

        if isinstance(data, PatientProfile):
            return data

        try:
            patient_profile = PatientProfile(**data)
            return patient_profile
        except TypeError as e:
            print(f"[ERROR] The provided data is invalid {e}")
            raise


    async def save(self, patient_profile: PatientProfile):
        # data = self.__to_dict_data_mapper(preference)
        # print("pref data", data)
        # data = self.__to_dict_data_mapper(patient_profile)
        await self.storage.insert(data=patient_profile, collection_name=self.collection_name)


    async def delete(self, patient_id):
        self.storage.delete(patient_id)


    async def get(self, user_id) -> PatientProfile:
        # Attempt fetching from persistent storage
        profile = await self.storage.fetch(user_id=user_id, collection_name=self.collection_name)

        if not profile:  # If no data, create a default value
            raise ModuleNotFoundError("The user does not have preferences saved")

        return self._from_dict_data_mapper(profile)


class UserAIConversationsRepository(Repository):

    def __init__(self):
        super().__init__()

    def _to_dict_data_mapper(self, conv_object: UserAIConversations) -> dict:
        """Converts a UserAIConversations object to a dictionary for storage."""
        return {
            "user_id": conv_object.user_id,
            "conversations": conv_object.conversations,
        }

    def _from_dict_data_mapper(self, conv_object: dict) -> UserAIConversations:
        """Converts a dictionary from the database into a UserPreferences object."""
        return UserAIConversations(
            user_id=conv_object.get("user_id"),
            conversations=conv_object.get("conversations", []),
        )

    async def save(self, user_ai_conversations: UserAIConversations):
        """ Persists conversation data to storage """
        # self.user_conversations[user_ai_conversations.user_id].conversations.extend(user_ai_conversations.conversations) # update cache with new data
        print("user convo", self.user_conversations)
        # data = self.__to_dict_data_mapper(user_ai_conversations)
        await self.storage.insert(data=user_ai_conversations, collection_name="conversations") # update persistent storage with new data

    async def delete(self, user_id):
        """ Deletes conversation data from storage """
        self.storage.delete(user_id)

    async def get(self, user_id) -> UserAIConversations:
        """
            Retrieve conversation data from storage
        """
        # data = self.user_conversations.get(user_id)  # Directly fetch from cache
        # if data:
        #     return data  # Ensure we always return a mapped object

        # Attempt fetching from persistent storage
        data = await self.storage.fetch(user_id=user_id, collection_name="conversations")
        data_object = self._from_dict_data_mapper(data if data else {"user_id": user_id})

        # if not data.get("conversations"):  # If no data, create a default value
        #     data = {"user_id": user_id, "conversations": []}

        # Map and store in cache
        # data_object = self.__from_dict_data_mapper(data)
        # self.user_conversations[f"{data_object.user_id}"] = data_object

        return data_object

