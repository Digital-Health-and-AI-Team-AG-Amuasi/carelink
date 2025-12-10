<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Dto\Visits\CreateVisitDto;
use App\Enums\PregnancyState;
use App\Models\Pregnancy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveVisitRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'reason' => ['nullable', 'string'],
            'pregnancy_state' => ['required', Rule::enum(PregnancyState::class)],
            'edd' => ['nullable', Rule::requiredIf(fn () => $this->enum('pregnancy_state', PregnancyState::class) === PregnancyState::New), 'date', 'date_format:Y-m-d'],
            'pregnancy' => ['nullable', Rule::requiredIf(fn () => $this->enum('pregnancy_state', PregnancyState::class) === PregnancyState::Old), Rule::exists(Pregnancy::class, 'id')],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'patient_id' => $this->route('patient')
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pregnancy_state.required' => 'The pregnancy state is required.',
            'pregnancy_state.enum' => 'The selected pregnancy state is invalid.',
            'edd.required_if' => 'The Estimated date of delivery is required when the pregnancy state is new.',
            'edd.date_format' => 'The Estimated date of delivery must be a valid date in the format YYYY-MM-DD',
            'pregnancy.required_if' => 'The pregnancy is required when the pregnancy state is old.',
            'pregnancy.exists' => 'The selected pregnancy does not exist.',
        ];
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return CreateVisitDto
     */
    public function toDto(): CreateVisitDto
    {
        $pregnancyState = $this->enum('pregnancy_state', PregnancyState::class);
        if ($pregnancyState === null) {
            throw new \InvalidArgumentException('Pregnancy state is required');
        }
        return new CreateVisitDto(
            reasonForVisit: $this->string('reason')->toString(),
            pregnancyState: $pregnancyState,
            edd: $this->date('edd'),
            pregnancyId: $this->integer('pregnancy'),
            patientId: $this->integer('patient_id'),
        );
    }
}
