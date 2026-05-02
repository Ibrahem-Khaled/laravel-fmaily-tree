<?php

namespace Tests\Feature;

use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramShowApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.password_protection_enabled' => false]);
    }

    public function test_api_program_show_returns_404_for_non_program_image(): void
    {
        $image = Image::create([
            'name' => 'صورة عادية',
            'path' => 'gallery/x.jpg',
            'is_program' => false,
            'is_proud_of' => false,
        ]);

        $this->getJson('/api/programs/'.$image->id)->assertNotFound();
    }

    public function test_api_program_show_returns_structure_for_program(): void
    {
        $program = Image::create([
            'name' => 'برنامج تجريبي',
            'path' => 'programs/test-cover.jpg',
            'is_program' => true,
            'program_title' => 'عنوان البرنامج',
            'program_description' => '<p>وصف</p>',
            'program_is_active' => true,
            'program_order' => 1,
            'media_type' => 'image',
        ]);

        $response = $this->getJson('/api/programs/'.$program->id);

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('program.id', $program->id);
        $response->assertJsonPath('program.kind', 'program');
        $response->assertJsonStructure([
            'success',
            'program' => [
                'id',
                'kind',
                'page_url',
                'hero_image_url',
                'thumbnail_url',
                'program_title',
                'is_program',
                'is_proud_of',
            ],
            'gallery_media',
            'video_media',
            'program_links',
            'program_galleries',
            'sub_programs',
            'competitions',
            'competition_filter_categories',
            'selected_category_id',
        ]);
    }
}
