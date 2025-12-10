from pydantic_settings import BaseSettings

class Settings(BaseSettings):
    PROJECT_NAME: str = "C.A.R.E-LLM"
    VERSION: str = "1.0.0"
    API_PREFIX: str = "/api/v1"
    
    AZURE_OCR_RESOURCE_KEY: str | None = None
    IS_RUNNING_LOCALLY: bool = False

    class Config:
        env_file = ".env"
        extra = "ignore" 

settings = Settings()
