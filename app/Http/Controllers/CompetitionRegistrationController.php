<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionRegistration;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompetitionRegistrationController extends Controller
{
    /**
     * عرض صفحة التسجيل في المسابقة
     */
    public function register(string $token): View|RedirectResponse
    {
        $competition = Competition::where('registration_token', $token)->first();

        if (!$competition) {
            return redirect()->route('home')
                ->with('error', 'رابط التسجيل غير صحيح');
        }

        if (!$competition->isRegistrationOpen()) {
            return redirect()->route('home')
                ->with('error', 'المسابقة غير متاحة للتسجيل حالياً');
        }

        // تحميل الفرق غير المكتملة والتصنيفات
        $competition->load([
            'teams' => function($query) {
                $query->where('is_complete', false);
            },
            'categories'
        ]);

        return view('competitions.register', compact('competition'));
    }

    /**
     * معالجة التسجيل في المسابقة
     */
    public function store(Request $request, string $token): RedirectResponse
    {
        $competition = Competition::where('registration_token', $token)->first();

        if (!$competition || !$competition->isRegistrationOpen()) {
            return back()->with('error', 'المسابقة غير متاحة للتسجيل');
        }

        // جلب التصنيفات المتاحة في المسابقة
        $competition->load('categories');
        $hasCategories = $competition->categories->isNotEmpty();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'brother_name' => 'nullable|string|max:255',
            'brother_phone' => 'nullable|string|max:20',
            'team_name' => 'nullable|string|max:255',
            'category_ids' => $hasCategories ? 'required|array|min:1' : 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ], [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'brother_name.required' => 'اسم خوي مطلوب',
            'brother_phone.required' => 'رقم هاتف خوي مطلوب',
            'team_name.required' => 'اسم الفريق مطلوب',
            'category_ids.required' => 'يجب اختيار تصنيف واحد على الأقل',
            'category_ids.min' => 'يجب اختيار تصنيف واحد على الأقل',
            'category_ids.*.exists' => 'أحد التصنيفات المختارة غير موجود',
        ]);

        // التحقق من بيانات خوي إذا تم إدخالها
        if ($request->filled('brother_name') || $request->filled('brother_phone')) {
            $request->validate([
                'brother_name' => 'required|string|max:255',
                'brother_phone' => 'required|string|max:20',
                'team_name' => 'required|string|max:255',
            ], [
                'brother_name.required' => 'اسم خوي مطلوب',
                'brother_phone.required' => 'رقم هاتف خوي مطلوب',
                'team_name.required' => 'اسم الفريق مطلوب عند التسجيل مع خوي',
            ]);
        }

        try {
            DB::beginTransaction();

            // إنشاء مستخدم جديد لكل تسجيل (السماح بتسجيل أكثر من مستخدم بنفس رقم الهاتف)
            $user = User::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'password' => Hash::make(uniqid()),
                'status' => 1,
            ]);

            // معالجة بيانات خوي إذا كانت موجودة
            $brotherUser = null;
            if (!empty($validated['brother_name']) && !empty($validated['brother_phone'])) {
                $brotherUser = User::where('phone', $validated['brother_phone'])->first();

                if (!$brotherUser) {
                    // إنشاء مستخدم جديد لخوي
                    $brotherUser = User::create([
                        'name' => $validated['brother_name'],
                        'phone' => $validated['brother_phone'],
                        'password' => Hash::make(uniqid()),
                        'status' => 1,
                    ]);
                } else {
                    // تحديث بيانات خوي إذا كانت غير موجودة
                    if (empty($brotherUser->name)) {
                        $brotherUser->name = $validated['brother_name'];
                    }
                    $brotherUser->save();
                }
            }

            // تسجيل المستخدم في المسابقة (سجل جديد لكل تسجيل)
            $registration = CompetitionRegistration::create([
                'competition_id' => $competition->id,
                'user_id' => $user->id,
                'team_id' => null,
                'has_brother' => !empty($brotherUser),
            ]);

            // ربط التصنيفات المختارة بالتسجيل
            if ($request->has('category_ids') && is_array($request->category_ids)) {
                $categoryIds = array_filter($request->category_ids);
                // التحقق من أن التصنيفات المختارة موجودة في مسابقة
                $validCategoryIds = $competition->categories()
                    ->whereIn('categories.id', $categoryIds)
                    ->pluck('categories.id')
                    ->toArray();
                
                if (!empty($validCategoryIds)) {
                    $registration->categories()->sync($validCategoryIds);
                }
            }

            // إنشاء فريق فقط إذا كان معه خوي
            $team = null;
            if ($brotherUser && !empty($validated['team_name'])) {
                // إنشاء فريق جديد
                $team = Team::create([
                    'competition_id' => $competition->id,
                    'name' => $validated['team_name'],
                    'created_by_user_id' => $user->id,
                    'is_complete' => false,
                ]);

                $registration->update(['team_id' => $team->id]);
            }

            // إضافة المستخدم للفريق إذا كان موجوداً (أي إذا كان معه خوي)
            if ($team) {
                $team->members()->attach($user->id, [
                    'role' => 'captain',
                    'joined_at' => now(),
                ]);

                // تسجيل خوي في المسابقة أيضاً
                if ($brotherUser) {
                    $brotherRegistration = CompetitionRegistration::updateOrCreate(
                        [
                            'competition_id' => $competition->id,
                            'user_id' => $brotherUser->id,
                        ],
                        [
                            'has_brother' => false,
                            'team_id' => $team->id,
                        ]
                    );

                    // ربط نفس التصنيفات المختارة بتسجيل خوي
                    if ($request->has('category_ids') && is_array($request->category_ids)) {
                        $categoryIds = array_filter($request->category_ids);
                        $validCategoryIds = $competition->categories()
                            ->whereIn('categories.id', $categoryIds)
                            ->pluck('categories.id')
                            ->toArray();
                        
                        if (!empty($validCategoryIds)) {
                            $brotherRegistration->categories()->sync($validCategoryIds);
                        }
                    }

                    // إضافة خوي للفريق
                    // التحقق من أن الفريق لم يكتمل
                    if (!$team->is_complete) {
                        // التحقق من أن خوي ليس في الفريق بالفعل
                        if (!$team->members()->where('user_id', $brotherUser->id)->exists()) {
                            $team->members()->attach($brotherUser->id, [
                                'role' => 'member',
                                'joined_at' => now(),
                            ]);
                        }
                    }
                }

                // التحقق من اكتمال الفريق
                $team->checkCompletion();

                DB::commit();

                return redirect()->route('competitions.team.register', ['team' => $team->id])
                    ->with('success', 'تم التسجيل بنجاح');
            } else {
                // إذا لم يكن معه خوي، يسجل كفرد فقط
                DB::commit();

                return redirect()->route('competitions.register', ['token' => $competition->registration_token])
                    ->with('success', 'تم التسجيل بنجاح');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * عرض صفحة تسجيل عضو في فريق
     */
    public function teamRegister(Team $team): View|RedirectResponse
    {
        $competition = $team->competition;

        if (!$competition->isRegistrationOpen()) {
            return redirect()->route('home')
                ->with('error', 'المسابقة غير متاحة للتسجيل حالياً');
        }

        if ($team->is_complete) {
            return redirect()->route('home')
                ->with('error', 'الفريق مكتمل ولا يمكن إضافة أعضاء جدد');
        }

        $team->load('members', 'competition');

        return view('competitions.team-register', compact('team', 'competition'));
    }

    /**
     * معالجة تسجيل عضو في فريق
     */
    public function teamStore(Request $request, Team $team): RedirectResponse
    {
        $competition = $team->competition;

        if (!$competition->isRegistrationOpen() || $team->is_complete) {
            return back()->with('error', 'لا يمكن التسجيل في هذا الفريق');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ], [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
        ]);

        try {
            DB::beginTransaction();

            // إنشاء مستخدم جديد (السماح بنفس رقم الهاتف لأكثر من مستخدم)
            $user = User::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'password' => Hash::make(uniqid()),
                'status' => 1,
            ]);

            // إضافة المستخدم للفريق
            $team->members()->attach($user->id, [
                'role' => 'member',
                'joined_at' => now(),
            ]);

            // تسجيل المستخدم في المسابقة
            CompetitionRegistration::create([
                'competition_id' => $competition->id,
                'user_id' => $user->id,
                'team_id' => $team->id,
                'has_brother' => false,
            ]);

            // التحقق من اكتمال الفريق
            $team->checkCompletion();

            DB::commit();

            return back()->with('success', 'تم التسجيل بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
