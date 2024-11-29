<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\BookRequestFilter;
use App\Http\Requests\Book\BookRequestStore;
use App\Http\Requests\Book\BookRequestUpdate;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\BookService;
use DB;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
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

    public function store(BookRequestStore $request)
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

    public function update(BookRequestUpdate $request, string $id)
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

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $book = Book::find($id);
            if ($book) {
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
