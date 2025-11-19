<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Category;
use App\Models\Article;
use App\Models\Location;
use App\Models\Marriage;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * عرض صفحة التقارير والإحصائيات.
     */
    public function index()
    {
        // إجمالي عدد أبناء العائلة الأحياء (ذكور وإناث)
        $totalFamilyMembers = Person::where('from_outside_the_family', false)
            ->whereNull('death_date')
            ->count();

        // عدد الذكور الأحياء
        $maleCount = Person::where('from_outside_the_family', false)
            ->where('gender', 'male')
            ->whereNull('death_date')
            ->count();

        // عدد الإناث الأحياء
        $femaleCount = Person::where('from_outside_the_family', false)
            ->where('gender', 'female')
            ->whereNull('death_date')
            ->count();

        // عدد مقالات فئة الماجستير
        $masterCategoryIds = Category::where(function($query) {
            $query->where('name', 'like', '%ماجستير%')
                  ->orWhere('name', 'like', '%Master%');
        })->pluck('id');

        $masterDegreeCount = Article::whereIn('category_id', $masterCategoryIds)->count();

        // عدد مقالات فئة الدكتوراه
        $phdCategoryIds = Category::where(function($query) {
            $query->where('name', 'like', '%دكتوراه%')
                  ->orWhere('name', 'like', '%PhD%')
                  ->orWhere('name', 'like', '%Ph.D%');
        })->pluck('id');

        $phdCount = Article::whereIn('category_id', $phdCategoryIds)->count();

        // ترتيب أبناء العائلة الأحياء حسب العمر (جميع الأبناء: ذكور وإناث)
        // مرتبون من الأكبر للأصغر حسب تاريخ الميلاد
        $allFamilyMembersByAge = Person::where('from_outside_the_family', false)
            ->whereNull('death_date')
            ->get()
            ->map(function($person) {
                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'gender' => $person->gender,
                    'age' => $person->age, // استخدام accessor من Person model
                    'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
                    'birth_date_raw' => $person->birth_date ? $person->birth_date->timestamp : PHP_INT_MAX, // للترتيب
                ];
            })
            ->sortBy(function($person) {
                // ترتيب من الأكبر للأصغر حسب تاريخ الميلاد
                // تاريخ الميلاد الأقدم (timestamp أصغر) = عمر أكبر
                // نستخدم sortBy تصاعدياً: timestamp أصغر أولاً = عمر أكبر أولاً
                return $person['birth_date_raw'];
            })
            ->values();

        // إحصائيات حسب الجد مع حساب الأبناء والأحفاد بشكل متكرر
        $generationsData = $this->getGenerationsStatistics();

        // إحصائيات الأماكن (عدد سكان المدن حسب الذكور والإناث)
        $locationsStatistics = $this->getLocationsStatistics();

        // أكثر الأسماء تكراراً (من داخل العائلة فقط) - مرتبة من الأكثر تكراراً للأقل
        $mostCommonNames = Person::where('from_outside_the_family', false)
            ->select('first_name', DB::raw('count(*) as count'))
            ->groupBy('first_name')
            ->orderByDesc('count')
            ->orderBy('first_name') // في حالة التساوي، ترتيب أبجدي
            ->limit(20)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->first_name,
                    'count' => $item->count,
                ];
            })
            ->sortByDesc('count') // ترتيب إضافي من الأكثر للأقل
            ->values();

        return view('reports', compact(
            'totalFamilyMembers',
            'maleCount',
            'femaleCount',
            'masterDegreeCount',
            'phdCount',
            'allFamilyMembersByAge',
            'generationsData',
            'locationsStatistics',
            'mostCommonNames'
        ));
    }

    /**
     * جلب إحصائيات الأجيال حسب الجد
     * يحسب الأبناء والأحفاد بشكل متكرر
     */
    private function getGenerationsStatistics()
    {
        // جلب الأجداد (الأشخاص الذين ليس لهم أباء ومن داخل العائلة)
        $grandfathers = Person::where('from_outside_the_family', false)
            ->whereNull('parent_id')
            ->orderBy('first_name')
            ->get();

        $statistics = [];

        foreach ($grandfathers as $grandfather) {
            // حساب الأبناء والأحفاد بشكل متكرر (جميع المستويات)
            $allDescendants = $this->countAllDescendants($grandfather->id);

            if ($allDescendants['total'] > 0) {
                // جلب أبناء كل جيل
                $generationsWithMembers = [];
                foreach ($allDescendants['generations'] as $genLevel => $genStats) {
                    $generationsWithMembers[$genLevel] = array_merge($genStats, [
                        'members' => $this->getGenerationMembers($grandfather->id, $genLevel)
                    ]);
                }

                // حساب إجمالي الأنساب لهذا الجد (الأشخاص من خارج العائلة المتزوجون من أحفاده)
                $totalRelativesForGrandfather = $this->countRelativesForGrandfather($grandfather->id);

                $statistics[] = [
                    'grandfather_id' => $grandfather->id,
                    'grandfather_name' => $grandfather->full_name,
                    'total_descendants' => $allDescendants['total'],
                    'male_descendants' => $allDescendants['males'],
                    'female_descendants' => $allDescendants['females'],
                    'total_relatives' => $totalRelativesForGrandfather,
                    'generations_breakdown' => $generationsWithMembers,
                ];
            }
        }

        // ترتيب حسب عدد الأحفاد
        usort($statistics, function($a, $b) {
            return $b['total_descendants'] <=> $a['total_descendants'];
        });

        return $statistics;
    }

    /**
     * حساب جميع الأبناء والأحفاد بشكل متكرر
     */
    private function countAllDescendants($personId, $currentLevel = 1)
    {
        $query = Person::where('parent_id', $personId)
            ->where('from_outside_the_family', false)
            ->get();

        $total = 0;
        $males = 0;
        $females = 0;
        $generations = []; // تفصيل حسب الجيل

        foreach ($query as $child) {
            $total++;

            if ($child->gender === 'male') {
                $males++;
            } else {
                $females++;
            }

            // تحديث عداد الجيل
            if (!isset($generations[$currentLevel])) {
                $generations[$currentLevel] = [
                    'total' => 0,
                    'males' => 0,
                    'females' => 0
                ];
            }
            $generations[$currentLevel]['total']++;
            if ($child->gender === 'male') {
                $generations[$currentLevel]['males']++;
            } else {
                $generations[$currentLevel]['females']++;
            }

            // حساب الأبناء بشكل متكرر
            $descendantsStats = $this->countAllDescendants($child->id, $currentLevel + 1);
            $total += $descendantsStats['total'];
            $males += $descendantsStats['males'];
            $females += $descendantsStats['females'];

            // دمج تفاصيل الأجيال
            foreach ($descendantsStats['generations'] as $genLevel => $genStats) {
                if (!isset($generations[$genLevel])) {
                    $generations[$genLevel] = ['total' => 0, 'males' => 0, 'females' => 0];
                }
                $generations[$genLevel]['total'] += $genStats['total'];
                $generations[$genLevel]['males'] += $genStats['males'];
                $generations[$genLevel]['females'] += $genStats['females'];
            }
        }

        return [
            'total' => $total,
            'males' => $males,
            'females' => $females,
            'generations' => $generations,
        ];
    }

    /**
     * جلب أبناء جيل معين من جد معين
     */
    private function getGenerationMembers($grandfatherId, $targetLevel, $currentPersonId = null, $currentLevel = 1)
    {
        if ($currentPersonId === null) {
            $currentPersonId = $grandfatherId;
        }

        $members = [];

        if ($currentLevel === $targetLevel) {
            // نحن في المستوى المطلوب، نجلب الأبناء المباشرين
            $children = Person::where('parent_id', $currentPersonId)
                ->where('from_outside_the_family', false)
                ->orderBy('first_name')
                ->get();

            foreach ($children as $child) {
                $members[] = [
                    'id' => $child->id,
                    'full_name' => $child->full_name,
                    'gender' => $child->gender,
                ];
            }
        } else {
            // نحتاج للانتقال إلى المستوى التالي
            $children = Person::where('parent_id', $currentPersonId)
                ->where('from_outside_the_family', false)
                ->get();

            foreach ($children as $child) {
                $childMembers = $this->getGenerationMembers($grandfatherId, $targetLevel, $child->id, $currentLevel + 1);
                $members = array_merge($members, $childMembers);
            }
        }

        return $members;
    }

    /**
     * حساب إجمالي الأنساب لجد معين (الأشخاص من خارج العائلة المتزوجون من أحفاده)
     */
    private function countRelativesForGrandfather($grandfatherId)
    {
        // جلب جميع IDs لأحفاد الجد (جميع الأجيال)
        $descendantIds = $this->getAllDescendantIds($grandfatherId);
        
        if (empty($descendantIds)) {
            return 0;
        }

        // حساب الأنساب (الأشخاص من خارج العائلة المتزوجون من أحفاد الجد)
        $totalRelatives = DB::table('marriages')
            ->join('persons as husbands', 'marriages.husband_id', '=', 'husbands.id')
            ->join('persons as wives', 'marriages.wife_id', '=', 'wives.id')
            ->where(function($query) use ($descendantIds) {
                // الزوج من خارج العائلة والزوجة من أحفاد الجد
                $query->where(function($q) use ($descendantIds) {
                    $q->where('husbands.from_outside_the_family', true)
                      ->whereIn('wives.id', $descendantIds);
                })
                // أو الزوجة من خارج العائلة والزوج من أحفاد الجد
                ->orWhere(function($q) use ($descendantIds) {
                    $q->where('husbands.from_outside_the_family', false)
                      ->whereIn('husbands.id', $descendantIds)
                      ->where('wives.from_outside_the_family', true);
                });
            })
            ->where(function($query) {
                // شرط: أن يكون الزواج نشط (غير مطلق)
                $query->where('marriages.is_divorced', false)
                      ->whereNull('marriages.divorced_at');
            })
            ->selectRaw('CASE 
                WHEN husbands.from_outside_the_family = 1 THEN marriages.husband_id 
                ELSE marriages.wife_id 
            END as relative_id')
            ->distinct()
            ->count();

        return $totalRelatives;
    }

    /**
     * جلب جميع IDs لأحفاد الجد (جميع الأجيال)
     */
    private function getAllDescendantIds($personId, $ids = [])
    {
        $children = Person::where('parent_id', $personId)
            ->where('from_outside_the_family', false)
            ->pluck('id')
            ->toArray();
        
        $ids = array_merge($ids, $children);
        
        foreach ($children as $childId) {
            $ids = $this->getAllDescendantIds($childId, $ids);
        }
        
        return array_unique($ids);
    }

    /**
     * جلب إحصائيات الأحياء مع عدد الذكور والإناث الأحياء فقط
     */
    private function getLocationsStatistics()
    {
        // جلب جميع الأحياء التي لديها أشخاص أحياء مرتبطين بها
        $locations = Location::whereHas('persons', function($query) {
            $query->where('from_outside_the_family', false)
                  ->whereNull('death_date'); // الأفراد الأحياء فقط
        })->get();

        $statistics = [];

        foreach ($locations as $location) {
            // حساب عدد الذكور والإناث الأحياء فقط في هذا الحي
            $males = $location->persons()
                ->where('from_outside_the_family', false)
                ->where('gender', 'male')
                ->whereNull('death_date') // الأفراد الأحياء فقط
                ->count();

            $females = $location->persons()
                ->where('from_outside_the_family', false)
                ->where('gender', 'female')
                ->whereNull('death_date') // الأفراد الأحياء فقط
                ->count();

            $total = $males + $females;

            if ($total > 0) {
                $statistics[] = [
                    'location_id' => $location->id,
                    'location_name' => $location->display_name,
                    'total' => $total,
                    'males' => $males,
                    'females' => $females,
                ];
            }
        }

        // ترتيب حسب العدد الإجمالي (من الأكبر للأصغر)
        usort($statistics, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        return $statistics;
    }

    /**
     * جلب الأشخاص التابعين لمنطقة معينة (الأحياء فقط)
     */
    public function getLocationPersons($locationId)
    {
        $location = Location::findOrFail($locationId);

        // جلب جميع الأشخاص الأحياء التابعين لهذه المنطقة
        $persons = Person::where('location_id', $locationId)
            ->where('from_outside_the_family', false)
            ->whereNull('death_date')
            ->orderBy('gender')
            ->orderBy('first_name')
            ->get()
            ->map(function($person) {
                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'gender' => $person->gender,
                    'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
                    'age' => $person->birth_date ? $person->birth_date->diffInYears(now()) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'location' => [
                'id' => $location->id,
                'name' => $location->display_name,
            ],
            'persons' => $persons,
            'total' => $persons->count(),
            'males' => $persons->where('gender', 'male')->count(),
            'females' => $persons->where('gender', 'female')->count(),
        ]);
    }

    /**
     * جلب إحصائيات شخص معين (للعرض عند النقر على اسم من القائمة)
     */
    public function getPersonStatistics($personId)
    {
        $person = Person::findOrFail($personId);

        // حساب الأبناء والأحفاد بشكل متكرر
        $allDescendants = $this->countAllDescendants($person->id);

        // جلب أبناء كل جيل
        $generationsWithMembers = [];
        if ($allDescendants['total'] > 0) {
            foreach ($allDescendants['generations'] as $genLevel => $genStats) {
                $generationsWithMembers[$genLevel] = array_merge($genStats, [
                    'members' => $this->getGenerationMembers($person->id, $genLevel)
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'person' => [
                'id' => $person->id,
                'full_name' => $person->full_name,
                'gender' => $person->gender,
            ],
            'statistics' => [
                'total_descendants' => $allDescendants['total'],
                'male_descendants' => $allDescendants['males'],
                'female_descendants' => $allDescendants['females'],
                'generations_breakdown' => $generationsWithMembers,
            ]
        ]);
    }

    /**
     * جلب الأشخاص الذين يحملون اسم معين
     */
    public function getPersonsByName($name)
    {
        $persons = Person::where('from_outside_the_family', false)
            ->where('first_name', $name)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(function($person) {
                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'gender' => $person->gender,
                    'age' => $person->age,
                    'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
                    'location' => $person->location,
                ];
            });

        return response()->json([
            'success' => true,
            'name' => $name,
            'persons' => $persons,
            'total' => $persons->count(),
            'males' => $persons->where('gender', 'male')->count(),
            'females' => $persons->where('gender', 'female')->count(),
        ]);
    }
}
