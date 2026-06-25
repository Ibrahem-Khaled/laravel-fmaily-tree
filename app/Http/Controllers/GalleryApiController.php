<?php

namespace App\Http\Controllers;

use App\Http\Resources\GalleryCategoryApiResource;
use App\Http\Resources\GalleryImageApiResource;
use App\Services\GalleryApiQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Article;
use App\Models\Category;
use App\Models\Person;
use App\Models\Image;

class GalleryApiController extends Controller
{
    public function categories(Request $request, GalleryApiQuery $galleryApiQuery): JsonResponse
    {
        $categories = $galleryApiQuery->categoriesForIndex($request);

        return response()->json([
            'success' => true,
            'data' => GalleryCategoryApiResource::collection($categories)->resolve(),
        ]);
    }

    public function images(Request $request, GalleryApiQuery $galleryApiQuery): JsonResponse
    {
        $categoryId = $request->query('category');
        $deep = $request->boolean('deep');

        $category = null;
        if ($categoryId !== null && $categoryId !== '') {
            $id = (int) $categoryId;
            $category = $galleryApiQuery->resolveCategoryForFilter($request, $id);
            if ($category === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'التصنيف غير موجود أو غير متاح.',
                ], 404);
            }
        }

        $paginator = $galleryApiQuery->paginateImages($request, $category, $deep);

        return response()->json(array_merge(
            [
                'success' => true,
                'data' => GalleryImageApiResource::collection($paginator->items())->resolve(),
            ],
            ['meta' => $this->paginationMeta($paginator)]
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private function paginationMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'path' => $paginator->path(),
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ];
    }

    public function articles(Request $request): JsonResponse
    {
        $query = Article::with(['images', 'person', 'category']);

        // فلترة حسب السنة
        if ($request->has('year') && $request->year != '') {
            $query->whereYear('created_at', $request->year);
        }

        // فلترة حسب القسم
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // فلترة حسب الكاتب
        if ($request->has('author')) {
            $query->where('person_id', $request->author);
        }

        // فلترة حسب نوع الدرجة العلمية
        if ($request->has('degree')) {
            $degree = $request->degree;
            $categoryIds = [];

            if ($degree === 'phd') {
                $categoryIds = Category::where(function($query) {
                    $query->where('name', 'like', '%دكتوراه%')
                          ->orWhere('name', 'like', '%PhD%')
                          ->orWhere('name', 'like', '%Ph.D%');
                })->pluck('id');
            } elseif ($degree === 'master') {
                $categoryIds = Category::where(function($query) {
                    $query->where('name', 'like', '%ماجستير%')
                          ->orWhere('name', 'like', '%Master%');
                })->pluck('id');
            } elseif ($degree === 'bachelor') {
                $categoryIds = Category::where(function($query) {
                    $query->where('name', 'like', '%بكالوريوس%')
                          ->orWhere('name', 'like', '%Bachelor%')
                          ->orWhere('name', 'like', '%Bachelors%')
                          ->orWhere('name', 'like', '%ليسانس%');
                })->pluck('id');
            }

            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // البحث
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // جلب السنوات المتاحة (من كل المقالات)
        $availableYearsQuery = Article::selectRaw('YEAR(created_at) as year');
        if ($request->has('category')) {
            $availableYearsQuery->where('category_id', $request->category);
        }
        $availableYears = $availableYearsQuery
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // تنفيذ الاستعلام مع باجينيشن
        $perPage = $request->get('per_page', 12);
        $paginator = $query->latest()->paginate($perPage);

        // جلب التصنيفات النشطة مع عدد المقالات
        $filterYear = ($request->has('year') && $request->year != '') ? $request->year : null;
        $categoriesQuery = Category::query();
        if ($filterYear) {
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
            ->get()
            ->filter(fn($c) => $c->articles_count > 0)
            ->values()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'articles_count' => $c->articles_count,
            ]);

        // إحصائيات سريعة للـ API
        $totalArticlesQuery = Article::query();
        if ($filterYear) {
            $totalArticlesQuery->whereYear('created_at', $filterYear);
        }
        $totalArticles = $totalArticlesQuery->count();

        $totalAuthorsQuery = Person::query();
        if ($filterYear) {
            $totalAuthorsQuery->whereHas('articles', function($q) use ($filterYear) {
                $q->whereYear('created_at', $filterYear);
            });
        }
        $totalAuthors = $totalAuthorsQuery->count();
        $totalImages = Image::count();

        $articlesData = collect($paginator->items())->map(function($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'excerpt' => mb_substr(strip_tags($article->content), 0, 150) . '...',
                'created_at' => $article->created_at,
                'category' => [
                    'id' => $article->category->id ?? null,
                    'name' => $article->category->name ?? null,
                ],
                'author' => [
                    'id' => $article->person->id ?? null,
                    'name' => $article->person->full_name ?? 'غير معروف',
                    'avatar' => $article->person ? $article->person->avatar : null,
                ],
                'images' => $article->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'path' => asset('storage/' . $image->path),
                        'thumbnail_path' => $image->thumbnail_path ? asset('storage/' . $image->thumbnail_path) : null,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'articles' => $articlesData,
                'filters' => [
                    'available_years' => $availableYears,
                    'categories' => $categories,
                ],
                'stats' => [
                    'total_articles' => $totalArticles,
                    'total_authors' => $totalAuthors,
                    'total_images' => $totalImages,
                ]
            ],
            'meta' => $this->paginationMeta($paginator)
        ]);
    }

    public function showArticle($id): JsonResponse
    {
        $article = Article::with(['images', 'videos', 'person', 'category'])->find($id);

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'المقال غير موجود.',
            ], 404);
        }

        // جلب المقالات ذات الصلة (نفس القسم)
        $relatedArticles = Article::where('category_id', $article->category_id)
            ->where('id', '!=', $id)
            ->with(['images', 'person'])
            ->limit(3)
            ->get()
            ->map(function ($related) {
                return [
                    'id' => $related->id,
                    'title' => $related->title,
                    'created_at' => $related->created_at,
                    'author' => [
                        'name' => $related->person->full_name ?? 'غير معروف',
                        'avatar' => $related->person ? $related->person->avatar : null,
                    ],
                    'image' => $related->images->first() ? asset('storage/' . $related->images->first()->path) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'article' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'content' => $article->content,
                    'created_at' => $article->created_at,
                    'category' => [
                        'id' => $article->category->id ?? null,
                        'name' => $article->category->name ?? null,
                    ],
                    'author' => [
                        'id' => $article->person->id ?? null,
                        'name' => $article->person->full_name ?? 'غير معروف',
                        'avatar' => $article->person ? $article->person->avatar : null,
                    ],
                    'images' => $article->images->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'path' => asset('storage/' . $image->path),
                            'thumbnail_path' => $image->thumbnail_path ? asset('storage/' . $image->thumbnail_path) : null,
                        ];
                    }),
                    'videos' => $article->videos->map(function ($video) {
                        return [
                            'id' => $video->id,
                            'provider' => $video->provider,
                            'video_id' => $video->video_id,
                            'youtube_url' => $video->provider === 'youtube' ? 'https://www.youtube.com/watch?v=' . $video->video_id : null,
                        ];
                    }),
                ],
                'related_articles' => $relatedArticles,
            ]
        ]);
    }
}
