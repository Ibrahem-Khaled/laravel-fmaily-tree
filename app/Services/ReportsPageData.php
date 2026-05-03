<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\Location;
use App\Models\Person;
use App\Models\Scopes\OrderedScope;
use Illuminate\Support\Facades\DB;

class ReportsPageData
{
    /**
     * نفس بيانات صفحة GET /reports (للواجهة أو JSON).
     *
     * @return array<string, mixed>
     */
    public function buildIndex(): array
    {
        $totalFamilyMembers = Person::where('from_outside_the_family', false)
            ->whereNull('death_date')
            ->count();

        $maleCount = Person::where('from_outside_the_family', false)
            ->where('gender', 'male')
            ->whereNull('death_date')
            ->count();

        $femaleCount = Person::where('from_outside_the_family', false)
            ->where('gender', 'female')
            ->whereNull('death_date')
            ->count();

        $masterCategoryIds = Category::where(function ($query) {
            $query->where('name', 'like', '%ماجستير%')
                ->orWhere('name', 'like', '%Master%');
        })->pluck('id');

        $masterDegreeCount = Article::whereIn('category_id', $masterCategoryIds)->count();

        $phdCategoryIds = Category::where(function ($query) {
            $query->where('name', 'like', '%دكتوراه%')
                ->orWhere('name', 'like', '%PhD%')
                ->orWhere('name', 'like', '%Ph.D%');
        })->pluck('id');

        $phdCount = Article::whereIn('category_id', $phdCategoryIds)->count();

        $bachelorCategoryIds = Category::where(function ($query) {
            $query->where('name', 'like', '%بكالوريوس%')
                ->orWhere('name', 'like', '%Bachelor%')
                ->orWhere('name', 'like', '%Bachelors%')
                ->orWhere('name', 'like', '%ليسانس%');
        })->pluck('id');

        $bachelorDegreeCount = Article::whereIn('category_id', $bachelorCategoryIds)->count();

        $allFamilyMembersByAge = Person::where('from_outside_the_family', false)
            ->whereNull('death_date')
            ->where('gender', 'male')
            ->get()
            ->map(function (Person $person) {
                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'gender' => $person->gender,
                    'age' => $person->age,
                    'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
                    'birth_date_raw' => $person->birth_date ? $person->birth_date->timestamp : PHP_INT_MAX,
                    'profile_url' => route('people.profile.show', $person->id),
                ];
            })
            ->sortBy(fn ($person) => $person['birth_date_raw'])
            ->values()
            ->map(fn ($person) => $this->stripSortKey($person));

