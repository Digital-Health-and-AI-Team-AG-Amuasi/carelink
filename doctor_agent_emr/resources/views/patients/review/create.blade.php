@extends('layouts.app')

@section('title', 'Add Patient Record')

@section('content')
    <div class="kt-container-fixed">
        <div class="grid gap-5 lg:gap-7.5">

            @if ($disableForm)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mb-4">
                    The patient has no visits. Please <a class="underline font-medium text-yellow-800" href="{{ route('patients.create-visit', $patient) }}">create a visit</a> first.
                </div>
            @endif

            <form method="post" action="{{route('patients.store-review', compact('patient'))}}" class="kt-form">
                @csrf

                <div class="kt-form-item">
                    <label class="kt-form-label">Issues</label>
                    <div class="kt-form-control">
                        <textarea name="issues" class="kt-input" placeholder="Any issues since last visit" {{ $disableForm ? 'disabled' : '' }}></textarea>
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">Updates</label>
                    <div class="kt-form-control">
                        <textarea name="updates" class="kt-input" placeholder="Updates since last meeting" {{ $disableForm ? 'disabled' : '' }}></textarea>
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">On Direct Questions</label>
                    <div class="kt-form-control">
                        <textarea name="on_direct_questions" class="kt-input" placeholder="On Direct Questions" {{ $disableForm ? 'disabled' : '' }}></textarea>
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">Current Complains</label>
                    <div class="kt-form-control">
                        <textarea name="current_complains" class="kt-input" placeholder="Current Complains" {{ $disableForm ? 'disabled' : '' }}></textarea>
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">On Examinations</label>
                    <div class="kt-form-control">
                        <textarea name="on_examinations" class="kt-input" placeholder="On Examinations" {{ $disableForm ? 'disabled' : '' }}></textarea>
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">Vitals</label>
                    <div class="kt-form-control">
                        <textarea name="vitals" class="kt-input" placeholder="Vitals" {{ $disableForm ? 'disabled' : '' }}></textarea>
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">Labs</label>
                    <div class="kt-form-control">
                        <textarea name="labs" class="kt-input" placeholder="Lab" {{ $disableForm ? 'disabled' : '' }}></textarea>
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">Impression</label>
                    <div class="kt-form-control">
                        <textarea name="impression" class="kt-input" placeholder="Impression" {{ $disableForm ? 'disabled' : '' }}></textarea>
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">Plan</label>
                    <div class="kt-form-control">
                        <textarea name="plan" class="kt-input" placeholder="Plan" {{ $disableForm ? 'disabled' : '' }}></textarea>
                    </div>
                </div>

                <input type="submit" class="kt-btn" value="Create Review" name="save" {{ $disableForm ? 'disabled' : '' }}>
            </form>
        </div>
    </div>
@endsection
