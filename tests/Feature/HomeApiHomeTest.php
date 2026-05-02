<?php

namespace Tests\Feature;

use App\Models\FamilyNews;
use App\Models\ImportantLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeApiHomeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.password_protection_enabled' => false]);
    }

    public function test_api_home_includes_parity_fields_for_frontend(): void
    {
        ImportantLink::create([
            'title' => 'تطبيق تجريبي',
            'url' => 'https://example.com/app',
            'url_ios' => 'https://apps.apple.com/app/example',
            'url_android' => 'https://play.google.com/store/apps/details?id=example',
            'type' => 'app',
            'status' => 'approved',
            'is_active' => true,
            'order' => 1,
            'open_in_new_tab' => true,
        ]);

        $news = FamilyNews::create([
            'title' => 'خبر تجريبي',
            'content' => '<p>نص</p>',
            'summary' => 'ملخص',
            'published_at' => now(),
            'display_order' => 0,
            'is_active' => true,
            'views_count' => 0,
        ]);

        $response = $this->getJson('/api/home');

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure([
            'success',
            'degree_category_ids' => [
                'bachelor',
                'master',
                'phd',
            ],
            'degree_categories' => [
                'bachelor',
                'master',
                'phd',
            ],
            'importantLinks',
            'familyNews',
            'latestGraduates',
            'quiz' => [
                'quizCompetitions',
                'nextQuizEvent',
                'activeQuizCompetitions',
            ],
        ]);

        $response->assertJsonPath('degree_category_ids.bachelor', null);
        $response->assertJsonPath('degree_categories.bachelor', null);

        $link = collect($response->json('importantLinks'))->firstWhere('title', 'تطبيق تجريبي');
        $this->assertNotNull($link);
        $this->assertArrayHasKey('url_ios', $link);
        $this->assertArrayHasKey('url_android', $link);
        $this->assertArrayHasKey('media', $link);
        $this->assertIsArray($link['media']);
        $this->assertStringContainsString('apps.apple.com', $link['url_ios']);

        $newsJson = collect($response->json('familyNews'))->firstWhere('id', $news->id);
        $this->assertNotNull($newsJson);
        $this->assertArrayHasKey('url', $newsJson);
        $this->assertStringContainsString((string) $news->id, $newsJson['url']);
    }
}
