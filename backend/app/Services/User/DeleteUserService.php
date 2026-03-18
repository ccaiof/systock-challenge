<?php

namespace App\Services\User;

class DeleteUserService
{
    public function __construct(
        private FindByIdUserService $findByIdUserService
    ) {}

    public function execute(int $id): bool|null
    {
        $user = $this->findByIdUserService->execute($id);

        return $user->delete();
    }
}
