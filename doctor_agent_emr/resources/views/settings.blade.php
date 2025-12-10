@extends('layouts.app')

@section('title', 'CareEMR - Settings')

@section('content')
    <div id="mainContent" class="container p-6 flex-1 overflow-y-auto h-screen ml-64">
        <h1 class="text-3xl font-bold tracking-tight">Settings</h1>
        <p class="text-gray-600">Manage your account settings and preferences</p>

        @if (session('success'))
            <div class="bg-green-100 border-t-4 border-green-500 text-green-700 p-4 mb-4">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-t-4 border-red-500 text-red-700 p-4 mb-4">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="mt-6">
            <div class="border-b flex space-x-6">
                <button class="py-2 px-4 border-b-2 border-blue-500 font-medium">Profile</button>
                <button class="py-2 px-4 text-gray-600 hover:text-gray-900">Notifications</button>
                <button class="py-2 px-4 text-gray-600 hover:text-gray-900">Security</button>
                <button class="py-2 px-4 text-gray-600 hover:text-gray-900">System</button>
            </div>
            <div class="mt-4">
                <div class="bg-white shadow-md rounded-lg p-6 space-y-4">
                    <h2 class="text-xl font-semibold">Profile Information</h2>
                    <p class="text-gray-600">Update your personal information</p>

                    <form  id="appointment-form" class="space-y-4" action="{{ route('profile_update') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium">First Name</label>
                                <input name="first_name" type="text" id="first_name" value="{{ $user->first_name }}"
                                       class="mt-1 block w-full p-2 border rounded">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium">Last Name</label>
                                <input name="last_name" type="text" id="last_name" value="{{ $user->last_name }}"
                                       class="mt-1 block w-full p-2 border rounded">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium">Email</label>
                            <input name="email" type="email" id="email" value="{{ $user->email }}"
                                   class="mt-1 block w-full p-2 border rounded">
                        </div>
                        <div>
                            <label for="specialty" class="block text-sm font-medium">Specialty</label>
                            <input type="text" id="specialty" value="Obstetrician"
                                   class="mt-1 block w-full p-2 border rounded">
                        </div>
                        <div>
                            <label for="license" class="block text-sm font-medium">Medical License Number</label>
                            <input type="text" id="license" value="MD12345678"
                                   class="mt-1 block w-full p-2 border rounded">
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        const mainContent = document.getElementById('mainContent');
    </script>
@endpush

{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>--}}
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">--}}





