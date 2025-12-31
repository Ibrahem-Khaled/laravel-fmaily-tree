<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\SiteContent;
use App\Models\SlideshowImage;
use App\Models\HomeGalleryImage;
use App\Models\HomeSection;
use App\Models\Course;
use App\Models\FamilyCouncil;
use App\Models\FamilyEvent;
use App\Models\Person;
use App\Models\Article;
use App\Models\Category;
use App\Models\ImportantLink;
use App\Models\FamilyNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // إذا كان المستخدم مسجل دخول، اجلب جميع الصور (المفعلة وغير المفعلة)
        if (Auth::check()) {
            $latestGalleryImages = HomeGalleryImage::with('category:id,name')
                ->orderBy('order')
                ->take(8)
                ->get();
        } else {
            $latestGalleryImages = HomeGalleryImage::getActiveGalleryImages();
        }

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

        // جلب نبذة العائلة (يظهر فقط إذا كان هناك محتوى و is_active = true)
        $familyBriefContent = SiteContent::where('key', 'family_brief')
            ->where('is_active', true)
            ->first();
        $familyBrief = $familyBriefContent && !empty(trim($familyBriefContent->content)) 
            ? $familyBriefContent->content 
            : null;

        // جلب قسم ما الجديد
        $whatsNew = SiteContent::getContent('whats_new', 'آخر أخبار عائلة السريع');

        // جلب الأقسام الديناميكية
        $dynamicSections = HomeSection::getActiveSections();

        // جلب الدورات
        // إذا كان المستخدم مسجل دخول، اجلب جميع الدورات (المفعلة وغير المفعلة)
        if (Auth::check()) {
            $courses = Course::orderBy('order')->get();
        } else {
            $courses = Course::getActiveCourses();
        }

        // جلب برامج السريع
        // إذا كان المستخدم مسجل دخول، اجلب جميع البرامج (المفعلة وغير المفعلة)
        // استبعاد البرامج الفرعية (التي لها program_id)
        if (Auth::check()) {
            $programs = Image::where('is_program', true)
                ->whereNull('program_id') // استبعاد البرامج الفرعية
                ->whereNotNull('path')
                ->where(function($query) {
                    $query->whereNull('youtube_url')
                          ->where(function($q) {
                              $q->where('media_type', 'image')
                                ->orWhereNull('media_type');
                          });
                })
                ->orderBy('program_order')
                ->get();
        } else {
            $programs = Image::getActivePrograms();
        }

        // جلب عناصر نفتخر بهم
        // إذا كان المستخدم مسجل دخول، اجلب جميع العناصر (المفعلة وغير المفعلة)
        // وإلا اجلب فقط العناصر المفعلة
        if (Auth::check()) {
            $proudOf = Image::where('is_proud_of', true)
                ->whereNotNull('path')
                ->where(function($query) {
                    $query->whereNull('youtube_url')
                          ->where(function($q) {
                              $q->where('media_type', 'image')
                                ->orWhereNull('media_type');
                          });
                })
                ->orderBy('proud_of_order')
                ->get();
        } else {
            $proudOf = Image::getActiveProudOf();
        }

        // جلب مجالس العائلة مع الصور
        // إذا كان المستخدم مسجل دخول، اجلب جميع المجالس (المفعلة وغير المفعلة)
        if (Auth::check()) {
            $councils = FamilyCouncil::with('images')
                ->orderBy('display_order')
                ->get();
        } else {
            $councils = FamilyCouncil::where('is_active', true)
                ->with('images')
                ->orderBy('display_order')
                ->get();
        }

        // جلب مناسبات العائلة
        // إذا كان المستخدم مسجل دخول، اجلب جميع المناسبات (المفعلة وغير المفعلة)
        if (Auth::check()) {
            $events = FamilyEvent::orderBy('display_order')
                ->orderBy('event_date')
                ->get();
        } else {
            $events = FamilyEvent::where('is_active', true)
                ->orderBy('display_order')
                ->orderBy('event_date')
                ->get();
        }

        // جلب الأشخاص الذين ولدوا في مثل هذا اليوم (نفس اليوم والشهر)
        $today = now();
        $birthdayPersons = Person::whereNotNull('birth_date')
            ->where('from_outside_the_family', false) // من داخل العائلة فقط
            ->whereRaw('DAY(birth_date) = ?', [$today->day])
            ->whereRaw('MONTH(birth_date) = ?', [$today->month])
            ->with(['parent:id,first_name,gender,parent_id', 'parent.parent:id,first_name,gender,parent_id'])
            ->orderBy('birth_date')
            ->take(12)
            ->get();

        // جلب فئات الشهادات بناءً على ID محددة (يجب تحديد الـ IDs الصحيحة من قاعدة البيانات)
        // البحث عن الفئات التي تحتوي على مقالات فقط
        $bachelorCategoryIds = Category::where(function($query) {
            $query->where('name', 'like', '%بكالوريوس%')
                  ->orWhere('name', 'like', '%Bachelor%')
                  ->orWhere('name', 'like', '%Bachelors%')
                  ->orWhere('name', 'like', '%ليسانس%');
        })
        ->where('is_active', true) // فقط الفئات النشطة
        ->whereHas('articles') // فقط الفئات التي تحتوي على مقالات
        ->pluck('id');

        $masterCategoryIds = Category::where(function($query) {
            $query->where('name', 'like', '%ماجستير%')
                  ->orWhere('name', 'like', '%Master%');
        })
        ->where('is_active', true) // فقط الفئات النشطة
        ->whereHas('articles') // فقط الفئات التي تحتوي على مقالات
        ->pluck('id');

        $phdCategoryIds = Category::where(function($query) {
            $query->where('name', 'like', '%دكتوراه%')
                  ->orWhere('name', 'like', '%PhD%')
                  ->orWhere('name', 'like', '%Ph.D%');
        })
        ->where('is_active', true) // فقط الفئات النشطة
        ->whereHas('articles') // فقط الفئات التي تحتوي على مقالات
        ->pluck('id');

        // حساب العدد الكلي لكل فئة (عدد الأشخاص المميزين)
        $bachelorTotalCount = Article::whereIn('status', ['published', 'draft'])
            ->whereNotNull('person_id')
            ->whereIn('category_id', $bachelorCategoryIds)
            ->select('person_id')
            ->distinct()
            ->count('person_id');

        $masterTotalCount = Article::whereIn('status', ['published', 'draft'])
            ->whereNotNull('person_id')
            ->whereIn('category_id', $masterCategoryIds)
            ->select('person_id')
            ->distinct()
            ->count('person_id');

        $phdTotalCount = Article::whereIn('status', ['published', 'draft'])
            ->whereNotNull('person_id')
            ->whereIn('category_id', $phdCategoryIds)
            ->select('person_id')
            ->distinct()
            ->count('person_id');

        // جلب آخر 10 خريجين من كل فئة
        $bachelorGraduates = Article::whereIn('status', ['published', 'draft'])
            ->whereNotNull('person_id')
            ->whereIn('category_id', $bachelorCategoryIds)
            ->with(['person:id,first_name,last_name,photo_url,parent_id,gender', 'person.parent:id,first_name,gender,parent_id', 'person.parent.parent:id,first_name,gender,parent_id', 'category:id,name'])
            ->latest('created_at')
            ->take(10)
            ->get();

        $masterGraduates = Article::whereIn('status', ['published', 'draft'])
            ->whereNotNull('person_id')
            ->whereIn('category_id', $masterCategoryIds)
            ->with(['person:id,first_name,last_name,photo_url,parent_id,gender', 'person.parent:id,first_name,gender,parent_id', 'person.parent.parent:id,first_name,gender,parent_id', 'category:id,name'])
            ->latest('created_at')
            ->take(10)
            ->get();

        $phdGraduates = Article::whereIn('status', ['published', 'draft'])
            ->whereNotNull('person_id')
            ->whereIn('category_id', $phdCategoryIds)
            ->with(['person:id,first_name,last_name,photo_url,parent_id,gender', 'person.parent:id,first_name,gender,parent_id', 'person.parent.parent:id,first_name,gender,parent_id', 'category:id,name'])
            ->latest('created_at')
            ->take(10)
            ->get();

        // دمج جميع الخريجين مع إضافة نوع الشهادة
        $latestGraduates = collect()
            ->merge($bachelorGraduates->map(function($article) {
                $article->degree_type = 'bachelor';
                return $article;
            }))
            ->merge($masterGraduates->map(function($article) {
                $article->degree_type = 'master';
                return $article;
            }))
            ->merge($phdGraduates->map(function($article) {
                $article->degree_type = 'phd';
                return $article;
            }));

        // جلب الروابط المهمة
        // إذا كان المستخدم مسجل دخول، اجلب جميع الروابط (المفعلة وغير المفعلة)
        if (Auth::check()) {
            $importantLinks = ImportantLink::orderBy('order')->get();
        } else {
            $importantLinks = ImportantLink::getActiveLinks();
        }

        // جلب أخبار العائلة
        // إذا كان المستخدم مسجل دخول، اجلب جميع الأخبار (المفعلة وغير المفعلة)
        if (Auth::check()) {
            $familyNews = FamilyNews::with('images')
                ->orderBy('display_order')
                ->orderBy('published_at', 'desc')
                ->take(8)
                ->get();
        } else {
            $familyNews = FamilyNews::getActiveNews(8);
        }

        return view('home', [
            'latestImages' => $slideshowImages,
            'latestGalleryImages' => $latestGalleryImages,
            'familyBrief' => $familyBrief,
            'whatsNew' => $whatsNew,
            'courses' => $courses,
            'programs' => $programs,
            'proudOf' => $proudOf,
            'councils' => $councils,
            'events' => $events,
            'birthdayPersons' => $birthdayPersons,
            'latestGraduates' => $latestGraduates,
            'bachelorTotalCount' => $bachelorTotalCount,
            'masterTotalCount' => $masterTotalCount,
            'phdTotalCount' => $phdTotalCount,
            'importantLinks' => $importantLinks,
            'familyNews' => $familyNews,
            'dynamicSections' => $dynamicSections,
        ]);
    }
}
