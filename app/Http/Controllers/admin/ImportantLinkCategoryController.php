<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ImportantLinkCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ImportantLinkCategoryController extends Controller
{
    /**
     * عرض قائمة فئات الروابط المهمة
     */
    public function index(Request $request)
    {
        $categories = ImportantLinkCategory::withCount('links')
            ->orderBy('sort_order')
            ->get();

        return view('dashboard.important-links.categories', compact('categories'));
    }

    /**
     * حفظ فئة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ImportantLinkCategory::create($validated);

        return redirect()->route('dashboard.important-links.categories.index')
            ->with('success', 'تم إضافة الفئة بنجاح');
    }

    /**
     * تحديث فئة موجودة
     */
    public function update(Request $request, ImportantLinkCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('dashboard.important-links.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    /**
     * حذف فئة
     */
    public function destroy(ImportantLinkCategory $category)
    {
        // عند الحذف، روابط هذه الفئة ستصبح category_id = null (بدون فئة) بفضل nullOnDelete في قاعدة البيانات
        $category->delete();

        return redirect()->route('dashboard.important-links.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح، وتحويل روابطها لتصبح بدون فئة');
    }

    /**
     * تفعيل أو تعطيل الفئة
     */
    public function toggle(ImportantLinkCategory $category): JsonResponse
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $category->is_active,
            'message' => $category->is_active ? 'تم تفعيل الفئة بنجاح' : 'تم إلغاء تفعيل الفئة بنجاح'
        ]);
    }

    /**
     * إعادة ترتيب الفئات
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:important_link_categories,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            ImportantLinkCategory::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث ترتيب الفئات بنجاح'
        ]);
    }
}
