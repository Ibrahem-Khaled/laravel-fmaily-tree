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
        // تصفية الأشخاص داخل العائلة فقط
        $familyQuery = Person::where('from_outside_the_family', false);
        
        // --- 1. الإحصائيات الرئيسية ---
        $totalPeople = $familyQuery->count();
        $totalMarriages = Marriage::whereHas('husband', function($q) {
            $q->where('from_outside_the_family', false);
        })->orWhereHas('wife', function($q) {
            $q->where('from_outside_the_family', false);
        })->count();

        // الأشخاص الأحياء والمتوفين
        $alivePeople = (clone $familyQuery)->whereNull('death_date')->count();
        $deceasedPeople = (clone $familyQuery)->whereNotNull('death_date')->count();
        
        // عدد الذكور والإناث
        $maleCount = (clone $familyQuery)->where('gender', 'male')->count();
        $femaleCount = (clone $familyQuery)->where('gender', 'female')->count();
        
        // الأشخاص المضافة هذا الشهر
        $peopleAddedThisMonth = (clone $familyQuery)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        // الزيجات هذا الشهر (على الأقل أحد الزوجين من العائلة)
        $marriagesThisMonth = Marriage::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where(function($q) {
                $q->whereHas('husband', function($q2) {
                    $q2->where('from_outside_the_family', false);
                })->orWhereHas('wife', function($q2) {
                    $q2->where('from_outside_the_family', false);
                });
            })
            ->count();

        // حساب عدد الأجيال
        $roots = (clone $familyQuery)->whereNull('parent_id')->get();
        $totalGenerations = 0;
        foreach ($roots as $root) {
            $descendants = $root->descendants()->where('from_outside_the_family', false)->get();
            if ($descendants->isNotEmpty()) {
                $maxDepth = $descendants->max(function($person) {
                    return $person->depth ?? 0;
                }) ?? 0;
                $totalGenerations = max($totalGenerations, $maxDepth + 1);
            } else {
                $totalGenerations = max($totalGenerations, 1);
            }
        }

        // --- 2. أحداث اليوم في تاريخ العائلة ---
        $today = Carbon::today();
        $birthsToday = (clone $familyQuery)
            ->whereMonth('birth_date', $today->month)
            ->whereDay('birth_date', $today->day)
            ->get();

        $marriagesToday = Marriage::whereMonth('married_at', $today->month)
            ->whereDay('married_at', $today->day)
            ->where(function($q) {
                $q->whereHas('husband', function($q2) {
                    $q2->where('from_outside_the_family', false);
                })->orWhereHas('wife', function($q2) {
                    $q2->where('from_outside_the_family', false);
                });
            })
            ->with(['husband', 'wife'])
            ->get();

        // --- 3. أفراد أضيفوا مؤخراً ---
        $recentlyAdded = (clone $familyQuery)->latest()->take(5)->get();

        // --- 4. أعياد ميلاد قادمة هذا الشهر ---
        $upcomingBirthdays = (clone $familyQuery)
            ->whereNull('death_date')
            ->whereMonth('birth_date', $today->month)
            ->whereDay('birth_date', '>=', $today->day)
            ->orderByRaw('DAY(birth_date)')
            ->take(10)
            ->get();

        // --- 5. حقائق شيقة عن العائلة ---
        $mostChildrenParentData = DB::table('persons')
            ->select('parent_id', DB::raw('COUNT(id) as children_count'))
            ->where('from_outside_the_family', false)
            ->whereNotNull('parent_id')
            ->groupBy('parent_id')
            ->orderBy(DB::raw('COUNT(id)'), 'desc')
            ->first();

        $personWithMostChildren = null;
        if ($mostChildrenParentData) {
            $personWithMostChildren = Person::where('from_outside_the_family', false)
                ->find($mostChildrenParentData->parent_id);
            if ($personWithMostChildren) {
                $personWithMostChildren->children_count = $mostChildrenParentData->children_count;
            }
        }
        
        // أكبر شخص سناً (على قيد الحياة)
        $oldestPerson = (clone $familyQuery)
            ->whereNull('death_date')
            ->whereNotNull('birth_date')
            ->orderBy('birth_date', 'asc')
            ->first();
        
        // أحدث زواج (على الأقل أحد الزوجين من العائلة)
        $latestMarriage = Marriage::where(function($q) {
            $q->whereHas('husband', function($q2) {
                $q2->where('from_outside_the_family', false);
            })->orWhereHas('wife', function($q2) {
                $q2->where('from_outside_the_family', false);
            });
        })
        ->with(['husband', 'wife'])
        ->latest('married_at')
        ->first();
        
        // عدد الأشخاص المضافة هذا الأسبوع
        $peopleAddedThisWeek = (clone $familyQuery)
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count();

        // --- 6. إحصائيات إضافية جديدة ---
        // عدد الأشخاص الذين لديهم أوسمة
        $peopleWithBadges = (clone $familyQuery)->whereHas('padges')->count();
        
        // عدد الصور المذكور فيها أشخاص من العائلة
        $totalImages = \App\Models\Image::whereHas('mentionedPersons', function($q) {
            $q->where('from_outside_the_family', false);
        })->count();
        
        // عدد المقالات
        $totalArticles = \App\Models\Article::whereHas('person', function($q) {
            $q->where('from_outside_the_family', false);
        })->count();
        
        // عدد البرامج النشطة
        $activePrograms = \App\Models\Image::where('is_program', true)
            ->where('program_is_active', true)
            ->count();
        
        // عدد المناسبات النشطة
        $activeEvents = \App\Models\FamilyEvent::where('is_active', true)->count();
        
        // عدد المجالس
        $totalCouncils = \App\Models\FamilyCouncil::where('is_active', true)->count();
        
        // عدد الأوسمة
        $totalBadges = \App\Models\Padge::where('is_active', true)->count();
        
        // عدد القصص
        $totalStories = \App\Models\Story::whereHas('storyOwner', function($q) {
            $q->where('from_outside_the_family', false);
        })->count();


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
                'peopleWithBadges' => $peopleWithBadges,
                'totalImages' => $totalImages,
                'totalArticles' => $totalArticles,
                'activePrograms' => $activePrograms,
                'activeEvents' => $activeEvents,
                'totalCouncils' => $totalCouncils,
                'totalBadges' => $totalBadges,
                'totalStories' => $totalStories,
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
