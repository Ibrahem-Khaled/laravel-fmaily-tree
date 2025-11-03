<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\SiteContent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية الجديدة
     */
    public function index()
    {
        // جلب آخر صور المعرض للـ slideshow (آخر 10 صور)
        $latestImages = Image::whereNotNull('category_id')
            ->whereNotNull('path')
            ->with(['category:id,name'])
            ->latest('created_at')
            ->take(10)
            ->get();

        // جلب نبذة العائلة
        $familyBrief = SiteContent::getContent('family_brief', 'نبذة عن عائلة السريع');

        // جلب قسم ما الجديد
        $whatsNew = SiteContent::getContent('whats_new', 'آخر أخبار عائلة السريع');

        // الدورات (4 دورات وهمية - سيتم ربطها بـ API لاحقاً)
        $courses = [
            [
                'id' => 1,
                'title' => 'دورة تعليم القرآن الكريم',
                'description' => 'تعلم القرآن الكريم وحفظه بشكل صحيح',
                'instructor' => 'الشيخ محمد السريع',
                'duration' => '3 أشهر',
                'students' => 25,
                'image' => asset('storage/defaults/course1.jpg'),
            ],
            [
                'id' => 2,
                'title' => 'دورة الفقه الإسلامي',
                'description' => 'دراسة الفقه الإسلامي والأحكام الشرعية',
                'instructor' => 'الشيخ أحمد السريع',
                'duration' => '6 أشهر',
                'students' => 18,
                'image' => asset('storage/defaults/course2.jpg'),
            ],
            [
                'id' => 3,
                'title' => 'دورة اللغة العربية',
                'description' => 'تحسين مهارات اللغة العربية والتحدث',
                'instructor' => 'الأستاذ خالد السريع',
                'duration' => '4 أشهر',
                'students' => 30,
                'image' => asset('storage/defaults/course3.jpg'),
            ],
            [
                'id' => 4,
                'title' => 'دورة البرمجة والتقنية',
                'description' => 'تعلم أساسيات البرمجة والتقنيات الحديثة',
                'instructor' => 'المهندس فهد السريع',
                'duration' => '8 أشهر',
                'students' => 15,
                'image' => asset('storage/defaults/course4.jpg'),
            ],
        ];

        return view('home', [
            'latestImages' => $latestImages,
            'familyBrief' => $familyBrief,
            'whatsNew' => $whatsNew,
            'courses' => $courses,
        ]);
    }
}
