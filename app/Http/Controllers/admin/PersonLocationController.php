<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\PersonLocation;
use App\Models\Location;
use Illuminate\Http\Request;

class PersonLocationController extends Controller
{
    public function store(Request $request, Person $person)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'label' => 'nullable|string|max:100',
            'url' => 'nullable|url|max:1000',
            'is_primary' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // إذا تم تحديد موقع أساسي، إلغاء الأساسية من المواقع الأخرى
        if ($validated['is_primary'] ?? false) {
            $person->personLocations()->update(['is_primary' => false]);
        }

        $validated['person_id'] = $person->id;
        $validated['sort_order'] = $validated['sort_order'] ?? $person->personLocations()->max('sort_order') + 1 ?? 0;
        $validated['is_primary'] = $validated['is_primary'] ?? false;

        PersonLocation::create($validated);

        // مسح الـ cache للشخص
        cache()->forget("person_details_{$person->id}");
        cache()->forget("person_children_details_{$person->id}");

        return redirect()->back()->with('success', 'تم إضافة الموقع بنجاح');
    }

    public function update(Request $request, Person $person, $personLocationId)
    {
        $personLocation = PersonLocation::findOrFail($personLocationId);

        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'label' => 'nullable|string|max:100',
            'url' => 'nullable|url|max:1000',
            'is_primary' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // إذا تم تحديد موقع أساسي، إلغاء الأساسية من المواقع الأخرى
        if ($validated['is_primary'] ?? false) {
            $person->personLocations()->where('id', '!=', $personLocation->id)->update(['is_primary' => false]);
        }

        $personLocation->update($validated);

        // مسح الـ cache للشخص
        cache()->forget("person_details_{$person->id}");
        cache()->forget("person_children_details_{$person->id}");

        return redirect()->back()->with('success', 'تم تحديث الموقع بنجاح');
    }

    public function destroy(Person $person, $personLocationId)
    {
        $personLocation = PersonLocation::findOrFail($personLocationId);
        $personId = $personLocation->person_id;

        $personLocation->delete();

        // مسح الـ cache للشخص
        cache()->forget("person_details_{$personId}");
        cache()->forget("person_children_details_{$personId}");

        return redirect()->back()->with('success', 'تم حذف الموقع بنجاح');
    }
}
