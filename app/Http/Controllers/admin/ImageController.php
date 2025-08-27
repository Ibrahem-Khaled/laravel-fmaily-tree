<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * عرض صفحة إدارة الصور مع الفلترة والبحث.
     */
    public function index(Request $request)
    {
        // استعلام أساسي للصور مع علاقة القسم
        $query = Image::with('category')->latest();

        // فلترة حسب القسم المحدد
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // بحث بالاسم
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $images = $query->paginate(10);
        $categories = Category::whereHas('images')->get();
        $selectedCategory = $request->category ?? 'all';

        // الإحصائيات
        $imagesCount = Image::count();
        $categoriesCount = $categories->count();
        $mostImagesCategory = Category::withCount('images')->orderBy('images_count', 'desc')->first();

        return view('dashboard.images.index', compact(
            'images',
            'categories',
            'selectedCategory',
            'imagesCount',
            'categoriesCount',
            'mostImagesCategory'
        ));
    }

    /**
     * تخزين صورة جديدة.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'path' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'category_selection' => 'required', // تم تغيير اسم الحقل هنا
        ]);

        $categoryId = null;
        $categorySelection = $request->input('category_selection');

        // التحقق مما إذا كانت القيمة المُدخلة رقماً (ID موجود) أم نصاً (قسم جديد)
        if (is_numeric($categorySelection)) {
            // إذا كانت رقماً، استخدمها مباشرة كـ ID
            $categoryId = $categorySelection;
        } else {
            // إذا كانت نصاً، قم بإنشاء قسم جديد بهذا الاسم
            // firstOrCreate تضمن عدم إنشاء قسم مكرر بنفس الاسم
            $newCategory = Category::firstOrCreate(['name' => $categorySelection]);
            $categoryId = $newCategory->id;
        }

        // رفع الصورة وتخزينها
        $path = $request->file('path')->store('public/images');

        // إنشاء سجل الصورة بالـ category_id الصحيح
        Image::create([
            'name' => $request->name,
            'path' => str_replace('public/', '', $path),
            'category_id' => $categoryId,
        ]);

        return back()->with('success', 'تمت إضافة الصورة بنجاح.');
    }

    /**
     * تحديث بيانات صورة موجودة.
     */
    public function update(Request $request, Image $image)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // اختياري
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->only('name', 'category_id');

        if ($request->hasFile('path')) {
            // حذف الصورة القديمة
            Storage::delete('public/' . $image->path);

            // تخزين الصورة الجديدة
            $path = $request->file('path')->store('public/images');
            $data['path'] = str_replace('public/', '', $path);
        }

        $image->update($data);

        return back()->with('success', 'تم تحديث الصورة بنجاح.');
    }

    /**
     * حذف صورة.
     */
    public function destroy(Image $image)
    {
        // حذف الملف من التخزين
        Storage::delete('public/' . $image->path);

        // حذف السجل من قاعدة البيانات
        $image->delete();

        return back()->with('success', 'تم حذف الصورة بنجاح.');
    }
}
