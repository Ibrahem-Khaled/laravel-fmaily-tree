<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $gender = $request->input('gender');

        $people = Person::when($search, function ($query, $search) {
            return $query->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%");
        })
            ->when($gender, function ($query, $gender) {
                return $query->where('gender', $gender);
            })
            ->defaultOrder()
            ->paginate(20);

        $stats = [
            'total' => Person::count(),
            'male' => Person::where('gender', 'male')->count(),
            'female' => Person::where('gender', 'female')->count(),
            'living' => Person::whereNull('death_date')->count(),
            'with_photos' => Person::whereNotNull('photo_url')->count(),
        ];

        return view('people.index', compact('people', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'gender' => 'required|in:male,female',
            'photo' => 'nullable|image|max:2048',
            'biography' => 'nullable|string',
            'occupation' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:persons,id',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_url'] = $request->file('photo')->store('people-photos', 'public');
        }

        // ✨ فصل الإنشاء عن ترتيب الشجرة
        $person = new Person($validated);
        $person->save();

        if (!empty($validated['parent_id'])) {
            $parent = Person::find($validated['parent_id']);
            $person->appendToNode($parent)->save();
        }

        return redirect()->route('people.index')->with('success', 'تمت إضافة الشخص بنجاح');
    }

    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'gender' => 'required|in:male,female',
            'photo' => 'nullable|image|max:2048',
            'biography' => 'nullable|string',
            'occupation' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:persons,id',
        ]);

        if ($request->hasFile('photo')) {
            if ($person->photo_url) {
                Storage::disk('public')->delete($person->photo_url);
            }
            $validated['photo_url'] = $request->file('photo')->store('people-photos', 'public');
        }

        $person->update($validated);

        if (!empty($validated['parent_id'])) {
            $parent = Person::find($validated['parent_id']);
            $person->appendToNode($parent)->save();
        }

        return redirect()->route('people.index')->with('success', 'تم تحديث بيانات الشخص بنجاح');
    }

    public function destroy(Person $person)
    {
        if ($person->photo_url) {
            Storage::disk('public')->delete($person->photo_url);
        }

        $person->delete();
        return redirect()->route('people.index')->with('success', 'تم حذف الشخص بنجاح');
    }

    public function tree()
    {
        $tree = Person::defaultOrder()->withDepth()->get()->toTree();
        return view('people.tree', compact('tree'));
    }
}
