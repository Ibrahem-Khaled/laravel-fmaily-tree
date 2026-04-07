<?php

namespace App\Http\Resources;

use App\Models\CouncilImage;
use App\Models\HomeGalleryImage;
use App\Models\HomeSectionItem;
use App\Models\Image;
use App\Models\Person;
use App\Models\QuizCompetition;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionChoice;
use App\Models\QuizSurveyItem;
use App\Models\SlideshowImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class HomePageApiResource extends JsonResource
{
    public static $wrap = null;

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $d = $this->resource;

        return [
            'success' => true,
            'slides' => $this->serializeSlides($d['slides'] ?? collect()),
            'gallery' => $this->serializeGallery($d['gallery'] ?? collect()),
            'familyBrief' => $d['familyBrief'] ?? null,
            'whatsNew' => $d['whatsNew'] ?? '',
            'courses' => $this->serializeCourses($d['courses'] ?? collect()),
            'programs' => $this->serializeProgramImages($d['programs'] ?? collect()),
            'program_categories' => $this->serializeProgramCategoryGroups($d['programCategories'] ?? collect()),
            'proudOf' => $this->serializeProudOfImages($d['proudOf'] ?? collect()),
            'councils' => $this->serializeCouncils($d['councils'] ?? collect()),
            'events' => $this->serializeEvents($d['events'] ?? collect()),
            'birthdayPersons' => $this->serializeBirthdayPersons($d['birthdayPersons'] ?? collect()),
            'latestGraduates' => $this->serializeGraduates($d['latestGraduates'] ?? collect()),
            'bachelorTotalCount' => (int) ($d['bachelorTotalCount'] ?? 0),
            'masterTotalCount' => (int) ($d['masterTotalCount'] ?? 0),
            'phdTotalCount' => (int) ($d['phdTotalCount'] ?? 0),
            'importantLinks' => $this->serializeImportantLinks($d['importantLinks'] ?? collect()),
            'familyNews' => $this->serializeFamilyNews($d['familyNews'] ?? collect()),
            'dynamicSections' => $this->serializeDynamicSections($d['dynamicSections'] ?? collect()),
            'quiz' => $this->serializeQuizBlock($d['quiz'] ?? []),
        ];
    }

    private function storageUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        return asset('storage/'.ltrim($path, '/'));
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

    private function serializeSlides($collection): array
    {
        return collect($collection)->map(function (SlideshowImage $slide) {
            return [
                'id' => $slide->id,
                'title' => $slide->title,
                'description' => $slide->description,
                'link' => $slide->link ? $this->absoluteUrl($slide->link) : null,
                'order' => $slide->order,
                'is_active' => (bool) $slide->is_active,
                'image_url' => $slide->image_url,
            ];
        })->values()->all();
    }

    private function serializeGallery($collection): array
    {
        return collect($collection)->map(function ($item) {
            if ($item instanceof HomeGalleryImage) {
                return [
                    'type' => 'home_gallery',
                    'id' => $item->id,
                    'name' => $item->name,
                    'image_url' => $item->image_url,
                    'category' => $item->relationLoaded('category') && $item->category
                        ? ['id' => $item->category->id, 'name' => $item->category->name]
                        : null,
                ];
            }
            if ($item instanceof Image) {
                return [
                    'type' => 'gallery_image',
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'image_url' => $item->path ? $this->storageUrl($item->path) : null,
                    'youtube_url' => $item->youtube_url,
                    'media_type' => $item->media_type,
                    'category' => $item->relationLoaded('category') && $item->category
                        ? ['id' => $item->category->id, 'name' => $item->category->name]
                        : null,
                ];
            }

            return [];
        })->filter()->values()->all();
    }

    private function serializeCourses($collection): array
    {
        return collect($collection)->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'instructor' => $course->instructor,
                'duration' => $course->duration,
                'students' => $course->students,
                'link' => $course->link ? $this->absoluteUrl($course->link) : null,
                'order' => $course->order,
                'is_active' => (bool) $course->is_active,
                'image_url' => $course->image_url,
            ];
        })->values()->all();
    }

    private function serializeProgramImages($collection): array
    {
        return collect($collection)->map(fn (Image $img) => $this->serializeContentImage($img))->all();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, array{title: string, programs: \Illuminate\Support\Collection}>  $collection
     */
    private function serializeProgramCategoryGroups($collection): array
    {
        return collect($collection)->map(function (array $group) {
            return [
                'title' => $group['title'],
                'programs' => $this->serializeProgramImages($group['programs'] ?? collect()),
            ];
        })->values()->all();
    }

    private function serializeProudOfImages($collection): array
    {
        return collect($collection)->map(fn (Image $img) => $this->serializeContentImage($img))->all();
    }

    private function serializeContentImage(Image $img): array
    {
        return [
            'id' => $img->id,
            'name' => $img->name,
            'description' => $img->description,
            'path' => $img->path,
            'image_url' => $img->path ? $this->storageUrl($img->path) : null,
            'youtube_url' => $img->youtube_url,
            'media_type' => $img->media_type,
            'is_program' => (bool) $img->is_program,
            'program_title' => $img->program_title,
            'program_description' => $img->program_description,
            'program_order' => $img->program_order,
            'is_proud_of' => (bool) $img->is_proud_of,
            'proud_of_title' => $img->proud_of_title,
            'proud_of_description' => $img->proud_of_description,
            'proud_of_order' => $img->proud_of_order,
        ];
    }

    private function serializeCouncils($collection): array
    {
        return collect($collection)->map(function ($council) {
            return [
                'id' => $council->id,
                'name' => $council->name,
                'description' => $council->description,
                'address' => $council->address,
                'google_map_url' => $council->google_map_url ? $this->absoluteUrl($council->google_map_url) : null,
                'working_days_from' => $council->working_days_from,
                'working_days_to' => $council->working_days_to,
                'display_order' => $council->display_order,
                'is_active' => (bool) $council->is_active,
                'images' => $council->relationLoaded('images')
                    ? $council->images->map(function (CouncilImage $img) {
                        return [
                            'id' => $img->id,
                            'description' => $img->description,
                            'display_order' => $img->display_order,
                            'image_url' => $img->image_url,
                            'thumbnail_url' => $img->thumbnail_url,
                        ];
                    })->values()->all()
                    : [],
            ];
        })->values()->all();
    }

    private function serializeEvents($collection): array
    {
        return collect($collection)->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'city' => $event->city,
                'location' => $event->location,
                'location_name' => $event->location_name,
                'event_date' => $event->event_date instanceof Carbon
                    ? $event->event_date->toIso8601String()
                    : null,
                'show_countdown' => (bool) $event->show_countdown,
                'display_order' => $event->display_order,
                'is_active' => (bool) $event->is_active,
            ];
        })->values()->all();
    }

    private function serializeBirthdayPersons($collection): array
    {
        return collect($collection)->map(function (Person $person) {
            return [
                'id' => $person->id,
                'first_name' => $person->first_name,
                'last_name' => $person->last_name,
                'full_name' => $person->full_name,
                'gender' => $person->gender,
                'birth_date' => $person->birth_date?->format('Y-m-d'),
                'photo_url' => $person->photo_url,
                'avatar_url' => $person->avatar,
                'occupation' => $person->occupation,
            ];
        })->values()->all();
    }

    private function serializeGraduates($collection): array
    {
        return collect($collection)->map(function ($article) {
            $person = $article->person;

            return [
                'id' => $article->id,
                'degree_type' => $article->degree_type ?? null,
                'category' => $article->relationLoaded('category') && $article->category
                    ? ['id' => $article->category->id, 'name' => $article->category->name]
                    : null,
                'person' => $person ? [
                    'id' => $person->id,
                    'first_name' => $person->first_name,
                    'last_name' => $person->last_name,
                    'full_name' => $person->full_name,
                    'gender' => $person->gender,
                    'avatar_url' => $person->avatar,
                ] : null,
            ];
        })->values()->all();
    }

    private function serializeImportantLinks($collection): array
    {
        return collect($collection)->map(function ($link) {
            return [
                'id' => $link->id,
                'title' => $link->title,
                'url' => $link->url ? $this->absoluteUrl($link->url) : null,
                'type' => $link->type,
                'icon' => $link->icon,
                'description' => $link->description,
                'order' => $link->order,
                'is_active' => (bool) $link->is_active,
                'open_in_new_tab' => (bool) $link->open_in_new_tab,
                'image_url' => $link->image_url,
            ];
        })->values()->all();
    }

    private function serializeFamilyNews($collection): array
    {
        return collect($collection)->map(function ($news) {
            return [
                'id' => $news->id,
                'title' => $news->title,
                'summary' => $news->summary,
                'content' => $news->content,
                'published_at' => $news->published_at instanceof Carbon
                    ? $news->published_at->toIso8601String()
                    : null,
                'display_order' => $news->display_order,
                'is_active' => (bool) $news->is_active,
                'views_count' => (int) $news->views_count,
                'main_image_url' => $news->main_image_url,
                'images' => $news->relationLoaded('images')
                    ? $news->images->map(fn ($img) => [
                        'id' => $img->id,
                        'caption' => $img->caption,
                        'display_order' => $img->display_order,
                        'image_url' => $img->image_url,
                    ])->values()->all()
                    : [],
            ];
        })->values()->all();
    }

    private function serializeDynamicSections($collection): array
    {
        return collect($collection)->map(function ($section) {
            $items = $section->relationLoaded('items')
                ? $section->items->map(fn (HomeSectionItem $item) => $this->serializeSectionItem($item))->values()->all()
                : [];

            $sourceItems = null;
            if ($section->content_source_type && isset($section->content_source_items)) {
                $col = $section->content_source_items;
                $sourceItems = $col ? collect($col)->map(fn ($entity) => $this->serializeSourceEntity($entity))->values()->all() : [];
            }

            return [
                'id' => $section->id,
                'title' => $section->title,
                'section_type' => $section->section_type,
                'display_order' => $section->display_order,
                'is_active' => (bool) $section->is_active,
                'settings' => $section->settings ?? [],
                'css_classes' => $section->css_classes,
                'content_source_type' => $section->content_source_type,
                'items' => $items,
                'content_source_items' => $sourceItems,
            ];
        })->values()->all();
    }

    private function serializeSectionItem(HomeSectionItem $item): array
    {
        $content = $item->content ?? [];
        $settings = $item->settings ?? [];
        $content = $this->normalizeContentUrls($item->item_type, $content);

        $row = [
            'id' => $item->id,
            'item_type' => $item->item_type,
            'display_order' => $item->display_order,
            'content' => $content,
            'settings' => $settings,
            'youtube_url' => $item->youtube_url,
            'image_url' => $item->image_url,
            'video_url' => $item->video_url,
            'content_format' => $this->contentFormatForType($item->item_type),
        ];

        if (in_array($item->item_type, ['button', 'card', 'image'], true)) {
            if (! empty($content['link'])) {
                $row['link_absolute'] = $this->absoluteUrl($content['link']);
            }
            if (! empty($content['url'])) {
                $row['url_absolute'] = $this->absoluteUrl($content['url']);
            }
        }

        return $row;
    }

    private function normalizeContentUrls(string $itemType, array $content): array
    {
        if ($itemType === 'button' && ! empty($content['url'])) {
            $content['url'] = $this->absoluteUrl($content['url']) ?? $content['url'];
        }
        if (in_array($itemType, ['image', 'card'], true) && ! empty($content['link'])) {
            $content['link'] = $this->absoluteUrl($content['link']) ?? $content['link'];
        }

        return $content;
    }

    private function contentFormatForType(?string $itemType): string
    {
        return match ($itemType) {
            'rich_text', 'html' => 'html',
            'text' => 'plain',
            default => 'structured',
        };
    }

    private function serializeSourceEntity($entity): array
    {
        if ($entity instanceof Person) {
            return [
                'entity_type' => 'person',
                'id' => $entity->id,
                'full_name' => $entity->full_name,
                'subtitle' => $entity->occupation,
                'avatar_url' => $entity->avatar,
            ];
        }
        if ($entity instanceof User) {
            $avatar = $entity->avatar
                ? $this->storageUrl($entity->avatar)
                : asset('assets/img/avatar-male.png');

            return [
                'entity_type' => 'user',
                'id' => $entity->id,
                'full_name' => $entity->name,
                'subtitle' => $entity->email,
                'avatar_url' => $avatar,
            ];
        }

        return [
            'entity_type' => 'unknown',
            'id' => $entity->id ?? null,
        ];
    }

    private function serializeQuizBlock(array $quiz): array
    {
        $next = $quiz['nextQuizEvent'] ?? null;
        if (is_array($next) && isset($next['target_at']) && $next['target_at'] instanceof Carbon) {
            $next['target_at'] = $next['target_at']->toIso8601String();
        }

        return [
            'quizCompetitions' => collect($quiz['quizCompetitions'] ?? [])->map(fn ($c) => $this->serializeQuizCompetition($c))->values()->all(),
            'nextQuizEvent' => $next,
            'activeQuizCompetitions' => collect($quiz['activeQuizCompetitions'] ?? [])->map(fn ($c) => $this->serializeQuizCompetition($c))->values()->all(),
        ];
    }

    private function serializeQuizCompetition(QuizCompetition $c): array
    {
        return [
            'id' => $c->id,
            'title' => $c->title,
            'description' => $c->description,
            'is_active' => (bool) $c->is_active,
            'display_order' => $c->display_order,
            'start_at' => $c->start_at?->toIso8601String(),
            'end_at' => $c->end_at?->toIso8601String(),
            'reveal_delay_seconds' => $c->reveal_delay_seconds,
            'show_draw_only' => (bool) $c->show_draw_only,
            'questions_visible_at' => $c->getQuestionsVisibleAt()?->toIso8601String(),
            'questions' => $c->relationLoaded('questions')
                ? $c->questions->map(fn (QuizQuestion $q) => $this->serializeQuizQuestion($q))->values()->all()
                : [],
        ];
    }

    private function serializeQuizQuestion(QuizQuestion $q): array
    {
        return [
            'id' => $q->id,
            'question_text' => $q->question_text,
            'description' => $q->description,
            'answer_type' => $q->answer_type,
            'is_multiple_selections' => (bool) $q->is_multiple_selections,
            'winners_count' => $q->winners_count,
            'display_order' => $q->display_order,
            'prize' => $q->prize,
            'groups_count' => $q->groups_count,
            'vote_max_selections' => $q->vote_max_selections,
            'require_prior_registration' => (bool) $q->require_prior_registration,
            'choices' => $q->relationLoaded('choices')
                ? $q->choices->map(fn (QuizQuestionChoice $ch) => $this->serializeQuizChoice($ch))->values()->all()
                : [],
            'survey_items' => $q->relationLoaded('surveyItems')
                ? $q->surveyItems->map(fn (QuizSurveyItem $si) => $this->serializeSurveyItem($si))->values()->all()
                : [],
        ];
    }

    private function serializeQuizChoice(QuizQuestionChoice $ch): array
    {
        return [
            'id' => $ch->id,
            'choice_text' => $ch->choice_text,
            'is_correct' => (bool) $ch->is_correct,
            'group_name' => $ch->group_name,
            'image_url' => $ch->image ? $this->storageUrl($ch->image) : null,
            'video_url' => $this->maybeStorageOrUrl($ch->video),
        ];
    }

    private function serializeSurveyItem(QuizSurveyItem $si): array
    {
        $isHtmlText = $si->block_type === 'text' && $si->body_text && strip_tags($si->body_text) !== $si->body_text;

        return [
            'id' => $si->id,
            'sort_order' => $si->sort_order,
            'block_type' => $si->block_type,
            'body_text' => $si->body_text,
            'youtube_url' => $si->youtube_url ? $this->absoluteUrl($si->youtube_url) : null,
            'image_url' => $si->media_path ? $this->storageUrl($si->media_path) : null,
            'response_kind' => $si->response_kind,
            'rating_max' => $si->rating_max,
            'number_min' => $si->number_min,
            'number_max' => $si->number_max,
            'content_format' => $isHtmlText ? 'html' : ($si->block_type === 'text' ? 'plain' : 'structured'),
        ];
    }

    private function maybeStorageOrUrl(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return $this->storageUrl($value);
    }
}
