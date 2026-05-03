<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportsPageData;
use Illuminate\Http\JsonResponse;

class ReportsPublicApiController extends Controller
{
    public function __construct(
        protected ReportsPageData $reportsPageData
    ) {}

    /**
     * نفس بيانات صفحة HTML /reports بصيغة JSON.
     */
    public function index(): JsonResponse
    {
        return response()->json($this->reportsPageData->buildIndex());
    }
}
