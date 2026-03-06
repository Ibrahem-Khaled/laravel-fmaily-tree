<?php

namespace App\Exports;

use App\Models\Competition;
use App\Models\CompetitionRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompetitionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $competition;

    public function __construct(Competition $competition)
    {
        $this->competition = $competition;
    }

    public function collection()
    {
        // Get all registrations for this competition
        return CompetitionRegistration::with(['user', 'team.members', 'categories'])
            ->where('competition_id', $this->competition->id)
            ->get();
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'رقم الهاتف',
            'البريد الإلكتروني',
            'التصنيفات المختارة',
            'اسم الفريق',
            'الدور',
            'حالة الفريق',
            'تاريخ التسجيل'
        ];
    }

    public function map($registration): array
    {
        $user = $registration->user;
        $team = $registration->team;
        
        $categoriesNames = $registration->categories->pluck('name')->implode('، ');

        $teamName = $team ? $team->name : '-';
        
        // Determine role in team if any
        $role = '-';
        if ($team && $user) {
            $member = $team->members->firstWhere('id', $user->id);
            if ($member && isset($member->pivot->role)) {
                $role = $member->pivot->role === 'captain' ? 'قائد' : 'عضو';
            }
        }

        $teamStatus = '-';
        if ($team) {
            $teamStatus = $team->is_complete ? 'مكتمل' : 'غير مكتمل';
        }

        return [
            $user ? $user->name : '-',
            $user ? ($user->phone ?? '-') : '-',
            $user ? ($user->email ?? '-') : '-',
            $categoriesNames ?: '-',
            $teamName,
            $role,
            $teamStatus,
            $registration->created_at ? $registration->created_at->format('Y-m-d H:i') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
