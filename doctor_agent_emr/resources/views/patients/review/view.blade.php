@extends('layouts.app')

@section('title', sprintf('Patient Details: %s %s', $patient->first_name, $patient->last_name))

@section('action-links')
    <a class="kt-btn kt-btn-primary" href="{{ route('patients.review', $patient->id) }}">
        Reviews
    </a>
    <a class="kt-btn kt-btn-danger" href="{{ route('patients.index', $patient->id) }}">
        Back to Patients
    </a>
@endsection

@section('content')
    <div class="kt-container-fixed">

    </div>
@endsection
