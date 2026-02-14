<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ImportantLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ImportantLinkController extends Controller
{
    /**
     * عرض صفحة إدارة الروابط المهمة
     */
    public function index()
    {
        $links = ImportantLink::with('submitter')->where('status', 'approved')->orderBy('order')->get();
        $pendingLinks = ImportantLink::with('submitter')->where('status', 'pending')->orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => ImportantLink::count(),
            'active' => ImportantLink::where('is_active', true)->count(),
            'inactive' => ImportantLink::where('is_active', false)->count(),
            'pending' => ImportantLink::where('status', 'pending')->count(),
        ];

        return view('dashboard.important-links.index', compact('links', 'pendingLinks', 'stats'));
    }

    /**
     * إضافة رابط جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'type' => ['nullable', Rule::in(['app', 'website'])],
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'open_in_new_tab' => 'nullable|boolean',
        ]);

        $lastOrder = ImportantLink::max('order') ?? 0;
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('important-links', 'public');
        }

        ImportantLink::create([
            'title' => $request->title,
            'url' => $request->url,
            'type' => $request->type ?? 'website',
            'icon' => $request->icon ?? 'fas fa-link',
            'description' => $request->description,
            'image' => $imagePath,
            'submitted_by_user_id' => $request->submitted_by_user_id ?: null,
            'status' => 'approved',
            'order' => $lastOrder + 1,
            'is_active' => $request->has('is_active'),
            'open_in_new_tab' => $request->has('open_in_new_tab'),
        ]);

        return redirect()->route('dashboard.important-links.index')
            ->with('success', 'تم إضافة الرابط بنجاح');
    }

    /**
     * تحديث رابط
     */
    public function update(Request $request, ImportantLink $importantLink)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'type' => ['nullable', Rule::in(['app', 'website'])],
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => ['nullable', Rule::in(['pending', 'approved'])],
            'open_in_new_tab' => 'nullable|boolean',
        ]);

        $data = [
            'title' => $request->title,
            'url' => $request->url,
            'type' => $request->type ?? 'website',
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

        return redirect()->route('dashboard.important-links.index')
            ->with('success', 'تم تحديث الرابط بنجاح');
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
            'is_active' => !$importantLink->is_active
        ]);

        return redirect()->route('dashboard.important-links.index')
            ->with('success', 'تم تحديث حالة الرابط بنجاح');
    }
}
