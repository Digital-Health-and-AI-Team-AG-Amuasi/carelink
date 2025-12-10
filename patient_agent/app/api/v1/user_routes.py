
import logging
from fastapi import APIRouter, Query, HTTPException, Request
from fastapi.responses import PlainTextResponse, JSONResponse

from app.config.constants import VERIFIABLE_TOKEN
from app.services.dal.schemas import UpdatePIPRequest
from app.services import user_service

logger = logging.getLogger(__name__)

router = APIRouter()

@router.get("/webhook")
async def verify_webhook(
        hub_mode: str = Query(..., alias="hub.mode"),
        hub_verify_token: str = Query(..., alias="hub.verify_token"),
        hub_challenge: str = Query(..., alias="hub.challenge")
):
    if hub_mode and hub_verify_token:
        if hub_mode == "subscribe" and hub_verify_token == VERIFIABLE_TOKEN:
            return PlainTextResponse(content=hub_challenge)
        else:
            raise HTTPException(status_code=403, detail="Unauthorized")


@router.post("/update_patient_profile")
async def update_pip(pip_request: UpdatePIPRequest):
    try:
        result = await user_service.update_patient_management_plan(pip_request)
        return JSONResponse(status_code=200, content={"status": "success", "message": "Patient Profile successfully updated", "data": result})
    except Exception as error:
        return JSONResponse(status_code=500, content=str(error))


@router.post("/cds")
async def cds_endpoint(request: Request):
    try:
        body = await request.json()
        success, ai_response = await user_service.process_cds_request(body)
        
        # Depending on how process_cds_request returns response, adjust here:
        if hasattr(ai_response, 'model_dump'):
             response_data = ai_response.model_dump()
        else:
             response_data = ai_response

        return JSONResponse(status_code=200, content={"status": success, "message": response_data})

    except Exception as e:
        logger.exception(e)
        return JSONResponse(status_code=500,
                            content={"status": False, "message": f"An error occurred while processing the request. : {str(e)}"})


@router.post("/webhook")
async def webhook(request: Request):
    try:
        body = await request.json()
        headers = request.headers
        
        success, message, data = await user_service.process_webhook_message(body, headers)
        
        # Map tuple response to JSON structure
        # user_service.process_webhook_message returns: success, message_string, data (or None)
        
        return JSONResponse(status_code=200, content={"status": success, "message": message, "data": data})

    except Exception as e:
        logger.exception(e)
        return JSONResponse(status_code=500, content={"error": str(e)})


@router.post("/show_patient_preferences")
async def show_user_preference(request: Request):
    try:
        body: dict = await request.json()
        phone_number = body.get("phone")
        
        data = await user_service.get_patient_preferences(phone_number)
        
        return JSONResponse(status_code=200, content={"status": True, "message": "Success", "data": data})

    except Exception as e:
        return JSONResponse(status_code=200, content={"status": False, "message": f"Not successful: {e}"})
