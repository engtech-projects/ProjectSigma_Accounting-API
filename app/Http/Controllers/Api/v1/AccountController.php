<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Store\StoreAccountRequest;
use App\Http\Requests\Api\v1\Update\UpdateAccountRequest;
use App\Http\Resources\collections\AccountCollections;
use App\Http\Resources\resources\AccountResource;
use App\Models\Account;
use App\Services\Api\v1\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    protected $accountService;
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $accounts = $this->accountService->getAll([
            'opening_balance'
        ]);

        return new AccountCollections($accounts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {

        $this->accountService->create($request->validated());

        return new JsonResponse([
            "success" => true,
            "message" => "Account successfully created."
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        $account = $this->accountService->getById($account);
        return new AccountResource($account);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $data = $request->validated();
        $this->accountService->updateAccount($account, $data);

        return new JsonResponse([
            'success' => true,
            'message' => "Account successfully updated."
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();
        return new JsonResponse([
            'success' => true,
            'message' => "Account successfully deleted."
        ], JsonResponse::HTTP_OK);
    }
}
