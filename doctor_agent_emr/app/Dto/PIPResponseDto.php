<?php

declare(strict_types=1);

namespace App\Dto;

class PIPResponseDto implements AibackendResponseDtoInterface
{
    public function __construct(
        public string $status,
        public string $message,
        public CreateReminderDto $reminders_dto,
    ) {
    }

    /**
     * @return array{
     *     status: string,
     *     message: string,
     *     reminders: array<int, array{
     *         reminder_text: string,
     *         reminder_time: string
     *     }>
     * }
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'reminders' => $this->reminders_dto->toArray(),
        ];
    }

    /**
     * @param array{
     *     status: string,
     *     message: string,
     *     data: array{
     *         patient_phone_number: string,
     *         reminders: array<int, array<string, string>>,
     *     }
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['status'],
            (string) $data['message'],
            CreateReminderDto::fromArray($data['data']),
        );
    }
}
