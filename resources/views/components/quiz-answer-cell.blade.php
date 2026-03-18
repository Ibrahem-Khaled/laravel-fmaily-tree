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
                $lab = $sit ? Str::limit(strip_tags($sit->body_text ?: $sit->labelForAdmin()), 50) : '#'.$sid;
                $v = is_array($entry) ? ($entry['v'] ?? '') : '';
                $k = is_array($entry) ? ($entry['k'] ?? '') : '';
                if ($k === 'rating') {
                    $surveyParts[] = $lab . ': تقييم ' . $v;
                } elseif ($k === 'number') {
                    $surveyParts[] = $lab . ': ' . $v;
                } else {
                    $surveyParts[] = $lab . ': ' . Str::limit((string) $v, 80);
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
    $display = Str::limit($out, (int) $limit);
@endphp
<span class="quiz-answer-cell-text" dir="auto">{{ $display }}</span>
