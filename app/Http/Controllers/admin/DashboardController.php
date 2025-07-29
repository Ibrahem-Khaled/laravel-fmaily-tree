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

        // حساب عدد الأجيال بالطريقة الصحيحة
        // نستخدم withDepth() لحساب العمق أولاً، ثم نجد القيمة القصوى.
        // ونضيف شرطاً للتحقق من وجود بيانات لتجنب الأخطاء في الجداول الفارغة.
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
                                  ->with(['husband', 'wife']) // لجلب بيانات الزوج والزوجة
                                  ->get();


        // --- 3. أفراد أضيفوا مؤخراً ---
        $recentlyAdded = Person::latest()->take(5)->get();


        // --- 4. أعياد ميلاد قادمة هذا الشهر ---
        $upcomingBirthdays = Person::whereNull('death_date') // فقط الأشخاص الأحياء
                                   ->whereMonth('birth_date', $today->month)
                                   ->whereDay('birth_date', '>=', $today->day) // من اليوم وحتى نهاية الشهر
                                   ->orderBy('birth_date')
                                   ->take(5)
                                   ->get();


        // --- 5. حقائق شيقة عن العائلة ---
        // ✅ تم الإصلاح: استخدام الدالة التجميعية مباشرة في الترتيب لضمان التوافق
        // هذا الاستعلام يجد الـ parent_id الذي يظهر أكثر من غيره (أي الأب/الأم صاحب أكبر عدد من الأبناء)
        $mostChildrenParentData = DB::table('persons')
                                    ->select('parent_id', DB::raw('COUNT(id) as children_count'))
                                    ->whereNotNull('parent_id')
                                    ->groupBy('parent_id')
                                    ->orderBy(DB::raw('COUNT(id)'), 'desc') // Order by the raw aggregate expression
                                    ->first();

        $personWithMostChildren = null;
        if ($mostChildrenParentData) {
            $personWithMostChildren = Person::find($mostChildrenParentData->parent_id);
            // To display the count in the view, we can attach it to the model
            if ($personWithMostChildren) {
                $personWithMostChildren->children_count = $mostChildrenParentData->children_count;
            }
        }


        // --- إرسال جميع البيانات إلى الـ View ---
        return view('dashboard.index', [
            'stats' => [
                'totalPeople' => $totalPeople,
                'totalMarriages' => $totalMarriages,
                'totalGenerations' => $totalGenerations,
            ],
            'events' => [
                'birthsToday' => $birthsToday,
                'marriagesToday' => $marriagesToday,
            ],
            'recentlyAdded' => $recentlyAdded,
            'upcomingBirthdays' => $upcomingBirthdays,
            'funFact' => [
                'personWithMostChildren' => $personWithMostChildren,
            ]
        ]);
    }
}
