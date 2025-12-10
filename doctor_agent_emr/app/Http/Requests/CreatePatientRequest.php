<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Dto\Patients\CreatePatientDto;
use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\NhisStatus;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePatientRequest extends FormRequest
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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:patients,phone'],
            'lhims_number' => ['required', 'string', 'unique:patients'],
            'address' => ['nullable', 'string'],
            'date_of_birth' => ['required', 'date', 'date_format:Y-m-d'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'marital_status' => ['required', Rule::enum(MaritalStatus::class)],
            'religion' => ['nullable', 'string'],
            'occupation' => ['nullable', 'string'],
            'medical_history' => ['nullable', 'string'],
            'drug_history' => ['nullable', 'string'],
            'obstetric_history' => ['nullable', 'string'],
            'social_history' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'dob.date' => 'The date of birth must be a date',
            'dob.date_format' => 'The date of birth must be in the format YYYY-MM-DD',
        ];
    }

    public function toDto(): CreatePatientDto
    {
        return new CreatePatientDto(
            firstName: $this->string('first_name')->toString(),
            lastName: $this->string('last_name')->toString(),
            phone: $this->string('phone')->toString(),
            lhimsNumber: $this->string('lhims_number')->toString(),
            address: $this->string('address')->toString(),
            dob: $this->filled('date_of_birth') ? Carbon::parse($this->date('date_of_birth')) : null,
            gender: Gender::from($this->string('gender')->toString()),
            notes: $this->string('notes')->toString(),
            religion: $this->string('religion')->toString(),
            maritalStatus: $this->filled('marital_status') ? MaritalStatus::from($this->string('marital_status')->toString()) : null,
            nhisStatus: $this->filled('nhis_status') ? NhisStatus::from($this->string('nhis_status')->toString()) : null,
            occupation: $this->string('occupation')->toString(),
            edd: Carbon::parse($this->date('edd')),
            medicalHistory: array_column((array) json_decode($this->string('medical_history')->toString() ?: '[]', true), 'value'),
            drugHistory: array_column((array) json_decode($this->string('drug_history')->toString() ?: '[]', true), 'value'),
            obstetricHistory: array_column((array) json_decode($this->string('obstetric_history')->toString() ?: '[]', true), 'value'),
            socialHistory: array_column((array) json_decode($this->string('social_history')->toString() ?: '[]', true), 'value'),
        );
    }

}
