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
        // 1. جلب الفئات الرئيسية لعرضها كمجلدات مع أول 4 صور للمعاينة
        $categoriesForView = Category::whereHas('images')
            ->whereNull('parent_id')
            ->with(['images' => function ($query) {
                $query->select('id', 'path', 'article_id')->latest()->take(4);
            }])
            ->withCount('images') // لحساب عدد الصور في كل مجلد
            ->get();

        // 2. جلب نفس الفئات ولكن مع كل الصور والمعلومات المرتبطة بها لتمريرها إلى JavaScript
        $categoriesForJs = Category::whereNull('parent_id')
            ->with([
                'images.article:id,title,person_id,category_id', // اختر الحقول التي تحتاجها فقط لتحسين الأداء
                'images.article.person:id,name',
                'images.article.category:id,name'
            ])
            ->get();

        return view('gallery', [
            'categories' => $categoriesForView, // للاستخدام في عرض المجلدات
            'categoriesWithImages' => $categoriesForJs, // للاستخدام في JavaScript
        ]);
    }

    public function show($id)
    {
        $article = Article::with(['images', 'videos', 'person', 'category'])->findOrFail($id);
        // $badges = [
        //     ['title' => 'مؤرّخ العائلة', 'tier' => 'gold', 'level' => 92, 'graded' => true, 'grade' => 'S', 'desc' => 'جمع وتأريخ الصور القديمة.'],
        //     ['title' => 'منسّق المعارض', 'tier' => 'silver', 'level' => 68, 'graded' => false, 'desc' => 'تنسيق المعارض حسب السنوات.'],
        // ];
        // جلب المقالات ذات الصلة (نفس القسم)
        $relatedArticles = Article::where('category_id', $article->category_id)
            ->where('id', '!=', $id)
            ->with(['images', 'person'])
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
        $categories = Category::whereHas('articles')
            ->orderBy('sort_order', 'asc')
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
