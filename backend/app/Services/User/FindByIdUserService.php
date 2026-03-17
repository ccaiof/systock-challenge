<?php

namespace App\Services\User;

use App\Exceptions\UserNotFoundException;
use App\Models\User;

class FindByIdUserService
{
    public function execute(int $id): User
    {
        return User::find($id) ?? throw new UserNotFoundException($id);
    }
}
