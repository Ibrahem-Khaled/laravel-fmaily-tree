<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\ProgramCategory;
use App\Services\HomePageData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageProgramCategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_data_groups_main_programs_by_program_category(): void
    {
        $category = ProgramCategory::create([
            'name' => 'فئة تجريبية',
            'description' => null,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        Image::create([
            'name' => 'برنامج مصنّف',
            'path' => 'programs/test-a.jpg',
            'is_program' => true,
            'program_title' => 'برنامج أ',
            'program_order' => 1,
            'program_is_active' => true,
            'media_type' => 'image',
            'program_category_id' => $category->id,
        ]);

        Image::create([
            'name' => 'برنامج بدون فئة',
            'path' => 'programs/test-b.jpg',
            'is_program' => true,
            'program_title' => 'برنامج ب',
            'program_order' => 2,
            'program_is_active' => true,
            'media_type' => 'image',
            'program_category_id' => null,
        ]);

        /** @var HomePageData $homePageData */
        $homePageData = $this->app->make(HomePageData::class);
        $d = $homePageData->build();

        $groups = $d['programCategories'];
        $this->assertCount(2, $groups);

        $this->assertSame('فئة تجريبية', $groups[0]['title']);
        $this->assertCount(1, $groups[0]['programs']);
        $this->assertSame('برنامج أ', $groups[0]['programs']->first()->program_title);

        $this->assertSame('بدون تصنيف', $groups[1]['title']);
        $this->assertCount(1, $groups[1]['programs']);
        $this->assertSame('برنامج ب', $groups[1]['programs']->first()->program_title);

        $flat = $d['programs'];
        $this->assertCount(2, $flat);
    }
}
