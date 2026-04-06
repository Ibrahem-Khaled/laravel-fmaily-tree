<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\Course;
use App\Models\FamilyCouncil;
use App\Models\FamilyEvent;
use App\Models\FamilyNews;
use App\Models\HomeGalleryImage;
use App\Models\HomeSection;
use App\Models\Image;
use App\Models\ImportantLink;
use App\Models\Person;
use App\Models\QuizCompetition;
use App\Models\SiteContent;
use App\Models\SlideshowImage;
use Illuminate\Support\Facades\Auth;

class HomePageData
{
    public function build(): array
    {
        $slideshowImages = SlideshowImage::getActiveSlideshowImages();

        if (Auth::check()) {
            $latestGalleryImages = HomeGalleryImage::with('category:id,name')
                ->orderBy('order')
                ->take(8)
                ->get();
        } else {
            $latestGalleryImages = HomeGalleryImage::getActiveGalleryImages();
        }

        if ($latestGalleryImages->isEmpty()) {
            $latestGalleryImages = Image::whereNotNull('category_id')
                ->whereNotNull('path')
                ->where(function ($query) {
                    $query->whereNull('youtube_url')
                        ->where(function ($q) {
                            $q->where('media_type', 'image')
                                ->orWhereNull('media_type');
                        });
                })
                ->with(['category:id,name'])
                ->latest('created_at')
                ->take(8)
                ->get();
        }

        $familyBriefContent = SiteContent::where('key', 'family_brief')
            ->where('is_active', true)
            ->first();
        $familyBrief = $familyBriefContent && ! empty(trim($familyBriefContent->content))
            ? $familyBriefContent->content
            : null;

        $whatsNew = SiteContent::getContent('whats_new', 'آخر أخبار عائلة السريع');

        $dynamicSections = HomeSection::getActiveSections();
        foreach ($dynamicSections as $section) {
            if ($section->content_source_type) {
                $section->content_source_items = $section->getContentSourceCollection();
            }
        }

        if (Auth::check()) {
            $courses = Course::orderBy('order')->get();
        } else {
            $courses = Course::getActiveCourses();
        }

        if (Auth::check()) {
            $programs = Image::where('is_program', true)
                ->whereNull('program_id')
                ->whereNotNull('path')
                ->where(function ($query) {
                    $query->whereNull('youtube_url')
                        ->where(function ($q) {
                            $q->where('media_type', 'image')
                                ->orWhereNull('media_type');
                        });
                })
                ->orderBy('program_order')
                ->get();
        } else {
            $programs = Image::getActivePrograms();
        }

        if (Auth::check()) {
            $proudOf = Image::where('is_proud_of', true)
                ->whereNotNull('path')
                ->where(function ($query) {
                    $query->whereNull('youtube_url')
                        ->where(function ($q) {
                            $q->where('media_type', 'image')
                                ->orWhereNull('media_type');
                        });
                })
                ->orderBy('proud_of_order')
                ->get();
        } else {
            $proudOf = Image::getActiveProudOf();
        }

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

        if (Auth::check()) {
            $events = FamilyEvent::orderBy('event_date', 'asc')
                ->orderBy('display_order')
                ->get();
        } else {
            $events = FamilyEvent::where('is_active', true)
                ->orderBy('event_date', 'asc')
                ->orderBy('display_order')
                ->get();
        }

        $events = $events->filter(function ($event) {
            return $event->event_date->gte(now()->subHours(15));
        })->values();

        $today = now();
        $birthdayPersons = Person::whereNotNull('birth_date')
            ->where('from_outside_the_family', false)
            ->whereRaw('DAY(birth_date) = ?', [$today->day])
            ->whereRaw('MONTH(birth_date) = ?', [$today->month])
            ->with(['parent:id,first_name,gender,parent_id', 'parent.parent:id,first_name,gender,parent_id'])
            ->orderBy('birth_date')
            ->take(12)
            ->get();

        $bachelorCategoryIds = Category::where(function ($query) {
            $query->where('name', 'like', '%بكالوريوس%')
                ->orWhere('name', 'like', '%Bachelor%')
                ->orWhere('name', 'like', '%Bachelors%')
                ->orWhere('name', 'like', '%ليسانس%');
        })
            ->where('is_active', true)
            ->whereHas('articles')
            ->pluck('id');

        $masterCategoryIds = Category::where(function ($query) {
            $query->where('name', 'like', '%ماجستير%')
                ->orWhere('name', 'like', '%Master%');
        })
            ->where('is_active', true)
            ->whereHas('articles')
            ->pluck('id');

        $phdCategoryIds = Category::where(function ($query) {
            $query->where('name', 'like', '%دكتوراه%')
                ->orWhere('name', 'like', '%PhD%')
                ->orWhere('name', 'like', '%Ph.D%');
        })
            ->where('is_active', true)
            ->whereHas('articles')
            ->pluck('id');

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

        $latestGraduates = collect()
            ->merge($bachelorGraduates->map(function ($article) {
                $article->degree_type = 'bachelor';

                return $article;
            }))
            ->merge($masterGraduates->map(function ($article) {
                $article->degree_type = 'master';

                return $article;
            }))
            ->merge($phdGraduates->map(function ($article) {
                $article->degree_type = 'phd';

                return $article;
            }));

        if (Auth::check()) {
            $importantLinks = ImportantLink::with('media')->orderBy('order')->get();
        } else {
            $importantLinks = ImportantLink::getActiveLinks();
        }

        if (Auth::check()) {
            $familyNews = FamilyNews::with('images')
                ->orderBy('display_order')
                ->orderBy('published_at', 'desc')
                ->take(8)
                ->get();
        } else {
            $familyNews = FamilyNews::getActiveNews(8);
        }

        $quizCompetitions = QuizCompetition::active()->ordered()->with(['questions.choices', 'questions.surveyItems'])->get();
        $nextQuizEvent = QuizCompetition::getNextUpcomingEvent();

        $activeQuizCompetitions = QuizCompetition::active()
            ->whereNotNull('start_at')
            ->whereNotNull('end_at')
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->ordered()
            ->with(['questions.choices', 'questions.surveyItems'])
            ->get();

        return [
            'slides' => $slideshowImages,
            'gallery' => $latestGalleryImages,
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
            'quiz' => [
                'quizCompetitions' => $quizCompetitions,
                'nextQuizEvent' => $nextQuizEvent,
                'activeQuizCompetitions' => $activeQuizCompetitions,
            ],
        ];
    }
}
