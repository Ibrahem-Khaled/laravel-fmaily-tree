<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use App\Models\Person;
use App\Models\Location;
use App\Models\ProductMedia;
use App\Models\ProductAvailabilitySlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $subcategoryId = $request->query('subcategory_id');
        $search = $request->query('search');
        $type = $request->query('type', 'all'); // all, rental, normal

        $query = Product::with(['category', 'subcategory', 'owner', 'location']);

        // Filter by type (rental/normal)
        if ($type === 'rental') {
            $query->where('is_rental', true);
        } elseif ($type === 'normal') {
            $query->where('is_rental', false);
        }

        if ($categoryId) {
            $query->where('product_category_id', $categoryId);
        }

        if ($subcategoryId) {
            $query->where('product_subcategory_id', $subcategoryId);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->ordered()->paginate(20)->appends($request->query());
        $categories = ProductCategory::active()->ordered()->get();
        $subcategories = ProductSubcategory::active()->ordered()->get();

        $stats = [
            'total' => Product::count(),
            'active' => Product::active()->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'rental' => Product::where('is_rental', true)->count(),
            'normal' => Product::where('is_rental', false)->count(),
        ];

        return view('dashboard.products.index', compact('products', 'categories', 'subcategories', 'categoryId', 'subcategoryId', 'search', 'stats', 'type'));
    }

    public function create()
    {
        $categories = ProductCategory::active()->ordered()->get();
        // تحميل جميع الفئات الفرعية (نشطة وغير نشطة) لإتاحة الاختيار
        $subcategories = ProductSubcategory::ordered()->get();
        $persons = Person::orderBy('first_name')->orderBy('last_name')->get();
        $locations = Location::ordered()->get();

        return view('dashboard.products.create', compact('categories', 'subcategories', 'persons', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable',
            'price' => 'required|numeric|min:0',
            'contact_phone' => 'nullable|string|max:20',
            'contact_whatsapp' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'contact_instagram' => 'nullable|string|max:255',
            'contact_facebook' => 'nullable|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'location_url' => 'nullable|url|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'product_category_id' => 'required|exists:product_categories,id',
            'product_subcategory_id' => 'nullable|exists:product_subcategories,id',
            'location_id' => 'nullable|exists:locations,id',
            'is_active' => 'boolean',
            'available_all_week' => 'boolean',
            'all_week_start_time' => 'nullable|date_format:H:i',
            'all_week_end_time' => 'nullable|date_format:H:i|after:all_week_start_time',
            'availability_slots' => 'nullable|array',
            'availability_slots.*.day_of_week' => 'required_with:availability_slots|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'availability_slots.*.start_time' => 'required_with:availability_slots|date_format:H:i',
            'availability_slots.*.end_time' => 'required_with:availability_slots|date_format:H:i|after:availability_slots.*.start_time',
            'availability_slots.*.is_active' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_file' => 'nullable|mimetypes:video/mp4,video/mpeg,video/quicktime|max:20480',
            'youtube_url' => 'nullable|url',
        ]);

        $data = $request->only([
            'name', 'description', 'price',
            'contact_phone', 'contact_whatsapp', 'contact_email',
            'contact_instagram', 'contact_facebook', 'website_url', 'location_url',
            'product_category_id', 'product_subcategory_id', 'owner_id', 'location_id', 'is_active', 'is_rental'
        ]);

        // معالجة المميزات من textarea
        if ($request->has('features') && is_array($request->features)) {
            $features = array_filter(array_map('trim', $request->features));
            $data['features'] = !empty($features) ? $features : null;
        } elseif ($request->has('features') && is_string($request->features)) {
            // إذا كانت نصاً من textarea
            $features = array_filter(array_map('trim', explode("\n", $request->features)));
            $data['features'] = !empty($features) ? $features : null;
        }

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        $lastOrder = Product::max('sort_order') ?? 0;
        $data['sort_order'] = $lastOrder + 1;
        $data['is_active'] = $request->input('is_active', 0) == 1;
        $data['is_rental'] = $request->input('is_rental', 0) == 1;
        $data['available_all_week'] = $request->input('available_all_week', 0) == 1;

        $product = Product::create($data);

        // معالجة المواعيد المحددة (فقط إذا لم يكن متاح طوال الأسبوع)
        if (!$data['available_all_week'] && $request->has('availability_slots')) {
            $sortOrder = 0;
            foreach ($request->availability_slots as $slot) {
                if (!empty($slot['day_of_week']) && !empty($slot['start_time']) && !empty($slot['end_time'])) {
                    $product->availabilitySlots()->create([
                        'day_of_week' => $slot['day_of_week'],
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                        'is_active' => isset($slot['is_active']) ? 1 : 0,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

        // معالجة الصور الإضافية
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/gallery', 'public');
                $product->media()->create([
                    'media_type' => 'image',
                    'file_path' => $path,
                ]);
            }
        }

        // معالجة الفيديو المحلي
        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('products/videos', 'public');
            $product->media()->create([
                'media_type' => 'video',
                'file_path' => $path,
            ]);
        }

        // معالجة يوتيوب
        if ($request->filled('youtube_url')) {
            $product->media()->create([
                'media_type' => 'youtube',
                'youtube_url' => $request->youtube_url,
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::active()->ordered()->get();
        // تحميل جميع الفئات الفرعية (نشطة وغير نشطة) لإتاحة الاختيار
        $subcategories = ProductSubcategory::ordered()->get();
        $persons = Person::orderBy('first_name')->orderBy('last_name')->get();
        $locations = Location::ordered()->get();
        
        // تحميل المواعيد المحددة
        $product->load('availabilitySlots');

        return view('dashboard.products.edit', compact('product', 'categories', 'subcategories', 'persons', 'locations'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable',
            'price' => 'required|numeric|min:0',
            'contact_phone' => 'nullable|string|max:20',
            'contact_whatsapp' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'contact_instagram' => 'nullable|string|max:255',
            'contact_facebook' => 'nullable|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'location_url' => 'nullable|url|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'product_category_id' => 'required|exists:product_categories,id',
            'product_subcategory_id' => [
                'nullable',
                Rule::exists('product_subcategories', 'id')->where(function ($query) use ($request) {
                    return $query->where('product_category_id', $request->product_category_id);
                }),
            ],
            'owner_id' => 'nullable|exists:persons,id',
            'location_id' => 'nullable|exists:locations,id',
            'is_active' => 'boolean',
            'is_rental' => 'boolean',
            'available_all_week' => 'boolean',
            'all_week_start_time' => 'nullable|date_format:H:i',
            'all_week_end_time' => 'nullable|date_format:H:i|after:all_week_start_time',
            'availability_slots' => 'nullable|array',
            'availability_slots.*.day_of_week' => 'required_with:availability_slots|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'availability_slots.*.start_time' => 'required_with:availability_slots|date_format:H:i',
            'availability_slots.*.end_time' => 'required_with:availability_slots|date_format:H:i|after:availability_slots.*.start_time',
            'availability_slots.*.is_active' => 'boolean',
            'delete_slots' => 'nullable|array',
            'delete_slots.*' => 'exists:product_availability_slots,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_file' => 'nullable|mimetypes:video/mp4,video/mpeg,video/quicktime|max:20480',
            'youtube_url' => 'nullable|url',
            'delete_media' => 'nullable|array',
            'delete_media.*' => 'exists:product_media,id',
        ]);

        $data = $request->only([
            'name', 'description', 'price',
            'contact_phone', 'contact_whatsapp', 'contact_email',
            'contact_instagram', 'contact_facebook', 'website_url', 'location_url',
            'product_category_id', 'product_subcategory_id', 'owner_id', 'location_id', 
            'is_active', 'is_rental', 'available_all_week', 'all_week_start_time', 'all_week_end_time'
        ]);

        // معالجة المميزات من textarea
        if ($request->has('features') && is_array($request->features)) {
            $features = array_filter(array_map('trim', $request->features));
            $data['features'] = !empty($features) ? $features : null;
        } elseif ($request->has('features') && is_string($request->features)) {
            // إذا كانت نصاً من textarea
            $features = array_filter(array_map('trim', explode("\n", $request->features)));
            $data['features'] = !empty($features) ? $features : null;
        }

        if ($request->hasFile('main_image')) {
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        $data['is_active'] = $request->input('is_active', 0) == 1;
        $data['is_rental'] = $request->input('is_rental', 0) == 1;
        $data['available_all_week'] = $request->input('available_all_week', 0) == 1;

        // إعادة تعيين الفئة الفرعية إذا تم تغيير الفئة الرئيسية والفئة الفرعية الحالية لا تتبع الفئة الجديدة
        if ($request->has('product_category_id') && $request->product_category_id != $product->product_category_id) {
            if ($request->has('product_subcategory_id') && $request->product_subcategory_id) {
                $subcategory = ProductSubcategory::find($request->product_subcategory_id);
                if (!$subcategory || $subcategory->product_category_id != $request->product_category_id) {
                    $data['product_subcategory_id'] = null;
                }
            } else {
                $data['product_subcategory_id'] = null;
            }
        }

        $product->update($data);

        // حذف الوسائط المختارة
        if ($request->has('delete_media')) {
            ProductMedia::whereIn('id', $request->delete_media)->where('product_id', $product->id)->each(function($media) {
                $media->delete();
            });
        }

        // إضافة صور جديدة
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/gallery', 'public');
                $product->media()->create([
                    'media_type' => 'image',
                    'file_path' => $path,
                ]);
            }
        }

        // معالجة الفيديو (تحديث أو إضافة)
        if ($request->hasFile('video_file')) {
            // حذف الفيديو القديم إذا كان موجوداً
            $product->media()->where('media_type', 'video')->delete();
            
            $path = $request->file('video_file')->store('products/videos', 'public');
            $product->media()->create([
                'media_type' => 'video',
                'file_path' => $path,
            ]);
        }

        // معالجة يوتيوب (تحديث أو إضافة)
        if ($request->filled('youtube_url')) {
            // تحديث الرابط إذا كان موجوداً أو إنشاء جديد
            $product->media()->updateOrCreate(
                ['product_id' => $product->id, 'media_type' => 'youtube'],
                ['youtube_url' => $request->youtube_url]
            );
        } elseif ($request->has('youtube_url') && empty($request->youtube_url)) {
            // إذا تم مسح الرابط، احذفه من قاعدة البيانات
            $product->media()->where('media_type', 'youtube')->delete();
        }

        // معالجة المواعيد
        // حذف المواعيد المحددة
        if ($request->has('delete_slots')) {
            ProductAvailabilitySlot::whereIn('id', $request->delete_slots)
                ->where('product_id', $product->id)
                ->delete();
        }

        // إذا كان متاح طوال الأسبوع، احذف جميع المواعيد المحددة
        if ($data['available_all_week']) {
            $product->availabilitySlots()->delete();
        } elseif ($request->has('availability_slots')) {
            // تحديث أو إضافة المواعيد المحددة
            $existingSlotIds = [];
            $sortOrder = 0;
            
            foreach ($request->availability_slots as $slot) {
                if (!empty($slot['day_of_week']) && !empty($slot['start_time']) && !empty($slot['end_time'])) {
                    if (isset($slot['id']) && $slot['id']) {
                        // تحديث موعد موجود
                        $existingSlot = ProductAvailabilitySlot::where('id', $slot['id'])
                            ->where('product_id', $product->id)
                            ->first();
                        
                        if ($existingSlot) {
                            $existingSlot->update([
                                'day_of_week' => $slot['day_of_week'],
                                'start_time' => $slot['start_time'],
                                'end_time' => $slot['end_time'],
                                'is_active' => isset($slot['is_active']) ? 1 : 0,
                                'sort_order' => $sortOrder++,
                            ]);
                            $existingSlotIds[] = $existingSlot->id;
                        }
                    } else {
                        // إضافة موعد جديد
                        $newSlot = $product->availabilitySlots()->create([
                            'day_of_week' => $slot['day_of_week'],
                            'start_time' => $slot['start_time'],
                            'end_time' => $slot['end_time'],
                            'is_active' => isset($slot['is_active']) ? 1 : 0,
                            'sort_order' => $sortOrder++,
                        ]);
                        $existingSlotIds[] = $newSlot->id;
                    }
                }
            }
            
            // حذف المواعيد التي لم تعد موجودة
            $product->availabilitySlots()
                ->whereNotIn('id', $existingSlotIds)
                ->delete();
        }

        return redirect()->route('products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }

    public function toggle(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', $product->is_active ? 'تم تفعيل المنتج' : 'تم إلغاء تفعيل المنتج');
    }
}
