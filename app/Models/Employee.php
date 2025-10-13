<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $appends = ['name', 'can_update', 'can_delete', 'can_view_attachment', 'can_create_attachment', 'can_update_attachment', 'can_delete_attachment'];

    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? ($this->name_ar ?? $this->name_en) : ($this->name_en ?? $this->name_ar);
    }

    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }


        // permissions

        public function getCanUpdateAttribute(){
            return auth()->user()->hasPermissionTo('update_employee');
        }
    
        public function getCanDeleteAttribute()
        {
            return auth()->user()->hasPermissionTo('delete_employee');
        }
    
        public function getCanViewAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('view_all_employee_attachments');
        }
    
        public function getCanCreateAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('create_employee_attachments');
        }
    
        public function getCanUpdateAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('update_employee_attachments');
        }
    
        public function getCanDeleteAttachmentAttribute()
        {
            return auth()->user()->hasPermissionTo('delete_employee_attachments');
        }
}
