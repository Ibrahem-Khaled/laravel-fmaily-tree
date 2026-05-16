<?php

namespace App\Http\Resources;

use App\Models\ProductSubcategory;
use App\Services\StoreApiQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductSubcategory
 */
class ProductSubcategoryApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        /** @var ProductSubcategory $subcategory */
        $subcategory = $this->resource;
        $storeApiQuery = app(StoreApiQuery::class);

        $category = null;
        if ($subcategory->relationLoaded('category') && $subcategory->category) {
            $category = [
                'id' => $subcategory->category->id,
                'name' => $subcategory->category->name,
            ];
        }

        return [
            'id' => $subcategory->id,
            'name' => $subcategory->name,
            'description' => $subcategory->description,
            'image_url' => $storeApiQuery->storageUrl($subcategory->image),
            'product_category_id' => $subcategory->product_category_id,
            'category' => $category,
            'sort_order' => (int) $subcategory->sort_order,
        ];
    }
}
