<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $appends = ['can_update', 'can_delete', 'can_view_attachment', 'can_create_attachment', 'can_update_attachment', 'can_delete_attachment'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }


        // permissions

        public function getCanUpdateAttribute(){
            return auth()->user()->hasPermissionTo('update_document');
        }
    
        public function getCanDeleteAttribute()
        {
            return auth()->user()->hasPermissionTo('delete_document');
        }
    
        public function getCanViewAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('view_all_document_attachments');
        }
    
        public function getCanCreateAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('create_document_attachments');
        }
    
        public function getCanUpdateAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('update_document_attachments');
        }
    
        public function getCanDeleteAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('delete_document_attachments');
        }
}
