<?php

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function successResponse(mixed $data, string $message = 'Operação realizada com sucesso.', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
