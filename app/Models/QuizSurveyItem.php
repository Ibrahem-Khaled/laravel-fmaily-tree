<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizSurveyItem extends BaseModel
{
    protected $fillable = [
        'quiz_question_id',
        'sort_order',
        'block_type',
        'body_text',
        'media_path',
        'response_kind',
        'rating_max',
        'number_min',
        'number_max',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'rating_max' => 'integer',
        'number_min' => 'integer',
        'number_max' => 'integer',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    public function labelForAdmin(): string
    {
        $prefix = match ($this->block_type) {
            'image' => 'صورة',
            'video' => 'فيديو',
            default => 'نص',
        };
        $body = $this->body_text ? \Illuminate\Support\Str::limit(strip_tags($this->body_text), 40) : '';

        return trim($prefix.($body ? ': '.$body : '').' #'.$this->id);
    }
}
