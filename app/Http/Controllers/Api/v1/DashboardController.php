<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('user.accessibilities:accounting:dashboard');
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return new JsonResponse(['message' => "Dashboard endpoint"]);
    }
}
