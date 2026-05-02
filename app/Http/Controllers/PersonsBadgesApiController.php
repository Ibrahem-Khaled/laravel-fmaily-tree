<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonWithPadgesApiResource;
use App\Models\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonsBadgesApiController extends Controller
{
    /**
     * نفس منطق صفحة {@see \App\Http\Controllers\HomePersonController::personsWhereHasBadges} — JSON للواجهات.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $persons = Person::whereHas('padges')
            ->with(['padges' => fn ($q) => $q->orderBy('sort_order')])
            ->get();

        return response()->json([
            'success' => true,
            'persons' => $persons
                ->map(fn (Person $person) => (new PersonWithPadgesApiResource($person))->toArray($request))
                ->values()
                ->all(),
        ]);
    }
}
