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

        return redirect()->back()->with('success', 'تم إضافة الموقع بنجاح');
    }

    public function update(Request $request, Person $person, PersonLocation $personLocation)
    {
        // التأكد من أن الموقع يخص الشخص المحدد
        if ($personLocation->person_id !== $person->id) {
            abort(403);
        }

        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'label' => 'nullable|string|max:100',
            'is_primary' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // إذا تم تحديد موقع أساسي، إلغاء الأساسية من المواقع الأخرى
        if ($validated['is_primary'] ?? false) {
            $person->personLocations()->where('id', '!=', $personLocation->id)->update(['is_primary' => false]);
        }

        $personLocation->update($validated);

        return redirect()->back()->with('success', 'تم تحديث الموقع بنجاح');
    }

    public function destroy(Person $person, PersonLocation $personLocation)
    {
        // التأكد من أن الموقع يخص الشخص المحدد
        if ($personLocation->person_id !== $person->id) {
            abort(403);
        }

        $personLocation->delete();

        return redirect()->back()->with('success', 'تم حذف الموقع بنجاح');
    }
}
