<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;

class UpdateUserRequestDTO extends BaseDTO
{
    function __construct(
        public ?string $name = null,
        public ?string $email = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
        );
    }
}
