<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuranCompetitionWinner extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'person_id',
        'position',
        'category',
        'notes',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    /**
     * العلاقة مع المسابقة
     */
    public function competition()
    {
        return $this->belongsTo(QuranCompetition::class, 'competition_id');
    }

    /**
     * العلاقة مع الشخص الفائز
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * الحصول على اسم المركز
     */
    public function getPositionNameAttribute(): string
    {
        $positions = [
            1 => 'المركز الأول',
            2 => 'المركز الثاني',
            3 => 'المركز الثالث',
        ];

        return $positions[$this->position] ?? "المركز {$this->position}";
    }
}

