<?php

namespace App\Http\Controllers;

use App\Models\QuizCompetition;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\QuizRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QuizCompetitionPublicController extends Controller
{
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

        // إضافة معلومات عن إجابات المستخدم لكل سؤال بناءً على session
        $userAnsweredQuestions = [];
        foreach ($questions as $question) {
            $lastAnsweredAt = session('quiz_answered_' . $question->id);
            $userAnsweredQuestions[$question->id] = !empty($lastAnsweredAt);
        }

        return view('quiz-competitions.show', compact('quizCompetition', 'questions', 'upcomingQuestions', 'endedQuestions', 'userAnsweredQuestions'));
    }

    public function question(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): View|RedirectResponse|Response
    {
        // if ($quizQuestion->quiz_competition_id !== $quizCompetition->id) {
        //     abort(404);
        // }

        $quizQuestion->load(['choices', 'winners.user', 'answers.user']);

        $stats = [
            'total' => $quizQuestion->answers->count(),
            'correct' => $quizQuestion->answers->where('is_correct', true)->count(),
            'wrong' => $quizQuestion->answers->where('is_correct', false)->count(),
        ];

        // Fetch candidate names for animation (real users) - Available for ALL statuses
        $candidateNames = $quizQuestion->answers()
            ->where('is_correct', true)
            ->inRandomOrder()
            ->limit(50)
            ->with('user')
            ->get()
            ->map(fn($a) => $a->user->name ?? 'مجهول')
            ->filter()
            ->values()
            ->all();

        if ($quizQuestion->hasNotStarted()) {
            return view('quiz-competitions.question', [
                'quizCompetition' => $quizCompetition,
                'quizQuestion' => $quizQuestion,
                'status' => 'not_started',
                'stats' => $stats,
            ]);
        }

        if ($quizQuestion->hasEnded()) {
            $selectionAt = $quizCompetition->end_at ? $quizCompetition->end_at->copy() : null;

            if ($selectionAt && now()->gte($selectionAt)) {
                $cacheKey = 'quiz_question_ended_' . $quizQuestion->id;
                $cachedHtml = Cache::get($cacheKey);
                if ($cachedHtml !== null) {
                    return response($cachedHtml)->header('Content-Type', 'text/html; charset=UTF-8');
                }

                if ($quizQuestion->winners->count() === 0) {
                    $lockKey = 'quiz-winner-selection-' . $quizQuestion->id;
                    Cache::lock($lockKey, 15)->block(10, function () use ($quizQuestion) {
                        $quizQuestion->refresh();
                        $quizQuestion->load(['winners']);
                        if ($quizQuestion->winners->count() === 0 && $quizQuestion->answers()->where('is_correct', true)->exists()) {
                            $quizQuestion->selectRandomWinners();
                        }
                    });
                }

                $quizQuestion->refresh();
                $quizQuestion->load(['choices', 'winners.user', 'answers.user']);

                $stats = [
                    'total' => $quizQuestion->answers->count(),
                    'correct' => $quizQuestion->answers->where('is_correct', true)->count(),
                    'wrong' => $quizQuestion->answers->where('is_correct', false)->count(),
                ];

                $html = view('quiz-competitions.question', [
                    'quizCompetition' => $quizCompetition,
                    'quizQuestion' => $quizQuestion,
                    'status' => 'ended',
                    'stats' => $stats,
                    'selectionAt' => $selectionAt,
                    'candidateNames' => $candidateNames,
                ])->render();

                Cache::put($cacheKey, $html, now()->addMinutes(5));

                return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
            }

            return view('quiz-competitions.question', [
                'quizCompetition' => $quizCompetition,
                'quizQuestion' => $quizQuestion,
                'status' => 'ended',
                'stats' => $stats,
                'selectionAt' => $selectionAt,
                'candidateNames' => $candidateNames,
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
            'candidateNames' => $candidateNames,
        ]);
    }

    public function storeAnswer(Request $request, QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        // if ($quizQuestion->quiz_competition_id !== $quizCompetition->id) {
        //     abort(404);
        // }

        if ($quizQuestion->hasNotStarted()) {
            return back()->with('error', 'السؤال لم يبدأ بعد');
        }

        if ($quizQuestion->hasEnded()) {
            return back()->with('error', 'انتهت فترة الإجابة على هذا السؤال');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|size:10|regex:/^[0-9]{10}$/',
            'answer' => 'required|string',
            'is_from_ancestry' => 'nullable|in:1',
            'mother_name' => 'nullable|string|max:255',
        ], [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.size' => 'يجب أن يكون رقم الهاتف 10 أرقام بالضبط',
            'phone.regex' => 'يجب أن يكون رقم الهاتف 10 أرقام فقط',
            'answer.required' => 'الإجابة مطلوبة',
        ]);

        try {
            DB::beginTransaction();

            // إذا تم إدخال اسم الأم، يعتبر المستخدم من الأنساب تلقائياً
            $hasMotherName = !empty($validated['mother_name']);
            $isFromAncestry = isset($validated['is_from_ancestry']) && $validated['is_from_ancestry'] || $hasMotherName;

            // التحقق من وجود إجابة سابقة بنفس رقم الهاتف
            $existingAnswer = QuizAnswer::where('quiz_question_id', $quizQuestion->id)
                ->whereHas('user', function($q) use ($validated) {
                    $q->where('phone', $validated['phone']);
                })
                ->latest()
                ->first();

            if ($existingAnswer) {
                $cooldownHours = 2;
                $lastAnswerTime = $existingAnswer->created_at;
                $canAnswerAgain = now()->diffInHours($lastAnswerTime) >= $cooldownHours;

                if (!$canAnswerAgain) {
                    DB::rollBack();
                    return back()->with('error', 'لقد أجبت على هذا السؤال مسبقاً.');
                }
            }

            // إنشاء مستخدم جديد دائماً
            $user = User::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'password' => Hash::make(uniqid()),
                'status' => 1,
                'is_from_ancestry' => $isFromAncestry,
                'mother_name' => $hasMotherName ? $validated['mother_name'] : null,
            ]);

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

            // إنشاء إجابة جديدة دائماً
            QuizAnswer::create([
                'quiz_question_id' => $quizQuestion->id,
                'user_id' => $user->id,
                'answer' => $validated['answer'],
                'answer_type' => $answerType,
                'is_correct' => $isCorrect,
            ]);

            session(['quiz_answered_' . $quizQuestion->id => now()->toDateTimeString()]);

            DB::commit();

            return redirect()
                ->route('quiz-competitions.question', [$quizCompetition, $quizQuestion])
                ->with('answer_submitted', true)
                ->with('answer_correct', $isCorrect);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            // معالجة أخطاء قاعدة البيانات بشكل أفضل
            if ($e->getCode() == 23000) {
                // خطأ في constraint (مثل duplicate entry)
                if (str_contains($e->getMessage(), 'quiz_answers_quiz_question_id_user_id_unique')) {
                    return back()->withInput()->with('error', 'لقد أجبت على هذا السؤال مسبقاً.');
                }
                return back()->withInput()->with('error', 'حدث خطأ في حفظ البيانات. يرجى المحاولة مرة أخرى.');
            }
            return back()->withInput()->with('error', 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * JSON endpoint to get the winner — designed for high concurrency (1000+ users).
     * Uses Cache::remember so only ONE DB query ever runs; all other requests get cached result.
     */
    public function getWinner(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion)
    {
        $selectionAt = $quizCompetition->end_at ? $quizCompetition->end_at->copy() : null;

        // Not time yet
        if (!$selectionAt || now()->lt($selectionAt)) {
            return response()->json(['status' => 'pending', 'message' => 'لم يحن وقت الاختيار بعد']);
        }

        // Try to get from cache first (10 min TTL)
        $cacheKey = 'quiz_winner_json_' . $quizQuestion->id;
        $result = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($quizQuestion) {
            // Atomic lock: only one process selects winners
            if ($quizQuestion->winners()->count() === 0) {
                $lockKey = 'quiz-winner-selection-' . $quizQuestion->id;
                Cache::lock($lockKey, 15)->block(10, function () use ($quizQuestion) {
                    $quizQuestion->refresh();
                    $quizQuestion->load(['winners']);
                    if ($quizQuestion->winners->count() === 0 && $quizQuestion->answers()->where('is_correct', true)->exists()) {
                        $quizQuestion->selectRandomWinners();
                    }
                });
            }

            $quizQuestion->refresh();
            $quizQuestion->load(['winners.user']);

            if ($quizQuestion->winners->count() > 0) {
                $winners = $quizQuestion->winners->map(function ($w) {
                    return [
                        'position' => $w->position,
                        'name' => $w->user->name ?? 'مجهول',
                    ];
                })->values()->all();
                return ['status' => 'done', 'winners' => $winners];
            }

            return ['status' => 'no_winners', 'message' => 'لا يوجد فائزين (لا توجد إجابات صحيحة)'];
        });

        return response()->json($result);
    }
}
