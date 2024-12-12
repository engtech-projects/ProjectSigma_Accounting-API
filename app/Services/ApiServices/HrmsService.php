<?php

namespace App\Http\Services\ApiServices;

class HrmsService
{
    protected $apiUrl;
    protected $authToken;

    public function __construct($authToken)
    {
        $this->authToken = $authToken;
        $this->apiUrl = config('services.url.hrms_api');
    }

}
