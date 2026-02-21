<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\QuizCompetition;
use App\Models\QuizQuestion;
use App\Exports\QuizCompetitionExport;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class QuizCompetitionController extends Controller
{
    public function index(): View
    {
        $competitions = QuizCompetition::with(['questions'])
            ->ordered()
            ->get();

        $stats = [
            'total' => QuizCompetition::count(),
            'active' => QuizCompetition::where('is_active', true)->count(),
            'inactive' => QuizCompetition::where('is_active', false)->count(),
            'total_questions' => QuizQuestion::count(),
        ];

        return view('dashboard.quiz-competitions.index', compact('competitions', 'stats'));
    }

    public function create(): View
    {
        return view('dashboard.quiz-competitions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'عنوان المسابقة مطلوب',
            'end_at.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        QuizCompetition::create($validated);

        return redirect()->route('dashboard.quiz-competitions.index')
            ->with('success', 'تم إضافة المسابقة بنجاح');
    }

    public function show(QuizCompetition $quizCompetition): View
    {
        $quizCompetition->load(['questions.choices', 'questions.answers.user', 'questions.winners.user']);

        return view('dashboard.quiz-competitions.show', compact('quizCompetition'));
    }

    public function edit(QuizCompetition $quizCompetition): View
    {
        return view('dashboard.quiz-competitions.edit', compact('quizCompetition'));
    }

    public function update(Request $request, QuizCompetition $quizCompetition): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'عنوان المسابقة مطلوب',
            'end_at.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        $quizCompetition->update($validated);

        return redirect()->route('dashboard.quiz-competitions.index')
            ->with('success', 'تم تحديث المسابقة بنجاح');
    }

    public function destroy(QuizCompetition $quizCompetition): RedirectResponse
    {
        $quizCompetition->delete();

        return redirect()->route('dashboard.quiz-competitions.index')
            ->with('success', 'تم حذف المسابقة بنجاح');
    }

    public function simulateAnswers(QuizCompetition $quizCompetition): RedirectResponse
    {
        $users = \App\Models\User::all();
        $questions = $quizCompetition->questions;
        $count = 0;

        foreach ($questions as $question) {
            foreach ($users as $user) {
                // Check if user already answered
                if ($question->answers()->where('user_id', $user->id)->exists()) {
                    continue;
                }

                $choices = $question->choices;
                $answerText = '';
                $isCorrect = true; // Default to correct for text answers

                if ($choices->count() > 0) {
                    // Try to pick a correct choice
                    $correctChoices = $choices->where('is_correct', true);
                    
                    if ($correctChoices->isNotEmpty()) {
                        $choice = $correctChoices->random();
                    } else {
                        // Fallback if no correct choice defined
                        $choice = $choices->random();
                    }
                    
                    // For multiple choice, store the choice ID
                    $answerText = (string) $choice->id;
                    $isCorrect = $choice->is_correct;
                } else {
                    $answerText = 'Simulated Answer ' . \Illuminate\Support\Str::random(5);
                    $isCorrect = true;
                }

                \App\Models\QuizAnswer::create([
                    'quiz_question_id' => $question->id,
                    'user_id' => $user->id,
                    'answer' => $answerText,
                    'answer_type' => $question->answer_type,
                    'is_correct' => $isCorrect,
                    'created_at' => now()->subSeconds(rand(0, 300)),
                ]);
                $count++;
            }
        }

        return back()->with('success', "تم محاكاة الإجابات لـ $count مستخدم.");
    }

    /**
     * تصدير بيانات المسابقة بالكامل إلى Excel (أسئلة، إجابات، فائزون).
     */
    public function export(QuizCompetition $quizCompetition): BinaryFileResponse
    {
        $quizCompetition->load(['questions.choices', 'questions.answers.user', 'questions.winners.user']);

        $safeTitle = \Illuminate\Support\Str::slug($quizCompetition->title);
        $filename = sprintf('quiz-competition-%s-%s.xlsx', $safeTitle, now()->format('Y-m-d-His'));

        return Excel::download(new QuizCompetitionExport($quizCompetition), $filename);
    }
}
