<?php

namespace App\Http\Controllers;

use App\Http\Resources\GalleryCategoryApiResource;
use App\Http\Resources\GalleryImageApiResource;
use App\Services\GalleryApiQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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
}