        $allFamilyFemalesByAge = Person::where('from_outside_the_family', false)
            ->whereNull('death_date')
            ->where('gender', 'female')
            ->whereNotNull('birth_date')
            ->get()
            ->map(function (Person $person) {
                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'gender' => $person->gender,
                    'birth_date_raw' => $person->birth_date ? $person->birth_date->timestamp : PHP_INT_MAX,
                    'profile_url' => route('people.profile.show', $person->id),
                ];
            })
            ->sortBy(fn ($person) => $person['birth_date_raw'])
            ->values()
            ->map(fn ($person) => $this->stripSortKey($person));

        $generationsData = $this->getGenerationsStatistics();
        $locationsStatistics = $this->getLocationsStatistics();

        // بدون OrderedScope: الـ scope يضيف ORDER BY على أعمدة خارج GROUP BY فيفشل مع MySQL ONLY_FULL_GROUP_BY
        $mostCommonNames = Person::withoutGlobalScope(OrderedScope::class)
            ->where('from_outside_the_family', false)
            ->select(
                'first_name',
                DB::raw('count(*) as count'),
                DB::raw('SUM(CASE WHEN gender = "male" THEN 1 ELSE 0 END) as males'),
                DB::raw('SUM(CASE WHEN gender = "female" THEN 1 ELSE 0 END) as females')
            )
            ->groupBy('first_name')
            ->orderByDesc('count')
            ->orderBy('first_name')
            ->limit(20)
            ->get()
            ->map(function ($item) {
                $dominantGender = $item->males > $item->females ? 'male' : 'female';

                return [
                    'name' => $item->first_name,
                    'count' => $item->count,
                    'males' => $item->males,
                    'females' => $item->females,
                    'dominant_gender' => $dominantGender,
                    'persons_list_url' => route('reports.name.persons', ['name' => $item->first_name]),
                ];
            })
            ->sortByDesc('count')
            ->values();

        return compact(
            'totalFamilyMembers',
            'maleCount',
            'femaleCount',
            'masterDegreeCount',
            'phdCount',
            'bachelorDegreeCount',
            'allFamilyMembersByAge',
            'allFamilyFemalesByAge',
            'generationsData',
            'locationsStatistics',
            'mostCommonNames'
        );
    }

    /**
     * @return array{success: true, location: array, persons: \Illuminate\Support\Collection, total: int, males: int, females: int}
     */
    public function locationPersonsPayload(int $locationId): array
    {
        $location = Location::findOrFail($locationId);

        $persons = Person::where('location_id', $locationId)
            ->where('from_outside_the_family', false)
            ->whereNull('death_date')
            ->orderBy('gender')
            ->orderBy('first_name')
            ->get()
            ->map(function (Person $person) {
                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'gender' => $person->gender,
                    'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
                    'age' => $person->birth_date ? $person->birth_date->diffInYears(now()) : null,
                    'profile_url' => route('people.profile.show', $person->id),
                ];
            });

        return [
            'success' => true,
            'location' => [
                'id' => $location->id,
                'name' => $location->display_name,
            ],
            'persons' => $persons,
            'total' => $persons->count(),
            'males' => $persons->where('gender', 'male')->count(),
            'females' => $persons->where('gender', 'female')->count(),
        ];
    }

    /**
     * @return array{success: true, person: array, statistics: array}
     */
    public function personStatisticsPayload(int $personId): array
    {
        $person = Person::findOrFail($personId);

        $allDescendants = $this->countAllDescendants($person->id);

        $generationsWithMembers = [];
        if ($allDescendants['total'] > 0) {
            foreach ($allDescendants['generations'] as $genLevel => $genStats) {
                $relatives = $this->getGenerationRelatives($person->id, $genLevel);
                $generationsWithMembers[$genLevel] = array_merge($genStats, [
                    'members' => $this->getGenerationMembers($person->id, $genLevel),
                    'relatives' => $relatives,
                    'relatives_count' => count($relatives),
                ]);
            }
        }

        return [
            'success' => true,
            'person' => [
                'id' => $person->id,
                'full_name' => $person->full_name,
                'gender' => $person->gender,
                'profile_url' => route('people.profile.show', $person->id),
            ],
            'statistics' => [
                'total_descendants' => $allDescendants['total'],
                'male_descendants' => $allDescendants['males'],
                'female_descendants' => $allDescendants['females'],
                'generations_breakdown' => $generationsWithMembers,
            ],
        ];
    }

    /**
     * @return array{success: true, name: string, persons: \Illuminate\Support\Collection, total: int, males: int, females: int}
     */
    public function personsByNamePayload(string $name): array
    {
        $persons = Person::where('from_outside_the_family', false)
            ->where('first_name', $name)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(function (Person $person) {
                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'gender' => $person->gender,
                    'age' => $person->age,
                    'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
                    'location' => $person->location,
                    'profile_url' => route('people.profile.show', $person->id),
                ];
            });

        return [
            'success' => true,
            'name' => $name,
            'persons' => $persons,
            'total' => $persons->count(),
            'males' => $persons->where('gender', 'male')->count(),
            'females' => $persons->where('gender', 'female')->count(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getGenerationsStatistics(): array
    {
        $grandfathers = Person::where('from_outside_the_family', false)
            ->whereNull('parent_id')
            ->orderBy('first_name')
            ->get();

        $statistics = [];

        foreach ($grandfathers as $grandfather) {
            $allDescendants = $this->countAllDescendants($grandfather->id);

            if ($allDescendants['total'] > 0) {
                $generationsWithMembers = [];
                foreach ($allDescendants['generations'] as $genLevel => $genStats) {
                    $relatives = $this->getGenerationRelatives($grandfather->id, $genLevel);
                    $generationsWithMembers[$genLevel] = array_merge($genStats, [
                        'members' => $this->getGenerationMembers($grandfather->id, $genLevel),
                        'relatives' => $relatives,
                        'relatives_count' => count($relatives),
                    ]);
                }

                $totalRelativesForGrandfather = $this->countRelativesForGrandfather($grandfather->id);

                $statistics[] = [
                    'grandfather_id' => $grandfather->id,
                    'grandfather_name' => $grandfather->full_name,
                    'grandfather_profile_url' => route('people.profile.show', $grandfather->id),
                    'total_descendants' => $allDescendants['total'],
                    'male_descendants' => $allDescendants['males'],
                    'female_descendants' => $allDescendants['females'],
                    'total_relatives' => $totalRelativesForGrandfather,
                    'generations_breakdown' => $generationsWithMembers,
                ];
            }
        }

        usort($statistics, function ($a, $b) {
            return $b['total_descendants'] <=> $a['total_descendants'];
        });

        return $statistics;
    }

    /**
     * @return array{total: int, males: int, females: int, generations: array<int, array{total: int, males: int, females: int}>}
     */
    private function countAllDescendants(int $personId, int $currentLevel = 1): array
    {
        $query = Person::where('parent_id', $personId)
            ->where('from_outside_the_family', false)
            ->get();

        $total = 0;
        $males = 0;
        $females = 0;
        $generations = [];

        foreach ($query as $child) {
            $total++;

            if ($child->gender === 'male') {
                $males++;
            } else {
                $females++;
            }

            if (! isset($generations[$currentLevel])) {
                $generations[$currentLevel] = [
                    'total' => 0,
                    'males' => 0,
                    'females' => 0,
                ];
            }
            $generations[$currentLevel]['total']++;
            if ($child->gender === 'male') {
                $generations[$currentLevel]['males']++;
            } else {
                $generations[$currentLevel]['females']++;
            }

            $descendantsStats = $this->countAllDescendants($child->id, $currentLevel + 1);
            $total += $descendantsStats['total'];
            $males += $descendantsStats['males'];
            $females += $descendantsStats['females'];

            foreach ($descendantsStats['generations'] as $genLevel => $genStats) {
                if (! isset($generations[$genLevel])) {
                    $generations[$genLevel] = ['total' => 0, 'males' => 0, 'females' => 0];
                }
                $generations[$genLevel]['total'] += $genStats['total'];
                $generations[$genLevel]['males'] += $genStats['males'];
                $generations[$genLevel]['females'] += $genStats['females'];
            }
        }

        return [
            'total' => $total,
            'males' => $males,
            'females' => $females,
            'generations' => $generations,
        ];
    }

    /**
     * @return list<array{id: int, full_name: string, gender: string, profile_url: string}>
     */
    private function getGenerationMembers(int $grandfatherId, int $targetLevel, ?int $currentPersonId = null, int $currentLevel = 1): array
    {
        if ($currentPersonId === null) {
            $currentPersonId = $grandfatherId;
        }

        $members = [];

        if ($currentLevel === $targetLevel) {
            $children = Person::where('parent_id', $currentPersonId)
                ->where('from_outside_the_family', false)
                ->orderBy('first_name')
                ->get();

            foreach ($children as $child) {
                $members[] = [
                    'id' => $child->id,
                    'full_name' => $child->full_name,
                    'gender' => $child->gender,
                    'profile_url' => route('people.profile.show', $child->id),
                ];
            }
        } else {
            $children = Person::where('parent_id', $currentPersonId)
                ->where('from_outside_the_family', false)
                ->get();

            foreach ($children as $child) {
                $childMembers = $this->getGenerationMembers($grandfatherId, $targetLevel, $child->id, $currentLevel + 1);
                $members = array_merge($members, $childMembers);
            }
        }

        return $members;
    }

    private function countRelativesForGrandfather(int $grandfatherId): int
    {
        $descendantIds = $this->getAllDescendantIds($grandfatherId);

        if (empty($descendantIds)) {
            return 0;
        }

        return (int) DB::table('marriages')
            ->join('persons as husbands', 'marriages.husband_id', '=', 'husbands.id')
            ->join('persons as wives', 'marriages.wife_id', '=', 'wives.id')
            ->where(function ($query) use ($descendantIds) {
                $query->where(function ($q) use ($descendantIds) {
                    $q->where('husbands.from_outside_the_family', true)
                        ->whereIn('wives.id', $descendantIds);
                })
                    ->orWhere(function ($q) use ($descendantIds) {
                        $q->where('husbands.from_outside_the_family', false)
                            ->whereIn('husbands.id', $descendantIds)
                            ->where('wives.from_outside_the_family', true);
                    });
            })
            ->where(function ($query) {
                $query->where('marriages.is_divorced', false)
                    ->whereNull('marriages.divorced_at');
            })
            ->selectRaw('CASE
                WHEN husbands.from_outside_the_family = 1 THEN marriages.husband_id
                ELSE marriages.wife_id
            END as relative_id')
            ->distinct()
            ->count();
    }

    /**
     * @param  list<int>  $ids
     * @return list<int>
     */
    private function getAllDescendantIds(int $personId, array $ids = []): array
    {
        $children = Person::where('parent_id', $personId)
            ->where('from_outside_the_family', false)
            ->pluck('id')
            ->toArray();

        $ids = array_merge($ids, $children);

        foreach ($children as $childId) {
            $ids = $this->getAllDescendantIds($childId, $ids);
        }

        return array_values(array_unique($ids));
    }

    /**
     * @return list<array{id: int, full_name: string, gender: string, profile_url: string}>
     */
    private function getGenerationRelatives(int $grandfatherId, int $targetLevel): array
    {
        $generationMemberIds = $this->getGenerationMemberIds($grandfatherId, $targetLevel);

        if (empty($generationMemberIds)) {
            return [];
        }

        $relativesData = DB::table('marriages')
            ->join('persons as husbands', 'marriages.husband_id', '=', 'husbands.id')
            ->join('persons as wives', 'marriages.wife_id', '=', 'wives.id')
            ->where(function ($query) use ($generationMemberIds) {
                $query->where(function ($q) use ($generationMemberIds) {
                    $q->where('husbands.from_outside_the_family', true)
                        ->whereIn('wives.id', $generationMemberIds);
                })
                    ->orWhere(function ($q) use ($generationMemberIds) {
                        $q->where('husbands.from_outside_the_family', false)
                            ->whereIn('husbands.id', $generationMemberIds)
                            ->where('wives.from_outside_the_family', true);
                    });
            })
            ->where(function ($query) {
                $query->where('marriages.is_divorced', false)
                    ->whereNull('marriages.divorced_at');
            })
            ->selectRaw('CASE
                WHEN husbands.from_outside_the_family = 1 THEN husbands.id
                ELSE wives.id
            END as relative_id')
            ->distinct()
            ->pluck('relative_id')
            ->toArray();

        if (empty($relativesData)) {
            return [];
        }

        return Person::with([
            'parent' => function ($query) {
                $query->select('id', 'first_name', 'gender', 'parent_id');
            },
            'parent.parent' => function ($query) {
                $query->select('id', 'first_name', 'gender', 'parent_id');
            },
            'parent.parent.parent' => function ($query) {
                $query->select('id', 'first_name', 'gender', 'parent_id');
            },
            'parent.parent.parent.parent' => function ($query) {
                $query->select('id', 'first_name', 'gender', 'parent_id');
            },
            'parent.parent.parent.parent.parent' => function ($query) {
                $query->select('id', 'first_name', 'gender', 'parent_id');
            },
        ])
            ->whereIn('id', $relativesData)
            ->get()
            ->map(fn (Person $person) => [
                'id' => $person->id,
                'full_name' => $person->full_name,
                'gender' => $person->gender,
                'profile_url' => route('people.profile.show', $person->id),
            ])
            ->toArray();
    }

    private function getGenerationMemberIds(int $grandfatherId, int $targetLevel, ?int $currentPersonId = null, int $currentLevel = 1): array
    {
        if ($currentPersonId === null) {
            $currentPersonId = $grandfatherId;
        }

        $memberIds = [];

        if ($currentLevel === $targetLevel) {
            $children = Person::where('parent_id', $currentPersonId)
                ->where('from_outside_the_family', false)
                ->pluck('id')
                ->toArray();

            $memberIds = array_merge($memberIds, $children);
        } else {
            $children = Person::where('parent_id', $currentPersonId)
                ->where('from_outside_the_family', false)
                ->get();

            foreach ($children as $child) {
                $childIds = $this->getGenerationMemberIds($grandfatherId, $targetLevel, $child->id, $currentLevel + 1);
                $memberIds = array_merge($memberIds, $childIds);
            }
        }

        return array_values(array_unique($memberIds));
    }

    /**
     * @return list<array{location_id: int, location_name: string, total: int, males: int, females: int, persons_list_url: string}>
     */
    private function getLocationsStatistics(): array
    {
        $locations = Location::whereHas('persons', function ($query) {
            $query->where('from_outside_the_family', false)
                ->whereNull('death_date');
        })->get();

        $statistics = [];

        foreach ($locations as $location) {
            $males = $location->persons()
                ->where('from_outside_the_family', false)
                ->where('gender', 'male')
                ->whereNull('death_date')
                ->count();

            $females = $location->persons()
                ->where('from_outside_the_family', false)
                ->where('gender', 'female')
                ->whereNull('death_date')
                ->count();

            $total = $males + $females;

            if ($total > 0) {
                $statistics[] = [
                    'location_id' => $location->id,
                    'location_name' => $location->display_name,
                    'total' => $total,
                    'males' => $males,
                    'females' => $females,
                    'persons_list_url' => route('reports.location.persons', ['locationId' => $location->id]),
                ];
            }
        }

        usort($statistics, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        return $statistics;
    }

    /**
     * @param  array<string, mixed>  $person
     * @return array<string, mixed>
     */
    private function stripSortKey(array $person): array
    {
        unset($person['birth_date_raw']);

        return $person;
    }
}
