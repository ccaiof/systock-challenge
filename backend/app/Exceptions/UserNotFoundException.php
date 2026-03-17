<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserNotFoundException extends Exception implements ShouldntReport
{
    function __construct(int $userId)
    {
        parent::__construct(
            __('errors.user.not_found', ['id' => $userId]),
            Response::HTTP_NOT_FOUND
        );
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'status' => Response::HTTP_NOT_FOUND,
            'message' => $this->getMessage(),
        ], Response::HTTP_NOT_FOUND);
    }
}
