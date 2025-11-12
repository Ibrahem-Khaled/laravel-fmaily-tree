<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Models\SlideshowImage;
use App\Models\HomeGalleryImage;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SiteContentController extends Controller
{
    /**
     * عرض صفحة إدارة المحتوى
     */
    public function index()
    {
        $familyBrief = SiteContent::firstOrNew(['key' => 'family_brief']);
        $whatsNew = SiteContent::firstOrNew(['key' => 'whats_new']);
        
        return view('dashboard.site-content.index', compact('familyBrief', 'whatsNew'));
    }

    /**
     * تحديث نبذة العائلة
     */
    public function updateFamilyBrief(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        SiteContent::updateOrCreate(
            ['key' => 'family_brief'],
            [
                'content' => $request->content,
                'is_active' => $request->has('is_active') ? true : false
            ]
        );

        return redirect()->route('dashboard.site-content.index')
            ->with('success', 'تم تحديث نبذة العائلة بنجاح');
    }

    /**
     * تحديث قسم ما الجديد
     */
    public function updateWhatsNew(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        SiteContent::updateOrCreate(
            ['key' => 'whats_new'],
            [
                'content' => $request->content,
                'is_active' => $request->has('is_active') ? true : false
            ]
        );

        return redirect()->route('dashboard.site-content.index')
            ->with('success', 'تم تحديث قسم ما الجديد بنجاح');
    }

    /**
     * عرض صفحة إدارة السلايدشو
     */
    public function slideshow()
    {
        $slideshowImages = SlideshowImage::orderBy('order')->get();
        
        // إحصائيات السلايدشو
        $stats = [
            'total' => SlideshowImage::count(),
            'active' => SlideshowImage::where('is_active', true)->count(),
            'inactive' => SlideshowImage::where('is_active', false)->count(),
            'with_links' => SlideshowImage::whereNotNull('link')->count(),
        ];
        
        return view('dashboard.slideshow.index', compact('slideshowImages', 'stats'));
    }

    /**
     * إضافة صورة للسلايدشو
     */
    public function addSlideshowImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'link' => 'nullable|url|max:500',
        ]);

        // رفع الصورة
        $imagePath = $request->file('image')->store('slideshow', 'public');

        // الحصول على آخر ترتيب
        $lastOrder = SlideshowImage::max('order') ?? 0;

        SlideshowImage::create([
            'image_path' => $imagePath,
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
            'order' => $lastOrder + 1,
            'is_active' => true,
        ]);

        return redirect()->route('dashboard.slideshow.index')
            ->with('success', 'تم إضافة الصورة للسلايدشو بنجاح');
    }

    /**
     * تحديث صورة في السلايدشو
     */
    public function updateSlideshowImage(Request $request, SlideshowImage $slideshowImage)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'link' => 'nullable|url|max:500',
        ]);

        // رفع صورة جديدة إذا تم رفعها
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($slideshowImage->image_path) {
                Storage::disk('public')->delete($slideshowImage->image_path);
            }
            
            $imagePath = $request->file('image')->store('slideshow', 'public');
            $slideshowImage->image_path = $imagePath;
        }

        $slideshowImage->update([
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
        ]);

        return redirect()->route('dashboard.slideshow.index')
            ->with('success', 'تم تحديث الصورة بنجاح');
    }

    /**
     * إعادة ترتيب صور السلايدشو
     */
    public function reorderSlideshow(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:slideshow_images,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $slideshowImageId) {
                SlideshowImage::where('id', $slideshowImageId)
                    ->update(['order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }

    /**
     * إزالة صورة من السلايدشو
     */
    public function removeSlideshowImage(SlideshowImage $slideshowImage)
    {
        // حذف الصورة من التخزين
        if ($slideshowImage->image_path) {
            Storage::disk('public')->delete($slideshowImage->image_path);
        }
        
        $slideshowImage->delete();

        return redirect()->route('dashboard.slideshow.index')
            ->with('success', 'تم إزالة الصورة من السلايدشو بنجاح');
    }

    /**
     * جلب بيانات صورة السلايدشو (للتعديل)
     */
    public function getSlideshowImage(SlideshowImage $slideshowImage)
    {
        return response()->json([
            'title' => $slideshowImage->title,
            'description' => $slideshowImage->description,
            'link' => $slideshowImage->link,
        ]);
    }

    /**
     * تفعيل/تعطيل صورة في السلايدشو
     */
    public function toggleSlideshowImage(SlideshowImage $slideshowImage)
    {
        $slideshowImage->update([
            'is_active' => !$slideshowImage->is_active
        ]);

        return redirect()->route('dashboard.slideshow.index')
            ->with('success', 'تم تحديث حالة الصورة بنجاح');
    }

    /**
     * عرض صفحة إدارة صور الصفحة الرئيسية
     */
    public function homeGallery()
    {
        $galleryImages = HomeGalleryImage::with('category')->orderBy('order')->get();
        
        // إحصائيات
        $stats = [
            'total' => HomeGalleryImage::count(),
            'active' => HomeGalleryImage::where('is_active', true)->count(),
            'inactive' => HomeGalleryImage::where('is_active', false)->count(),
        ];
        
        // جلب الفئات للمنقائمة
        $categories = Category::orderBy('name')->get();
        
        return view('dashboard.home-gallery.index', compact('galleryImages', 'stats', 'categories'));
    }

    /**
     * إضافة صورة لمعرض الصفحة الرئيسية
     */
    public function addHomeGalleryImage(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max per image
            'name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // الحصول على آخر ترتيب
        $lastOrder = HomeGalleryImage::max('order') ?? 0;
        $uploadedCount = 0;

        DB::transaction(function () use ($request, &$lastOrder, &$uploadedCount) {
            // رفع جميع الصور
            foreach ($request->file('images') as $index => $file) {
                $imagePath = $file->store('home-gallery', 'public');
                
                // استخدام الاسم المحدد للصورة الأولى، أو اسم الملف للباقي
                $imageName = ($index === 0 && $request->name) 
                    ? $request->name 
                    : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                HomeGalleryImage::create([
                    'image_path' => $imagePath,
                    'name' => $imageName,
                    'category_id' => $request->category_id,
                    'order' => ++$lastOrder,
                    'is_active' => true,
                ]);
                
                $uploadedCount++;
            }
        });

        $message = $uploadedCount > 1 
            ? "تم إضافة {$uploadedCount} صور بنجاح" 
            : 'تم إضافة الصورة بنجاح';

        return redirect()->route('dashboard.home-gallery.index')
            ->with('success', $message);
    }

    /**
     * تحديث صورة في معرض الصفحة الرئيسية
     */
    public function updateHomeGalleryImage(Request $request, HomeGalleryImage $homeGalleryImage)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // رفع صورة جديدة إذا تم رفعها
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($homeGalleryImage->image_path) {
                Storage::disk('public')->delete($homeGalleryImage->image_path);
            }
            
            $imagePath = $request->file('image')->store('home-gallery', 'public');
            $homeGalleryImage->image_path = $imagePath;
        }

        $homeGalleryImage->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('dashboard.home-gallery.index')
            ->with('success', 'تم تحديث الصورة بنجاح');
    }

    /**
     * إعادة ترتيب صور معرض الصفحة الرئيسية
     */
    public function reorderHomeGallery(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:home_gallery_images,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $galleryImageId) {
                HomeGalleryImage::where('id', $galleryImageId)
                    ->update(['order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }

    /**
     * إزالة صورة من معرض الصفحة الرئيسية
     */
    public function removeHomeGalleryImage(HomeGalleryImage $homeGalleryImage)
    {
        // حذف الصورة من التخزين
        if ($homeGalleryImage->image_path) {
            Storage::disk('public')->delete($homeGalleryImage->image_path);
        }
        
        $homeGalleryImage->delete();

        return redirect()->route('dashboard.home-gallery.index')
            ->with('success', 'تم إزالة الصورة بنجاح');
    }

    /**
     * تفعيل/تعطيل صورة في معرض الصفحة الرئيسية
     */
    public function toggleHomeGalleryImage(HomeGalleryImage $homeGalleryImage)
    {
        $homeGalleryImage->update([
            'is_active' => !$homeGalleryImage->is_active
        ]);

        return redirect()->route('dashboard.home-gallery.index')
            ->with('success', 'تم تحديث حالة الصورة بنجاح');
    }
}
