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

class QuizAnswersSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(
        protected QuizCompetition $competition
    ) {}

    public function collection(): Collection
    {
        $rows = collect();
        foreach ($this->competition->questions as $question) {
            foreach ($question->answers as $answer) {
                $rows->push((object) [
                    'question' => $question,
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
            'المشارك',
            'رقم الهاتف',
            'اسم الأم',
            'الإجابة',
            'النتيجة',
            'تاريخ الإجابة',
        ];
    }

    public function map($row): array
    {
        $question = $row->question;
        $answer = $row->answer;
        $user = $answer->user;

        $answerText = $this->resolveAnswerText($answer, $question);
        $motherName = ($user && $user->is_from_ancestry && $user->mother_name) ? $user->mother_name : '—';

        return [
            $question->id,
            \Illuminate\Support\Str::limit(strip_tags($question->question_text ?? ''), 80),
            $user->name ?? '—',
            $user->phone ?? '—',
            $motherName,
            $answerText,
            $answer->is_correct ? 'صحيح' : 'خاطئ',
            $answer->created_at?->format('Y-m-d H:i') ?? '—',
        ];
    }

    protected function resolveAnswerText($answer, $question): string
    {
        if ($answer->answer_type === 'choice') {
            $choice = $question->choices->firstWhere('id', (int) $answer->answer);

            return $choice ? $choice->choice_text : $answer->answer;
        }

        return \Illuminate\Support\Str::limit($answer->answer ?? '', 200);
    }

    public function title(): string
    {
        return 'الإجابات';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
