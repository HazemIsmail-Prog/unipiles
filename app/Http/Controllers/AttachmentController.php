<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use App\Services\AttachmentService;
use Illuminate\Support\Facades\Crypt;

class AttachmentController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $attachments = Attachment::all();
            return response()->json($attachments);
        }
        return view('pages.attachments');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description_en' => 'required|string|max:255',
            'description_ar' => 'required|string|max:255',
            'expires_at' => 'nullable|date',
            'notify_before' => 'nullable|integer',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|exclude',
            'attachable_type' => 'required|string|max:255',
            'attachable_id' => 'required|integer',
        ]);
        $validated['path'] = AttachmentService::saveToDisk($request->file('file'), $validated['attachable_id'], $validated['attachable_type']);
        $attachment = Attachment::create($validated);
        return response()->json($attachment);
    }
    
    public function update(Request $request, Attachment $attachment)
    {
        $validated = $request->validate([
            'description_en' => 'required|string|max:255',
            'description_ar' => 'required|string|max:255',
            'expires_at' => 'nullable|date',
            'notify_before' => 'nullable|integer',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|exclude',
        ]);
        if($request->hasFile('file')) {
            AttachmentService::deleteFromDisk($attachment->path);
            $validated['path'] = AttachmentService::saveToDisk($request->file('file'), $attachment->attachable_id, $attachment->attachable_type);
        }
        $attachment->update($validated);
        return response()->json($attachment);
    }
    
    public function destroy(Attachment $attachment)
    {
        AttachmentService::deleteFromDisk($attachment->path);
        $attachment->delete();
        return response()->json($attachment);
    }

    public function updatePath()
    {
        $attachment = Attachment::all();
        foreach ($attachment as $item) {
            // check if the path starts with attachments/ then skip
            if (!str_starts_with($item->path, 'attachments/')) {
                $new_path = 'attachments/' . $item->attachable_type . '/' . $item->path;
                $item->path = $new_path;
                $item->save();
            }
        }
        return back();
    }

    public function view($encrypted_id)
    {
        $decrypted_attachment = Attachment::find(decrypt($encrypted_id));
        return Storage::disk('s3')->response($decrypted_attachment->path);
    }
}
