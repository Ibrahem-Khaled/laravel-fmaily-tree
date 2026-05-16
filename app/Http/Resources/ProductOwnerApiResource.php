<?php

namespace App\Http\Resources;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Person
 */
class ProductOwnerApiResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        /** @var Person $person */
        $person = $this->resource;

        return [
            'id' => $person->id,
            'name' => $person->full_name,
            'products_count' => (int) ($person->products_count ?? 0),
        ];
    }
}
