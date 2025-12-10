@use(App\Enums\PregnancyState)
@extends('layouts.app')

@section('title', sprintf('Create Visit for %s %s', $patient->first_name, $patient->last_name))

@section('content')
    <div class="kt-container-fixed">
        <div class="grid gap-5 lg:gap-7.5">

            <form method="post" action="{{route('patients.save-visit', $patient)}}" class="kt-form">
                @csrf

                <div class="kt-form-item">
                    <label class="kt-form-label">Reason for visit</label>
                    <div class="kt-form-control">
                        <textarea name="reason" class="kt-input" placeholder="Reason for visit">{{ old('reason') }}</textarea>
                    </div>
                </div>

                <div class="kt-form-item">
                    <label class="kt-form-label">Pregnancy State</label>
                    <div class="kt-form-control">
                        <select name="pregnancy_state" id="pregnancy_state" class="kt-select">
                            <option class="kt-select-option" value="{{ PregnancyState::Old->value }}" @selected(old('pregnancy_state') === PregnancyState::Old->value)>{{ PregnancyState::Old->value }}</option>
                            <option class="kt-select-option" value="{{ PregnancyState::New->value }}" @selected(old('pregnancy_state') === PregnancyState::New->value)>{{ PregnancyState::New->value }}</option>
                        </select>
                    </div>
                </div>

                <div class="kt-form-item new-pregnancy-options hidden">
                    <label class="kt-form-label">Estimated Date of Delivery</label>
                    <div class="kt-form-control">
                        <input type="date" name="edd" value="{{ old('edd') }}" class="kt-input" placeholder="Estimated Date of Delivery" />
                    </div>
                </div>

                <div class="kt-form-item old-pregnancy-options hidden">
                    <label class="kt-form-label">Pregnancy</label>
                    <div class="kt-form-control">
                        <select name="pregnancy" class="kt-select">
                            <option class="kt-select-option" value="">--Please Select--</option>
                            @foreach($patient->pregnancies as $pregnancy)
                                <option class="kt-select-option" value="{{ $pregnancy->id }}" @selected(old('pregnancy') == $pregnancy->id)>Pregnancy with EDD: {{ $pregnancy->edd }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <input type="submit" class="kt-btn" value="Save" name="save">
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // when the page loads, if the pregnancy type is "new", show the new pregnancy options
        // when the user toggles, show the correct pregnancy options

        document.addEventListener('DOMContentLoaded', function () {
            $('#pregnancy_state').change(function() {
                if ($(this).val() === @js(PregnancyState::New->value)) {
                    $('.new-pregnancy-options').removeClass('hidden');
                    $('.old-pregnancy-options').addClass('hidden');
                } else {
                    $('.old-pregnancy-options').removeClass('hidden');
                    $('.new-pregnancy-options').addClass('hidden');
                }
            });
            // Trigger change event on page load to set the correct options
            $('#pregnancy_state').trigger('change');
        }, false);
    </script>
@endpush
