<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class UltramsgSendLog extends Model
{
    protected $table = 'ultramsg_send_logs';

    protected $fillable = [
        'notification_id',
        'person_id',
        'to_number',
        'message_sent',
        'media_type',
        'media_path',
        'status',
        'ultramsg_id',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function delivery(): MorphOne
    {
        return $this->morphOne(NotificationDelivery::class, 'deliverable');
    }
}
