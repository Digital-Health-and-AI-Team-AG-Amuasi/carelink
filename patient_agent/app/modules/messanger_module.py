import os
import logging
from dataclasses import dataclass
from pathlib import Path
from typing import Optional

import aiofiles
from fastapi import  HTTPException
import httpx
import mimetypes

from app.config.constants import ERROR_FILE_DIR
from app.services.dal.schemas import SendReminderRequest
from app.modules.schemas import MessageConfig
from app.utils.audio_factory import convert_audio_to_wav

logging.basicConfig(level=logging.DEBUG, filename=ERROR_FILE_DIR)


@dataclass
class MessageResponse:
    message_type: str
    recipient_business_id: str
    message_body: Optional[str] = None
    media_id: Optional[str] = None
    media_path: Optional[Path] = None


    def __post_init__(self):

        if(self.message_type == "audio") and bool(self.message_body):
            raise ValueError("Message body cannot be set when sending a media file.")

        if (self.message_type == "audio") and not bool(self.media_path):
            raise ValueError("Media path not specified.")


@dataclass
class Message:

    def __init__(self,
                 business_phone_number_id,
                 messenger_phone_number,
                 message_type,
                 message_id,
                 message,
                 config: MessageConfig = MessageConfig()):

        self.__message_config = config
        # Extract relevant information from the message payload

        self.business_phone_number_id = business_phone_number_id
        self.messenger_phone_number = messenger_phone_number  # Get the sender's phone number
        self.message_type = message_type
        self.message_id = message_id  # Get the message ID
        self.__message = message

    @classmethod
    def from_ehr_request(cls, request_payload: SendReminderRequest):
        recipient_phone_number = request_payload.recipient_phone_number
        message_type: str = "text"
        message_id = None
        __message = request_payload.reminder
        business_phone_number = None

        return cls(
            messenger_phone_number=recipient_phone_number,
            message_type=message_type,
            message_id=message_id,
            business_phone_number_id=business_phone_number,
            message=__message
        )

    @classmethod
    def from_whatsapp(cls, request_body: dict):
        __message_payload = request_body["entry"][0]["changes"][0]["value"][
            "messages"][0]
        # Extract relevant information from the message payload
        business_phone_number_id = request_body["entry"][0]["changes"][0]["value"]["metadata"]["phone_number_id"]

        messenger_phone_number = __message_payload["from"]  # Get the sender's phone number
        message_type = __message_payload["type"]
        message_id = __message_payload["id"]  # Get the message ID
        __message = __message_payload[message_type]

        return cls(
            business_phone_number_id,
            messenger_phone_number,
            message_type,
            message_id,
            __message,
        )

    # class Message:
    #
    #     def __init__(self, request_body: dict, config: MessageConfig = MessageConfig()):
    #
    #         self.__message_config = config
    #         self.__message_payload = request_body["entry"][0]["changes"][0]["value"][
    #             "messages"][0]
    #         # Extract relevant information from the message payload
    #         self.business_phone_number_id = request_body["entry"][0]["changes"][0]["value"]["metadata"][
    #             "phone_number_id"]
    #         self.messenger_phone_number = self.__message_payload["from"]  # Get the sender's phone number
    #         self.message_type = self.__message_payload["type"]
    #         self.message_id = self.__message_payload["id"]  # Get the message ID
    #         self.__message = self.__message_payload[self.message_type]
    #
    #     async def get_message(self):
    #         if self.message_type == "text":
    #             return self.__message["body"]
    #         else:
    #             download_success, path_to_downloaded_message = await self.__download_media_message()
    #             return path_to_downloaded_message
    #
    #     async def __download_media_message(self) -> tuple[bool, Path]:
    #         """
    #         Download the audio message asynchronously from WhatsApp API using media_id.
    #         """
    #
    #         media_id = self.__message["id"]
    #         mime_type = self.__message["mime_type"].split(";")[0].strip()
    #
    #         media_messages_dir = str(self.__message_config.DOWNLOADED_MEDIA_MESSAGES_DIR).format(self.message_type)
    #         os.makedirs(media_messages_dir, exist_ok=True)  # Create a folder to store downloaded media local_storage
    #
    #         try:
    #             # Get the URL for the media file
    #             media_url = str(self.__message_config.WHATSAPP_MEDIA_URL).format(media_id=media_id)
    #             async with httpx.AsyncClient() as client:
    #                 response = await client.get(media_url, headers=self.__message_config.WHATSAPP_HEADERS)
    #
    #             response.raise_for_status()
    #
    #             media_url = response.json().get('url')
    #             if not media_url:
    #                 raise HTTPException(status_code=404, detail=f"{self.message_type.capitalize()} URL not found")
    #
    #             # Download the media file
    #             async with httpx.AsyncClient() as client:
    #                 get_media_response = await client.get(media_url, headers=self.__message_config.WHATSAPP_HEADERS)
    #
    #             get_media_response.raise_for_status()
    #
    #             # Write data to file
    #             path_to_downloaded_media_message = Path(
    #                 media_messages_dir) / f"{media_id}{mimetypes.guess_extension(mime_type)}"
    #             async with aiofiles.open(path_to_downloaded_media_message, 'wb') as f:
    #                 await f.write(get_media_response.content)
    #
    #         except httpx.HTTPStatusError as error:
    #             message = f"[ERROR] HTTP error occurred: {error.response.status_code} - {error.response.text}"
    #             logging.error(message)
    #             print(message)
    #             raise
    #         except httpx.RequestError as error:
    #             message = f"[ERROR] Request error occurred: {error}"
    #             logging.error(message)
    #             print(message)
    #             raise
    #         except Exception as error:
    #             message = f"[ERROR] Error downloading media: {error}"
    #             logging.error(message)
    #             print(message)
    #             raise
    #         else:
    #             print("[INFO] Media download successful!")
    #             return True, path_to_downloaded_media_message
    #
    #         # return False, Path()

    async def get_message(self):
        if self.message_type == "text":
            return self.__message["body"]
        else:
            download_success, path_to_downloaded_message = await self.__download_media_message()
            return path_to_downloaded_message

    async def __download_media_message(self) -> tuple[bool, Path]:
        """
        Download the audio message asynchronously from WhatsApp API using media_id.
        """

        media_id = self.__message["id"]
        mime_type = self.__message["mime_type"].split(";")[0].strip()

        media_messages_dir = str(self.__message_config.DOWNLOADED_MEDIA_MESSAGES_DIR).format(self.message_type)
        os.makedirs(media_messages_dir, exist_ok=True)  # Create a folder to store downloaded media local_storage

        try:
            # Get the URL for the media file
            media_url = str(self.__message_config.WHATSAPP_MEDIA_URL).format(media_id=media_id)
            async with httpx.AsyncClient() as client:
                response = await client.get(media_url, headers=self.__message_config.WHATSAPP_HEADERS)

            response.raise_for_status()

            media_url = response.json().get('url')
            if not media_url:
                raise HTTPException(status_code=404, detail=f"{self.message_type.capitalize()} URL not found")

            # Download the media file
            async with httpx.AsyncClient() as client:
                get_media_response = await client.get(media_url, headers=self.__message_config.WHATSAPP_HEADERS)

            get_media_response.raise_for_status()

            # Write data to file
            path_to_downloaded_media_message =  Path(media_messages_dir) / f"{media_id}{mimetypes.guess_extension(mime_type)}"
            async with aiofiles.open(path_to_downloaded_media_message, 'wb') as f:
                await f.write(get_media_response.content)

        except httpx.HTTPStatusError as error:
            message = f"[ERROR] HTTP error occurred: {error.response.status_code} - {error.response.text}"
            logging.error(message)
            print(message)
            raise
        except httpx.RequestError as error:
            message = f"[ERROR] Request error occurred: {error}"
            logging.error(message)
            print(message)
            raise
        except Exception as error:
            message = f"[ERROR] Error downloading media: {error}"
            logging.error(message)
            print(message)
            raise
        else:
            print("[INFO] Media download successful!")
            return True, path_to_downloaded_media_message

        # return False, Path()

