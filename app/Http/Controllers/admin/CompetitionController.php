<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionRegistration;
use App\Models\Team;
use App\Models\User;
use App\Models\Image;
use App\Models\Category;
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
        $competitions = Competition::with(['creator', 'teams', 'program'])
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
        $programs = Image::where('is_program', true)
            ->where('program_is_active', true)
            ->orderBy('program_order')
            ->get();
        
        // جلب جميع التصنيفات النشطة (لإتاحة إعادة استخدامها وإضافة تصنيفات جديدة)
        $categories = Category::active()
            ->ordered()
            ->get();
        
        return view('dashboard.competitions.create', compact('programs', 'categories'));
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
            'team_size' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'program_id' => 'nullable|exists:images,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ], [
            'title.required' => 'عنوان المسابقة مطلوب',
            'game_type.required' => 'نوع اللعبة مطلوب',
            'team_size.required' => 'عدد أعضاء الفريق مطلوب',
            'team_size.min' => 'عدد أعضاء الفريق يجب أن يكون على الأقل 1',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
            'category_ids.array' => 'التصنيفات يجب أن تكون مصفوفة',
            'category_ids.*.exists' => 'أحد التصنيفات المختارة غير موجود',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = auth()->id();

        try {
            DB::beginTransaction();

            // إنشاء المسابقة
            $competition = Competition::create($validated);

            // معالجة التصنيفات المختارة
            $categoryIds = [];
            
            // إضافة التصنيفات المختارة
            if ($request->has('category_ids') && is_array($request->category_ids)) {
                $categoryIds = array_filter(array_map('intval', $request->category_ids));
            }
            
            // Debug: التحقق من البيانات المرسلة
            \Log::info('Competition Store - Categories', [
                'category_ids' => $categoryIds,
                'request_category_ids' => $request->category_ids ?? null,
            ]);

            // ربط التصنيفات بالمسابقة
            if (!empty($categoryIds)) {
                $competition->categories()->sync(array_unique($categoryIds));
            } else {
                // إذا لم يتم اختيار أي تصنيفات، قم بإزالة جميع التصنيفات
                $competition->categories()->sync([]);
            }

            DB::commit();

            return redirect()->route('dashboard.competitions.index')
                ->with('success', 'تم إضافة المسابقة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء إضافة المسابقة: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل مسابقة مع الفرق
     */
    public function show(Competition $competition): View
    {
        $competition->load([
            'teams.members.competitionRegistrations' => function($query) use ($competition) {
                $query->where('competition_id', $competition->id)
                      ->with('categories');
            },
            'teams.creator',
            'creator',
            'categories',
            'registrations.categories'
        ]);

        // جلب الأفراد: من لديهم تسجيل بـ team_id = null (مطابق لصفحة البرنامج، بدون استبعاد من في team_members)
        $individualUsers = User::whereHas('competitionRegistrations', function($query) use ($competition) {
                $query->where('competition_id', $competition->id)
                      ->whereNull('team_id');
            })
            ->with(['competitionRegistrations' => function($query) use ($competition) {
                $query->where('competition_id', $competition->id)
                      ->with('categories');
            }])
            ->orderBy('name')
            ->get();

        // إضافة أعضاء الفرق من عضو واحد إلى قائمة الأفراد (مطابقة لصفحة البرنامج العامة)
        $singleMemberTeamUserIds = $competition->teams
            ->filter(fn($team) => $team->members->count() === 1)
            ->pluck('members')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->values()
            ->all();
        if (!empty($singleMemberTeamUserIds)) {
            $singleMemberUsers = User::whereIn('id', $singleMemberTeamUserIds)
                ->with(['competitionRegistrations' => function($query) use ($competition) {
                    $query->where('competition_id', $competition->id)
                          ->with('categories');
                }])
                ->orderBy('name')
                ->get();
            $individualUsers = $individualUsers->concat($singleMemberUsers)->unique('id')->sortBy('name')->values();
        }

        // إحصائيات المسابقة (إجمالي المسجلين = عدد المستخدمين الفريدين من التسجيلات)
        $stats = [
            'total_teams' => $competition->teams()->count(),
            'complete_teams' => $competition->teams()->where('is_complete', true)->count(),
            'incomplete_teams' => $competition->teams()->where('is_complete', false)->count(),
            'total_members' => $competition->registrations()->distinct('user_id')->count('user_id'),
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
     * تحديث اسم متسابق (فرد أو عضو فريق) في المسابقة
     */
    public function updateCompetitorName(Request $request, Competition $competition): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name'    => 'required|string|max:255',
        ], [
            'user_id.required' => 'المتسابق مطلوب',
            'user_id.exists'   => 'المتسابق غير موجود',
            'name.required'   => 'الاسم مطلوب',
            'name.max'        => 'الاسم يجب ألا يتجاوز 255 حرفاً',
        ]);

        $userId = (int) $validated['user_id'];
        $name   = trim($validated['name']);
        if ($name === '') {
            return response()->json(['success' => false, 'message' => 'الاسم لا يمكن أن يكون فارغاً'], 422);
        }

        // التحقق من أن المستخدم مسجل في هذه المسابقة (فرد غير مجمع أو عضو في فريق)
        $isIndividual = CompetitionRegistration::where('competition_id', $competition->id)
            ->where('user_id', $userId)
            ->whereNull('team_id')
            ->exists();

        $isTeamMember = DB::table('team_members')
            ->join('teams', 'team_members.team_id', '=', 'teams.id')
            ->where('teams.competition_id', $competition->id)
            ->where('team_members.user_id', $userId)
            ->exists();

        if (!$isIndividual && !$isTeamMember) {
            return response()->json(['success' => false, 'message' => 'المتسابق غير مسجل في هذه المسابقة'], 403);
        }

        $user = User::findOrFail($userId);
        $user->update(['name' => $name]);

        return response()->json(['success' => true, 'name' => $user->name]);
    }

    /**
     * تحديث اسم فريق
     */
    public function updateTeamName(Request $request, Team $team): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'اسم الفريق مطلوب',
            'name.max'      => 'اسم الفريق يجب ألا يتجاوز 255 حرفاً',
        ]);

        $name = trim($validated['name']);
        if ($name === '') {
            return response()->json(['success' => false, 'message' => 'اسم الفريق لا يمكن أن يكون فارغاً'], 422);
        }

        $team->update(['name' => $name]);

        return response()->json(['success' => true, 'name' => $team->name]);
    }

    /**
     * عرض نموذج تعديل مسابقة
     */
    public function edit(Competition $competition): View
    {
        $programs = Image::where('is_program', true)
            ->where('program_is_active', true)
            ->orderBy('program_order')
            ->get();
        
        // جلب جميع التصنيفات النشطة (لإتاحة إعادة استخدامها وإضافة تصنيفات جديدة)
        $categories = Category::active()
            ->ordered()
            ->get();
        
        // جلب التصنيفات المختارة للمسابقة
        $competition->load('categories');
        
        return view('dashboard.competitions.edit', compact('competition', 'programs', 'categories'));
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
            'team_size' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'program_id' => 'nullable|exists:images,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ], [
            'title.required' => 'عنوان المسابقة مطلوب',
            'game_type.required' => 'نوع اللعبة مطلوب',
            'team_size.required' => 'عدد أعضاء الفريق مطلوب',
            'team_size.min' => 'عدد أعضاء الفريق يجب أن يكون على الأقل 1',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
            'category_ids.array' => 'التصنيفات يجب أن تكون مصفوفة',
            'category_ids.*.exists' => 'أحد التصنيفات المختارة غير موجود',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            DB::beginTransaction();

            // تحديث بيانات المسابقة
            $competition->update($validated);

            // معالجة التصنيفات المختارة
            $categoryIds = [];
            
            // إضافة التصنيفات المختارة
            if ($request->has('category_ids') && is_array($request->category_ids)) {
                $categoryIds = array_filter(array_map('intval', $request->category_ids));
            }
            
            // Debug: التحقق من البيانات المرسلة
            \Log::info('Competition Update - Categories', [
                'category_ids' => $categoryIds,
                'request_category_ids' => $request->category_ids ?? null,
            ]);

            // ربط التصنيفات بالمسابقة (sync لحذف القديمة وإضافة الجديدة)
            $competition->categories()->sync(array_unique($categoryIds));

            DB::commit();

            return redirect()->route('dashboard.competitions.index')
                ->with('success', 'تم تحديث المسابقة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث المسابقة: ' . $e->getMessage());
        }
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

    /**
     * حذف تسجيل مستخدم من المسابقة
     */
    public function removeRegistration(Competition $competition, User $user): RedirectResponse
    {
        CompetitionRegistration::where('competition_id', $competition->id)
            ->where('user_id', $user->id)
            ->whereNull('team_id')
            ->delete();

        return redirect()->route('dashboard.competitions.show', $competition)
            ->with('success', 'تم حذف التسجيل بنجاح');
    }

    /**
     * حذف فريق من المسابقة
     */
    public function destroyTeam(Team $team): RedirectResponse
    {
        $competition = $team->competition;

        try {
            DB::beginTransaction();

            // إزالة الأعضاء من الفريق
            $team->members()->detach();

            // تحديث أو حذف سجلات التسجيل المرتبطة
            CompetitionRegistration::where('team_id', $team->id)->update(['team_id' => null]);

            $team->delete();

            DB::commit();

            return redirect()->route('dashboard.competitions.show', $competition)
                ->with('success', 'تم حذف الفريق بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حذف الفريق: ' . $e->getMessage());
        }
    }

    /**
     * استخراج بيانات المسابقة إلى ملف إكسيل
     */
    public function export(Competition $competition)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\CompetitionExport($competition),
            'مسابقة_' . str_replace(' ', '_', $competition->title) . '_' . date('Y-m-d') . '.xlsx'
        );
    }
}
