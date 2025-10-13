<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermissionTo('view_all_permission'), 403);
        if (request()->wantsJson()) {
            return Permission::paginate(request()->per_page ?? 10);
        }
        return view('pages.permissions');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermissionTo('create_permission'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string|max:255',
        ]);
        $permission = Permission::create($validated);
        return response()->json($permission, JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, Permission $permission)
    {
        abort_if(!auth()->user()->hasPermissionTo('update_permission'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string|max:255',
        ]);
        $permission->update($validated);
        return response()->json($permission);
    }

    public function destroy(Permission $permission)
    {
        abort_if(!auth()->user()->hasPermissionTo('delete_permission'), 403);
        $permission->delete();
        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
