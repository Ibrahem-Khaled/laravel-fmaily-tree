<?php

namespace App\Http\Controllers;

use App\Models\Competition;
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

        // تحميل الفرق غير المكتملة
        $competition->load(['teams' => function($query) {
            $query->where('is_complete', false);
        }]);

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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'team_name' => 'required|string|max:255',
            'join_existing_team' => 'nullable|boolean',
            'existing_team_id' => 'nullable|exists:teams,id',
        ], [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'team_name.required' => 'اسم الفريق مطلوب',
        ]);

        try {
            DB::beginTransaction();

            // البحث عن المستخدم أو إنشاؤه
            $user = User::where('phone', $validated['phone'])->first();

            if (!$user) {
                // إنشاء مستخدم جديد
                $user = User::create([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'password' => Hash::make(uniqid()), // كلمة مرور عشوائية
                    'status' => 1,
                ]);
            } else {
                // تحديث بيانات المستخدم إذا كانت غير موجودة
                if (empty($user->name)) {
                    $user->name = $validated['name'];
                }
                if (empty($user->email) && !empty($validated['email'])) {
                    $user->email = $validated['email'];
                }
                $user->save();
            }

            // تحديد الفريق
            if ($request->has('join_existing_team') && $request->has('existing_team_id')) {
                $team = Team::where('id', $request->existing_team_id)
                    ->where('competition_id', $competition->id)
                    ->first();

                if (!$team) {
                    throw new \Exception('الفريق المحدد غير موجود');
                }

                if ($team->is_complete) {
                    throw new \Exception('الفريق مكتمل ولا يمكن إضافة أعضاء جدد');
                }

                // التحقق من أن المستخدم ليس في الفريق بالفعل
                if ($team->members()->where('user_id', $user->id)->exists()) {
                    throw new \Exception('أنت مسجل بالفعل في هذا الفريق');
                }
            } else {
                // إنشاء فريق جديد
                $team = Team::create([
                    'competition_id' => $competition->id,
                    'name' => $validated['team_name'],
                    'created_by_user_id' => $user->id,
                    'is_complete' => false,
                ]);
            }

            // إضافة المستخدم للفريق
            $team->members()->attach($user->id, [
                'role' => $team->created_by_user_id === $user->id ? 'captain' : 'member',
                'joined_at' => now(),
            ]);

            // التحقق من اكتمال الفريق
            $team->checkCompletion();

            DB::commit();

            return redirect()->route('competitions.team.register', ['team' => $team->id])
                ->with('success', 'تم التسجيل بنجاح! يمكنك مشاركة رابط الفريق مع الأعضاء الآخرين.');

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
            'email' => 'nullable|email|max:255',
        ], [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
        ]);

        try {
            DB::beginTransaction();

            // البحث عن المستخدم أو إنشاؤه
            $user = User::where('phone', $validated['phone'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'password' => Hash::make(uniqid()),
                    'status' => 1,
                ]);
            } else {
                if (empty($user->name)) {
                    $user->name = $validated['name'];
                }
                if (empty($user->email) && !empty($validated['email'])) {
                    $user->email = $validated['email'];
                }
                $user->save();
            }

            // التحقق من أن المستخدم ليس في الفريق بالفعل
            if ($team->members()->where('user_id', $user->id)->exists()) {
                throw new \Exception('أنت مسجل بالفعل في هذا الفريق');
            }

            // إضافة المستخدم للفريق
            $team->members()->attach($user->id, [
                'role' => 'member',
                'joined_at' => now(),
            ]);

            // التحقق من اكتمال الفريق
            $team->checkCompletion();

            DB::commit();

            return back()->with('success', 'تم إضافة العضو للفريق بنجاح!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
