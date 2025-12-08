<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ImportantLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportantLinkController extends Controller
{
    /**
     * عرض صفحة إدارة الروابط المهمة
     */
    public function index()
    {
        $links = ImportantLink::orderBy('order')->get();

        // إحصائيات
        $stats = [
            'total' => ImportantLink::count(),
            'active' => ImportantLink::where('is_active', true)->count(),
            'inactive' => ImportantLink::where('is_active', false)->count(),
        ];

        return view('dashboard.important-links.index', compact('links', 'stats'));
    }

    /**
     * إضافة رابط جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'open_in_new_tab' => 'nullable|boolean',
        ]);

        // الحصول على آخر ترتيب
        $lastOrder = ImportantLink::max('order') ?? 0;

        ImportantLink::create([
            'title' => $request->title,
            'url' => $request->url,
            'icon' => $request->icon ?? 'fas fa-link',
            'description' => $request->description,
            'order' => $lastOrder + 1,
            'is_active' => $request->has('is_active') ? true : false,
            'open_in_new_tab' => $request->has('open_in_new_tab') ? true : false,
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
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'open_in_new_tab' => 'nullable|boolean',
        ]);

        $importantLink->update([
            'title' => $request->title,
            'url' => $request->url,
            'icon' => $request->icon ?? 'fas fa-link',
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
            'open_in_new_tab' => $request->has('open_in_new_tab') ? true : false,
        ]);

        return redirect()->route('dashboard.important-links.index')
            ->with('success', 'تم تحديث الرابط بنجاح');
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
