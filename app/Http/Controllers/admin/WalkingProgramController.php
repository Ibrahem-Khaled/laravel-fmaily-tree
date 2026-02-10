<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalkingRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WalkingProgramController extends Controller
{
    private const DAILY_GOAL_STEPS = 10000;

    /**
     * Display the walking program dashboard with statistics.
     */
    public function index(): View
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // Daily rankings (today) - ordered from most to least
        $dailyRankings = WalkingRecord::whereDate('date', $today)
            ->with('user')
            ->orderByDesc('steps')
            ->get();

        // Weekly rankings - ordered from most to least
        $weeklyRankings = WalkingRecord::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->select('user_id', DB::raw('SUM(steps) as total_steps'), DB::raw('SUM(distance_km) as total_distance'), DB::raw('SUM(duration_minutes) as total_duration'))
            ->groupBy('user_id')
            ->orderByDesc('total_steps')
            ->get()
            ->load('user');

        // Monthly rankings - ordered from most to least
        $monthlyRankings = WalkingRecord::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select('user_id', DB::raw('SUM(steps) as total_steps'), DB::raw('SUM(distance_km) as total_distance'), DB::raw('SUM(duration_minutes) as total_duration'))
            ->groupBy('user_id')
            ->orderByDesc('total_steps')
            ->get()
            ->load('user');

        // Summary stats
        $dailyTotalSteps = $dailyRankings->sum('steps');
        $weeklyTotalSteps = $weeklyRankings->sum('total_steps');
        $monthlyTotalSteps = $monthlyRankings->sum('total_steps');

        $dailyParticipants = $dailyRankings->count();
        $weeklyParticipants = $weeklyRankings->count();
        $monthlyParticipants = $monthlyRankings->count();

        $dailyAvg = $dailyParticipants > 0 ? round($dailyTotalSteps / $dailyParticipants) : 0;
        $weeklyAvg = $weeklyParticipants > 0 ? round($weeklyTotalSteps / $weeklyParticipants) : 0;
        $monthlyAvg = $monthlyParticipants > 0 ? round($monthlyTotalSteps / $monthlyParticipants) : 0;

        // Smart statistics
        $smartStats = $this->getSmartStatistics($today, $startOfMonth, $endOfMonth, $startOfLastMonth, $endOfLastMonth, $startOfWeek, $endOfWeek, $startOfLastWeek, $endOfLastWeek);

        return view('dashboard.walking.index', [
            'dailyRankings' => $dailyRankings,
            'weeklyRankings' => $weeklyRankings,
            'monthlyRankings' => $monthlyRankings,
            'dailyStats' => [
                'total_steps' => $dailyTotalSteps,
                'participants' => $dailyParticipants,
                'average' => $dailyAvg,
            ],
            'weeklyStats' => [
                'total_steps' => $weeklyTotalSteps,
                'participants' => $weeklyParticipants,
                'average' => $weeklyAvg,
            ],
            'monthlyStats' => [
                'total_steps' => $monthlyTotalSteps,
                'participants' => $monthlyParticipants,
                'average' => $monthlyAvg,
            ],
            'smartStats' => $smartStats,
            'today' => $today,
        ]);
    }

    /**
     * Get smart statistics.
     */
    private function getSmartStatistics($today, $startOfMonth, $endOfMonth, $startOfLastMonth, $endOfLastMonth, $startOfWeek, $endOfWeek, $startOfLastWeek, $endOfLastWeek): array
    {
        // Longest streak (consecutive days with walking)
        $longestStreak = $this->getLongestStreak();

        // Most active day of week (0=Sunday, 6=Saturday in Carbon)
        $dayNames = [0 => 'الأحد', 1 => 'الإثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'];
        $dayTotals = [];
        $monthRecords = WalkingRecord::whereBetween('date', [$startOfMonth, $endOfMonth])->get();
        foreach ($monthRecords as $rec) {
            $dow = Carbon::parse($rec->date)->dayOfWeek;
            $dayTotals[$dow] = ($dayTotals[$dow] ?? 0) + $rec->steps;
        }
        arsort($dayTotals);
        $mostActiveDayNum = array_key_first($dayTotals);
        $mostActiveDayName = $mostActiveDayNum !== null ? ($dayNames[$mostActiveDayNum] ?? '-') : '-';

        // Progress vs goal (today)
        $todayTotal = WalkingRecord::whereDate('date', $today)->sum('steps');
        $goalProgress = self::DAILY_GOAL_STEPS > 0 ? min(100, round(($todayTotal / self::DAILY_GOAL_STEPS) * 100, 1)) : 0;

        // Comparison with previous period
        $lastWeekTotal = WalkingRecord::whereBetween('date', [$startOfLastWeek, $endOfLastWeek])->sum('steps');
        $thisWeekTotal = WalkingRecord::whereBetween('date', [$startOfWeek, $endOfWeek])->sum('steps');
        $weeklyChange = $lastWeekTotal > 0 ? round((($thisWeekTotal - $lastWeekTotal) / $lastWeekTotal) * 100, 1) : 0;

        $lastMonthTotal = WalkingRecord::whereBetween('date', [$startOfLastMonth, $endOfLastMonth])->sum('steps');
        $thisMonthTotal = WalkingRecord::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('steps');
        $monthlyChange = $lastMonthTotal > 0 ? round((($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100, 1) : 0;

        // Most improved users (compare this month avg vs last month avg)
        $improvedUsers = $this->getMostImprovedUsers($startOfMonth, $endOfMonth, $startOfLastMonth, $endOfLastMonth);

        // Average daily steps per user (this month)
        $avgDailyPerUser = User::whereHas('walkingRecords', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('date', [$startOfMonth, $endOfMonth]);
        })->get()->map(function ($user) use ($startOfMonth, $endOfMonth) {
            $totalSteps = $user->walkingRecords()->whereBetween('date', [$startOfMonth, $endOfMonth])->sum('steps');
            $daysCount = $user->walkingRecords()->whereBetween('date', [$startOfMonth, $endOfMonth])->distinct('date')->count('date');
            $avgDaily = $daysCount > 0 ? round($totalSteps / $daysCount) : 0;
            return [
                'user' => $user,
                'total_steps' => $totalSteps,
                'days_count' => $daysCount,
                'avg_daily' => $avgDaily,
            ];
        })->sortByDesc('avg_daily')->take(5)->values();

        return [
            'longest_streak' => $longestStreak,
            'most_active_day' => $mostActiveDayName,
            'goal_progress' => $goalProgress,
            'weekly_change_percent' => $weeklyChange,
            'monthly_change_percent' => $monthlyChange,
            'improved_users' => $improvedUsers,
            'avg_daily_per_user' => $avgDailyPerUser,
        ];
    }

    /**
     * Get the longest consecutive days streak.
     */
    private function getLongestStreak(): int
    {
        $dates = WalkingRecord::select('date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date')
            ->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $maxStreak = 1;
        $currentStreak = 1;

        for ($i = 1; $i < count($dates); $i++) {
            $prev = Carbon::parse($dates[$i - 1]);
            $curr = Carbon::parse($dates[$i]);
            if ($curr->diffInDays($prev) === 1) {
                $currentStreak++;
            } else {
                $maxStreak = max($maxStreak, $currentStreak);
                $currentStreak = 1;
            }
        }
        return max($maxStreak, $currentStreak);
    }

    /**
     * Get most improved users (comparing this month vs last month average).
     */
    private function getMostImprovedUsers($startOfMonth, $endOfMonth, $startOfLastMonth, $endOfLastMonth)
    {
        $users = User::whereHas('walkingRecords')->get();
        $improved = [];

        foreach ($users as $user) {
            $thisMonthAvg = $user->walkingRecords()
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->avg('steps') ?? 0;
            $lastMonthAvg = $user->walkingRecords()
                ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
                ->avg('steps') ?? 0;

            if ($lastMonthAvg > 0 && $thisMonthAvg > $lastMonthAvg) {
                $improvement = round((($thisMonthAvg - $lastMonthAvg) / $lastMonthAvg) * 100, 1);
                $improved[] = [
                    'user' => $user,
                    'improvement_percent' => $improvement,
                    'this_month_avg' => round($thisMonthAvg),
                    'last_month_avg' => round($lastMonthAvg),
                ];
            }
        }

        return collect($improved)->sortByDesc('improvement_percent')->take(5)->values();
    }

    /**
     * Show the form for creating a new walking record.
     */
    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('dashboard.walking.create', compact('users'));
    }

    /**
     * Store a newly created walking record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date|before_or_equal:today',
            'steps' => 'required|integer|min:0',
            'distance_km' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ], [
            'user_id.required' => 'يجب اختيار المستخدم',
            'date.required' => 'يجب تحديد التاريخ',
            'steps.required' => 'يجب إدخال عدد الخطوات',
        ]);

        $exists = WalkingRecord::where('user_id', $validated['user_id'])
            ->whereDate('date', $validated['date'])
            ->first();

        if ($exists) {
            return back()->withErrors(['date' => 'يوجد سجل مشي لهذا المستخدم في هذا التاريخ بالفعل. يمكنك تعديله.'])->withInput();
        }

        WalkingRecord::create([
            'user_id' => $validated['user_id'],
            'date' => $validated['date'],
            'steps' => $validated['steps'],
            'distance_km' => $validated['distance_km'] ?? 0,
            'duration_minutes' => $validated['duration_minutes'] ?? 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('dashboard.walking.index')
            ->with('success', 'تم إضافة سجل المشي بنجاح');
    }

    /**
     * Show the form for editing a walking record.
     */
    public function edit(WalkingRecord $walking): View
    {
        $users = User::orderBy('name')->get();
        return view('dashboard.walking.edit', compact('walking', 'users'));
    }

    /**
     * Update the specified walking record.
     */
    public function update(Request $request, WalkingRecord $walking)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date|before_or_equal:today',
            'steps' => 'required|integer|min:0',
            'distance_km' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $exists = WalkingRecord::where('user_id', $validated['user_id'])
            ->whereDate('date', $validated['date'])
            ->where('id', '!=', $walking->id)
            ->first();

        if ($exists) {
            return back()->withErrors(['date' => 'يوجد سجل مشي لهذا المستخدم في هذا التاريخ بالفعل.'])->withInput();
        }

        $walking->update($validated);

        return redirect()->route('dashboard.walking.index')
            ->with('success', 'تم تحديث سجل المشي بنجاح');
    }

    /**
     * Remove the specified walking record.
     */
    public function destroy(WalkingRecord $walking)
    {
        $walking->delete();
        return redirect()->route('dashboard.walking.index')
            ->with('success', 'تم حذف سجل المشي بنجاح');
    }
}
