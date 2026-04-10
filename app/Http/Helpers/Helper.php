<?php

namespace App\Http\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;

class Helper
{

    /**
     * Success response
     *
     * @param string $message
     * @param mixed $data
     * @param int $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function successResponse($message, $data = [], $code = 200)
    {
        $response = [
            "success" => true,
            "message" => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }


    /**
     * Error response
     *
     * @param string $message
     * @param mixed $errors
     * @param int $code
     *
     * @return void
     */
    public static function errorResponse($message, $errors = [], $code = 401)
    {
        $response = [
            "success" => false,
            "message" => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        throw new HttpResponseException(response()->json($response, $code));
    }
}
