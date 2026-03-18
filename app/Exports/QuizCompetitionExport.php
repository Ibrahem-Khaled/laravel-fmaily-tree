<?php

namespace App\Exports;

use App\Exports\QuizCompetition\QuizAnswersSheet;
use App\Exports\QuizCompetition\QuizCompetitionInfoSheet;
use App\Exports\QuizCompetition\QuizQuestionsSheet;
use App\Exports\QuizCompetition\QuizWinnersSheet;
use App\Models\QuizCompetition;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class QuizCompetitionExport implements WithMultipleSheets
{
    public function __construct(
        protected QuizCompetition $competition
    ) {
        $this->competition->load([
            'questions.choices',
            'questions.surveyItems',
            'questions.answers.user',
            'questions.winners.user',
        ]);
    }

    /**
     * @return array<int, \Maatwebsite\Excel\Concerns\WithTitle>
     */
    public function sheets(): array
    {
        return [
            new QuizCompetitionInfoSheet($this->competition),
            new QuizQuestionsSheet($this->competition),
            new QuizAnswersSheet($this->competition),
            new QuizWinnersSheet($this->competition),
        ];
    }
}
