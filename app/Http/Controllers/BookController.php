<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookCollection;
use App\Services\BookService;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BookRequest $request)
    {
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Books Successfully Retrieved.',
                'data' => BookCollection::collection(BookService::getPaginated($request->validated())),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Books Failed to Retrieve.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        try {
            $book = Book::create($request->validated());
            return new JsonResponse([
                'success' => true,
                'message' => 'Book Successfully Created.',
                'data' => new BookResource($book),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Book Failed to Create.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $book = Book::find($id);
            if ($book) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Book Successfully Retrieved.',
                    'data' => new BookResource($book),
                ], 200);
            }
            return new JsonResponse([
                'success' => false,
                'message' => 'Book Not Found.',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Book Failed to Retrieve.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UpdateBookRequest $request, string $id)
    {
        $book = Book::find($id);
        if ($book) {
            return new JsonResponse([
                'success' => true,
                'message' => 'Book Successfully Retrieved.',
                'data' => new BookResource($book),
            ], 200);
        }
        return new JsonResponse([
            'success' => false,
            'message' => 'Book Not Found.',
            'data' => null,
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);
        if ($book) {
            if ($book->isUsedInAccountGroup()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Book is currently being used.',
                    'data' => null,
                ], 400);
            }
            $book->delete();
            return new JsonResponse([
                'success' => true,
                'message' => 'Book Successfully Deleted.',
                'data' => null,
            ], 200);
        }
        return new JsonResponse([
            'success' => false,
            'message' => 'Book Not Found.',
            'data' => null,
        ], 404);
    }
}
