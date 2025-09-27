<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Marriage;
use Illuminate\Http\Request;

class FamilyTreeController extends Controller
{
    // عرض تواصل العائلة الرئيسية
    public function index()
    {
        return view('old_index');
    }
    public function newIndex()
    {
        return view('new_index');
    }

    // API لجلب تواصل العائلة
    public function getFamilyTree()
    {
        // الحصول على الجذور (الأشخاص الذين ليس لهم والد)
        $roots = Person::whereNull('parent_id')
            ->where('from_outside_the_family', false)
            // ->where('gender', 'male')
            ->with('children')
            ->get();

        // تنظيم البيانات بشكل شجري
        $tree = $this->buildTree($roots);

        return response()->json([
            'success' => true,
            'tree' => $tree
        ]);
    }

    public function addSelf()
    {
        $males = Person::where('gender', 'male')->get();
        $females = Person::where('gender', 'female')->get();
        return view('add-self', [
            'males' => $males,
            'females' => $females,
        ]);
    }

    private function buildTree($people)
    {
        return $people->map(function ($person) {
            $data = $this->formatPersonData($person);

            // إذا كان للشخص أبناء، نضيفهم بشكل متداخل
            if ($person->children->isNotEmpty()) {
                $data['children'] = $this->buildTree($person->children);
                // تحديث عدد الأبناء (للرجال فقط)
                $data['children_count'] = $person->gender === 'male' ? $person->children->count() : 0;
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
            ->get();

        return response()->json([
            'success' => true,
            'children' => array_values($children->map(function (Person $child) {
                $childData = $this->formatPersonData($child);
                // عدد الأبناء للرجال فقط
                $childData['children_count'] = $child->gender === 'male' ? $child->children_count : 0;
                return $childData;
            })->toArray())
        ]);
    }

    // API لجلب تفاصيل شخص معين
    public function getPersonDetails($id)
    {
        // Eager load relationships to prevent N+1 query problem
        $person = Person::with(['parent', 'mother', 'husband', 'wives', 'articles'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'person' => $this->formatPersonData($person, true) // Request full details
        ]);
    }

    // API لجلب زوجات شخص معين
    public function getWives($fatherId)
    {
        $father = Person::findOrFail($fatherId);

        // استخدام العلاقة 'wives' الموجودة في موديل Person لجلب الزوجات
        $wives = $father->wives()->get();

        // إرجاع قائمة الزوجات كـ JSON
        return response()->json($wives);
    }

    // تنسيق بيانات الشخص للاستجابة
    private function formatPersonData(Person $person, $fullDetails = false)
    {
        // Calculate children count based on gender
        if (isset($person->children_count)) {
            $children_count = $person->children_count;
        } else {
            // If not preloaded with withCount, calculate it now.
            if ($person->gender === 'female') {
                $children_count = Person::where('mother_id', $person->id)->count();
            } else {
                $children_count = $person->children()->count();
            }
        }

        $data = [
            'id' => $person->id,
            'parent_id' => $person->parent_id,
            'mother_id' => $person->mother_id,
            'full_name' => $person->full_name,
            'first_name' => $person->first_name,
            'gender' => $person->gender,
            'photo_url' => $person->avatar, // Assuming 'avatar' is the attribute for photo url
            'children_count' => $person->gender === 'male' ? $children_count : 0, // للنساء نضع العدد صفر
            'birth_date' => optional($person->birth_date)->format('Y/m/d'),
            'death_date' => optional($person->death_date)->format('Y/m/d'), // Send death date to frontend
            'age' => $person->age,
        ];

        if ($fullDetails) {
            $data['occupation'] = $person->occupation;
            $data['location'] = $person->location;
            $data['biography'] = $person->biography;

            if ($person->parent) {
                // To display father's name under main person's name
                $data['parent_name'] = $person->parent->full_name;
                // Add full father object for details card, formatted with basic details
                $data['parent'] = $this->formatPersonData($person->parent, false);
            }

            if ($person->mother) {
                // Add full mother object for details card, formatted with basic details
                $data['mother'] = $this->formatPersonData($person->mother, false);
            }

            if ($person->relationLoaded('articles')) {
                $data['articles'] = $person->articles->map(function ($article) {
                    // نختار فقط الحقول التي نحتاجها في الواجهة الأمامية
                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        // يمكنك إضافة حقول أخرى إذا احتجتها
                    ];
                });
            }

            // Format spouses - للنساء نعرض الزوج الأخير فقط (غير مطلق)
            $spouses = collect();
            if ($person->gender === 'male' && $person->wives->isNotEmpty()) {
                $spouses = $person->wives;
            } elseif ($person->gender === 'female') {
                // للنساء: نبحث عن الزوج الحالي (غير مطلق) من خلال جدول الزواج
                $currentMarriage = Marriage::where('wife_id', $person->id)
                    ->where(function($query) {
                        $query->where('is_divorced', false)
                              ->whereNull('divorced_at');
                    })
                    ->orderBy('married_at', 'desc')
                    ->first();

                if ($currentMarriage && $currentMarriage->husband) {
                    $spouses->push($currentMarriage->husband);
                }
            }

            $data['spouses'] = $spouses->map(function ($spouse) {
                // Format spouse using basic details to avoid deep nesting
                return $this->formatPersonData($spouse, false);
            });
        }

        return $data;
    }
}
