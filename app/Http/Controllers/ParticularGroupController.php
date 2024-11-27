<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchParticularGroupRequest;
use App\Http\Resources\ParticularGroupCollection;
use App\Models\ParticularGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParticularGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Particular Group Successfully Retrieved.',
            'data' => ParticularGroupCollection::collection(ParticularGroup::orderBy('name', 'asc')->paginate(config('app.pagination_limit')))->response()->getData(true),
        ], 200);
    }

    public function searchParticularGroups(SearchParticularGroupRequest $request)
    {
        $query = ParticularGroup::query();
        if ($request->has('key')) {
            $query->where('name', 'like', '%' . $request->key . '%');
        }
        return new JsonResponse([
            'success' => true,
            'message' => 'Particular Group Successfully Retrieved.',
            'data' => ParticularGroupCollection::collection($query->orderBy('name', 'asc')->paginate(config('app.pagination_limit'))),
        ], 200);
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $particularGroup = ParticularGroup::create($validated);
        return response()->json($particularGroup, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $particularGroup = ParticularGroup::findOrFail($id);
        return response()->json($particularGroup);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $particularGroup = ParticularGroup::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $particularGroup->update($validated);
        return response()->json($particularGroup);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $particularGroup = ParticularGroup::findOrFail($id);
        $particularGroup->delete();
        return response()->json(null, 204);
    }
}
