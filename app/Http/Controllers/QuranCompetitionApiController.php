<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuranCategoryNavApiResource;
use App\Http\Resources\QuranCompetitionListApiResource;
use App\Http\Resources\QuranCompetitionShowApiResource;
use App\Models\Category;
use App\Models\QuranCompetition;
use App\Services\QuranCompetitionApiQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API مسابقات القرآن والسنة — مرجع تكامل الفرونت / الموبايل.
 *
 * Base URL: /api/quran-competitions (middleware web — credentials: 'include' مع site-password)
 *
 * تدفق الشاشات:
 * 1) GET /api/quran-competitions/navigation     — قائمة الهيدر (فئات + route_type)
 * 2) GET /api/quran-competitions                — كل المسابقات (حالية 3 + سابقة)
 * 3) GET /api/quran-competitions/categories/{id} — مسابقات فئة (+ redirect_to_competition_id)
 * 4) GET /api/quran-competitions/{id}           — تفاصيل مسابقة
 *
 * Query للقائمة: category (category_id), search
 *
 * @see QuranCompetitionPublicController · QuranCategoryController
 */
class QuranCompetitionApiController extends Controller
{
    public function __construct(
        private readonly QuranCompetitionApiQuery $quranCompetitionApiQuery
    ) {}

    /**
     * GET /api/quran-competitions/navigation
     */
    public function navigation(): JsonResponse
    {
        $items = $this->quranCompetitionApiQuery
            ->categoriesForNavigation()
            ->map(fn (Category $category) => $this->quranCompetitionApiQuery->resolveNavigationItem($category));

        return response()->json([
            'success' => true,
            'data' => QuranCategoryNavApiResource::collection($items)->resolve(),
        ]);
    }

    /**
     * GET /api/quran-competitions
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $this->quranCompetitionApiQuery->parseFilters($request);
        $competitions = $this->quranCompetitionApiQuery->listCompetitions($request);
        $split = $this->quranCompetitionApiQuery->splitCurrentAndPrevious($competitions);

        return response()->json([
            'success' => true,
            'filters' => $filters,
            'categories' => $this->quranCompetitionApiQuery->categoriesForFilter()->map(fn (Category $c) => [
                'id' => $c->id,
                'name' => $c->name,
                'description' => $c->description,
                'sort_order' => (int) $c->sort_order,
            ])->values()->all(),
            'current_competitions' => QuranCompetitionListApiResource::collection($split['current'])->resolve(),
            'previous_competitions' => QuranCompetitionListApiResource::collection($split['previous'])->resolve(),
        ]);
    }

    /**
     * GET /api/quran-competitions/categories/{category}
     */
    public function category(Category $category): JsonResponse
    {
        $resolved = $this->quranCompetitionApiQuery->resolveCategory($category->id);

        if ($resolved === null) {
            return response()->json([
                'success' => false,
                'message' => 'التصنيف غير موجود أو غير متاح.',
            ], 404);
        }

        $competitions = $this->quranCompetitionApiQuery->competitionsForCategory($resolved->id);
        $redirectId = $this->quranCompetitionApiQuery->shouldRedirectToSingleCompetition($competitions);
        $split = $this->quranCompetitionApiQuery->splitCurrentAndPrevious($competitions);

        return response()->json([
            'success' => true,
            'redirect_to_competition_id' => $redirectId,
            'category' => [
                'id' => $resolved->id,
                'name' => $resolved->name,
                'description' => $resolved->description,
                'sort_order' => (int) $resolved->sort_order,
            ],
            'current_competitions' => QuranCompetitionListApiResource::collection($split['current'])->resolve(),
            'previous_competitions' => QuranCompetitionListApiResource::collection($split['previous'])->resolve(),
        ]);
    }

    /**
     * GET /api/quran-competitions/{competition}
     */
    public function show(QuranCompetition $competition): JsonResponse
    {
        $resolved = $this->quranCompetitionApiQuery->resolveCompetitionForShow($competition->id);

        if ($resolved === null) {
            return response()->json([
                'success' => false,
                'message' => 'المسابقة غير موجودة أو غير متاحة.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'is_single_in_category' => $this->quranCompetitionApiQuery->isSingleInCategory($resolved),
            'data' => (new QuranCompetitionShowApiResource($resolved))->resolve(),
        ]);
    }
}
