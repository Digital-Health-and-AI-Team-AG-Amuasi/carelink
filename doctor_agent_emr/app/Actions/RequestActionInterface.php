<?php

declare(strict_types=1);

namespace App\Actions;

use App\Dto\AibackendResponseDtoInterface;

interface RequestActionInterface
{
    /**
     * @param array<string, mixed> $payload
     * */
    public function request(array $payload): AibackendResponseDtoInterface;
}
