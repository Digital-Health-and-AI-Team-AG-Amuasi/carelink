@extends('layouts.app')

@section('title', 'CareEMR - CDS')

@section('content')

    <main id="mainContent" class="flex-1 overflow-y-auto h-screen ml-64">
        <div class="container mt-5">
        <!-- Main content -->
            <div class="space-y-6">
                <h1 class="text-3xl font-bold tracking-tight">Clinical Decision Support</h1>

                <hr style="border: 1px solid #a9a9a9;">

                <div class="card mb-4">
                    <div class="card-header text-gray-900 bg-slate-200">
                        <i class="fas fa-search me-2"></i> Patient Search
                    </div>
                    <div class="card-body">
                        <form action="{{ route('select.patient') }}" method="POST">
                            @csrf
                            <!-- Search Type and Input (Side by Side) -->
                            <div class="row mb-3">
                                <!-- Search Type Selector -->
                                <div class="col-md-3">
                                    <label for="search_type" class="form-label">Search By</label>
                                    <select name="search_type" id="search_type" class="form-select">
                                        <option value="phone" selected>Phone Number</option>
                                        <option value="name">Name</option>
                                    </select>
                                </div>

                                <!-- Search Input -->
                                <div class="col-md-7">
                                    <label for="search_input" class="form-label">Enter Search Value</label>
                                    <input type="text" id="search_input" class="form-control"
                                           placeholder="Enter phone number or name...">
                                </div>
                            </div>

                            <!-- Patient Selection Dropdown -->
                            <div class="mb-3">
                                <label for="patient_id" class="form-label">Choose a Patient</label>
                                <select name="patient_id" id="patient_id" class="form-select" required>
                                    <option value="">-- Select --</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}"
                                                data-phone="{{ $patient->phone }}"
                                                data-name="{{ strtolower($patient->first_name . ' ' . $patient->last_name) }}">
                                            {{  $patient->first_name . " " . $patient->last_name . " (" . $patient->phone . ")"}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" id="submitBtn" class="btn btn-primary">
                                <i class="fas fa-check-circle me-2"></i>Submit
                            </button>
                        </form>
                    </div>
                </div>

                @if(session('selected_patient'))

                    <hr style="border: 1px solid #a9a9a9;">

                    <div id="patientDetails">
                        @php $patientData = session('selected_patient'); @endphp
                        <div class="row mb-4">
                            <div class="col-lg-12">
                                <div class="patient-card">
                                    <div class="patient-header d-flex align-items-center">
                                        <div class="patient-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>

                                        <div>
                                            <h4 id="patientName">{{ $patientData['patient']['name'] }}</h4>
                                            <div class="d-flex align-items-center mt-2">
                                                    <span class="badge bg-light text-dark me-2">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        <span id="patientDOB">{{ $patientData['patient']['date_of_birth'] }}</span>
                                                    </span>
                                                <span class="badge bg-light text-dark me-2">
                                                        <i class="fas fa-venus-mars me-1"></i>
                                                        <span id="patientGender">{{ ucfirst($patientData['patient']['gender']) }}</span>
                                                    </span>
                                                <span class="badge bg-light text-dark">
                                                        <i class="fas fa-id-card me-1"></i>
                                                        <span id="patientID">{{ $patientData['patient']['id'] }}</span>
                                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="patient-info">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><i class="fas fa-phone me-2"></i>Phone: <span id="patientPhone">{{ $patientData['patient']['phone'] }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Encounters section -->
                        <h3 class="page-header">Medical Encounters</h3>

                        @foreach($patientData['encounter'] as $encounter)
                            <div class="encounter-card">
                                <div class="encounter-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-clipboard-list me-2"></i>
                                        Encounter # {{ $encounter['id'] }}
                                    </div>
                                    <div class="encounter-date">{{ $encounter['date'] }}</div>
                                </div>
                                <div class="encounter-body">
                                    <p><strong>Reason for Visit:</strong> {{ $encounter['reason'] }}</p>

                                    @if(!empty($encounter['observations']))
                                        <h5 class="mt-3 mb-2">Observations</h5>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Value</th>
                                                    <th>Unit</th>
                                                    <th>Recorded At</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($encounter['observations'] as $obs)
                                                    <tr>
                                                        <td>{{ $obs[0] }}</td>
                                                        <td>{{ $obs[1] }}</td>
                                                        <td>{{ $obs[2] }}</td>
                                                        <td>{{ $obs[3] }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                    @if(!empty($encounter['medications']))
                                        <h5 class="mt-4 mb-2">Medications</h5>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Drug Name</th>
                                                    <th>Dosage</th>
                                                    <th>Frequency</th>
                                                    <th>Duration</th>
                                                    <th>Prescribed At</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($encounter['medications'] as $med)
                                                    <tr>
                                                        <td>{{ $med[0] }}</td>
                                                        <td>{{ $med[1] }}</td>
                                                        <td>{{ $med[2] }}</td>
                                                        <td>{{ $med[3] }}</td>
                                                        <td>{{ $med[4] }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach


                    </div>
                @endif
            </div>

            <!-- Floating chat button -->
            <button class="floating-button" id="toggleChatAiSection">
                <i class="fas fa-robot"></i>
            </button>

            <!-- Chatbot sidebar -->
            <div class="sidebar" id="chatAiSection">
                <div class="sidebar-header">
                    <h5><i class="fas fa-robot me-2"></i>Doctor AI Assistant</h5>
                    <button class="sidebar-close" id="closeBtn"><i class="fas fa-times"></i></button>
                </div>
                <div class="chat-container">
                    <div class="chat-box" id="chatBox">
                        {{--                        <div class="message bot-message">--}}
                        {{--                            Hello! I'm your medical assistant. I have access to the patient's medical records.--}}
                        {{--                            How can I help you today?--}}
                        {{--                        </div>--}}
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" id="questionInput"
                               placeholder="Type your question...">
                        <button class="btn btn-primary" id="sendBtn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
    </div>
    </main>

@endsection

@push("scripts")
    <script>
        let contextSent = false; // Check if context has been sent
        const mainContent = document.getElementById('mainContent');

        // Show patient details when a patient is selected and submitted
        document.getElementById('submitBtn').addEventListener('click', function () {
            const patientId = document.getElementById('patient_id').value;
            if (patientId) {
                document.getElementById('patientDetails').style.display = 'block';

                // Scroll to patient details
                setTimeout(() => {
                    document.getElementById('patientDetails').scrollIntoView({
                        behavior: 'smooth'
                    });
                }, 100);
            }
        });

        // Filter patient options based on search input
        document.getElementById('search_input').addEventListener('input', function () {
            let searchValue = this.value.trim().toLowerCase();
            let searchType = document.getElementById('search_type').value;
            let patientSelect = document.getElementById('patient_id');

            // Loop through options and hide/show based on search
            Array.from(patientSelect.options).forEach(option => {
                console.log(searchValue)
                if (option.value === "") return; // Skip the placeholder option

                let matches = false;
                if (searchType === "phone") {
                    matches = option.getAttribute('data-phone').includes(searchValue);
                } else {
                    matches = option.getAttribute('data-name').includes(searchValue);
                }

                option.style.display = matches ? "" : "none";
            });
        });

        // Toggle sidebar
        document.getElementById('toggleChatAiSection').addEventListener('click', function () {
            document.getElementById('chatAiSection').classList.add('open');

            let chatBox = document.getElementById('chatBox');

            if (!contextSent) {
                // Add typing animation
                const typingAnimation = document.createElement('div');
                typingAnimation.classList.add('typing-animation');
                typingAnimation.innerHTML = '<span></span><span></span><span></span>';
                chatBox.appendChild(typingAnimation);
                chatBox.scrollTop = chatBox.scrollHeight;

                fetch('/ask-llm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        patient_id: "{{$patientData['patient']['id'] ?? ''}}"
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Context sent:', data);
                        contextSent = true; // Mark as sent

                        chatBox.removeChild(typingAnimation);

                        // Display LLM response
                        let botMessage = document.createElement('div');
                        botMessage.classList.add('message', 'bot-message');
                        botMessage.textContent = "I have access to the patients medical records. What can I help with?"; // Assuming Laravel returns JSON { response: "Bot reply" }
                        chatBox.appendChild(botMessage);
                        chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to latest message

                    })
                    .catch(error => {
                        console.error('Error:', error)
                        // Remove typing animation
                        chatBox.removeChild(typingAnimation);

                        // Display AI response
                        let botMessage = document.createElement('div');
                        botMessage.classList.add('message', 'bot-message');
                        botMessage.textContent = "Sorry, an error occurred on your side. Refresh your page and try again!";
                        chatBox.appendChild(botMessage);
                        chatBox.scrollTop = chatBox.scrollHeight;
                    });
            }
        });

        // Close sidebar
        document.getElementById('closeBtn').addEventListener('click', function () {
            document.getElementById('chatAiSection').classList.remove('open');
        });

        // Handle chat sending
        document.getElementById('sendBtn').addEventListener('click', sendMessage);
        document.getElementById('questionInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        function sendMessage() {
            const inputField = document.getElementById('questionInput');
            const messageText = inputField.value.trim();
            const chatBox = document.getElementById('chatBox');

            if (messageText) {
                // Add user message
                const userMessage = document.createElement('div');
                userMessage.classList.add('message', 'user-message');
                userMessage.textContent = messageText;
                chatBox.appendChild(userMessage);

                // Clear input
                inputField.value = '';

                // Auto scroll
                chatBox.scrollTop = chatBox.scrollHeight;

                // Add typing animation
                const typingAnimation = document.createElement('div');
                typingAnimation.classList.add('typing-animation');
                typingAnimation.innerHTML = '<span></span><span></span><span></span>';
                chatBox.appendChild(typingAnimation);
                chatBox.scrollTop = chatBox.scrollHeight;

                // Send request to Laravel endpoint
                fetch('/ask-llm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ doctor_question: messageText })
                })
                    .then(response => response.json())
                    .then(data => {
                        // Remove typing animation
                        chatBox.removeChild(typingAnimation);

                        // Display AI response
                        let botMessage = document.createElement('div');
                        botMessage.classList.add('message', 'bot-message');
                        botMessage.textContent = data.response;
                        chatBox.appendChild(botMessage);
                        chatBox.scrollTop = chatBox.scrollHeight;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        chatBox.removeChild(typingAnimation); // Remove animation on error
                    });
            }
        }
    </script>
@endpush

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
