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
        // الخطوة 1: الاستعلام الأساسي مع تحميل العلاقات المطلوبة للعرض
        // - 'category': العلاقة المباشرة لعرض اسم القسم
        // - 'article.person': العلاقة المتداخلة لعرض اسم المؤلف
        $query = Image::with(['category', 'article.person']);

        // الخطوة 2: الفلترة بناءً على القسم (Category) مباشرة
        // الآن نستخدم "where" بسيط لأن العلاقة مباشرة (Image->Category)
        $query->when($request->filled('category'), function ($q) use ($request) {
            return $q->where('category_id', $request->category);
        });

        // الخطوة 3: الفلترة بناءً على الشخص المساهم (Author) لا تزال عبر المقال
        // هذا الجزء يبقى كما هو لأن العلاقة غير مباشرة (Image->Article->Person)
        $query->when($request->filled('person'), function ($q) use ($request) {
            return $q->whereHas('article', function ($subQ) use ($request) {
                $subQ->where('person_id', $request->person);
            });
        });

        // جلب النتائج النهائية مع الترتيب والترقيم
        $images = $query->latest()->paginate(24)->withQueryString();

        // جلب البيانات اللازمة لقوائم الفلترة في الواجهة
        $categories = Category::whereNull('parent_id')->with('children')->whereHas('images')->get();
        $authors = Person::whereHas('articles')->get();

        // إرسال البيانات إلى الـ View
        return view('gallery', [
            'images' => $images,
            'categories' => $categories,
            'authors' => $authors,
            'currentCategory' => $request->category, // << أضف هذا السطر مجدداً
            'currentAuthor' => $request->person,     // << وأضف هذا السطر أيضاً
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
        $categories = Category::withCount('articles')
            ->whereHas('articles')
            ->orderBy('sort_order', 'desc')
            ->get();

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
