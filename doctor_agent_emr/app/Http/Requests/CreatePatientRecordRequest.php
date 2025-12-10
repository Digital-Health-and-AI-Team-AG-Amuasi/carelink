<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Dto\Patients\CreatePatientRecordDto;
use Illuminate\Foundation\Http\FormRequest;

class CreatePatientRecordRequest extends FormRequest
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
            'current_complains' => ['required', 'string'],
            'on_direct_questions' => ['required', 'string'],
            'issues' => ['nullable', 'string'],
            'updates' => ['nullable', 'string'],
            'on_examinations' => ['required', 'string'],
            'vitals' => ['required', 'string'],
            'investigations' => ['required', 'string'],
            'impression' => ['required', 'string'],
            'plan' => ['required', 'string'],
            'history_presenting_complains' => ['required', 'string'],
        ];
    }

    public function toDto(): CreatePatientRecordDto
    {
        return new CreatePatientRecordDto(
            currentComplains: $this->string('current_complains')->toString(),
            onDirectQuestions: $this->string('on_direct_questions')->toString(),
            issues: $this->string('issues')->toString(),
            updates: $this->string('updates')->toString(),
            onExaminations: $this->string('on_examinations')->toString(),
            vitals: array_column((array) json_decode($this->string('vitals')->toString()), 'value'),
            investigations: $this->string('investigations')->toString(),
            impression: $this->string('impression')->toString(),
            plan: $this->string('plan')->toString(),
            history_presenting_complains: $this->string('history_presenting_complains')->toString()
        );
    }
}
