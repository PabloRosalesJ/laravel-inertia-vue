<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    public function success($data, $code = 200) : JsonResponse {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $code);
    }
}
