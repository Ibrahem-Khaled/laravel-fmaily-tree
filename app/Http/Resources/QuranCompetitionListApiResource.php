<?php

namespace App\Http\Resources;

use App\Models\QuranCompetition;
use App\Services\QuranCompetitionApiQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin QuranCompetition
 */
class QuranCompetitionListApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        /** @var QuranCompetition $competition */
        $competition = $this->resource;
        $query = app(QuranCompetitionApiQuery::class);

        $category = null;
        if ($competition->relationLoaded('category') && $competition->category) {
            $category = [
                'id' => $competition->category->id,
                'name' => $competition->category->name,
            ];
        }

        return [
            'id' => $competition->id,
            'title' => $competition->title,
            'description' => $competition->description,
            'hijri_year' => $competition->hijri_year,
            'start_date' => $competition->start_date?->toDateString(),
            'end_date' => $competition->end_date?->toDateString(),
            'cover_image_url' => $competition->cover_image_url ?? $query->storageUrl($competition->cover_image),
            'category' => $category,
            'winners_count' => $competition->relationLoaded('winners')
                ? $competition->winners->count()
                : 0,
        ];
    }
}
