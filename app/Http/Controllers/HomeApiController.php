<?php

namespace App\Http\Controllers;

use App\Http\Resources\HomePageApiResource;
use App\Services\HomePageData;
use Illuminate\Http\JsonResponse;

class HomeApiController extends Controller
{
    public function __invoke(HomePageData $homePageData): JsonResponse
    {
        return (new HomePageApiResource($homePageData->build()))->response();
    }
}
