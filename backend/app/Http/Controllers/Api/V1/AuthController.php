<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\User\CreateUserService;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    function __construct(
        readonly private CreateUserService $createUserService
    )
    {
    }

    public function register(CreateUserRequest $request)
    {
        $dto = $request->toDTO();

        $response = $this->createUserService->execute($dto);

        return UserResource::make($response)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
