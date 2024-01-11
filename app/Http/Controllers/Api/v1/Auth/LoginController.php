<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\LoginRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;


class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {

        $credentials = $request->validated();
        if(Auth::attempt($credentials)){
            $user = Auth::user();

            $hashToken = $user->createToken('access_token')->plainTextToken;
            return $hashToken;
        }
        return $this->sendFailedResponse('Credentials not found',401);


    }
}
