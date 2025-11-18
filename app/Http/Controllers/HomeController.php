<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\SiteContent;
use App\Models\SlideshowImage;
use App\Models\HomeGalleryImage;
use App\Models\Course;
use App\Models\FamilyCouncil;
use App\Models\Person;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // جلب الأشخاص الذين ولدوا في مثل هذا اليوم (نفس اليوم والشهر)
        $today = now();
        $birthdayPersons = Person::whereNotNull('birth_date')
            ->whereNull('death_date') // الأحياء فقط
            ->where('from_outside_the_family', false) // من داخل العائلة فقط
            ->whereRaw('DAY(birth_date) = ?', [$today->day])
            ->whereRaw('MONTH(birth_date) = ?', [$today->month])
            ->with(['parent:id,first_name,gender,parent_id', 'parent.parent:id,first_name,gender,parent_id'])
            ->orderBy('birth_date')
            ->take(12)
            ->get();

        // جلب آخر الخريجين من أصحاب المقالات (آخر 12 مقال مع فئة التخرج)
        $latestGraduates = Article::where('status', 'published')
            ->whereNotNull('person_id')
            ->whereNotNull('category_id')
            ->with(['person:id,first_name,last_name,photo_url,parent_id', 'person.parent:id,first_name,gender,parent_id', 'person.parent.parent:id,first_name,gender,parent_id', 'category:id,name'])
            ->latest('created_at')
            ->take(12)
            ->get();

        return view('home', [
            'latestImages' => $slideshowImages,
            'latestGalleryImages' => $latestGalleryImages,
            'familyBrief' => $familyBrief,
            'whatsNew' => $whatsNew,
            'courses' => $courses,
            'programs' => $programs,
            'councils' => $councils,
            'birthdayPersons' => $birthdayPersons,
            'latestGraduates' => $latestGraduates,
        ]);
    }
}
