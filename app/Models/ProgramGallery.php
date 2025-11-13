<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'title',
        'description',
        'gallery_order',
    ];

    protected $casts = [
        'gallery_order' => 'integer',
    ];

    /**
     * Program that this gallery belongs to.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'program_id');
    }

    /**
     * Images in this gallery.
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'gallery_id')->orderBy('program_media_order');
    }
}
