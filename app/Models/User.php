<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
// use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements AuditableContract
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age',
        'email',
        'password',
        'email_verified_at',
        'phone',
        'avatar',
        'address',
        'status',
        'is_from_ancestry',
        'mother_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_from_ancestry' => 'boolean',
    ];

    /**
     * Disable model caching to ensure fresh data
     */
    protected $cacheFor = 0;

    /**
     * العلاقة مع الفرق التي ينتمي إليها المستخدم
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps()
            ->orderByPivot('joined_at');
    }

    /**
     * العلاقة مع الفرق التي أنشأها المستخدم
     */
    public function createdTeams()
    {
        return $this->hasMany(Team::class, 'created_by_user_id');
    }

    /**
     * العلاقة مع سجلات العضوية في الفرق
     */
    public function teamMemberships()
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * العلاقة مع المسابقات التي أنشأها المستخدم
     */
    public function createdCompetitions()
    {
        return $this->hasMany(Competition::class, 'created_by');
    }

    /**
     * العلاقة مع تسجيلات المسابقات
     */
    public function competitionRegistrations()
    {
        return $this->hasMany(CompetitionRegistration::class);
    }

    /**
     * العلاقة مع سجلات المشي
     */
    public function walkingRecords()
    {
        return $this->hasMany(WalkingRecord::class);
    }

    /**
     * الروابط المهمة التي اقترحها المستخدم
     */
    public function suggestedImportantLinks()
    {
        return $this->hasMany(ImportantLink::class, 'submitted_by_user_id');
    }
}
