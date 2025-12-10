from fastapi import FastAPI
from app.api.v1 import user_routes
from app.api import health
from app.modules.utils_obs import run_startup_codes
from app.config.settings import settings

app = FastAPI(
    title=settings.PROJECT_NAME,
    version=settings.VERSION,
    lifespan=run_startup_codes
)

app.include_router(health.router, tags=["Health"])
app.include_router(user_routes.router, tags=["User"])
