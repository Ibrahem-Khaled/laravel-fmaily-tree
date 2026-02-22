<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\QuizCompetition;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionChoice;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuizQuestionController extends Controller
{
    public function create(QuizCompetition $quizCompetition): View
    {
        return view('dashboard.quiz-questions.create', compact('quizCompetition'));
    }

    public function store(Request $request, QuizCompetition $quizCompetition): RedirectResponse
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'description' => 'nullable|string',
            'answer_type' => 'required|in:multiple_choice,custom_text',
            'winners_count' => 'required|integer|min:1',
            'display_order' => 'nullable|integer|min:0',
            'choices' => 'required_if:answer_type,multiple_choice|array',
            'choices.*.text' => 'required_with:choices|string',
            'choices.*.is_correct' => 'nullable|boolean',
        ], [
            'question_text.required' => 'نص السؤال مطلوب',
            'answer_type.required' => 'نوع الإجابة مطلوب',
            'winners_count.required' => 'عدد الفائزين مطلوب',
            'winners_count.min' => 'عدد الفائزين يجب أن يكون على الأقل 1',
        ]);

        $question = $quizCompetition->questions()->create([
            'question_text' => $validated['question_text'],
            'description' => $validated['description'] ?? null,
            'answer_type' => $validated['answer_type'],
            'winners_count' => $validated['winners_count'],
            'display_order' => $validated['display_order'] ?? 0,
        ]);

        if ($validated['answer_type'] === 'multiple_choice' && !empty($validated['choices'])) {
            $choices = array_filter($validated['choices'], fn($c) => !empty(trim($c['text'] ?? '')));
            foreach ($choices as $choice) {
                $question->choices()->create([
                    'choice_text' => $choice['text'],
                    'is_correct' => !empty($choice['is_correct']),
                ]);
            }
        }

        return redirect()->route('dashboard.quiz-competitions.show', $quizCompetition)
            ->with('success', 'تم إضافة السؤال بنجاح');
    }

    public function edit(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): View
    {
        $quizQuestion->load('choices');

        return view('dashboard.quiz-questions.edit', compact('quizCompetition', 'quizQuestion'));
    }

    public function update(Request $request, QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'description' => 'nullable|string',
            'answer_type' => 'required|in:multiple_choice,custom_text',
            'winners_count' => 'required|integer|min:1',
            'display_order' => 'nullable|integer|min:0',
            'choices' => 'required_if:answer_type,multiple_choice|array',
            'choices.*.text' => 'required_with:choices|string',
            'choices.*.is_correct' => 'nullable|boolean',
        ], [
            'question_text.required' => 'نص السؤال مطلوب',
            'answer_type.required' => 'نوع الإجابة مطلوب',
            'winners_count.required' => 'عدد الفائزين مطلوب',
            'winners_count.min' => 'عدد الفائزين يجب أن يكون على الأقل 1',
        ]);

        $quizQuestion->update([
            'question_text' => $validated['question_text'],
            'description' => $validated['description'] ?? null,
            'answer_type' => $validated['answer_type'],
            'winners_count' => $validated['winners_count'],
            'display_order' => $validated['display_order'] ?? 0,
        ]);

        if ($validated['answer_type'] === 'multiple_choice' && !empty($validated['choices'])) {
            $quizQuestion->choices()->delete();
            $choices = array_filter($validated['choices'], fn($c) => !empty(trim($c['text'] ?? '')));
            foreach ($choices as $choice) {
                $quizQuestion->choices()->create([
                    'choice_text' => $choice['text'],
                    'is_correct' => !empty($choice['is_correct']),
                ]);
            }
        } else {
            $quizQuestion->choices()->delete();
        }

        return redirect()->route('dashboard.quiz-competitions.show', $quizCompetition)
            ->with('success', 'تم تحديث السؤال بنجاح');
    }

    public function destroy(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        $quizQuestion->delete();

        return redirect()->route('dashboard.quiz-competitions.show', $quizCompetition)
            ->with('success', 'تم حذف السؤال بنجاح');
    }

    public function selectWinners(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        if (!$quizQuestion->hasEnded()) {
            return back()->with('error', 'لا يمكن اختيار الفائزين قبل انتهاء فترة الإجابة');
        }

        $count = $quizQuestion->selectRandomWinners();

        return back()->with('success', "تم اختيار {$count} فائز عشوائياً");
    }
}
