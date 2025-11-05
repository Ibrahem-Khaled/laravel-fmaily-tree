<?php

namespace App\Exports;

use App\Models\Person;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PersonsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Person::with('location')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'الاسم الأول',
            'اسم العائلة',
            'تاريخ الميلاد',
            'تاريخ الوفاة',
            'الجنس',
            'المهنة',
            'المكان',
            'السيرة الذاتية',
            'معرف الأب/الأم',
            '_lft',
            '_rgt',
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
            $person->first_name,
            $person->last_name,
            $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
            $person->death_date ? $person->death_date->format('Y-m-d') : null,
            $person->gender,
            $person->occupation,
            $person->location_display ?? null,
            $person->biography,
            $person->parent_id,
            $person->_lft,
            $person->_rgt,
        ];
    }
}
