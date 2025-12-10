from pathlib import Path

from dotenv import load_dotenv  # Import load_dotenv to load environment variables from a .env file
import os  # Import os module to interact with the operating system

# Load environment variables from a .env file
load_dotenv()

VERIFIABLE_TOKEN = os.getenv("VERIFIABLE_TOKEN")

BASE_DIR = (Path("C:/Users/sndvi/PycharmProjects/C.A.R.E-LLM/") if os.getenv("IS_RUNNING_LOCALLY") else Path("/home/")) / "local_storage/"

ERROR_FILE_DIR = BASE_DIR / "error_log.txt"

AZURE_STORAGE_CONTAINER_NAME = "project-care-container"

DEBUG_MODE = True

