# PMPMP - Patient Medical Profile and Management Plan
from typing import Optional

from core.dal.repositories.abstracts import IPMPMPRepository
from core.dal.repositories.schemas import PatientProfile


class PMPMPRepository(IPMPMPRepository):

    def __init__(self):
        super().__init__()
        self._pmpmp_collection = self.db["pmpmp_collection"]

    async def save_pmpmp(self, entry: PatientProfile):
        try:
            self._pmpmp_collection.update_one(
                {"patient_id": entry.user_id},  # filter by unique field
                {"$set": entry.model_dump()},  # update the rest of the fields
                upsert=True  # insert if not found
            )

            print(f"[INFO] PMPMP for {entry.patient_name} saved/updated successful")
        except Exception as e:
            print(f"[INFO] PMPMP for {entry.patient_name} could not be saved/updated: {e}")
            raise


    def get_pmpmp(self, patient_id: str) -> Optional[PatientProfile]:
        """
        Args:
            patient_id: PAT_233<patient_phone_number>

        Returns:
             PMPMPDTO
        """

        try:
            data = self._pmpmp_collection.find_one({"patient_id": patient_id})
            if data:
                data.pop("_id", None)
                return PatientProfile(**data)

            test = {
                "patient_id" : "PAT_233537287636",
                "patient_name" : "Princess Stokes",
                "patient_phone_number" : "0537287636",
                "latest_management_plans" : "Eat supper",
                "business_phone_number" : "537944602725224",
                "patient_preference" : {
                    "language" : "English",
                    "modality" : "text",
                    "agent" : "gdm-assistant"
                }
            }
            return PatientProfile(**test)
        except Exception as error:
            print(f"[INFO] Could not retrieve PMPMP for patient: {error}")
            raise