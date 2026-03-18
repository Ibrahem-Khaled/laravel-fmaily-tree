@props(['question', 'answer', 'limit' => 500])
@php
    use Illuminate\Support\Str;
    $out = '';
    if ($answer->answer_type === 'survey') {
        $surveyDecoded = json_decode($answer->answer, true);
        $surveyParts = [];
        if (is_array($surveyDecoded)) {
            foreach ($surveyDecoded as $sid => $entry) {
                $sit = $question->surveyItems->firstWhere('id', (int) $sid);
                // لا نقص الـ label داخل المكوّن، لأن ميزة "عرض المزيد" هتتعامل مع طول النص.
                $lab = $sit ? strip_tags($sit->body_text ?: $sit->labelForAdmin()) : '#'.$sid;
                $v = is_array($entry) ? ($entry['v'] ?? '') : '';
                $k = is_array($entry) ? ($entry['k'] ?? '') : '';
                if ($k === 'rating') {
                    $surveyParts[] = $lab . ': تقييم ' . $v;
                } elseif ($k === 'number') {
                    $surveyParts[] = $lab . ': ' . $v;
                } else {
                    $surveyParts[] = $lab . ': ' . (string) $v;
                }
            }
        }
        $out = implode(' · ', $surveyParts) ?: (string) $answer->answer;
    } elseif (in_array($answer->answer_type, ['multiple_choice', 'vote', 'ordering', 'true_false', 'choice'], true)) {
        $decoded = json_decode($answer->answer, true);
        if (is_array($decoded)) {
            if ($answer->answer_type === 'true_false') {
                $parts = [];
                foreach ($decoded as $choiceId => $val) {
                    $c = $question->choices->firstWhere('id', $choiceId);
                    $cText = $c ? strip_tags($c->choice_text) : $choiceId;
                    $valText = ($val === '1' || $val === 'true' || $val === true) ? 'صح' : 'خطأ';
                    $parts[] = $cText . ': ' . $valText;
                }
                $out = implode(' | ', $parts);
            } else {
                $choiceTexts = [];
                foreach ($decoded as $choiceId) {
                    $c = $question->choices->firstWhere('id', $choiceId);
                    if ($c) {
                        $choiceTexts[] = strip_tags($c->choice_text);
                    }
                }
                $out = implode(' — ', $choiceTexts);
            }
        } else {
            $c = $question->choices->firstWhere('id', (int) $answer->answer);
            $out = $c ? strip_tags($c->choice_text) : (string) $answer->answer;
        }
    } else {
        $out = (string) $answer->answer;
    }
    $fullText = (string) $out;
    $limitInt = max(0, (int) $limit);
    $isTruncated = $limitInt > 0 && Str::length($fullText) > $limitInt;
    $shortText = $isTruncated ? Str::limit($fullText, $limitInt) : $fullText;

    $uid = 'quiz-answer-cell-' . ($answer->id ?? md5($question->id . '|' . ($answer->user_id ?? '') . '|' . ($answer->created_at?->format('c') ?? '') . '|' . $fullText));
@endphp

@once
    @push('styles')
    <style>
        .quiz-answer-cell {
            white-space: normal;
        }
        .quiz-answer-cell-toggle {
            display: none;
        }
        .quiz-answer-cell-text-wrapper {
            white-space: pre-wrap;
            word-break: break-word;
        }
        .quiz-answer-cell-text-full {
            display: none;
        }
        .quiz-answer-cell-label {
            display: inline-block;
            font-size: .85rem;
            margin-top: 4px;
            cursor: pointer;
            color: #0d6efd;
            user-select: none;
        }
        .quiz-answer-cell-label--less {
            display: none;
        }
        .quiz-answer-cell-toggle:checked ~ .quiz-answer-cell-text-wrapper .quiz-answer-cell-text-short {
            display: none;
        }
        .quiz-answer-cell-toggle:checked ~ .quiz-answer-cell-text-wrapper .quiz-answer-cell-text-full {
            display: inline;
        }
        .quiz-answer-cell-toggle:checked ~ .quiz-answer-cell-label--more {
            display: none;
        }
        .quiz-answer-cell-toggle:checked ~ .quiz-answer-cell-label--less {
            display: inline-block;
        }
    </style>
    @endpush
@endonce

@if($isTruncated)
    <div class="quiz-answer-cell">
        <input class="quiz-answer-cell-toggle" type="checkbox" id="{{ $uid }}">
        <div class="quiz-answer-cell-text-wrapper">
            <span class="quiz-answer-cell-text quiz-answer-cell-text-short" dir="auto">{{ $shortText }}</span>
            <span class="quiz-answer-cell-text quiz-answer-cell-text-full" dir="auto">{{ $fullText }}</span>
        </div>
        <label class="quiz-answer-cell-label quiz-answer-cell-label--more" for="{{ $uid }}">عرض المزيد</label>
        <label class="quiz-answer-cell-label quiz-answer-cell-label--less" for="{{ $uid }}">عرض أقل</label>
    </div>
@else
    <span class="quiz-answer-cell-text" dir="auto">{{ $fullText }}</span>
@endif
