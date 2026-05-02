<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Services\ProgramShowPageData;
use Illuminate\Http\Request;

class ProgramPageController extends Controller
{
    /**
     * عرض صفحة برنامج محدد للجمهور.
     */
    public function show(Image $program, Request $request, ProgramShowPageData $programShowPageData)
    {
        return view('programs.show', $programShowPageData->build($program, $request));
    }
}
