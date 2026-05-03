<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BreastfeedingPublicPageData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BreastfeedingPublicApiController extends Controller
{
    public function __construct(
        protected BreastfeedingPublicPageData $breastfeedingPublicPageData
    ) {}

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'view_mode' => ['nullable', 'string', Rule::in(['mothers', 'children'])],
        ]);

        $viewMode = $validated['view_mode'] ?? 'mothers';
        $search = $validated['search'] ?? null;

        $page = $this->breastfeedingPublicPageData->build($search);

        return response()->json([
            'mothers' => $page['mothers']->values(),
            'children' => $page['children']->values(),
            'stats' => $page['stats'],
            'view_mode' => $viewMode,
            'search' => $search ?? '',
        ]);
    }
}
