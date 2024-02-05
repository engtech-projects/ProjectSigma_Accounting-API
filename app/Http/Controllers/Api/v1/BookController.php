<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Store\StoreBookRequest;
use App\Http\Requests\Api\v1\Update\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;

use App\Services\Api\V1\BookService;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{

    protected $bookService;
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }
    public function index()
    {
        $book = $this->bookService->getBookList();
        return new BookResource($book);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();
        $this->bookService->createBook($data);
        return new JsonResponse([
            'success' => true,
            'message' => "Book successfully created."
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book = $this->bookService->getBookById($book);
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $data = $request->validated();
        $this->bookService->updateBook($book, $data);

        return new JsonResponse([
            'success' => true,
            'message' => "Book successfully updated."
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $this->bookService->deleteBook($book);
        return new JsonResponse([
            'success' => true,
            'message' => "Book successfully deleted."
        ]);
    }
}
