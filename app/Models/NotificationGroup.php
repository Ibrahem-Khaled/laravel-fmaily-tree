<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NotificationGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'notification_group_person')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('notification_group_person.sort_order');
    }
}
