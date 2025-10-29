<?php

namespace App\Http\Controllers;

use App\Models\Person;
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

        // عدد حملة الماجستير الأحياء (البحث في occupation أو biography)
        $masterDegreeCount = Person::where('from_outside_the_family', false)
            ->whereNull('death_date')
            ->where(function($query) {
                $query->where('occupation', 'like', '%ماجستير%')
                      ->orWhere('biography', 'like', '%ماجستير%')
                      ->orWhere('occupation', 'like', '%Master%')
                      ->orWhere('biography', 'like', '%Master%');
            })
            ->count();

        // عدد حملة الدكتوراه الأحياء
        $phdCount = Person::where('from_outside_the_family', false)
            ->whereNull('death_date')
            ->where(function($query) {
                $query->where('occupation', 'like', '%دكتوراه%')
                      ->orWhere('biography', 'like', '%دكتوراه%')
                      ->orWhere('occupation', 'like', '%PhD%')
                      ->orWhere('occupation', 'like', '%Ph.D%')
                      ->orWhere('biography', 'like', '%PhD%')
                      ->orWhere('biography', 'like', '%Ph.D%');
            })
            ->count();

        // ترتيب العائلة الأحياء حسب العمر (الذكور فقط)
        $malesByAge = Person::where('from_outside_the_family', false)
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

        return view('reports', compact(
            'totalFamilyMembers',
            'maleCount',
            'femaleCount',
            'masterDegreeCount',
            'phdCount',
            'malesByAge',
            'generationsData'
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
                $statistics[] = [
                    'grandfather_name' => $grandfather->full_name,
                    'total_descendants' => $allDescendants['total'],
                    'male_descendants' => $allDescendants['males'],
                    'female_descendants' => $allDescendants['females'],
                    'generations_breakdown' => $allDescendants['generations'],
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
}
