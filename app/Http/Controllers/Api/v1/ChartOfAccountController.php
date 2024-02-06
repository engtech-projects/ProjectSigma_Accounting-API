<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\collections\AccountCollections;
use App\Http\Resources\collections\AccountTypeCollection;
use App\Models\Account;
use App\Services\Api\v1\AccountService;
use App\Utils\PaginateCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChartOfAccountController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, AccountService $accountService)
    {

        $accounts = collect($accountService->chartOfAccounts())->map(function ($account) {
            return [
                "account_id" => $account->account_id,
                "account_name" => $account->account_name,
                "account_number" => $account->account_number,
                "account_description" => $account->account_description,
                "bank_reconciliation" => $account->bank_reconciliation,
                "status" => $account->status,
                "category" => $account->account_type->account_category,
                "account_type" => $account->account_type->account_type,
            ];
        })->groupBy("account_type");


        return new JsonResource(PaginateCollection::paginate($accounts, 10));

    }
}
