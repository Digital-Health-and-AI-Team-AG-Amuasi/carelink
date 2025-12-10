@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('title', 'Patients')

@section('action-links')
    <a class="kt-btn kt-btn-primary bg-gradient-to-r from-green-500 to-green-400 hover:from-green-600 hover:to-green-500 text-white font-semibold py-2 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200"
       href="{{ route('patients.create') }}">
        Add Patient
    </a>
@endsection

@section('content')
    <main class="grow bg-gray-50 min-h-screen" id="content" role="content">
        <div class="container mx-auto px-4 max-w-7xl pt-8 pb-12">
            <div class="space-y-8">
                <!-- Header Section -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Patient Management</h1>
                        <p class="text-gray-600 mt-2 text-sm">Effortlessly manage patient records and information</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('patients.create') }}"
                           class="inline-flex items-center justify-center bg-gradient-to-r from-green-500 to-green-400 hover:from-green-600 hover:to-green-500 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Patient
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Total Patients Card -->
                     <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex items-center justify-between focus-within:ring-2 focus-within:ring-green-500 focus-within:ring-offset-2 hover:-translate-y-1">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-500">Total Patients</p>
            <h2 class="text-2xl font-bold text-gray-900 mt-1 sm:text-3xl">{{ $patients->total() ?? 0 }}</h2>
        </div>
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white transform transition-transform duration-300 hover:scale-110">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor">
                <g id="patient">
                    <path d="M7 24H4.9a.9.9 0 0 1-.9-.9V8.9a.9.9 0 0 1 .9-.9H21a1 1 0 0 0 0-2H4.9A2.9 2.9 0 0 0 2 8.9v14.2A2.9 2.9 0 0 0 4.9 26H7a1 1 0 0 0 0-2zM27.1 6H25a1 1 0 0 0 0 2h2.1a.9.9 0 0 1 .9.9v14.2a.9.9 0 0 1-.9.9H11a1 1 0 0 0 0 2h16.1a2.9 2.9 0 0 0 2.9-2.9V8.9A2.9 2.9 0 0 0 27.1 6z"/>
                    <path d="M14 21v-2h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-2v-2a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v2H6a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h2v2a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1zm-2-3v2h-2v-2a1 1 0 0 0-1-1H7v-2h2a1 1 0 0 0 1-1v-2h2v2a1 1 0 0 0 1 1h2v2h-2a1 1 0 0 0-1 1zM26 11h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2zM26 15h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2zM26 19h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2z"/>
                </g>
            </svg>
        </div>
    </div>

    <!-- Total Visits Card -->
    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex items-center justify-between focus-within:ring-2 focus-within:ring-green-500 focus-within:ring-offset-2 hover:-translate-y-1">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-500">Total Visits</p>
            <h2 class="text-2xl font-bold text-gray-900 mt-1 sm:text-3xl">{{ auth()->user()->visits()->count() }}</h2>
        </div>
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white transform transition-transform duration-300 hover:scale-110">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 4h16a2 2 0 0 1 2 2v38h0-20 0V6a2 2 0 0 1 2-2zM24 8v8M28 12h-8M18 19h4M26 19h4M18 24h4M26 24h4M18 29h4M26 29h4M35 24h3M35 28h3"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M22 33h3a2 2 0 0 1 2 2v9h0-7 0v-9a2 2 0 0 1 2-2zM34 14h6a2 2 0 0 1 2 2v28h0-8 0V14h0zM10 24h4M10 28h4M8 14h6v30h0-8 0V16a2 2 0 0 1 2-2z"/>
            </svg>
        </div>
    </div>

                    <!-- Emergency Cases Card -->
                    <div id="emergency-card" class="relative bg-white border-l-4 border-red-500 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex flex-col justify-between opacity-80">
                        <!-- Beta Badge -->
                        <div class="absolute -top-3 -right-3 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm transform rotate-12">
                            Beta
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Priority Cases</p>
                            <h2 id="emergency-count" class="text-2xl font-bold text-red-600 mt-1">0 Patients</h2>
                            <div class="mt-2 space-y-1">
                                <p class="text-sm text-gray-600">AI flagged from WhatsApp</p>
                                <p id="emergency-last" class="text-xs text-gray-500 italic">Last alert: --</p>
                            </div>
                        </div>
                        <a href="/emergency"
                           class="mt-4 inline-flex items-center bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                           aria-label="Review priority cases">
                            Review Now →
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                <x-flash-messages/>

                <!-- Search Bar -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="p-6">
                        <div class="relative w-full">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text"
                                   placeholder="Search patients by name, LHIMS number, or phone..."
                                   class="pl-10 w-full h-12 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 placeholder-gray-400 transition-all duration-200">
                        </div>
                    </div>
                </div>

                <!-- Patient Records Table -->
               <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center gap-4">
            <svg class="w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-900">Patient Records</h3>
        </div>
        <p class="text-sm text-gray-600 mt-2">View and manage all patient information</p>
    </div>

    <!-- Table -->
    <div class="p-6">
        <div class="rounded-lg border border-gray-200 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">
                            Patient Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">
                            LHIMS Number
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">
                            Phone
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">
                            Date of Birth
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-900 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($patients->count() > 0)
                        @foreach($patients as $patient)
                            <tr class="hover:bg-gray-50 transition-all duration-200 focus-within:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <a class="text-sm font-semibold text-gray-900 hover:text-green-600 transition-colors duration-200 focus:outline-none focus:underline"
                                               href="{{ route('patients.review', compact('patient')) }}">
                                                {{ $patient->first_name ?? 'N/A' }} {{ $patient->last_name ?? 'N/A' }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono text-gray-800">
                                        {{ $patient->lhims_number ?? 'N/A' }}
                                    </code>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $patient->phone ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                    @if(!empty($patient->dob))
                                        {{ Carbon::parse($patient->dob)->format('M j, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2">
                                    <a href="{{ route('patients.edit', $patient->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-gray-600 hover:text-green-600 bg-gray-50 hover:bg-green-50 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('patients.destroy', $patient->id) }}"
                                          method="POST" class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this patient?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-red-500 hover:text-red-600 bg-gray-50 hover:bg-red-50 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-600">No patients found</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="px-6 py-3 text-xs font-semibold text-gray-600 text-right bg-gray-50">
                            Total Patients: <span class="font-bold">{{ $patients->total() ?? 0 }}</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-6 px-6">
            {{ $patients->links('pagination::tailwind') }}
        </div>
    </div>
</div>
            </div>
        </div>
    </main>
@endsection

@push("scripts")
    <script>
        let poller = setInterval(async () => {
            try {
                const res = await fetch('/api/get_emergency_flags', {
                    headers: {
                        'Api-Key': 'W11JUFcsYd6s80htOBy3VBpmQ0ombRSSES9kZqKFsp9qdrNzS1PDnRRh8ePD',
                        'Accept': 'application/json'
                    }
                });
                const json = await res.json();

                if (!json.success) return;

                const flags = json.data; // assuming this is an array of emergency flags


                // Update patient count
                const countEl = document.getElementById('emergency-count');
                countEl.textContent = `${flags.length} Patient${flags.length !== 1 ? 's' : ''}`;

                // Update last alert timestamp
                const lastEl = document.getElementById('emergency-last');
                if (flags.length > 0) {
                    // Assuming each flag has a 'timestamp' property
                    const latest = flags.reduce((a, b) => new Date(a.timestamp) > new Date(b.timestamp) ? a : b);
                    const timeAgo = timeSince(new Date(latest.timestamp));
                    lastEl.textContent = `Last alert: ${timeAgo} ago`;
                } else {
                    lastEl.textContent = `Last alert: --`;
                }

            } catch (err) {
                console.error('Polling error:', err);
            }
        }, 5000); // poll every 5 seconds

        // Helper function to display human-readable time ago
        function timeSince(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            let interval = Math.floor(seconds / 3600);
            if (interval >= 1) return interval + " hour" + (interval > 1 ? "s" : "");
            interval = Math.floor(seconds / 60);
            if (interval >= 1) return interval + " min" + (interval > 1 ? "s" : "");
            return Math.floor(seconds) + " sec";
        }

    </script>
@endpush
