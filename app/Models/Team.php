<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'name',
        'created_by_user_id',
        'is_complete',
    ];

    protected $casts = [
        'is_complete' => 'boolean',
    ];

    /**
     * العلاقة مع المسابقة
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * العلاقة مع منشئ الفريق
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * العلاقة مع الأعضاء
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps()
            ->orderByPivot('joined_at');
    }

    /**
     * العلاقة مع سجلات الأعضاء
     */
    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * التحقق من اكتمال الفريق
     */
    public function checkCompletion(): void
    {
        $memberCount = $this->members()->count();
        $this->is_complete = $memberCount >= $this->competition->team_size;
        $this->save();
    }

    /**
     * الحصول على عدد الأعضاء الحالي
     */
    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }

    /**
     * الحصول على عدد المقاعد المتاحة
     */
    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->competition->team_size - $this->member_count);
    }

    /**
     * الحصول على رابط التسجيل في الفريق
     */
    public function getRegistrationUrlAttribute(): string
    {
        return route('competitions.team.register', ['team' => $this->id]);
    }

    /**
     * Scope للفرق المكتملة
     */
    public function scopeComplete($query)
    {
        return $query->where('is_complete', true);
    }

    /**
     * Scope للفرق غير المكتملة
     */
    public function scopeIncomplete($query)
    {
        return $query->where('is_complete', false);
    }
}
