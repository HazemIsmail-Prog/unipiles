<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use App\Models\Company;

class ProjectController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermissionTo('view_all_project'), 403);
        if (request()->wantsJson()) {
            return Project::query()
                ->with('company')
                ->paginate(request()->per_page ?? 10);
        }
        $companies = Company::all();
        return view('pages.projects', compact('companies'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermissionTo('create_project'), 403);
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255|unique:projects',
            'name_en' => 'required|string|max:255|unique:projects',
            'company_id' => 'required|exists:companies,id',
        ]);
        $project = Project::create($validated);
        return response()->json($project->load('company'), JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, Project $project)
    {
        abort_if(!auth()->user()->hasPermissionTo('update_project'), 403);
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255|unique:projects,name_ar,' . $project->id,
            'name_en' => 'required|string|max:255|unique:projects,name_en,' . $project->id,
            'company_id' => 'required|exists:companies,id',
        ]);
        $project->update($validated);
        return response()->json($project->load('company'));
    }

    public function destroy(Project $project)
    {
        abort_if(!auth()->user()->hasPermissionTo('delete_project'), 403);
        $project->delete();
        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
