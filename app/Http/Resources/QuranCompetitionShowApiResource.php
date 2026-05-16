<?php

namespace App\Http\Resources;

use App\Models\Person;
use App\Models\QuranCategoryManager;
use App\Models\QuranCompetition;
use App\Models\QuranCompetitionMedia;
use App\Models\QuranCompetitionSection;
use App\Models\QuranCompetitionWinner;
use App\Services\QuranCompetitionApiQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin QuranCompetition
 */
class QuranCompetitionShowApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        /** @var QuranCompetition $competition */
        $competition = $this->resource;
        $query = app(QuranCompetitionApiQuery::class);

        $category = null;
        $managers = [];

        if ($competition->relationLoaded('category') && $competition->category) {
            $category = [
                'id' => $competition->category->id,
                'name' => $competition->category->name,
                'description' => $competition->category->description,
            ];

            if ($competition->category->relationLoaded('managers')) {
                $managers = $competition->category->managers->map(function (QuranCategoryManager $manager) {
                    $person = $manager->relationLoaded('person') ? $manager->person : null;

                    return [
                        'id' => $manager->id,
                        'sort_order' => (int) $manager->sort_order,
                        'person' => $person ? [
                            'id' => $person->id,
                            'name' => $person->full_name,
                        ] : null,
                    ];
                })->values()->all();
            }
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
            'winners' => $competition->relationLoaded('winners')
                ? $competition->winners->map(fn (QuranCompetitionWinner $w) => $this->formatWinner($w))->values()->all()
                : [],
            'media' => $competition->relationLoaded('media')
                ? $competition->media->map(fn (QuranCompetitionMedia $m) => $this->formatMedia($m))->values()->all()
                : [],
            'sections' => $competition->relationLoaded('sections')
                ? $competition->sections->map(fn (QuranCompetitionSection $s) => $this->formatSection($s))->values()->all()
                : [],
            'managers' => $managers,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatWinner(QuranCompetitionWinner $winner): array
    {
        $person = $winner->relationLoaded('person') ? $winner->person : null;

        return [
            'id' => $winner->id,
            'position' => (int) $winner->position,
            'position_name' => $winner->position_name,
            'category' => $winner->category,
            'notes' => $winner->notes,
            'person' => $person ? [
                'id' => $person->id,
                'name' => $person->full_name,
            ] : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatMedia(QuranCompetitionMedia $media): array
    {
        $url = $media->media_type === 'youtube'
            ? $media->youtube_url
            : $media->file_url;

        return [
            'id' => $media->id,
            'media_type' => $media->media_type,
            'url' => $url,
            'thumbnail_url' => $media->youtube_thumbnail,
            'caption' => $media->caption,
            'sort_order' => (int) $media->sort_order,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatSection(QuranCompetitionSection $section): array
    {
        $people = $section->relationLoaded('people')
            ? $section->people->map(function (Person $person) {
                return [
                    'id' => $person->id,
                    'name' => $person->full_name,
                    'sort_order' => (int) ($person->pivot->sort_order ?? 0),
                ];
            })->values()->all()
            : [];

        return [
            'id' => $section->id,
            'name' => $section->name,
            'sort_order' => (int) $section->sort_order,
            'people' => $people,
        ];
    }
}
