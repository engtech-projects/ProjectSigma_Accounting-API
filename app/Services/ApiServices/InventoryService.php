<?php

namespace App\Services\ApiServices;

use App\Models\StakeHolder;
use App\Models\Stakeholders\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    protected $apiUrl;

    protected $authToken;

    public function __construct()
    {
        $this->apiUrl = config('services.url.inventory_api');
        $this->authToken = config('services.sigma.secret_key');
        if (empty($this->authToken)) {
            throw new \InvalidArgumentException('SECRET KEY is not configured');
        }
        if (empty($this->apiUrl)) {
            throw new \InvalidArgumentException('Inventory API URL is not configured');
        }
    }

    public function syncAll()
    {
        $syncData = [
            'suppliers' => $this->syncSuppliers(),
        ];
        return $syncData;
    }

    public function syncSuppliers()
    {
        $suppliers = $this->getSuppliers();
        $suppliers = collect($suppliers)->map(function ($supplier) {
            return [
                'id' => $supplier['id'],
                'source_id' => $supplier['id'],
                'name' => $supplier['supplier_code'].'-'.$supplier['company_name'],
            ];
        });
        $suppliers_stakeholder = collect($suppliers)->map(function ($supplier) {
            return [
                'id' => $supplier['id'],
                'stakeholdable_id' => $supplier['id'],
                'stakeholdable_type' => Supplier::class,
                'name' => $supplier['name'],
            ];
        });
        DB::transaction(function () use ($suppliers, $suppliers_stakeholder) {
            Supplier::upsert($suppliers->toArray(), ['id','source_id'], ['name']);
            StakeHolder::upsert(
                $suppliers_stakeholder->toArray(),
                [
                    'id',
                    'stakeholdable_id',
                    'stakeholdable_type',
                ],
                ['name']
            );
        });

        return true;
    }

    public function getSuppliers()
    {
        $response = Http::withToken($this->authToken)
            ->acceptJson()
            ->get($this->apiUrl.'/api/supplier');
        if (! $response->successful()) {
            Log::channel("InventoryService")->error('Failed to fetch suppliers from inventory API', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return [];
        }
        return $response->json();
    }
}
