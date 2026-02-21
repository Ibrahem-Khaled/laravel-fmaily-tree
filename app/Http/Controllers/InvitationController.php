<?php

namespace App\Http\Controllers;

use App\Jobs\SendWhatsAppInvitationJob;
use App\Models\Notification;
use App\Models\NotificationGroup;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class InvitationController extends Controller
{
    /**
     * صفحة تسجيل الدخول/التسجيل بالاسم والهاتف
     */
    public function loginIndex()
    {
        if (Auth::check()) {
            return redirect()->route('invitations.dashboard');
        }
        return view('invitations.index');
    }

    /**
     * لوحة تحكم الدعوات
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('invitations.index');
        }

        $stats = [
            'total_groups' => NotificationGroup::where('user_id', Auth::id())->count(),
            'total_sent' => Notification::where('user_id', Auth::id())->count(),
            'recent_notifications' => Notification::where('user_id', Auth::id())
                ->with(['recipients'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('invitations.dashboard', $stats);
    }

    /**
     * معالجة تسجيل الدخول/التسجيل بالهاتف
     */
    public function loginOrRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ], [], [
            'name' => 'الاسم',
            'phone' => 'رقم الهاتف',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $validated['phone']);

        if (empty($phone)) {
            return back()->withErrors(['phone' => 'رقم الهاتف غير صالح.'])->withInput();
        }

        // البحث عن مستخدم موجود بنفس الرقم
        $user = \App\Models\User::where('phone', $phone)->first();

        if ($user) {
            // تسجيل دخول تلقائي
            Auth::login($user);
            Log::channel('ultramsg')->info('Invitation: Auto-login by phone', ['user_id' => $user->id, 'phone' => $phone]);
            return redirect()->route('invitations.dashboard')->with('success', 'مرحباً ' . $user->name);
        } else {
            // إنشاء حساب جديد بدون كلمة مرور (نستخدم رقم عشوائي)
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'phone' => $phone,
                'password' => Hash::make(uniqid('', true)), // كلمة مرور عشوائية
                'email' => 'invitation_' . time() . '@temp.local', // email مؤقت
                'status' => 'active',
            ]);

            Auth::login($user);
            Log::channel('ultramsg')->info('Invitation: New user created by phone', ['user_id' => $user->id, 'phone' => $phone]);
            return redirect()->route('invitations.dashboard')->with('success', 'تم إنشاء حسابك بنجاح. مرحباً ' . $user->name);
        }
    }

    /**
     * صفحة إرسال الدعوات (للمستخدم المسجل)
     */
    public function send(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('invitations.index')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        $groups = NotificationGroup::where('user_id', Auth::id())
            ->withCount('persons')
            ->orderBy('name')
            ->get();

        return view('invitations.send', compact('groups'));
    }

    /**
     * معالجة إرسال الدعوة
     */
    public function sendSubmit(Request $request): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('invitations.index')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        Log::channel('ultramsg')->info('=== INVITATION SEND SUBMIT START ===', [
            'user' => Auth::user()->name ?? Auth::id(),
            'recipient_type' => $request->input('recipient_type'),
            'media_type' => $request->input('media_type'),
            'has_media_file' => $request->hasFile('media'),
        ]);

        $validated = $request->validate([
            'recipient_type' => 'required|in:persons,group',
            'person_ids' => 'required_if:recipient_type,persons|array|min:1',
            'person_ids.*' => 'integer|exists:persons,id',
            'group_id' => 'required_if:recipient_type,group|nullable|integer|exists:notification_groups,id',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string|max:65535',
            'media_type' => 'nullable|string|in:image,video,voice',
            'media' => 'nullable|file|max:16384', // 16MB
        ], [], [
            'recipient_type' => 'نوع المستلمين',
            'person_ids' => 'الأشخاص',
            'group_id' => 'المجموعة',
            'body' => 'نص الرسالة',
            'media' => 'الملف المرفق',
        ]);

        // التحقق من أن المجموعة تخص المستخدم الحالي
        if ($validated['recipient_type'] === 'group') {
            $group = NotificationGroup::findOrFail($validated['group_id']);
            if ($group->user_id !== Auth::id()) {
                return back()->with('error', 'ليس لديك صلاحية للوصول لهذه المجموعة.');
            }
        }

        $personIds = $validated['recipient_type'] === 'group'
            ? $group->persons()->pluck('persons.id')->toArray()
            : array_values($validated['person_ids']);

        Log::channel('ultramsg')->info('Person IDs resolved', ['count' => count($personIds), 'ids' => $personIds]);

        $persons = Person::with(['contactAccounts', 'parent.parent.parent.parent.parent'])
            ->whereIn('id', $personIds)
            ->get();

        $personsWithWhatsApp = [];
        $personsWithoutWhatsApp = [];
        foreach ($persons as $person) {
            $numbers = $this->getWhatsAppNumbers($person);
            if (!empty($numbers)) {
                $personsWithWhatsApp[] = ['person' => $person, 'numbers' => $numbers];
            } else {
                $personsWithoutWhatsApp[] = $person->full_name . ' (#' . $person->id . ')';
            }
        }

        if (!empty($personsWithoutWhatsApp)) {
            Log::channel('ultramsg')->warning('Persons without WhatsApp', ['persons' => $personsWithoutWhatsApp]);
        }

        if (empty($personsWithWhatsApp)) {
            Log::channel('ultramsg')->error('No persons with WhatsApp found, aborting');
            return back()->with('error', 'لا يوجد أي شخص من المحددين لديه رقم واتساب مسجّل.');
        }

        // Handle media upload
        $mediaPath = null;
        $mediaType = $validated['media_type'] ?? null;
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mediaPath = $file->store('notifications', 'public');
            $mediaType = $mediaType ?? $this->guessMediaType($file->getMimeType());

            Log::channel('ultramsg')->info('Media uploaded', [
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size_kb' => round($file->getSize() / 1024),
                'stored_path' => $mediaPath,
                'media_type' => $mediaType,
            ]);
        }

        // Warn if using media with localhost
        if ($mediaPath && in_array($mediaType, ['video', 'voice'])) {
            $appUrl = config('app.url', 'http://localhost');
            if (str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1')) {
                Log::channel('ultramsg')->warning('APP_URL is localhost — video/voice media will NOT be accessible by UltraMSG. Images use base64 and will work.');
            }
        }

        $notification = Notification::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'] ?? null,
            'body' => $validated['body'] ?? '',
            'media_type' => $mediaType,
            'media_path' => $mediaPath,
            'status' => 'sending',
        ]);

        Log::channel('ultramsg')->info('Notification created', ['notification_id' => $notification->id]);

        foreach ($personsWithWhatsApp as $item) {
            /** @var Person $person */
            $person = $item['person'];
            $numbers = $item['numbers'];
            $notification->recipients()->create(['person_id' => $person->id]);
            $messageBody = $this->replacePlaceholders($validated['body'] ?? '', $person);
            $toNumber = $numbers[0];

            Log::channel('ultramsg')->info('Dispatching job', [
                'notification_id' => $notification->id,
                'person' => $person->full_name,
                'to' => $toNumber,
                'media_type' => $mediaType,
            ]);

            SendWhatsAppInvitationJob::dispatch(
                $notification->id,
                $person->id,
                $toNumber,
                $messageBody,
                $mediaType,
                $mediaPath
            );
        }

        Log::channel('ultramsg')->info('=== INVITATION SEND SUBMIT DONE ===', [
            'notification_id' => $notification->id,
            'dispatched_count' => count($personsWithWhatsApp),
        ]);

        return redirect()->route('invitations.logs')
            ->with('success', 'تم بدء إرسال الإشعار لـ ' . count($personsWithWhatsApp) . ' شخص.');
    }

    /**
     * سجل الإرسال
     */
    public function logs()
    {
        if (!Auth::check()) {
            return redirect()->route('invitations.index');
        }

        $notifications = Notification::where('user_id', Auth::id())
            ->with(['recipients.person'])
            ->latest()
            ->paginate(10);

        return view('invitations.logs', compact('notifications'));
    }

    // ========== Groups Management ==========

    public function groupsIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('invitations.index');
        }

        $groups = NotificationGroup::where('user_id', Auth::id())
            ->withCount('persons')
            ->latest()
            ->get();

        return view('invitations.groups.index', compact('groups'));
    }

    public function groupsCreate()
    {
        if (!Auth::check()) {
            return redirect()->route('invitations.index');
        }

        return view('invitations.groups.create');
    }

    public function groupsStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ], [], [
            'name' => 'اسم المجموعة',
            'description' => 'الوصف',
        ]);

        NotificationGroup::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('invitations.groups.index')
            ->with('success', 'تم إنشاء المجموعة بنجاح');
    }

    public function groupsEdit(NotificationGroup $group)
    {
        if (!Auth::check() || (int) $group->user_id !== (int) Auth::id()) {
            return redirect()->route('invitations.index')->with('error', 'غير مصرح');
        }

        $group->load('persons');
        return view('invitations.groups.edit', compact('group'));
    }

    public function groupsUpdate(Request $request, NotificationGroup $group): RedirectResponse
    {
        if ((int) $group->user_id !== (int) Auth::id()) {
            return back()->with('error', 'غير مصرح');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $group->update($validated);

        return redirect()->route('invitations.groups.index')
            ->with('success', 'تم تحديث المجموعة بنجاح');
    }

    public function groupsDestroy(NotificationGroup $group): RedirectResponse
    {
        if ((int) $group->user_id !== (int) Auth::id()) {
            return back()->with('error', 'غير مصرح');
        }

        $group->delete();

        return redirect()->route('invitations.groups.index')
            ->with('success', 'تم حذف المجموعة بنجاح');
    }

    public function groupsAttachPerson(Request $request, NotificationGroup $group): RedirectResponse
    {
        if ((int) $group->user_id !== (int) Auth::id()) {
            return back()->with('error', 'غير مصرح');
        }

        $validated = $request->validate([
            'person_id' => 'required|integer|exists:persons,id',
        ]);

        $maxOrder = $group->persons()->max('notification_group_person.sort_order') ?? 0;
        $group->persons()->syncWithoutDetaching([
            $validated['person_id'] => ['sort_order' => $maxOrder + 1],
        ]);

        return back()->with('success', 'تمت إضافة الشخص للمجموعة');
    }

    public function groupsDetachPerson(NotificationGroup $group, Person $person): RedirectResponse
    {
        if ((int) $group->user_id !== (int) Auth::id()) {
            return back()->with('error', 'غير مصرح');
        }

        $group->persons()->detach($person->id);

        return back()->with('success', 'تمت إزالة الشخص من المجموعة');
    }

    /**
     * AJAX: البحث عن أشخاص لديهم واتساب
     */
    public function personsWithWhatsApp(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $query = Person::whereHas('contactAccounts', function ($q) {
            $q->where('type', 'whatsapp')->whereNotNull('value')->where('value', '!=', '');
        });

        if ($search !== '') {
            $query->searchByName($search);
        }

        $persons = $query->with(['contactAccounts', 'parent.parent.parent.parent.parent'])
            ->limit(50)
            ->get();

        $result = [];
        foreach ($persons as $person) {
            $numbers = $this->getWhatsAppNumbers($person);
            if (!empty($numbers)) {
                $result[] = [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'first_name' => $person->first_name,
                    'whatsapp_numbers' => $numbers,
                ];
            }
        }
        return response()->json(['persons' => $result]);
    }

    /**
     * AJAX: معاينة الرسالة
     */
    public function previewMessage(Request $request)
    {
        $validated = $request->validate([
            'body' => 'nullable|string',
            'person_id' => 'required|integer|exists:persons,id',
        ]);
        $person = Person::with(['parent.parent.parent.parent.parent'])->findOrFail($validated['person_id']);
        $text = $this->replacePlaceholders($validated['body'] ?? '', $person);
        return response()->json(['preview' => $text]);
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('invitations.index')->with('success', 'تم تسجيل الخروج بنجاح.');
    }

    private function getWhatsAppNumbers(Person $person): array
    {
        $numbers = [];
        if (!$person->relationLoaded('contactAccounts')) {
            $person->load('contactAccounts');
        }
        if ($person->contactAccounts) {
            foreach ($person->contactAccounts->where('type', 'whatsapp') as $account) {
                $clean = preg_replace('/[^0-9]/', '', $account->value);
                if ($clean && !in_array($clean, $numbers)) {
                    $numbers[] = $clean;
                }
            }
        }
        return $numbers;
    }

    private function replacePlaceholders(string $body, Person $person): string
    {
        $fullName = $person->full_name;
        $firstName = $person->first_name;
        $body = str_replace(['{name}', '{first_name}', '{full_name}'], [$firstName, $firstName, $fullName], $body);
        return $body;
    }

    private function guessMediaType(?string $mime): string
    {
        if (!$mime) {
            return 'image';
        }
        if (str_starts_with($mime, 'video/')) {
            return 'video';
        }
        if (str_starts_with($mime, 'audio/')) {
            return 'voice';
        }
        return 'image';
    }
}
