<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Services\StoreApiQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductListApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        /** @var Product $product */
        $product = $this->resource;
        $storeApiQuery = app(StoreApiQuery::class);

        $description = $product->description;
        if ($description !== null && mb_strlen($description) > 200) {
            $description = mb_substr($description, 0, 200).'…';
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $description,
            'price' => $product->price !== null ? number_format((float) $product->price, 2, '.', '') : null,
            'main_image_url' => $storeApiQuery->storageUrl($product->main_image),
            'category' => $this->nestedCategory($product),
            'subcategory' => $this->nestedSubcategory($product),
            'owner' => $this->nestedOwner($product),
        ];
    }

    /**
     * @return array{id: int, name: string}|null
     */
    private function nestedCategory(Product $product): ?array
    {
        if (! $product->relationLoaded('category') || ! $product->category) {
            return null;
        }

        return [
            'id' => $product->category->id,
            'name' => $product->category->name,
        ];
    }

    /**
     * @return array{id: int, name: string}|null
     */
    private function nestedSubcategory(Product $product): ?array
    {
        if (! $product->relationLoaded('subcategory') || ! $product->subcategory) {
            return null;
        }

        return [
            'id' => $product->subcategory->id,
            'name' => $product->subcategory->name,
        ];
    }

    /**
     * @return array{id: int, name: string}|null
     */
    private function nestedOwner(Product $product): ?array
    {
        if (! $product->relationLoaded('owner') || ! $product->owner) {
            return null;
        }

        return [
            'id' => $product->owner->id,
            'name' => $product->owner->full_name,
        ];
    }
}
