<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendSuccessResponse($data, $message): JsonResponse {
        return response()->json($data,200);
    }
    public function sendFailedResponse($message,$code): JsonResponse {
        return response()->json(['success' => false,'error'=> $message],$code);
    }
}
