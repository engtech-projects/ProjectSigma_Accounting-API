<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequestFilter;
use App\Http\Requests\BookRequest;
use App\Http\Resources\BookCollection;
use App\Services\BookService;
use DB;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BookRequestFilter $request)
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
    public function store(BookRequest $request)
    {
        DB::beginTransaction();
        try {
            $book = Book::create($request->validated());
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Book Successfully Created.',
                'data' => new BookResource($book),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $book = Book::find($id);
            $book->fill($request->validated());
            if ($book->save()) {
                DB::commit();
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Book Successfully Updated.',
                    'data' => new BookResource($book),
                ], 200);
            }
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Book Not Found.',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Book Failed to Update.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $book = Book::find($id);
            if ($book) {
                if ($book->isUsedInAccountGroup()) {
                    DB::rollBack();
                    return new JsonResponse([
                        'success' => false,
                    'message' => 'Book is currently being used.',
                        'data' => null,
                    ], 400);
                }
                $book->delete();
                DB::commit();
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Book Successfully Deleted.',
                    'data' => null,
                ], 200);
            }
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
            'message' => 'Book Not Found.',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Book Failed to Delete.',
                'data' => null,
            ], 500);
        }
    }
}
