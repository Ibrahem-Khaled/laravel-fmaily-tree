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

class QuizQuestionsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(
        protected QuizCompetition $competition
    ) {}

    public function collection(): Collection
    {
        return $this->competition->questions;
    }

    public function headings(): array
    {
        return [
            '#',
            'نص السؤال',
            'نوع الإجابة',
            'عدد الفائزين المطلوب',
            'ترتيب العرض',
            'عدد الإجابات',
            'إجابات صحيحة',
            'إجابات خاطئة',
            'عدد الفائزين الحالي',
        ];
    }

    public function map($question): array
    {
        $answers = $question->answers;
        $correct = $answers->where('is_correct', true)->count();
        $wrong = $answers->where('is_correct', false)->count();

        return [
            $question->id,
            strip_tags($question->question_text ?? ''),
            $question->answer_type === 'multiple_choice' ? 'اختيار من متعدد' : 'إجابة حرة',
            $question->winners_count,
            $question->display_order ?? 0,
            $answers->count(),
            $correct,
            $wrong,
            $question->winners->count(),
        ];
    }

    public function title(): string
    {
        return 'الأسئلة';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
