from abc import ABC, abstractmethod

class BaseDB(ABC):
    @abstractmethod
    def connect(self):
        pass

    @abstractmethod
    def fetch(self, **kwargs):
        pass

    @abstractmethod
    def insert(self, **kwargs):
        pass

    @abstractmethod
    def close(self):
        pass

    def __repr__(self):
        return "return nice representation"