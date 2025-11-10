<?php

namespace App\Services\ApiServices;

use App\Models\StakeHolder;
use App\Models\Stakeholders\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class InventorySecretkeyService
{
    protected $apiUrl;
    protected $authToken;

    public function __construct()
    {
        $this->authToken = config('services.sigma.secret_key');
        $this->apiUrl = config('services.url.inventory_api');
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
        if ($suppliers === false) {
            return false;
        }
        $suppliers = collect($suppliers)->map(function ($supplier) {
            return [
                'id' => $supplier['id'],
                'source_id' => $supplier['id'],
                'name' => $supplier['supplier_code'].'-'.$supplier['company_name'],
            ];
        });
        $suppliers_stakeholder = collect($suppliers)->map(function ($supplier) {
            return [
                'stakeholdable_id' => $supplier['id'],
                'stakeholdable_type' => Supplier::class,
                'name' => $supplier['name'],
            ];
        });
        DB::transaction(function () use ($suppliers, $suppliers_stakeholder) {
            Supplier::upsert($suppliers->toArray(), ['source_id'], ['name']);
            StakeHolder::upsert(
                $suppliers_stakeholder->toArray(),
                [
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
            ->get($this->apiUrl.'/api/suppliers');
        if (! $response->successful()) {
            return false;
        }
        return $response->json();
    }
}
