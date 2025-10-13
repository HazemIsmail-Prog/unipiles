<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermissionTo('view_all_user'), 403);
        if (request()->wantsJson()) {
            return User::query()
                ->with('roles')
                ->with('permissions')
                ->paginate(request()->per_page ?? 10);
        }
        $roles = Role::all();
        $permissions = Permission::all();
        return view('pages.users', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermissionTo('create_user'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        $user = DB::transaction(function () use ($validated, $request) {  
            $user = User::create($validated);
            $user->roles()->attach($request->roles);
            $user->permissions()->attach($request->permissions);
            $user->clearCache();
            return $user;
        });
        return response()->json($user->load('roles', 'permissions'), JsonResponse::HTTP_OK);
    }

    public function update(Request $request, User $user)
    {
        abort_if(!auth()->user()->hasPermissionTo('update_user'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);
        DB::transaction(function () use ($validated, $user, $request) {
            $user->update($validated);
            $user->roles()->sync($request->roles);
            $user->permissions()->sync($request->permissions);
            $user->clearCache();
        });
        return response()->json($user->load('roles', 'permissions'), JsonResponse::HTTP_OK);
    }

    public function updateLocale(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string|max:255|in:en,ar',
        ]);
        auth()->user()->update(['locale' => $validated['locale']]);
        return back();
    }

    public function destroy(User $user)
    {
        abort_if(!auth()->user()->hasPermissionTo('delete_user'), 403);
        $user->delete();
        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
