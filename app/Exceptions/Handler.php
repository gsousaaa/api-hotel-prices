<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
 public function render($request, Throwable $exception): JsonResponse|\Symfony\Component\HttpFoundation\Response
{
    if ($exception instanceof ValidationException) {
        return response()->json([
            'message' => 'Os dados fornecidos são inválidos.',
            'errors' => $exception->errors(),
        ], 422);
    }

    return parent::render($request, $exception);
}
}
