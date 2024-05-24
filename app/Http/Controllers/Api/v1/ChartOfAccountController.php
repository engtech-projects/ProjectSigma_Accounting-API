<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\collections\AccountCollections;
use App\Http\Resources\collections\AccountTypeCollection;
use App\Models\Account;
use App\Services\Api\v1\AccountService;
use App\Utils\PaginateCollection;
use App\Utils\PaginateResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChartOfAccountController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, AccountService $accountService)
    {
        $accounts = $accountService->getAll(false, ['account_type', 'opening_balance']);
        $collection = $accounts->map(function ($account) {
            return [
                "account_id" => $account->account_id,
                "account_name" => $account->account_name,
                "account_number" => $account->account_number,
                "account_description" => $account->account_description,
                "bank_reconciliation" => $account->bank_reconciliation,
                "status" => $account->status,
                "category" => $account->account_type->account_category,
                "account_type" => $account->account_type->account_type,
                "opening_balance" => $account->opening_balance->first()?->opening_balance
            ];
        })->groupBy('account_type');

        return new JsonResponse([
            "success" => true,
            "message" => "Sucessfully fetched.",
            "data" => PaginateResourceCollection::paginate($collection, 5)
        ]);
    }
}
