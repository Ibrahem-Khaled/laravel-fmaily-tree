<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'title',
        'url',
        'description',
        'link_order',
    ];

    protected $casts = [
        'link_order' => 'integer',
    ];

    /**
     * Program relationship.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'program_id');
    }
}



