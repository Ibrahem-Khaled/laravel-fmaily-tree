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
    public function oldIndex()
    {
        return view('old_index');
    }

    // API لجلب شجرة العائلة
    public function getFamilyTree()
    {
        // الحصول على الجذور (الأشخاص الذين ليس لهم والد)
        $roots = Person::whereNull('parent_id')
            ->where('from_outside_the_family', false)
            // ->where('gender', 'male')
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
        $childrenQuery = Person::query();

        // التحقق من جنس الشخص لتحديد كيفية البحث عن الأبناء
        if ($person->gender === 'female') {
            // إذا كان الشخص أنثى، يتم البحث عن الأبناء عن طريق mother_id
            $childrenQuery->where('mother_id', $person->id);
        } else {
            // إذا كان الشخص ذكر، يتم البحث عن طريق parent_id (الأب)
            $childrenQuery->where('parent_id', $person->id);
        }

        // جلب الأبناء مع عدد أبنائهم (للجيل التالي) وترتيبهم حسب تاريخ الميلاد
        $children = $childrenQuery->withCount('children')
                                  ->orderBy('birth_date')
                                  ->get();

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
        $person = Person::with(['parent', 'mother', 'husband', 'wives'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'person' => $this->formatPersonData($person, true)
        ]);
    }

    // تنسيق بيانات الشخص للاستجابة
     private function formatPersonData(Person $person, $fullDetails = false)
    {
        // --- حساب عدد الأبناء بناءً على الجنس ---
        // ملاحظة: هذا قد يسبب مشاكل في الأداء (مشكلة N+1) عند تحميل عدد كبير من الأشخاص.
        // الحل الأفضل هو تعريف accessor في موديل Person لجلب العدد بشكل محسن.
        if (isset($person->children_count)) {
            // استخدم العدد إذا تم تحميله مسبقًا (باستخدام withCount)
            $children_count = $person->children_count;
        } else {
            // إذا لم يتم تحميل العدد، قم بحسابه الآن
            if ($person->gender === 'female') {
                $children_count = Person::where('mother_id', $person->id)->count();
            } else {
                // الافتراضي هو الأب
                $children_count = $person->children()->count();
            }
        }

        $data = [
            'id' => $person->id,
            'parent_id' => $person->parent_id,
            'mother_id' => $person->mother_id,
            'mother_name' => $person->mother ? $person->mother->full_name : null,
            'full_name' => $person->full_name,
            'first_name' => $person->first_name,
            'last_name' => $person->last_name,
            'gender' => $person->gender,
            'photo_url' => $person->avatar,
            'children_count' => $children_count, // استخدام العدد المحسوب
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

            // إضافة معلومات الزوج/الزوجات
            $spouses = [];

            if ($person->gender === 'male' && $person->wives->isNotEmpty()) {
                foreach ($person->wives as $wife) {
                    $spouses[] = [
                        'id' => $wife->id,
                        'name' => $wife->full_name,
                        'gender' => $wife->gender,
                        'photo' => $wife->photo_url ? asset('storage/' . $wife->photo_url) : null
                    ];
                }
            } elseif ($person->gender === 'female' && is_object($person->husband)) {
                $husband = $person->husband;
                $spouses[] = [
                    'id' => $husband->id,
                    'name' => $husband->full_name,
                    'gender' => $husband->gender,
                    'photo' => $husband->photo_url ? asset('storage/' . $husband->photo_url) : null
                ];
            }

            $data['spouses'] = $spouses;
        }

        return $data;
    }
}
