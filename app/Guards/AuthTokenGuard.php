<?php

namespace App\Guards;

use App\Models\HrmsUser;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum;

class AuthTokenGuard implements Guard
{
    use GuardHelpers;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }
        $token = $this->request->bearerToken();
        $response = Http::acceptJson()->withToken($token)->get('http://localhost:8000/api/me'/* 'http://larave-sanctum-api-hrms.test/api/me' */);
        if (!$response->successful()) {
            return null;
        }

        if($response->json()['data']) {
            $this->user = new HrmsUser();
            $this->user->id = $response->json()['data']['id'];
            $this->user->email = $response->json()['data']['email'];
            $this->user->name = $response->json()['data']['name'];
        }
        return $this->user;
    }
    public function validate(array $credentials = [])
    {

    }
}
