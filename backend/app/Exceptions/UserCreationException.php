<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserCreationException extends Exception implements ShouldntReport
{
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'status' => Response::HTTP_BAD_REQUEST,
            'message' => $this->getMessage(),
        ], Response::HTTP_BAD_REQUEST);
    }
}
