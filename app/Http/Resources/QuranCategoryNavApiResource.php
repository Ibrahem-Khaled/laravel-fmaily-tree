<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuranCategoryNavApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'category_id' => $this->resource['category_id'],
            'name' => $this->resource['name'],
            'route_type' => $this->resource['route_type'],
            'competition_id' => $this->resource['competition_id'],
            'competitions_count' => $this->resource['competitions_count'],
        ];
    }
}
