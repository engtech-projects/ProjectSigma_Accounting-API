<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('user.accessibilities:accounting:dashboard');
    }
    public function index()
    {
        return new JsonResponse(['message' => 'Accounting dashboard index endpoint']);
    }
}
