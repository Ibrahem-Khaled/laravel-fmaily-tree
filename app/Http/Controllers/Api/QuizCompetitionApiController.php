<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizAnswer;
use App\Models\QuizCompetition;
use App\Models\QuizQuestion;
use App\Models\QuizRegistration;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class QuizCompetitionApiController extends Controller
{
    /**
     * Get the active competition or the next upcoming event.
     * Caches the competition layout to support high concurrency.
     */
    public function getActiveCompetition(Request $request): JsonResponse
    {
        $activeComp = Cache::remember('api_active_quiz_competition', now()->addMinutes(2), function () {
            $comp = QuizCompetition::active()->ordered()->first();
            if (!$comp) {
                return null;
            }

            // Eager load everything needed for the API
            $comp->load([
                'questions.choices',
                'questions.surveyItems',
            ]);

            return $comp;
        });

        // التحقق من صلاحية المسابقة النشطة حالياً (أنها بدأت ولم تنتهِ بعد)
        if ($activeComp) {
            $now = now();
            $hasStarted = !$activeComp->start_at || $now->gte($activeComp->start_at);
            $hasEnded = $activeComp->end_at && $now->gt($activeComp->end_at);

            if (!$hasStarted || $hasEnded) {
                $activeComp = null;
            }
        }

        // Resolve user identifier if provided (phone, or authenticated user)
        $phone = $request->query('phone') ?? ($request->user()?->phone);
        $answeredQuestionIds = [];

        if ($phone && $activeComp) {
            $answeredQuestionIds = Cache::remember("user_answered_questions_{$phone}", now()->addMinutes(2), function () use ($phone, $activeComp) {
                $questionIds = $activeComp->questions->pluck('id')->toArray();
                return QuizAnswer::whereIn('quiz_question_id', $questionIds)
                    ->whereHas('user', function ($q) use ($phone) {
                        $q->where('phone', $phone);
                    })
                    ->pluck('quiz_question_id')
                    ->toArray();
            });
        }

        if (!$activeComp) {
            $upcoming = QuizCompetition::getNextUpcomingEvent();
            if ($upcoming && isset($upcoming['target_at'])) {
                // Format target_at for consistency
                $upcoming['target_at'] = $upcoming['target_at']->toIso8601String();
            }
            return response()->json([
                'status' => 'success',
                'data' => [
                    'active_competition' => null,
                    'upcoming_event' => $upcoming,
                ]
            ]);
        }

        // Format active competition details for API response
        $questionsVisibleAt = $activeComp->getQuestionsVisibleAt();
        $isLocked = $questionsVisibleAt && now()->lt($questionsVisibleAt);

        $formattedQuestions = $activeComp->questions->map(function (QuizQuestion $question) use ($answeredQuestionIds, $isLocked) {
            $hasAnswered = in_array($question->id, $answeredQuestionIds);

            // Format choices
            $choices = $question->choices->map(function ($choice) {
                return [
                    'id' => $choice->id,
                    'choice_text' => $choice->choice_text,
                    'image_url' => $choice->image ? asset('storage/' . $choice->image) : null,
                    'video_url' => $choice->video ? asset('storage/' . $choice->video) : null,
                    'group_name' => $choice->group_name,
                ];
            });

            // Format survey items
            $surveyItems = $question->surveyItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'sort_order' => $item->sort_order,
                    'block_type' => $item->block_type,
                    'body_text' => $item->body_text,
                    'media_url' => $item->media_path ? asset('storage/' . $item->media_path) : null,
                    'youtube_url' => $item->youtube_url,
                    'response_kind' => $item->response_kind,
                    'rating_max' => $item->rating_max,
                    'number_min' => $item->number_min,
                    'number_max' => $item->number_max,
                ];
            });

            return [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'description' => $question->description,
                'answer_type' => $question->answer_type,
                'is_multiple_selections' => (bool)$question->is_multiple_selections,
                'vote_max_selections' => $question->vote_max_selections ?? 1,
                'require_prior_registration' => (bool)$question->require_prior_registration,
                'prize' => $question->prize,
                'has_answered' => $hasAnswered,
                'choices' => $isLocked ? [] : $choices, // Hide choices if questions are pre-locked
                'survey_items' => $isLocked ? [] : $surveyItems,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'active_competition' => [
                    'id' => $activeComp->id,
                    'title' => $activeComp->title,
                    'description' => $activeComp->description,
                    'start_at' => $activeComp->start_at ? $activeComp->start_at->toIso8601String() : null,
                    'end_at' => $activeComp->end_at ? $activeComp->end_at->toIso8601String() : null,
                    'show_draw_only' => (bool)$activeComp->show_draw_only,
                    'questions_visible_at' => $questionsVisibleAt ? $questionsVisibleAt->toIso8601String() : null,
                    'is_locked' => $isLocked,
                    'questions' => $formattedQuestions,
                ],
                'upcoming_event' => null,
            ]
        ]);
    }

    /**
     * Submit an answer or a vote.
     * Matches the validation and evaluation logic of QuizCompetitionPublicController.
     */
    public function storeAnswer(Request $request, QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): JsonResponse
    {
        // 1. Signature verification (anti-bot / client verification) (Technical Partner decision)
        $clientSignature = $request->header('X-App-Signature');
        $timestamp = $request->header('X-App-Timestamp');
        
        if ($clientSignature && $timestamp) {
            // Verify timestamp isn't older than 5 minutes to prevent replay attacks
            if (abs(time() - (int)$timestamp) > 300) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'انتهت صلاحية الطلب (زمن غير صالح).'
                ], 403);
            }
            
            $secret = config('quiz.api_secret_key');
            $expectedSignature = hash('sha256', $secret . $timestamp . $quizQuestion->id . $request->input('phone'));
            
            if (!hash_equals($expectedSignature, $clientSignature)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'توقيع الطلب غير صحيح.'
                ], 403);
            }
        }

        if ($quizCompetition->show_draw_only) {
            return response()->json([
                'status' => 'error',
                'message' => 'عذراً، باب الإجابة مغلق حالياً للمتابعة مع القرعة والسحب.'
            ], 422);
        }

        if ($quizQuestion->quiz_competition_id != $quizCompetition->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'السؤال المحدد لا ينتمي لهذه المسابقة.'
            ], 400);
        }

        if ($quizQuestion->hasNotStarted()) {
            return response()->json([
                'status' => 'error',
                'message' => 'السؤال لم يبدأ بعد.'
            ], 422);
        }

        $questionsVisibleAt = $quizCompetition->getQuestionsVisibleAt();
        if ($questionsVisibleAt && now()->lt($questionsVisibleAt)) {
            return response()->json([
                'status' => 'error',
                'message' => 'السؤال يظهر بعد مرور الوقت المحدد من بدء المسابقة.'
            ], 422);
        }

        if ($quizQuestion->hasEnded()) {
            return response()->json([
                'status' => 'error',
                'message' => 'انتهت فترة الإجابة على هذا السؤال.'
            ], 422);
        }

        $requiresPriorRegistration = (bool)$quizQuestion->require_prior_registration;
        $isVoteType = $quizQuestion->answer_type === 'vote';

        // Prepare validation rules
        $rules = [
            'name' => ($requiresPriorRegistration || $isVoteType) ? 'nullable|string|max:255' : 'required|string|max:255',
            'phone' => 'required|string|size:10|regex:/^[0-9]{10}$/',
            'is_from_ancestry' => 'nullable|boolean',
            'mother_name' => 'nullable|string|max:255',
        ];

        // Specific answer validations based on question type
        if ($quizQuestion->is_multiple_selections || $quizQuestion->answer_type === 'ordering' || $quizQuestion->answer_type === 'true_false') {
            $rules['answer'] = 'required|array';
            if ($quizQuestion->answer_type !== 'true_false') {
                $rules['answer.*'] = 'required|exists:quiz_question_choices,id';
            } else {
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
        } elseif ($quizQuestion->answer_type === 'survey') {
            $quizQuestion->loadMissing('surveyItems');
            if ($quizQuestion->surveyItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'هذا الاستبيان غير مكتمل الإعداد.'
                ], 422);
            }
            $rules['survey_item'] = 'required|array';
            foreach ($quizQuestion->surveyItems as $item) {
                $key = 'survey_item.' . $item->id;
                if ($item->response_kind === 'rating') {
                    $rules[$key] = 'required|integer|min:1|max:' . max(1, (int)$item->rating_max);
                } elseif ($item->response_kind === 'number') {
                    $min = (int)($item->number_min ?? 0);
                    $max = (int)($item->number_max ?? 100);
                    if ($min > $max) {
                        $min = 0;
                        $max = 100;
                    }
                    $rules[$key] = 'required|integer|min:' . $min . '|max:' . $max;
                } else {
                    $rules[$key] = 'required|string|max:2000';
                }
            }
        } else {
            $rules['answer'] = 'required|string|max:1000';
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.size' => 'يجب أن يكون رقم الهاتف 10 أرقام بالضبط',
            'phone.regex' => 'يجب أن يكون رقم الهاتف 10 أرقام فقط تبدأ بـ 05',
            'answer.required' => 'الإجابة مطلوبة',
            'answer.max' => 'لا يمكنك اختيار أكثر من ' . ($quizQuestion->vote_max_selections ?? 1) . ' خيارات',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل التحقق من البيانات المدخلة.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // 1. Check phone exists if prior registration is required
        if ($requiresPriorRegistration) {
            $phoneExists = User::where('phone', $validated['phone'])->exists();
            if (!$phoneExists) {
                $actionMsg = $isVoteType ? 'التصويت' : 'المشاركة';
                return response()->json([
                    'status' => 'error',
                    'message' => "رقم الهاتف غير مسجل. لا يمكنك {$actionMsg} إلا لمن سبق تسجيله برقم هاتفه."
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            $hasMotherName = !empty($validated['mother_name']);
            $isFromAncestry = (isset($validated['is_from_ancestry']) && $validated['is_from_ancestry']) || $hasMotherName;

            // 2. Cooldown check (2 hours)
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
                    return response()->json([
                        'status' => 'error',
                        'message' => $isVoteType ? 'لقد صوّتت مسبقاً على هذا السؤال.' : 'لقد أجبت على هذا السؤال مسبقاً.'
                    ], 422);
                }
            }

            // 3. Find or create User
            if ($requiresPriorRegistration) {
                $user = User::where('phone', $validated['phone'])->latest()->first();
                if (!$user) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'رقم الهاتف غير مسجل.'
                    ], 422);
                }
            } else {
                // Check if user already exists to reuse them rather than duplicating rows (Technical Partner decision)
                $user = User::where('phone', $validated['phone'])->latest()->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $validated['name'] ?? ('مصوّت_' . $validated['phone']),
                        'phone' => $validated['phone'],
                        'password' => Hash::make(uniqid()),
                        'status' => 1,
                        'is_from_ancestry' => $isFromAncestry,
                        'mother_name' => $hasMotherName ? $validated['mother_name'] : null,
                    ]);
                }
            }

            // 4. Register user for competition
            QuizRegistration::firstOrCreate([
                'quiz_competition_id' => $quizCompetition->id,
                'user_id' => $user->id,
            ]);

            $isCorrect = false;
            $answerType = 'custom';
            $answerData = '';
            if ($quizQuestion->answer_type !== 'survey') {
                $answerData = $validated['answer'];
            }

            // 5. Evaluate correctness of the answer based on type
            if ($quizQuestion->answer_type === 'multiple_choice') {
                $answerType = 'choice';

                if ($quizQuestion->is_multiple_selections) {
                    $selectedChoiceIds = $validated['answer'];
                    $correctChoiceIds = $quizQuestion->choices()->where('is_correct', true)->pluck('id')->toArray();

                    $selectedCorrectCount = 0;
                    $hasIncorrectChoice = false;

                    foreach ($selectedChoiceIds as $choiceId) {
                        if (in_array((int)$choiceId, $correctChoiceIds)) {
                            $selectedCorrectCount++;
                        } else {
                            $hasIncorrectChoice = true;
                            break;
                        }
                    }

                    $isCorrect = !$hasIncorrectChoice && ($selectedCorrectCount === count($correctChoiceIds));
                    $answerData = json_encode($selectedChoiceIds);
                } else {
                    $choiceId = (int)$validated['answer'];
                    $correctChoice = $quizQuestion->choices->firstWhere('is_correct', true);
                    $isCorrect = $correctChoice && $correctChoice->id === $choiceId;
                    $answerData = (string)$validated['answer'];
                }
            } elseif ($quizQuestion->answer_type === 'vote') {
                $answerType = 'vote';
                $maxSelections = $quizQuestion->vote_max_selections ?? 1;
                if ($maxSelections > 1) {
                    $selectedChoiceIds = (array)$validated['answer'];
                    $answerData = json_encode($selectedChoiceIds);
                } else {
                    $selectedChoiceIds = [(int)$validated['answer']];
                    $answerData = (string)$validated['answer'];
                }
                $isCorrect = false; // Votes don't have a correct answer
            } elseif ($quizQuestion->answer_type === 'ordering') {
                $answerType = 'ordering';
                $selectedChoiceIds = $validated['answer'];

                $isCorrect = true;

                if ($quizQuestion->groups_count && $quizQuestion->groups_count > 0) {
                    $groups = $quizQuestion->choices->filter(fn($c) => !empty($c->group_name))->groupBy('group_name');

                    if (count($selectedChoiceIds) !== $quizQuestion->choices->count()) {
                        $isCorrect = false;
                    } else {
                        $correctGroups = [];
                        $groupSizes = [];
                        foreach ($groups as $groupName => $groupChoices) {
                            $correctGroups[] = array_map('intval', $groupChoices->pluck('id')->toArray());
                            $groupSizes[] = $groupChoices->count();
                        }

                        $submittedChunks = [];
                        $currentIndex = 0;
                        foreach ($groupSizes as $size) {
                            $submittedChunks[] = array_map('intval', array_slice($selectedChoiceIds, $currentIndex, $size));
                            $currentIndex += $size;
                        }

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
                    $correctChoiceIds = $quizQuestion->choices()->orderBy('id')->pluck('id')->toArray();

                    if (count($selectedChoiceIds) !== count($correctChoiceIds)) {
                        $isCorrect = false;
                    } else {
                        foreach ($correctChoiceIds as $index => $correctId) {
                            if ((int)$selectedChoiceIds[$index] !== (int)$correctId) {
                                $isCorrect = false;
                                break;
                            }
                        }
                    }
                }

                $answerData = json_encode($selectedChoiceIds);
            } elseif ($quizQuestion->answer_type === 'true_false') {
                $answerType = 'true_false';
                $selectedAnswers = $validated['answer'];

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
                        $submittedBool = ($submittedVal === '1' || $submittedVal === 'true' || $submittedVal === 1 || $submittedVal === true);
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
                $choiceId = (int)$validated['answer'];
                $selectedChoice = $quizQuestion->choices->firstWhere('id', $choiceId);
                $isCorrect = $selectedChoice && (bool)$selectedChoice->is_correct;
                $answerData = (string)$choiceId;
            } elseif ($quizQuestion->answer_type === 'survey') {
                $quizQuestion->loadMissing('surveyItems');
                $answerType = 'survey';
                $isCorrect = true;
                $payload = [];
                foreach ($quizQuestion->surveyItems as $item) {
                    $raw = $validated['survey_item'][$item->id] ?? null;
                    if ($item->response_kind === 'rating') {
                        $payload[$item->id] = ['k' => 'rating', 'v' => (int)$raw];
                    } elseif ($item->response_kind === 'number') {
                        $payload[$item->id] = ['k' => 'number', 'v' => (int)$raw];
                    } else {
                        $payload[$item->id] = ['k' => 'text', 'v' => (string)$raw];
                    }
                }
                $answerData = json_encode($payload, JSON_UNESCAPED_UNICODE);
            } else {
                $answerType = 'custom';
                $isCorrect = true;
                $answerData = (string)$validated['answer'];
            }

            // 6. Create the QuizAnswer record
            $quizAnswer = QuizAnswer::create([
                'quiz_question_id' => $quizQuestion->id,
                'user_id' => $user->id,
                'answer' => $answerData,
                'answer_type' => $answerType,
                'is_correct' => $isCorrect,
            ]);

            // 7. Store selected choices mapping in quiz_answer_choices
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
                    'quiz_question_choice_id' => (int)$validated['answer'],
                ]);
            }

            // Invalidate the cache for user answers immediately
            Cache::forget("user_answered_questions_{$user->phone}");
            // Invalidate vote results cache so the next request gets fresh aggregates
            Cache::forget("vote_results_{$quizQuestion->id}");

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'تم تسجيل مشاركتك بنجاح.',
                'data' => [
                    'is_correct' => $isCorrect,
                    'cooldown_ends_at' => now()->addHours(2)->toIso8601String()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حفظ الإجابة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get live poll results.
     * Caches result to protect database from slam.
     */
    public function getVoteResults(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): JsonResponse
    {
        if ($quizQuestion->answer_type !== 'vote') {
            return response()->json([
                'status' => 'error',
                'message' => 'هذا السؤال ليس تصويتاً.'
            ], 400);
        }

        if ($quizQuestion->quiz_competition_id != $quizCompetition->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'السؤال المحدد لا ينتمي لهذه المسابقة.'
            ], 400);
        }

        // Cache results for 10 seconds to throttle heavy hits
        $cacheKey = "vote_results_{$quizQuestion->id}";
        $data = Cache::remember($cacheKey, now()->addSeconds(10), function () use ($quizQuestion) {
            $quizQuestion->load('choices');

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
                    'image_url' => $choice->image ? asset('storage/' . $choice->image) : null,
                ];
            }

            return [
                'total_votes' => $totalVotes,
                'results' => $results,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
