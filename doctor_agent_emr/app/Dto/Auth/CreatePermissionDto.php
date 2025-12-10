<?php

declare(strict_types=1);

namespace App\Dto\Auth;

class CreatePermissionDto
{
    public function __construct(
        public string $name,
        public string $guardName = 'web',
    ) {
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'guard_name' => $this->guardName,
        ];
    }
}
