<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Dto\CreateReminderDto;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReminderRequest extends FormRequest
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
            'patient_id' => ['required', 'string', 'exists:patients,phone'],

            // Ensure `form_fields` exists and is an array
            'form_fields' => ['required', 'array', 'min:1'],

            // Validate each reminder inside the array
            'form_fields.*.reminder_text' => ['required', 'string', 'max:255'],
            'form_fields.*.reminder_time' => ['required', 'in:morning,afternoon,evening'],
        ];
    }

    public function toDto(): CreateReminderDto
    {
        throw new \BadMethodCallException();

    }
}
