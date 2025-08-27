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

        // ابدأ الاستعلام الأساسي مع تحميل العلاقة لتجنب مشكلة N+1
        $query = Category::with('parent');

        // **الجديد: الفلترة بناءً على نوع المحتوى (صور أو مقالات)**
        // نستخدم when لتطبيق الفلتر فقط في حال وجود باراميتر 'type'
        $query->when($request->input('type'), function ($q, $type) {
            if ($type === 'images') {
                // جلب الأقسام التي تحتوي على صورة واحدة على الأقل
                return $q->whereHas('images');
            }
            if ($type === 'articles') {
                // جلب الأقسام التي تحتوي على مقال واحد على الأقل
                return $q->whereHas('articles');
            }
        });

        // ترتيب افتراضي في حالة عدم وجود بحث أو فلترة
        if (!$request->has('search') && !$request->has('parent_id')) {
            $query->orderBy('sort_order', 'asc');
        }

        // فلترة حسب القسم الرئيسي (تبقى كما هي)
        $selectedParent = 'all';
        if ($request->has('parent_id') && $request->parent_id != 'all') {
            $query->where('parent_id', $request->parent_id);
            $selectedParent = $request->parent_id;
        }

        // **تحسين البحث: وضع شروط البحث داخل مجموعة لضمان عدم تعارضها مع الفلاتر الأخرى**
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // **تحديث الترقيم: إضافة withQueryString للحفاظ على كل الفلاتر عند التنقل بين الصفحات**
        $categories = $query->paginate(10)->withQueryString();

        // **تحديث الإحصائيات: لتعكس الفلاتر المطبقة**
        // سنبني استعلاماً جديداً للإحصائيات يطابق الفلترة الأساسية
        $statsQuery = Category::query();
        $statsQuery->when($request->input('type'), function ($q, $type) {
            if ($type === 'images') {
                return $q->whereHas('images');
            }
            if ($type === 'articles') {
                return $q->whereHas('articles');
            }
        });

        $stats = [
            'total' => $statsQuery->clone()->count(), // نستخدم clone() لأن count() تنهي الاستعلام
            'main'  => $statsQuery->clone()->whereNull('parent_id')->count(),
            'sub'   => $statsQuery->clone()->whereNotNull('parent_id')->count(),
        ];

        // هذا الجزء لجلب كل الأقسام الرئيسية لعرضها في قائمة الفلترة (يبقى كما هو)
        $mainCategories = Category::whereNull('parent_id')->get();

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
