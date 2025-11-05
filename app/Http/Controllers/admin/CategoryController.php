<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\QuickStoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    // دالة مخصصة لاستقبال طلبات إنشاء الفئات عبر AJAX
    public function store(QuickStoreCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();

        // رفع صورة اختيارية
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create($data);

        // نُرجع الفئة الجديدة كي نضيفها للقائمة (حتى لو لا تملك مقالات بعد)
        return response()->json([
            'ok' => true,
            'category' => [
                'id'          => $category->id,
                'name'        => $category->name,
                'description' => $category->description,
                'image'       => $category->image ? asset('storage/' . $category->image) : null,
            ]
        ]);
    }
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 15);
        $allCategories = Category::all();

        $query = Category::query()
            ->with(['parent'])
            ->withCount(['articles', 'images', 'children']);


        // تطبيق الفلاتر
        switch ($filter) {
            case 'has_articles':
                $query->hasArticles();
                break;
            case 'no_articles':
                $query->noArticles();
                break;
            case 'has_images':
                $query->hasImages();
                break;
            case 'no_images':
                $query->noImages();
                break;
            case 'parents':
                $query->parents();
                break;
            case 'children':
                $query->children();
                break;
            case 'all':
            default:
                // لا شيء
                break;
        }


        // البحث
        $query->search($search);


        // ترتيب افتراضي
        $query->orderByDesc('sort_order')->orderBy('name');


        $categories = $query->paginate($perPage)->appends($request->query());


        // إحصائيات
        $stats = [
            'total' => Category::count(),
            'parents' => Category::parents()->count(),
            'children' => 0,
            'has_articles' => Category::hasArticles()->count(),
            'no_articles' => Category::noArticles()->count(),
            'has_images' => Category::hasImages()->count(),
            'no_images' => Category::noImages()->count(),
        ];


        return view('dashboard.categories.index', compact('categories', 'filter', 'search', 'stats', 'allCategories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();


        // معالجة الصورة (اختياري):
        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = $path;
        }


        // منع parent_id أن يكون نفسه
        if (isset($data['parent_id']) && (int)$data['parent_id'] === (int)$category->id) {
            unset($data['parent_id']);
        }


        $category->update($data);


        return back()->with('success', 'تم تحديث التصنيف بنجاح.');
    }

    public function destroy(Category $category)
    {
        // سياسات الحذف: نمنع الحذف إن كان لديه أبناء أو مقالات/صور
        if ($category->children()->exists()) {
            return back()->with('error', 'لا يمكن حذف التصنيف لوجود تصنيفات فرعية مرتبطة به.');
        }
        if ($category->articles()->exists() || $category->images()->exists()) {
            return back()->with('error', 'لا يمكن حذف التصنيف لوجود مقالات/صور مرتبطة به.');
        }


        // حذف الصورة إن وجدت
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }


        $category->delete();


        return back()->with('success', 'تم حذف التصنيف بنجاح.');
    }

    public function deleteEmpty()
    {
        // البحث عن الأصناف التي لا تحتوي على مقالات أو صور أو أصناف فرعية
        $emptyCategories = Category::whereDoesntHave('articles')
            ->whereDoesntHave('images')
            ->whereDoesntHave('children')
            ->get();

        $deletedCount = 0;

        foreach ($emptyCategories as $category) {
            // حذف الصورة إن وجدت
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $category->delete();
            $deletedCount++;
        }

        return response()->json([
            'success' => true,
            'deleted_count' => $deletedCount,
            'message' => $deletedCount > 0 
                ? "تم حذف {$deletedCount} صنف بدون علاقات بنجاح." 
                : "لا توجد أصناف بدون علاقات للحذف."
        ]);
    }
}
