<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use App\Models\Person;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductStoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // إنشاء الفئات الرئيسية
            $categories = [
                [
                    'name' => 'منتجات غذائية',
                    'description' => 'منتجات غذائية طبيعية ومحلية من الأسر المنتجة',
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'منتجات يدوية',
                    'description' => 'منتجات يدوية مصنوعة بعناية فائقة',
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'منتجات تجميلية',
                    'description' => 'منتجات تجميلية طبيعية وآمنة',
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'ملابس وإكسسوارات',
                    'description' => 'ملابس وإكسسوارات عصرية ومميزة',
                    'is_active' => true,
                    'sort_order' => 4,
                ],
            ];

            $createdCategories = [];
            foreach ($categories as $categoryData) {
                $createdCategories[] = ProductCategory::create($categoryData);
            }

            // إنشاء الفئات الفرعية
            $subcategories = [
                [
                    'product_category_id' => $createdCategories[0]->id,
                    'name' => 'عسل طبيعي',
                    'description' => 'عسل طبيعي خالص من المناحل المحلية',
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'product_category_id' => $createdCategories[0]->id,
                    'name' => 'مربيات',
                    'description' => 'مربيات منزلية بجودة عالية',
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'product_category_id' => $createdCategories[0]->id,
                    'name' => 'تمور',
                    'description' => 'تمور طازجة من المزارع المحلية',
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'product_category_id' => $createdCategories[1]->id,
                    'name' => 'سجاد يدوي',
                    'description' => 'سجاد يدوي بتصاميم تقليدية وعصرية',
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'product_category_id' => $createdCategories[1]->id,
                    'name' => 'أعمال فخارية',
                    'description' => 'أواني فخارية يدوية الصنع',
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'product_category_id' => $createdCategories[2]->id,
                    'name' => 'صابون طبيعي',
                    'description' => 'صابون طبيعي بدون مواد كيميائية',
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'product_category_id' => $createdCategories[2]->id,
                    'name' => 'زيوت طبيعية',
                    'description' => 'زيوت طبيعية للعناية بالبشرة والشعر',
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'product_category_id' => $createdCategories[3]->id,
                    'name' => 'عبايات',
                    'description' => 'عبايات عصرية وأنيقة',
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'product_category_id' => $createdCategories[3]->id,
                    'name' => 'حقائب يد',
                    'description' => 'حقائب يد بتصاميم مميزة',
                    'is_active' => true,
                    'sort_order' => 2,
                ],
            ];

            $createdSubcategories = [];
            foreach ($subcategories as $subcategoryData) {
                $createdSubcategories[] = ProductSubcategory::create($subcategoryData);
            }

            // إنشاء المنتجات
            $products = [
                // منتجات غذائية - عسل
                [
                    'name' => 'عسل سدر طبيعي',
                    'description' => 'عسل سدر طبيعي خالص من جبال عسير، يتميز بنكهة مميزة وفوائد صحية عديدة',
                    'features' => ['طبيعي 100%', 'من جبال عسير', 'غير مبستر', 'غني بالفيتامينات'],
                    'price' => 150.00,
                    'contact_phone' => '0501234567',
                    'contact_whatsapp' => '0501234567',
                    'contact_email' => 'honey@example.com',
                    'contact_instagram' => '@natural_honey',
                    'product_category_id' => $createdCategories[0]->id,
                    'product_subcategory_id' => $createdSubcategories[0]->id,
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'عسل طلح أصلي',
                    'description' => 'عسل طلح أصلي من المناحل المحلية، يتميز بلونه الذهبي وطعمه الحلو',
                    'features' => ['أصلي 100%', 'من المناحل المحلية', 'جودة عالية'],
                    'price' => 120.00,
                    'contact_phone' => '0502345678',
                    'contact_whatsapp' => '0502345678',
                    'product_category_id' => $createdCategories[0]->id,
                    'product_subcategory_id' => $createdSubcategories[0]->id,
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                // منتجات غذائية - مربيات
                [
                    'name' => 'مربى التوت البري',
                    'description' => 'مربى التوت البري منزلي الصنع، بدون مواد حافظة، طعم طبيعي لذيذ',
                    'features' => ['منزلي الصنع', 'بدون مواد حافظة', 'طعم طبيعي'],
                    'price' => 45.00,
                    'contact_phone' => '0503456789',
                    'contact_whatsapp' => '0503456789',
                    'contact_instagram' => '@homemade_jam',
                    'product_category_id' => $createdCategories[0]->id,
                    'product_subcategory_id' => $createdSubcategories[1]->id,
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'مربى المشمش',
                    'description' => 'مربى المشمش الطازج، مصنوع من فواكه طبيعية بجودة عالية',
                    'features' => ['من فواكه طازجة', 'طعم أصلي', 'عبوة زجاجية'],
                    'price' => 40.00,
                    'contact_phone' => '0503456789',
                    'contact_whatsapp' => '0503456789',
                    'product_category_id' => $createdCategories[0]->id,
                    'product_subcategory_id' => $createdSubcategories[1]->id,
                    'is_active' => true,
                    'sort_order' => 4,
                ],
                // منتجات غذائية - تمور
                [
                    'name' => 'تمر سكري فاخر',
                    'description' => 'تمر سكري فاخر من المزارع المحلية، طازج وطبيعي',
                    'features' => ['فاخر', 'طازج', 'من المزارع المحلية'],
                    'price' => 80.00,
                    'contact_phone' => '0504567890',
                    'contact_email' => 'dates@example.com',
                    'product_category_id' => $createdCategories[0]->id,
                    'product_subcategory_id' => $createdSubcategories[2]->id,
                    'is_active' => true,
                    'sort_order' => 5,
                ],
                // منتجات يدوية - سجاد
                [
                    'name' => 'سجاد يدوي تقليدي',
                    'description' => 'سجاد يدوي بتصميم تقليدي أصيل، مصنوع من الصوف الطبيعي',
                    'features' => ['يدوي الصنع', 'صوف طبيعي', 'تصميم تقليدي', 'متين'],
                    'price' => 1200.00,
                    'contact_phone' => '0505678901',
                    'contact_whatsapp' => '0505678901',
                    'contact_instagram' => '@handmade_rugs',
                    'contact_facebook' => 'https://facebook.com/handmade-rugs',
                    'product_category_id' => $createdCategories[1]->id,
                    'product_subcategory_id' => $createdSubcategories[3]->id,
                    'is_active' => true,
                    'sort_order' => 6,
                ],
                [
                    'name' => 'سجاد عصري صغير',
                    'description' => 'سجاد يدوي بتصميم عصري، مناسب للغرف الصغيرة',
                    'features' => ['تصميم عصري', 'مقاسات مختلفة', 'ألوان متنوعة'],
                    'price' => 600.00,
                    'contact_phone' => '0505678901',
                    'contact_whatsapp' => '0505678901',
                    'product_category_id' => $createdCategories[1]->id,
                    'product_subcategory_id' => $createdSubcategories[3]->id,
                    'is_active' => true,
                    'sort_order' => 7,
                ],
                // منتجات يدوية - فخار
                [
                    'name' => 'مجموعة أواني فخارية',
                    'description' => 'مجموعة أواني فخارية يدوية الصنع، مناسبة للطهي والتقديم',
                    'features' => ['يدوي الصنع', 'آمن للطعام', 'تصميم تقليدي'],
                    'price' => 250.00,
                    'contact_phone' => '0506789012',
                    'contact_email' => 'pottery@example.com',
                    'product_category_id' => $createdCategories[1]->id,
                    'product_subcategory_id' => $createdSubcategories[4]->id,
                    'is_active' => true,
                    'sort_order' => 8,
                ],
                // منتجات تجميلية - صابون
                [
                    'name' => 'صابون زيت الزيتون',
                    'description' => 'صابون طبيعي من زيت الزيتون البكر، مناسب لجميع أنواع البشرة',
                    'features' => ['طبيعي 100%', 'زيت زيتون بكر', 'مناسب لجميع البشرة', 'بدون مواد كيميائية'],
                    'price' => 35.00,
                    'contact_phone' => '0507890123',
                    'contact_whatsapp' => '0507890123',
                    'contact_instagram' => '@natural_soap',
                    'product_category_id' => $createdCategories[2]->id,
                    'product_subcategory_id' => $createdSubcategories[5]->id,
                    'is_active' => true,
                    'sort_order' => 9,
                ],
                [
                    'name' => 'صابون العسل واللبن',
                    'description' => 'صابون طبيعي من العسل واللبن، يرطب البشرة ويمنحها نعومة فائقة',
                    'features' => ['مغذي للبشرة', 'يرطب', 'رائحة عطرة'],
                    'price' => 40.00,
                    'contact_phone' => '0507890123',
                    'contact_whatsapp' => '0507890123',
                    'product_category_id' => $createdCategories[2]->id,
                    'product_subcategory_id' => $createdSubcategories[5]->id,
                    'is_active' => true,
                    'sort_order' => 10,
                ],
                // منتجات تجميلية - زيوت
                [
                    'name' => 'زيت الأرغان المغربي',
                    'description' => 'زيت أرغان طبيعي من المغرب، للعناية بالبشرة والشعر',
                    'features' => ['طبيعي 100%', 'من المغرب', 'للبشرة والشعر', 'مغذي'],
                    'price' => 90.00,
                    'contact_phone' => '0508901234',
                    'contact_email' => 'oils@example.com',
                    'contact_instagram' => '@natural_oils',
                    'product_category_id' => $createdCategories[2]->id,
                    'product_subcategory_id' => $createdSubcategories[6]->id,
                    'is_active' => true,
                    'sort_order' => 11,
                ],
                [
                    'name' => 'زيت جوز الهند البكر',
                    'description' => 'زيت جوز الهند البكر الطبيعي، متعدد الاستخدامات',
                    'features' => ['بكر', 'طبيعي', 'متعدد الاستخدامات'],
                    'price' => 55.00,
                    'contact_phone' => '0508901234',
                    'contact_whatsapp' => '0508901234',
                    'product_category_id' => $createdCategories[2]->id,
                    'product_subcategory_id' => $createdSubcategories[6]->id,
                    'is_active' => true,
                    'sort_order' => 12,
                ],
                // ملابس - عبايات
                [
                    'name' => 'عباية سوداء كلاسيكية',
                    'description' => 'عباية سوداء بتصميم كلاسيكي أنيق، مناسبة للمناسبات',
                    'features' => ['تصميم أنيق', 'قمة عالية', 'أقمشة فاخرة'],
                    'price' => 450.00,
                    'contact_phone' => '0509012345',
                    'contact_whatsapp' => '0509012345',
                    'contact_instagram' => '@elegant_abayas',
                    'product_category_id' => $createdCategories[3]->id,
                    'product_subcategory_id' => $createdSubcategories[7]->id,
                    'is_active' => true,
                    'sort_order' => 13,
                ],
                [
                    'name' => 'عباية مطرزة',
                    'description' => 'عباية مطرزة بتطريز يدوي فاخر، تصميم عصري ومميز',
                    'features' => ['تطريز يدوي', 'تصميم عصري', 'ألوان متعددة'],
                    'price' => 650.00,
                    'contact_phone' => '0509012345',
                    'contact_whatsapp' => '0509012345',
                    'product_category_id' => $createdCategories[3]->id,
                    'product_subcategory_id' => $createdSubcategories[7]->id,
                    'is_active' => true,
                    'sort_order' => 14,
                ],
                // ملابس - حقائب
                [
                    'name' => 'حقيبة يد جلدية',
                    'description' => 'حقيبة يد من الجلد الطبيعي، بتصميم عصري وأنيق',
                    'features' => ['جلد طبيعي', 'تصميم عصري', 'متينة', 'ألوان متنوعة'],
                    'price' => 350.00,
                    'contact_phone' => '0500123456',
                    'contact_email' => 'bags@example.com',
                    'contact_instagram' => '@leather_bags',
                    'contact_facebook' => 'https://facebook.com/leather-bags',
                    'product_category_id' => $createdCategories[3]->id,
                    'product_subcategory_id' => $createdSubcategories[8]->id,
                    'is_active' => true,
                    'sort_order' => 15,
                ],
                [
                    'name' => 'حقيبة قماشية مطرزة',
                    'description' => 'حقيبة قماشية بتطريز يدوي، مناسبة للاستخدام اليومي',
                    'features' => ['تطريز يدوي', 'قابلة للغسل', 'تصميم مميز'],
                    'price' => 180.00,
                    'contact_phone' => '0500123456',
                    'contact_whatsapp' => '0500123456',
                    'product_category_id' => $createdCategories[3]->id,
                    'product_subcategory_id' => $createdSubcategories[8]->id,
                    'is_active' => true,
                    'sort_order' => 16,
                ],
            ];

            // الحصول على الأشخاص والأماكن المتاحة (اختياري)
            $persons = Person::all();
            $locations = Location::all();
            
            foreach ($products as $productData) {
                // إضافة owner_id و location_id بشكل عشوائي (50% احتمال لكل منهما)
                if ($persons->count() > 0 && rand(0, 1)) {
                    $productData['owner_id'] = $persons->random()->id;
                }
                
                if ($locations->count() > 0 && rand(0, 1)) {
                    $productData['location_id'] = $locations->random()->id;
                }
                
                Product::create($productData);
            }

            $this->command->info('تم إنشاء ' . count($createdCategories) . ' فئة رئيسية');
            $this->command->info('تم إنشاء ' . count($createdSubcategories) . ' فئة فرعية');
            $this->command->info('تم إنشاء ' . count($products) . ' منتج');
        });
    }
}
