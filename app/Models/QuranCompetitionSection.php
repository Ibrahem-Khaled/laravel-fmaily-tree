<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class QuranCompetitionSection extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'name',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(QuranCompetition::class, 'competition_id');
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'quran_competition_section_person', 'section_id', 'person_id')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('quran_competition_section_person.sort_order');
    }
}

