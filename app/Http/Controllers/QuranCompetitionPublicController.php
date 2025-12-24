<?php

namespace App\Http\Controllers;

use App\Models\QuranCompetition;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuranCompetitionPublicController extends Controller
{
    /**
     * عرض صفحة مسابقات القرآن الكريم
     */
    public function index(Request $request): View
    {
        $query = QuranCompetition::active()
            ->ordered()
            ->with(['winners.person', 'media', 'category']);

        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('hijri_year', 'like', "%{$search}%");
            });
        }

        // الفلترة حسب الفئة
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $competitions = $query->get();

        // فصل المسابقات: الحالية (آخر 3 سنوات) والسابقة
        $currentCompetitions = $competitions->take(3);
        $previousCompetitions = $competitions->skip(3);

        // جلب الفئات للفلتر
        $categories = Category::whereHas('quranCompetitions', function($q) {
            $q->where('is_active', true);
        })->ordered()->active()->get();

        return view('quran-competitions.index', compact(
            'currentCompetitions',
            'previousCompetitions',
            'categories'
        ));
    }

    /**
     * عرض تفاصيل مسابقة معينة
     */
    public function show($id): View
    {
        $competition = QuranCompetition::with(['winners.person', 'media', 'category'])
            ->findOrFail($id);

        return view('quran-competitions.show', compact('competition'));
    }

    /**
     * عرض المسابقات حسب الفئة
     */
    public function showByCategory($categoryId): View
    {
        $category = Category::findOrFail($categoryId);
        
        $competitions = QuranCompetition::where('category_id', $categoryId)
            ->active()
            ->ordered()
            ->with(['winners.person', 'media'])
            ->get();

        // فصل المسابقات: الحالية (آخر 3 سنوات) والسابقة
        $currentCompetitions = $competitions->take(3);
        $previousCompetitions = $competitions->skip(3);

        return view('quran-competitions.category', compact(
            'category',
            'currentCompetitions',
            'previousCompetitions'
        ));
    }
}

