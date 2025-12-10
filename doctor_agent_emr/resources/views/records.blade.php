@extends('layouts.app')

@section('content')

    <!-- Main Content -->
    <div id="mainContent" class="space-y-6 container p-6 overflow-y-auto h-screen ml-64">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Medical Records</h1>
                <p class="text-gray-500">View and manage patient medical records</p>
            </div>
            <button id="new-record-btn"
                    class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Record
            </button>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border">
            <div class="pb-3">
                <h2 class="text-xl font-semibold">Patient Records</h2>
                <div class="flex items-center gap-2 mt-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"></path>
                        </svg>
                        <input type="search" placeholder="Search records..."
                               class="w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
                    </div>
                    <button class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-100">Filter</button>
                </div>
            </div>

            <div class="overflow-x-auto mt-4">
                <table class="w-full border-collapse border rounded-lg">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Patient Name</th>
                        <th class="px-4 py-2 text-left">Recent Appointment</th>
                        <th class="px-4 py-2 text-left">Conditions</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                    </thead>

                    <tbody id="records-table-body">
                    @foreach($patients_data as $patient_data)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{$patient_data['patient']['name']}}</td>
{{--                            <td class="px-4 py-2">--}}
{{--                                {{$patient_data['encounter'][0]['reason']}}--}}
{{--                            </td>--}}
                            @if ($patient_data['encounter']->isEmpty())
                                <td class="px-4 py-2">N/A</td>
                            @else
                                <td class="px-4 py-2">{{ $patient_data['encounter'][0]['reason'] }}</td>
                            @endif
                            <td class="px-4 py-2 max-w-xs truncate">
                                @if ($patient_data['conditions']->isNotEmpty())
                                    @foreach($patient_data['conditions'] as $condition)
                                        {{ $condition["diagnosis"] }}@if (!$loop->last), @endif
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>

                            {{--                                <td class="px-4 py-2">Dr. Smith</td>--}}
                            <td class="px-4 py-2 text-right">
                                <form action="{{ route('select.patient') }}" method="POST">
                                    <input type="hidden" name="patient_id" value="{{$patient_data['patient']['id']}}">
                                    <button type="submit"
                                            class="flex items-center px-3 py-1 border rounded-lg text-gray-700 hover:bg-gray-100">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-3-3v6m9 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"></path>
                                        </svg>
                                        View
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Overlay -->
    <div id="modal-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg w-full max-w-lg p-6 shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">New Medical Record</h3>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="record-form" class="space-y-4" action="{{ route('add.patient') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first-name" class="block text-sm font-medium text-gray-700">First Names</label>
                        <input type="text" id="first-name" name="first_name" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                    </div>

                    <div>
                        <label for="last-name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" id="last-name" name="last_name" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="date-of-birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" id="date-of-birth" name="date_of_birth" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                        <select id="gender" name="gender" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                            <option selected value="female">Female</option>
                            <option value="male">Male</option>
                        </select>
                    </div>
                </div>

                <div style="margin-right: 175px;">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="text" id="phone" name="phone" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" id="address" name="address" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                </div>


                {{--                <div>--}}
                {{--                    <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnosis</label>--}}
                {{--                    <input type="text" id="diagnosis" name="diagnosis" required--}}
                {{--                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">--}}
                {{--                </div>--}}

                {{--                <div>--}}
                {{--                    <label for="treatment" class="block text-sm font-medium text-gray-700">Treatment</label>--}}
                {{--                    <textarea id="treatment" name="treatment" rows="3" required--}}
                {{--                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2"></textarea>--}}
                {{--                </div>--}}

                {{--                <div>--}}
                {{--                    <label for="doctor" class="block text-sm font-medium text-gray-700">Doctor</label>--}}
                {{--                    <select id="doctor" name="doctor" required--}}
                {{--                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">--}}
                {{--                        <option value="">Select doctor</option>--}}
                {{--                        <option value="Dr. Smith">Dr. Smith</option>--}}
                {{--                        <option value="Dr. Williams">Dr. Williams</option>--}}
                {{--                        <option value="Dr. Adams">Dr. Adams</option>--}}
                {{--                        <option value="Dr. Johnson">Dr. Johnson</option>--}}
                {{--                        <option value="Dr. Garcia">Dr. Garcia</option>--}}
                {{--                    </select>--}}
                {{--                </div>--}}

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                    <textarea id="notes" name="notes" rows="2"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancel-button"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save Record
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
    // DOM elements
    const mainContent = document.getElementById('mainContent');

    const newRecordBtn = document.getElementById('new-record-btn');
    const modalOverlay = document.getElementById('modal-overlay');
    const closeModalBtn = document.getElementById('close-modal');
    const cancelBtn = document.getElementById('cancel-button');
    const recordForm = document.getElementById('record-form');
    const recordsTableBody = document.getElementById('records-table-body');

    // Function to open modal
    function openModal() {
    modalOverlay.classList.remove('hidden');
    }

    // Function to close modal
    function closeModal() {
    modalOverlay.classList.add('hidden');
    recordForm.reset();
    }

    // Function to add a new record to the table
    function addRecordToTable(record) {
    const row = document.createElement('tr');
    row.className = 'border-t';

    row.innerHTML = `
    <td class="px-4 py-2">${record.patientName}</td>
    <td class="px-4 py-2">${record.date}</td>
    <td class="px-4 py-2">${record.diagnosis}</td>
    <td class="px-4 py-2 max-w-xs truncate">${record.treatment}</td>
    <td class="px-4 py-2">${record.doctor}</td>
    <td class="px-4 py-2 text-right">
        <button class="flex items-center px-3 py-1 border rounded-lg text-gray-700 hover:bg-gray-100">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-3-3v6m9 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"></path>
            </svg>
            View
        </button>
    </td>
    `;

    // Insert at the top of the table
    if (recordsTableBody.firstChild) {
    recordsTableBody.insertBefore(row, recordsTableBody.firstChild);
    } else {
    recordsTableBody.appendChild(row);
    }
    }

    // Event listeners
    newRecordBtn.addEventListener('click', openModal);
    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside of it
    modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) {
    closeModal();
    }
    });
    </script>

    {{--        // Form submission--}}
    {{--        recordForm.addEventListener('submit', (e) => {--}}
    {{--            e.preventDefault();--}}

    {{--            // Get form data--}}
    {{--            const patientName = document.getElementById('patient-name').value;--}}
    {{--            const date = document.getElementById('record-date').value;--}}
    {{--            const diagnosis = document.getElementById('diagnosis').value;--}}
    {{--            const treatment = document.getElementById('treatment').value;--}}
    {{--            const doctor = document.getElementById('doctor').value;--}}
    {{--            const notes = document.getElementById('notes').value;--}}

    {{--            // Create record object--}}
    {{--            const newRecord = {--}}
    {{--                patientName,--}}
    {{--                date,--}}
    {{--                diagnosis,--}}
    {{--                treatment,--}}
    {{--                doctor,--}}
    {{--                notes--}}
    {{--            };--}}

    {{--            // Add to table--}}
    {{--            addRecordToTable(newRecord);--}}

    {{--            // Close modal--}}
    {{--            closeModal();--}}
    {{--        });--}}
@endpush
