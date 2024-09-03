<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Journal;
use App\Models\DocumentSeries;
use App\Models\PostingPeriod;
use App\Models\TransactionType;
use App\Models\Transaction;
use Illuminate\Http\Response;
use App\Http\Resources\resources\TransactionTypeResource;


class JournalController extends Controller
{
	/**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
     	
     	$transactionType = TransactionType::where('transaction_type_name', 'Journal Entry')->get()->load('document_series', 'stakeholder_group', 'book');

     	$postingPeriod = PostingPeriod::getPeriod();

        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully Fetched.',
            'data' => $transactionType,
            'posting_period' => $postingPeriod,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {	

    	$transactionType = TransactionType::where('transaction_type_name', 'Journal Entry')->get()->load('document_series');

    	$transaction = Transaction::create($request->entry);
    	$transaction->transaction_details()->createMany($request->details);

        return new JsonResponse([
        	'success' => true,
        	'message' => 'Successfully Created.',
        	'data' => $transaction,
        ], Response::HTTP_CREATED);
    }
}
