<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\FindByIdUserService;
use App\Services\User\ListAllUserService;

class UsersController extends Controller
{
    function __construct(
        readonly private ListAllUserService $listAllUserService,
        readonly private FindByIdUserService $findByIdUserService,
    )
    {
    }

    public function index()
    {
        $users = $this->listAllUserService->execute();
        return UserResource::collection($users);
    }

    public function show(int $id)
    {
        $user = $this->findByIdUserService->execute($id);

        return UserResource::make($user);
    }
}
