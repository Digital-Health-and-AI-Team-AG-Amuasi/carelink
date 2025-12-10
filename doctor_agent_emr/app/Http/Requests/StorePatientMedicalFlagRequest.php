<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Dto\PatientMedicalFlag\CreateFlagDto;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePatientMedicalFlagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_phone' => 'required|exists:patients,phone',
            'reason' => 'required|string'
        ];
    }

    public function toDto(): CreateFlagDto
    {
        return new CreateFlagDto(
            patientPhone: $this->string('patient_phone')->toString(),
            reason: $this->string('reason')->toString(),
        );
    }
}
