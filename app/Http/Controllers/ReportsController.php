<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Category;
use App\Models\Article;
use App\Models\Location;
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

        // ترتيب أبناء العائلة الأحياء حسب العمر (ذكور فقط)
        $allFamilyMembersByAge = Person::where('from_outside_the_family', false)
            ->where('gender', 'male')
            ->whereNotNull('birth_date')
            ->whereNull('death_date')
            ->orderBy('birth_date', 'asc')
            ->get()
            ->map(function($person) {
                // حساب العمر من تاريخ الميلاد حتى الآن (الأحياء فقط)
                $age = null;
                if ($person->birth_date) {
                    $age = $person->birth_date->diffInYears(now());
                }

                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'age' => $age ?? 'غير محدد',
                    'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
                ];
            });

        // إحصائيات حسب الجد مع حساب الأبناء والأحفاد بشكل متكرر
        $generationsData = $this->getGenerationsStatistics();

        // إحصائيات الأماكن (عدد سكان المدن حسب الذكور والإناث)
        $locationsStatistics = $this->getLocationsStatistics();

        return view('reports', compact(
            'totalFamilyMembers',
            'maleCount',
            'femaleCount',
            'masterDegreeCount',
            'phdCount',
            'allFamilyMembersByAge',
            'generationsData',
            'locationsStatistics'
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

                $statistics[] = [
                    'grandfather_id' => $grandfather->id,
                    'grandfather_name' => $grandfather->full_name,
                    'total_descendants' => $allDescendants['total'],
                    'male_descendants' => $allDescendants['males'],
                    'female_descendants' => $allDescendants['females'],
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
     * جلب إحصائيات الأماكن مع عدد الذكور والإناث
     */
    private function getLocationsStatistics()
    {
        // جلب جميع الأماكن التي لديها أشخاص مرتبطين بها
        $locations = Location::whereHas('persons', function($query) {
            $query->where('from_outside_the_family', false);
        })->get();

        $statistics = [];

        foreach ($locations as $location) {
            // حساب عدد الذكور والإناث في هذا المكان
            $males = $location->persons()
                ->where('from_outside_the_family', false)
                ->where('gender', 'male')
                ->count();

            $females = $location->persons()
                ->where('from_outside_the_family', false)
                ->where('gender', 'female')
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
}
