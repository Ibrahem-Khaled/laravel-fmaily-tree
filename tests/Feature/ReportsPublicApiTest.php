<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsPublicApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.password_protection_enabled' => false]);
    }

    public function test_reports_public_api_returns_expected_top_level_keys(): void
    {
        $response = $this->getJson('/api/reports');

        $response->assertOk();
        $response->assertJsonStructure([
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
            'mostCommonNames',
        ]);
    }
}
