<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $appends = ['can_update', 'can_delete', 'can_view_attachment', 'can_create_attachment', 'can_update_attachment', 'can_delete_attachment'];

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

        // permissions

        public function getCanUpdateAttribute(){
            return auth()->user()->hasPermissionTo('update_quotation');
        }
    
        public function getCanDeleteAttribute()
        {
            return auth()->user()->hasPermissionTo('delete_quotation');
        }
    
        public function getCanViewAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('view_all_quotation_attachments');
        }
    
        public function getCanCreateAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('create_quotation_attachments');
        }
    
        public function getCanUpdateAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('update_quotation_attachments');
        }
    
        public function getCanDeleteAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('delete_quotation_attachments');
        }
}
