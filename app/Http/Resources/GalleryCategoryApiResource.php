<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
class GalleryCategoryApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        /** @var Category $cat */
        $cat = $this->resource;

        return [
            'id' => $cat->id,
            'name' => $cat->name,
            'description' => $cat->description,
            'parent_id' => $cat->parent_id,
            'sort_order' => (int) $cat->sort_order,
            'is_active' => (bool) $cat->is_active,
            'total_images_count' => (int) ($cat->getAttribute('total_images_count') ?? 0),
        ];
    }
}
