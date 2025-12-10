<?php

declare(strict_types=1);

namespace App\Dto;

use BadMethodCallException;

class CDSResponseDto implements AibackendResponseDtoInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function fromArray(array $data): AibackendResponseDtoInterface
    {
        // TODO: Implement fromArray() method.
        throw new BadMethodCallException('Method not implemented.');
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        throw new BadMethodCallException('Method not implemented.');
    }
}
