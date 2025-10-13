<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $appends = ['description', 'can_update', 'can_delete'];
    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

        // permissions

        public function getCanUpdateAttribute(){
            return auth()->user()->hasPermissionTo('update_permission');
        }
    
        public function getCanDeleteAttribute()
        {
            return auth()->user()->hasPermissionTo('delete_permission');
        }
}
