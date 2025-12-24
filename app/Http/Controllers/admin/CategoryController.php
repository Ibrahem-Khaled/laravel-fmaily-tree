<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\QuickStoreCategoryRequest;
use App\Models\Category;
use App\Models\QuranCategoryManager;
use App\Models\Person;
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

        // إضافة القائمين على البرنامج إن وجدوا
        if ($request->has('managers') && is_array($request->managers)) {
            $sortOrder = 1;
            foreach ($request->managers as $personId) {
                if ($personId) {
                    QuranCategoryManager::create([
                        'category_id' => $category->id,
                        'person_id' => $personId,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

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
        $persons = Person::orderBy('first_name')->orderBy('last_name')->get();
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
            case 'has_children':
                $query->whereHas('children');
                break;
            case 'no_children':
                $query->whereDoesntHave('children');
                break;
            case 'parents':
                $query->parents();
                break;
            case 'children':
                $query->children();
                break;
            case 'active':
                $query->active();
                break;
            case 'inactive':
                $query->inactive();
                break;
            case 'all':
            default:
                // لا شيء
                break;
        }


        // البحث
        $query->search($search);


        // ترتيب افتراضي
        $query->orderBy('sort_order')->orderBy('name');


        $categories = $query->paginate($perPage)->appends($request->query());


        // إحصائيات
        $stats = [
            'total' => Category::count(),
            'parents' => Category::parents()->count(),
            'children' => Category::whereNotNull('parent_id')->count(),
            'has_articles' => Category::hasArticles()->count(),
            'no_articles' => Category::noArticles()->count(),
            'has_images' => Category::hasImages()->count(),
            'no_images' => Category::noImages()->count(),
            'has_children' => Category::whereHas('children')->count(),
            'no_children' => Category::whereDoesntHave('children')->count(),
            'active' => Category::active()->count(),
            'inactive' => Category::inactive()->count(),
        ];


        return view('dashboard.categories.index', compact('categories', 'filter', 'search', 'stats', 'allCategories', 'persons'));
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

    public function toggleActive(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $category->is_active,
            'message' => $category->is_active ? 'تم تفعيل الفئة بنجاح' : 'تم إلغاء تفعيل الفئة بنجاح'
        ]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:categories,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            Category::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الترتيب بنجاح'
        ]);
    }

    /**
     * إضافة قائم على البرنامج للفئة
     */
    public function addManager(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:persons,id',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'person_id.required' => 'يجب اختيار الشخص',
            'person_id.exists' => 'الشخص المختار غير موجود',
        ]);

        // التحقق من عدم تكرار الشخص في نفس الفئة
        $existing = QuranCategoryManager::where('category_id', $category->id)
            ->where('person_id', $validated['person_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الشخص موجود بالفعل في القائمة'
            ], 422);
        }

        $validated['category_id'] = $category->id;
        if (!isset($validated['sort_order'])) {
            $maxOrder = QuranCategoryManager::where('category_id', $category->id)->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        $manager = QuranCategoryManager::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة القائم على البرنامج بنجاح',
            'manager' => [
                'id' => $manager->id,
                'person' => [
                    'id' => $manager->person->id,
                    'name' => $manager->person->full_name,
                    'avatar' => $manager->person->avatar,
                ]
            ]
        ]);
    }

    /**
     * حذف قائم على البرنامج
     */
    public function removeManager(QuranCategoryManager $manager): JsonResponse
    {
        $manager->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف القائم على البرنامج بنجاح'
        ]);
    }

    /**
     * تحديث ترتيب القائمين على البرنامج
     */
    public function updateManagerOrder(Request $request, Category $category): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:quran_category_managers,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            QuranCategoryManager::where('id', $item['id'])
                ->where('category_id', $category->id)
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الترتيب بنجاح'
        ]);
    }
}
