<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AskLLMRequest extends FormRequest
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
            'required_assistant' => [
                'required',
                'string',
                'in:cds_assistant,cds_patient_impressions_assistant,cds_patient_issues_assistant,management_plan_assistant',
            ],
            'records' => [
                'required_if:required_assistant,management_plan_assistant',
                'array',
            ],
            'doctor_query' => [
                'required_if:required_assistant,cds_assistant',
                'string'
            ],
            'send_patient_profile' => [
                'required_if:required_assistant,management_plan_assistant',
                'boolean'
            ],
            'send_chat_as_context' => [
                'required_if:required_assistant,management_plan_assistant',
                'boolean'
            ],
            'patient_phone_number' => [
                Rule::requiredIf(
                    $this->input('required_assistant') === 'management_plan_assistant' || $this->input('required_assistant') === 'cds_patient_issues_assistant'
                ),
                'string'
            ],
        ];
    }
}
