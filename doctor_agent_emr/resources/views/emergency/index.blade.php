@extends('layouts.app')

@section('title', 'Priority Cases')

@section('content')
    <main class="grow bg-gray-50 min-h-screen" id="content" role="content">
        <div class="container mx-auto px-4 max-w-7xl pt-8 pb-12">
            <div class="space-y-8">
                <!-- Header Section -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Priority Cases</h1>
                        <p class="text-gray-600 mt-2 text-sm">Review and manage urgent patient situations identified by AI</p>
                    </div>
{{--                    <div class="flex gap-3">--}}
{{--                        <a href=""--}}
{{--                           class="inline-flex items-center justify-center bg-gradient-to-r from-green-500 to-green-400 hover:from-green-600 hover:to-green-500 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">--}}
{{--                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>--}}
{{--                            </svg>--}}
{{--                            Add Manual Case--}}
{{--                        </a>--}}
{{--                    </div>--}}
                </div>

                <!-- Priority Cases Grid (Centered) -->
                <div class="flex justify-center">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full lg:w-auto lg:max-w-full">
                        @forelse($priorityCases as $case)
                            @php
                                $isNew = $case['is_new'] ?? false;
                                $isResolved = false;

                                // Button classes for 'View Patient'
                                $buttonClass = $isResolved
                                    ? 'bg-gray-400 hover:bg-gray-500 cursor-not-allowed'
                                    : 'bg-green-500 hover:bg-green-600 focus:ring-red-500';

                                $shadowClass = $isNew ? 'shadow-xl ring-2 ring-red-500/50' : 'shadow-md';
                                $hoverClass = $isNew ? 'hover:shadow-2xl hover:ring-red-500/80' : 'hover:shadow-xl';
                            @endphp
                            <div class="relative bg-white rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 {{ $shadowClass }} {{ $hoverClass }}">
{{--                                @if($isNew)--}}
{{--                                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg transform rotate-6 border-2 border-white">--}}
{{--                                        New--}}
{{--                                    </span>--}}
{{--                                @endif--}}

                                <div>
{{--                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Case #{{ $case['id'] }}</h3>--}}
                                    <p class="text-xl font-bold text-gray-900 mt-1 mb-3">{{ $case['patient']['first_name'] . ' ' . $case['patient']['last_name'] }}</p>

                                    <div class="text-sm space-y-2">
                                        <p class="font-medium text-gray-700">Details:</p>
                                        <p class="text-gray-600 leading-relaxed text-sm">{{ $case['reason'] }}</p>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-gray-100">
                                    <a href="{{ route('patients.review', ['patient' => $case['patient_id']]) }}"
                                       class="w-full inline-flex items-center justify-center text-sm font-semibold px-3 py-2 rounded-lg text-white shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $buttonClass }}"
                                       >
                                        See Patient
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-200 shadow-sm p-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">All Clear!</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    No urgent priority cases have been flagged by the AI at this time.
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('patients.index') }}"
                                       class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                        Go to Patient List
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>


            </div>
        </div>
    </main>
@endsection
