<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswerChoice extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'quiz_answer_id',
        'quiz_question_choice_id',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(QuizAnswer::class, 'quiz_answer_id');
    }

    public function choice(): BelongsTo
    {
        return $this->belongsTo(QuizQuestionChoice::class, 'quiz_question_choice_id');
    }
}
