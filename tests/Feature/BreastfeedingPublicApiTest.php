<?php

namespace Tests\Feature;

use App\Models\Breastfeeding;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BreastfeedingPublicApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.password_protection_enabled' => false]);
    }

    public function test_breastfeeding_public_api_returns_expected_shape(): void
    {
        $mother = Person::factory()->create(['gender' => 'female', 'first_name' => 'أمينة', 'last_name' => 'الرضاعة']);
        $child = Person::factory()->create(['gender' => 'male', 'first_name' => 'طفل', 'last_name' => 'الرضاعة']);
        $father = Person::factory()->create(['gender' => 'male', 'first_name' => 'الأب', 'last_name' => 'الرضاعة']);

        Breastfeeding::create([
            'nursing_mother_id' => $mother->id,
            'breastfed_child_id' => $child->id,
            'breastfeeding_father_id' => $father->id,
            'start_date' => now()->subMonths(6),
            'end_date' => null,
            'notes' => 'ملاحظة تجريبية',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/breastfeeding');

        $response->assertOk();
        $response->assertJsonStructure([
            'mothers' => [
                '*' => [
                    'id',
                    'name',
                    'first_name',
                    'avatar',
                    'profile_url',
                    'children' => [
                        '*' => [
                            'id',
                            'name',
                            'first_name',
                            'avatar',
                            'profile_url',
                            'start_date',
                            'end_date',
                            'duration_months',
                            'is_active',
                            'notes',
                            'relationship_id',
                            'breastfeeding_father' => [
                                'id',
                                'name',
                                'first_name',
                                'avatar',
                                'profile_url',
                            ],
                        ],
                    ],
                ],
            ],
            'children' => [
                '*' => [
                    'id',
                    'name',
                    'first_name',
                    'avatar',
                    'profile_url',
                    'relationship_id',
                    'nursing_mother' => [
                        'id',
                        'name',
                        'profile_url',
                    ],
                    'breastfeeding_father',
                ],
            ],
            'stats' => [
                'total_relationships',
                'total_nursing_mothers',
                'total_breastfed_children',
                'active_breastfeeding',
            ],
            'view_mode',
            'search',
        ]);

        $response->assertJsonPath('stats.total_relationships', 1);
        $response->assertJsonPath('stats.total_nursing_mothers', 1);
        $response->assertJsonPath('stats.total_breastfed_children', 1);
        $response->assertJsonPath('stats.active_breastfeeding', 1);
        $response->assertJsonPath('view_mode', 'mothers');

        $motherJson = collect($response->json('mothers'))->firstWhere('id', $mother->id);
        $this->assertNotNull($motherJson);
        $this->assertStringContainsString((string) $mother->id, $motherJson['profile_url']);

        $childRow = collect($response->json('children'))->firstWhere('id', $child->id);
        $this->assertNotNull($childRow);
        $this->assertSame($mother->id, $childRow['nursing_mother']['id']);
        $this->assertSame($father->id, $childRow['breastfeeding_father']['id']);
    }

    public function test_breastfeeding_public_api_validates_view_mode(): void
    {
        $response = $this->getJson('/api/breastfeeding?view_mode=invalid');

        $response->assertStatus(422);
    }

    public function test_breastfeeding_public_api_search_filters_mothers(): void
    {
        $motherA = Person::factory()->create(['gender' => 'female', 'first_name' => 'فريدة', 'last_name' => 'واحد']);
        $motherB = Person::factory()->create(['gender' => 'female', 'first_name' => 'سعاد', 'last_name' => 'اثنين']);
        $childA = Person::factory()->create(['gender' => 'male']);
        $childB = Person::factory()->create(['gender' => 'male']);

        Breastfeeding::create([
            'nursing_mother_id' => $motherA->id,
            'breastfed_child_id' => $childA->id,
            'is_active' => true,
        ]);
        Breastfeeding::create([
            'nursing_mother_id' => $motherB->id,
            'breastfed_child_id' => $childB->id,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/breastfeeding?search=' . urlencode('فريدة'));

        $response->assertOk();
        $ids = collect($response->json('mothers'))->pluck('id')->all();
        $this->assertContains($motherA->id, $ids);
        $this->assertNotContains($motherB->id, $ids);
    }
}
