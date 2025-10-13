<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    protected $appends = ['name', 'can_update', 'can_delete'];

    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? ($this->name_ar ?? $this->name_en) : ($this->name_en ?? $this->name_ar);
    }


        // permissions

        public function getCanUpdateAttribute(){
            return auth()->user()->hasPermissionTo('update_title');
        }
    
        public function getCanDeleteAttribute()
        {
            return auth()->user()->hasPermissionTo('delete_title');
        }

}
