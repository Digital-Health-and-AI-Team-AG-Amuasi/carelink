<?php

declare(strict_types=1);

namespace App\Http\Validation;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ResponseRemindersValidation
{
    /**
     * @param array<mixed> $data
     *
     * @throws ValidationException
     *
     * @return array<mixed>
     */
    public static function validate(array $data): array
    {
        $rules = [
            'status' => ['required'],
            'message' => ['required'],
            'data.patient_phone_number' => ['required', 'string', 'exists:patients,phone'],
            'data.reminders.*.reminder_time' => ['required', 'string'],
            'data.reminders.*.reminder_text' => ['required', 'string'],
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }
}
