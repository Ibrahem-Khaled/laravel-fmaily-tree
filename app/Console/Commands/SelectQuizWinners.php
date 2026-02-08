<?php

namespace App\Console\Commands;

use App\Models\QuizQuestion;
use Illuminate\Console\Command;

class SelectQuizWinners extends Command
{
    protected $signature = 'quiz:select-winners';

    protected $description = 'اختيار الفائزين عشوائياً للأسئلة المنتهية التي لم يتم اختيار فائزين لها';

    public function handle(): int
    {
        $questions = QuizQuestion::whereHas('competition', function ($q) {
            $q->whereNotNull('end_at')->where('end_at', '<', now());
        })
            ->whereDoesntHave('winners')
            ->whereHas('answers', fn($q) => $q->where('is_correct', true))
            ->get();

        $count = 0;

        foreach ($questions as $question) {
            $winnersCount = $question->selectRandomWinners();
            $count += $winnersCount;
            $this->info("تم اختيار {$winnersCount} فائز(ين) لسؤال #{$question->id}");
        }

        $this->info("تم اختيار الفائزين لـ {$questions->count()} سؤال بشكل إجمالي.");
        return Command::SUCCESS;
    }
}
