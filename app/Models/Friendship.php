<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friendship extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'friend_id',
        'description',
        'friendship_story',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'friend_id');
    }
}
