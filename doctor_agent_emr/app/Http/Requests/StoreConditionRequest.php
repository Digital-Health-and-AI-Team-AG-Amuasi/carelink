<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Dto\CreateConditionDto;
use Illuminate\Foundation\Http\FormRequest;

class StoreConditionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'diagnosis' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'started_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date'],
            'is_active' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function toDto(): CreateConditionDto
    {
        return new CreateConditionDto(
            patientId: $this->integer('patient_id'),
            diseaseDiagnosis: $this->string('diagnosis')->toString(),
            diseaseDescription: $this->string('description')->toString(),
            diseaseStartedAt: $this->date('started_at'),
            diseaseEndedAt: $this->date('ended_at'),
            isDiseaseActive: $this->boolean('is_active') == 'true',
            doctorsNotes: $this->string('notes')->toString(),
        );
    }
}
