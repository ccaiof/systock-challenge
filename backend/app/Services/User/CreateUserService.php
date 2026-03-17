<?php

namespace App\Services\User;

use App\DTOs\User\CreateUserRequestDTO;
use App\DTOs\User\CreateUserResponseDTO;
use App\Exceptions\UserCreationException;
use App\Models\User;

class CreateUserService
{
    public function execute(CreateUserRequestDTO $createUserRequestDTO): CreateUserResponseDTO
    {
        $user = User::where('cpf', $createUserRequestDTO->cpf)
            ->orWhere('email', $createUserRequestDTO->email)
            ->first();

        if ($user) {
            throw new UserCreationException(__('errors.user.already_exists'));
        }

        $newUser = User::create($createUserRequestDTO->toArray());

        return new CreateUserResponseDTO($newUser->id, $newUser->name, $newUser->email);
    }
}
