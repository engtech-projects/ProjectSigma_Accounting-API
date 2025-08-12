<?php

namespace App\Http\Controllers;

use App\Models\TransactionFlowModel;
use Illuminate\Http\Request;

class TransactionFLowModelController extends Controller
{
    public function index()
    {
        return response()->json(['data' => TransactionFlowModel::all()], 200);
    }
}
