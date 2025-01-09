<?php

namespace App\Services\ApiServices;

use App\Models\Stakeholders\Supplier;
use Http;

class InventoryService
{
    protected $apiUrl;

    protected $authToken;

    public function __construct($authToken)
    {
        $this->authToken = $authToken;
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
        collect($suppliers)->map(function ($supplier) {
            return [
                'id' => $supplier['id'],
                'source_id' => $supplier['id'],
                'name' => $supplier['supplier_code'].'-'.$supplier['company_name'],
            ];
        });
        Supplier::upsert(
            $suppliers,
            [
                'id',
            ],
            [
                'source_id',
                'name',
            ]
        );

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
