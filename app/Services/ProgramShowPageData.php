<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;

class ProgramShowPageData
{
    /**
     * بيانات صفحة البرنامج/«نفتخر بـ» العامة — نفس منطق {@see \App\Http\Controllers\ProgramPageController::show}.
     *
     * @return array{
     *     program: Image,
     *     galleryMedia: \Illuminate\Support\Collection,
     *     videoMedia: \Illuminate\Support\Collection,
     *     programLinks: \Illuminate\Support\Collection,
     *     programGalleries: \Illuminate\Support\Collection,
     *     subPrograms: \Illuminate\Support\Collection,
     *     competitions: \Illuminate\Support\Collection,
     *     availableCategories: \Illuminate\Support\Collection,
     *     selectedCategoryId: int|string|null
     * }
     */
    public function build(Image $program, Request $request): array
    {
        abort_unless($program->is_program || $program->is_proud_of, 404);

        $program->load([
            'mediaItems',
            'programLinks',
            'programGalleries.images',
            'subPrograms',
            'programCategory',
            'competitions.categories',
            'competitions.registrations.user',
            'competitions.registrations.categories',
            'competitions.teams.members',
        ]);

        $galleryMedia = $program->mediaItems->filter(function ($item) {
            return ($item->media_type === 'image' || is_null($item->media_type))
                && is_null($item->gallery_id)
                && ! $item->is_program;
        });

        $videoMedia = $program->mediaItems->where('media_type', 'youtube');

        $subPrograms = $program->subPrograms->where('program_is_active', true);

        $competitions = $program->competitions;

        $selectedCategoryId = $request->query('category');
        if ($selectedCategoryId) {
            $competitions = $competitions->filter(function ($competition) use ($selectedCategoryId) {
                return $competition->categories->contains('id', $selectedCategoryId);
            });
        }

        $availableCategories = Category::whereHas('competitions', function ($query) use ($program) {
            $query->where('program_id', $program->id);
        })
            ->active()
            ->ordered()
            ->get();

        return [
            'program' => $program,
            'galleryMedia' => $galleryMedia,
            'videoMedia' => $videoMedia,
            'programLinks' => $program->programLinks,
            'programGalleries' => $program->programGalleries,
            'subPrograms' => $subPrograms,
            'competitions' => $competitions,
            'availableCategories' => $availableCategories,
            'selectedCategoryId' => $selectedCategoryId,
        ];
    }
}
