<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\CreateUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $user = User::where('email', $request->string('email'))->first();

        if (! $user || ! Hash::check($request->string('password'), $user->password)) {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Credenciais inválidas.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Login realizado com sucesso.',
            'data' => UserResource::make($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Logout realizado com sucesso.',
        ], Response::HTTP_OK);
    }
}
