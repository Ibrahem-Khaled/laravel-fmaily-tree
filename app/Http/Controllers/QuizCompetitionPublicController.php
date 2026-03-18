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

        // السؤال لم يظهر بعد (خلال 60 ثانية من بدء المسابقة)
        $questionsVisibleAt = $quizCompetition->getQuestionsVisibleAt();
        if ($questionsVisibleAt && now()->lt($questionsVisibleAt)) {
            return view('quiz-competitions.question', [
                'quizCompetition' => $quizCompetition,
                'quizQuestion' => $quizQuestion,
                'status' => 'question_locked',
                'stats' => $stats,
                'questionsVisibleAt' => $questionsVisibleAt,
                'candidateNames' => $candidateNames,
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
        if ($quizCompetition->show_draw_only) {
            return back()->with('error', 'عذراً، باب الإجابة مغلق حالياً للمتابعة مع القرعة والسحب.');
        }

        // if ($quizQuestion->quiz_competition_id !== $quizCompetition->id) {
        //     abort(404);
        // }

        if ($quizQuestion->hasNotStarted()) {
            return back()->with('error', 'السؤال لم يبدأ بعد');
        }

        $questionsVisibleAt = $quizCompetition->getQuestionsVisibleAt();
        if ($questionsVisibleAt && now()->lt($questionsVisibleAt)) {
            return back()->with('error', 'السؤال يظهر بعد مرور الوقت المحدد من بدء المسابقة.');
        }

        if ($quizQuestion->hasEnded()) {
            return back()->with('error', 'انتهت فترة الإجابة على هذا السؤال');
        }

        $rules = [
            'name' => 'nullable|string|max:255',
            'phone' => 'required|string|size:10|regex:/^[0-9]{10}$/',
            'is_from_ancestry' => 'nullable|in:1',
            'mother_name' => 'nullable|string|max:255',
        ];

        // For vote type: name is not required
        $isVoteType = $quizQuestion->answer_type === 'vote';

        if (!$isVoteType) {
            $rules['name'] = 'required|string|max:255';
        }

        if ($quizQuestion->is_multiple_selections || $quizQuestion->answer_type === 'ordering' || $quizQuestion->answer_type === 'true_false') {
            $rules['answer'] = 'required|array';
            if ($quizQuestion->answer_type !== 'true_false') {
                $rules['answer.*'] = 'required|exists:quiz_question_choices,id';
            } else {
                // For true_false, keys are choice IDs, values are true/false strings or 1/0
                $rules['answer.*'] = 'required|in:1,0,true,false';
            }
        } elseif ($isVoteType) {
            $maxSelections = $quizQuestion->vote_max_selections ?? 1;
            if ($maxSelections > 1) {
                $rules['answer'] = 'required|array|min:1|max:' . $maxSelections;
                $rules['answer.*'] = 'required|exists:quiz_question_choices,id';
            } else {
                $rules['answer'] = 'required|exists:quiz_question_choices,id';
            }
        } elseif ($quizQuestion->answer_type === 'multiple_choice') {
            $rules['answer'] = 'required|exists:quiz_question_choices,id';
        } elseif ($quizQuestion->answer_type === 'fill_blank') {
            $rules['answer'] = 'required|exists:quiz_question_choices,id';
        } else {
            $rules['answer'] = 'required|string|max:1000';
        }

        $validated = $request->validate($rules, [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.size' => 'يجب أن يكون رقم الهاتف 10 أرقام بالضبط',
            'phone.regex' => 'يجب أن يكون رقم الهاتف 10 أرقام فقط',
            'answer.required' => 'الإجابة مطلوبة',
            'answer.max' => 'لا يمكنك اختيار أكثر من ' . ($quizQuestion->vote_max_selections ?? 1) . ' خيارات',
        ]);

        // Vote type with require_prior_registration: verify phone exists
        if ($isVoteType && $quizQuestion->require_prior_registration) {
            $phoneExists = User::where('phone', $validated['phone'])->exists();
            if (!$phoneExists) {
                return back()->withInput()->with('error', 'رقم الهاتف غير مسجل. هذا التصويت للمشاركين السابقين فقط.');
            }
        }

        try {
            DB::beginTransaction();

            // إذا تم إدخال اسم الأم، يعتبر المستخدم من الأنساب تلقائياً
            $hasMotherName = !empty($validated['mother_name']);
            $isFromAncestry = isset($validated['is_from_ancestry']) && $validated['is_from_ancestry'] || $hasMotherName;

            // التحقق من وجود إجابة سابقة بنفس رقم الهاتف
            $existingAnswer = QuizAnswer::where('quiz_question_id', $quizQuestion->id)
                ->whereHas('user', function ($q) use ($validated) {
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
                    return back()->with('error', $isVoteType ? 'لقد صوّتت مسبقاً على هذا السؤال.' : 'لقد أجبت على هذا السؤال مسبقاً.');
                }
            }

            // إنشاء مستخدم أو استخدام الموجود (للتصويت نحاول إيجاده أولاً)
            if ($isVoteType && $quizQuestion->require_prior_registration) {
                // For vote with required registration: find the existing user
                $user = User::where('phone', $validated['phone'])->latest()->first();
                if (!$user) {
                    DB::rollBack();
                    return back()->withInput()->with('error', 'رقم الهاتف غير مسجل.');
                }
            } else {
                $user = User::create([
                    'name' => $validated['name'] ?? ('مصوّت_' . $validated['phone']),
                    'phone' => $validated['phone'],
                    'password' => Hash::make(uniqid()),
                    'status' => 1,
                    'is_from_ancestry' => $isFromAncestry,
                    'mother_name' => $hasMotherName ? $validated['mother_name'] : null,
                ]);
            }

            QuizRegistration::firstOrCreate(
                [
                    'quiz_competition_id' => $quizCompetition->id,
                    'user_id' => $user->id,
                ]
            );

            $isCorrect = false;
            $answerType = 'custom';
            $answerData = $validated['answer'];

            if ($quizQuestion->answer_type === 'multiple_choice') {
                $answerType = 'choice';

                if ($quizQuestion->is_multiple_selections) {
                    $selectedChoiceIds = $validated['answer']; // Array of ids
                    $correctChoiceIds = $quizQuestion->choices()->where('is_correct', true)->pluck('id')->toArray();

                    $selectedCorrectCount = 0;
                    $hasIncorrectChoice = false;

                    foreach ($selectedChoiceIds as $choiceId) {
                        if (in_array((int) $choiceId, $correctChoiceIds)) {
                            $selectedCorrectCount++;
                        } else {
                            $hasIncorrectChoice = true;
                            break;
                        }
                    }

                    // Must select ALL correct choices and NO incorrect choices
                    $isCorrect = !$hasIncorrectChoice && ($selectedCorrectCount === count($correctChoiceIds));
                    $answerData = json_encode($selectedChoiceIds);
                } else {
                    $choiceId = (int) $validated['answer'];
                    $correctChoice = $quizQuestion->choices->firstWhere('is_correct', true);
                    $isCorrect = $correctChoice && $correctChoice->id === $choiceId;
                    $answerData = (string) $validated['answer'];
                }
            } elseif ($quizQuestion->answer_type === 'vote') {
                $answerType = 'vote';
                $maxSelections = $quizQuestion->vote_max_selections ?? 1;
                if ($maxSelections > 1) {
                    $selectedChoiceIds = (array) $validated['answer'];
                    $answerData = json_encode($selectedChoiceIds);
                } else {
                    $selectedChoiceIds = [(int) $validated['answer']];
                    $answerData = (string) $validated['answer'];
                }
                $isCorrect = false; // Vote has no correct answer
            } elseif ($quizQuestion->answer_type === 'ordering') {
                $answerType = 'ordering';
                $selectedChoiceIds = $validated['answer']; // Array of ids

                $isCorrect = true;

                if ($quizQuestion->groups_count && $quizQuestion->groups_count > 0) {
                    // Group-based ordering: correct ORDER within each group matters,
                    // but images can be in ANY group (groups are interchangeable)
                    $groups = $quizQuestion->choices->filter(fn($c) => !empty($c->group_name))->groupBy('group_name');
                    
                    if (count($selectedChoiceIds) !== $quizQuestion->choices->count()) {
                        $isCorrect = false;
                    } else {
                        // Build the correct groups (each is an array of IDs in order)
                        $correctGroups = [];
                        $groupSizes = [];
                        foreach ($groups as $groupName => $groupChoices) {
                            $correctGroups[] = array_map('intval', $groupChoices->pluck('id')->toArray());
                            $groupSizes[] = $groupChoices->count();
                        }
                        
                        // Chunk submitted IDs by group slot sizes
                        $submittedChunks = [];
                        $currentIndex = 0;
                        foreach ($groupSizes as $size) {
                            $submittedChunks[] = array_map('intval', array_slice($selectedChoiceIds, $currentIndex, $size));
                            $currentIndex += $size;
                        }
                        
                        // Match each submitted chunk to any correct group (order must match exactly)
                        $matched = [];
                        foreach ($submittedChunks as $chunk) {
                            $found = false;
                            foreach ($correctGroups as $gIdx => $correctIds) {
                                if (isset($matched[$gIdx])) continue;
                                if ($chunk === $correctIds) {
                                    $matched[$gIdx] = true;
                                    $found = true;
                                    break;
                                }
                            }
                            if (!$found) {
                                $isCorrect = false;
                                break;
                            }
                        }
                        
                        if (count($matched) !== count($correctGroups)) {
                            $isCorrect = false;
                        }
                    }
                } else {
                    // Standard exact ordering
                    $correctChoiceIds = $quizQuestion->choices()->orderBy('id')->pluck('id')->toArray();
                    
                    if (count($selectedChoiceIds) !== count($correctChoiceIds)) {
                        $isCorrect = false;
                    } else {
                        foreach ($correctChoiceIds as $index => $correctId) {
                            if ((int) $selectedChoiceIds[$index] !== (int) $correctId) {
                                $isCorrect = false;
                                break;
                            }
                        }
                    }
                }
                
                $answerData = json_encode($selectedChoiceIds);
            } elseif ($quizQuestion->answer_type === 'true_false') {
                $answerType = 'true_false';
                $selectedAnswers = $validated['answer']; // Array map: choice_id => true/false

                $isCorrect = true;
                $correctChoicesCount = $quizQuestion->choices->count();
                
                if (count($selectedAnswers) !== $correctChoicesCount || $correctChoicesCount === 0) {
                    $isCorrect = false;
                } else {
                    foreach ($quizQuestion->choices as $choice) {
                        if (!isset($selectedAnswers[$choice->id])) {
                            $isCorrect = false;
                            break;
                        }
                        
                        $submittedVal = $selectedAnswers[$choice->id];
                        $submittedBool = ($submittedVal === '1' || $submittedVal === 'true');
                        $actualBool = (bool)$choice->is_correct;
                        
                        if ($submittedBool !== $actualBool) {
                            $isCorrect = false;
                            break;
                        }
                    }
                }

                $answerData = json_encode($selectedAnswers);
            } elseif ($quizQuestion->answer_type === 'fill_blank') {
                $answerType = 'fill_blank';
                $choiceId = (int) $validated['answer'];
                $selectedChoice = $quizQuestion->choices->firstWhere('id', $choiceId);
                $isCorrect = $selectedChoice && (bool) $selectedChoice->is_correct;
                $answerData = (string) $choiceId;
            } else {
                // Free-text / custom answers: treat any submitted answer as correct (as requested).
                $answerType = 'custom';
                $isCorrect = true;
                $answerData = (string) $validated['answer'];
            }

            // إنشاء إجابة جديدة دائماً
            $quizAnswer = QuizAnswer::create([
                'quiz_question_id' => $quizQuestion->id,
                'user_id' => $user->id,
                'answer' => $answerData,
                'answer_type' => $answerType,
                'is_correct' => $isCorrect,
            ]);

            // Store selected choices for vote type
            if (($quizQuestion->is_multiple_selections && $quizQuestion->answer_type === 'multiple_choice') || $quizQuestion->answer_type === 'ordering') {
                $selectedChoiceIds = $validated['answer'];
                foreach ($selectedChoiceIds as $choiceId) {
                    $quizAnswer->selectedChoices()->create([
                        'quiz_question_choice_id' => $choiceId,
                    ]);
                }
            } elseif ($quizQuestion->answer_type === 'true_false') {
                foreach ($validated['answer'] as $choiceId => $val) {
                    $quizAnswer->selectedChoices()->create([
                        'quiz_question_choice_id' => $choiceId,
                    ]);
                }
            } elseif ($quizQuestion->answer_type === 'vote') {
                foreach ($selectedChoiceIds as $choiceId) {
                    $quizAnswer->selectedChoices()->create([
                        'quiz_question_choice_id' => $choiceId,
                    ]);
                }
            } elseif ($quizQuestion->answer_type === 'fill_blank') {
                $quizAnswer->selectedChoices()->create([
                    'quiz_question_choice_id' => (int) $validated['answer'],
                ]);
            }

            session(['quiz_answered_' . $quizQuestion->id => now()->toDateTimeString()]);

            DB::commit();

            if ($isVoteType) {
                if ($request->input('source') === 'home') {
                    // Redirect back to home page with fragment to scroll to quiz block
                    return redirect()
                        ->to(route('home') . '#activeQuizSection')
                        ->with('vote_submitted', true)
                        ->with('answered_question_id', $quizQuestion->id);
                }

                return redirect()
                    ->route('quiz-competitions.question', [$quizCompetition, $quizQuestion])
                    ->with('vote_submitted', true);
            }

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
     * JSON endpoint to get vote results (percentage per choice) for vote-type questions.
     * Returns aggregate counts from ALL respondents (no filter by current user).
     */
    public function getVoteResults(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion)
    {
        if ($quizQuestion->answer_type !== 'vote') {
            return response()->json(['error' => 'Not a vote question'], 400);
        }

        if ($quizQuestion->quiz_competition_id != $quizCompetition->id) {
            abort(404);
        }

        $quizQuestion->load('choices');

        // Count votes per choice via selected_choices (all respondents, not filtered by user)
        $totalVotes = DB::table('quiz_answer_choices')
            ->join('quiz_answers', 'quiz_answers.id', '=', 'quiz_answer_choices.quiz_answer_id')
            ->where('quiz_answers.quiz_question_id', $quizQuestion->id)
            ->count();

        $results = [];
        foreach ($quizQuestion->choices as $choice) {
            $voteCount = DB::table('quiz_answer_choices')
                ->join('quiz_answers', 'quiz_answers.id', '=', 'quiz_answer_choices.quiz_answer_id')
                ->where('quiz_answers.quiz_question_id', $quizQuestion->id)
                ->where('quiz_answer_choices.quiz_question_choice_id', $choice->id)
                ->count();

            $percent = $totalVotes > 0 ? round(($voteCount / $totalVotes) * 100, 1) : 0;

            $results[] = [
                'id' => $choice->id,
                'text' => $choice->choice_text,
                'count' => $voteCount,
                'percent' => $percent,
                'image' => $choice->image ? asset('storage/' . $choice->image) : null,
            ];
        }

        return response()->json([
            'total' => $totalVotes,
            'results' => $results,
        ])->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
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
