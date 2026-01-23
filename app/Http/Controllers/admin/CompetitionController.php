<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class CompetitionController extends Controller
{
    /**
     * عرض قائمة المسابقات
     */
    public function index(): View
    {
        $competitions = Competition::with(['creator', 'teams'])
            ->orderBy('created_at', 'desc')
            ->get();

        // إحصائيات
        $stats = [
            'total' => Competition::count(),
            'active' => Competition::where('is_active', true)->count(),
            'inactive' => Competition::where('is_active', false)->count(),
            'total_teams' => Team::count(),
            'complete_teams' => Team::where('is_complete', true)->count(),
            'incomplete_teams' => Team::where('is_complete', false)->count(),
        ];

        return view('dashboard.competitions.index', compact('competitions', 'stats'));
    }

    /**
     * عرض نموذج إضافة مسابقة جديدة
     */
    public function create(): View
    {
        return view('dashboard.competitions.create');
    }

    /**
     * حفظ مسابقة جديدة
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'game_type' => 'required|string|max:255',
            'team_size' => 'required|integer|min:2',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'عنوان المسابقة مطلوب',
            'game_type.required' => 'نوع اللعبة مطلوب',
            'team_size.required' => 'عدد أعضاء الفريق مطلوب',
            'team_size.min' => 'عدد أعضاء الفريق يجب أن يكون على الأقل 2',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = auth()->id();

        Competition::create($validated);

        return redirect()->route('dashboard.competitions.index')
            ->with('success', 'تم إضافة المسابقة بنجاح');
    }

    /**
     * عرض تفاصيل مسابقة مع الفرق
     */
    public function show(Competition $competition): View
    {
        $competition->load([
            'teams.members',
            'teams.creator',
            'creator'
        ]);

        // جلب جميع المستخدمين المسجلين في فرق هذه المسابقة
        $usersInTeams = DB::table('team_members')
            ->join('teams', 'team_members.team_id', '=', 'teams.id')
            ->where('teams.competition_id', $competition->id)
            ->pluck('team_members.user_id')
            ->unique()
            ->toArray();

        // جلب الأفراد المسجلين في المسابقة ولكن ليسوا في أي فريق
        $individualUsers = User::whereHas('competitionRegistrations', function($query) use ($competition) {
                $query->where('competition_id', $competition->id)
                      ->whereNull('team_id');
            })
            ->whereNotIn('id', $usersInTeams)
            ->with('competitionRegistrations')
            ->orderBy('name')
            ->get();

        // إحصائيات المسابقة
        $stats = [
            'total_teams' => $competition->teams()->count(),
            'complete_teams' => $competition->teams()->where('is_complete', true)->count(),
            'incomplete_teams' => $competition->teams()->where('is_complete', false)->count(),
            'total_members' => DB::table('team_members')
                ->join('teams', 'team_members.team_id', '=', 'teams.id')
                ->where('teams.competition_id', $competition->id)
                ->distinct('team_members.user_id')
                ->count('team_members.user_id'),
        ];

        return view('dashboard.competitions.show', compact('competition', 'stats', 'individualUsers'));
    }

    /**
     * إنشاء فريق من أفراد محددين
     */
    public function createTeamFromIndividuals(Request $request, Competition $competition): RedirectResponse
    {
        $validated = $request->validate([
            'team_name' => 'required|string|max:255',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ], [
            'team_name.required' => 'اسم الفريق مطلوب',
            'user_ids.required' => 'يجب اختيار عضو واحد على الأقل',
        ]);

        try {
            DB::beginTransaction();

            // التحقق من أن عدد الأعضاء لا يتجاوز حجم الفريق المطلوب
            if (count($validated['user_ids']) > $competition->team_size) {
                return back()->with('error', 'عدد الأعضاء المحددين يتجاوز حجم الفريق المطلوب (' . $competition->team_size . ')');
            }

            // التحقق من أن المستخدمين ليسوا في فرق أخرى في نفس المسابقة
            $usersInTeams = DB::table('team_members')
                ->join('teams', 'team_members.team_id', '=', 'teams.id')
                ->where('teams.competition_id', $competition->id)
                ->whereIn('team_members.user_id', $validated['user_ids'])
                ->pluck('team_members.user_id')
                ->toArray();

            if (!empty($usersInTeams)) {
                $users = User::whereIn('id', $usersInTeams)->pluck('name')->toArray();
                return back()->with('error', 'بعض المستخدمين المحددين موجودون بالفعل في فرق: ' . implode(', ', $users));
            }

            // إنشاء الفريق
            $team = Team::create([
                'competition_id' => $competition->id,
                'name' => $validated['team_name'],
                'created_by_user_id' => $validated['user_ids'][0], // أول عضو يكون القائد
                'is_complete' => false,
            ]);

            // إضافة الأعضاء للفريق وتحديث التسجيلات
            foreach ($validated['user_ids'] as $index => $userId) {
                $team->members()->attach($userId, [
                    'role' => $index === 0 ? 'captain' : 'member',
                    'joined_at' => now(),
                ]);

                // تحديث التسجيل برقم الفريق
                CompetitionRegistration::where('competition_id', $competition->id)
                    ->where('user_id', $userId)
                    ->update(['team_id' => $team->id]);
            }

            // التحقق من اكتمال الفريق
            $team->checkCompletion();

            DB::commit();

            return back()->with('success', 'تم إنشاء الفريق بنجاح!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تعديل مسابقة
     */
    public function edit(Competition $competition): View
    {
        return view('dashboard.competitions.edit', compact('competition'));
    }

    /**
     * تحديث مسابقة
     */
    public function update(Request $request, Competition $competition): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'game_type' => 'required|string|max:255',
            'team_size' => 'required|integer|min:2',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'عنوان المسابقة مطلوب',
            'game_type.required' => 'نوع اللعبة مطلوب',
            'team_size.required' => 'عدد أعضاء الفريق مطلوب',
            'team_size.min' => 'عدد أعضاء الفريق يجب أن يكون على الأقل 2',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $competition->update($validated);

        return redirect()->route('dashboard.competitions.index')
            ->with('success', 'تم تحديث المسابقة بنجاح');
    }

    /**
     * حذف مسابقة
     */
    public function destroy(Competition $competition): RedirectResponse
    {
        $competition->delete();

        return redirect()->route('dashboard.competitions.index')
            ->with('success', 'تم حذف المسابقة بنجاح');
    }
}
