<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\SiteContent;
use App\Models\SlideshowImage;
use App\Models\Course;
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

        // جلب آخر 8 صور من المعرض لقسم "ما الجديد"
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

        // جلب نبذة العائلة
        $familyBrief = SiteContent::getContent('family_brief', 'نبذة عن عائلة السريع');

        // جلب قسم ما الجديد
        $whatsNew = SiteContent::getContent('whats_new', 'آخر أخبار عائلة السريع');

        // جلب الدورات النشطة
        $courses = Course::getActiveCourses();

        // جلب برامج السريع
        $programs = Image::getActivePrograms();

        return view('home', [
            'latestImages' => $slideshowImages,
            'latestGalleryImages' => $latestGalleryImages,
            'familyBrief' => $familyBrief,
            'whatsNew' => $whatsNew,
            'courses' => $courses,
            'programs' => $programs,
        ]);
    }
}
