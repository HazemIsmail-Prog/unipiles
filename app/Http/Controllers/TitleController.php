<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Title;
use Illuminate\Http\JsonResponse;

class TitleController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermissionTo('view_all_title'), 403);
        if (request()->wantsJson()) {
            return Title::paginate(request()->per_page ?? 10);
        }
        return view('pages.titles');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermissionTo('create_title'), 403);
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);
        $title = Title::create($validated);
        return response()->json($title, JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, Title $title)
    {
        abort_if(!auth()->user()->hasPermissionTo('update_title'), 403);
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);
        $title->update($validated);
        return response()->json($title);
    }

    public function destroy(Title $title)
    {
        abort_if(!auth()->user()->hasPermissionTo('delete_title'), 403);
        $title->delete();
        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
