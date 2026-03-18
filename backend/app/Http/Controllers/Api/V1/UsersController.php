<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\User\CreateUserService;
use App\Services\User\DeleteUserService;
use App\Services\User\FindByIdUserService;
use App\Services\User\ListAllUserService;
use App\Services\User\UpdateUserService;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    function __construct(
        readonly private ListAllUserService $listAllUserService,
        readonly private FindByIdUserService $findByIdUserService,
        readonly private CreateUserService $createUserService,
        readonly private UpdateUserService $updateUserService,
        readonly private DeleteUserService $deleteUserService,
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

    public function update(UpdateUserRequest $request, int $id)
    {
        $dto = $request->toDTO();

        $user = $this->updateUserService->execute($id, $dto);

        return UserResource::make($user);
    }

    public function destroy(int $id)
    {
        $isUserDelete = $this->deleteUserService->execute($id);

        if (!$isUserDelete) {
            return response()->json([
                'message' => 'Failed to delete user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
