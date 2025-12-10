<?php

declare(strict_types=1);

namespace App\Dto;

class AiBackendResponseDto implements AibackendResponseDtoInterface
{
    /**
     * Create a new class instance.
     *
     * @param string $status
     * @param string $message
     * @param string|array<mixed>|null $data
     */
    public function __construct(
        public string $status,
        public string $message,
        public array|string|null $data,
    ) {
    }

    /**
     * @param array{
     *     status: string,
     *     message: string,
     *     data: null|string|array<mixed>
     * } $data
     */
    public static function fromArray(array $data): AibackendResponseDtoInterface
    {
        return new self(
            (string) $data['status'],
            (string) $data['message'],
            ($data['data']),
        );
    }

    /**
     * @return array{
     *     status: string,
     *     message: string,
     *     data: null|string|array<mixed>
     * }
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
