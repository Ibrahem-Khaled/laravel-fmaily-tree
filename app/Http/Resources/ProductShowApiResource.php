<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\ProductMedia;
use App\Services\StoreApiQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductShowApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        /** @var Product $product */
        $product = $this->resource;
        $storeApiQuery = app(StoreApiQuery::class);

        $price = $product->price !== null ? number_format((float) $product->price, 2, '.', '') : null;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'features' => $product->features ?? [],
            'price' => $price,
            'price_formatted' => $price !== null ? $price.' ر.س' : null,
            'main_image_url' => $storeApiQuery->storageUrl($product->main_image),
            'contact' => [
                'phone' => $product->contact_phone,
                'whatsapp' => $product->contact_whatsapp,
                'email' => $product->contact_email,
                'instagram' => $product->contact_instagram,
                'facebook' => $product->contact_facebook,
                'website_url' => $product->website_url,
                'location_url' => $product->location_url,
            ],
            'category' => $product->relationLoaded('category') && $product->category
                ? ['id' => $product->category->id, 'name' => $product->category->name]
                : null,
            'subcategory' => $product->relationLoaded('subcategory') && $product->subcategory
                ? ['id' => $product->subcategory->id, 'name' => $product->subcategory->name]
                : null,
            'owner' => $product->relationLoaded('owner') && $product->owner
                ? ['id' => $product->owner->id, 'name' => $product->owner->full_name]
                : null,
            'location' => $product->relationLoaded('location') && $product->location
                ? ['id' => $product->location->id, 'name' => $product->location->name]
                : null,
            'media' => $product->relationLoaded('media')
                ? $product->media->map(fn (ProductMedia $media) => $this->formatMedia($media))->values()->all()
                : [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatMedia(ProductMedia $media): array
    {
        $thumbnail = null;
        if ($media->media_type === 'youtube') {
            $thumbnail = $media->youtube_thumbnail;
        }

        return [
            'id' => $media->id,
            'media_type' => $media->media_type,
            'url' => $media->url,
            'thumbnail_url' => $thumbnail,
        ];
    }
}
