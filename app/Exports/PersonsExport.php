<?php

namespace App\Exports;

use App\Models\Person;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PersonsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ($this->query) {
            // إذا كان Collection، نعيده مباشرة
            if ($this->query instanceof \Illuminate\Support\Collection) {
                return $this->query;
            }
            // إذا كان Query Builder، نستدعي get()
            return $this->query->get();
        }

        // تحميل العلاقات المطلوبة لـ full_name accessor
        return Person::with([
            'parent',
            'parent.parent',
            'parent.parent.parent',
            'parent.parent.parent.parent',
            'parent.parent.parent.parent.parent',
            'parent.parent.parent.parent.parent.parent',
            'parent.parent.parent.parent.parent.parent.parent',
            'parent.parent.parent.parent.parent.parent.parent.parent',
            'parent.parent.parent.parent.parent.parent.parent.parent.parent',
            'parent.parent.parent.parent.parent.parent.parent.parent.parent.parent',
            'mother',
            'location'
        ])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'الاسم الكامل',
            'الاسم الأول',
            'اسم العائلة',
            'الجنس',
            'تاريخ الميلاد',
            'العمر',
            'فترة الحياة',
            'تاريخ الوفاة',
            'مكان الميلاد',
            'مكان الإقامة',
            'مكان الوفاة',
            'المقبرة',
            'المهنة',
            'الأب',
            'الأم',
            'الحالة العائلية',
            'السيرة الذاتية',
        ];
    }

    /**
     * @param Person $person
     * @return array
     */
    public function map($person): array
    {
        return [
            $person->id,
            $person->full_name, // استخدام accessor
            $person->first_name,
            $person->last_name ?? '',
            $person->gender == 'male' ? 'ذكر' : 'أنثى',
            $person->birth_date ? $person->birth_date->format('Y-m-d') : '',
            $person->age ?? '',
            $person->life_span ?? '',
            $person->death_date ? $person->death_date->format('Y-m-d') : '',
            $person->birth_place ?? '',
            $person->location_display ?? '',
            $person->death_place ?? '',
            $person->cemetery ?? '',
            $person->occupation ?? '',
            $person->parent ? $person->parent->full_name : '',
            $person->mother ? $person->mother->full_name : '',
            $person->from_outside_the_family ? 'خارج العائلة' : 'داخل العائلة',
            $person->biography ?? '',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
