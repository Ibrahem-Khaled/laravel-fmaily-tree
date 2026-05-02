<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * شخص مع أوسمته لاستجابة {@see \App\Http\Controllers\PersonsBadgesApiController}.
 */
class PersonWithPadgesApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'avatar_url' => $this->avatar,
            'profile_url' => url()->route('people.profile.show', $this->id),
            'padges' => $this->padges->map(function ($padge) {
                return [
                    'id' => $padge->id,
                    'name' => $padge->name,
                    'description' => $padge->description,
                    'color' => $padge->color,
                    'sort_order' => (int) $padge->sort_order,
                    'is_active' => (bool) $padge->is_active,
                    'image_url' => $padge->image ? asset('storage/'.$padge->image) : null,
                    'pivot_is_active' => isset($padge->pivot) ? (bool) $padge->pivot->is_active : true,
                ];
            })->values()->all(),
        ];
    }
}
