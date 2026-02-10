<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalkingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'steps',
        'distance_km',
        'duration_minutes',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'steps' => 'integer',
        'distance_km' => 'decimal:2',
        'duration_minutes' => 'integer',
    ];

    /**
     * Get the user that owns the walking record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
