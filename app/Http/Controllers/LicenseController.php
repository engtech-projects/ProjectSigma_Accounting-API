<?php

namespace App\Http\Controllers;

use App\Http\Requests\License\LicenseRequestStore;
use App\Models\License;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::all();

        return response()->json($licenses);
    }

    public function store(LicenseRequestStore $request)
    {
        $validated = $request->validated();

        $license = License::create($validated);

        return response()->json($license, 201);
    }

    public function show(License $license)
    {
        return response()->json($license);
    }

    public function update(Request $request, License $license)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $license->update($validated);

        return response()->json($license);
    }

    public function destroy(License $license)
    {
        $license->delete();

        return response()->json(null, 204);
    }
}
