<?php

namespace App\Http\Resources;

use App\Models\Competition;
use App\Models\Image;
use App\Models\ProgramGallery;
use App\Models\ProgramLink;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * JSON لصفحة برنامج عامة — نفس بيانات {@see \App\Services\ProgramShowPageData::build()}.
 */
class ProgramShowApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        /** @var array<string, mixed> $d */
        $d = $this->resource;

        /** @var Image $program */
        $program = $d['program'];

        return [
            'program' => $this->serializeProgram($program),
            'gallery_media' => $d['galleryMedia']->map(fn (Image $m) => $this->serializeMediaItem($m))->values()->all(),
            'video_media' => $d['videoMedia']->map(fn (Image $m) => $this->serializeMediaItem($m))->values()->all(),
            'program_links' => $d['programLinks']->map(fn (ProgramLink $link) => $this->serializeProgramLink($link))->values()->all(),
            'program_galleries' => $d['programGalleries']->map(fn (ProgramGallery $g) => $this->serializeProgramGallery($g))->values()->all(),
            'sub_programs' => $d['subPrograms']->map(fn (Image $sub) => $this->serializeSubProgram($sub))->values()->all(),
            'competitions' => $d['competitions']->map(fn (Competition $c) => $this->serializeCompetition($c))->values()->all(),
            'competition_filter_categories' => $d['availableCategories']->map(fn ($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
            ])->values()->all(),
            'selected_category_id' => $d['selectedCategoryId'],
        ];
    }

    private function serializeProgram(Image $program): array
    {
        $coverPath = $program->cover_image_path ?? $program->path;

        return [
            'id' => $program->id,
            'kind' => $program->is_proud_of ? 'proud_of' : 'program',
            'name' => $program->name,
            'description' => $program->description,
            'program_title' => $program->program_title,
            'program_description' => $program->program_description,
            'program_order' => $program->program_order,
            'program_is_active' => (bool) $program->program_is_active,
            'proud_of_title' => $program->proud_of_title,
            'proud_of_description' => $program->proud_of_description,
            'proud_of_order' => $program->proud_of_order,
            'proud_of_is_active' => (bool) $program->proud_of_is_active,
            'is_program' => (bool) $program->is_program,
            'is_proud_of' => (bool) $program->is_proud_of,
            'youtube_url' => $program->youtube_url,
            'youtube_embed_url' => $program->isYouTube() ? $program->getYouTubeEmbedUrl() : null,
            'youtube_thumbnail_url' => $program->isYouTube() ? $program->getYouTubeThumbnail() : null,
            'media_type' => $program->media_type,
            'hero_image_url' => $coverPath ? asset('storage/'.$coverPath) : null,
            'thumbnail_url' => $program->getThumbnailUrl(),
            'page_url' => url()->route('programs.show', $program->id),
            'program_category' => $program->relationLoaded('programCategory') && $program->programCategory
                ? ['id' => $program->programCategory->id, 'name' => $program->programCategory->name]
                : null,
        ];
    }

    private function serializeMediaItem(Image $item): array
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
            'media_type' => $item->media_type,
            'youtube_url' => $item->youtube_url,
            'youtube_embed_url' => $item->isYouTube() ? $item->getYouTubeEmbedUrl() : null,
            'youtube_thumbnail_url' => $item->isYouTube() ? $item->getYouTubeThumbnail() : null,
            'image_url' => $item->path ? asset('storage/'.$item->path) : null,
            'thumbnail_url' => $item->getThumbnailUrl(),
            'program_media_order' => $item->program_media_order,
            'gallery_id' => $item->gallery_id,
            'is_program' => (bool) $item->is_program,
        ];
    }

    private function serializeProgramLink(ProgramLink $link): array
    {
        $href = $link->url ?? '';

        return [
            'id' => $link->id,
            'title' => $link->title,
            'url' => $this->absoluteUrl($href) ?? $href,
            'description' => $link->description,
            'link_order' => $link->link_order,
        ];
    }

    private function serializeProgramGallery(ProgramGallery $gallery): array
    {
        $images = $gallery->relationLoaded('images')
            ? $gallery->images->map(fn (Image $img) => [
                'id' => $img->id,
                'name' => $img->name,
                'description' => $img->description,
                'image_url' => $img->path ? asset('storage/'.$img->path) : null,
                'thumbnail_url' => $img->getThumbnailUrl(),
                'program_media_order' => $img->program_media_order,
            ])->values()->all()
            : [];

        return [
            'id' => $gallery->id,
            'title' => $gallery->title,
            'description' => $gallery->description,
            'gallery_order' => $gallery->gallery_order,
            'images' => $images,
        ];
    }

    private function serializeSubProgram(Image $sub): array
    {
        $coverPath = $sub->cover_image_path ?? $sub->path;

        return [
            'id' => $sub->id,
            'name' => $sub->name,
            'program_title' => $sub->program_title,
            'program_description' => $sub->program_description,
            'program_is_active' => (bool) $sub->program_is_active,
            'program_order' => $sub->program_order,
            'image_url' => $coverPath ? asset('storage/'.$coverPath) : null,
            'thumbnail_url' => $sub->getThumbnailUrl(),
            'page_url' => url()->route('programs.show', $sub->id),
        ];
    }

    private function serializeCompetition(Competition $c): array
    {
        return [
            'id' => $c->id,
            'title' => $c->title,
            'description' => $c->description,
            'game_type' => $c->game_type,
            'team_size' => $c->team_size,
            'start_date' => $c->start_date?->toDateString(),
            'end_date' => $c->end_date?->toDateString(),
            'is_active' => (bool) $c->is_active,
            'is_registration_open' => $c->isRegistrationOpen(),
            'categories' => $c->relationLoaded('categories')
                ? $c->categories->map(fn ($cat) => ['id' => $cat->id, 'name' => $cat->name])->values()->all()
                : [],
            'participants' => $this->serializeCompetitionParticipants($c),
        ];
    }

    /**
     * نفس تجميع الصفحة العامة: أفراد + فرق متعددة الأعضاء (بدون أسرار تسجيل).
     */
    private function serializeCompetitionParticipants(Competition $competition): array
    {
        $competition->loadMissing(['registrations.user', 'registrations.categories', 'teams.members']);

        $registrations = $competition->registrations;
        $teams = $competition->teams;

        $individualUsers = $registrations->whereNull('team_id')->pluck('user')->filter()->unique('id');

        $singleMemberTeams = $teams->filter(fn ($team) => $team->members->count() === 1);
        foreach ($singleMemberTeams as $team) {
            $member = $team->members->first();
            if ($member && ! $individualUsers->contains('id', $member->id)) {
                $individualUsers->push($member);
            }
        }

        $multiMemberTeams = $teams->filter(fn ($team) => $team->members->count() >= 2);

        $individualsPayload = $individualUsers->values()->map(function ($user) use ($registrations) {
            $reg = $registrations->where('user_id', $user->id)->first();
            $cats = $reg && $reg->relationLoaded('categories') ? $reg->categories : collect();

            return [
                'user_id' => $user->id,
                'name' => $user->name,
                'categories' => $cats->map(fn ($cat) => [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ])->values()->all(),
            ];
        })->all();

        $teamsPayload = $multiMemberTeams->values()->map(function ($team) use ($registrations) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'is_complete' => (bool) $team->is_complete,
                'members' => $team->members->map(function ($member) use ($registrations) {
                    $reg = $registrations->where('user_id', $member->id)->first();
                    $cats = $reg && $reg->relationLoaded('categories') ? $reg->categories : collect();

                    return [
                        'user_id' => $member->id,
                        'name' => $member->name,
                        'role' => $member->pivot->role ?? null,
                        'categories' => $cats->map(fn ($c) => [
                            'id' => $c->id,
                            'name' => $c->name,
                        ])->values()->all(),
                    ];
                })->values()->all(),
            ];
        })->all();

        return [
            'individuals' => $individualsPayload,
            'teams' => $teamsPayload,
        ];
    }

    private function absoluteUrl(?string $href): ?string
    {
        if ($href === null || $href === '') {
            return null;
        }
        $href = trim($href);
        if (filter_var($href, FILTER_VALIDATE_URL)) {
            return $href;
        }
        if (str_starts_with($href, '//')) {
            return request()->getScheme().':'.$href;
        }
        if (str_starts_with($href, '/')) {
            return url($href);
        }

        return url($href);
    }
}
