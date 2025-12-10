@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 p-4">
        <!-- Notification Button -->
        <button id="notification-button"
                class="fixed z-[100] bottom-8 right-8 bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-full shadow-xl hover:shadow-2xl hover:from-green-600 hover:to-green-700 transition-all duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-green-400/50 disabled:opacity-50 disabled:cursor-not-allowed hidden">
            <div id="notification-loader"
                 class="loader-spinner absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 hidden border-2 border-white/30 border-t-white rounded-full w-6 h-6 animate-spin"></div>
            <i id="chat-icon" class="fas fa-comment-dots text-xl"></i>
            <span id="notification-badge"
                  class="absolute top-0 right-0 bg-red-500 text-white text-xs font-medium rounded-full h-5 w-5 flex items-center justify-center -mt-1 -mr-1 transition-all duration-300 ease-in-out scale-0 opacity-0">
                0
            </span>
        </button>

        <!-- Chat Popup -->
        <div id="chat-popup"
             class="absolute bottom-24 right-8 w-[24rem] h-[32rem] bg-white border border-gray-200 rounded-2xl shadow-2xl flex flex-col overflow-hidden translate-y-full opacity-0 transition-all duration-300 ease-in-out font-sans z-[80]"
             style="top: auto; left: auto;">
            <div id="chat-popupHeader" class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-green-500 to-green-600 border-b border-gray-200/50 cursor-move">
                <h2 class="text-base font-semibold text-white tracking-tight">CareEMR AI</h2>
                <button id="close-chat-button"
                        class="text-white hover:text-gray-200 p-1.5 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="chat-messages"
                 class="flex-1 px-5 py-4 space-y-4 overflow-y-auto bg-gray-50 text-sm text-gray-900">
                <div class="flex justify-start">
                    <div class="bg-white border border-gray-200 px-4 py-3 rounded-xl rounded-bl-none max-w-[80%] shadow-sm">
                        Hello! How can I assist you today?
                    </div>
                </div>
            </div>
            <div id="typing-indicator" class="hidden px-5 pb-3 text-xs text-gray-500 animate-pulse font-medium">
                CareEMR AI is typing...
            </div>
            <div class="px-5 py-4 border-t border-gray-200 bg-white">
                <div class="flex items-center gap-3">
                    <input type="text" id="user-input" placeholder="Type your message..."
                           class="flex-1 bg-gray-100 border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-sm placeholder-gray-400">
                    <button id="send-button"
                            class="bg-green-500 hover:bg-green-600 text-white p-2.5 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="w-full mx-auto px-6 kt-container-fixed">
            <!-- Main Header with Settings Icon -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-normal text-gray-900">PROFILE</h1>
                <div class="flex gap-2 items-center">
                    <a href="{{ route('patients.index') }}"
                       class="flex items-center border border-green-500 hover:border-green-600 text-gray-500 font-normal py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                        </svg>
                        Back to Patients
                    </a>
                    <button id="addNewRecordBtn"
                            class="justify-center bg-gradient-to-r from-green-500 to-green-400 hover:from-green-600 hover:to-green-500 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Add New
                    </button>
                    <button id="settings-toggle"
                            class="text-gray-500 hover:text-gray-700 p-2 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500"
                            aria-label="Open settings">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Settings Sidebar -->
            <div id="settings-sidebar"
                 class="fixed inset-y-0 right-0 w-80 bg-white rounded-l-2xl shadow-2xl flex flex-col overflow-hidden translate-x-full opacity-0 transition-all duration-300 ease-in-out z-[90]">
                <div id="settings-header" class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-green-500 to-green-400 border-b border-gray-200/50 cursor-move">
                    <h2 class="text-base font-semibold text-white tracking-tight">Settings</h2>
                    <button id="close-settings-sidebar"
                            class="text-white hover:text-gray-200 p-1.5 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white/50"
                            aria-label="Close settings">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 p-6 overflow-y-auto space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Language</h3>
                        <select id="language-select"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200">
                            <option value="en">English</option>
                            <option value="ga">Ga</option>
                            <option value="tw">Twi</option>
                        </select>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Modality</h3>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="modality" value="text" class="text-green-500 focus:ring-green-500" checked>
                                <span class="text-sm text-gray-600">Text</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="modality" value="audio" class="text-green-500 focus:ring-green-500">
                                <span class="text-sm text-gray-600">Audio</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200">
                    <div class="flex justify-end gap-3">
                        <button id="cancel-settings"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button id="save-settings"
                                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Save
                        </button>
                    </div>
                </div>
            </div>

            <!-- Patient Info Card -->
            <div id="record-data-phone" data-phone=@json($patient->phone)></div>
            <div id="record-data-id hidden" data-id=@json($patient->id)></div>
            <div class="bg-white shadow-xl rounded-lg mb-8 w-full border border-gray-100">
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-y-4 gap-x-6">
                        <div class="flex flex-col">
                            <label class="text-sm font-semibold text-gray-500 mb-1">Name</label>
                            <p class="text-lg font-bold text-gray-800">{{ $patient->first_name }} {{ $patient->last_name }}</p>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-semibold text-gray-500 mb-1">Age</label>
                            <p class="text-lg font-bold text-gray-800">{{ Carbon::parse($patient->dob)->age ?? 'N/A' }} years</p>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-semibold text-gray-500 mb-1">EDD</label>
                            <p class="text-lg font-bold text-gray-800">{{ Carbon::parse($patient->edd)->format('F j, Y') ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-semibold text-gray-500 mb-1">EGA</label>
                            <p class="text-lg font-bold text-gray-800">{{ $patient->ega ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-semibold text-gray-500 mb-1">Notation</label>
                            <p class="text-lg font-bold text-gray-800">{{ $patient->notation ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <x-flash-messages/>

            <!-- Main Layout -->
            <div class="flex gap-6">
                <!-- Left Sidebar -->
                <div class="w-80 flex-shrink-0">
                    <div class="bg-white shadow rounded-lg sticky top-6">
                        <div class="border-b px-4 py-4">
                            <h2 class="text-lg font-semibold text-gray-800">Patient Information</h2>
                        </div>
                        <div class="p-4 space-y-2">
                            <!-- Demographics -->
                            <div class="collapsible-section">
                                <button class="collapsible-trigger flex items-center justify-between w-full p-2 hover:bg-gray-50 rounded-md" data-target="demographics">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-users class="w-4 h-4"/>
                                        <span class="font-medium text-sm">Demographics</span>
                                    </div>
                                    <x-heroicon-o-chevron-right class="w-4 h-4 transition-transform duration-200"/>
                                </button>
                                <div id="demographics" class="collapsible-content hidden pl-6 pt-2 space-y-2 text-sm">
                                    <div><strong>Marital Status:</strong> {{ $patient->marital_status ?? 'N/A' }}</div>
                                    <div><strong>Religion:</strong> {{ $patient->religion ?? 'N/A' }}</div>
                                    <div><strong>Occupation:</strong> {{ $patient->occupation ?? 'N/A' }}</div>
                                    <div><strong>NHIS Status:</strong> {{ $patient->nhis_status ?? 'N/A' }}</div>
                                    <div><strong>Phone:</strong> {{ $patient->phone ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <!-- Past Medical & Surgical History -->
                            <div class="collapsible-section">
                                <button class="collapsible-trigger flex items-center justify-between w-full p-2 hover:bg-gray-50 rounded-md" data-target="pastHistory">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-document-text class="w-4 h-4"/>
                                        <span class="font-medium text-sm">Past Medical & Surgical History</span>
                                    </div>
                                    <x-heroicon-o-chevron-right class="w-4 h-4 transition-transform duration-200"/>
                                </button>
                                <div id="pastHistory" class="collapsible-content hidden pl-6 pt-2 space-y-2 text-sm">
                                    @if($patient->medical_history)
                                        @foreach ($patient->medical_history as $item)
                                            <div>• {{ $item }}</div>
                                        @endforeach
                                    @else
                                        <div>• No history available</div>
                                    @endif
                                </div>
                            </div>
                            <!-- Obstetric History -->
                            <div class="collapsible-section">
                                <button class="collapsible-trigger flex items-center justify-between w-full p-2 hover:bg-gray-50 rounded-md" data-target="obstetricHistory">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-heart class="w-4 h-4"/>
                                        <span class="font-medium text-sm">Obstetric History</span>
                                    </div>
                                    <x-heroicon-o-chevron-right class="w-4 h-4 transition-transform duration-200"/>
                                </button>
                                <div id="obstetricHistory" class="collapsible-content hidden pl-6 pt-2 space-y-2 text-sm">
                                    @if($patient->obstetric_history)
                                        @foreach ($patient->obstetric_history as $item)
                                            <div>• {{ $item }}</div>
                                        @endforeach
                                    @else
                                        <div>• No history available</div>
                                    @endif
                                </div>
                            </div>
                            <!-- Drug History -->
                            <div class="collapsible-section">
                                <button class="collapsible-trigger flex items-center justify-between w-full p-2 hover:bg-gray-50 rounded-md" data-target="drugHistory">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-cube class="w-4 h-4"/>
                                        <span class="font-medium text-sm">Drug History</span>
                                    </div>
                                    <x-heroicon-o-chevron-right class="w-4 h-4 transition-transform duration-200"/>
                                </button>
                                <div id="drugHistory" class="collapsible-content hidden pl-6 pt-2 space-y-2 text-sm">
                                    @if($patient->drug_history)
                                        @foreach ($patient->drug_history as $item)
                                            <div>• {{ $item }}</div>
                                        @endforeach
                                    @else
                                        <div>• No history available</div>
                                    @endif
                                </div>
                            </div>
                            <!-- Social History -->
                            <div class="collapsible-section">
                                <button class="collapsible-trigger flex items-center justify-between w-full p-2 hover:bg-gray-50 rounded-md" data-target="socialHistory">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-users class="w-4 h-4"/>
                                        <span class="font-medium text-sm">Social History</span>
                                    </div>
                                    <x-heroicon-o-chevron-right class="w-4 h-4 transition-transform duration-200"/>
                                </button>
                                <div id="socialHistory" class="collapsible-content hidden pl-6 pt-2 space-y-2 text-sm">
                                    @if($patient->social_history)
                                        @foreach ($patient->social_history as $item)
                                            <div>• {{ $item }}</div>
                                        @endforeach
                                    @else
                                        <div>• No history available</div>
                                    @endif
                                </div>
                            </div>
                            <!-- Medical Records -->
                            <div class="collapsible-section">
                                <button class="collapsible-trigger flex items-center justify-between w-full p-2 hover:bg-gray-50 rounded-md" data-target="medicalRecords">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-document-text class="w-4 h-4"/>
                                        <span class="font-medium text-sm">Medical Records</span>
                                    </div>
                                    <x-heroicon-o-chevron-right class="w-4 h-4 transition-transform duration-200"/>
                                </button>
                                <div id="medicalRecords" class="collapsible-content hidden pl-6 pt-2 space-y-2 text-sm">
                                    @if($patient->vitals)
                                        @foreach ($patient->vitals as $item)
                                            <div>• {{ $item }}</div>
                                        @endforeach
                                    @else
                                        <div>• No history available</div>
                                    @endif
                                </div>
                            </div>
                            <!-- Vitals -->
                            <div class="collapsible-section">
                                <button class="collapsible-trigger flex items-center justify-between w-full p-2 hover:bg-gray-50 rounded-md" data-target="vitals-">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-heart class="w-4 h-4"/>
                                        <span class="font-medium text-sm">Vitals</span>
                                    </div>
                                    <x-heroicon-o-chevron-right class="w-4 h-4 transition-transform duration-200"/>
                                </button>
                                <div id="vitals-" class="collapsible-content hidden pl-6 pt-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="flex items-start gap-3 bg-gray-50 p-4 rounded-md border border-gray-100">
                                            <x-heroicon-o-clipboard-document class="w-5 h-5 text-gray-500 mt-1"/>
                                            <div>
                                                <label class="text-xs text-gray-500">Last BP</label>
                                                <p class="text-base font-semibold text-gray-800">
                                                    {{ $patient->last_bp ?? '140/90 mmHg' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3 bg-gray-50 p-4 rounded-md border border-gray-100">
                                            <x-heroicon-o-scale class="w-5 h-5 text-gray-500 mt-1"/>
                                            <div>
                                                <label class="text-xs text-gray-500">Weight</label>
                                                <p class="text-base font-semibold text-gray-800">
                                                    {{ $patient->weight ?? '165 lbs' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3 bg-gray-50 p-4 rounded-md border border-gray-100">
                                            <x-heroicon-o-chart-bar class="w-5 h-5 text-gray-500 mt-1"/>
                                            <div>
                                                <label class="text-xs text-gray-500">BMI</label>
                                                <p class="text-base font-semibold text-gray-800">
                                                    {{ $patient->bmi ?? '24.8' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Current Medication -->
                            <div class="collapsible-section">
                                <button class="collapsible-trigger flex items-center justify-between w-full p-2 hover:bg-gray-50 rounded-md" data-target="currentMedication">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-cube class="w-4 h-4"/>
                                        <span class="font-medium text-sm">Past Medications</span>
                                    </div>
                                    <x-heroicon-o-chevron-right class="w-4 h-4 transition-transform duration-200"/>
                                </button>
                                <div id="currentMedication" class="collapsible-content hidden pl-6 pt-2 space-y-2 text-sm">
                                    @if($patient->medications)
                                        @foreach ($patient->medications as $med)
                                            <div>• {{ $med }}</div>
                                        @endforeach
                                    @else
                                        <div>• No medications yet</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Section - Visit Records -->
                <div class="flex-1 min-w-0 space-y-6">
                    @forelse ($patient->patientRecords as $patientRecord)
                        <div class="record-data bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200" data-id=@json($patientRecord->id)>
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                        <h3 class="text-xl font-bold text-gray-800">Record #{{ $patientRecord->id }}</h3>
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12V12zm0 3h.008v.008H12V15zm0 3h.008v.008H12V18z"/>
                                            </svg>
                                            {{ $patientRecord->visit->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                            </svg>
                                            {{ $patientRecord->user->first_name ?? 'N/A' }} {{ $patientRecord->user->last_name ?? '' }}
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 mt-3 sm:mt-0">
                                        <button class="view-record-btn-{{$patientRecord->id}} inline-flex items-center px-4 py-2 text-sm font-medium border border-green-500 rounded-md shadow-sm text-green-500 bg-white hover:bg-green-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 ease-in-out"
                                                data-record-id="{{ $patientRecord->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 01 6 0Z"/>
                                            </svg>
                                            View
                                        </button>
                                      <div class="record-meta">
    <input type="hidden" class="record-id" value="{{ $patientRecord->id }}">
    <input type="hidden" class="update-url" value="{{ route('patients.update-review', compact("patientRecord", "patient")) }}">
                                          <input type="hidden" class="patient-id" value="{{$patient}}">
                                          <input type="hidden" id="patient-id" value="{{$patient->id}}">

    <button class="edit-record-btn inline-flex items-center px-4 py-2 text-sm font-medium border border-green-500 rounded-md shadow-sm text-green-500 bg-white hover:bg-green-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14.25v4.5a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18.75v-11.25A2.25 2.25 0 0 1 5.25 5.25h4.5"/>
        </svg>
        Edit
    </button>
</div>

                                        <form action="{{ route('patients.delete-review', compact('patient', 'patientRecord')) }}"
                                              method="POST" class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to delete this record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                               class="inline-flex items-center px-4 py-2 text-sm font-medium border border-red-300 rounded-md shadow-sm text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150 ease-in-out">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                </svg>
                                            </button>
                                        </form>


                                    </div>
                                </div>
                            </div>
                            <div id="record-content-{{ $patientRecord->id }}" class="record-content hidden">
                                <div class="px-6 space-y-2">
                                    <div class="p-4 rounded-lg space-y-4">

                                        <div class="space-y-2">
                                            <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-800">
                                                <x-heroicon-o-arrow-path-rounded-square class="w-5 h-5 text-green-500"/>
                                                History of Presenting Complaints
                                            </h3>
                                            <p class="px-8 text-md text-gray-700">{{ $patientRecord->history_presenting_complains ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <div class="flex items-center gap-4 my-6">
                                            <div class="w-6 border-t border-gray-300"></div>
                                            <h2 class="text-lg text-gray-800 whitespace-nowrap">Review</h2>
                                            <div class="flex-1 border-t border-gray-300"></div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-6">
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-800">
                                                    <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 text-green-500"/>
                                                    Current Complaints
                                                </h3>
                                                <p class="px-8 text-md text-gray-700">{{ $patientRecord->current_complains ?? 'None' }}</p>
                                            </div>
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-800">
                                                    <x-heroicon-o-question-mark-circle class="w-5 h-5 text-green-500"/>
                                                    On Direct Questioning
                                                </h3>
                                                <p class="px-8 text-md text-gray-700">{{ $patientRecord->on_direct_questions ?? 'None' }}</p>
                                            </div>
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-800">
                                                    <x-heroicon-o-eye class="w-5 h-5 text-green-500"/>
                                                    Examination Findings
                                                </h3>
                                                <p class="px-8 text-md text-gray-700">{{ $patientRecord->on_examinations ?? 'None' }}</p>
                                            </div>
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-800">
                                                    <x-heroicon-o-heart class="w-5 h-5 text-red-500"/>
                                                    Current Vitals
                                                </h3>
                                                @php
                                                    $parsedVitals = $patientRecord->vitals
                                                @endphp
                                                @if(is_array($parsedVitals) && count($parsedVitals) > 0)
                                                    <ul class="text-sm font-mono p-2 space-y-1 px-8 text-md text-gray-700">
                                                        @foreach($parsedVitals as $vital)
                                                            <li><span class="font-semibold">{{ $vital }}</span></li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="px-8 text-md text-gray-700">N/A</p>
                                                @endif
                                            </div>
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-800">
                                                    <x-heroicon-o-beaker class="w-5 h-5 text-green-500"/>
                                                    Investigations
                                                </h3>
                                                <p class="px-8 text-md text-gray-700">{{ $patientRecord->labs ?? 'N/A' }}</p>
                                            </div>
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-800">
                                                    <x-heroicon-o-pencil-square class="w-5 h-5 text-green-500"/>
                                                    Impression
                                                </h3>
                                                <p class="px-8 text-md text-gray-700">{{ $patientRecord->impression ?? 'None' }}</p>
                                            </div>
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-800">
                                                    <x-heroicon-o-document-text class="w-5 h-5 text-green-500"/>
                                                    Plan
                                                </h3>
                                                <p class="px-8 text-md text-gray-700">{{ $patientRecord->plan ?? 'None' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-yellow-50 text-yellow-800 border border-yellow-200 p-4 rounded-lg shadow-sm">
                                No patient records found for this patient.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Add/Edit Record Modal -->
            <div id="addNewRecordModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50"
                 style="background: rgba(0, 0, 0, 0.75);">
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="flex justify-between items-center pb-3">
                        <h3 id="modal-title" class="text-2xl font-bold text-gray-900">Add New Record</h3>
                        <button id="closeModalBtn" type="button" class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-2 text-gray-700">
                        <!-- Loader for Edit Fetch -->
                        <div id="modal-loader" class="hidden absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                            <div class="loader-spinner border-2 border-gray-200 border-t-green-500 rounded-full w-8 h-8 animate-spin"></div>
                        </div>
                        <form id="newRecordForm" action="{{ route('patients.store-review', compact('patient')) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('POST')
                            <hr class="border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 20.25H3.75a2.25 2.25 0 0 1-2.25-2.25V6.75a2.25 2.25 0 0 1 2.25-2.25h16.5a2.25 2.25 0 0 1 2.25 2.25v11.25c0 1.241-.94 2.25-2.25 2.25H10.5Z"/>
                                </svg>
                                Review
                            </h3>
                            <div>
                                <label for="current_complains" class="block text-sm font-medium text-gray-700">Current Complaints</label>
                                <textarea name="current_complains" id="current_complaints" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-green-500 focus:border-green-500"
                                          placeholder="Patient's current complaints..."></textarea>
                            </div>
                            <div class="mb-4 relative">
                                <label for="history_presenting_complains" class="block text-sm font-medium text-gray-700 mb-1">
                                    History of Presenting Complaint (HPC)
                                </label>
                                <textarea name="history_presenting_complains" id="hpc" rows="4"
                                          class="block w-full border border-gray-300 rounded-md shadow-sm p-3 pr-12 pb-10 text-sm focus:ring-green-500 focus:border-green-500 resize-y"
                                          placeholder="Enter manually or use AI to generate..."></textarea>
                                <div class="absolute bottom-2 right-2 flex items-center gap-2 bg-white bg-opacity-80 px-2 py-1 rounded-md shadow-sm z-10">
                                    <label for="toggle-ai-fetch" class="text-xs text-gray-600 select-none">Use AI</label>
                                    <button id="toggle-ai-fetch"
                                            type="button"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-300 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 data-[state=checked]:bg-green-500">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 data-[state=checked]:translate-x-6 translate-x-1"></span>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label for="on_direct_questions" class="block text-sm font-medium text-gray-700">On Direct Questioning</label>
                                <textarea name="on_direct_questions" id="on_direct_questions" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-green-500 focus:border-green-500"
                                          placeholder="Findings on direct questioning..."></textarea>
                            </div>
                            <div>
                                <label for="on_examinations" class="block text-sm font-medium text-gray-700">Examination Findings</label>
                                <textarea name="on_examinations" id="on_examinations" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-green-500 focus:border-green-500"
                                          placeholder="Results of physical examinations..."></textarea>
                            </div>
                            <div>
                                <label for="vitals" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                    </svg>
                                    Current Vitals
                                </label>
                                <input name="vitals" id="vitals"
                                       class="tagify-field mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder='Eg: BP: 120/80 mmHg'/>
                            </div>
                            <div>
                                <label for="investigations" class="block text-sm font-medium text-gray-700">Investigations</label>
                                <textarea name="investigations" id="investigations" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-green-500 focus:border-green-500"
                                          placeholder="Details of lab tests or investigations..."></textarea>
                            </div>
                            <div class="mb-4 relative">
                                <label for="impression" class="block text-sm font-medium text-gray-700">Impression</label>
                                <textarea name="impression" id="impression" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 pr-10 resize-y focus:ring-green-500 focus:border-green-500"
                                          placeholder="Doctor's impression..."></textarea>
                                <button id="aiSuggestionTrigger"
                                        class="absolute bottom-2 right-2 bg-white rounded-full p-1 shadow-md opacity-0 transition-opacity duration-300 z-20 hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                        aria-label="Get AI suggestions" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-green-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L18.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z"/>
                                    </svg>
                                </button>
                                <div id="aiChatPopup"
                                     class="fixed hidden w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50 overflow-hidden flex-col"
                                     style="top: 100px; right: 100px;">
                                    <div id="aiChatPopupHeader"
                                         class="bg-gradient-to-r from-green-100 to-green-200 p-4 flex justify-between items-start">
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-800">AI Chat Assistant</h4>
                                        </div>
                                        <button id="closeAiChatPopup" class="text-gray-500 hover:text-gray-700" aria-label="Close" type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="login-wrapper p-4 flex-grow overflow-y-auto text-sm text-gray-700 space-y-4">
                                        <div id="aiPrompt">
                                            <div id="form-loader-ai" class="loader-overlay hidden">
                                                <div class="loader-spinner"></div>
                                            </div>
                                            <p class="text-base font-medium text-gray-800">Add patient AI chat to patient data?</p>
                                            <div class="mt-2 flex gap-3">
                                                <button id="acceptAiPrompt" type="button"
                                                        class="px-4 py-1.5 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                                                    Yes
                                                </button>
                                                <button id="declineAiPrompt" type="button"
                                                        class="px-4 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-100">
                                                    No
                                                </button>
                                            </div>
                                        </div>
                                        <div id="aiImpression" class="hidden">
                                            <h5 class="font-medium text-green-700">Impression Suggestion:</h5>
                                            <div class="bg-green-50 p-3 rounded-md border border-green-200">
                                                <p>#AI_IMPRESSION_SUGGESTION_GOES_HERE#</p>
                                                <button class="mt-2 text-green-700 hover:underline text-xs" type="button">Insert</button>
                                            </div>
                                            <button id="showPlanSuggestion" type="button"
                                                    class="mt-3 text-blue-600 text-sm hover:underline">Show Plan Suggestion</button>
                                        </div>
                                        <div id="aiPlan" class="hidden">
                                            <h5 class="font-medium text-blue-700">Plan Suggestion:</h5>
                                            <div class="bg-blue-50 p-3 rounded-md border border-blue-200">
                                                <p>#AI_PLAN_SUGGESTION_GOES_HERE#</p>
                                                <button type="button" class="mt-2 text-blue-700 hover:underline text-xs">Insert</button>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-400">These are AI-generated suggestions and should be reviewed by a medical professional.</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="plan" class="block text-sm font-medium text-gray-700">Plan</label>
                                <textarea name="plan" id="plan" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-green-500 focus:border-green-500"
                                          placeholder="Treatment plan..."></textarea>
                            </div>
                            <div class="flex justify-end pt-4">
                                <button id="form-submit-btn" type="submit"
                                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                                    Save Record
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- AI Suggestions Modal -->
            <div id="ai-suggestions-modal"
                 class="fixed inset-y-0 right-0 w-[50%] bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm h-full max-h-[90vh] overflow-hidden flex flex-col">
                    <div class="flex justify-between items-center p-6 border-b pb-3">
                        <h2 class="text-xl font-semibold text-gray-800">AI Suggestions</h2>
                        <button id="close-ai-suggestions-btn" type="button"
                                class="text-gray-500 hover:text-gray-700 transition duration-200 focus:outline-none"
                                aria-label="Close AI suggestions panel">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="space-x-2 p-2 bg-green-50 rounded-lg border border-green-200">
                        <input type="checkbox" id="include-ai-patient-chat" class="rounded text-green-600 focus:ring-green-500"/>
                        <label for="ai-chat-1" class="text-sm font-medium leading-none text-gray-700">
                            Add patient AI chat to patient data
                        </label>
                    </div>
                    <div class="flex-grow p-6 overflow-y-auto">
                        <div class="space-y-4">
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h4 class="text-md font-medium text-gray-700 mb-2">Impression Suggestions:</h4>
                                <p id="ai-suggestion-impression-modal"></p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h4 class="text-md font-medium text-gray-700 mb-2">Plan Suggestions:</h4>
                                <p id="ai-suggestion-plan-modal"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
  <script>

document.addEventListener("DOMContentLoaded", function () {
    // Drag functionality for aiChatPopup

});
</script>
@endpush
