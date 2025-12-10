import asyncio

from dotenv import load_dotenv
from langchain_openai import OpenAIEmbeddings, ChatOpenAI
from langchain.prompts import PromptTemplate
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_community.document_loaders import PyPDFLoader
from langchain_chroma import Chroma
import os
import logging


from app.config.constants import BASE_DIR
from app.services.dal.schemas import ClinicalAssessmentResponseStructure, GDMAssistantResponse
from app.modules.ai import ai_prompts

load_dotenv()

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class ClinicalAssessmentRAG:
    doc_path = BASE_DIR / "gmtg.pdf"

    patient_data = """Patient Information (patient review during antenatal visit): {patient_review_information}"""
    __persist_dir = str(BASE_DIR / "chroma_db")

    def __init__(self, chunk_size=1000, chunk_overlap=200, model_name="gpt-4o-2024-08-06"):
        self.__chunk_size = chunk_size
        self.__chunk_overlap = chunk_overlap
        self.__model_name = model_name

        self.__embeddings = OpenAIEmbeddings()

        self.__llm = ChatOpenAI(model=self.__model_name, temperature=0)
        self.__llm_for_clinical_assessment_rag = self.__llm.with_structured_output(ClinicalAssessmentResponseStructure)
        self.__llm_for_gdm_assistance = self.__llm.with_structured_output(GDMAssistantResponse)
        self.__prompt = PromptTemplate.from_template(ai_prompts.management_plan_assistant)

        try:
            if os.path.exists(self.__persist_dir):
                self.vector_store = Chroma(persist_directory=self.__persist_dir, embedding_function=self.__embeddings)
                logger.info(f"Loaded vector store from '{self.__persist_dir}'.")
                print("vector ", self.vector_store)
            else:
                self.vector_store = self._build_vector_store()
                logger.info(f"Created and saved vector store at '{self.__persist_dir}'.")
        except Exception as e:
            print("error", e)
            logger.exception("Failed to initialize vector store.")
            raise RuntimeError(f"Could not initialize vector store. {e}") from e

    def _build_vector_store(self):
        if not os.path.exists(self.doc_path):
            logger.exception(f"No file found: {self.doc_path}")
            raise FileNotFoundError(f"Document not found at path: {self.doc_path}")

        loader = PyPDFLoader(self.doc_path)
        try:
            docs = loader.load()
            if not docs:
                raise ValueError("No content loaded from PDF.")
        except Exception as e:
            logger.exception("Failed to load documents from PDF.")
            raise

        splitter = RecursiveCharacterTextSplitter(
            chunk_size=self.__chunk_size,
            chunk_overlap=self.__chunk_overlap,
            add_start_index=True
        )
        splits = splitter.split_documents(docs)
        if not splits:
            raise ValueError("Splitting documents produced no chunks.")

        return Chroma.from_documents(splits, self.__embeddings, persist_directory=self.__persist_dir)

    def query_for_patient(self, query) -> GDMAssistantResponse:
        print("Querryyyy", query)
        return self.__llm_for_gdm_assistance.invoke(query)

    def query(self, patient_data: str):
        if not patient_data:
            raise ValueError("Question cannot be empty.")

        retriever = self.vector_store.as_retriever(search_kwargs={"k": 4})
        try:
            retrieval_query = "gestational diabetes screening diagnosis management diet exercise insulin metformin monitoring complications postpartum follow-up prevention"
            docs = retriever.invoke(retrieval_query)
            if not docs:
                logger.warning("No relevant documents retrieved.")
                return None, []
        except Exception as e:
            logger.exception("Failed during document retrieval.")
            raise

        context = "\n\n".join(doc.page_content for doc in docs)
        prompt_text = self.__prompt.format_prompt(context=context, patient_review_information=patient_data).to_string()

        print(prompt_text)
        try:
            parsed_response: ClinicalAssessmentResponseStructure = self.__llm_for_clinical_assessment_rag.invoke(prompt_text)
        except Exception as e:
            logger.exception("LLM failed to parse structured output.")
            raise RuntimeError("LLM failed to generate structured response.") from e

        citations = [doc.metadata for doc in docs]
        return parsed_response, citations


if __name__ == "__main__":
    assistant = ClinicalAssessmentRAG()
    question = "How do I handle heart attack in pregnant women?"

    # try:
    parsed, sources = assistant.query(question)

    if parsed:
        print(parsed.data)
        # print("Impression:", parsed.impression)
        # print("Citations:", parsed.citations)
        # print("Plan:", parsed.plan)
        # print("Sources:", sources)
    else:
        print("No answer was generated for the query.")

    # except Exception as e:
    #     print("an error occurred", e)
    #     logger.error(f"An error occurred while processing the query: {e}")
