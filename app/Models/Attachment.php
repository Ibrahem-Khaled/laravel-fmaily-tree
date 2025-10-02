<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Attachment extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'path',
        'file_name',
        'mime_type',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }

    protected static function booted(): void
    {
        static::deleting(function (Attachment $attachment) {
            if ($attachment->path) {
                Storage::disk('public')->delete($attachment->path);
            }
        });
    }
}
