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
                $query->select('id', 'path', 'thumbnail_path', 'article_id', 'media_type', 'youtube_url', 'created_at')->latest()->take(4);
            }])
            ->withCount('images') // لحساب عدد الصور في كل مجلد
            ->orderBy('updated_at', 'desc') // ترتيب حسب آخر تحديث
            ->get();

        // 2. جلب نفس الفئات ولكن مع كل الصور والمعلومات المرتبطة بها لتمريرها إلى JavaScript
        $categoriesForJs = Category::whereNull('parent_id')
            ->with([
                'images' => function ($query) {
                    $query->with(['article:id,title,person_id,category_id', 'article.person:id,name', 'article.category:id,name', 'mentionedPersons'])
                          ->select('id', 'name', 'path', 'thumbnail_path', 'youtube_url', 'media_type', 'file_size', 'file_extension', 'article_id', 'category_id', 'created_at')
                          ->orderBy('created_at', 'desc'); // ترتيب الصور داخل كل فئة حسب التاريخ
                }
            ])
            ->orderBy('updated_at', 'desc') // ترتيب الفئات حسب آخر تحديث
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

        $isFiltered = false;

        // جلب السنوات المتاحة (من أول سنة مقال)
        $firstArticleYear = Article::selectRaw('YEAR(MIN(created_at)) as year')
            ->value('year');
        
        $currentYear = date('Y');
        $selectedYear = $request->get('year', $currentYear); // السنة الحالية افتراضياً

        // فلترة حسب السنة
        if ($request->has('year')) {
            $query->whereYear('created_at', $request->year);
            $isFiltered = true;
        } else {
            // افتراضياً: عرض مقالات السنة الحالية فقط إذا كانت هناك مقالات في السنة الحالية
            $hasCurrentYearArticles = Article::whereYear('created_at', $currentYear)->exists();
            if ($hasCurrentYearArticles) {
                $query->whereYear('created_at', $currentYear);
            }
            // إذا لم تكن هناك مقالات في السنة الحالية، اعرض الكل
        }

        // فلترة حسب القسم
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
            $isFiltered = true;
        }

        // فلترة حسب الكاتب
        if ($request->has('author')) {
            $query->where('person_id', $request->author);
            $isFiltered = true;
        }

        // فلترة حسب نوع الدرجة العلمية
        if ($request->has('degree')) {
            $degree = $request->degree;
            $categoryIds = [];
            
            if ($degree === 'phd') {
                $categoryIds = \App\Models\Category::where(function($query) {
                    $query->where('name', 'like', '%دكتوراه%')
                          ->orWhere('name', 'like', '%PhD%')
                          ->orWhere('name', 'like', '%Ph.D%');
                })->pluck('id');
            } elseif ($degree === 'master') {
                $categoryIds = \App\Models\Category::where(function($query) {
                    $query->where('name', 'like', '%ماجستير%')
                          ->orWhere('name', 'like', '%Master%');
                })->pluck('id');
            } elseif ($degree === 'bachelor') {
                $categoryIds = \App\Models\Category::where(function($query) {
                    $query->where('name', 'like', '%بكالوريوس%')
                          ->orWhere('name', 'like', '%Bachelor%')
                          ->orWhere('name', 'like', '%Bachelors%')
                          ->orWhere('name', 'like', '%ليسانس%');
                })->pluck('id');
            }
            
            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
                $isFiltered = true;
            }
        }

        // البحث
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
            $isFiltered = true;
        }

        // جلب السنوات المتاحة (جميع السنوات التي تحتوي على مقالات)
        $availableYears = Article::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // إذا كان هناك فلترة، اعرض جميع النتائج بدون باجينيشن
        if ($isFiltered) {
            $articles = $query->latest()->get();
        } else {
            $articles = $query->latest()->paginate(12)->withQueryString();
        }

        // تحديد السنة المستخدمة للفلترة
        $filterYear = $request->has('year') ? $request->year : null;
        if (!$filterYear) {
            $hasCurrentYearArticles = Article::whereYear('created_at', $currentYear)->exists();
            if ($hasCurrentYearArticles) {
                $filterYear = $currentYear;
            }
        }
        
        // جلب التصنيفات مع عدد المقالات حسب السنة المحددة
        $categoriesQuery = Category::query();
        
        if ($filterYear) {
            // إضافة فلترة السنة للتصنيفات
            $categoriesQuery->whereHas('articles', function($q) use ($filterYear) {
                $q->whereYear('created_at', $filterYear);
            });
        }
        
        $categories = $categoriesQuery
            ->withCount(['articles' => function($q) use ($filterYear) {
                if ($filterYear) {
                    $q->whereYear('created_at', $filterYear);
                }
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        // جلب الكتّاب الأكثر نشاطاً
        $topAuthors = Person::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->limit(5)
            ->get();

        // الإحصائيات حسب السنة المحددة
        $totalArticlesQuery = Article::query();
        if ($filterYear) {
            $totalArticlesQuery->whereYear('created_at', $filterYear);
        }
        $totalArticles = $totalArticlesQuery->count();
        
        // عدد الكتّاب حسب السنة المحددة
        $totalAuthorsQuery = Person::query();
        if ($filterYear) {
            $totalAuthorsQuery->whereHas('articles', function($q) use ($filterYear) {
                $q->whereYear('created_at', $filterYear);
            });
        }
        $totalAuthors = $totalAuthorsQuery->count();
        
        $totalImages = Image::count(); // عدد الصور يبقى كما هو

        return view('articles', compact(
            'articles',
            'categories',
            'topAuthors',
            'totalArticles',
            'totalAuthors',
            'totalImages',
            'isFiltered',
            'availableYears',
            'selectedYear',
            'currentYear',
            'firstArticleYear'
        ));
    }

    /**
     * عرض معرض صور شخص معين
     */
    public function personGallery(Person $person)
    {
        // جلب الصور المرتبطة بالشخص مع التفاصيل
        $images = $person->mentionedImages()
            ->with(['article:id,title,person_id', 'article.person:id,first_name,last_name'])
            ->orderBy('created_at', 'desc')
            ->get();

        // إذا لم توجد صور، إرجاع صفحة خطأ أو رسالة
        if ($images->isEmpty()) {
            abort(404, 'لا توجد صور مرتبطة بهذا الشخص');
        }

        return view('person-gallery', compact('person', 'images'));
    }
}
