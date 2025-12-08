<?php

namespace App\Http\Controllers;

use App\Models\Image;

class ProgramPageController extends Controller
{
    /**
     * عرض صفحة برنامج محدد للجمهور.
     */
    public function show(Image $program)
    {
        abort_unless($program->is_program || $program->is_proud_of, 404);

        $program->load(['mediaItems', 'programLinks', 'programGalleries.images', 'subPrograms']);

        $galleryMedia = $program->mediaItems->filter(function ($item) {
            return ($item->media_type === 'image' || is_null($item->media_type))
                && is_null($item->gallery_id)
                && !$item->is_program; // استبعاد البرامج الفرعية
        });

        $videoMedia = $program->mediaItems->where('media_type', 'youtube');

        // البرامج الفرعية النشطة فقط
        $subPrograms = $program->subPrograms->where('program_is_active', true);

        return view('programs.show', [
            'program' => $program,
            'galleryMedia' => $galleryMedia,
            'videoMedia' => $videoMedia,
            'programLinks' => $program->programLinks,
            'programGalleries' => $program->programGalleries,
            'subPrograms' => $subPrograms,
        ]);
    }
}

