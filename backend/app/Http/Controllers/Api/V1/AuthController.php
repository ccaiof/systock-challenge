<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\CreateUserService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    function __construct(
        readonly private CreateUserService $createUserService
    )
    {
    }

    public function register(RegisterRequest $request)
    {
        $dto = $request->toDTO();

        $createdUser = $this->createUserService->execute($dto);
        $user = User::findOrFail($createdUser->id);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => UserResource::make($createdUser),
            'token' => $token,
            'token_type' => 'Bearer',
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => __('auth.failed'),
            ], Response::HTTP_UNAUTHORIZED);
        }

        $request->session()->regenerate();

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => __('auth.login_success'),
        ], Response::HTTP_OK);
    }
}
