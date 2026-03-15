<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;
use Illuminate\Support\Facades\Hash;

class CreateUserRequestDTO extends BaseDTO
{
    function __construct(
        public string $name,
        public string $email,
        public string $cpf,
        public string $password
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            cpf: $data['cpf'],
            password: Hash::make($data['password'])
        );
    }
}
