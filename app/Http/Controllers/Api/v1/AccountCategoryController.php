<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AccountCategory;
use App\Http\Requests\Api\v1\Store\StoreAccountCategoryRequest;
use App\Http\Requests\Api\v1\Update\UpdateAccountCategoryRequest;
use App\Services\Api\v1\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AccountCategoryController extends Controller
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
        return JsonResource::collection(AccountCategory::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountCategoryRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            return AccountCategory::create($data);
        });

        return new JsonResponse(
            ['success' => true, "message" => "Account category successfully created."],
            JsonResponse::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountCategory $accountCategory)
    {
        return new JsonResource($accountCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountCategoryRequest $request, AccountCategory $accountCategory)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $accountCategory) {
            $accountCategory->update($data);
        });

        return new JsonResponse(["success"=> true,"message"=> "Category successfully updated."]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountCategory $accountCategory)
    {
        DB::transaction(function () use ($accountCategory) {
            $accountCategory->delete();
        });
        return new JsonResponse(["success"=> true,"message" => "Category successfully deleted."]);
    }
}
