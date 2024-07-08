<?php

namespace App\Http\Controllers\Api\v1\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $url = config()->get('services.hrms_api_url');
    }
}
