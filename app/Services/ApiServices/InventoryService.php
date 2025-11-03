<?php

namespace App\Http\Services\ApiServices;

use Illuminate\Support\Facades\Http;

class InventoryService
{
    protected $apiUrl;
    protected $authToken;

    public function __construct($authToken)
    {
        $this->authToken = $authToken;
        $this->apiUrl = config('services.url.inventory_api');
        if (empty($this->apiUrl)) {
            throw new \InvalidArgumentException('Inventory API URL is not configured');
        }
    }

    public function getSuppliers()
    {
        $response = Http::withToken($this->authToken)
            ->acceptJson()
            ->get(
                $this->apiUrl . '/api/suppliers'
            );
        if (!$response->successful()) {
            return false;
        }
        return $response->json("data");
    }
}
