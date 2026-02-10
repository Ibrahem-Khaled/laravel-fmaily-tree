<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsAppInvitationJob;
use App\Models\Notification;
use App\Models\NotificationGroup;
use App\Models\Person;
use App\Models\UltramsgSendLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        return view('dashboard.notifications.index');
    }

    public function send(Request $request): View
    {
        $groups = NotificationGroup::withCount('persons')->orderBy('name')->get();
        return view('dashboard.notifications.send', compact('groups'));
    }

    public function sendSubmit(Request $request): RedirectResponse
    {
        Log::channel('ultramsg')->info('=== SEND SUBMIT START ===', [
            'user' => $request->user()->name ?? $request->user()->id,
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

        $personIds = $validated['recipient_type'] === 'group'
            ? NotificationGroup::findOrFail($validated['group_id'])->persons()->pluck('persons.id')->toArray()
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
            'user_id' => $request->user()->id,
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

        Log::channel('ultramsg')->info('=== SEND SUBMIT DONE ===', [
            'notification_id' => $notification->id,
            'dispatched_count' => count($personsWithWhatsApp),
        ]);

        return redirect()->route('dashboard.notifications.logs')
            ->with('success', 'تم بدء إرسال الإشعار لـ ' . count($personsWithWhatsApp) . ' شخص.');
    }

    public function logs(Request $request): View
    {
        $query = Notification::with(['user', 'recipients.person', 'deliveries.deliverable'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $notifications = $query->paginate(15)->withQueryString();
        return view('dashboard.notifications.logs', compact('notifications'));
    }

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

    // Groups
    public function groupsIndex(): View
    {
        $groups = NotificationGroup::withCount('persons')->orderBy('name')->get();
        return view('dashboard.notifications.groups.index', compact('groups'));
    }

    public function groupsCreate(): View
    {
        return view('dashboard.notifications.groups.create');
    }

    public function groupsStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
        ]);
        NotificationGroup::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'user_id' => $request->user()->id,
        ]);
        return redirect()->route('dashboard.notification-groups.index')->with('success', 'تم إنشاء المجموعة.');
    }

    public function groupsEdit(NotificationGroup $notificationGroup): View
    {
        $notificationGroup->load('persons');
        return view('dashboard.notifications.groups.edit', compact('notificationGroup'));
    }

    public function groupsUpdate(Request $request, NotificationGroup $notificationGroup): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
        ]);
        $notificationGroup->update($validated);
        return redirect()->route('dashboard.notification-groups.index')->with('success', 'تم تحديث المجموعة.');
    }

    public function groupsDestroy(NotificationGroup $notificationGroup): RedirectResponse
    {
        $notificationGroup->delete();
        return redirect()->route('dashboard.notification-groups.index')->with('success', 'تم حذف المجموعة.');
    }

    public function groupsPersons(NotificationGroup $notificationGroup)
    {
        $persons = $notificationGroup->persons()->with(['contactAccounts'])->orderBy('notification_group_person.sort_order')->get();
        $list = [];
        foreach ($persons as $person) {
            $numbers = $this->getWhatsAppNumbers($person);
            $list[] = [
                'id' => $person->id,
                'full_name' => $person->full_name,
                'whatsapp_numbers' => $numbers,
            ];
        }
        return response()->json(['persons' => $list]);
    }

    public function groupsAttachPerson(Request $request, NotificationGroup $notificationGroup): RedirectResponse
    {
        $validated = $request->validate(['person_id' => 'required|integer|exists:persons,id']);
        $maxOrder = $notificationGroup->persons()->max('notification_group_person.sort_order') ?? 0;
        $notificationGroup->persons()->syncWithoutDetaching([
            $validated['person_id'] => ['sort_order' => $maxOrder + 1],
        ]);
        return back()->with('success', 'تمت إضافة الشخص.');
    }

    public function groupsDetachPerson(NotificationGroup $notificationGroup, Person $person): RedirectResponse
    {
        $notificationGroup->persons()->detach($person->id);
        return back()->with('success', 'تمت إزالة الشخص.');
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
