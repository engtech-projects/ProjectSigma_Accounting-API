<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class VerifyAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected $hrmsUrl;
    public function __construct() {
        $this->hrmsUrl = config("services.api.hrms_url");
    }
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (empty($token)) {
            return response()->json(['error' => Response::HTTP_UNAUTHORIZED]);
        }
        $response = Http::withToken($token)->post($this->hrmsUrl.'/api/session');

        if(!$response->successful()) {
            return response()->json(['error' => Response::HTTP_UNAUTHORIZED]);

        }
        return $next($request);

    }
}
