<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompetitionRegistration extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'user_id',
        'team_id',
        'has_brother',
    ];

    protected $casts = [
        'has_brother' => 'boolean',
    ];

    /**
     * العلاقة مع المسابقة
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع الفريق
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * العلاقة مع التصنيفات المختارة (many-to-many)
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'competition_registration_categories')
            ->withTimestamps()
            ->orderBy('categories.sort_order')
            ->orderBy('categories.name');
    }
}
