<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class QuizQuestion extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'quiz_competition_id',
        'question_text',
        'description',
        'answer_type',
        'is_multiple_selections',
        'winners_count',
        'display_order',
        'prize',
        'groups_count',
        'vote_max_selections',
        'require_prior_registration',
    ];

    protected $casts = [
        'is_multiple_selections' => 'boolean',
        'winners_count' => 'integer',
        'groups_count' => 'integer',
        'display_order' => 'integer',
        'vote_max_selections' => 'integer',
        'require_prior_registration' => 'boolean',
    ];

    protected function prize(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value === null || $value === '') {
                    return [];
                }

                if (is_array($value)) {
                    return $value;
                }

                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return is_array($decoded) ? $decoded : [];
                    }

                    return [$value];
                }

                return [];
            },
            set: fn ($value) => $value === null ? null : json_encode(is_array($value) ? $value : [$value], JSON_UNESCAPED_UNICODE)
        );
    }

    protected static function booted(): void
    {
        static::deleting(function (QuizQuestion $question) {
            $question->loadMissing('surveyItems');
            $disk = Storage::disk('public');
            foreach ($question->surveyItems as $item) {
                if ($item->media_path) {
                    $disk->delete($item->media_path);
                }
            }
        });
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(QuizCompetition::class, 'quiz_competition_id');
    }

    public function choices(): HasMany
    {
        return $this->hasMany(QuizQuestionChoice::class)->orderBy('id');
    }

    public function surveyItems(): HasMany
    {
        return $this->hasMany(QuizSurveyItem::class)->orderBy('sort_order')->orderBy('id');
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
        if (! $comp || ! $comp->start_at || ! $comp->end_at) {
            return false;
        }
        $now = now();

        return $now->gte($comp->start_at) && $now->lte($comp->end_at);
    }

    public function hasEnded(): bool
    {
        $comp = $this->competition;
        if (! $comp || ! $comp->end_at) {
            return false;
        }

        return now()->gt($comp->end_at);
    }

    public function hasNotStarted(): bool
    {
        $comp = $this->competition;
        if (! $comp || ! $comp->start_at) {
            return true;
        }

        return now()->lt($comp->start_at);
    }

    public function getCorrectAnswersCount(): int
    {
        return $this->answers()->where('is_correct', true)->count();
    }

    public function getRequiredCorrectAnswersCount(): int
    {
        if ($this->answer_type !== 'multiple_choice') {
            return 1;
        }

        return $this->choices()->where('is_correct', true)->count();
    }

    public function selectRandomWinners(): int
    {
        $this->winners()->delete();

        return $this->fillVacantWinners();
    }

    public function fillVacantWinners(): int
    {
        $existingWinnerUserIds = $this->winners()->pluck('user_id')->all();
        $currentWinnerCount = $this->winners()->count();
        $vacancies = $this->winners_count - $currentWinnerCount;

        if ($vacancies <= 0) {
            return 0;
        }

        $newWinnerUserIds = $this->answers()
            ->where('is_correct', true)
            ->whereNotIn('user_id', $existingWinnerUserIds)
            ->pluck('user_id')
            ->shuffle()
            ->take($vacancies)
            ->values()
            ->all();

        foreach ($newWinnerUserIds as $index => $userId) {
            QuizWinner::create([
                'quiz_question_id' => $this->id,
                'user_id' => $userId,
                'position' => $currentWinnerCount + $index + 1,
            ]);
        }

        // إزالة التخزين المؤقت لكي تظهر النتيجة الجديدة فوراً للجمهور
        \Illuminate\Support\Facades\Cache::forget('quiz_winner_json_'.$this->id);
        \Illuminate\Support\Facades\Cache::forget('quiz-winner-selection-'.$this->id);

        return count($newWinnerUserIds);
    }
}
