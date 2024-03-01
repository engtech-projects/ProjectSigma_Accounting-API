<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\collections\AccountGroupCollection;
use App\Http\Resources\resources\AccountGroupResource;
use App\Models\AccountGroup;
use App\Http\Requests\Api\v1\Store\StoreAccountGroupRequest;
use App\Http\Requests\Api\v1\Update\UpdateAccountGroupRequest;
use App\Services\Api\v1\AccountGroupService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AccountGroupController extends Controller
{

    protected $accountGroupService;

    public function __construct(AccountGroupService $accountGroupService)
    {
        $this->accountGroupService = $accountGroupService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountGroup = $this->accountGroupService->getAll();

        return new AccountGroupCollection($accountGroup);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountGroupRequest $request)
    {
        AccountGroupService::create($request->validated());
        return new JsonResponse(['succes' => true, 'message' => 'Account group successfully created.'], JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountGroup $accountGroup)
    {
        $accountGroup = $this->accountGroupService->getById($accountGroup, ['account', 'book']);

        return new AccountGroupResource($accountGroup);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountGroupRequest $request, AccountGroup $accountGroup)
    {
        $accountGroup->fill($request->validated())->update();

        return new JsonResponse(['succes' => true, 'message' => 'Account group successfully updated.'], JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountGroup $accountGroup)
    {
        $accountGroup->delete();

        return new JsonResponse(['succes' => true, 'message' => 'Account group successfully deleted.'], JsonResponse::HTTP_OK);
    }
}
