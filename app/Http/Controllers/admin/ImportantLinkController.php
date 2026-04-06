<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ImportantLink;
use App\Services\ImportantLinkMediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ImportantLinkController extends Controller
{
    public function __construct(
        protected ImportantLinkMediaService $mediaService
    ) {}

    /**
     * عرض صفحة إدارة الروابط المهمة
     */
    public function index()
    {
        $links = ImportantLink::with(['submitter', 'media'])->where('status', 'approved')->orderBy('order')->get();
        $pendingLinks = ImportantLink::with(['submitter', 'media'])->where('status', 'pending')->orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => ImportantLink::count(),
            'active' => ImportantLink::where('is_active', true)->count(),
            'inactive' => ImportantLink::where('is_active', false)->count(),
            'pending' => ImportantLink::where('status', 'pending')->count(),
        ];

        return view('dashboard.important-links.index', compact('links', 'pendingLinks', 'stats'));
    }

    /**
     * نموذج إضافة رابط (صفحة كاملة)
     */
    public function create()
    {
        return view('dashboard.important-links.create');
    }

    /**
     * نموذج تعديل رابط مع معاينة الوسائط (صفحة كاملة)
     */
    public function edit(ImportantLink $importantLink)
    {
        $importantLink->load(['media', 'submitter']);

        return view('dashboard.important-links.edit', compact('importantLink'));
    }

    /**
     * إضافة رابط جديد
     */
    public function store(Request $request)
    {
        $type = $request->input('type', 'website');

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => $type === 'website' ? 'required|url|max:500' : 'nullable|url|max:500',
            'url_ios' => 'nullable|url|max:500',
            'url_android' => 'nullable|url|max:500',
            'type' => ['nullable', Rule::in(['app', 'website'])],
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'open_in_new_tab' => 'nullable|boolean',
            'media_files' => 'nullable|array',
            'media_files.*' => 'nullable|file|max:51200',
            'media_kinds' => 'nullable|array',
            'media_kinds.*' => ['nullable', Rule::in(['image', 'video'])],
            'media_titles' => 'nullable|array',
            'media_titles.*' => 'nullable|string|max:255',
            'media_descriptions' => 'nullable|array',
            'media_descriptions.*' => 'nullable|string|max:2000',
        ]);

        $this->assertAppHasAtLeastOneUrl($request, $type);

        $lastOrder = ImportantLink::max('order') ?? 0;
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('important-links', 'public');
        }

        $urlIos = $type === 'website' ? null : ($request->filled('url_ios') ? $request->url_ios : null);
        $urlAndroid = $type === 'website' ? null : ($request->filled('url_android') ? $request->url_android : null);

        $link = ImportantLink::create([
            'title' => $request->title,
            'url' => $request->filled('url') ? $request->url : '',
            'url_ios' => $urlIos,
            'url_android' => $urlAndroid,
            'type' => $type,
            'icon' => $request->icon ?? 'fas fa-link',
            'description' => $request->description,
            'image' => $imagePath,
            'submitted_by_user_id' => $request->submitted_by_user_id ?: null,
            'status' => 'approved',
            'order' => $lastOrder + 1,
            'is_active' => $request->has('is_active'),
            'open_in_new_tab' => $request->has('open_in_new_tab'),
        ]);

        $this->mediaService->attachFromRequest($request, $link);

        return redirect()->route('dashboard.important-links.index')
            ->with('success', 'تم إضافة الرابط بنجاح');
    }

    /**
     * تحديث رابط
     */
    public function update(Request $request, ImportantLink $importantLink)
    {
        $importantLink->load('media');

        $type = $request->input('type', $importantLink->type ?? 'website');

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => $type === 'website' ? 'required|url|max:500' : 'nullable|url|max:500',
            'url_ios' => 'nullable|url|max:500',
            'url_android' => 'nullable|url|max:500',
            'type' => ['nullable', Rule::in(['app', 'website'])],
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => ['nullable', Rule::in(['pending', 'approved'])],
            'open_in_new_tab' => 'nullable|boolean',
            'delete_media_ids' => 'nullable|array',
            'delete_media_ids.*' => 'integer|exists:important_link_media,id',
            'media_files' => 'nullable|array',
            'media_files.*' => 'nullable|file|max:51200',
            'media_kinds' => 'nullable|array',
            'media_kinds.*' => ['nullable', Rule::in(['image', 'video'])],
            'media_titles' => 'nullable|array',
            'media_titles.*' => 'nullable|string|max:255',
            'media_descriptions' => 'nullable|array',
            'media_descriptions.*' => 'nullable|string|max:2000',
        ]);

        $this->assertAppHasAtLeastOneUrl($request, $type);

        $deleteIds = $request->input('delete_media_ids', []);
        if (is_array($deleteIds) && $deleteIds !== []) {
            $ownedIds = $importantLink->media->pluck('id')->all();
            $deleteIds = array_values(array_intersect(array_map('intval', $deleteIds), $ownedIds));
            $this->mediaService->deleteMediaByIds($importantLink, $deleteIds);
        }

        $urlIos = $type === 'website' ? null : ($request->filled('url_ios') ? $request->url_ios : null);
        $urlAndroid = $type === 'website' ? null : ($request->filled('url_android') ? $request->url_android : null);

        $data = [
            'title' => $request->title,
            'url' => $request->filled('url') ? $request->url : '',
            'url_ios' => $urlIos,
            'url_android' => $urlAndroid,
            'type' => $type,
            'icon' => $request->icon ?? 'fas fa-link',
            'description' => $request->description,
            'status' => $request->status ?? $importantLink->status,
            'is_active' => $request->has('is_active'),
            'open_in_new_tab' => $request->has('open_in_new_tab'),
        ];

        if ($request->hasFile('image')) {
            if ($importantLink->image) {
                Storage::disk('public')->delete($importantLink->image);
            }
            $data['image'] = $request->file('image')->store('important-links', 'public');
        }

        $importantLink->update($data);

        $this->mediaService->attachFromRequest($request, $importantLink);

        return redirect()->route('dashboard.important-links.index')
            ->with('success', 'تم تحديث الرابط بنجاح');
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

    /**
     * اعتماد اقتراح رابط
     */
    public function approve(ImportantLink $importantLink)
    {
        $importantLink->update([
            'status' => 'approved',
            'is_active' => true,
        ]);

        return redirect()->route('dashboard.important-links.index')
            ->with('success', 'تم اعتماد الرابط بنجاح');
    }

    /**
     * رفض اقتراح رابط (حذف)
     */
    public function reject(ImportantLink $importantLink)
    {
        $importantLink->delete();

        return redirect()->route('dashboard.important-links.index')
            ->with('success', 'تم رفض الاقتراح');
    }

    /**
     * إعادة ترتيب الروابط
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:important_links,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $linkId) {
                ImportantLink::where('id', $linkId)
                    ->update(['order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }

    /**
     * حذف رابط
     */
    public function destroy(ImportantLink $importantLink)
    {
        $importantLink->delete();

        return redirect()->route('dashboard.important-links.index')
            ->with('success', 'تم حذف الرابط بنجاح');
    }

    /**
     * تفعيل/تعطيل رابط
     */
    public function toggle(ImportantLink $importantLink)
    {
        $importantLink->update([
            'is_active' => ! $importantLink->is_active,
        ]);

        return redirect()->route('dashboard.important-links.index')
            ->with('success', 'تم تحديث حالة الرابط بنجاح');
    }
}
