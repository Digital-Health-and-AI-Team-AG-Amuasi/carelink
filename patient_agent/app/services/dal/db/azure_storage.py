from __future__ import annotations

import os
import logging

import aiofiles
from azure.storage.blob.aio import BlobServiceClient, BlobClient

from app.config.constants import BASE_DIR
from app.services.dal.db.base import BaseDB

# from core.dal.data_repositories import Repository


logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)


class AzureCloudStorage(BaseDB):
    AZURE_STORAGE_KEY = os.getenv("AzureWebJobsStorage")

    AZURE_STORAGE_CONTAINER_NAME = "project-care-container"

    def __init__(self):
        self.blob_service_client: BlobServiceClient | None = None
        # self.conv_dir = conv_dir
        # self.pref_dir = pref_dir

    def __repr__(self):
        return "Storage: AzureStorage()"

    async def connect(self):
        try:
            self.blob_service_client = BlobServiceClient.from_connection_string(self.AZURE_STORAGE_KEY)
            print("[INFO] Connection successfully established for Azure Cloud Storage")
        except Exception as err:
            print(f"[INFO] Could not set up connection with Azure Cloud Storage: '{err}'")

    def __get_data_blob(self, data_dir) -> BlobClient:
        return self.blob_service_client.get_blob_client(
            container=self.AZURE_STORAGE_CONTAINER_NAME,
            blob=data_dir)

    async def fetch(self):
        """
        Fetches blob from Azure CLoud
        """

        try:
            if self.blob_service_client is None:
                await self.connect()

            # user_preferences_blob_client = self.__get_data_blob(data_dir="user_preferences.txt")
            # user_data_blob_client = self.__get_data_blob(data_dir="user_data.txt")
            mdg_doc_blob_client = self.__get_data_blob(data_dir="gmtg.pdf")

            # download_user_data_stream = await user_data_blob_client.download_blob()
            # download_user_preference_stream = await user_preferences_blob_client.download_blob()
            download_mdg_doc_stream = await mdg_doc_blob_client.download_blob()

            # async with aiofiles.open(self.conv_dir, "w") as file:
            #     content = await download_user_data_stream.readall()
            #     await file.write(content.decode("utf-8"))  # Decode to

            # logger.warning(f"[INFO] {self.conv_dir} downloaded and loaded successfully!!!")
            # print(f"[INFO] {self.conv_dir} downloaded and loaded successfully!!!")

            # async with aiofiles.open(self.pref_dir, "w") as file:
            #     content = await download_user_preference_stream.readall()
            #     await file.write(content.decode("utf-8"))

            # logger.warning(f"[INFO] {self.pref_dir} downloaded and loaded successfully!!!")
            # print(f"[INFO] {self.pref_dir} downloaded and loaded successfully!!!")

            if not os.getenv("IS_RUNNING_LOCALLY"): # fetch ffmpeg
                os.makedirs(BASE_DIR, exist_ok=True)

                ffmpeg_blob_client = self.__get_data_blob(data_dir="ffmpeg")
                download_ffmpeg_stream = await ffmpeg_blob_client.download_blob()
                async with aiofiles.open("/home/local_storage/ffmpeg", "wb") as file:
                    content = await download_ffmpeg_stream.readall()
                    await file.write(content)

                async with aiofiles.open(BASE_DIR / "gmtg.pdf", "wb") as file:
                    content = await download_mdg_doc_stream.readall()
                    await file.write(content)


                logger.info("[INFO] gmtg.pdf downloaded and loaded successfully!!!")
                print("[INFO] gmtg.pdf downloaded and loaded successfully!!!")
            else:
                print("skipping ffmpeg/doc download")

            print("[INFO] Data downloaded and loaded successfully!!!")


        except Exception as err:
            print(f"Couldn't load data {err}")
            # logger.exception(f"Couldn't load data {err}")
            raise

    async def insert(self, repository_handler):
        """
            push text files to azure cloud storage
        """

        return


        # if self.blob_service_client is None:
        #     await self.connect()
        #
        # files = {"conversations": self.conv_dir, "preferences": self.pref_dir}
        #
        # try:
        #     for data_type, file_dir in files.items():
        #         print("Uploading to Azure Storage as blob: " + str(file_dir.name))
        #
        #         async with repository_handler as repository:
        #             file_storage = repository.set_storage_strategy(new_storage_strategy="file")()
        #             data = await file_storage.fetch_all(data_type)
        #
        #         data_blob_client = self.__get_data_blob(file_dir.name)
        #
        #         await data_blob_client.upload_blob(data=json.dumps(data), overwrite=True)
        #         print("Done uploading " + str(file_dir.name))
        # except Exception as err:
        #     print(f"ERROR: {err}")

    async def close(self):
        try:
            await self.blob_service_client.close()
            print("[INFO] Azure storage connection successfully closed")
        except Exception as err:
            print(f"[ERROR] Could not close Azure storage connection: {err}")

# if __name__ == "__main__":
#     # from core.dal.data_repositories import Repository
#     # from core.dal.db.file_storage import FileStorage
#     # import asyncio
#
#     test_repository_handler = Repository()
#     print("[INFO] Pushing files to azure storage")
#
#     azure_cloud = test_repository_handler.set_storage_strategy(
#         new_storage_strategy="azure_cloud")  # get the reference to the class
#     print("storage choice", azure_cloud)
#     azure_cloud_storage: AzureCloudStorage = azure_cloud(conv_dir=FileStorage.Dir.CONV_HISTORY_DIR.value,
#                                       pref_dir=FileStorage.Dir.USER_PREFERENCE_DIR.value)  # initialise the class
#
#     # # test upload functionality
#     # asyncio.run(azure_cloud_storage.insert(test_repository_handler))
#     # asyncio.run(azure_cloud_storage.close())
#
#     # test download functionality
#     asyncio.run(azure_cloud_storage.fetch())
#     asyncio.run(azure_cloud_storage.close())