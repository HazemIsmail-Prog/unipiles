<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $appends = ['name', 'can_view_attachment', 'can_create_attachment', 'can_update', 'can_delete', 'can_update_attachment', 'can_delete_attachment'];

    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? ($this->name_ar ?? $this->name_en) : ($this->name_en ?? $this->name_ar);
    }

    public function asset_type()
    {
        return $this->belongsTo(AssetType::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }





    // permissions

    public function getCanUpdateAttribute(){
        return auth()->user()->hasPermissionTo('update_asset');
    }

    public function getCanDeleteAttribute()
    {
        return auth()->user()->hasPermissionTo('delete_asset');
    }

    public function getCanViewAttachmentAttribute()
    {
        return auth()->user()->hasPermissionTo('view_all_asset_attachments');
    }

    public function getCanCreateAttachmentAttribute()
    {
        return auth()->user()->hasPermissionTo('create_asset_attachments');
    }

    public function getCanUpdateAttachmentAttribute()
    {
        return auth()->user()->hasPermissionTo('update_asset_attachments');
    }

    public function getCanDeleteAttachmentAttribute()
    {
        return auth()->user()->hasPermissionTo('delete_asset_attachments');
    }


}
