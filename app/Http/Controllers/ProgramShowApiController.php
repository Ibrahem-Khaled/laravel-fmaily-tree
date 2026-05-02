<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProgramShowApiResource;
use App\Models\Image;
use App\Services\ProgramShowPageData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramShowApiController extends Controller
{
    /**
     * JSON لصفحة برنامج عامة — مطابق لـ {@see \App\Http\Controllers\ProgramPageController::show}.
     */
    public function show(Image $program, Request $request, ProgramShowPageData $programShowPageData): JsonResponse
    {
        $d = $programShowPageData->build($program, $request);

        return response()->json(array_merge(
            ['success' => true],
            (new ProgramShowApiResource($d))->resolve()
        ));
    }
}
