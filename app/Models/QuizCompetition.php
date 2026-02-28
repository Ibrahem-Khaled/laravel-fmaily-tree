<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizCompetition extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_active',
        'display_order',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('display_order');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(QuizRegistration::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('created_at', 'desc');
    }

    /**
     * Time at which questions become visible to visitors (start_at + configured delay in seconds).
     * Returns null if start_at is not set.
     */
    public function getQuestionsVisibleAt(): ?Carbon
    {
        if (!$this->start_at) {
            return null;
        }

        $delaySeconds = config('quiz.question_visible_after_seconds', 300);

        return $this->start_at->copy()->addSeconds($delaySeconds);
    }

    /**
     * الحصول على أقرب حدث قادم (مسابقة - وقت السؤال يحدد من المسابقة)
     */
    public static function getNextUpcomingEvent(): ?array
    {
        $nextCompetition = self::where('is_active', true)
            ->whereNotNull('start_at')
            ->where('start_at', '>', now())
            ->orderBy('start_at')
            ->first();

        if (!$nextCompetition) {
            return null;
        }

        return [
            'type' => 'competition',
            'target_at' => $nextCompetition->start_at,
            'title' => $nextCompetition->title,
            'description' => $nextCompetition->description,
        ];
    }
}
