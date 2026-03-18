@extends('layouts.app')

@section('title', 'لوحة إجابات: ' . $quizCompetition->title)

@section('content')
@php
    $filterQs = request()->only(['q', 'correct', 'from', 'to']);
    $typeLabel = function ($q) {
        return match ($q->answer_type) {
            'multiple_choice' => 'اختيار من متعدد',
            'vote' => 'تصويت',
            'true_false' => 'صح / خطأ',
            'ordering' => 'ترتيب',
            'survey' => 'استبيان',
            default => 'إجابة حرة',
        };
    };
    $typeBadge = function ($q) {
        return match ($q->answer_type) {
            'multiple_choice' => 'info',
            'vote' => 'primary',
            'true_false' => 'warning',
            'ordering' => 'success',
            'survey' => 'success',
            default => 'secondary',
        };
    };
@endphp
<div class="container-fluid quiz-responses-page">
    <div class="quiz-responses-hero mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div class="mb-2 mb-md-0">
                <h1 class="h4 mb-1 text-white font-weight-bold">
                    <i class="fas fa-chart-bar ml-2"></i>لوحة الإجابات والإحصائيات
                </h1>
                <p class="mb-0 text-white-50 small">{{ $quizCompetition->title }}</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('dashboard.quiz-competitions.show', $quizCompetition) }}" class="btn btn-light btn-sm shadow-sm">
                    <i class="fas fa-arrow-right ml-1"></i>تفاصيل المسابقة
                </a>
                <a href="{{ route('dashboard.quiz-competitions.export', $quizCompetition) }}" class="btn btn-outline-light btn-sm" target="_blank" rel="noopener">
                    <i class="fas fa-file-excel ml-1"></i>Excel
                </a>
            </div>
        </div>
    </div>

    @if(!$activeQuestion)
        <div class="card shadow-sm border-0 text-center py-5">
            <div class="card-body">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">لا توجد أسئلة في هذه المسابقة.</p>
                <a href="{{ route('dashboard.quiz-competitions.show', $quizCompetition) }}" class="btn btn-primary mt-3">العودة</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm border-0 sticky-top quiz-responses-sidebar" style="top: 1rem; z-index: 10;">
                    <div class="card-header bg-white border-bottom py-3">
                        <span class="font-weight-bold text-primary"><i class="fas fa-list-ul ml-2"></i>الأسئلة</span>
                    </div>
                    <div class="list-group list-group-flush quiz-question-nav">
                        @foreach($questions as $q)
                            <a href="{{ route('dashboard.quiz-competitions.responses', array_merge($filterQs, ['quizCompetition' => $quizCompetition, 'question_id' => $q->id])) }}"
                               class="list-group-item list-group-item-action border-0 py-3 {{ $q->id === $activeQuestion->id ? 'active font-weight-bold' : '' }}">
                                <span class="d-block small text-muted mb-1">سؤال {{ $loop->iteration }}</span>
                                <span class="d-block question-nav-preview">{!! Str::limit(strip_tags($q->question_text), 55) !!}</span>
                                <span class="badge badge-{{ $typeBadge($q) }} mt-2">{{ $typeLabel($q) }}</span>
                                <span class="badge badge-light border ml-1">{{ $q->answers_count }} رد</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                {{-- بطاقات إحصائية --}}
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm h-100 quiz-stat-card quiz-stat-total">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center ml-3" style="width:48px;height:48px;">
                                        <i class="fas fa-paper-plane"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">إجمالي الردود</div>
                                        <div class="h4 mb-0 font-weight-bold">{{ number_format($totalAnswerCount) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!in_array($activeQuestion->answer_type, ['vote', 'survey'], true))
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm h-100 quiz-stat-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center ml-3" style="width:48px;height:48px;">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">صحيح</div>
                                            <div class="h4 mb-0 font-weight-bold text-success">{{ number_format($correctCount) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm h-100 quiz-stat-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center ml-3" style="width:48px;height:48px;">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">خاطئ</div>
                                            <div class="h4 mb-0 font-weight-bold text-danger">{{ number_format($wrongCount) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($activeQuestion->answer_type === 'survey')
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm h-100 quiz-stat-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center ml-3" style="width:48px;height:48px;">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">بنود الاستبيان</div>
                                            <div class="h4 mb-0 font-weight-bold">{{ $activeQuestion->surveyItems->count() }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm h-100 quiz-stat-card border-right border-success" style="border-width: 3px !important;">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-info-circle text-success mb-2 d-block" style="font-size:1.5rem;"></i>
                                    <div class="small font-weight-bold text-dark">استبيان تجميعي</div>
                                    <div class="text-muted small mt-1">بدون تصحيح تلقائي أو فائزين</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm h-100 quiz-stat-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center ml-3" style="width:48px;height:48px;">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">عدد المصوّتين</div>
                                            <div class="h4 mb-0 font-weight-bold">{{ number_format($totalAnswerCount) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm h-100 quiz-stat-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center ml-3" style="width:48px;height:48px;">
                                            <i class="fas fa-list"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">خيارات التصويت</div>
                                            <div class="h4 mb-0 font-weight-bold">{{ $activeQuestion->choices->count() }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if($lastAnswerAt)
                    <p class="text-muted small mb-3"><i class="far fa-clock ml-1"></i>آخر رد: {{ \Carbon\Carbon::parse($lastAnswerAt)->format('Y-m-d H:i') }}</p>
                @endif

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <span class="badge badge-{{ $typeBadge($activeQuestion) }} mb-2">{{ $typeLabel($activeQuestion) }}</span>
                        <h2 class="h5 font-weight-bold text-dark mb-2 question-text">{!! $activeQuestion->question_text !!}</h2>
                        @if($activeQuestion->description)
                            <div class="text-muted small quiz-description mb-0">{!! Str::limit(strip_tags($activeQuestion->description), 300) !!}</div>
                        @endif
                    </div>
                </div>

                @if($activeQuestion->answer_type === 'vote' && count($voteStats))
                    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0 font-weight-bold"><i class="fas fa-percentage text-primary ml-2"></i>توزيع التصويت</h6>
                        </div>
                        <div class="card-body">
                            @foreach($voteStats as $vs)
                                @php $choiceRow = $vs['choice']; @endphp
                                <div class="mb-4 last-mb-0">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="font-weight-bold">{!! Str::limit(strip_tags($choiceRow->choice_text), 120) !!}</span>
                                        <span class="text-nowrap mr-2">
                                            <strong>{{ $vs['percent'] }}%</strong>
                                            <span class="text-muted small">({{ $vs['count'] }})</span>
                                        </span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 14px;">
                                        <div class="progress-bar bg-gradient-vote" role="progressbar"
                                             style="width: {{ min(100, $vs['percent']) }}%;"
                                             aria-valuenow="{{ $vs['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($activeQuestion->answer_type === 'survey' && count($surveySummary))
                    <div class="row mb-4">
                        @foreach($surveySummary as $sum)
                            @php $item = $sum['item']; @endphp
                            <div class="col-md-6 mb-4">
                                <div class="card shadow-sm border-0 h-100 survey-item-card">
                                    <div class="card-header bg-light border-0">
                                        <small class="text-muted d-block mb-1">
                                            @if($sum['kind'] === 'rating') تقييم
                                            @elseif($sum['kind'] === 'number') رقم
                                            @else نص @endif
                                        </small>
                                        <strong class="d-block">{!! Str::limit(strip_tags($item->body_text ?: $item->labelForAdmin()), 80) !!}</strong>
                                    </div>
                                    <div class="card-body">
                                        @if($sum['kind'] === 'rating')
                                            <div class="display-4 text-primary mb-3">{{ $sum['avg'] ?? '—' }}<span class="h6 text-muted">/متوسط</span></div>
                                            <div class="text-muted small mb-2">{{ $sum['count'] }} تقييم</div>
                                            @if(!empty($sum['distribution']))
                                                @foreach($sum['distribution'] as $star => $cnt)
                                                    <div class="d-flex align-items-center mb-1 small">
                                                        <span class="text-muted" style="width:4rem;">{{ $star }} ★</span>
                                                        <div class="flex-grow-1 progress mx-2" style="height:8px;">
                                                            <div class="progress-bar bg-warning" style="width: {{ $sum['max_bar'] ? round(100 * $cnt / $sum['max_bar']) : 0 }}%"></div>
                                                        </div>
                                                        <span class="text-muted">{{ $cnt }}</span>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @elseif($sum['kind'] === 'number')
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <div class="text-muted small">أدنى</div>
                                                    <div class="h5 mb-0">{{ $sum['min'] ?? '—' }}</div>
                                                </div>
                                                <div class="col-4 border-right border-left">
                                                    <div class="text-muted small">متوسط</div>
                                                    <div class="h5 mb-0 text-primary">{{ $sum['avg'] ?? '—' }}</div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-muted small">أقصى</div>
                                                    <div class="h5 mb-0">{{ $sum['max'] ?? '—' }}</div>
                                                </div>
                                            </div>
                                            <p class="text-muted small mt-2 mb-0">{{ $sum['count'] }} رقم مسجّل</p>
                                        @else
                                            <p class="h4 mb-2">{{ $sum['count'] }} <span class="h6 text-muted">رد نصي</span></p>
                                            @if(!empty($sum['samples']))
                                                <ul class="list-unstyled small text-muted mb-0 survey-samples">
                                                    @foreach($sum['samples'] as $sample)
                                                        <li class="border-bottom py-1"><i class="fas fa-quote-right text-muted ml-1" style="font-size:.65rem;"></i>{{ Str::limit($sample, 100) }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 font-weight-bold text-secondary"><i class="fas fa-filter ml-2"></i>تصفية الردود</h6>
                    </div>
                    <div class="card-body">
                        <form method="get" action="{{ route('dashboard.quiz-competitions.responses', $quizCompetition) }}" class="row align-items-end">
                            <input type="hidden" name="question_id" value="{{ $activeQuestion->id }}">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="small font-weight-bold text-muted">بحث (اسم / هاتف)</label>
                                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="…">
                            </div>
                            @if(!in_array($activeQuestion->answer_type, ['vote', 'survey'], true))
                                <div class="col-md-2 mb-3 mb-md-0">
                                    <label class="small font-weight-bold text-muted">النتيجة</label>
                                    <select name="correct" class="form-control">
                                        <option value="">الكل</option>
                                        <option value="1" @selected(request('correct') === '1')>صحيح</option>
                                        <option value="0" @selected(request('correct') === '0')>خاطئ</option>
                                    </select>
                                </div>
                            @endif
                            <div class="col-md-2 mb-3 mb-md-0">
                                <label class="small font-weight-bold text-muted">من تاريخ</label>
                                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                            </div>
                            <div class="col-md-2 mb-3 mb-md-0">
                                <label class="small font-weight-bold text-muted">إلى تاريخ</label>
                                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search ml-1"></i>تطبيق</button>
                                <a href="{{ route('dashboard.quiz-competitions.responses', ['quizCompetition' => $quizCompetition, 'question_id' => $activeQuestion->id]) }}" class="btn btn-outline-secondary btn-block mt-1">إعادة ضبط</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap">
                        <span class="font-weight-bold"><i class="fas fa-table ml-2 text-primary"></i>سجل الردود</span>
                        <span class="badge badge-light border">{{ $answers->total() }} سجل</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>المشارك</th>
                                    <th>الهاتف</th>
                                    <th>اسم الأم</th>
                                    <th>الإجابة</th>
                                    <th>
                                        @if(in_array($activeQuestion->answer_type, ['vote', 'survey'], true))
                                            النوع
                                        @else
                                            النتيجة
                                        @endif
                                    </th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($answers as $i => $answer)
                                    <tr>
                                        <td>{{ $answers->firstItem() + $i }}</td>
                                        <td>{{ $answer->user->name ?? '—' }}</td>
                                        <td dir="ltr">{{ $answer->user->phone ?? '—' }}</td>
                                        <td>
                                            @if($answer->user && $answer->user->is_from_ancestry && $answer->user->mother_name)
                                                <span class="badge badge-info">{{ $answer->user->mother_name }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td style="max-width: 320px;">
                                            <x-quiz-answer-cell :question="$activeQuestion" :answer="$answer" :limit="600" />
                                        </td>
                                        <td>
                                            @if($activeQuestion->answer_type === 'vote')
                                                <span class="badge badge-primary">تصويت</span>
                                            @elseif($activeQuestion->answer_type === 'survey')
                                                <span class="badge badge-success">استبيان</span>
                                            @elseif($answer->is_correct)
                                                <span class="badge badge-success">صحيح</span>
                                            @else
                                                <span class="badge badge-danger">خاطئ</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap small">{{ $answer->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="fas fa-search mb-2 d-block fa-2x"></i>
                                            لا توجد ردود تطابق التصفية.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($answers->hasPages())
                        <div class="card-footer bg-white border-top">
                            {{ $answers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.quiz-responses-page { direction: rtl; text-align: right; }
.quiz-responses-hero {
    background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 45%, #1a4d6d 100%);
    border-radius: 1rem;
    padding: 1.5rem 1.75rem;
    box-shadow: 0 8px 32px rgba(30, 58, 95, 0.25);
}
.quiz-responses-sidebar .list-group-item.active {
    background: linear-gradient(90deg, #e8f4fc 0%, #fff 100%);
    border-right: 4px solid #2d5a87 !important;
    color: #1e3a5f !important;
}
.quiz-question-nav .question-nav-preview { font-size: 0.9rem; line-height: 1.35; }
.quiz-stat-card { transition: transform 0.15s ease; }
.quiz-stat-card:hover { transform: translateY(-2px); }
.quiz-stat-total .card-body { border-radius: 0.5rem; }
.bg-gradient-vote {
    background: linear-gradient(90deg, #2d5a87, #4a90c2) !important;
}
.survey-item-card { border-radius: 0.75rem; overflow: hidden; }
.survey-samples li:last-child { border-bottom: 0 !important; }
.last-mb-0:last-child { margin-bottom: 0 !important; }
.gap-2 { gap: 0.5rem; }
</style>
@endpush
