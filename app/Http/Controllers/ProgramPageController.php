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
        abort_unless($program->is_program, 404);

        $program->load(['mediaItems', 'programLinks']);

        $galleryMedia = $program->mediaItems->filter(function ($item) {
            return $item->media_type === 'image' || is_null($item->media_type);
        });

        $videoMedia = $program->mediaItems->where('media_type', 'youtube');

        return view('programs.show', [
            'program' => $program,
            'galleryMedia' => $galleryMedia,
            'videoMedia' => $videoMedia,
            'programLinks' => $program->programLinks,
        ]);
    }
}

