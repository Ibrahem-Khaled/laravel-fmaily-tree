<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\Category;
use App\Models\Image;
use App\Models\Person;
use Illuminate\Support\Facades\DB;



class ArticleController extends Controller
{
    public function index(Request $request)
    {
        // الإحصائيات
        $articlesCount = Article::count();
        $categoriesCount = Category::count();

        // لجلب الفئات الرئيسية فقط للتبويبات
        $mainCategories = Category::whereNull('parent_id')->get();
        $selectedCategory = $request->get('category');

        $articlesQuery = Article::with('category', 'images')->latest();

        // فلترة بالبحث
        if ($request->has('search')) {
            $articlesQuery->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        // فلترة بالفئة المختارة من التبويب
        if ($selectedCategory && $selectedCategory !== 'all') {
            $articlesQuery->where('category_id', $selectedCategory);
        }

        $articles = $articlesQuery->paginate(10);

        // لجلب كل الفئات لعرضها في مودال الإنشاء/التعديل
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $persons = Person::all(); // افترض أنك تريد ربط المقال بشخص

        return view('dashboard.articles.index', compact(
            'articles',
            'articlesCount',
            'categoriesCount',
            'mainCategories',
            'selectedCategory',
            'categories',
            'persons'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'person_id' => 'nullable|exists:persons,id',
            'images' => 'nullable|array',
            'images.*.file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*.name' => 'nullable|string|max:255',
        ]);

        $article = Article::create($request->only('title', 'content', 'category_id', 'person_id'));

        if ($request->has('images')) {
            foreach ($request->images as $imageData) {
                if (isset($imageData['file'])) {
                    $file = $imageData['file'];
                    $path = $file->store('articles/' . Str::slug($article->title), 'public');

                    $article->images()->create([
                        'name' => $imageData['name'],
                        'path' => $path,
                    ]);
                }
            }
        }

        return back()->with('success', 'تم إنشاء المقال بنجاح!');
    }

    public function update(Request $request, Article $article)
    {

        // dd($request->all()); // <-- أضف هذا السطر

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'person_id' => 'nullable|exists:persons,id',
            'images' => 'nullable|array',
            // نستخدم 'file' كقاعدة للتحقق من وجود الصورة
            'images.*.file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*.name' => 'nullable|string|max:255',
        ]);

        try {
            // 1. استخدام Transactions لضمان سلامة البيانات
            DB::beginTransaction();

            $article->update($request->only('title', 'content', 'category_id', 'person_id'));

            if ($request->hasFile('images')) {
                // $key هو الفهرس الفريد الذي أنشأناه (Date.now())
                foreach ($request->file('images') as $key => $fileData) {
                    // 2. التحقق من الملف بطريقة Laravel الصحيحة
                    if (isset($fileData['file'])) {
                        $file = $fileData['file'];
                        // 3. تنظيم أفضل لمجلدات التخزين
                        $path = $file->store('articles/' . $article->id, 'public');

                        $imageName = $request->input("images.{$key}.name", null); // الحصول على الاسم الاختياري

                        $article->images()->create([
                            'name' => $imageName,
                            'path' => $path,
                        ]);
                    }
                }
            }

            DB::commit(); // تم كل شيء بنجاح، قم بتثبيت التغييرات

            return back()->with('success', 'تم تحديث المقال بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack(); // حدث خطأ، تراجع عن كل التغييرات

            // يمكنك تسجيل الخطأ للمراجعة لاحقاً
            // Log::error($e->getMessage());

            return back()->with('error', 'حدث خطأ غير متوقع أثناء تحديث المقال.');
        }
    }
    public function destroy(Article $article)
    {
        // حذف الصور من الـ storage
        foreach ($article->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $article->delete(); // الحذف من قاعدة البيانات سيحذف الصور المرتبطة بسبب cascade

        return back()->with('success', 'تم حذف المقال بنجاح!');
    }


    public function deleteImage($id)
    {
        $image = Image::findOrFail($id);

        // حذف الصورة من التخزين
        if (Storage::exists($image->path)) {
            Storage::delete($image->path);
        }

        // حذف السجل من قاعدة البيانات
        $image->delete();

        return response()->json(['success' => true]);
    }
}
