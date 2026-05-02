<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.password_protection_enabled' => false]);
    }

    public function test_gallery_images_excludes_program_and_proud_of_rows(): void
    {
        $cat = Category::create([
            'name' => 'فئة تجريبية',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $visible = Image::create([
            'name' => 'صورة معرض',
            'path' => 'gallery/a.jpg',
            'category_id' => $cat->id,
            'is_program' => false,
            'is_proud_of' => false,
        ]);

        Image::create([
            'name' => 'وسيط برنامج',
            'path' => 'programs/m.jpg',
            'category_id' => $cat->id,
            'program_id' => $visible->id,
            'is_program' => false,
            'is_proud_of' => false,
        ]);

        Image::create([
            'name' => 'نفتخر',
            'path' => 'proud/p.jpg',
            'category_id' => $cat->id,
            'is_program' => false,
            'is_proud_of' => true,
        ]);

        $response = $this->getJson('/api/gallery/images');

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('meta.total', 1);
        $response->assertJsonPath('data.0.id', $visible->id);
    }

    public function test_gallery_images_pagination(): void
    {
        $cat = Category::create([
            'name' => 'فئة',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        for ($i = 0; $i < 30; $i++) {
            Image::create([
                'name' => "صورة {$i}",
                'path' => "gallery/x{$i}.jpg",
                'category_id' => $cat->id,
                'is_program' => false,
                'is_proud_of' => false,
            ]);
        }

        $first = $this->getJson('/api/gallery/images?per_page=10&page=1');
        $first->assertOk();
        $first->assertJsonPath('meta.total', 30);
        $first->assertJsonPath('meta.per_page', 10);
        $first->assertJsonPath('meta.last_page', 3);
        $first->assertJsonCount(10, 'data');

        $second = $this->getJson('/api/gallery/images?per_page=10&page=2');
        $second->assertOk();
        $second->assertJsonCount(10, 'data');
    }

    public function test_gallery_images_filter_by_category(): void
    {
        $a = Category::create(['name' => 'أ', 'sort_order' => 0, 'is_active' => true]);
        $b = Category::create(['name' => 'ب', 'sort_order' => 1, 'is_active' => true]);

        $imgA = Image::create([
            'name' => 'في أ',
            'path' => 'gallery/a1.jpg',
            'category_id' => $a->id,
            'is_program' => false,
            'is_proud_of' => false,
        ]);

        Image::create([
            'name' => 'في ب',
            'path' => 'gallery/b1.jpg',
            'category_id' => $b->id,
            'is_program' => false,
            'is_proud_of' => false,
        ]);

        $response = $this->getJson('/api/gallery/images?category='.$a->id);

        $response->assertOk();
        $response->assertJsonPath('meta.total', 1);
        $response->assertJsonPath('data.0.id', $imgA->id);
    }

    public function test_gallery_images_unknown_category_returns_404(): void
    {
        $this->getJson('/api/gallery/images?category=99999')
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_guest_does_not_see_images_in_inactive_category(): void
    {
        $inactive = Category::create([
            'name' => 'مخفية',
            'sort_order' => 0,
            'is_active' => false,
        ]);

        Image::create([
            'name' => 'مخفية',
            'path' => 'gallery/h.jpg',
            'category_id' => $inactive->id,
            'is_program' => false,
            'is_proud_of' => false,
        ]);

        $this->getJson('/api/gallery/images')->assertJsonPath('meta.total', 0);
    }

    public function test_authenticated_user_sees_images_in_inactive_category(): void
    {
        $user = User::factory()->create();

        $inactive = Category::create([
            'name' => 'مخفية',
            'sort_order' => 0,
            'is_active' => false,
        ]);

        $img = Image::create([
            'name' => 'ظاهرة للمسجّل',
            'path' => 'gallery/h.jpg',
            'category_id' => $inactive->id,
            'is_program' => false,
            'is_proud_of' => false,
        ]);

        $this->actingAs($user)->getJson('/api/gallery/images')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.id', $img->id);
    }

    public function test_gallery_categories_returns_only_categories_with_images(): void
    {
        $with = Category::create(['name' => 'مع صور', 'sort_order' => 0, 'is_active' => true]);
        Category::create(['name' => 'بدون صور', 'sort_order' => 1, 'is_active' => true]);

        Image::create([
            'name' => 'واحدة',
            'path' => 'gallery/one.jpg',
            'category_id' => $with->id,
            'is_program' => false,
            'is_proud_of' => false,
        ]);

        $response = $this->getJson('/api/gallery/categories');

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $ids = collect($response->json('data'))->pluck('id')->all();
        $this->assertContains($with->id, $ids);
        $this->assertCount(1, $ids);
    }

    public function test_gallery_images_deep_includes_child_category(): void
    {
        $parent = Category::create(['name' => 'أب', 'sort_order' => 0, 'is_active' => true]);
        $child = Category::create([
            'name' => 'ابن',
            'parent_id' => $parent->id,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $img = Image::create([
            'name' => 'في الابن',
            'path' => 'gallery/c.jpg',
            'category_id' => $child->id,
            'is_program' => false,
            'is_proud_of' => false,
        ]);

        $response = $this->getJson('/api/gallery/images?category='.$parent->id.'&deep=1');

        $response->assertOk();
        $response->assertJsonPath('meta.total', 1);
        $response->assertJsonPath('data.0.id', $img->id);
    }
}
