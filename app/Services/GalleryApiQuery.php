<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Image;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GalleryApiQuery
{
    private const PER_PAGE_DEFAULT = 24;

    private const PER_PAGE_MAX = 50;

    private const SEARCH_MAX_LENGTH = 120;

    public function isGuest(Request $request): bool
    {
        return ! $request->user();
    }

    /**
     * صور المعرض العام: مرتبطة بتصنيف، وليست وسائط برنامج أو «نفتخر بـ».
     */
    public function baseImageQuery(Request $request): Builder
    {
        $guest = $this->isGuest($request);

        $q = Image::query()
            ->whereNotNull('category_id')
            ->whereNull('program_id')
            ->where('is_program', false)
            ->where('is_proud_of', false);

        if ($guest) {
            $q->whereHas('category', fn (Builder $c) => $c->where('is_active', true));
        }

        return $q;
    }

    public function normalizedPerPage(?int $perPage): int
    {
        if ($perPage === null || $perPage < 1) {
            return self::PER_PAGE_DEFAULT;
        }

        return min($perPage, self::PER_PAGE_MAX);
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

    /**
     * معرفات التصنيف المستهدفة عند ?category=&deep=1.
     *
     * @return array<int, int>|null null إذا لم يُمرَّر category
     */
    public function categoryFilterIds(?Category $category, bool $deep): ?array
    {
        if ($category === null) {
            return null;
        }

        if (! $deep) {
            return [$category->id];
        }

        return $this->descendantCategoryIdsIncludingSelf($category->id);
    }

    /**
     * @return array<int, int>
     */
    public function descendantCategoryIdsIncludingSelf(int $categoryId): array
    {
        $ids = [$categoryId];
        $frontier = [$categoryId];

        while ($frontier !== []) {
            $children = Category::query()
                ->whereIn('parent_id', $frontier)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            if ($children === []) {
                break;
            }

            $ids = array_merge($ids, $children);
            $frontier = $children;
        }

        return array_values(array_unique($ids));
    }

    /**
     * عدد الصور لكل تصنيف (مباشرة فقط) وفق نفس قاعدة baseImageQuery.
     *
     * @return array<int, int> category_id => count
     */
    public function directImageCountsByCategoryId(Request $request): array
    {
        $rows = $this->baseImageQuery($request)
            ->clone()
            ->selectRaw('category_id, COUNT(*) as c')
            ->groupBy('category_id')
            ->pluck('c', 'category_id');

        $out = [];
        foreach ($rows as $categoryId => $count) {
            $out[(int) $categoryId] = (int) $count;
        }

        return $out;
    }

    /**
     * تصنيفات للعرض في الـ API: مسطّحة، مع total_images_count (التصنيف + كل الفروع).
     *
     * @return Collection<int, Category>
     */
    public function categoriesForIndex(Request $request): Collection
    {
        $guest = $this->isGuest($request);

        $directCounts = $this->directImageCountsByCategoryId($request);

        $categoriesQuery = Category::query()
            ->select(['id', 'name', 'description', 'parent_id', 'sort_order', 'is_active'])
            ->orderBy('sort_order')
            ->orderBy('id');

        if ($guest) {
            $categoriesQuery->where('is_active', true);
        }

        /** @var Collection<int, Category> $all */
        $all = $categoriesQuery->get()->keyBy('id');

        $byParent = $all->groupBy(fn (Category $c) => $c->parent_id);

        $countSubtree = function (Category $cat) use (&$countSubtree, $byParent, $directCounts): int {
            $direct = $directCounts[$cat->id] ?? 0;
            $children = $byParent->get($cat->id, collect());
            $sub = 0;
            foreach ($children as $child) {
                $sub += $countSubtree($child);
            }

            return $direct + $sub;
        };

        $totals = [];
        foreach ($all as $cat) {
            $totals[$cat->id] = $countSubtree($cat);
        }

        return $all
            ->filter(fn (Category $c) => ($totals[$c->id] ?? 0) > 0)
            ->map(function (Category $c) use ($totals) {
                $c->setAttribute('total_images_count', $totals[$c->id] ?? 0);

                return $c;
            })
            ->values();
    }

    public function paginateImages(Request $request, ?Category $category = null, bool $deep = false): LengthAwarePaginator
    {
        $perPage = $this->normalizedPerPage($request->query('per_page') !== null ? (int) $request->query('per_page') : null);
        $search = $this->normalizedSearch($request->query('search'));

        $q = $this->baseImageQuery($request)
            ->select([
                'id', 'name', 'description', 'path', 'thumbnail_path', 'youtube_url',
                'media_type', 'category_id', 'article_id', 'created_at',
            ])
            ->with([
                'category:id,name,parent_id',
                'article:id,title,person_id',
                'article.person:id,first_name,last_name',
                'mentionedPersons:id,first_name,last_name',
            ])
            ->orderByDesc('id');

        if ($search !== null) {
            $like = '%'.addcslashes($search, '%_\\').'%';
            $q->where(function (Builder $w) use ($like) {
                $w->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like);
            });
        }

        $ids = $this->categoryFilterIds($category, $deep);
        if ($ids !== null) {
            $q->whereIn('category_id', $ids);
        }

        return $q->paginate($perPage)->withQueryString();
    }

    /**
     * تحميل تصنيف للفلترة مع التحقق من صلاحية الزائر.
     */
    public function resolveCategoryForFilter(Request $request, int $categoryId): ?Category
    {
        $guest = $this->isGuest($request);

        $q = Category::query()->whereKey($categoryId);
        if ($guest) {
            $q->where('is_active', true);
        }

        return $q->first();
    }
}
