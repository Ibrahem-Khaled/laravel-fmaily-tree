<?php

namespace App\Exports;

use App\Models\Person;
use Barryvdh\DomPDF\Facade\Pdf;

class PersonsPdfExport
{
    public function export()
    {
        $persons = Person::all();
        
        $pdf = PDF::loadView('exports.persons-pdf', [
            'persons' => $persons
        ]);
        
        return $pdf->download('persons.pdf');
    }
}

