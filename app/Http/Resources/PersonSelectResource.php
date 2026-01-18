<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for Select2 person search dropdowns.
 */
class PersonSelectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fullName = $this->full_name ?? trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));

        return [
            'id' => $this->id,
            'text' => $fullName,
            'full_name' => $fullName,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'gender' => $this->gender ?? null,
            'photo_url' => $this->avatar ?? null,
        ];
    }
}

