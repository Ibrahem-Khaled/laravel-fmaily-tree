<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\SiteContent;
use App\Models\SlideshowImage;
use App\Models\HomeGalleryImage;
use App\Models\Course;
use App\Models\FamilyCouncil;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية الجديدة
     */
    public function index()
    {
        // جلب صور السلايدشو المحددة من لوحة التحكم
        $slideshowImages = SlideshowImage::getActiveSlideshowImages();

        // جلب صور معرض الصفحة الرئيسية (أو آخر 8 صور من المعرض كبديل)
        $latestGalleryImages = HomeGalleryImage::getActiveGalleryImages();
        
        // إذا لم توجد صور في معرض الصفحة الرئيسية، استخدم الصور من المعرض العام
        if ($latestGalleryImages->isEmpty()) {
            $latestGalleryImages = Image::whereNotNull('category_id')
                ->whereNotNull('path')
                ->where(function($query) {
                    $query->whereNull('youtube_url')
                          ->where(function($q) {
                              $q->where('media_type', 'image')
                                ->orWhereNull('media_type');
                          });
                })
                ->with(['category:id,name'])
                ->latest('created_at')
                ->take(8)
                ->get();
        }

        // جلب نبذة العائلة
        $familyBrief = SiteContent::getContent('family_brief', 'نبذة عن عائلة السريع');

        // جلب قسم ما الجديد
        $whatsNew = SiteContent::getContent('whats_new', 'آخر أخبار عائلة السريع');

        // جلب الدورات النشطة
        $courses = Course::getActiveCourses();

        // جلب برامج السريع
        $programs = Image::getActivePrograms();

        // جلب مجالس العائلة النشطة مع الصور
        $councils = FamilyCouncil::where('is_active', true)
            ->with('images')
            ->orderBy('display_order')
            ->get();

        return view('home', [
            'latestImages' => $slideshowImages,
            'latestGalleryImages' => $latestGalleryImages,
            'familyBrief' => $familyBrief,
            'whatsNew' => $whatsNew,
            'courses' => $courses,
            'programs' => $programs,
            'councils' => $councils,
        ]);
    }
}
