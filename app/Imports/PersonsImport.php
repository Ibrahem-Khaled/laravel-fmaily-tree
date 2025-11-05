<?php

namespace App\Imports;

use App\Models\Person;
use App\Models\Location;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;

class PersonsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip if required fields are missing
            if (empty($row['الاسم_الأول']) || empty($row['اسم_العائلة']) || empty($row['الجنس'])) {
                continue;
            }
            
            // Create or update person
            $person = Person::updateOrCreate(
                [
                    'id' => $row['id'] ?? null,
                ],
                [
                    'first_name' => $row['الاسم_الأول'],
                    'last_name' => $row['اسم_العائلة'],
                    'birth_date' => !empty($row['تاريخ_الميلاد']) ? Carbon::parse($row['تاريخ_الميلاد']) : null,
                    'death_date' => !empty($row['تاريخ_الوفاة']) ? Carbon::parse($row['تاريخ_الوفاة']) : null,
                    'gender' => $row['الجنس'],
                    'occupation' => $row['المهنة'] ?? null,
                    'location_id' => !empty($row['المكان']) ? Location::findOrCreateByName($row['المكان'])->id : null,
                    'biography' => $row['السيرة_الذاتية'] ?? null,
                    'parent_id' => $row['معرف_الأب_الأم'] ?? null,
                    '_lft' => $row['_lft'] ?? null,
                    '_rgt' => $row['_rgt'] ?? null,
                ]
            );
        }
        
        // Rebuild the tree to ensure consistency
        Person::fixTree();
    }
}
