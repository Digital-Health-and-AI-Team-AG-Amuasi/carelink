@extends('layouts.app')

@section('title', 'CareEMR - Dashboard')

@section('content')

    <!-- Main Content -->
    <main id="mainContent" class="flex-1 p-6 overflow-y-auto h-screen ml-10 ml-64">
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
                <p class="text-gray-700 font-medium italic mt-2 pr-8">Welcome back, Dr. {{ $user->first_name . " " . $user->last_name}}</p>
            </div>

            <hr style="border: 1px solid #a9a9a9;">

            <div class="grid gap-4 grid-cols-2">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-semibold">Total Patients</h2>
                        <i class="h-4 w-4 text-muted-foreground">👥</i>
                    </div>
                    <div class="text-2xl font-bold mt-4">25</div>
                    <p class="text-xs text-muted-foreground">+4 new this month</p>
                </div>

                <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-semibold">Today's Appointments</h2>
                        <i class="h-4 w-4 text-muted-foreground">📅</i>
                    </div>
                    <div class="text-2xl font-bold mt-4">12</div>
                    <p class="text-xs text-muted-foreground">2 urgent consultations</p>
                </div>

                <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-semibold">Pending Lab Results</h2>
                        <i class="h-4 w-4 text-muted-foreground">📋</i>
                    </div>
                    <div class="text-2xl font-bold mt-4">34</div>
                    <p class="text-xs text-muted-foreground">3 require immediate review</p>
                </div>

                <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-semibold">Unread Messages</h2>
                        <i class="h-4 w-4 text-muted-foreground">💬</i>
                    </div>
                    <div class="text-2xl font-bold mt-4">57</div>
                    <p class="text-xs text-muted-foreground">1 urgent message</p>
                </div>
            </div>
        </div>
    </main>

@endsection

