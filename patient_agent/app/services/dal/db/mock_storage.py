from core.dal.db.base import BaseDB


class MockStorage(BaseDB):
    def connect(self):
        pass

    def fetch(self, **kwargs):
        print("[INFO] Data fetched")
        return None

    def insert(self, **kwargs):
        print("[INFO] Data inserted")
        return None

    def close(self):
        pass