<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Dto\CreateMedicationDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateMedicationRequest extends FormRequest
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
            'visit_id' => 'required|exists:visits,id',
            'drug' => 'required|exists:drugs,id',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
        ];
    }

    protected function prepareForValidation(): void
    {
        /**
         * @var \App\Models\Visit $visit
         */
        $visit = $this->route('visit');
        $this->merge([
            'visit_id' => $visit->id,
        ]);
    }

    public function toDto(): CreateMedicationDto
    {
        return new CreateMedicationDto(
            visitId: $this->integer('visit_id'),
            drugId: $this->integer('drug'),
            frequency: $this->string('frequency')->toString(),
            duration: $this->string('duration')->toString(),
        );
    }
}
