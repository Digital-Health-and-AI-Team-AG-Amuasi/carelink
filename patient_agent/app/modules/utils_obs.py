# Initialize the ChatGroq model with a specific model identifier
import logging
import os
import subprocess
from contextlib import asynccontextmanager

from fastapi import FastAPI

from app.services.dal.data_repositories import Repository
# from core.dal.db.file_storage import FileStorage
from app.modules.ai import ClinicalAssessmentRAG
from app.modules.messanger_module import Messanger
from app.modules.vision_module import AzureVisionService

repository_handler = Repository = Repository()
messanger: Messanger = Messanger()
ocr_handler = AzureVisionService()
rag_handler = None

async def initialization():
    print("[INFO] Running startup")
    azure_cloud = repository_handler.set_storage_strategy(new_storage_strategy="azure_cloud") # get the reference to the class
    azure_cloud_storage = azure_cloud() # instantiate the class

    await azure_cloud_storage.fetch()
    await azure_cloud_storage.close()

async def de_initialization():
    print("[INFO] Pushing files to azure storage")
    azure_cloud = repository_handler.set_storage_strategy(new_storage_strategy="azure_cloud")  # get the reference to the class
    azure_cloud_storage = azure_cloud()  # instantiate the class

    await azure_cloud_storage.insert(repository_handler)
    await azure_cloud_storage.close()

@asynccontextmanager
async def run_startup_codes(app: FastAPI): #-> AsyncIterator[State]:
    global rag_handler

    try:

        await initialization()

        rag_handler = ClinicalAssessmentRAG()

        if not os.getenv("IS_RUNNING_LOCALLY"):
            print("[INFO] Changing ffmpeg file permissions")
            chmod_command = ["chmod", "u+x", "/home/local_storage/ffmpeg"]
            success = run_subprocess(chmod_command)

            if not success:
                raise ProgramError()
            else:
                print("[INFO] Permissions on ffmpeg changed successfully")
        else:
            print("[INFO] Local environment detected, skipping ffmpeg")
        yield

    except ProgramError as err:
        print("Custom detected error triggered")
        yield

    except Exception as err: # TODO: Handle more specific exceptions
        print(f"An error occurred during program startup: {err}")
        logging.exception("An error occurred during program startup")
        yield

    finally:
        await de_initialization()
        # Shutdown logic goes here
        logging.info("Shutting down and cleaning up resources")
        print("Shutting down...")
        print("Upload error log file")


def run_subprocess(command: list, capture_output=False, capture_text=False):  # TODO: Use asyncio here
    try:
        run_subproc = subprocess.run(command, capture_output=capture_output, text=capture_text, check=True)
        run_subproc.check_returncode()
    except subprocess.CalledProcessError as error:
        print("Command failed with error code:", error.returncode)
        print("Called Process Error output: ", error.stderr)
        return False
    else:
        print("Successfully run command")
        return True


def list_files_and_dirs(path):
    for root, dirs, files in os.walk(path):
        print(f"\n📁 Directory: {root}")
        for d in dirs:
            print(f"  📂 Subdirectory: {d}")
        for f in files:
            print(f"  📄 File: {f}")


class ProgramError(Exception):
    def __init__(self, message="There was a program error."):
        super().__init__(message)
