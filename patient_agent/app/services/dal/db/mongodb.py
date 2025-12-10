from dotenv import load_dotenv
from pymongo import MongoClient
import os

# from core.dal.repositories.schemas import PatientProfile
# from core.dal.schemas import UserPreference, UserAIConversations

load_dotenv()

class MongoDB:
    mongo_db_uri = os.getenv("COSMOS_CONNECTION_STRING")
    db_name = os.getenv("MONGO_DB_DATABASE_NAME")

    def __init__(self):
        self.client = MongoClient(self.mongo_db_uri)

        # if os.getenv("IS_RUNNING_LOCALLY"):
        #     for prop, value in vars(self.client.options).items():
        #         print("Property: {}: Value: {} ".format(prop, value))

        self.db = self.client[self.db_name]


    async def fetch(self, collection_name, user_id):
        collection = self.db[collection_name]
        data = collection.find_one({"user_id": user_id})

        return data

    async def insert(self, collection_name, data):
        """
        Args:
            collection_name: or data_type refers to the collection name
            data: the data to insert into the db

        Returns:

        """
        try:
            collection = self.db[collection_name]

            collection.update_one(
                {
                    "user_id": data.user_id
                },
                {
                    "$set": data.model_dump()
                },
                upsert=True
            )
        except Exception as e:
            print(f"[ERROR] there was an error {e}")
        else:
            print("Insert successful")


# if __name__ == "__main__":
# import asyncio

#
#     storage = MongoDB()
#
#     # asyncio.run(storage.insert(collection_name="preferences", data=UserPreference(user_id="1", language="tw", modality="text")))
#
#     asyncio.run(storage.fetch(collection_name="preferences", user_id="1"))