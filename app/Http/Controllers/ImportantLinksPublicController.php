<?php

namespace App\Http\Controllers;

use App\Models\ImportantLink;
use App\Models\User;
use App\Services\ImportantLinkMediaService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ImportantLinksPublicController extends Controller
{
    public function __construct(
        protected ImportantLinkMediaService $mediaService
    ) {}

    /**
     * عرض صفحة الروابط المهمة العامة (تطبيقات ومواقع معتمدة)
     */
    public function index()
    {
        $links = ImportantLink::getApprovedActiveLinks();

        return view('important-links.index', compact('links'));
    }

    /**
     * حفظ اقتراح رابط جديد من الجمهور (مسجّل أو ضيف)
     */
    public function suggest(Request $request)
    {
        $type = $request->input('type', 'app');

        $rules = [
            'title' => 'required|string|max:255',
            'url' => $type === 'website' ? 'required|url|max:500' : 'nullable|url|max:500',
            'url_ios' => 'nullable|url|max:500',
            'url_android' => 'nullable|url|max:500',
            'type' => ['required', Rule::in(['app', 'website'])],
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'media_files' => 'nullable|array',
            'media_files.*' => 'nullable|file|max:51200',
            'media_kinds' => 'nullable|array',
            'media_kinds.*' => ['nullable', Rule::in(['image', 'video'])],
            'media_titles' => 'nullable|array',
            'media_titles.*' => 'nullable|string|max:255',
            'media_descriptions' => 'nullable|array',
            'media_descriptions.*' => 'nullable|string|max:2000',
        ];

        if (auth()->check()) {
            $userId = auth()->id();
        } else {
            $rules['name'] = 'required|string|max:255';
            $rules['phone'] = 'required|string|max:50';
        }

        $validated = $request->validate($rules);

        $this->assertAppHasAtLeastOneUrl($request, $type);

        $submittedByUserId = null;
        if (auth()->check()) {
            $submittedByUserId = auth()->id();
        } else {
            $user = User::firstOrCreate(
                ['phone' => $validated['phone']],
                [
                    'name' => $validated['name'],
                    'email' => null,
                    'password' => null,
                ]
            );
            $submittedByUserId = $user->id;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('important-links', 'public');
        }

        $lastOrder = ImportantLink::max('order') ?? 0;

        $urlIos = $type === 'website' ? null : ($request->filled('url_ios') ? $request->url_ios : null);
        $urlAndroid = $type === 'website' ? null : ($request->filled('url_android') ? $request->url_android : null);

        $link = ImportantLink::create([
            'title' => $validated['title'],
            'url' => $request->filled('url') ? $request->url : '',
            'url_ios' => $urlIos,
            'url_android' => $urlAndroid,
            'type' => $type,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'icon' => $validated['type'] === 'app' ? 'fas fa-mobile-alt' : 'fas fa-globe',
            'submitted_by_user_id' => $submittedByUserId,
            'status' => 'pending',
            'order' => $lastOrder + 1,
            'is_active' => false,
            'open_in_new_tab' => true,
        ]);

        $this->mediaService->attachFromRequest($request, $link);

        return redirect()->route('important-links.index')
            ->with('success', 'تم إرسال اقتراحك بنجاح. سيتم مراجعته من فريق الموقع.');
    }

    protected function assertAppHasAtLeastOneUrl(Request $request, string $type): void
    {
        if ($type !== 'app') {
            return;
        }

        $has = $request->filled('url')
            || $request->filled('url_ios')
            || $request->filled('url_android');

        if (! $has) {
            throw ValidationException::withMessages([
                'url' => ['أدخل رابطاً عاماً أو رابط iOS أو رابط أندرويد.'],
            ]);
        }
    }
}
