<?php

namespace App\Services;

use App\Models\Category;
use App\Models\QuranCompetition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class QuranCompetitionApiQuery
{
    private const SEARCH_MAX_LENGTH = 120;

    private const CURRENT_COMPETITIONS_LIMIT = 3;

    /**
     * @return array{category: ?int, search: ?string}
     */
    public function parseFilters(Request $request): array
    {
        $category = $request->query('category');

        return [
            'category' => $category !== null && $category !== '' ? (int) $category : null,
            'search' => $this->normalizedSearch($request->query('search')),
        ];
    }

    public function normalizedSearch(?string $search): ?string
    {
        if ($search === null || $search === '') {
            return null;
        }

        $search = trim($search);

        if ($search === '') {
            return null;
        }

        return mb_substr($search, 0, self::SEARCH_MAX_LENGTH);
    }

    public function baseCompetitionQuery(): Builder
    {
        return QuranCompetition::query()
            ->active()
            ->ordered()
            ->with(['category', 'winners.person', 'media']);
    }

    public function applyListFilters(Builder $query, array $filters): Builder
    {
        if ($filters['category'] !== null) {
            $query->where('category_id', $filters['category']);
        }

        if ($filters['search'] !== null) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('hijri_year', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function listCompetitions(Request $request): Collection
    {
        $filters = $this->parseFilters($request);
        $query = $this->baseCompetitionQuery();
        $this->applyListFilters($query, $filters);

        return $query->get();
    }

    /**
     * @return array{current: Collection<int, QuranCompetition>, previous: Collection<int, QuranCompetition>}
     */
    public function splitCurrentAndPrevious(Collection $competitions): array
    {
        return [
            'current' => $competitions->take(self::CURRENT_COMPETITIONS_LIMIT)->values(),
            'previous' => $competitions->skip(self::CURRENT_COMPETITIONS_LIMIT)->values(),
        ];
    }

    /**
     * @return Collection<int, Category>
     */
    public function categoriesForFilter(): Collection
    {
        return Category::query()
            ->whereHas('quranCompetitions', fn (Builder $q) => $q->where('is_active', true))
            ->ordered()
            ->active()
            ->get(['id', 'name', 'description', 'sort_order']);
    }

    /**
     * @return Collection<int, Category>
     */
    public function categoriesForNavigation(): Collection
    {
        return Category::query()
            ->whereHas('quranCompetitions', fn (Builder $q) => $q->where('is_active', true))
            ->ordered()
            ->active()
            ->with(['quranCompetitions' => fn ($q) => $q->active()->ordered()])
            ->get();
    }

    /**
     * @return array{
     *     category_id: int,
     *     name: string,
     *     route_type: string,
     *     competition_id: int|null,
     *     competitions_count: int
     * }
     */
    public function resolveNavigationItem(Category $category): array
    {
        $activeCompetitions = $category->relationLoaded('quranCompetitions')
            ? $category->quranCompetitions
            : $category->quranCompetitions()->active()->ordered()->get();

        $count = $activeCompetitions->count();

        if ($count === 1) {
            return [
                'category_id' => $category->id,
                'name' => $category->name,
                'route_type' => 'competition',
                'competition_id' => $activeCompetitions->first()->id,
                'competitions_count' => 1,
            ];
        }

        return [
            'category_id' => $category->id,
            'name' => $category->name,
            'route_type' => 'category',
            'competition_id' => null,
            'competitions_count' => $count,
        ];
    }

    public function resolveCategory(int $categoryId): ?Category
    {
        return Category::query()
            ->whereKey($categoryId)
            ->active()
            ->first();
    }

    public function competitionsForCategory(int $categoryId): Collection
    {
        return $this->baseCompetitionQuery()
            ->where('category_id', $categoryId)
            ->get();
    }

    public function shouldRedirectToSingleCompetition(Collection $competitions): ?int
    {
        if ($competitions->count() !== 1) {
            return null;
        }

        return $competitions->first()->id;
    }

    public function resolveCompetitionForShow(int $competitionId): ?QuranCompetition
    {
        return QuranCompetition::query()
            ->whereKey($competitionId)
            ->active()
            ->with([
                'winners.person',
                'media',
                'category.managers.person',
                'sections.people',
            ])
            ->first();
    }

    public function isSingleInCategory(QuranCompetition $competition): bool
    {
        if (! $competition->category_id) {
            return false;
        }

        return QuranCompetition::query()
            ->where('category_id', $competition->category_id)
            ->active()
            ->count() === 1;
    }

    public function storageUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        return asset('storage/'.$path);
    }
}
