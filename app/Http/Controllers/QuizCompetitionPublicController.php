<?php

namespace App\Http\Controllers;

use App\Models\QuizCompetition;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\QuizRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QuizCompetitionPublicController extends Controller
{
    public function index(): View
    {
        $competitions = QuizCompetition::active()
            ->ordered()
            ->with('questions.choices')
            ->get();

        return view('quiz-competitions.index', compact('competitions'));
    }

    public function show(QuizCompetition $quizCompetition): View
    {
        $quizCompetition->load(['questions.choices', 'questions.winners.user', 'questions.answers.user']);

        $now = now();
        $comp = $quizCompetition;
        $questions = $comp->questions;
        $upcomingQuestions = collect();
        $endedQuestions = collect();

        if ($comp->start_at && $comp->end_at) {
            if ($now->gte($comp->start_at) && $now->lte($comp->end_at)) {
                $questions = $comp->questions;
            } elseif ($now->lt($comp->start_at)) {
                $questions = collect();
                $upcomingQuestions = $comp->questions;
            } else {
                $questions = collect();
                $endedQuestions = $comp->questions;
            }
        }

        return view('quiz-competitions.show', compact('quizCompetition', 'questions', 'upcomingQuestions', 'endedQuestions'));
    }

    public function question(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): View|RedirectResponse
    {
        if ($quizQuestion->quiz_competition_id !== $quizCompetition->id) {
            abort(404);
        }

        $quizQuestion->load(['choices', 'winners.user', 'answers.user']);

        $stats = [
            'total' => $quizQuestion->answers->count(),
            'correct' => $quizQuestion->answers->where('is_correct', true)->count(),
            'wrong' => $quizQuestion->answers->where('is_correct', false)->count(),
        ];

        if ($quizQuestion->hasNotStarted()) {
            return view('quiz-competitions.question', [
                'quizCompetition' => $quizCompetition,
                'quizQuestion' => $quizQuestion,
                'status' => 'not_started',
                'stats' => $stats,
            ]);
        }

        if ($quizQuestion->hasEnded()) {
            return view('quiz-competitions.question', [
                'quizCompetition' => $quizCompetition,
                'quizQuestion' => $quizQuestion,
                'status' => 'ended',
                'stats' => $stats,
            ]);
        }

        $cooldownHours = 2;
        $lastAnsweredAt = session('quiz_answered_' . $quizQuestion->id);
        $canAnswer = true;
        if ($lastAnsweredAt) {
            $lastAt = \Carbon\Carbon::parse($lastAnsweredAt);
            $canAnswer = now()->diffInHours($lastAt) >= $cooldownHours;
            if ($canAnswer) {
                session()->forget('quiz_answered_' . $quizQuestion->id);
            }
        }

        return view('quiz-competitions.question', [
            'quizCompetition' => $quizCompetition,
            'quizQuestion' => $quizQuestion,
            'status' => 'active',
            'stats' => $stats,
            'canAnswer' => $canAnswer,
        ]);
    }

    public function storeAnswer(Request $request, QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        if ($quizQuestion->quiz_competition_id !== $quizCompetition->id) {
            abort(404);
        }

        if ($quizQuestion->hasNotStarted()) {
            return back()->with('error', 'السؤال لم يبدأ بعد');
        }

        if ($quizQuestion->hasEnded()) {
            return back()->with('error', 'انتهت فترة الإجابة على هذا السؤال');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'answer' => 'required|string',
        ], [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'answer.required' => 'الإجابة مطلوبة',
        ]);

        try {
            DB::beginTransaction();

            $user = User::where('phone', $validated['phone'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'password' => Hash::make(uniqid()),
                    'status' => 1,
                ]);
            } else {
                if (empty($user->name)) {
                    $user->name = $validated['name'];
                    $user->save();
                }
            }

            $lastAnswer = QuizAnswer::where('quiz_question_id', $quizQuestion->id)
                ->where('user_id', $user->id)
                ->latest('created_at')
                ->first();

            $cooldownHours = 2;
            if ($lastAnswer && $lastAnswer->created_at->addHours($cooldownHours)->isFuture()) {
                DB::rollBack();
                return back();
            }

            QuizRegistration::firstOrCreate(
                [
                    'quiz_competition_id' => $quizCompetition->id,
                    'user_id' => $user->id,
                ]
            );

            $isCorrect = false;
            $answerType = 'custom';

            if ($quizQuestion->answer_type === 'multiple_choice') {
                $choiceId = (int) $validated['answer'];
                $correctChoice = $quizQuestion->choices->firstWhere('is_correct', true);
                $isCorrect = $correctChoice && $correctChoice->id === $choiceId;
                $answerType = 'choice';
            }

            QuizAnswer::create([
                'quiz_question_id' => $quizQuestion->id,
                'user_id' => $user->id,
                'answer' => $validated['answer'],
                'answer_type' => $answerType,
                'is_correct' => $isCorrect,
            ]);

            session(['quiz_answered_' . $quizQuestion->id => now()->toDateTimeString()]);

            DB::commit();

            return back()->with('success', $isCorrect ? 'أحسنت! إجابتك صحيحة' : 'للأسف إجابتك غير صحيحة');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }
}
