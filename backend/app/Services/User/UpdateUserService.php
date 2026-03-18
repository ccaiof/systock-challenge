<?php

namespace App\Services\User;

use App\DTOs\User\UpdateUserRequestDTO;

class UpdateUserService
{
    public function __construct(
        private FindByIdUserService $findByIdUserService
    ) {}

    public function execute(int $id, UpdateUserRequestDTO $dto)
    {
        $user = $this->findByIdUserService->execute($id);

        $data = array_filter($dto->toArray(), function ($value) {
            return !is_null($value);
        });

        $user->update($data);

        return $user;
    }
}
