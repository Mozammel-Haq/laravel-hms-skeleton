<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * BaseV2Controller
 * 
 * Provides standardized response methods for the Enterprise Dashboard APIs.
 */
class BaseV2Controller extends Controller
{
    /**
     * Standard Success Response
     */
    protected function successResponse($data, string $message = 'Operation successful', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toIso8601String()
        ], $code);
    }

    /**
     * Standard Error Response
     */
    protected function errorResponse(string $message, int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toIso8601String()
        ], $code);
    }

    /**
     * Forbidden/Unauthorized Response
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized access'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }
}