class Messanger:
    __message_config: MessageConfig = MessageConfig()
    __message: Message

    async def send_response(self,
                            message_response: MessageResponse,
                            user_input_message_object: Message,
                            mark_seen: bool=True):
        self.__message = user_input_message_object
        try:

            if mark_seen:
                await self.__mark_as_read()

            print("message_response", message_response)
            await self.__reply_to_message(message_response=message_response)

            return True
        except HTTPException as error:
            print(f"[ERROR] There was an error: {error}")
            return False

    async def __mark_as_read(self):
        # Mark the message as read by sending another POST request
        async with httpx.AsyncClient() as client:
            response = await client.post(
                self.__message_config.WHATSAPP_MESSAGE_URL.format(self.__message.business_phone_number_id),
                headers=self.__message_config.WHATSAPP_HEADERS,
                json={
                    "messaging_product": "whatsapp",
                    "status": "read",  # Set the status to 'read'
                    "message_id": self.__message.message_id  # Specify the message ID to mark as read
                }
            )
            response.raise_for_status()  # Raise an error if the response status is not successful

    async def __reply_to_message(self, message_response: MessageResponse):

        if message_response.message_type not in ["image", "audio", "text"]:
            raise ValueError(f"Message type {message_response.message_type} is not supported. Must be one of ['image', 'audio']")

        # Send response message
        async with httpx.AsyncClient() as client:
            response = await client.post(
                self.__message_config.WHATSAPP_MESSAGE_URL.format(message_response.recipient_business_id),  # URL to send a message
                headers=self.__message_config.WHATSAPP_HEADERS,  # Set the Authorization header with the token
                json={
                    "messaging_product": "whatsapp",  # Specify the messaging product
                    "to": self.__message.messenger_phone_number,  # Specify the recipient's phone number
                    "text":  {"body": f"{message_response.message_body}"},
                    # "context": {"message_id": self.__message.message_id}  # Include the original message ID in the context
                }
            )

            response.raise_for_status()  # Raise an error if the response status is not successful

    async def __upload_media_asset(self, path_to_media_asset: Path, user_input_message_object: Message):
        """
        Download the audio message asynchronously from WhatsApp API using media_id.
        """

        media_messages_dir = str(self.__message_config.DOWNLOADED_MEDIA_MESSAGES_DIR).format(user_input_message_object.message_type)
        os.makedirs(media_messages_dir, exist_ok=True)  # Create a folder to store downloaded media local_storage
        send_media_url = self.__message_config.WHATSAPP_UPLOAD_MEDIA_URL.format(user_phone_number_id=user_input_message_object.business_phone_number_id)

        try:

            success, path_to_converted_audio_message = convert_audio_to_wav(
                path_to_downloaded_audio_message=Path(path_to_media_asset))

            if not success:
                raise ValueError(f"Could not convert audio file into appropriate format: {path_to_converted_audio_message}")

            async with aiofiles.open(path_to_converted_audio_message, "rb") as audio_file:
                file_content = await audio_file.read()

            payload = {
                "file": (str(path_to_media_asset), file_content, "audio/ogg"),
                "type": ".ogg",
                "messaging_product": (None, "whatsapp")
            }

            # Upload the media file
            async with httpx.AsyncClient() as client:
                get_media_response = await client.post(send_media_url, headers=self.__message_config.WHATSAPP_HEADERS, files=payload, timeout=None)

            get_media_response.raise_for_status()

            print("[INFO] Media upload successful!")
            return True, get_media_response.json()["id"]

        except httpx.HTTPStatusError as error:
            user_input_message_object = f"[ERROR] HTTP error occurred: {error.response.status_code} - {error.response.text}"
            logging.error(user_input_message_object)
            print(user_input_message_object)
        except httpx.RequestError as error:
            user_input_message_object = f"[ERROR] Request error occurred: {error}"
            logging.error(user_input_message_object)
            print(user_input_message_object)
        except Exception as error:
            user_input_message_object = f"[ERROR] Error uploading media: {error}"
            logging.error(user_input_message_object)
            print(user_input_message_object)

        return False, Path()



