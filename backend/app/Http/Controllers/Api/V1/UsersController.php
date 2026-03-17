<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\User\CreateUserService;
use App\Services\User\FindByIdUserService;
use App\Services\User\ListAllUserService;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    function __construct(
        readonly private ListAllUserService $listAllUserService,
        readonly private FindByIdUserService $findByIdUserService,
        readonly private CreateUserService $createUserService,
    ) {}

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

    public function store(CreateUserRequest $request)
    {
        $dto = $request->toDTO();

        $user = $this->createUserService->execute($dto);

        return UserResource::make($user)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
