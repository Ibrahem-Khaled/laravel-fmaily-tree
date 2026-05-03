<?php

namespace App\Services;

use App\Models\Breastfeeding;
use App\Models\Person;
use Illuminate\Support\Collection;

class BreastfeedingPublicPageData
{
    /**
     * Build the same dataset as the public breastfeeding index (mothers-first and children-first views).
     *
     * @return array{mothers: Collection<int, array<string, mixed>>, children: Collection<int, array<string, mixed>>, stats: array<string, int>}
     */
    public function build(?string $search): array
    {
        $nursingMothersQuery = Person::where('gender', 'female')
            ->whereHas('nursingRelationships')
            ->with(['nursingRelationships.breastfedChild', 'nursingRelationships.breastfeedingFather']);

        if ($search) {
            $nursingMothersQuery->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhereHas('nursingRelationships.breastfedChild', function ($childQuery) use ($search) {
                        $childQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        $nursingMothers = $nursingMothersQuery->get();

        $mothers = $nursingMothers->map(function (Person $mother) {
            return [
                'id' => $mother->id,
                'name' => $mother->full_name,
                'first_name' => $mother->first_name,
                'avatar' => $mother->avatar,
                'profile_url' => route('people.profile.show', $mother->id),
                'children' => $mother->nursingRelationships->map(function ($relationship) {
                    $child = $relationship->breastfedChild;

                    return [
                        'id' => $child->id,
                        'name' => $child->full_name,
                        'first_name' => $child->first_name,
                        'avatar' => $child->avatar,
                        'profile_url' => route('people.profile.show', $child->id),
                        'start_date' => $relationship->start_date?->format('Y/m/d'),
                        'end_date' => $relationship->end_date?->format('Y/m/d'),
                        'duration_months' => $relationship->duration_in_months,
                        'is_active' => $relationship->is_active,
                        'notes' => $relationship->notes,
                        'relationship_id' => $relationship->id,
                        'breastfeeding_father' => $relationship->breastfeedingFather
                            ? $this->personCard($relationship->breastfeedingFather)
                            : null,
                    ];
                }),
            ];
        });

        $children = collect();
        foreach ($nursingMothers as $mother) {
            foreach ($mother->nursingRelationships as $relationship) {
                $child = $relationship->breastfedChild;
                $children->push([
                    'id' => $child->id,
                    'name' => $child->full_name,
                    'first_name' => $child->first_name,
                    'avatar' => $child->avatar,
                    'profile_url' => route('people.profile.show', $child->id),
                    'start_date' => $relationship->start_date?->format('Y/m/d'),
                    'end_date' => $relationship->end_date?->format('Y/m/d'),
                    'duration_months' => $relationship->duration_in_months,
                    'is_active' => $relationship->is_active,
                    'notes' => $relationship->notes,
                    'relationship_id' => $relationship->id,
                    'nursing_mother' => $this->personCard($mother),
                    'breastfeeding_father' => $relationship->breastfeedingFather
                        ? $this->personCard($relationship->breastfeedingFather)
                        : null,
                ]);
            }
        }

        $children = $children->sortBy('first_name')->values();

        $stats = [
            'total_relationships' => Breastfeeding::count(),
            'total_nursing_mothers' => Person::where('gender', 'female')->whereHas('nursingRelationships')->count(),
            'total_breastfed_children' => Person::whereHas('breastfedRelationships')->count(),
            'active_breastfeeding' => Breastfeeding::where('is_active', true)->count(),
        ];

        return [
            'mothers' => $mothers,
            'children' => $children,
            'stats' => $stats,
        ];
    }

    /**
     * @return array{id: int, name: string, first_name: string|null, avatar: mixed, profile_url: string}
     */
    private function personCard(Person $person): array
    {
        return [
            'id' => $person->id,
            'name' => $person->full_name,
            'first_name' => $person->first_name,
            'avatar' => $person->avatar,
            'profile_url' => route('people.profile.show', $person->id),
        ];
    }
}
