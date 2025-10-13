<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Http\JsonResponse;
use App\Models\AssetType;
use Illuminate\Support\Facades\DB;
use App\Services\AttachmentService;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasPermissionTo('view_all_asset'), 403);
        if ($request->wantsJson()) {
            $filters = $request->filters;
            return Asset::query()
                ->with('asset_type')
                ->with('attachments')
                ->when($filters['name'], function ($query) use ($filters) {
                    $query->where(function ($query) use ($filters) {
                        $query->where('name_ar', 'like', '%' . $filters['name'] . '%');
                        $query->orWhere('name_en', 'like', '%' . $filters['name'] . '%');
                    });
                })
                ->when($filters['serial'], function ($query) use ($filters) {
                    $query->where('serial', 'like', '%' . $filters['serial'] . '%');
                })
                ->when($filters['sub_category_name'], function ($query) use ($filters) {
                    $query->where('sub_category_name', 'like', '%' . $filters['sub_category_name'] . '%');
                })
                ->when($filters['asset_type_id'], function ($query) use ($filters) {
                    $query->where('asset_type_id', $filters['asset_type_id']);
                })
                ->orderBy($filters['sort'] ?? 'id', $filters['sort_direction'] ?? 'desc')
                ->paginate($request->per_page ?? 10);

        }
        $asset_types = AssetType::all();
        return view('pages.assets', compact('asset_types'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermissionTo('create_asset'), 403);
        $validated = $request->validate([
            'asset_type_id' => 'required|exists:asset_types,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'serial' => 'nullable|string|max:255',
            'sub_category_name' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        $asset = Asset::create($validated);
        return response()->json($asset->load('asset_type', 'attachments'), JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, Asset $asset)
    {
        abort_if(!auth()->user()->hasPermissionTo('update_asset'), 403);
        $validated = $request->validate([
            'asset_type_id' => 'required|exists:asset_types,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'serial' => 'nullable|string|max:255',
            'sub_category_name' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        $asset->update($validated);
        return response()->json($asset->load('asset_type', 'attachments'));
    }

    public function destroy(Asset $asset)
    {
        abort_if(!auth()->user()->hasPermissionTo('delete_asset'), 403);
        // begin transaction
        DB::beginTransaction();
        try {

            // loop through the attachments and delete them
            foreach ($asset->attachments as $attachment) {

                // delete the attachment from the disk
                AttachmentService::deleteFromDisk($attachment->path);
                $attachment->delete();
            }

            // delete the asset
            $asset->delete();

            // commit the transaction
            DB::commit();
            return response()->json($asset, JsonResponse::HTTP_NO_CONTENT);

        // catch the exception
        } catch (\Exception $e) {
            // roll back the transaction
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
