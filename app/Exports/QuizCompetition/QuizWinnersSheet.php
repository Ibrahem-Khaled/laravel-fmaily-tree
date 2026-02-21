<?php

namespace App\Exports\QuizCompetition;

use App\Models\QuizCompetition;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuizWinnersSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(
        protected QuizCompetition $competition
    ) {}

    public function collection(): Collection
    {
        $rows = collect();
        foreach ($this->competition->questions as $question) {
            foreach ($question->winners->sortBy('position') as $winner) {
                $answer = $question->answers->where('user_id', $winner->user_id)->first();
                $rows->push((object) [
                    'question' => $question,
                    'winner' => $winner,
                    'answer' => $answer,
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'رقم السؤال',
            'نص السؤال (مختصر)',
            'المركز',
            'الاسم',
            'رقم الهاتف',
            'اسم الأم',
            'الإجابة',
            'تاريخ الإجابة',
        ];
    }

    public function map($row): array
    {
        $question = $row->question;
        $winner = $row->winner;
        $answer = $row->answer;
        $user = $winner->user;

        $positionLabel = $winner->position_name ?? "المركز {$winner->position}";
        $motherName = ($user && $user->is_from_ancestry && $user->mother_name) ? $user->mother_name : '—';

        $answerText = '—';
        $answeredAt = '—';
        if ($answer) {
            $answerText = $answer->answer_type === 'choice'
                ? ($question->choices->firstWhere('id', (int) $answer->answer)?->choice_text ?? $answer->answer)
                : \Illuminate\Support\Str::limit($answer->answer ?? '', 200);
            $answeredAt = $answer->created_at?->format('Y-m-d H:i') ?? '—';
        }

        return [
            $question->id,
            \Illuminate\Support\Str::limit(strip_tags($question->question_text ?? ''), 80),
            $positionLabel,
            $user->name ?? '—',
            $user->phone ?? '—',
            $motherName,
            $answerText,
            $answeredAt,
        ];
    }

    public function title(): string
    {
        return 'الفائزون';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
