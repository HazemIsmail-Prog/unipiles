<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetType;
use Illuminate\Http\JsonResponse;

class AssetTypeController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermissionTo('view_all_assettype'), 403);
        if (request()->wantsJson()) {
            return AssetType::paginate(request()->per_page ?? 10);
        }
        return view('pages.asset-types');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermissionTo('create_assettype'), 403);
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);
        $assetType = AssetType::create($validated);
        return response()->json($assetType, JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, AssetType $assetType)
    {
        abort_if(!auth()->user()->hasPermissionTo('update_assettype'), 403);
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);
        $assetType->update($validated);
        return response()->json($assetType);
    }

    public function destroy(AssetType $assetType)
    {
        abort_if(!auth()->user()->hasPermissionTo('delete_assettype'), 403);
        $assetType->delete();
        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
