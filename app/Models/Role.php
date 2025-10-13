<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $appends = ['can_update', 'can_delete'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

        // permissions

        public function getCanUpdateAttribute(){
            return auth()->user()->hasPermissionTo('update_role');
        }
    
        public function getCanDeleteAttribute()
        {
            return auth()->user()->hasPermissionTo('delete_role');
        }
}
