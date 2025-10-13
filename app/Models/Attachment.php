<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $casts = [
        'expires_at' => 'date:Y-m-d',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }

    protected $appends = ['full_path', 'description', 'formatted_expires_at', 'is_expired', 'notify_at', 'notify_now', 'encrypted_id'];

    public function getFullPathAttribute()
    {
        return $this->path;
        if (Storage::disk('s3')->exists($this->path) && $this->path) {
            return Storage::disk('s3')->url($this->path);
        }
        return null;
    }

    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function getFormattedExpiresAtAttribute()
    {
        return $this->expires_at ? $this->expires_at->format('d/m/Y') : null;
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at ? $this->expires_at->isPast() : false;
    }

    public function getNotifyAtAttribute()
    {
        if (!$this->notify_before || !$this->expires_at || $this->is_expired) {
            return null;
        }
        return $this->expires_at->subDays($this->notify_before);
    }

    public function getNotifyNowAttribute()
    {
        if (!$this->notify_at) {
            return null;
        }
        return $this->notify_at->isPast();
    }

    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
    }


}
