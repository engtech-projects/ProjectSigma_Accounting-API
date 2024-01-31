<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\collections\AccountTypeCollection;
use App\Http\Resources\resources\AccountTypeResource;
use App\Models\AccountType;
use App\Http\Requests\Api\v1\Store\StoreAccountTypeRequest;
use App\Http\Requests\Api\v1\Update\UpdateAccountTypeRequest;
use App\Services\Api\v1\AccountTypeService;
use Illuminate\Http\JsonResponse;

class AccountTypeController extends Controller
{

    protected $accountTypeService;
    public function __construct(AccountTypeService $accountTypeService)
    {
        $this->accountTypeService = $accountTypeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountType = $this->accountTypeService->getAccountTypes();
        return new AccountTypeCollection($accountType);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountTypeRequest $request)
    {
        $data = $request->validated();
        $this->accountTypeService->createAccountType($data);
        return new JsonResponse(['success' => true, 'message' => "Account type successfully created."], JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountType $accountType)
    {
        $accountType = $this->accountTypeService->getAccountTypeById($accountType);
        return new AccountTypeResource($accountType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountTypeRequest $request, AccountType $accountType)
    {
        $data = $request->validated();
        $accountType = $this->accountTypeService->updateAccountType($data, $accountType);
        return new JsonResponse(['success' => true, 'message' => "Account type successfully updated."]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountType $accountType)
    {
        $this->accountTypeService->deleteAccountType($accountType);
        return new JsonResponse(['success' => true, 'message' => "Account type successfully deleted."]);
    }
}
