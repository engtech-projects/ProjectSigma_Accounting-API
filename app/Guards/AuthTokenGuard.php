<?php

namespace App\Guards;

use App\Models\Stakeholders\Department;
use App\Models\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthTokenGuard implements Guard
{
    use GuardHelpers;

    protected $request;

    protected $hrmsApiUrl;

    public function __construct(Request $request)
    {
        $this->hrmsApiUrl = config()->get('services.url.hrms_api');
        $this->request = $request;
    }

    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $token = $this->request->bearerToken();
        $response = Http::withToken($token)
            ->acceptJson()
            ->get($this->hrmsApiUrl.'/api/session');
        if (! $response->successful()) {
            return null;
        }

        if ($response->json()) {
            $this->user = new User;
            $this->user->id = $response->json()['id'];
            $this->user->name = $response->json()['name'];
            $this->user->full_name = $response->json()['employee']['fullname_first'];
            $this->user->email = $response->json()['email'];
            $this->user->type = $response->json()['type'];
            $this->user->token = $token;
            $this->user->accessibilities = $response->json()['accessibilities'];
            $this->user->accessibilities_name = $response->json()['accessibility_names'];
            if($this->user->type == 'employee') {
                $this->user->employee = $response->json()['employee'];
                $this->user->department_code = Department::getByCode($response->json()['employee']['current_department']);
            } else {
                $this->user->employee = null;
                $this->user->department_code = null;
            }
        }

        return $this->user;
    }

    public function validate(array $credentials = []) {}
}
