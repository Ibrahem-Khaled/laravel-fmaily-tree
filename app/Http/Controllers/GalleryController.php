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
        // دالة recursive لحساب عدد الصور الكلي (الفئة + الفئات الفرعية)
        $countAllImages = function ($category) use (&$countAllImages) {
            $count = $category->images_count ?? $category->images->count();
            foreach ($category->children as $child) {
                $count += $countAllImages($child);
            }
            return $count;
        };

        // دالة recursive للتحقق من وجود صور في الفئة أو أي من فئاتها الفرعية
        $hasImages = function ($category) use (&$hasImages) {
            // التحقق من وجود صور مباشرة في الفئة
            if (($category->images_count ?? $category->images->count()) > 0) {
                return true;
            }
            // التحقق من وجود صور في الفئات الفرعية
            foreach ($category->children as $child) {
                if ($hasImages($child)) {
                    return true;
                }
            }
            return false;
        };

        // التحقق من تسجيل الدخول
        $isAuthenticated = auth()->check();

        // دالة recursive لتحميل الفئات الفرعية التي تحتوي على صور فقط
        $loadChildrenWithImages = function ($query) use (&$loadChildrenWithImages, $isAuthenticated) {
            // إذا كان المستخدم مسجل دخول، اعرض جميع الفئات (المتاحة وغير المتاحة)
            if ($isAuthenticated) {
                $query->withCount('images')
                      ->with([
                          'images' => function ($q) {
                              $q->select('id', 'path', 'thumbnail_path', 'article_id', 'media_type', 'youtube_url', 'category_id', 'created_at')
                                ->latest()
                                ->take(4);
                          },
                          'children' => $loadChildrenWithImages
                      ])
                      ->orderBy('sort_order');
            } else {
                $query->where('is_active', true)
                      ->withCount('images')
                      ->with([
                          'images' => function ($q) {
                              $q->select('id', 'path', 'thumbnail_path', 'article_id', 'media_type', 'youtube_url', 'category_id', 'created_at')
                                ->latest()
                                ->take(4);
                          },
                          'children' => $loadChildrenWithImages
                      ])
                      ->orderBy('sort_order');
            }
        };

        // 1. جلب الفئات الرئيسية التي تحتوي على صور (مباشرة أو من خلال الفئات الفرعية)
        $categoriesQuery = Category::whereNull('parent_id');
        
        // إذا كان المستخدم غير مسجل دخول، اعرض فقط الفئات المتاحة
        if (!$isAuthenticated) {
            $categoriesQuery->where('is_active', true);
        }
        
        $categories = $categoriesQuery
            ->with([
                'images' => function ($query) {
                    $query->select('id', 'path', 'thumbnail_path', 'article_id', 'media_type', 'youtube_url', 'category_id', 'created_at')
                          ->latest()
                          ->take(4);
                },
                'children' => $loadChildrenWithImages
            ])
            ->withCount('images')
            ->orderBy('sort_order')
            ->orderBy('updated_at', 'desc')
            ->get();

        // تصفية الفئات: إزالة الفئات التي لا تحتوي على صور (لا مباشرة ولا في الفئات الفرعية)
        $categories = $categories->filter(function ($category) use ($hasImages) {
            return $hasImages($category);
        });

        // تصفية الفئات الفرعية أيضاً
        $filterChildren = function ($category) use (&$filterChildren, $hasImages) {
            $category->children = $category->children->filter(function ($child) use (&$filterChildren, $hasImages) {
                if ($hasImages($child)) {
                    $filterChildren($child);
                    return true;
                }
                return false;
            });
        };
        $categories->each($filterChildren);

        // إضافة عدد الصور الكلي لكل فئة
        $categories->each(function ($category) use ($countAllImages) {
            $category->total_images_count = $countAllImages($category);
        });

        // 2. جلب الفئات للـ JavaScript
        // للمستخدمين المسجلين: اعرض جميع الفئات (المتاحة وغير المتاحة) والفئات الفرعية
        // للزوار: اعرض فقط الفئات المتاحة التي تحتوي على صور
        $categoriesForJsQuery = Category::query();
        
        if ($isAuthenticated) {
            // للمستخدمين المسجلين: اعرض جميع الفئات (المتاحة وغير المتاحة) والفئات الفرعية
            // الفئات التي تحتوي على صور أو الفئات الفرعية (حتى لو لم تحتوِ على صور)
            $categoriesForJsQuery->where(function($q) {
                $q->whereHas('images')
                  ->orWhereNotNull('parent_id'); // الفئات الفرعية حتى لو لم تحتوِ على صور
            });
        } else {
            // للزوار: فقط الفئات المتاحة التي تحتوي على صور
            $categoriesForJsQuery->where('is_active', true)
                                ->whereHas('images');
        }
        
        $categoriesForJs = $categoriesForJsQuery
            ->select('id', 'name', 'parent_id', 'is_active', 'sort_order')
            ->with([
                'images' => function ($query) {
                    $query->with(['article:id,title,person_id,category_id', 'article.person:id,name', 'article.category:id,name', 'mentionedPersons'])
                          ->select('id', 'name', 'path', 'thumbnail_path', 'youtube_url', 'media_type', 'file_size', 'file_extension', 'article_id', 'category_id', 'created_at')
                          ->orderBy('created_at', 'desc');
                },
                'parent:id,name',
                'children:id,name,parent_id,is_active'
            ])
            ->orderBy('sort_order')
            ->orderBy('updated_at', 'desc')
            ->get();

        // إحصائيات عامة
        $stats = [
            'total_categories' => Category::where('is_active', true)->count(),
            'total_images' => Image::count(),
            'recent_uploads' => Image::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('gallery', [
            'categories' => $categories,
            'categoriesWithImages' => $categoriesForJs,
            'stats' => $stats,
            'isAuthenticated' => $isAuthenticated,
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
        $selectedYear = $request->get('year', null); // لا يوجد سنة محددة افتراضياً

        // فلترة حسب السنة
        if ($request->has('year') && $request->year != '') {
            $query->whereYear('created_at', $request->year);
            $isFiltered = true;
            $selectedYear = $request->year;
        } else {
            // إذا تم اختيار فئة معينة ولم يتم اختيار سنة، اعرض كل المقالات للفئة
            // إذا لم يتم اختيار فئة، استخدم السلوك الافتراضي (السنة الحالية)
            if (!$request->has('category')) {
                $hasCurrentYearArticles = Article::whereYear('created_at', $currentYear)->exists();
                if ($hasCurrentYearArticles) {
                    $query->whereYear('created_at', $currentYear);
                    $selectedYear = $currentYear;
                }
            }
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

        // جلب السنوات المتاحة حسب الفئة المحددة
        $availableYearsQuery = Article::selectRaw('YEAR(created_at) as year');

        // إذا تم اختيار فئة معينة، اعرض فقط السنوات التي تحتوي على مقالات في هذه الفئة
        if ($request->has('category')) {
            $availableYearsQuery->where('category_id', $request->category);
        }

        $availableYears = $availableYearsQuery
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
        $filterYear = ($request->has('year') && $request->year != '') ? $request->year : null;
        // إذا تم اختيار فئة ولم يتم اختيار سنة، لا نستخدم فلترة السنة في الإحصائيات
        if (!$filterYear && !$request->has('category')) {
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
            ->where('is_active', true)
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
