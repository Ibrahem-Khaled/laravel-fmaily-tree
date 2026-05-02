<?php

namespace App\Http\Resources;

use App\Models\Image;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Image
 */
class GalleryImageApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        /** @var Image $img */
        $img = $this->resource;

        $mentioned = [];
        if ($img->relationLoaded('mentionedPersons')) {
            $mentioned = $img->mentionedPersons->map(function (Person $p) {
                return [
                    'id' => $p->id,
                    'name' => $p->full_name ?? trim($p->first_name.' '.$p->last_name),
                ];
            })->values()->all();
        }

        $article = null;
        if ($img->relationLoaded('article') && $img->article) {
            $article = [
                'id' => $img->article->id,
                'title' => $img->article->title,
            ];
            if ($img->article->relationLoaded('person') && $img->article->person) {
                $person = $img->article->person;
                $article['person'] = [
                    'id' => $person->id,
                    'name' => $person->full_name ?? trim($person->first_name.' '.$person->last_name),
                ];
            }
        }

        $category = null;
        if ($img->relationLoaded('category') && $img->category) {
            $category = [
                'id' => $img->category->id,
                'name' => $img->category->name,
                'parent_id' => $img->category->parent_id,
            ];
        }

        return [
            'id' => $img->id,
            'name' => $img->name,
            'description' => $img->description,
            'media_type' => $img->media_type,
            'youtube_url' => $img->youtube_url,
            'youtube_embed_url' => $img->isYouTube() ? $img->getYouTubeEmbedUrl() : null,
            'youtube_thumbnail_url' => $img->isYouTube() ? $img->getYouTubeThumbnail() : null,
            'image_url' => $img->path ? asset('storage/'.$img->path) : null,
            'thumbnail_url' => $img->getThumbnailUrl(),
            'category_id' => $img->category_id,
            'article_id' => $img->article_id,
            'category' => $category,
            'article' => $article,
            'mentioned_persons' => $mentioned,
            'created_at' => $img->created_at?->toIso8601String(),
        ];
    }
}
