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

class QuizCompetitionInfoSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(
        protected QuizCompetition $competition
    ) {}

    public function collection(): Collection
    {
        $questions = $this->competition->questions;
        $totalAnswers = $questions->sum(fn ($q) => $q->answers->count());
        $totalWinners = $questions->sum(fn ($q) => $q->winners->count());

        return collect([
            [
                'title' => $this->competition->title,
                'description' => strip_tags($this->competition->description ?? ''),
                'start_at' => $this->competition->start_at?->format('Y-m-d H:i'),
                'end_at' => $this->competition->end_at?->format('Y-m-d H:i'),
                'is_active' => $this->competition->is_active ? 'نعم' : 'لا',
                'questions_count' => $questions->count(),
                'total_answers' => $totalAnswers,
                'total_winners' => $totalWinners,
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'العنوان',
            'الوصف',
            'تاريخ البداية',
            'تاريخ النهاية',
            'نشط',
            'عدد الأسئلة',
            'إجمالي الإجابات',
            'إجمالي الفائزين',
        ];
    }

    public function map($row): array
    {
        return [
            $row['title'],
            $row['description'],
            $row['start_at'] ?? '—',
            $row['end_at'] ?? '—',
            $row['is_active'],
            $row['questions_count'],
            $row['total_answers'],
            $row['total_winners'],
        ];
    }

    public function title(): string
    {
        return 'المسابقة';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
