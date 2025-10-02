<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Breastfeeding extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'nursing_mother_id',
        'breastfed_child_id',
        'start_date',
        'end_date',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the nursing mother (الأم المرضعة)
     */
    public function nursingMother(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'nursing_mother_id');
    }

    /**
     * Get the breastfed child (الطفل المرتضع)
     */
    public function breastfedChild(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'breastfed_child_id');
    }

    /**
     * Scope for active breastfeeding relationships
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive breastfeeding relationships
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Get the duration of breastfeeding in days
     */
    public function getDurationInDaysAttribute(): ?int
    {
        if (!$this->start_date) {
            return null;
        }

        $endDate = $this->end_date ?? now();
        return $this->start_date->diffInDays($endDate);
    }

    /**
     * Get the duration of breastfeeding in months
     */
    public function getDurationInMonthsAttribute(): ?int
    {
        if (!$this->start_date) {
            return null;
        }

        $endDate = $this->end_date ?? now();
        return $this->start_date->diffInMonths($endDate);
    }

    /**
     * Check if breastfeeding is currently active
     */
    public function getIsCurrentlyActiveAttribute(): bool
    {
        return $this->is_active && (!$this->end_date || $this->end_date->isFuture());
    }
}
