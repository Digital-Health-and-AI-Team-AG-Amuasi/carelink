<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVitalsRequest extends FormRequest
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
        $rules = [
            'vital_type_value' => ['array'],
            'vital_type_measurement' => ['array'],
            'include' => ['array'],
        ];

        foreach ($this->array('include') as $id => $value) {
            /** @var string $id */
            if ($value) {
                $rules["vital_type_value.$id"] = ['required'];
                $rules["vital_type_measurement.$id"] = ['required', Rule::in(explode(',', $this->string("vital_type_measurements.$id")->toString()))];
            }
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = [];
        $vitalTypes = $this->array('vital_type_names');

        foreach ($this->array('include') as $id => $value) {
            /** @var string $id */
            if ($value) {
                $vitalTypeName = $vitalTypes[$id] ?? "Vital Type $id";
                $messages["vital_type_value.$id.required"] = "The vital type '$vitalTypeName' is required.";
                $messages["vital_type_measurement.$id.required"] = "The measurement for vital type '$vitalTypeName' is required.";
                $messages["vital_type_measurement.$id.in"] = "The chosen measurement for vital type '$vitalTypeName' is invalid.";
            }
        }

        return $messages;
    }
}
