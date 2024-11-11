<?php

namespace App\Http\Controllers;

use App\Services\InventoryServices;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function supplier()
    {
        return InventoryServices::syncSupplier();
    }
}
