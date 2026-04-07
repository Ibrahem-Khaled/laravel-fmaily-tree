<?php

namespace App\Http\Controllers;

use App\Services\HomePageData;

class HomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية الجديدة
     */
    public function index(HomePageData $homePageData)
    {
        $d = $homePageData->build();

        return view('web-site.home', [
            'latestImages' => $d['slides'],
            'latestGalleryImages' => $d['gallery'],
            'familyBrief' => $d['familyBrief'],
            'whatsNew' => $d['whatsNew'],
            'courses' => $d['courses'],
            'programs' => $d['programs'],
            'programCategories' => $d['programCategories'],
            'proudOf' => $d['proudOf'],
            'councils' => $d['councils'],
            'events' => $d['events'],
            'birthdayPersons' => $d['birthdayPersons'],
            'latestGraduates' => $d['latestGraduates'],
            'bachelorTotalCount' => $d['bachelorTotalCount'],
            'masterTotalCount' => $d['masterTotalCount'],
            'phdTotalCount' => $d['phdTotalCount'],
            'importantLinks' => $d['importantLinks'],
            'familyNews' => $d['familyNews'],
            'dynamicSections' => $d['dynamicSections'],
            'quizCompetitions' => $d['quiz']['quizCompetitions'],
            'nextQuizEvent' => $d['quiz']['nextQuizEvent'],
            'activeQuizCompetitions' => $d['quiz']['activeQuizCompetitions'],
        ]);
    }
}
