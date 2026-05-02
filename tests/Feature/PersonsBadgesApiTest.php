<?php

namespace Tests\Feature;

use App\Models\Padge;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonsBadgesApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.password_protection_enabled' => false]);
    }

    public function test_api_persons_badges_returns_json_structure(): void
    {
        $response = $this->getJson('/api/persons/badges');

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure([
            'success',
            'persons',
        ]);
        $this->assertIsArray($response->json('persons'));
    }

    public function test_api_persons_badges_includes_person_with_padges(): void
    {
        $person = Person::create([
            'first_name' => 'مختبر',
            'last_name' => 'الوسام',
            'gender' => 'male',
        ]);

        $padge = Padge::create([
            'name' => 'وسام تجريبي',
            'description' => 'وصف',
            'color' => '#22c55e',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $person->padges()->attach($padge->id, ['is_active' => true]);

        $response = $this->getJson('/api/persons/badges');

        $response->assertOk();
        $response->assertJsonCount(1, 'persons');

        $row = $response->json('persons.0');
        $this->assertSame($person->id, $row['id']);
        $this->assertArrayHasKey('full_name', $row);
        $this->assertArrayHasKey('avatar_url', $row);
        $this->assertArrayHasKey('profile_url', $row);
        $this->assertStringContainsString('/people/profile/'.$person->id, $row['profile_url']);

        $this->assertCount(1, $row['padges']);
        $this->assertSame('وسام تجريبي', $row['padges'][0]['name']);
        $this->assertArrayHasKey('image_url', $row['padges'][0]);
        $this->assertTrue($row['padges'][0]['pivot_is_active']);
    }
}
