import json
import os
from abc import ABC, abstractmethod
from datetime import datetime
from pathlib import Path
from typing import Optional

import aiofiles
import httpx
import speech_recognition

from app.utils.audio_factory import convert_audio_to_wav
from app.modules.schemas import MessageConfig


class MultiLingualSupportStrategy(ABC):

    @abstractmethod
    def text_to_speech(self, **kwargs):
        pass

    @abstractmethod
    def speech_to_text(self, **kwargs):
        pass

    @abstractmethod
    def translate(self, **kwargs):
        pass


class LocalDialectSupportStrategy(MultiLingualSupportStrategy):
    """
       Provides support for speech translation, text to speech and speech to text functionality in multiple local languages
       using the ghana_nlp library. Supported languages include Twi.
    """

    ghana_nlp_asr_available_languages = {"Twi": "tw", "Ga": "gaa", "Dagbani": "dag", "Ewe": "ee", "English": "en"}
    GHANA_NLP_ACCESS_TOKEN = os.getenv("GHANANLP_ACCESS_TOKEN")
    hdr = {
        'Cache-Control': 'no-cache',
        'Ocp-Apim-Subscription-Key': GHANA_NLP_ACCESS_TOKEN,
    }


    async def speech_to_text(self, **kwargs):

        file_path = kwargs.get("file_path", None)
        language = kwargs.get("language", None)

        if not isinstance(file_path, Path):
            raise ValueError(f"{file_path}")

        is_local_dialect = isinstance(self, LocalDialectSupportStrategy)
        if not file_path or (not language and is_local_dialect):
            raise ValueError("Missing required argument: 'file_path', 'language'")

        url = "https://translation-api.ghananlp.org/asr/v2/transcribe?language=tw"

        self.hdr['Content-Type'] = 'audio/mpeg'

        try:
            async with httpx.AsyncClient() as client:
                # Read the audio file in binary mode
                async with aiofiles.open(file_path, 'rb') as f:
                    data = await f.read()

                response = await client.post(url, headers=self.hdr, content=data, timeout=None)

                # print(f"Response Status Code: {response.status_code}")
                # print(f"Response Content STT: {response.content.decode()}")
                # print(f"Response Header STT: {response}")

                response.raise_for_status()

                # raise ValueError(str(response.json()["text"]))

                return True, str(response.json()["text"])

        except Exception as e:
            print(f"An error occurred while running ASR operation: {e}")
            return False, e


    async def text_to_speech(self, text, language: str = ghana_nlp_asr_available_languages["Twi"]):

        self.hdr['Content-Type'] = 'application/json'

        payload = {
            "text": text,
            "language": language,
            "speaker_id": "twi_speaker_4"
        }

        try:
            async with httpx.AsyncClient() as client:
                url = "https://translation-api.ghananlp.org/tts/v1/synthesize"

                data = json.dumps(payload)

                current_time = datetime.now().strftime("%Y%m%d_%H%M%S")
                file_name = f"gnlp_audio_{current_time}.wav"

                # Make the POST request
                response = await client.post(url, headers=self.hdr, content=bytes(data.encode("utf-8")), timeout=None)

                print("tts response", response.status_code)

                path_to_downloaded_message_tts = os.path.join(str(MessageConfig.DOWNLOADED_MEDIA_MESSAGES_DIR).format("audio"),
                                                                file_name)

                # Print response details
                print(f"Response Status Code: {response.status_code}")
                async with aiofiles.open(path_to_downloaded_message_tts, 'wb') as f:
                    await f.write(response.content)

                return True, path_to_downloaded_message_tts

        except Exception as e:
            print(f"An error occurred with tts: {e}")
            return False, e


    async def translate(self, **kwargs):
        """
        Translates text using the GhanaNLP API.

        Args:
            **kwargs
                text (str): Text to translate.
                source_language (str): Source language code.
                target_language (str): Target language code.

        Returns:
            tuple: isSuccess, message.
        """


        text = kwargs.get("text", None)
        source_language = kwargs.get("source_language", None)
        target_language = kwargs.get("target_language", None)

        if os.getenv("IS_RUNNING_LOCALLY") and source_language == "tw":
            return True, "How are you?"

        if not text or not source_language or not target_language:
            raise ValueError("'text', 'source_language', 'target_language' value fields required")

        url = "https://translation-api.ghananlp.org/v1/translate"

        translate_hdr = self.hdr
        translate_hdr['Content-Type'] = 'application/json'

        payload = {
            "in": text,
            "lang": f"{source_language}-{target_language}"
        }

        # print("Translating text", payload)
        print("hdr", translate_hdr)

        try:
            async with httpx.AsyncClient() as client:
                data = json.dumps(payload)
                # print("Translating text", data)
                response = await client.post(url, headers=translate_hdr, content=bytes(data.encode("utf-8")), timeout=None)

                response.raise_for_status()

                # Print response details
                print(f"Response Content after translating: {response.text}")

                return True, response.text

        except Exception as e:
            print(f"An error occurred while translating text: {e}")
            return False, e


class OfficialDialectSupportStrategy(MultiLingualSupportStrategy):
    """
        Transcribe audio file to text using SpeechRecognition.
    """
    def __init__(self):
        self.sr = speech_recognition

    async def speech_to_text(self, **kwargs):

        file_path: Optional[Path] = kwargs.get("file_path", None)

        if file_path is None:
            raise ValueError("Missing required argument: 'file_path'")

        try:
            # service only supports wav local_storage.
            success, path_to_converted_audio_message = convert_audio_to_wav(path_to_downloaded_audio_message=file_path)

            if not success:
                return success, path_to_converted_audio_message

            recognizer = self.sr.Recognizer()
            with self.sr.AudioFile(path_to_converted_audio_message) as source:
                audio_data = recognizer.record(source)
                transcribed_response = recognizer.recognize_google(audio_data)
                return True, transcribed_response
        except self.sr.UnknownValueError:
            return False, "Audio not clear enough to transcribe"
        except self.sr.RequestError:
            return False, "Error with the transcription service"
        except Exception as e:
            return False, e

    async def text_to_speech(self, text):
        print("Feature not implemented yet..")
        return False, NotImplemented

    async def translate(self, **kwargs):
        text = kwargs.get("text", None)

        if text is None:
            raise ValueError("No text to translate")
        return True, text


