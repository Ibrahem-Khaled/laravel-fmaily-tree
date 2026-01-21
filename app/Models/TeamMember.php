<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamMember extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'role',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /**
     * العلاقة مع الفريق
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope للقادة
     */
    public function scopeCaptains($query)
    {
        return $query->where('role', 'captain');
    }

    /**
     * Scope للأعضاء
     */
    public function scopeMembers($query)
    {
        return $query->where('role', 'member');
    }
}
