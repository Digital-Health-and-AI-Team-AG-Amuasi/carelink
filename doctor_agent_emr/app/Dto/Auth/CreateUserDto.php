<?php

declare(strict_types=1);

namespace App\Dto\Auth;

use Illuminate\Support\Facades\Hash;

class CreateUserDto
{
    public function __construct(
        public string $firstName,
        public string|null $lastName,
        public string $email,
        public string $password,
    ) {
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ];
    }
}
