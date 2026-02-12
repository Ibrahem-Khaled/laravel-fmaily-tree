<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Category;
use Illuminate\Http\Request;

class ProgramPageController extends Controller
{
    /**
     * عرض صفحة برنامج محدد للجمهور.
     */
    public function show(Image $program, Request $request)
    {
        abort_unless($program->is_program || $program->is_proud_of, 404);

        $program->load([
            'mediaItems', 
            'programLinks', 
            'programGalleries.images', 
            'subPrograms',
            'competitions.categories',
            'competitions.registrations.user',
            'competitions.registrations.categories'
        ]);

        $galleryMedia = $program->mediaItems->filter(function ($item) {
            return ($item->media_type === 'image' || is_null($item->media_type))
                && is_null($item->gallery_id)
                && !$item->is_program; // استبعاد البرامج الفرعية
        });

        $videoMedia = $program->mediaItems->where('media_type', 'youtube');

        // البرامج الفرعية النشطة فقط
        $subPrograms = $program->subPrograms->where('program_is_active', true);

        // المسابقات المرتبطة بالبرنامج مع المسجلين
        $competitions = $program->competitions;

        // فلترة المسابقات حسب التصنيف إذا تم اختيار تصنيف
        $selectedCategoryId = $request->query('category');
        if ($selectedCategoryId) {
            $competitions = $competitions->filter(function ($competition) use ($selectedCategoryId) {
                return $competition->categories->contains('id', $selectedCategoryId);
            });
        }

        // جلب جميع التصنيفات المرتبطة بمسابقات هذا البرنامج
        $availableCategories = Category::whereHas('competitions', function($query) use ($program) {
            $query->where('program_id', $program->id);
        })
        ->active()
        ->ordered()
        ->get();

        return view('programs.show', [
            'program' => $program,
            'galleryMedia' => $galleryMedia,
            'videoMedia' => $videoMedia,
            'programLinks' => $program->programLinks,
            'programGalleries' => $program->programGalleries,
            'subPrograms' => $subPrograms,
            'competitions' => $competitions,
            'availableCategories' => $availableCategories,
            'selectedCategoryId' => $selectedCategoryId,
        ]);
    }
}

