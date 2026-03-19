<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;

class CreateUserResponseDTO extends BaseDTO
{
    function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $cpf,
    )
    {
    }
}
