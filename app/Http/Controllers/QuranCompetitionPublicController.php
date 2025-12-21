<?php

namespace App\Http\Controllers;

use App\Models\QuranCompetition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuranCompetitionPublicController extends Controller
{
    /**
     * عرض صفحة مسابقات القرآن الكريم
     */
    public function index(): View
    {
        // جلب جميع المسابقات النشطة مرتبة
        $competitions = QuranCompetition::active()
            ->ordered()
            ->with(['winners.person', 'media'])
            ->get();

        // فصل المسابقات: الحالية (آخر 3 سنوات) والسابقة
        $currentCompetitions = $competitions->take(3);
        $previousCompetitions = $competitions->skip(3);

        return view('quran-competitions.index', compact(
            'currentCompetitions',
            'previousCompetitions'
        ));
    }

    /**
     * عرض تفاصيل مسابقة معينة
     */
    public function show($id): View
    {
        $competition = QuranCompetition::with(['winners.person', 'media'])
            ->findOrFail($id);

        return view('quran-competitions.show', compact('competition'));
    }
}

