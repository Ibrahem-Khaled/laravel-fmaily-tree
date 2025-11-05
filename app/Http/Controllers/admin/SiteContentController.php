<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Models\SlideshowImage;
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
        
        return view('dashboard.slideshow.index', compact('slideshowImages'));
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
}
