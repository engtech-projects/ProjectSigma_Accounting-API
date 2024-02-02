<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Store\StoreJournalBookRequest;
use App\Http\Resources\JournalBookResource;
use App\Models\JournalBook;
use App\Http\Requests\Api\v1\Update\UpdateJournalBookRequest;
use App\Services\Api\V1\JournalBookService;
use Illuminate\Http\JsonResponse;

class JournalBookController extends Controller
{

    protected $journalBookService;
    public function __construct(JournalBookService $journalBookService)
    {
        $this->journalBookService = $journalBookService;
    }
    public function index()
    {
        $journalBook = $this->journalBookService->getJournalBookList();
        return new JournalBookResource($journalBook);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJournalBookRequest $request)
    {
        $data = $request->validated();
        $this->journalBookService->createJournalBook($data);
        return new JsonResponse([
            'success' => true,
            'message' => "Journal book successfully created."
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(JournalBook $journalBook)
    {
        $journalBook = $this->journalBookService->getJournalBook($journalBook);
        return new JournalBookResource($journalBook);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJournalBookRequest $request, JournalBook $journalBook)
    {
        $data = $request->validated();
        $this->journalBookService->updateJournalBook($journalBook, $data);

        return new JsonResponse([
            'success' => true,
            'message' => "Journal book successfully updated."
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JournalBook $journalBook)
    {
        $this->journalBookService->deleteJournalBook($journalBook);
        return new JsonResponse([
            'success' => true,
            'message' => "Journal book successfully deleted."
        ]);
    }
}
