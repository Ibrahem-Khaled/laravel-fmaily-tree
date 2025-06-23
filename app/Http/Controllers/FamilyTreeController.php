<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class FamilyTreeController extends Controller
{
    // عرض شجرة العائلة الرئيسية
    public function index()
    {
        return view('index');
    }

    // API لجلب شجرة العائلة
    public function getFamilyTree()
    {
        // الحصول على الجذور (الأشخاص الذين ليس لهم والد)
        $roots = Person::whereNull('parent_id')
            ->with('children')
            ->orderBy('birth_date')
            ->get();

        // تنظيم البيانات بشكل شجري
        $tree = $this->buildTree($roots);

        return response()->json([
            'success' => true,
            'tree' => $tree
        ]);
    }

    private function buildTree($people)
    {
        return $people->map(function ($person) {
            $data = $this->formatPersonData($person);

            // إذا كان للشخص أبناء، نضيفهم بشكل متداخل
            if ($person->children->isNotEmpty()) {
                $data['children'] = $this->buildTree($person->children);
            }

            return $data;
        });
    }

    // API لجلب أبناء شخص معين
    public function getChildren($id)
    {
        $person = Person::findOrFail($id);
        $children = $person->children()->withCount('children')->get();

        return response()->json([
            'success' => true,
            'children' => $children->map(function ($child) {
                return $this->formatPersonData($child);
            })
        ]);
    }

    // API لجلب تفاصيل شخص معين
    public function getPersonDetails($id)
    {
        $person = Person::with('parent')->findOrFail($id);

        return response()->json([
            'success' => true,
            'person' => $this->formatPersonData($person, true)
        ]);
    }

    // تنسيق بيانات الشخص للاستجابة
    private function formatPersonData(Person $person, $fullDetails = false)
    {
        $data = [
            'id' => $person->id,
            'parent_id' => $person->parent_id, // إضافة معرف الوالد
            'full_name' => $person->full_name,
            'first_name' => $person->first_name,
            'last_name' => $person->last_name,
            'gender' => $person->gender,
            'photo_url' => $person->photo_url ?  asset('storage/' . $person->photo_url) : null,
            'children_count' => $person->children_count ?? $person->children()->count(),
        ];

        if ($person->birth_date) {
            $data['birth_date'] = $person->birth_date->format('Y/m/d');
            $data['age'] = $person->age;
        }

        if ($person->death_date) {
            $data['death_date'] = $person->death_date->format('Y/m/d');
        }

        if ($fullDetails) {
            $data['occupation'] = $person->occupation;
            $data['location'] = $person->location;
            $data['biography'] = $person->biography;

            if ($person->parent) {
                $data['parent_name'] = $person->parent->full_name;
            }

            // يمكنك إضافة المزيد من التفاصيل هنا حسب الحاجة
        }

        return $data;
    }
}
