<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.password_protection_enabled' => false]);
    }

    public function test_store_index_returns_categories_and_owners_with_empty_products_without_filters(): void
    {
        $category = ProductCategory::create([
            'name' => 'غذائية',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        Product::create([
            'name' => 'منتج',
            'price' => 10,
            'product_category_id' => $category->id,
            'is_active' => true,
            'is_rental' => false,
        ]);

        $response = $this->getJson('/api/store');

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('products.meta.total', 0);
        $response->assertJsonCount(0, 'products.data');
        $this->assertNotEmpty($response->json('categories'));
    }

    public function test_store_products_returns_empty_without_filters(): void
    {
        $category = ProductCategory::create([
            'name' => 'فئة',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        Product::create([
            'name' => 'منتج',
            'price' => 5,
            'product_category_id' => $category->id,
            'is_active' => true,
            'is_rental' => false,
        ]);

        $this->getJson('/api/store/products')
            ->assertOk()
            ->assertJsonPath('meta.total', 0)
            ->assertJsonCount(0, 'data');
    }

    public function test_store_products_filters_by_category_and_excludes_rental_and_inactive(): void
    {
        $category = ProductCategory::create([
            'name' => 'فئة',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $other = ProductCategory::create([
            'name' => 'أخرى',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $visible = Product::create([
            'name' => 'ظاهر',
            'price' => 20,
            'product_category_id' => $category->id,
            'is_active' => true,
            'is_rental' => false,
            'sort_order' => 0,
        ]);

        Product::create([
            'name' => 'إيجار',
            'price' => 30,
            'product_category_id' => $category->id,
            'is_active' => true,
            'is_rental' => true,
        ]);

        Product::create([
            'name' => 'غير نشط',
            'price' => 40,
            'product_category_id' => $category->id,
            'is_active' => false,
            'is_rental' => false,
        ]);

        Product::create([
            'name' => 'فئة أخرى',
            'price' => 50,
            'product_category_id' => $other->id,
            'is_active' => true,
            'is_rental' => false,
        ]);

        $response = $this->getJson('/api/store/products?category='.$category->id);

        $response->assertOk();
        $response->assertJsonPath('meta.total', 1);
        $response->assertJsonPath('data.0.id', $visible->id);
    }

    public function test_store_products_pagination(): void
    {
        $category = ProductCategory::create([
            'name' => 'فئة',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        for ($i = 0; $i < 15; $i++) {
            Product::create([
                'name' => "منتج {$i}",
                'price' => $i + 1,
                'product_category_id' => $category->id,
                'is_active' => true,
                'is_rental' => false,
            ]);
        }

        $first = $this->getJson('/api/store/products?category='.$category->id.'&per_page=10&page=1');
        $first->assertOk();
        $first->assertJsonPath('meta.total', 15);
        $first->assertJsonPath('meta.per_page', 10);
        $first->assertJsonPath('meta.last_page', 2);
        $first->assertJsonCount(10, 'data');

        $second = $this->getJson('/api/store/products?category='.$category->id.'&per_page=10&page=2');
        $second->assertOk();
        $second->assertJsonCount(5, 'data');
    }

    public function test_store_product_show_returns_404_for_inactive_or_rental(): void
    {
        $category = ProductCategory::create([
            'name' => 'فئة',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $inactive = Product::create([
            'name' => 'غير نشط',
            'price' => 1,
            'product_category_id' => $category->id,
            'is_active' => false,
            'is_rental' => false,
        ]);

        $rental = Product::create([
            'name' => 'إيجار',
            'price' => 2,
            'product_category_id' => $category->id,
            'is_active' => true,
            'is_rental' => true,
        ]);

        $this->getJson('/api/store/products/'.$inactive->id)
            ->assertNotFound()
            ->assertJsonPath('success', false);

        $this->getJson('/api/store/products/'.$rental->id)
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_store_product_show_includes_related_products(): void
    {
        $category = ProductCategory::create([
            'name' => 'فئة',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $main = Product::create([
            'name' => 'رئيسي',
            'price' => 100,
            'product_category_id' => $category->id,
            'is_active' => true,
            'is_rental' => false,
            'sort_order' => 0,
        ]);

        for ($i = 0; $i < 6; $i++) {
            Product::create([
                'name' => "مشابه {$i}",
                'price' => 10 + $i,
                'product_category_id' => $category->id,
                'is_active' => true,
                'is_rental' => false,
                'sort_order' => $i + 1,
            ]);
        }

        $response = $this->getJson('/api/store/products/'.$main->id);

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.id', $main->id);
        $this->assertCount(4, $response->json('related_products'));
    }

    public function test_store_category_returns_404_when_inactive(): void
    {
        $inactive = ProductCategory::create([
            'name' => 'مخفية',
            'is_active' => false,
            'sort_order' => 0,
        ]);

        $this->getJson('/api/store/categories/'.$inactive->id)
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_store_category_returns_products(): void
    {
        $category = ProductCategory::create([
            'name' => 'فئة',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        ProductSubcategory::create([
            'name' => 'فرعية',
            'product_category_id' => $category->id,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $product = Product::create([
            'name' => 'في الفئة',
            'price' => 25,
            'product_category_id' => $category->id,
            'is_active' => true,
            'is_rental' => false,
        ]);

        $response = $this->getJson('/api/store/categories/'.$category->id);

        $response->assertOk();
        $response->assertJsonPath('products.meta.total', 1);
        $response->assertJsonPath('products.data.0.id', $product->id);
        $this->assertCount(1, $response->json('subcategories'));
    }

    public function test_store_index_includes_owners_with_product_counts(): void
    {
        $category = ProductCategory::create([
            'name' => 'فئة',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $person = Person::factory()->create();

        Product::create([
            'name' => 'منتج 1',
            'price' => 10,
            'product_category_id' => $category->id,
            'owner_id' => $person->id,
            'is_active' => true,
            'is_rental' => false,
        ]);

        Product::create([
            'name' => 'منتج 2',
            'price' => 20,
            'product_category_id' => $category->id,
            'owner_id' => $person->id,
            'is_active' => true,
            'is_rental' => false,
        ]);

        $response = $this->getJson('/api/store');

        $response->assertOk();
        $owner = collect($response->json('owners'))->firstWhere('id', $person->id);
        $this->assertNotNull($owner);
        $this->assertSame(2, $owner['products_count']);
    }

    public function test_store_products_filter_by_person(): void
    {
        $category = ProductCategory::create([
            'name' => 'فئة',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $owner = Person::factory()->create();
        $other = Person::factory()->create();

        $mine = Product::create([
            'name' => 'ملكي',
            'price' => 10,
            'product_category_id' => $category->id,
            'owner_id' => $owner->id,
            'is_active' => true,
            'is_rental' => false,
        ]);

        Product::create([
            'name' => 'لآخر',
            'price' => 20,
            'product_category_id' => $category->id,
            'owner_id' => $other->id,
            'is_active' => true,
            'is_rental' => false,
        ]);

        $this->getJson('/api/store/products?person='.$owner->id)
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.id', $mine->id);
    }
}
