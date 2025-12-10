import os

import httpx

from app.modules.schemas import MedicalFlag


async def raise_flag_for_patient(medical_flag: MedicalFlag):
    print(f"[INFO] Reporting flag to remote EHR {medical_flag}")

    ehr_backend_endpoint = os.getenv("EHR_BACKEND_URL") + "/report_flag"
    ehr_backend_endpoint_api_key = os.getenv("EHR_API_KEY")

    headers = {
        "Api-Key": f"{ehr_backend_endpoint_api_key}",
        "Accept": "application/json",
        "Content-Type": "application/json"
    }
    # flag_details = medical_flag
    async with httpx.AsyncClient() as client:
        # response = await client.post(ehr_backend_endpoint,
        #                              headers=headers,
        #                              json=medical_flag.model_dump())

        try:
            response = await client.post(
                ehr_backend_endpoint,
                headers=headers,
                json=medical_flag.model_dump()
            )
            response.raise_for_status()  # Will raise for 4xx/5xx

        except httpx.HTTPStatusError as exc:
            # Print status + raw response body
            print(f"[ERROR] Request failed with status {exc.response.status_code}")
            print(f"[ERROR] Response body: {exc.response.text}")
            raise  # rethrow so calling code can handle it

        except Exception as exc:
            print(f"[ERROR] Unexpected exception: {exc}")
            raise

    # response.raise_for_status()

    print(f"[INFO] Report Completed Successfully")

    return True


if __name__ == "__main__":
    import asyncio

    mock_medical_flag = MedicalFlag(
        patient_phone="0562857121",
        reason="Jesus is king"
    )

    asyncio.run(raise_flag_for_patient(mock_medical_flag))

