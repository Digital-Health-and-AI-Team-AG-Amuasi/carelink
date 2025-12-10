from app.services.dal.db.azure_storage import AzureCloudStorage
# from app.services.dal.db.file_storage import FileStorage
# from app.services.dal.db.mock_storage import MockStorage
from app.services.dal.db.mongodb import MongoDB
from app.services.dal.schemas import UserPreference


# Factory class to handle data retrieval
class Database:

    storage_sources = {
        # "file": FileStorage,
        "azure_cloud": AzureCloudStorage,
        # "mock_storage": MockStorage,
        "mongo": MongoDB,
    }

    selected_database = None

    def __init__(self, storage_type="mock_storage"):
        """Returns the storage class"""
        if storage_type not in self.storage_sources.keys():
            print(f"[ERROR] Unknown database {storage_type}")
            raise ModuleNotFoundError(f"[ERROR] Unknown database {storage_type}")

        self.selected_database = self.storage_sources[storage_type]()


    @classmethod
    def get_storage(cls, storage_type: str|None = None):

        """Returns the storage class"""

        if storage_type in cls.storage_sources.keys():
            selected_storage = cls.storage_sources[storage_type]
        else:
            print(f"Storage source not specified or unknown: '{storage_type}'.")
            print(f"Using default storage 'mock storage'.")
            selected_storage = cls.storage_sources["mock_storage"]

        print(f"[INFO] Loading storage... {selected_storage}")

        return selected_storage


if __name__ == "__main__":
    import random
    import asyncio

    storage = Database.get_storage("mongo_db")()

    # generate random number between 1 and 10 (simulates 10 users)
    random_user_id = "PAT_1" #+ str(random.randint(1, 10))
    print("user_id", random_user_id)

    data = asyncio.run(storage.fetch(user_id=random_user_id, collection_name="preferences"))
    print("data before insert", data)

    # Insert data
    random_user_preference = {
        "user_id": random_user_id,
        "modality": random.choice(["text", "audio"]),
        "language": random.choice(["en", "tw"]),
        "agent": random.choice(["gdm-assistant", "cds-assistant"])
    }
    asyncio.run(storage.insert(collection_name="preferences",
                               data=UserPreference(**{"user_id": random_user_id, "preferences": random_user_preference})))

    data = asyncio.run(storage.fetch(user_id=random_user_id, collection_name="preferences"))
    print("data after insert", data)






