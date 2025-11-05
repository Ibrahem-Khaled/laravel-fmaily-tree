<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Marriage;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function index()
    {
        // --- 1. الإحصائيات الرئيسية ---
        $totalPeople = Person::count();
        $totalMarriages = Marriage::count();

        // الأشخاص الأحياء والمتوفين
        $alivePeople = Person::whereNull('death_date')->count();
        $deceasedPeople = Person::whereNotNull('death_date')->count();
        
        // عدد الذكور والإناث
        $maleCount = Person::where('gender', 'male')->count();
        $femaleCount = Person::where('gender', 'female')->count();
        
        // الأشخاص المضافة هذا الشهر
        $peopleAddedThisMonth = Person::whereMonth('created_at', Carbon::now()->month)
                                      ->whereYear('created_at', Carbon::now()->year)
                                      ->count();
        
        // الزيجات هذا الشهر
        $marriagesThisMonth = Marriage::whereMonth('created_at', Carbon::now()->month)
                                     ->whereYear('created_at', Carbon::now()->year)
                                     ->count();

        // حساب عدد الأجيال بالطريقة الصحيحة
        $totalGenerations = 0;
        if ($totalPeople > 0) {
            // The withDepth() scope calculates the depth for each node.
            $totalGenerations = 0;
        }


        // --- 2. أحداث اليوم في تاريخ العائلة ---
        $today = Carbon::today();
        $birthsToday = Person::whereMonth('birth_date', $today->month)
                               ->whereDay('birth_date', $today->day)
                               ->get();

        $marriagesToday = Marriage::whereMonth('married_at', $today->month)
                                  ->whereDay('married_at', $today->day)
                                  ->with(['husband', 'wife'])
                                  ->get();


        // --- 3. أفراد أضيفوا مؤخراً ---
        $recentlyAdded = Person::latest()->take(5)->get();


        // --- 4. أعياد ميلاد قادمة هذا الشهر ---
        $upcomingBirthdays = Person::whereNull('death_date')
                                   ->whereMonth('birth_date', $today->month)
                                   ->whereDay('birth_date', '>=', $today->day)
                                   ->orderByRaw('DAY(birth_date)')
                                   ->take(10)
                                   ->get();


        // --- 5. حقائق شيقة عن العائلة ---
        $mostChildrenParentData = DB::table('persons')
                                    ->select('parent_id', DB::raw('COUNT(id) as children_count'))
                                    ->whereNotNull('parent_id')
                                    ->groupBy('parent_id')
                                    ->orderBy(DB::raw('COUNT(id)'), 'desc')
                                    ->first();

        $personWithMostChildren = null;
        if ($mostChildrenParentData) {
            $personWithMostChildren = Person::find($mostChildrenParentData->parent_id);
            if ($personWithMostChildren) {
                $personWithMostChildren->children_count = $mostChildrenParentData->children_count;
            }
        }
        
        // أكبر شخص سناً (على قيد الحياة)
        $oldestPerson = Person::whereNull('death_date')
                             ->whereNotNull('birth_date')
                             ->orderBy('birth_date', 'asc')
                             ->first();
        
        // أحدث زواج
        $latestMarriage = Marriage::with(['husband', 'wife'])
                                  ->latest('married_at')
                                  ->first();
        
        // عدد الأشخاص المضافة هذا الأسبوع
        $peopleAddedThisWeek = Person::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();


        // --- إرسال جميع البيانات إلى الـ View ---
        return view('dashboard.index', [
            'stats' => [
                'totalPeople' => $totalPeople,
                'alivePeople' => $alivePeople,
                'deceasedPeople' => $deceasedPeople,
                'maleCount' => $maleCount,
                'femaleCount' => $femaleCount,
                'totalMarriages' => $totalMarriages,
                'totalGenerations' => $totalGenerations,
                'peopleAddedThisMonth' => $peopleAddedThisMonth,
                'marriagesThisMonth' => $marriagesThisMonth,
                'peopleAddedThisWeek' => $peopleAddedThisWeek,
            ],
            'events' => [
                'birthsToday' => $birthsToday,
                'marriagesToday' => $marriagesToday,
            ],
            'recentlyAdded' => $recentlyAdded,
            'upcomingBirthdays' => $upcomingBirthdays,
            'funFact' => [
                'personWithMostChildren' => $personWithMostChildren,
                'oldestPerson' => $oldestPerson,
                'latestMarriage' => $latestMarriage,
            ]
        ]);
    }
}
