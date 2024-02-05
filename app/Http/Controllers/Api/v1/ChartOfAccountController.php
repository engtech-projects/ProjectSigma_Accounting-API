<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\collections\AccountCollections;
use App\Models\Account;
use App\Services\Api\v1\AccountService;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, AccountService $accountService)
    {
        $accounts = $accountService->getAccountWithSubAccount(true);
        return new AccountCollections($accounts);
    }
}
