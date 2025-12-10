<?php

declare(strict_types=1);

namespace App\Dto;

interface AibackendResponseDtoInterface
{
    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): self;

    /**
     * @return array<mixed>
     */
    public function toArray(): array;
}
