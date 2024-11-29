<?php

namespace App\Http\Controllers;

use App\Models\Term;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:accounts,id',
        ]);
        $term = Term::create($validated);

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

    public function update(Request $request, Term $term)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:accounts,id',
        ]);
        $term->update($validated);

        return new JsonResponse([
            'success' => true,
            'message' => 'Term updated successfully',
            'data' => $term,
        ], 200);
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
