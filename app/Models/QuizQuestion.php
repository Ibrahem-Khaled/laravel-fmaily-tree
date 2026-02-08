<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'quiz_competition_id',
        'question_text',
        'answer_type',
        'winners_count',
        'display_order',
    ];

    protected $casts = [
        'winners_count' => 'integer',
        'display_order' => 'integer',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(QuizCompetition::class, 'quiz_competition_id');
    }

    public function choices(): HasMany
    {
        return $this->hasMany(QuizQuestionChoice::class)->orderBy('id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function winners(): HasMany
    {
        return $this->hasMany(QuizWinner::class)->orderBy('position');
    }

    public function isActive(): bool
    {
        $comp = $this->competition;
        if (!$comp || !$comp->start_at || !$comp->end_at) {
            return false;
        }
        $now = now();
        return $now->gte($comp->start_at) && $now->lte($comp->end_at);
    }

    public function hasEnded(): bool
    {
        $comp = $this->competition;
        if (!$comp || !$comp->end_at) {
            return false;
        }
        return now()->gt($comp->end_at);
    }

    public function hasNotStarted(): bool
    {
        $comp = $this->competition;
        if (!$comp || !$comp->start_at) {
            return true;
        }
        return now()->lt($comp->start_at);
    }

    public function getCorrectAnswersCount(): int
    {
        return $this->answers()->where('is_correct', true)->count();
    }

    public function selectRandomWinners(): int
    {
        $correctUserIds = $this->answers()
            ->where('is_correct', true)
            ->pluck('user_id')
            ->shuffle()
            ->take($this->winners_count)
            ->values()
            ->all();

        $this->winners()->delete();

        foreach ($correctUserIds as $position => $userId) {
            QuizWinner::create([
                'quiz_question_id' => $this->id,
                'user_id' => $userId,
                'position' => $position + 1,
            ]);
        }

        return count($correctUserIds);
    }
}
