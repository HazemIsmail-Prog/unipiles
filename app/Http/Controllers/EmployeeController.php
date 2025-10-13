<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use App\Models\Title;
use Illuminate\Support\Facades\DB;
use App\Services\AttachmentService;

class EmployeeController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermissionTo('view_all_employee'), 403);
        if (request()->wantsJson()) {
            $filters = request()->filters;
            return Employee::query()
                ->with('title')
                ->with('attachments')
                ->when($filters['name'], function ($query) use ($filters) {
                    $query->where('name_ar', 'like', '%' . $filters['name'] . '%');
                    $query->orWhere('name_en', 'like', '%' . $filters['name'] . '%');
                })
                ->when($filters['cid'], function ($query) use ($filters) {
                    $query->where('cid', 'like', '%' . $filters['cid'] . '%');
                })
                ->when($filters['title_id'], function ($query) use ($filters) {
                    $query->where('title_id', $filters['title_id']);
                })
                ->when($filters['is_active'], function ($query) use ($filters) {
                    if ($filters['is_active'] == 'true') {
                        $query->where('is_active', true);
                    } else {
                        $query->where('is_active', false);
                    }
                })
                ->orderBy($filters['sort'] ?? 'id', $filters['sort_direction'] ?? 'desc')
                ->paginate(request()->per_page ?? 10);
        }
        $titles = Title::all();
        return view('pages.employees', compact('titles'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermissionTo('create_employee'), 403);
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'cid' => 'nullable|string|max:255',
            'actual_salary' => 'nullable|string|max:255',
            'ezn_salary' => 'nullable|string|max:255',
            'employment_date' => 'nullable|date',
            'title_id' => 'nullable|exists:titles,id',
            'residency' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        $employee = Employee::create($validated);
        return response()->json($employee->load('title', 'attachments'), JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, Employee $employee)
    {
        abort_if(!auth()->user()->hasPermissionTo('update_employee'), 403);
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'cid' => 'nullable|string|max:255',
            'actual_salary' => 'nullable|string|max:255',
            'ezn_salary' => 'nullable|string|max:255',
            'employment_date' => 'nullable|date',
            'title_id' => 'nullable|exists:titles,id',
            'residency' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        $employee->update($validated);
        return response()->json($employee->load('title', 'attachments'));
    }
    
    public function destroy(Employee $employee)
    {
        abort_if(!auth()->user()->hasPermissionTo('delete_employee'), 403);
        // begin transaction
        DB::beginTransaction();
        try {

            // loop through the attachments and delete them
            foreach ($employee->attachments as $attachment) {

                // delete the attachment from the disk
                AttachmentService::deleteFromDisk($attachment->path);
                $attachment->delete();
            }

            // delete the employee
            $employee->delete();

            // commit the transaction
            DB::commit();
            return response()->json($employee, JsonResponse::HTTP_NO_CONTENT);

        // catch the exception
        } catch (\Exception $e) {
            // roll back the transaction
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
