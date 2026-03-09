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
            'answer_type' => 'required|in:multiple_choice,custom_text,ordering',
            'is_multiple_selections' => 'nullable|boolean',
            'winners_count' => 'required|integer|min:1',
            'display_order' => 'nullable|integer|min:0',
            'prize' => 'nullable|array',
            'prize.*' => 'nullable|string',
            'choices' => 'required_if:answer_type,multiple_choice,ordering|array',
            'choices.*.text' => 'nullable|string',
            'choices.*.image' => 'nullable|image|max:2048',
            'choices.*.video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
            'choices.*.is_correct' => 'nullable|boolean',
            'correct_choices' => 'nullable|array', // For multiple selections
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
            'is_multiple_selections' => !empty($validated['is_multiple_selections']),
            'winners_count' => $validated['winners_count'],
            'display_order' => $validated['display_order'] ?? 0,
            'prize' => $validated['prize'] ?? [],
        ]);

        if (in_array($validated['answer_type'], ['multiple_choice', 'ordering']) && !empty($validated['choices'])) {
            $choices = array_filter($validated['choices'], fn($c) => !empty(trim($c['text'] ?? '')));

            $isMultiple = !empty($validated['is_multiple_selections']);
            $isOrdering = $validated['answer_type'] === 'ordering';
            $correctChoicesKeys = $request->input('correct_choices', []);

            foreach ($choices as $index => $choice) {
                $isCorrect = false;
                if ($isOrdering) {
                    $isCorrect = true; // For ordering, all saved choices are part of the correct order sequence
                } elseif ($isMultiple) {
                    $isCorrect = in_array((string) $index, $correctChoicesKeys);
                } else {
                    $isCorrect = !empty($choice['is_correct']);
                }

                $question->choices()->create([
                    'choice_text' => $choice['text'] ?? '',
                    'is_correct' => $isCorrect,
                    'image' => isset($choice['image']) ? $choice['image']->store('quiz/choices/images', 'public') : null,
                    'video' => isset($choice['video']) ? $choice['video']->store('quiz/choices/videos', 'public') : null,
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
            'answer_type' => 'required|in:multiple_choice,custom_text,ordering',
            'is_multiple_selections' => 'nullable|boolean',
            'winners_count' => 'required|integer|min:1',
            'display_order' => 'nullable|integer|min:0',
            'prize' => 'nullable|array',
            'prize.*' => 'nullable|string',
            'choices' => 'required_if:answer_type,multiple_choice,ordering|array',
            'choices.*.text' => 'nullable|string',
            'choices.*.image' => 'nullable|image|max:2048',
            'choices.*.video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
            'choices.*.is_correct' => 'nullable|boolean',
            'correct_choices' => 'nullable|array',
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
            'is_multiple_selections' => !empty($validated['is_multiple_selections']),
            'winners_count' => $validated['winners_count'],
            'display_order' => $validated['display_order'] ?? 0,
            'prize' => $validated['prize'] ?? [],
        ]);

        if (in_array($validated['answer_type'], ['multiple_choice', 'ordering']) && !empty($validated['choices'])) {
            $oldChoices = $quizQuestion->choices->keyBy('choice_text');
            $quizQuestion->choices()->delete();
            $choices = $validated['choices'];

            $isMultiple = !empty($validated['is_multiple_selections']);
            $isOrdering = $validated['answer_type'] === 'ordering';
            $correctChoicesKeys = $request->input('correct_choices', []);

            foreach ($choices as $index => $choice) {
                $isCorrect = false;
                if ($isOrdering) {
                    $isCorrect = true;
                } elseif ($isMultiple) {
                    $isCorrect = in_array((string) $index, $correctChoicesKeys);
                } else {
                    $isCorrect = !empty($choice['is_correct']);
                }

                $oldChoice = $oldChoices->get($choice['text'] ?? '');

                // Handle media
                $imagePath = $oldChoice ? $oldChoice->image : null;
                $videoPath = $oldChoice ? $oldChoice->video : null;

                if (isset($choice['image'])) {
                    if ($imagePath)
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
                    $imagePath = $choice['image']->store('quiz/choices/images', 'public');
                }
                if (isset($choice['video'])) {
                    if ($videoPath)
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($videoPath);
                    $videoPath = $choice['video']->store('quiz/choices/videos', 'public');
                }

                $quizQuestion->choices()->create([
                    'choice_text' => $choice['text'] ?? '',
                    'is_correct' => $isCorrect,
                    'image' => $imagePath,
                    'video' => $videoPath,
                ]);
            }
        } else {
            foreach ($quizQuestion->choices as $choice) {
                if ($choice->image)
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($choice->image);
                if ($choice->video)
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($choice->video);
            }
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

    public function removeWinner(\App\Models\QuizWinner $winner): RedirectResponse
    {
        $questionId = $winner->quiz_question_id;
        $winner->delete();

        // إزالة التخزين المؤقت لتحديث صفحة المسابقة فوراً
        \Illuminate\Support\Facades\Cache::forget('quiz_winner_json_' . $questionId);

        return back()->with('success', 'تم إزالة الفائز بنجاح');
    }

    public function fillWinners(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        if (!$quizQuestion->hasEnded()) {
            return back()->with('error', 'لا يمكن اختيار الفائزين قبل انتهاء فترة الإجابة');
        }

        $count = $quizQuestion->fillVacantWinners();

        if ($count > 0) {
            return back()->with('success', "تم اختيار {$count} فائز إضافي عشوائياً");
        }

        return back()->with('info', "لا يوجد خانات فائزين شاغرة حالياً");
    }

    public function reorderWinners(Request $request, QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        $winnerIds = $request->input('winner_ids', []);

        foreach ($winnerIds as $index => $id) {
            \App\Models\QuizWinner::where('id', $id)
                ->where('quiz_question_id', $quizQuestion->id)
                ->update(['position' => $index + 1]);
        }

        // إزالة التخزين المؤقت
        \Illuminate\Support\Facades\Cache::forget('quiz_winner_json_' . $quizQuestion->id);

        return back()->with('success', 'تم إعادة ترتيب الفائزين بنجاح');
    }
}
