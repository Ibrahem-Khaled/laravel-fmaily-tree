<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportantLinkMedia extends BaseModel
{
    use HasFactory;

    protected $table = 'important_link_media';

    protected $fillable = [
        'important_link_id',
        'kind',
        'path',
        'title',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    protected $appends = ['file_url'];

    protected $hidden = ['path'];

    public function importantLink(): BelongsTo
    {
        return $this->belongsTo(ImportantLink::class);
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }

    public function isImage(): bool
    {
        return $this->kind === 'image';
    }

    public function isVideo(): bool
    {
        return $this->kind === 'video';
    }
}
