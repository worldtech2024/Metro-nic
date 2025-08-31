<?php

namespace App\Trait;

class ApiResponse
{
    static function sendResponse($status = true, $msg = null, $data = null, $code = 200)
    {
        $response = [
            'status' => $status,
            'message' => $msg,
            'data' => $data
        ];
        return response()->json($response, $code);
    }

    static function errorResponse($status = false, $msg )
    {
        $response = [
            'status' => false,
            'message' => $msg,

        ];
        return response()->json($response, 403);
    }
}