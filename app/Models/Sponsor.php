<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    public function competitions()
    {
        return $this->belongsToMany(QuizCompetition::class, 'quiz_competition_sponsor');
    }
}
