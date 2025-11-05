<?php

namespace App\Exports;

use App\Models\Person;
use Illuminate\Support\Facades\Storage;

class PersonsJsonExport
{
    public function export()
    {
        $persons = Person::with('location')->get();
        
        $data = $persons->map(function ($person) {
            return [
                'id' => $person->id,
                'first_name' => $person->first_name,
                'last_name' => $person->last_name,
                'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
                'death_date' => $person->death_date ? $person->death_date->format('Y-m-d') : null,
                'gender' => $person->gender,
                'occupation' => $person->occupation,
                'location' => $person->location_display ?? null,
                'biography' => $person->biography,
                'parent_id' => $person->parent_id,
                '_lft' => $person->_lft,
                '_rgt' => $person->_rgt,
            ];
        });
        
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        $filename = 'persons_' . now()->format('Y-m-d_H-i-s') . '.json';
        Storage::put('public/exports/' . $filename, $json);
        
        return Storage::download('public/exports/' . $filename);
    }
}

