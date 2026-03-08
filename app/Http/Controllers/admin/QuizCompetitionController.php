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
        $sponsors = \App\Models\Sponsor::all();
        return view('dashboard.quiz-competitions.create', compact('sponsors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'reveal_delay_seconds' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'show_draw_only' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'عنوان المسابقة مطلوب',
            'end_at.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['show_draw_only'] = $request->has('show_draw_only');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        $competition = QuizCompetition::create($validated);

        if ($request->has('sponsors')) {
            $competition->sponsors()->sync($request->input('sponsors'));
        }

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
        $sponsors = \App\Models\Sponsor::all();
        $quizCompetition->load('sponsors');
        return view('dashboard.quiz-competitions.edit', compact('quizCompetition', 'sponsors'));
    }

    public function update(Request $request, QuizCompetition $quizCompetition): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'reveal_delay_seconds' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'show_draw_only' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'عنوان المسابقة مطلوب',
            'end_at.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['show_draw_only'] = $request->has('show_draw_only');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        $quizCompetition->update($validated);

        if ($request->has('sponsors')) {
            $quizCompetition->sponsors()->sync($request->input('sponsors'));
        } else {
            $quizCompetition->sponsors()->sync([]);
        }

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
                $selectedChoicesIds = [];

                if ($choices->count() > 0) {
                    if ($question->is_multiple_selections) {
                        $requiredCount = $question->getRequiredCorrectAnswersCount();
                        $correctChoices = $choices->where('is_correct', true);

                        // randomly decide if we want to simulate a fully correct answer or a randomly partially wrong answer
                        $simulatePerfectAnswer = rand(0, 1) == 1;

                        if ($simulatePerfectAnswer && $correctChoices->count() >= $requiredCount) {
                            $picked = $correctChoices->random($requiredCount);
                            $selectedChoicesIds = $picked->pluck('id')->toArray();
                            $isCorrect = true;
                        } else {
                            // Pick random choices equal to required count (mix of correct/incorrect)
                            // Make sure we have enough choices
                            if ($choices->count() >= $requiredCount) {
                                $picked = $choices->random($requiredCount);
                                $selectedChoicesIds = $picked->pluck('id')->toArray();

                                // Check if this random mix happens to be perfectly correct
                                $correctIds = $correctChoices->pluck('id')->toArray();
                                $allCorrect = true;
                                foreach ($selectedChoicesIds as $chkId) {
                                    if (!in_array($chkId, $correctIds)) {
                                        $allCorrect = false;
                                        break;
                                    }
                                }
                                $isCorrect = $allCorrect;
                            } else {
                                $selectedChoicesIds = $choices->pluck('id')->toArray();
                                $isCorrect = false;
                            }
                        }

                        $answerText = json_encode($selectedChoicesIds);

                    } else {
                        // Single Choice Simulation
                        $correctChoices = $choices->where('is_correct', true);

                        if ($correctChoices->isNotEmpty()) {
                            // 80% chance to be correct
                            if (rand(1, 100) <= 80) {
                                $choice = $correctChoices->random();
                                $isCorrect = true;
                            } else {
                                $choice = $choices->random();
                                $isCorrect = (bool) $choice->is_correct;
                            }
                        } else {
                            // Fallback if no correct choice defined
                            $choice = $choices->random();
                            $isCorrect = (bool) $choice->is_correct;
                        }

                        $answerText = (string) $choice->id;
                    }
                } else {
                    $answerText = 'Simulated Answer ' . \Illuminate\Support\Str::random(5);
                    $isCorrect = true;
                }

                $answerModel = \App\Models\QuizAnswer::create([
                    'quiz_question_id' => $question->id,
                    'user_id' => $user->id,
                    'answer' => $answerText,
                    'answer_type' => $question->answer_type,
                    'is_correct' => $isCorrect,
                    'created_at' => now()->subSeconds(rand(0, 300)),
                ]);
                // `selectedChoices()` is a HasMany relationship, so we loop and create records instead of `attach()`
                if (!empty($selectedChoicesIds) && $question->is_multiple_selections) {
                    foreach ($selectedChoicesIds as $choiceId) {
                        $answerModel->selectedChoices()->create([
                            'quiz_question_choice_id' => $choiceId,
                        ]);
                    }
                }

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
