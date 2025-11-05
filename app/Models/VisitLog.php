<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'route_name',
        'referer',
        'metadata',
        'session_id',
        'request_id',
        'response_time',
        'status_code',
        'duration',
        'is_unique_visit',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'duration' => 'integer',
        'is_unique_visit' => 'boolean',
    ];

    /**
     * Get the user that made this visit
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get country from metadata
     */
    public function getCountryAttribute(): ?string
    {
        return $this->metadata['country'] ?? null;
    }

    /**
     * Get city from metadata
     */
    public function getCityAttribute(): ?string
    {
        return $this->metadata['city'] ?? null;
    }

    /**
     * Get browser from metadata
     */
    public function getBrowserAttribute(): ?string
    {
        return $this->metadata['browser'] ?? null;
    }

    /**
     * Get device from metadata
     */
    public function getDeviceAttribute(): ?string
    {
        return $this->metadata['device'] ?? null;
    }

    /**
     * Get platform from metadata
     */
    public function getPlatformAttribute(): ?string
    {
        return $this->metadata['platform'] ?? null;
    }

    /**
     * Scope: Filter by week
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [
            $startDate->startOfDay(),
            $endDate->endOfDay()
        ]);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by IP
     */
    public function scopeForIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }
}

