@extends('layouts.app')

@section('title', sprintf('Record Vitals for visit #%d for patient - %s %s', $visit->id, $visit->pregnancy->patient->first_name, $visit->pregnancy->patient->last_name))

@section('content')
    <div class="kt-container-fixed">
        <div class="grid gap-5 lg:gap-7.5">

            <form method="post" action="{{ route('visits.vital.store', $visit) }}" class="kt-form">
                @csrf

                @foreach($vitalTypes as $vitalType)
                    <div class="kt-form-item">
                        <label class="kt-form-label">{{ $vitalType->name }}</label>
                        <div class="kt-form-control">
                            <input type="text" name="vital_type_value[{{ $vitalType->id }}]" value="{{ old('vital_type_value.' . $vitalType->id) }}" class="kt-input" placeholder="Enter value" />
                        </div>
                    </div>

                    <div class="kt-form-item">
                        <label class="kt-form-label">Unit of Measurement</label>
                        <select class="kt-select" name="vital_type_measurement[{{ $vitalType->id }}]">
                            <option value="">-- Select --</option>
                            @foreach(json_decode($vitalType->units_of_measurement) as $unitOfMeasurement)
                                <option value="{{ $unitOfMeasurement }}" @selected(old('vital_type_measurement.' . $vitalType->id) == $unitOfMeasurement)>{{ $unitOfMeasurement }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="kt-form-item">
                        <div class="kt-form-control">
                            <input type="checkbox" @checked(old('include.' . $vitalType->id )) name="include[{{ $vitalType->id }}]" class="kt-checkbox" id="check" value="1" />
                        </div>
                    </div>

                    <input type="hidden" name="vital_type_names[{{ $vitalType->id }}]" value="{{ $vitalType->name }}" />
                    <input type="hidden" name="vital_type_measurements[{{ $vitalType->id }}]" value="{{ implode(',', json_decode($vitalType->units_of_measurement)) }}" />
                @endforeach

                <input type="submit" class="kt-btn" value="Save">
            </form>
        </div>
    </div>
@endsection
