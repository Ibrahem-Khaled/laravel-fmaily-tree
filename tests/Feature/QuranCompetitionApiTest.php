<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Person;
use App\Models\QuranCompetition;
use App\Models\QuranCompetitionSection;
use App\Models\QuranCompetitionWinner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuranCompetitionApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.password_protection_enabled' => false]);
    }

    private function createCategory(string $name = 'فئة قرآن'): Category
    {
        return Category::create([
            'name' => $name,
            'is_active' => true,
            'sort_order' => 0,
        ]);
    }

    private function createCompetition(Category $category, array $overrides = []): QuranCompetition
    {
        return QuranCompetition::create(array_merge([
            'title' => 'مسابقة تجريبية',
            'description' => 'وصف',
            'hijri_year' => '1445',
            'category_id' => $category->id,
            'is_active' => true,
            'display_order' => 0,
        ], $overrides));
    }

    public function test_navigation_single_competition_uses_competition_route_type(): void
    {
        $category = $this->createCategory();
        $competition = $this->createCompetition($category, ['title' => 'وحيدة']);

        $response = $this->getJson('/api/quran-competitions/navigation');

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $item = collect($response->json('data'))->firstWhere('category_id', $category->id);
        $this->assertNotNull($item);
        $this->assertSame('competition', $item['route_type']);
        $this->assertSame($competition->id, $item['competition_id']);
        $this->assertSame(1, $item['competitions_count']);
    }

    public function test_navigation_multiple_competitions_uses_category_route_type(): void
    {
        $category = $this->createCategory();
        $this->createCompetition($category, ['title' => 'أولى', 'display_order' => 0]);
        $this->createCompetition($category, ['title' => 'ثانية', 'display_order' => 1]);

        $response = $this->getJson('/api/quran-competitions/navigation');

        $response->assertOk();
        $item = collect($response->json('data'))->firstWhere('category_id', $category->id);
        $this->assertSame('category', $item['route_type']);
        $this->assertNull($item['competition_id']);
        $this->assertSame(2, $item['competitions_count']);
    }

    public function test_index_splits_five_competitions_into_three_current_and_two_previous(): void
    {
        $category = $this->createCategory();

        for ($i = 0; $i < 5; $i++) {
            $this->createCompetition($category, [
                'title' => "مسابقة {$i}",
                'display_order' => $i,
                'hijri_year' => (string) (1440 + $i),
            ]);
        }

        $response = $this->getJson('/api/quran-competitions');

        $response->assertOk();
        $response->assertJsonCount(3, 'current_competitions');
        $response->assertJsonCount(2, 'previous_competitions');
    }

    public function test_index_filters_by_category_and_search(): void
    {
        $catA = $this->createCategory('أ');
        $catB = $this->createCategory('ب');

        $match = $this->createCompetition($catA, ['title' => 'حفظ 1445', 'hijri_year' => '1445']);
        $this->createCompetition($catA, ['title' => 'أخرى', 'hijri_year' => '1440']);
        $this->createCompetition($catB, ['title' => 'حفظ 1445', 'hijri_year' => '1445']);

        $byCategory = $this->getJson('/api/quran-competitions?category='.$catA->id);
        $byCategory->assertOk();
        $byCategory->assertJsonCount(2, 'current_competitions');

        $bySearch = $this->getJson('/api/quran-competitions?search=1445');
        $bySearch->assertOk();
        $ids = collect($bySearch->json('current_competitions'))
            ->merge($bySearch->json('previous_competitions'))
            ->pluck('id')
            ->all();
        $this->assertContains($match->id, $ids);
        $this->assertCount(2, $ids);
    }

    public function test_category_returns_redirect_when_single_competition(): void
    {
        $category = $this->createCategory();
        $competition = $this->createCompetition($category);

        $response = $this->getJson('/api/quran-competitions/categories/'.$category->id);

        $response->assertOk();
        $response->assertJsonPath('redirect_to_competition_id', $competition->id);
    }

    public function test_category_returns_404_when_inactive(): void
    {
        $category = Category::create([
            'name' => 'مخفية',
            'is_active' => false,
            'sort_order' => 0,
        ]);

        $this->getJson('/api/quran-competitions/categories/'.$category->id)
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_show_returns_404_for_inactive_competition(): void
    {
        $category = $this->createCategory();
        $competition = $this->createCompetition($category, ['is_active' => false]);

        $this->getJson('/api/quran-competitions/'.$competition->id)
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_show_includes_winners_and_sections(): void
    {
        $category = $this->createCategory();
        $competition = $this->createCompetition($category);
        $person = Person::factory()->create();

        QuranCompetitionWinner::create([
            'competition_id' => $competition->id,
            'person_id' => $person->id,
            'position' => 1,
            'category' => 'حفظ',
        ]);

        $section = QuranCompetitionSection::create([
            'competition_id' => $competition->id,
            'name' => 'المشاركون',
            'sort_order' => 0,
        ]);
        $section->people()->attach($person->id, ['sort_order' => 0]);

        $response = $this->getJson('/api/quran-competitions/'.$competition->id);

        $response->assertOk();
        $response->assertJsonPath('data.id', $competition->id);
        $response->assertJsonCount(1, 'data.winners');
        $response->assertJsonPath('data.winners.0.person.id', $person->id);
        $response->assertJsonCount(1, 'data.sections');
        $response->assertJsonPath('data.sections.0.people.0.id', $person->id);
    }
}
