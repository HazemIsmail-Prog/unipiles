<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use App\Services\AttachmentService;

class DocumentController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermissionTo('view_all_document'), 403);
        if (request()->wantsJson()) {
            $filters = request()->filters;
            return Document::query()
                ->with('project')
                ->with('attachments')
                ->when($filters['date'], function ($query) use ($filters) {
                    $query->where('date', $filters['date']);
                })
                ->when($filters['type'], function ($query) use ($filters) {
                    $query->where('type', 'like', '%' . $filters['type'] . '%');
                })
                ->when($filters['ref'], function ($query) use ($filters) {
                    $query->where('ref', 'like', '%' . $filters['ref'] . '%');
                })
                ->when($filters['subject'], function ($query) use ($filters) {
                    $query->where('subject', 'like', '%' . $filters['subject'] . '%');
                })
                ->when($filters['sent_from'], function ($query) use ($filters) {
                    $query->where('sent_from', 'like', '%' . $filters['sent_from'] . '%');
                })
                ->when($filters['sent_to'], function ($query) use ($filters) {
                    $query->where('sent_to', 'like', '%' . $filters['sent_to'] . '%');
                })
                ->when($filters['project_id'], function ($query) use ($filters) {
                    $query->where('project_id', $filters['project_id']);
                })
                ->orderBy($filters['sort'] ?? 'id', $filters['sort_direction'] ?? 'desc')
                ->paginate(request()->per_page ?? 10);
        }
        $projects = Project::all();
        return view('pages.documents', compact('projects'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermissionTo('create_document'), 403);
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'date' => 'nullable|date',
            'type' => 'nullable|string|max:255',
            'ref' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'sent_from' => 'nullable|string|max:255',
            'sent_to' => 'nullable|string|max:255',
        ]);
        $document = Document::create($validated);
        return response()->json($document->load('project'), JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, Document $document)
    {
        abort_if(!auth()->user()->hasPermissionTo('update_document'), 403);
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'date' => 'nullable|date',
            'type' => 'nullable|string|max:255',
            'ref' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'sent_from' => 'nullable|string|max:255',
            'sent_to' => 'nullable|string|max:255',
        ]);
        $document->update($validated);
        return response()->json($document->load('project'));
    }

    public function destroy(Document $document)
    {
        abort_if(!auth()->user()->hasPermissionTo('delete_document'), 403);
        // begin transaction
        DB::beginTransaction();
        try {

            // loop through the attachments and delete them
            foreach ($document->attachments as $attachment) {

                // delete the attachment from the disk
                AttachmentService::deleteFromDisk($attachment->path);
                $attachment->delete();
            }

            // delete the document
            $document->delete();

            // commit the transaction
            DB::commit();
            return response()->json($document, JsonResponse::HTTP_NO_CONTENT);

        // catch the exception
        } catch (\Exception $e) {
            // roll back the transaction
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
