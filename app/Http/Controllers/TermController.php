<?php

namespace App\Http\Controllers;

use App\Http\Requests\Terms\TermsRequestFilter;
use App\Http\Requests\Terms\TermsRequestStore;
use App\Models\Term;
use DB;
use Illuminate\Http\JsonResponse;

class TermController extends Controller
{
    public function index()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Terms fetched successfully',
            'data' => Term::all(),
        ], 200);
    }

    public function store(TermsRequestStore $request)
    {
        $validatedData = $request->validated();
        $term = Term::create($validatedData);

        return new JsonResponse([
            'success' => true,
            'message' => 'Term created successfully',
            'data' => $term,
        ], 201);
    }

    public function show(Term $term)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Term fetched successfully',
            'data' => $term,
        ], 200);
    }

    public function update(TermsRequestFilter $request, Term $term)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $term->update($validatedData);
            DB::commit();

            return new JsonResponse([
                'success' => true,
                'message' => 'Term updated successfully',
                'data' => $term,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'success' => false,
                'message' => 'Term failed to update',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $term = Term::findOrFail($id);
            $term->delete();
            DB::commit();

            return new JsonResponse([
                'success' => true,
                'message' => 'Term deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete term',
            ], 500);
        }
    }
}
