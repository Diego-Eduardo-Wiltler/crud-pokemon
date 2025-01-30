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

    protected function errorResponse(string $message = 'Ocorreu um erro.', int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
