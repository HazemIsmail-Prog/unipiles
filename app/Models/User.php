<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['all_permissions', 'can_update', 'can_delete'];

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function getAllPermissionsAttribute()
    {
        // get unique permission names from permissions and roles and cache the result
        return cache()->remember("user_permissions_{$this->id}", 60, function () {
            return $this
                ->permissions()
                ->pluck('name')
                ->merge($this->roles()
                    ->with('permissions')
                    ->get()
                    ->pluck('permissions')
                    ->flatten()
                    ->pluck('name'))
                ->unique()
                ->sort()
                ->values(); // convert to non-associative collection
        });
    }

    // function to clear the cache
    public function clearCache()
    {
        cache()->forget("user_permissions_{$this->id}");
    }

    // haspermission
    public function hasPermissionTo($permission)
    {
        return $this->all_permissions->contains($permission);
    }


    // permissions

    public function getCanUpdateAttribute(){
        return auth()->user()->hasPermissionTo('update_user');
    }

    public function getCanDeleteAttribute()
    {
        return auth()->user()->hasPermissionTo('delete_user');
    }
}
