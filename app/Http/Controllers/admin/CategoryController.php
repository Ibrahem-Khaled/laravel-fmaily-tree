<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // استيراد DB facade

class CategoryController extends Controller
{
    // دالة مخصصة لاستقبال طلبات إنشاء الفئات عبر AJAX
    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الفئة بنجاح!',
            'category' => $category,
        ]);
    }

    public function index(Request $request)
    {
        $query = Category::with('parent');

        if (!$request->has('search') && !$request->has('parent_id')) {
            $query->orderBy('sort_order', 'asc');
        }

        // فلترة حسب القسم الرئيسي
        $selectedParent = 'all';
        if ($request->has('parent_id') && $request->parent_id != 'all') {
            $query->where('parent_id', $request->parent_id);
            $selectedParent = $request->parent_id;
        }

        // بحث
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(10);

        // بيانات الإحصائيات
        $stats = [
            'total' => Category::count(),
            'main' => Category::whereNull('parent_id')->count(),
            'sub' => Category::whereNotNull('parent_id')->count(),
        ];

        $mainCategories = Category::whereNull('parent_id')->get(); // لجلب الأقسام الرئيسية للتبويبات

        return view('dashboard.categories.index', compact('categories', 'stats', 'mainCategories', 'selectedParent'));
    }

    /**
     * تخزين قسم جديد.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validatedData);

        return redirect()->route('categories.index')->with('success', 'تمت إضافة القسم بنجاح.');
    }

    /**
     * تحديث بيانات قسم معين.
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validatedData['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validatedData);

        return redirect()->route('categories.index')->with('success', 'تم تحديث القسم بنجاح.');
    }

    /**
     * حذف قسم.
     */
    public function destroy(Category $category)
    {
        // حذف الصورة المرتبطة بالقسم
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'تم حذف القسم بنجاح.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'categoryIds' => 'required|array',
            'categoryIds.*' => 'integer|exists:categories,id',
        ]);

        // استخدام Transaction لضمان تنفيذ كل التحديثات معًا
        DB::transaction(function () use ($request) {
            foreach ($request->categoryIds as $index => $categoryId) {
                Category::where('id', $categoryId)->update(['sort_order' => $index]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث ترتيب الأقسام بنجاح.'
        ]);
    }
}
