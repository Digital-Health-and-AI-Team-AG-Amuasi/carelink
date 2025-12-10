<?php

declare(strict_types=1);

namespace App\Dto;

class CreateReminderDto
{
    public function __construct(
        public string $patient_phone,

        /**
         * @var ReminderData[]
         */
        public array $reminders,
    ) {
    }

    /**
     * @return array<int, array{
     *     phone: string,
     *     reminder_text: string,
     *     reminder_time: string
     * }>
     */
    public function toArray(): array
    {
        return array_map(
            fn ($field) => [
                'phone' => $this->patient_phone,
                'reminder_text' => $field->reminderText,
                'reminder_time' => $field->reminderTime,
            ],
            $this->reminders
        );
    }

    /**
     * @param array{
     *     patient_phone_number: string,
     *     reminders: array<int, array<string, string>>
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['patient_phone_number'],
            array_map(
                static fn ($reminder) => new ReminderData(
                    reminderTime: $reminder['reminder_time'],
                    reminderText: $reminder['reminder_text'],
                ),
                $data['reminders']
            )
        );
    }
}
