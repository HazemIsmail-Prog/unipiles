<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Services\AttachmentService;

class QuotationController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermissionTo('view_all_quotation'), 403);
        if (request()->wantsJson()) {
            $filters = request()->filters;
            return Quotation::query()
                ->with('attachments')
                ->when($filters['date'], function ($query) use ($filters) {
                    $query->where('date', $filters['date']);
                })
                ->when($filters['ref'], function ($query) use ($filters) {
                    $query->where('ref', 'like', '%' . $filters['ref'] . '%');
                })
                ->when($filters['subject'], function ($query) use ($filters) {
                    $query->where('subject', 'like', '%' . $filters['subject'] . '%');
                })
                ->when($filters['project'], function ($query) use ($filters) {
                    $query->where('project', 'like', '%' . $filters['project'] . '%');
                })
                ->when($filters['sent_to'], function ($query) use ($filters) {
                    $query->where('sent_to', 'like', '%' . $filters['sent_to'] . '%');
                })
                ->orderBy($filters['sort'] ?? 'id', $filters['sort_direction'] ?? 'desc')
                ->paginate(request()->per_page ?? 10);
        }
        return view('pages.quotations');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermissionTo('create_quotation'), 403);
        $validated = $request->validate([
            'date' => 'nullable|date',
            'ref' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'project' => 'nullable|string|max:255',
            'sent_to' => 'nullable|string|max:255',
        ]);
        $quotation = Quotation::create($validated);
        return response()->json($quotation->load('attachments'), JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, Quotation $quotation)
    {
        abort_if(!auth()->user()->hasPermissionTo('update_quotation'), 403);
        $validated = $request->validate([
            'date' => 'nullable|date',
            'ref' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'project' => 'nullable|string|max:255',
            'sent_to' => 'nullable|string|max:255',
        ]);
        $quotation->update($validated);
        return response()->json($quotation->load('attachments'));
    }

    public function destroy(Quotation $quotation)
    {
        abort_if(!auth()->user()->hasPermissionTo('delete_quotation'), 403);
        // begin transaction
        DB::beginTransaction();
        try {

            // loop through the attachments and delete them
            foreach ($quotation->attachments as $attachment) {

                // delete the attachment from the disk
                AttachmentService::deleteFromDisk($attachment->path);
                $attachment->delete();
            }

            // delete the quotation
            $quotation->delete();

            // commit the transaction
            DB::commit();
            return response()->json($quotation, JsonResponse::HTTP_NO_CONTENT);

        // catch the exception
        } catch (\Exception $e) {
            // roll back the transaction
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
