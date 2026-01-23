<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Competition extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'game_type',
        'team_size',
        'registration_token',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'team_size' => 'integer',
    ];

    /**
     * توليد رمز التسجيل تلقائياً
     */
    protected static function booted(): void
    {
        static::creating(function (Competition $competition) {
            if (empty($competition->registration_token)) {
                $competition->registration_token = Str::random(32);
            }
        });
    }

    /**
     * العلاقة مع الفرق
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * العلاقة مع التسجيلات
     */
    public function registrations()
    {
        return $this->hasMany(CompetitionRegistration::class);
    }

    /**
     * العلاقة مع منشئ المسابقة
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope للمسابقات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * التحقق من أن المسابقة في فترة التسجيل
     */
    public function isRegistrationOpen(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * الحصول على رابط التسجيل
     */
    public function getRegistrationUrlAttribute(): string
    {
        return route('competitions.register', ['token' => $this->registration_token]);
    }
}
