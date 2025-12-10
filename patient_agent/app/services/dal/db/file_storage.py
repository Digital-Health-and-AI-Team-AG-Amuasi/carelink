"""

    This is intended to be an intermediary between databases and modules that need certain data.
    It uses file local_storage to keep data temporarily. This will be scraped later in the future because file operations
    are more expensive. Writing directly to a database should be faster. At that time in the future, we will use a memory
    cache to keep data.

    But at the moment, we are treating it as a separate database. It depends on the Azure Storage Class to get and save
    data on or from the cloud.

"""

import json
import os
from enum import Enum
import aiofiles

from core.dal.db.base import BaseDB
from constants import BASE_DIR


class FileStorage(BaseDB):

    class Dir(Enum):
        DOWNLOADED_MEDIA_MESSAGES_DIR = BASE_DIR / "messages/{}_messages/"  # Directory to store media local_storage
        USER_PREFERENCE_DIR = BASE_DIR / "user_preferences.txt"
        CONV_HISTORY_DIR = BASE_DIR / "user_data.txt"
        ERROR_FILE_DIR = BASE_DIR / "error_log.txt"

    def __init__(self):
        directory = os.path.dirname(self.Dir.CONV_HISTORY_DIR.value)
        os.makedirs(directory, exist_ok=True)
        directory = os.path.dirname(self.Dir.USER_PREFERENCE_DIR.value)
        os.makedirs(directory, exist_ok=True)

        self.dirs = {
            "conversations": self.Dir.CONV_HISTORY_DIR.value,
            "preferences": self.Dir.USER_PREFERENCE_DIR.value
        }

    def connect(self):
        """ Simply check if files are already created, otherwise create it."""
        pass

    async def fetch_all(self, data_type: str):
        try:
            print(f"[INFO] error fetching all data: {data_type}")
            async with aiofiles.open(self.dirs[data_type], "r") as file:
                content = await file.read()

                if not content.strip():  # Check if the content is empty or just whitespace
                    return {}  # Return an empty dictionary instead of raising an error

                print(f"[INFO] error fetching all data: {json.loads(content)}")
                return json.loads(content)  # Convert JSON string to dictionary
        except Exception as err:
            raise ValueError(f"[ERROR] error fetching all data: {err}")
            print(f"[ERROR] error fetching all data: {err}")
            return None

    async def fetch(self, **kwargs):
        """
        Reads data from file storage
        params:
            must be one of (conversations, preferences)
        """

        user_id: str | None = kwargs.get("user_id", None)
        data_type: str | None = kwargs.get("data_type", None)

        if user_id is None or data_type is None:
            raise ValueError("Missing required arguments 'user_id' or 'data_type'")

        all_data = await self.fetch_all(data_type=data_type)

        print(f"fetch {all_data} {all_data.get(user_id, {})}")

        return {"user_id": user_id, data_type: all_data.get(user_id, {})}

    async def insert(self, **kwargs):
        """
        Writes data to file storage
        params:
        """

        data_type: str | None = kwargs.get("data_type", None)
        data: dict | None = kwargs.get("data", None)

        if data is None or data_type is None:
            raise ValueError("Missing required arguments 'user_id' or 'data_type'")

        all_data = await self.fetch_all(data_type=data_type)

        if data_type != "preferences":
            # add memory to cache
            if all_data:
                all_data.setdefault(data["user_id"], []).extend(data[data_type])

        else:
            all_data = {data["user_id"]: data[data_type]}

        async with aiofiles.open(self.dirs[data_type], "w") as file:
            await file.write(json.dumps(all_data, indent=4))

    def close(self):
        pass  # No persistent connection needed

    def __repr__(self):
        return (f"FileStorage(conv_storage='{self.Dir.CONV_HISTORY_DIR}', "
                f"preferences='{self.Dir.USER_PREFERENCE_DIR}')")