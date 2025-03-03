<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticularGroup\ParticularGroupRequestFilter;
use App\Http\Requests\ParticularGroup\ParticularGroupRequestStore;
use App\Http\Requests\ParticularGroup\ParticularGroupRequestUpdate;
use App\Http\Resources\ParticularGroupCollection;
use App\Models\ParticularGroup;
use App\Services\ParticularGroupService;
use Illuminate\Http\JsonResponse;

class ParticularGroupController extends Controller
{
    public function index(ParticularGroupRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Particular Group Successfully Retrieved.',
            'data' => (ParticularGroupService::getWithPagination($request->validated())),
        ], 200);
    }

    public function searchParticularGroups(ParticularGroupRequestFilter $request)
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

    public function store(ParticularGroupRequestStore $request)
    {
        $validated = $request->validated();

        $particularGroup = ParticularGroup::create($validated);

        return response()->json($particularGroup, 201);
    }

    public function show(string $id)
    {
        $particularGroup = ParticularGroup::findOrFail($id);

        return response()->json($particularGroup);
    }

    public function update(ParticularGroupRequestUpdate $request, string $id)
    {
        $particularGroup = ParticularGroup::findOrFail($id);

        $validated = $request->validated();
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
