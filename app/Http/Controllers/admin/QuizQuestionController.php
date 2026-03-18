<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\QuizCompetition;
use App\Models\QuizQuestion;
use App\Models\QuizSurveyItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'answer_type' => 'required|in:multiple_choice,custom_text,ordering,true_false,vote,fill_blank,survey',
            'is_multiple_selections' => 'nullable|boolean',
            'winners_count' => 'required|integer|min:1',
            'groups_count' => 'nullable|integer|min:1',
            'display_order' => 'nullable|integer|min:0',
            'prize' => 'nullable|array',
            'prize.*' => 'nullable|string',
            'choices' => 'required_if:answer_type,multiple_choice,ordering,true_false,vote,fill_blank|array',
            'choices.*.id' => 'nullable|integer',
            'choices.*.text' => 'nullable|string',
            'choices.*.group_name' => 'nullable|string|max:255',
            'choices.*.image' => 'nullable|image|max:5120',
            'choices.*.video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
            'choices.*.is_correct' => 'nullable|boolean',
            'correct_choices' => 'nullable|array',
            'vote_max_selections' => 'nullable|integer|min:1',
            'require_prior_registration' => 'nullable|boolean',
            'survey_items' => 'required_if:answer_type,survey|array|min:1',
            'survey_items.*.id' => 'nullable|integer',
            'survey_items.*.block_type' => 'required_if:answer_type,survey|in:image,video,text',
            'survey_items.*.body_text' => 'nullable|string',
            'survey_items.*.response_kind' => 'required_if:answer_type,survey|in:rating,number,text',
            'survey_items.*.rating_max' => 'nullable|integer|min:2|max:100',
            'survey_items.*.number_min' => 'nullable|integer',
            'survey_items.*.number_max' => 'nullable|integer',
            'survey_items.*.youtube_url' => 'nullable|string|max:2000',
            'survey_items.*.media' => 'nullable|file|max:20000',
        ], [
            'question_text.required' => 'نص السؤال مطلوب',
            'answer_type.required' => 'نوع الإجابة مطلوب',
            'winners_count.required' => 'عدد الفائزين مطلوب',
            'winners_count.min' => 'عدد الفائزين يجب أن يكون على الأقل 1',
            'groups_count.min' => 'عدد المجموعات يجب أن يكون على الأقل 1',
        ]);

        if ($validated['answer_type'] === 'survey') {
            $err = $this->validateSurveyPayload($request, null);
            if ($err) {
                return $err;
            }
        }

        $question = $quizCompetition->questions()->create([
            'question_text' => $validated['question_text'],
            'description' => $validated['description'] ?? null,
            'answer_type' => $validated['answer_type'],
            'is_multiple_selections' => ! empty($validated['is_multiple_selections']),
            'winners_count' => $validated['winners_count'],
            'groups_count' => ($validated['answer_type'] === 'ordering') ? $validated['groups_count'] ?? null : null,
            'display_order' => $validated['display_order'] ?? 0,
            'prize' => $validated['prize'] ?? [],
            'vote_max_selections' => ($validated['answer_type'] === 'vote') ? ($validated['vote_max_selections'] ?? 1) : null,
            'require_prior_registration' => ! empty($validated['require_prior_registration']),
        ]);

        if ($validated['answer_type'] === 'survey') {
            $this->persistSurveyItemsFromRequest($question, $request);

            return redirect()->route('dashboard.quiz-competitions.show', $quizCompetition)
                ->with('success', 'تم إضافة السؤال بنجاح');
        }

        if (in_array($validated['answer_type'], ['multiple_choice', 'ordering', 'true_false', 'vote', 'fill_blank']) && ! empty($validated['choices'])) {
            $choices = array_filter($validated['choices'], fn ($c) => ! empty(trim($c['text'] ?? '')));

            $isMultiple = ! empty($validated['is_multiple_selections']);
            $isOrdering = $validated['answer_type'] === 'ordering';
            $isVote = $validated['answer_type'] === 'vote';
            $correctChoicesKeys = $request->input('correct_choices', []);

            $isTrueFalse = $validated['answer_type'] === 'true_false';

            foreach ($choices as $index => $choice) {
                $isCorrect = false;
                if ($isOrdering) {
                    $isCorrect = true;
                } elseif ($isVote) {
                    $isCorrect = false;
                } elseif ($isMultiple) {
                    $isCorrect = in_array((string) $index, $correctChoicesKeys);
                } elseif ($isTrueFalse) {
                    $isCorrect = ! empty($choice['is_correct']) && $choice['is_correct'] != 'false' && $choice['is_correct'] != '0';
                } else {
                    $isCorrect = ! empty($choice['is_correct']) && $choice['is_correct'] != 'false' && $choice['is_correct'] != '0';
                }

                $question->choices()->create([
                    'choice_text' => $choice['text'] ?? '',
                    'is_correct' => $isCorrect,
                    'group_name' => $isOrdering ? ($choice['group_name'] ?? null) : null,
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
        $quizQuestion->load(['choices', 'surveyItems']);

        return view('dashboard.quiz-questions.edit', compact('quizCompetition', 'quizQuestion'));
    }

    public function update(Request $request, QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'description' => 'nullable|string',
            'answer_type' => 'required|in:multiple_choice,custom_text,ordering,true_false,vote,fill_blank,survey',
            'is_multiple_selections' => 'nullable|boolean',
            'winners_count' => 'required|integer|min:1',
            'groups_count' => 'nullable|integer|min:1',
            'display_order' => 'nullable|integer|min:0',
            'prize' => 'nullable|array',
            'prize.*' => 'nullable|string',
            'choices' => 'required_if:answer_type,multiple_choice,ordering,true_false,vote,fill_blank|array',
            'choices.*.id' => 'nullable|integer',
            'choices.*.text' => 'nullable|string',
            'choices.*.group_name' => 'nullable|string|max:255',
            'choices.*.image' => 'nullable|image|max:5120',
            'choices.*.video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
            'choices.*.is_correct' => 'nullable|boolean',
            'correct_choices' => 'nullable|array',
            'vote_max_selections' => 'nullable|integer|min:1',
            'require_prior_registration' => 'nullable|boolean',
            'survey_items' => 'required_if:answer_type,survey|array|min:1',
            'survey_items.*.id' => 'nullable|integer',
            'survey_items.*.block_type' => 'required_if:answer_type,survey|in:image,video,text',
            'survey_items.*.body_text' => 'nullable|string',
            'survey_items.*.response_kind' => 'required_if:answer_type,survey|in:rating,number,text',
            'survey_items.*.rating_max' => 'nullable|integer|min:2|max:100',
            'survey_items.*.number_min' => 'nullable|integer',
            'survey_items.*.number_max' => 'nullable|integer',
            'survey_items.*.youtube_url' => 'nullable|string|max:2000',
            'survey_items.*.media' => 'nullable|file|max:20000',
        ], [
            'question_text.required' => 'نص السؤال مطلوب',
            'answer_type.required' => 'نوع الإجابة مطلوب',
            'winners_count.required' => 'عدد الفائزين مطلوب',
            'winners_count.min' => 'عدد الفائزين يجب أن يكون على الأقل 1',
            'groups_count.min' => 'عدد المجموعات يجب أن يكون على الأقل 1',
        ]);

        if ($validated['answer_type'] === 'survey') {
            $quizQuestion->load('surveyItems');
            $err = $this->validateSurveyPayload($request, $quizQuestion);
            if ($err) {
                return $err;
            }
        }

        $quizQuestion->update([
            'question_text' => $validated['question_text'],
            'description' => $validated['description'] ?? null,
            'answer_type' => $validated['answer_type'],
            'is_multiple_selections' => ! empty($validated['is_multiple_selections']),
            'winners_count' => $validated['winners_count'],
            'groups_count' => ($validated['answer_type'] === 'ordering') ? $validated['groups_count'] ?? null : null,
            'display_order' => $validated['display_order'] ?? 0,
            'prize' => $validated['prize'] ?? [],
            'vote_max_selections' => ($validated['answer_type'] === 'vote') ? ($validated['vote_max_selections'] ?? 1) : null,
            'require_prior_registration' => ! empty($validated['require_prior_registration']),
        ]);

        if ($validated['answer_type'] === 'survey') {
            $this->syncSurveyItemsFromRequest($quizQuestion, $request);
            $this->purgeChoicesAndMedia($quizQuestion);

            return redirect()->route('dashboard.quiz-competitions.show', $quizCompetition)
                ->with('success', 'تم تحديث السؤال بنجاح');
        }

        $quizQuestion->load('surveyItems');
        foreach ($quizQuestion->surveyItems as $si) {
            if ($si->media_path) {
                Storage::disk('public')->delete($si->media_path);
            }
            $si->delete();
        }

        if (in_array($validated['answer_type'], ['multiple_choice', 'ordering', 'true_false', 'vote', 'fill_blank']) && ! empty($validated['choices'])) {
            $oldChoices = $quizQuestion->choices->keyBy('id');
            $quizQuestion->choices()->delete();
            $choices = $validated['choices'];

            $isMultiple = ! empty($validated['is_multiple_selections']);
            $isOrdering = $validated['answer_type'] === 'ordering';
            $isVote = $validated['answer_type'] === 'vote';
            $correctChoicesKeys = $request->input('correct_choices', []);

            $isTrueFalse = $validated['answer_type'] === 'true_false';

            foreach ($choices as $index => $choice) {
                $isCorrect = false;
                if ($isOrdering) {
                    $isCorrect = true;
                } elseif ($isVote) {
                    $isCorrect = false;
                } elseif ($isMultiple) {
                    $isCorrect = in_array((string) $index, $correctChoicesKeys);
                } elseif ($isTrueFalse) {
                    $isCorrect = ! empty($choice['is_correct']) && $choice['is_correct'] != 'false' && $choice['is_correct'] != '0';
                } else {
                    $isCorrect = ! empty($choice['is_correct']) && $choice['is_correct'] != 'false' && $choice['is_correct'] != '0';
                }

                $choiceId = $choice['id'] ?? null;
                $oldChoice = $choiceId ? $oldChoices->get($choiceId) : null;

                $imagePath = $oldChoice ? $oldChoice->image : null;
                $videoPath = $oldChoice ? $oldChoice->video : null;

                if (isset($choice['image'])) {
                    if ($imagePath) {
                        Storage::disk('public')->delete($imagePath);
                    }
                    $imagePath = $choice['image']->store('quiz/choices/images', 'public');
                }
                if (isset($choice['video'])) {
                    if ($videoPath) {
                        Storage::disk('public')->delete($videoPath);
                    }
                    $videoPath = $choice['video']->store('quiz/choices/videos', 'public');
                }

                $quizQuestion->choices()->create([
                    'choice_text' => $choice['text'] ?? '',
                    'is_correct' => $isCorrect,
                    'group_name' => $isOrdering ? ($choice['group_name'] ?? null) : null,
                    'image' => $imagePath,
                    'video' => $videoPath,
                ]);
            }
        } else {
            $this->purgeChoicesAndMedia($quizQuestion);
        }

        return redirect()->route('dashboard.quiz-competitions.show', $quizCompetition)
            ->with('success', 'تم تحديث السؤال بنجاح');
    }

    private function purgeChoicesAndMedia(QuizQuestion $quizQuestion): void
    {
        foreach ($quizQuestion->choices as $choice) {
            if ($choice->image) {
                Storage::disk('public')->delete($choice->image);
            }
            if ($choice->video) {
                Storage::disk('public')->delete($choice->video);
            }
        }
        $quizQuestion->choices()->delete();
    }

    private function validateSurveyPayload(Request $request, ?QuizQuestion $existingQuestion): ?RedirectResponse
    {
        $rows = $request->input('survey_items', []);
        if (! is_array($rows) || count($rows) < 1) {
            return back()->withInput()->withErrors(['survey_items' => 'أضف عنصراً واحداً على الأقل للاستبيان.']);
        }
        ksort($rows, SORT_NATURAL);
        foreach ($rows as $key => $item) {
            $blockType = $item['block_type'] ?? '';
            $responseKind = $item['response_kind'] ?? '';
            if ($blockType === 'text' && trim((string) ($item['body_text'] ?? '')) === '') {
                return back()->withInput()->withErrors(["survey_items.$key.body_text" => 'أدخل النص للعنصر النصي.']);
            }
            $file = $request->file("survey_items.$key.media");
            if ($blockType === 'image') {
                $existingRow = $this->findSurveyRow($existingQuestion, (int) ($item['id'] ?? 0));
                $keep = $existingRow && $existingRow->block_type === 'image' && $existingRow->media_path && ! $file;
                if (! $file && ! $keep) {
                    return back()->withInput()->withErrors(["survey_items.$key.media" => 'ارفع صورة لهذا العنصر.']);
                }
                if ($file) {
                    $v = \Illuminate\Support\Facades\Validator::make(['f' => $file], ['f' => 'image|max:5120']);
                    if ($v->fails()) {
                        return back()->withInput()->withErrors(["survey_items.$key.media" => 'صورة غير صالحة أو أكبر من 5 ميجا.']);
                    }
                }
            }
            if ($blockType === 'video') {
                $existingRow = $this->findSurveyRow($existingQuestion, (int) ($item['id'] ?? 0));
                $youtubeUrl = $this->normalizeYouTubeUrl($item['youtube_url'] ?? null);
                $keep = $existingRow
                    && $existingRow->block_type === 'video'
                    && ! $file
                    && empty($youtubeUrl)
                    && (! empty($existingRow->media_path) || ! empty($existingRow->youtube_url));

                if (! $file && empty($youtubeUrl) && ! $keep) {
                    return back()->withInput()->withErrors([
                        "survey_items.$key.youtube_url" => 'ارفع فيديو لهذا العنصر أو الصق رابط يوتيوب.',
                    ]);
                }

                if (! empty($youtubeUrl)) {
                    $id = $this->extractYouTubeIdFromUrl($youtubeUrl);
                    if (! $id) {
                        return back()->withInput()->withErrors([
                            "survey_items.$key.youtube_url" => 'رابط يوتيوب غير صالح.',
                        ]);
                    }
                }

                if ($file) {
                    $v = \Illuminate\Support\Facades\Validator::make(['f' => $file], ['f' => 'mimes:mp4,mov,ogg,qt|max:20000']);
                    if ($v->fails()) {
                        return back()->withInput()->withErrors(["survey_items.$key.media" => 'الفيديو يجب أن يكون mp4/mov/ogg/qt وبحد أقصى ~20 ميجا.']);
                    }
                }
            }
            if ($responseKind === 'number') {
                $min = (int) ($item['number_min'] ?? 0);
                $max = (int) ($item['number_max'] ?? 0);
                if ($min > $max) {
                    return back()->withInput()->withErrors(["survey_items.$key.number_max" => 'الحد الأعلى يجب أن يكون أكبر أو يساوي الأدنى.']);
                }
            }
        }

        return null;
    }

    private function findSurveyRow(?QuizQuestion $q, int $id): ?QuizSurveyItem
    {
        if (! $q || $id <= 0) {
            return null;
        }

        return $q->surveyItems->firstWhere('id', $id);
    }

    private function persistSurveyItemsFromRequest(QuizQuestion $question, Request $request): void
    {
        $rows = $request->input('survey_items', []);
        ksort($rows, SORT_NATURAL);
        $sort = 0;
        foreach ($rows as $key => $item) {
            $sort++;
            $mediaPath = $this->storeSurveyMedia($request, $key, $item['block_type']);
            $youtubeUrl = $item['block_type'] === 'video' ? $this->normalizeYouTubeUrl($item['youtube_url'] ?? null) : null;
            if ($item['block_type'] === 'video' && $mediaPath) {
                // If user uploaded a file, prefer it over YouTube.
                $youtubeUrl = null;
            }
            $question->surveyItems()->create($this->surveyItemAttributes($item, $sort, $mediaPath, $youtubeUrl));
        }
    }

    private function syncSurveyItemsFromRequest(QuizQuestion $question, Request $request): void
    {
        $rows = $request->input('survey_items', []);
        ksort($rows, SORT_NATURAL);
        $incomingIds = [];
        foreach ($rows as $item) {
            if (! empty($item['id'])) {
                $incomingIds[] = (int) $item['id'];
            }
        }
        $disk = Storage::disk('public');
        foreach ($question->surveyItems as $old) {
            if (! in_array($old->id, $incomingIds, true)) {
                if ($old->media_path) {
                    $disk->delete($old->media_path);
                }
                $old->delete();
            }
        }
        $sort = 0;
        foreach ($rows as $key => $item) {
            $sort++;
            $incomingYoutubeUrl = $item['block_type'] === 'video'
                ? $this->normalizeYouTubeUrl($item['youtube_url'] ?? null)
                : null;
            $attrs = $this->surveyItemAttributes($item, $sort, null, $incomingYoutubeUrl);
            if (! empty($item['id'])) {
                $row = QuizSurveyItem::where('quiz_question_id', $question->id)->find($item['id']);
                if (! $row) {
                    continue;
                }
                if ($item['block_type'] === 'text') {
                    if ($row->media_path) {
                        $disk->delete($row->media_path);
                    }
                    $attrs['media_path'] = null;
                    $attrs['youtube_url'] = null;
                } else {
                    $file = $request->file("survey_items.$key.media");

                    if ($item['block_type'] === 'image') {
                        $attrs['youtube_url'] = null;
                        if ($file) {
                            if ($row->media_path) {
                                $disk->delete($row->media_path);
                            }
                            $attrs['media_path'] = $file->store('quiz/survey/images', 'public');
                        } else {
                            if ($row->block_type !== $item['block_type'] && $row->media_path) {
                                $disk->delete($row->media_path);
                                $attrs['media_path'] = null;
                            } else {
                                $attrs['media_path'] = $row->media_path;
                            }
                        }
                    } elseif ($item['block_type'] === 'video') {
                        // Prefer uploaded file if present.
                        if ($file) {
                            if ($row->media_path) {
                                $disk->delete($row->media_path);
                            }
                            $attrs['media_path'] = $file->store('quiz/survey/videos', 'public');
                            $attrs['youtube_url'] = null;
                        } elseif (! empty($attrs['youtube_url'])) {
                            // If YouTube URL provided, clear media_path (and delete old file).
                            if ($row->media_path) {
                                $disk->delete($row->media_path);
                            }
                            $attrs['media_path'] = null;
                        } else {
                            // No new input => keep existing content.
                            $attrs['media_path'] = $row->media_path;
                            $attrs['youtube_url'] = $row->youtube_url;
                        }
                    }
                }
                $row->update($attrs);
            } else {
                $attrs['media_path'] = $this->storeSurveyMedia($request, $key, $item['block_type']);
                if ($item['block_type'] === 'video' && ! empty($attrs['media_path'])) {
                    // Prefer uploaded file over YouTube when both exist.
                    $attrs['youtube_url'] = null;
                }
                $question->surveyItems()->create($attrs);
            }
        }
    }

    private function surveyItemAttributes(array $item, int $sort, ?string $mediaPath, ?string $youtubeUrl): array
    {
        $responseKind = $item['response_kind'];
        $ratingMax = max(2, min(100, (int) ($item['rating_max'] ?? 10)));

        return [
            'sort_order' => $sort,
            'block_type' => $item['block_type'],
            'body_text' => $item['body_text'] ?? null,
            'media_path' => $mediaPath,
            'youtube_url' => $youtubeUrl,
            'response_kind' => $responseKind,
            'rating_max' => $responseKind === 'rating' ? $ratingMax : 10,
            'number_min' => $responseKind === 'number' ? (int) ($item['number_min'] ?? 0) : null,
            'number_max' => $responseKind === 'number' ? (int) ($item['number_max'] ?? 100) : null,
        ];
    }

    private function storeSurveyMedia(Request $request, string|int $key, string $blockType): ?string
    {
        if ($blockType === 'text') {
            return null;
        }
        $file = $request->file("survey_items.$key.media");
        if (! $file) {
            return null;
        }

        return $blockType === 'image'
            ? $file->store('quiz/survey/images', 'public')
            : $file->store('quiz/survey/videos', 'public');
    }

    private function normalizeYouTubeUrl(?string $url): ?string
    {
        $url = trim((string) ($url ?? ''));
        if ($url === '') {
            return null;
        }

        // Allow pasting raw 11-char YouTube ID.
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return 'https://www.youtube.com/watch?v='.$url;
        }

        return $url;
    }

    private function extractYouTubeIdFromUrl(string $url): ?string
    {
        $pattern = '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/';
        if (preg_match($pattern, $url, $m)) {
            return $m[1];
        }

        return null;
    }

    public function destroy(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        $quizQuestion->delete();

        return redirect()->route('dashboard.quiz-competitions.show', $quizCompetition)
            ->with('success', 'تم حذف السؤال بنجاح');
    }

    public function selectWinners(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        if (! $quizQuestion->hasEnded()) {
            return back()->with('error', 'لا يمكن اختيار الفائزين قبل انتهاء فترة الإجابة');
        }

        $count = $quizQuestion->selectRandomWinners();

        return back()->with('success', "تم اختيار {$count} فائز عشوائياً");
    }

    public function removeWinner(\App\Models\QuizWinner $winner): RedirectResponse
    {
        $questionId = $winner->quiz_question_id;
        $winner->delete();

        \Illuminate\Support\Facades\Cache::forget('quiz_winner_json_'.$questionId);

        return back()->with('success', 'تم إزالة الفائز بنجاح');
    }

    public function fillWinners(QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        if (! $quizQuestion->hasEnded()) {
            return back()->with('error', 'لا يمكن اختيار الفائزين قبل انتهاء فترة الإجابة');
        }

        $count = $quizQuestion->fillVacantWinners();

        if ($count > 0) {
            return back()->with('success', "تم اختيار {$count} فائز إضافي عشوائياً");
        }

        return back()->with('info', 'لا يوجد خانات فائزين شاغرة حالياً');
    }

    public function reorderWinners(Request $request, QuizCompetition $quizCompetition, QuizQuestion $quizQuestion): RedirectResponse
    {
        $winnerIds = $request->input('winner_ids', []);

        foreach ($winnerIds as $index => $id) {
            \App\Models\QuizWinner::where('id', $id)
                ->where('quiz_question_id', $quizQuestion->id)
                ->update(['position' => $index + 1]);
        }

        \Illuminate\Support\Facades\Cache::forget('quiz_winner_json_'.$quizQuestion->id);

        return back()->with('success', 'تم إعادة ترتيب الفائزين بنجاح');
    }
}
