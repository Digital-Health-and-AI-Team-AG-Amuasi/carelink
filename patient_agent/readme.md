
# C.A.R.E-LLM

**C.A.R.E-LLM** (Context-Aware Reasoning Engine for Large Language Models) is an advanced AI-driven backend system designed to revolutionize maternal healthcare in Ghana. It integrates Large Language Models (LLMs), Clinical Decision Support (CDS), and multilingual capabilities to support both patients (pregnant mothers) and healthcare providers.

## 🚀 Key Features

*   **Multilingual Support**: Real-time translation and audio processing for local dialects (e.g., Twi) using GhanaNLP integration.
*   **AI Assistants**: Specialized agents for different tasks:
    *   `GDM Assistant`: Patient support for Gestational Diabetes Management.
    *   `CDS Assistant`: Clinical decision support for doctors.
    *   `Reminders Assistant`: Automated patient follow-ups.
*   **RAG Pipeline**: Retrieval-Augmented Generation for evidence-based responses using clinical guidelines (`gmtg.pdf`).
*   **Omnichannel Integration**: Seamless communication via WhatsApp (using the Meta Cloud API) and EHR system integrations.
*   **Azure Infrastructure**: Built to run serverless on Azure Functions.

## 📂 Project Structure

The project follows a modern, modular FastAPI architecture:

```
C.A.R.E-LLM/
├── app/
│   ├── api/            # Route handlers (Controllers)
│   ├── services/       # Business logic & Data Access Layer (DAL)
│   ├── modules/        # AI, Messenger, and Vision modules
│   ├── schemas/        # Pydantic data models
│   ├── config/         # Configuration & Settings
│   └── utils/          # Shared utilities
├── infra/              # Infrastructure (Azure Functions)
└── tests/              # Unit and integration tests
```

## 🛠️ Technology Stack

*   **Framework**: FastAPI
*   **Runtime**: Python 3.10+
*   **Deployment**: Azure Functions (ASGI)
*   **AI/LLM**: LangChain, OpenAI GPT-4, Groq (Llama 3)
*   **Database**: MongoDB / Azure Blob Storage
*   **Integration**: WhatsApp Cloud API, GhanaNLP API

## 🚦 Getting Started

### Prerequisites

*   Python 3.10 or higher
*   Azure Functions Core Tools (for local deployment testing)
*   Environment variables configured in `.env`

### Local Development

1.  **Install Dependencies**:
    ```bash
    pip install -r requirements.txt
    ```

2.  **Run Locally (FastAPI)**:
    ```bash
    uvicorn app.main:app --reload
    ```
    Access Swagger documentation at `http://127.0.0.1:8000/docs`.

3.  **Run with Azure Functions**:
    ```bash
    cd infra
    func start
    ```

## 🧪 Testing

Run test suite using pytest:

```bash
pytest
```
