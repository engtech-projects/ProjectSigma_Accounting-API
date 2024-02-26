<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Store\StoreBookRequest;
use App\Http\Requests\Api\v1\Update\UpdateBookRequest;
use App\Http\Resources\collections\BookCollection;
use App\Http\Resources\resources\BookResource;
use App\Models\Book;

use App\Services\Api\v1\BookService;
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
        $books = $this->bookService->getAll();

        return new BookCollection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $this->bookService->createBook($request->validated());

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
        $book = $this->bookService->getById($book);

        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $this->bookService->updateBook($book, $request->validated());

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
        $book->delete();

        return new JsonResponse([
            'success' => true,
            'message' => "Book successfully deleted."
        ]);
    }
}
