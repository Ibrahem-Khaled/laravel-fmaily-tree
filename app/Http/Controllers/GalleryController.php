<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Image;
use App\Models\Person;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * عرض صفحة معرض الصور مع إمكانية الفلترة.
     */
    public function index(Request $request)
    {
        // الاستعلام الأساسي لجلب الصور مع علاقاتها لتجنب مشكلة N+1
        $query = Image::with(['article.category', 'article.person']);

        // 1. الفلترة بناءً على الفئة (Category)
        $query->when($request->category, function ($q, $categoryId) {
            return $q->whereHas('article', function ($subQ) use ($categoryId) {
                $subQ->where('category_id', $categoryId);
            });
        });

        // 2. الفلترة بناءً على الشخص المساهم (Author)
        $query->when($request->person, function ($q, $personId) {
            return $q->whereHas('article', function ($subQ) use ($personId) {
                $subQ->where('person_id', $personId);
            });
        });

        // جلب الصور مع الترتيب من الأحدث للأقدم + الترقيم (Pagination)
        $images = $query->latest()->paginate(24);

        // جلب الفئات الرئيسية مع الفئات الفرعية التابعة لها
        $categories = Category::whereNull('parent_id')->with('children')->get();

        // جلب الأشخاص الذين لديهم مقالات فقط لعرضهم في قائمة الفلترة
        $authors = Person::whereHas('articles')->get();

        // إرسال كل البيانات إلى الـ View
        return view('gallery', [
            'images' => $images,
            'categories' => $categories,
            'authors' => $authors,
            'currentCategory' => $request->category, // لإظهار الفلتر الحالي
            'currentAuthor' => $request->person,     // لإظهار الفلتر الحالي
        ]);
    }

    public function show($id)
    {
        $article = Article::with(['images', 'person', 'category'])->findOrFail($id);

        // جلب المقالات ذات الصلة (نفس القسم)
        $relatedArticles = Article::where('category_id', $article->category_id)
            ->where('id', '!=', $id)
            ->with('images')
            ->limit(3)
            ->get();

        return view('article', compact('article', 'relatedArticles'));
    }

    public function articles(Request $request)
    {
        $query = Article::with(['images', 'person', 'category']);

        // فلترة حسب القسم
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // فلترة حسب الكاتب
        if ($request->has('author')) {
            $query->where('person_id', $request->author);
        }

        // البحث
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->latest()->paginate(12);

        // جلب التصنيفات مع عدد المقالات
        $categories = Category::withCount('articles')->get();

        // جلب الكتّاب الأكثر نشاطاً
        $topAuthors = Person::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->limit(5)
            ->get();

        // الإحصائيات
        $totalArticles = Article::count();
        $totalAuthors = Person::has('articles')->count();
        $totalImages = Image::count();

        return view('articles', compact(
            'articles',
            'categories',
            'topAuthors',
            'totalArticles',
            'totalAuthors',
            'totalImages'
        ));
    }
}
