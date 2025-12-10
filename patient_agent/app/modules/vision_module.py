import aiofiles
from azure.ai.vision.imageanalysis.aio import ImageAnalysisClient
from azure.ai.vision.imageanalysis.models import VisualFeatures
from azure.core import exceptions
from azure.core.credentials import AzureKeyCredential

from pathlib import Path
import os


class AzureVisionService:
    AZURE_STORAGE_CONTAINER_NAME = "project-care-container"
    AZURE_OCR_RESOURCE_ENDPOINT = "https://care-ocr.cognitiveservices.azure.com/"
    AZURE_OCR_RESOURCE_KEY = os.getenv("AZURE_OCR_RESOURCE_KEY")

    def __init__(self):
        self.client = ImageAnalysisClient(
            endpoint=self.AZURE_OCR_RESOURCE_ENDPOINT,
            credential=AzureKeyCredential(self.AZURE_OCR_RESOURCE_KEY)
        )

    async def perform_ocr(self, downloaded_image_message_file_path: Path):
        try:
            async with aiofiles.open(downloaded_image_message_file_path, "rb") as f:
                image_data = await f.read()

            async with self.client:
                result = await self.client.analyze(
                    image_data=image_data,
                    visual_features=[VisualFeatures.READ],
                )

            if result.read is not None:
                extracted_text = ""  # Initialize an empty string to store the texts
                for line in result.read.blocks[0].lines:
                    extracted_text += line.text + " "  # Add the line's text followed by a space
                extracted_text = extracted_text.strip()  # Remove any trailing spaces
            else:
                raise ValueError()
        except FileNotFoundError as error:
            print(f"[ERROR] File not found: {error}")
            return False, ""
        except exceptions.HttpResponseError as error:
            print(f"[ERROR] Could not complete request: {error}")
            return False, ""
        except Exception as error:
            print(f"[ERROR] There was an error: {error}")
            return False, ""
        else:
            print("extracted text", extracted_text)
            return True, extracted_text