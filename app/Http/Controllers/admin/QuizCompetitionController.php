<?php

namespace App\Http\Controllers\admin;

use App\Exports\QuizCompetitionExport;
use App\Http\Controllers\Controller;
use App\Models\QuizAnswer;
use App\Models\QuizCompetition;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $quizCompetition->load([
            'questions' => static function ($q) {
                $q->orderBy('display_order')
                    ->withCount('answers')
                    ->withCount(['answers as correct_answers_count' => static function ($q2) {
                        $q2->where('is_correct', true);
                    }])
                    ->with(['choices', 'surveyItems', 'winners.user']);
            },
        ]);

        return view('dashboard.quiz-competitions.show', compact('quizCompetition'));
    }

    /**
     * لوحة إجابات المسابقة: فلاتر، تصويت بنِسَب، ملخص استبيان، جدول مترقّم.
     */
    public function responses(QuizCompetition $quizCompetition, Request $request): View
    {
        $quizCompetition->load([
            'questions' => static function ($q) {
                $q->orderBy('display_order')->withCount('answers')->with(['choices', 'surveyItems']);
            },
        ]);

        $questions = $quizCompetition->questions;

        if ($questions->isEmpty()) {
            return view('dashboard.quiz-competitions.responses', [
                'quizCompetition' => $quizCompetition,
                'questions' => $questions,
                'activeQuestion' => null,
                'answers' => null,
                'voteStats' => [],
                'surveySummary' => [],
                'totalAnswerCount' => 0,
                'correctCount' => 0,
                'wrongCount' => 0,
                'lastAnswerAt' => null,
            ]);
        }

        $requestedId = (int) $request->input('question_id', 0);
        $activeQuestion = $requestedId > 0 ? $questions->firstWhere('id', $requestedId) : null;
        if (! $activeQuestion) {
            $activeQuestion = $questions->first(fn (QuizQuestion $q) => $q->answers_count > 0) ?? $questions->first();
        }

        $totalAnswerCount = (int) $activeQuestion->answers_count;
        $correctCount = 0;
        $wrongCount = 0;
        if (! in_array($activeQuestion->answer_type, ['vote', 'survey'], true)) {
            $correctCount = (int) $activeQuestion->answers()->where('is_correct', true)->count();
            $wrongCount = max(0, $totalAnswerCount - $correctCount);
        }

        $voteStats = [];
        if ($activeQuestion->answer_type === 'vote') {
            $rawAnswers = QuizAnswer::query()
                ->where('quiz_question_id', $activeQuestion->id)
                ->pluck('answer');
            $choiceCounts = [];
            foreach ($rawAnswers as $ans) {
                $decoded = json_decode($ans, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $cid) {
                        $cid = (int) $cid;
                        if ($cid > 0) {
                            $choiceCounts[$cid] = ($choiceCounts[$cid] ?? 0) + 1;
                        }
                    }
                } elseif (is_numeric($ans)) {
                    $cid = (int) $ans;
                    $choiceCounts[$cid] = ($choiceCounts[$cid] ?? 0) + 1;
                }
            }
            $grandTotal = max(1, array_sum($choiceCounts));
            foreach ($activeQuestion->choices as $choice) {
                $cnt = $choiceCounts[$choice->id] ?? 0;
                $voteStats[] = [
                    'choice' => $choice,
                    'count' => $cnt,
                    'percent' => round(100 * $cnt / $grandTotal, 1),
                ];
            }
        }

        $surveySummary = [];
        if ($activeQuestion->answer_type === 'survey') {
            $activeQuestion->loadMissing('surveyItems');
            $allSurveyAnswers = QuizAnswer::query()
                ->where('quiz_question_id', $activeQuestion->id)
                ->pluck('answer');
            foreach ($activeQuestion->surveyItems as $item) {
                $numericVals = [];
                $textVals = [];
                foreach ($allSurveyAnswers as $json) {
                    $decoded = json_decode($json, true);
                    if (! is_array($decoded) || ! isset($decoded[$item->id])) {
                        continue;
                    }
                    $entry = $decoded[$item->id];
                    $v = is_array($entry) ? ($entry['v'] ?? '') : '';
                    if ($item->response_kind === 'rating') {
                        $n = (int) $v;
                        if ($n >= 1) {
                            $numericVals[] = $n;
                        }
                    } elseif ($item->response_kind === 'number') {
                        if (is_numeric($v)) {
                            $numericVals[] = (float) $v;
                        }
                    } else {
                        $t = trim((string) $v);
                        if ($t !== '') {
                            $textVals[] = $t;
                        }
                    }
                }
                $row = ['item' => $item, 'kind' => $item->response_kind];
                if ($item->response_kind === 'rating') {
                    $mx = max(1, (int) $item->rating_max);
                    $row['avg'] = count($numericVals) ? round(array_sum($numericVals) / count($numericVals), 2) : null;
                    $row['count'] = count($numericVals);
                    $row['distribution'] = array_fill_keys(range(1, $mx), 0);
                    foreach ($numericVals as $n) {
                        if (isset($row['distribution'][$n])) {
                            $row['distribution'][$n]++;
                        }
                    }
                    $maxBar = max(1, max($row['distribution']));
                } elseif ($item->response_kind === 'number') {
                    $row['count'] = count($numericVals);
                    $row['min'] = count($numericVals) ? min($numericVals) : null;
                    $row['max'] = count($numericVals) ? max($numericVals) : null;
                    $row['avg'] = count($numericVals) ? round(array_sum($numericVals) / count($numericVals), 2) : null;
                    $maxBar = 1;
                } else {
                    $row['count'] = count($textVals);
                    $unique = array_unique($textVals);
                    $row['samples'] = array_slice($unique, 0, 12);
                    $maxBar = 1;
                }
                if ($item->response_kind === 'rating') {
                    $row['max_bar'] = $maxBar;
                }
                $surveySummary[] = $row;
            }
        }

        $answersQuery = $activeQuestion->answers()->with('user');
        if ($request->filled('q')) {
            $sq = trim((string) $request->q);
            $answersQuery->whereHas('user', static function ($uq) use ($sq) {
                $uq->where('name', 'like', '%'.$sq.'%')
                    ->orWhere('phone', 'like', '%'.$sq.'%');
            });
        }
        if ($request->filled('correct') && ! in_array($activeQuestion->answer_type, ['vote', 'survey'], true)) {
            $answersQuery->where('is_correct', $request->correct === '1');
        }
        if ($request->filled('from')) {
            $answersQuery->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $answersQuery->whereDate('created_at', '<=', $request->to);
        }

        $answers = $answersQuery->orderByDesc('created_at')->paginate(30)->withQueryString();

        $lastAnswerAt = $activeQuestion->answers()->max('created_at');

        return view('dashboard.quiz-competitions.responses', compact(
            'quizCompetition',
            'questions',
            'activeQuestion',
            'answers',
            'voteStats',
            'surveySummary',
            'totalAnswerCount',
            'correctCount',
            'wrongCount',
            'lastAnswerAt'
        ));
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
                                    if (! in_array($chkId, $correctIds)) {
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
                } elseif ($question->answer_type === 'survey') {
                    $question->loadMissing('surveyItems');
                    $payload = [];
                    foreach ($question->surveyItems as $item) {
                        if ($item->response_kind === 'rating') {
                            $mx = max(1, (int) $item->rating_max);
                            $payload[$item->id] = ['k' => 'rating', 'v' => random_int(1, $mx)];
                        } elseif ($item->response_kind === 'number') {
                            $mn = (int) ($item->number_min ?? 0);
                            $mx = (int) ($item->number_max ?? 100);
                            if ($mn > $mx) {
                                $mn = 0;
                                $mx = 100;
                            }
                            $payload[$item->id] = ['k' => 'number', 'v' => random_int($mn, $mx)];
                        } else {
                            $payload[$item->id] = ['k' => 'text', 'v' => 'Simulated '.\Illuminate\Support\Str::random(6)];
                        }
                    }
                    $answerText = json_encode($payload, JSON_UNESCAPED_UNICODE);
                    $isCorrect = true;
                } else {
                    $answerText = 'Simulated Answer '.\Illuminate\Support\Str::random(5);
                    $isCorrect = true;
                }

                $answerModel = \App\Models\QuizAnswer::create([
                    'quiz_question_id' => $question->id,
                    'user_id' => $user->id,
                    'answer' => $answerText,
                    'answer_type' => $question->answer_type === 'survey' ? 'survey' : $question->answer_type,
                    'is_correct' => $isCorrect,
                    'created_at' => now()->subSeconds(rand(0, 300)),
                ]);
                // `selectedChoices()` is a HasMany relationship, so we loop and create records instead of `attach()`
                if (! empty($selectedChoicesIds) && $question->is_multiple_selections) {
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
        $quizCompetition->load(['questions.choices', 'questions.surveyItems', 'questions.answers.user', 'questions.winners.user']);

        $safeTitle = \Illuminate\Support\Str::slug($quizCompetition->title);
        $filename = sprintf('quiz-competition-%s-%s.xlsx', $safeTitle, now()->format('Y-m-d-His'));

        return Excel::download(new QuizCompetitionExport($quizCompetition), $filename);
    }
}
