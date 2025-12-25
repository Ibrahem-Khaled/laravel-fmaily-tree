<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuranCategoryController extends Controller
{
    /**
     * عرض صفحة الفئة مع المسابقات
     */
    public function show($id)
    {
        $category = Category::with([
            'quranCompetitions' => function($q) {
                $q->active()->ordered()->with(['winners.person', 'media']);
            },
            'managers.person'
        ])->findOrFail($id);

        $activeCompetitions = $category->quranCompetitions;

        // إذا كانت الفئة تحتوي على مسابقة واحدة فقط، إعادة توجيه مباشرة لصفحة المسابقة
        if ($activeCompetitions->count() === 1) {
            return redirect()->route('quran-competitions.show', $activeCompetitions->first()->id);
        }

        $competitions = $activeCompetitions;

        // فصل المسابقات: الحالية (آخر 3 سنوات) والسابقة
        $currentCompetitions = $competitions->take(3);
        $previousCompetitions = $competitions->skip(3);

        return view('quran-competitions.category-show', compact('category', 'competitions', 'currentCompetitions', 'previousCompetitions'));
    }
}
