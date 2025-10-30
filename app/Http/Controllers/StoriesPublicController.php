<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class StoriesPublicController extends Controller
{
    /**
     * عرض جميع قصص شخص محدد (صاحب القصة).
     */
    public function personStories(Person $person): View
    {
        $stories = Story::with(['storyOwner', 'narrators'])
            ->where('story_owner_id', $person->id)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('stories.index', compact('person', 'stories'));
    }

    /**
     * عرض تفاصيل قصة.
     */
    public function show(Story $story): View
    {
        $story->load(['storyOwner', 'narrators']);
        return view('stories.show', compact('story'));
    }

    /**
     * API: عداد قصص الشخص (لإظهار زر في الواجهة).
     */
    public function countForPerson(int $id): JsonResponse
    {
        $count = Story::where('story_owner_id', $id)->count();
        return response()->json(['count' => $count]);
    }
}


