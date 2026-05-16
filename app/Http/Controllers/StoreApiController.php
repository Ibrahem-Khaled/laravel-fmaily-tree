<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCategoryApiResource;
use App\Http\Resources\ProductListApiResource;
use App\Http\Resources\ProductOwnerApiResource;
use App\Http\Resources\ProductShowApiResource;
use App\Http\Resources\ProductSubcategoryApiResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use App\Services\StoreApiQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * API متجر الأسر المنتجة — مرجع تكامل الفرونت / الموبايل.
 *
 * Base URL: /api/store (مع middleware web — استخدم credentials: 'include' إن وُجد site-password)
 *
 * تدفق الشاشات:
 * 1) GET  /api/store              — شاشة البداية: categories + owners (منتجات فارغة بدون فلاتر)
 * 2) GET  /api/store/products?category=1&page=1  — قائمة منتجات بعد اختيار فلتر
 * 3) GET  /api/store/products/{id} — تفاصيل منتج + related_products (max 4)
 *
 * فلاتر المنتجات (query): category, subcategory, person, search, page, per_page (افتراضي 12، أقصى 50)
 * بدون أي فلتر: data فارغة و total=0 (مطابق /store على الويب)
 *
 * @see ProductStoreController مسارات الويب المكافئة
 */
class StoreApiController extends Controller
{
    public function __construct(
        private readonly StoreApiQuery $storeApiQuery
    ) {}

    /**
     * GET /api/store — يعادل GET /store
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $this->storeApiQuery->parseFilters($request);
        $paginator = $this->storeApiQuery->paginateProducts($request, $filters);

        return response()->json([
            'success' => true,
            'filters' => $filters,
            'categories' => ProductCategoryApiResource::collection(
                $this->storeApiQuery->categoriesForIndex()
            )->resolve(),
            'owners' => ProductOwnerApiResource::collection(
                $this->storeApiQuery->ownersForIndex()
            )->resolve(),
            'products' => $this->productsPayload($paginator),
        ]);
    }

    /**
     * GET /api/store/products — يعادل GET /store?category=1&...
     */
    public function products(Request $request): JsonResponse
    {
        $filters = $this->storeApiQuery->parseFilters($request);
        $paginator = $this->storeApiQuery->paginateProducts($request, $filters);

        return response()->json(array_merge(
            [
                'success' => true,
                'filters' => $filters,
                'data' => ProductListApiResource::collection($paginator->items())->resolve(),
            ],
            ['meta' => $this->paginationMeta($paginator)]
        ));
    }

    /**
     * GET /api/store/products/{product} — يعادل GET /store/{id}
     */
    public function show(Product $product): JsonResponse
    {
        $resolved = $this->storeApiQuery->resolveProductForShow($product->id);

        if ($resolved === null) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير موجود أو غير متاح.',
            ], 404);
        }

        $related = $this->storeApiQuery->relatedProducts($resolved);

        return response()->json([
            'success' => true,
            'data' => (new ProductShowApiResource($resolved))->resolve(),
            'related_products' => ProductListApiResource::collection($related)->resolve(),
        ]);
    }

    /**
     * GET /api/store/categories/{category} — يعادل GET /store/category/{id}
     */
    public function category(Request $request, ProductCategory $category): JsonResponse
    {
        $resolved = $this->storeApiQuery->resolveCategory($category->id);

        if ($resolved === null) {
            return response()->json([
                'success' => false,
                'message' => 'التصنيف غير موجود أو غير متاح.',
            ], 404);
        }

        $resolved->load(['subcategories' => function ($q) {
            $q->active()->ordered();
        }]);

        $paginator = $this->storeApiQuery->paginateProductsInCategory($resolved, $request);

        return response()->json([
            'success' => true,
            'category' => (new ProductCategoryApiResource($resolved))->resolve(),
            'subcategories' => ProductSubcategoryApiResource::collection($resolved->subcategories)->resolve(),
            'products' => $this->productsPayload($paginator),
        ]);
    }

    /**
     * GET /api/store/subcategories/{subcategory} — يعادل GET /store/subcategory/{id}
     */
    public function subcategory(Request $request, ProductSubcategory $subcategory): JsonResponse
    {
        $resolved = $this->storeApiQuery->resolveSubcategory($subcategory->id);

        if ($resolved === null) {
            return response()->json([
                'success' => false,
                'message' => 'التصنيف الفرعي غير موجود أو غير متاح.',
            ], 404);
        }

        $resolved->load('category');

        $paginator = $this->storeApiQuery->paginateProductsInSubcategory($resolved, $request);

        return response()->json([
            'success' => true,
            'subcategory' => (new ProductSubcategoryApiResource($resolved))->resolve(),
            'products' => $this->productsPayload($paginator),
        ]);
    }

    /**
     * @return array{data: array<int, mixed>, meta: array<string, mixed>}
     */
    private function productsPayload(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => ProductListApiResource::collection($paginator->items())->resolve(),
            'meta' => $this->paginationMeta($paginator),
        ];
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
