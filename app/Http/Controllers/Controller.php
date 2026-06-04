<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected function successResponse($data = null, $message = 'OK', $status = 200)
    {
        return response()->json(['success' => true, 'message' => $message, 'data' => $data], $status);
    }

    protected function errorResponse($message = 'Error', $status = 500, $errors = null)
    {
        $payload = ['success' => false, 'message' => $message];
        if (!is_null($errors)) {
            $payload['errors'] = $errors;
        }
        return response()->json($payload, $status);
    }
}
