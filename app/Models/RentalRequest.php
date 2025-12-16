<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'name',
        'phone',
        'email',
        'start_date',
        'end_date',
        'notes',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'معلق',
            'approved' => 'موافق عليه',
            'rejected' => 'مرفوض',
            'completed' => 'مكتمل',
            default => $this->status,
        };
    }
}
