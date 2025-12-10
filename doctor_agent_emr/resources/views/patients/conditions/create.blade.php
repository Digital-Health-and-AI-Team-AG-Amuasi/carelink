@extends('layouts.app')

@section('title', 'Record Patient Condition')

@section('content')
    <div class="kt-container-fixed">
        <div class="grid gap-5 lg:gap-7.5">

            <form method="post" action="{{route('visits.conditions.store', $visit)}}" class="kt-form">
                @csrf

                <div class="kt-form-item">
                    <label class="kt-form-label">Patient</label>
                    <div class="kt-form-control">
                        <select name="patient_id" id="patient_id" class="kt-select">
                            <option class="kt-select-option" selected value="">Please select a patient</option>
                            @foreach($patients as $patient)
                                <option class="kt-select-option" value="{{ $patient->id }}" >{{ $patient->first_name . ' ' . $patient->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">Diagnosis</label>
                    <div class="kt-form-control">
                        <input name="diagnosis" class="kt-input" placeholder="Diagnosis">
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">Description</label>
                    <div class="kt-form-control">
                        <textarea name="description" class="kt-input" placeholder="Description of condition"></textarea>
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">Start Date</label>
                    <div class="kt-form-control">
                        <input type="date" name="start_date" value="" class="kt-input" />
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">End Date</label>
                    <div class="kt-form-control">
                        <input type="date" name="end_date" value="" class="kt-input" />
                    </div>
                </div>

                <div class="kt-form-item new-pregnancy-options hidden">
                    <label class="kt-form-label">Estimated Date of Delivery</label>
                    <div class="kt-form-control">
                        <input type="date" name="edd" value="{{ old('edd') }}" class="kt-input" placeholder="Estimated Date of Delivery" />
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">Additional notes</label>
                    <div class="kt-form-control">
                        <textarea name="notes" class="kt-input" placeholder="..."></textarea>
                    </div>
                </div>

                <div class="kt-form-item">
                    <div class="kt-form-control">
                        <p class="kt-form-label">Status: </p>

                        <input type="radio" id="is_active_yes" name="is_active" value="true">
                        <label for="is_active_yes">Ongoing</label>

                        <input type="radio" id="is_active_no" name="is_active" value="false">
                        <label for="is_active_no">Resolved</label>
                    </div>
                </div>

                <input type="submit" class="kt-btn" value="Create Condition" name="save">
            </form>
        </div>
    </div>
@endsection
