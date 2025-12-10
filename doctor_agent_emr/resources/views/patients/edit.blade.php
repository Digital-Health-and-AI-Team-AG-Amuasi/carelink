@php use App\Enums\MaritalStatus;use App\Enums\NhisStatus; @endphp
@extends('layouts.app')

@section('title', 'Update Patient Records')

@section('action-links')
    <a class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
       href="{{ route('patients.index') }}">
        <!-- Lucide icon for back arrow -->
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-arrow-left mr-2">
            <path d="m12 19-7-7 7-7"/>
            <path d="M19 12H5"/>
        </svg>
        Back to Patients
    </a>
@endsection

@section('content')
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-xl max-w-4xl">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Update Patient Record</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Whoops! Something went wrong:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="post" action="{{ route('patients.update', $patient) }}" class="space-y-6" id="patientForm">
            @csrf
            @method('PUT') {{-- This is crucial for update operations --}}

            <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="first_name" name="first_name"
                               value="{{ old('first_name', $patient->first_name) }}"
                               class="input-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Patient's First Name" required/>
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="last_name" name="last_name"
                               value="{{ old('last_name', $patient->last_name) }}"
                               class="input-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Patient's Last Name" required/>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" id="phone" name="phone"
                               value="{{ old('phone', $patient->phone) }}"
                               class="input-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="e.g., +233241234567"/>
                    </div>
                    <div>
                        <label for="lhims_number" class="block text-sm font-medium text-gray-700 mb-1">LHIMS
                            Number</label>
                        <input type="text" id="lhims_number" name="lhims_number"
                               value="{{ old('lhims_number', $patient->lhims_number) }}"
                               class="input-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="LHIMS Number"/>
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" id="address" name="address"
                               value="{{ old('address', $patient->address) }}"
                               class="input-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Patient's Address"/>
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of
                            Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth"
                               value="{{ old('date_of_birth', $patient->dob?->format('Y-m-d')) }}"
                               class="input-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select id="gender" name="gender"
                                class="select-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="Female" {{ old('gender', $patient->gender) == 'Female' ? 'selected' : '' }}>
                                Female
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Demographics</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-1">Marital
                            Status</label>
                        <select id="marital_status" name="marital_status"
                                class="select-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Select Status</option>
                            @foreach(MaritalStatus::cases() as $marital_status)
                                <option value="{{ $marital_status->value }}"
                                    {{ old('marital_status', $patient->marital_status) == $marital_status->value ? 'selected' : '' }}>
                                    {{ $marital_status->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700 mb-1">Religion</label>
                        <input type="text" id="religion" name="religion"
                               value="{{ old('religion', $patient->religion) }}"
                               class="input-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="e.g., Christian, Muslim"/>
                    </div>
                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                        <input type="text" id="occupation" name="occupation"
                               value="{{ old('occupation', $patient->occupation) }}"
                               class="input-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="e.g., Teacher, Engineer"/>
                    </div>
                    <div>
                        <label for="nhis_status" class="block text-sm font-medium text-gray-700 mb-1">NHIS
                            Status</label>
                        <select id="nhis_status" name="nhis_status"
                                class="select-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @foreach(NhisStatus::cases() as $nhis_status)
                                <option value="{{ $nhis_status->value }}"
                                    {{ old('$nhis_status', $patient->nhis_status) == $nhis_status->value ? 'selected' : '' }}>
                                    {{ $nhis_status->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="edd" class="block text-sm font-medium text-gray-700 mb-1">EDD</label>
                        <input type="date" id="edd" name="edd"
                               value="{{ old('edd', $patient->edd?->format('Y-m-d')) }}"
                               class="input-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Expected Date of Delivery"/>
                    </div>
                    <div>
                        <label for="ega" class="block text-sm font-medium text-gray-700 mb-1">EGA</label>
                        <input type="text" id="ega" name="ega"
                               value="{{ old('ega', $patient->ega) }}"
                               class="input-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Expected Gestational Age" disabled/>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Medical History</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="medical_history" class="block text-sm font-medium text-gray-700 mb-1">
                            Past Medical & Surgical History
                        </label>
                        <input id="medical_history" name="medical_history"
                               value='{{ old('medical_history', json_encode($patient->medical_history ?: [])) }}'
                               placeholder="e.g., Hypertension; Appendectomy; Asthma"
                               class="tagify-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                    </div>

                    <div class="md:col-span-2">
                        <label for="obstetric_history" class="block text-sm font-medium text-gray-700 mb-1">
                            Obstetric History (if applicable)
                        </label>
                        <input id="obstetric_history" name="obstetric_history"
                               value="{{ old('obstetric_history', json_encode($patient->obstetric_history ?: [])) }}"
                               placeholder="e.g., Gravida 2; Para 1; Live birth 1"
                               class="tagify-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                    </div>

                    <div class="md:col-span-2">
                        <label for="drug_history" class="block text-sm font-medium text-gray-700 mb-1">
                            Drug History
                        </label>
                        <input id="drug_history" name="drug_history"
                               value="{{ old('drug_history', json_encode($patient->drug_history ?: [])) }}"
                               placeholder="e.g., Penicillin allergy; Ibuprofen; Metformin"
                               class="tagify-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                    </div>
                </div>
            </div>


            <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Social & Other Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="md:col-span-2">
                        <label for="social_history" class="block text-sm font-medium text-gray-700 mb-1">Social
                            History</label>
                        <input id="social_history" name="social_history"
                               value="{{ old('social_history', json_encode($patient->social_history ?: [])) }}"
                               placeholder="Smoking; Alcohol; Diet; Exercise; Living Situation..."
                               class="tagify-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                    </div>

                    <div class="md:col-span-2">
                        <label for="medical_records" class="block text-sm font-medium text-gray-700 mb-1">Medical
                            Records Notes</label>
                        <input id="medical_records" name="medical_records"
                               value="{{ old('medical_records', json_encode($patient->medical_records ?: [])) }}"
                               placeholder="Diabetes file; MRI scan; Prescriptions; Blood test"
                               class="tagify-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                    </div>

                    <div class="md:col-span-2">
                        <label for="current_medication" class="block text-sm font-medium text-gray-700 mb-1">Current
                            Medication</label>
                        <input id="current_medication" name="current_medication"
                               value="{{ old('current_medication', json_encode($patient->current_medication ?: [] )) }}"
                               placeholder="Paracetamol 500mg; Metformin; Lisinopril"
                               class="tagify-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                        <textarea id="notes" name="notes"
                                  class="textarea-field w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  rows="5"
                                  placeholder="Any other relevant notes...">{{ old('notes', $patient->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-8">
                <button type="submit"
                        class="inline-flex items-center px-8 py-3 bg-green-600 hover:bg-green-700 text-white text-lg font-semibold rounded-lg shadow-lg transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-save mr-3">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Update Patient Record
                </button>
            </div>
        </form>
    </div>

@endsection


@push("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const vitalsContainer = document.getElementById('vitalsContainer');
            const addVitalBtn = document.getElementById('addVitalBtn');
            const displayVitalsBtn = document.getElementById('displayVitalsBtn');
            const vitalsDisplayArea = document.getElementById('vitalsDisplayArea');
            const vitalsList = document.getElementById('vitalsList');
            const patientForm = document.getElementById('patientForm');
            const vitalsHiddenInput = document.getElementById('vitalsHiddenInput');

            const medicationsContainer = document.getElementById('medicationsContainer');
            const addMedicationBtn = document.getElementById('addMedicationBtn');
            const displayMedicationsBtn = document.getElementById('displayMedicationsBtn');
            const medicationsDisplayArea = document.getElementById('medicationsDisplayArea');
            const medicationsList = document.getElementById('medicationsList');
            const medicationsHiddenInput = document.getElementById('medicationsHiddenInput');


            // Function to create a new vital entry row
            function createVitalEntry(vital = {name: '', value: '', unit: ''}) {
                const vitalEntryDiv = document.createElement('div');
                if (vitalEntryDiv) {
                    vitalEntryDiv.className = 'grid grid-cols-3 gap-4 items-end bg-white p-4 rounded-md shadow-sm border border-gray-200';
                    vitalEntryDiv.innerHTML = `
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Vital Name</label>
                        <input type="text" class="vital-name input-field w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm" placeholder="e.g., Blood Pressure" value="${vital.name}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Value</label>
                        <input type="text" class="vital-value input-field w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm" placeholder="e.g., 120/80" value="${vital.value}">
                    </div>
                    <div class="flex items-center">
                        <div class="flex-grow">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Unit</label>
                            <input type="text" class="vital-unit input-field w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm" placeholder="e.g., mmHg" value="${vital.unit}">
                        </div>
                        <button type="button" class="remove-vital-btn ml-3 p-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>
                `;
                    vitalsContainer.appendChild(vitalEntryDiv);
                }

                // Add event listener for remove button
                vitalEntryDiv.querySelector('.remove-vital-btn').addEventListener('click', function () {
                    vitalEntryDiv.remove();
                    updateVitalsDisplay(); // Update display when a vital is removed
                });
            }

            // Function to update the displayed vitals
            function updateVitalsDisplay() {
                vitalsList.innerHTML = ''; // Clear previous entries
                const vitalEntries = [];
                vitalsContainer.querySelectorAll('.vital-name').forEach((nameInput, index) => {
                    const valueInput = vitalsContainer.querySelectorAll('.vital-value')[index];
                    const unitInput = vitalsContainer.querySelectorAll('.vital-unit')[index];

                    const name = nameInput.value.trim();
                    const value = valueInput.value.trim();
                    const unit = unitInput.value.trim();

                    // Only add to display if at least one field is not empty
                    if (name || value || unit) {
                        vitalEntries.push({name, value, unit});
                        const listItem = document.createElement('li');
                        listItem.className = 'mb-1 text-gray-700';
                        listItem.textContent = `${name}: ${value} ${unit}`;
                        vitalsList.appendChild(listItem);
                    }
                });

                // Show/hide display area based on whether there are vitals to show
                if (vitalEntries.length > 0) {
                    vitalsDisplayArea.classList.remove('hidden');
                } else {
                    vitalsDisplayArea.classList.add('hidden');
                }
            }


            // Function to create a new medication entry row
            function createMedicationEntry(medication = {drug: '', frequency: '', duration: ''}) {
                const medicationEntryDiv = document.createElement('div');
                medicationEntryDiv.className = 'grid grid-cols-3 gap-4 items-end bg-white p-4 rounded-md shadow-sm border border-gray-200';
                medicationEntryDiv.innerHTML = `
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Drug Name</label>
                        <input type="text" class="medication-drug input-field w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm" placeholder="e.g., Paracetamol" value="${medication.drug}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Frequency</label>
                        <input type="text" class="medication-frequency input-field w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm" placeholder="e.g., Twice daily" value="${medication.frequency}">
                    </div>
                    <div class="flex items-center">
                        <div class="flex-grow">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Duration</label>
                            <input type="text" class="medication-duration input-field w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm" placeholder="e.g., 7 days" value="${medication.duration}">
                        </div>
                        <button type="button" class="remove-medication-btn ml-3 p-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>
                `;
                medicationsContainer.appendChild(medicationEntryDiv);

                // Add event listener for remove button
                medicationEntryDiv.querySelector('.remove-medication-btn').addEventListener('click', function () {
                    medicationEntryDiv.remove();
                    updateMedicationsDisplay(); // Update display when a medication is removed
                });
            }

            // Function to update the displayed medications
            function updateMedicationsDisplay() {
                medicationsList.innerHTML = ''; // Clear previous entries
                const medicationEntries = [];
                medicationsContainer.querySelectorAll('.medication-drug').forEach((drugInput, index) => {
                    const frequencyInput = medicationsContainer.querySelectorAll('.medication-frequency')[index];
                    const durationInput = medicationsContainer.querySelectorAll('.medication-duration')[index];

                    const drug = drugInput.value.trim();
                    const frequency = frequencyInput.value.trim();
                    const duration = durationInput.value.trim();

                    // Only add to display if at least one field is not empty
                    if (drug || frequency || duration) {
                        medicationEntries.push({drug, frequency, duration});
                        const listItem = document.createElement('li');
                        listItem.className = 'mb-1 text-gray-700';
                        listItem.textContent = `${drug} (Freq: ${frequency}, Dur: ${duration})`;
                        medicationsList.appendChild(listItem);
                    }
                });

                // Show/hide display area based on whether there are medications to show
                if (medicationEntries.length > 0) {
                    medicationsDisplayArea.classList.remove('hidden');
                } else {
                    medicationsDisplayArea.classList.add('hidden');
                }
            }


            // Add initial vital entry if old data exists
            const oldVitals = "{{ old('vitals') }}";
            if (oldVitals) {
                try {
                    const parsedVitals = JSON.parse(oldVitals.replace(/&quot;/g, '"')); // Handle HTML entities
                    parsedVitals.forEach(vital => createVitalEntry(vital));
                    updateVitalsDisplay(); // Display initial vitals
                } catch (e) {
                    console.error("Error parsing old vitals data:", e);
                }
            } else {
                // Add one empty vital entry by default if no old data
                // createVitalEntry();
            }

            // Event listener for "Add Vital" button
            if (addVitalBtn)
                addVitalBtn.addEventListener('click', function () {
                    createVitalEntry();
                });

            // Event listener for "Display Vitals" button
            if (displayVitalsBtn)
                displayVitalsBtn.addEventListener('click', function () {
                    updateVitalsDisplay();
                });

            // Event listener for "Add Medication" button
            if (addMedicationBtn)
                addMedicationBtn.addEventListener('click', function () {
                    createMedicationEntry();
                });

            // Event listener for "Display Medications" button
            if (displayMedicationsBtn)
                displayMedicationsBtn.addEventListener('click', function () {
                    updateMedicationsDisplay();
                });

            // Event listener for form submission
            if (patientForm) {
                patientForm.addEventListener('submit', function (event) {
                    const vitalEntries = [];
                    vitalsContainer.querySelectorAll('.vital-name').forEach((nameInput, index) => {
                        const valueInput = vitalsContainer.querySelectorAll('.vital-value')[index];
                        const unitInput = vitalsContainer.querySelectorAll('.vital-unit')[index];

                        // Only add if at least one field is not empty
                        if (nameInput.value.trim() || valueInput.value.trim() || unitInput.value.trim()) {
                            vitalEntries.push({
                                name: nameInput.value.trim(),
                                value: valueInput.value.trim(),
                                unit: unitInput.value.trim()
                            });
                        }
                    });
                    vitalsHiddenInput.value = JSON.stringify(vitalEntries);

                    const medicationEntries = [];
                    medicationsContainer.querySelectorAll('.medication-drug').forEach((drugInput, index) => {
                        const frequencyInput = medicationsContainer.querySelectorAll('.medication-frequency')[index];
                        const durationInput = medicationsContainer.querySelectorAll('.medication-duration')[index];

                        if (drugInput.value.trim() || frequencyInput.value.trim() || durationInput.value.trim()) {
                            medicationEntries.push({
                                drug: drugInput.value.trim(),
                                frequency: frequencyInput.value.trim(),
                                duration: durationInput.value.trim()
                            });
                        }
                    });
                    medicationsHiddenInput.value = JSON.stringify(medicationEntries);
                });
            }
        });
    </script>
@endpush
