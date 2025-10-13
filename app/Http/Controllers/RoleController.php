<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermissionTo('view_all_role'), 403);
        if (request()->wantsJson()) {
            return Role::query()
                ->with('permissions')
                ->paginate(request()->per_page ?? 10);
        }
        $permissions = Permission::all();
        return view('pages.roles', compact('permissions'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermissionTo('create_role'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $role = DB::transaction(function () use ($validated, $request) {
        $role = Role::create($validated);
            $role->permissions()->attach($request->permissions);
            return $role;
        });
        return response()->json($role->load('permissions'), JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, Role $role)
    {
        abort_if(!auth()->user()->hasPermissionTo('update_role'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        DB::transaction(function () use ($validated, $role, $request) {
            $role->update($validated);
            $role->permissions()->sync($request->permissions);
        });
        return response()->json($role->load('permissions'));
    }

    public function destroy(Role $role)
    {
        abort_if(!auth()->user()->hasPermissionTo('delete_role'), 403);
        $role->delete();
        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
