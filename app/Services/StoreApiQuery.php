<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StoreApiQuery
{
    private const PER_PAGE_DEFAULT = 12;

    private const PER_PAGE_MAX = 50;

    private const SEARCH_MAX_LENGTH = 120;

    /**
     * @return array{category: ?int, subcategory: ?int, person: ?int, search: ?string}
     */
    public function parseFilters(Request $request): array
    {
        $category = $request->query('category');
        $subcategory = $request->query('subcategory');
        $person = $request->query('person');

        return [
            'category' => $category !== null && $category !== '' ? (int) $category : null,
            'subcategory' => $subcategory !== null && $subcategory !== '' ? (int) $subcategory : null,
            'person' => $person !== null && $person !== '' ? (int) $person : null,
            'search' => $this->normalizedSearch($request->query('search')),
        ];
    }

    public function hasAnyFilter(array $filters): bool
    {
        return $filters['category'] !== null
            || $filters['subcategory'] !== null
            || $filters['person'] !== null
            || $filters['search'] !== null;
    }

    public function normalizedPerPage(?int $perPage): int
    {
        if ($perPage === null || $perPage < 1) {
            return self::PER_PAGE_DEFAULT;
        }

        return min($perPage, self::PER_PAGE_MAX);
    }

    public function normalizedSearch(?string $search): ?string
    {
        if ($search === null || $search === '') {
            return null;
        }

        $search = trim($search);

        if ($search === '') {
            return null;
        }

        return mb_substr($search, 0, self::SEARCH_MAX_LENGTH);
    }

    public function baseProductQuery(): Builder
    {
        return Product::query()
            ->with(['category', 'subcategory', 'owner', 'location'])
            ->active()
            ->notRental();
    }

    public function applyFilters(Builder $query, array $filters): Builder
    {
        if ($filters['category'] !== null) {
            $query->where('product_category_id', $filters['category']);
        }

        if ($filters['subcategory'] !== null) {
            $query->where('product_subcategory_id', $filters['subcategory']);
        }

        if ($filters['person'] !== null) {
            $query->where('owner_id', $filters['person']);
        }

        if ($filters['search'] !== null) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function paginateProducts(Request $request, ?array $filters = null): LengthAwarePaginator
    {
        $filters ??= $this->parseFilters($request);
        $perPage = $this->normalizedPerPage(
            $request->query('per_page') !== null ? (int) $request->query('per_page') : null
        );

        if (! $this->hasAnyFilter($filters)) {
            return Product::query()
                ->whereRaw('1 = 0')
                ->paginate($perPage)
                ->withQueryString();
        }

        $query = $this->baseProductQuery();
        $this->applyFilters($query, $filters);

        return $query->ordered()->paginate($perPage)->withQueryString();
    }

    /**
     * @return Collection<int, ProductCategory>
     */
    public function categoriesForIndex(): Collection
    {
        return ProductCategory::active()
            ->whereHas('products', function (Builder $q) {
                $q->active()->notRental();
            })
            ->withCount(['products' => function (Builder $q) {
                $q->active()->notRental();
            }])
            ->ordered()
            ->get();
    }

    /**
     * @return Collection<int, Person>
     */
    public function ownersForIndex(): Collection
    {
        return Person::whereHas('products', function (Builder $q) {
            $q->active()->notRental();
        })
            ->withCount(['products' => function (Builder $q) {
                $q->active()->notRental();
            }])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }

    public function resolveCategory(int $categoryId): ?ProductCategory
    {
        return ProductCategory::query()
            ->whereKey($categoryId)
            ->active()
            ->first();
    }

    public function resolveSubcategory(int $subcategoryId): ?ProductSubcategory
    {
        return ProductSubcategory::query()
            ->whereKey($subcategoryId)
            ->active()
            ->first();
    }

    public function paginateProductsInCategory(ProductCategory $category, Request $request): LengthAwarePaginator
    {
        $perPage = $this->normalizedPerPage(
            $request->query('per_page') !== null ? (int) $request->query('per_page') : null
        );

        return $this->baseProductQuery()
            ->where('product_category_id', $category->id)
            ->ordered()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function paginateProductsInSubcategory(ProductSubcategory $subcategory, Request $request): LengthAwarePaginator
    {
        $perPage = $this->normalizedPerPage(
            $request->query('per_page') !== null ? (int) $request->query('per_page') : null
        );

        return $this->baseProductQuery()
            ->where('product_subcategory_id', $subcategory->id)
            ->ordered()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function resolveProductForShow(int $productId): ?Product
    {
        return Product::query()
            ->with(['category', 'subcategory', 'owner', 'location', 'media'])
            ->whereKey($productId)
            ->active()
            ->notRental()
            ->first();
    }

    /**
     * @return Collection<int, Product>
     */
    public function relatedProducts(Product $product, int $limit = 4): Collection
    {
        return $this->baseProductQuery()
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->ordered()
            ->limit($limit)
            ->get();
    }

    public function storageUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        return asset('storage/'.$path);
    }
}
