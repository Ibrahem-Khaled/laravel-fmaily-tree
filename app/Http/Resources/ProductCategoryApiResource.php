<?php

namespace App\Http\Resources;

use App\Models\ProductCategory;
use App\Services\StoreApiQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductCategory
 */
class ProductCategoryApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        /** @var ProductCategory $category */
        $category = $this->resource;
        $storeApiQuery = app(StoreApiQuery::class);

        return [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'image_url' => $storeApiQuery->storageUrl($category->image),
            'products_count' => (int) ($category->products_count ?? $category->getAttribute('products_count') ?? 0),
            'sort_order' => (int) $category->sort_order,
        ];
    }
}
