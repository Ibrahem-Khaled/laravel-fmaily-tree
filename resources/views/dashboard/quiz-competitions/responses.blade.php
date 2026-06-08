@extends('layouts.app')

@section('title', 'نتائج استطلاع الرأي: ' . $quizCompetition->title)

@section('content')
@php
    $filterQs = request()->only(['q', 'correct', 'from', 'to']);
    $typeLabel = function ($q) {
        return match ($q->answer_type) {
            'multiple_choice' => 'اختيار من متعدد',
            'vote' => 'تصويت / استطلاع رأي',
            'true_false' => 'صح / خطأ',
            'ordering' => 'ترتيب العناصر',
            'survey' => 'تقييم / استبيان',
            default => 'إجابة حرة',
        };
    };
    $typeBadge = function ($q) {
        return match ($q->answer_type) {
            'multiple_choice' => 'info',
            'vote' => 'success',
            'true_false' => 'warning',
            'ordering' => 'primary',
            'survey' => 'teal',
            default => 'secondary',
        };
    };
@endphp

@push('styles')
@include('dashboard.quiz-competitions._styles')
@endpush

<div class="container-fluid quiz-responses-page py-4">
    
    <!-- Hero Header -->
    <div class="quiz-responses-hero mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="h3 mb-1 text-white font-weight-bold">
                    <i class="fas fa-chart-pie ml-2 text-warning"></i>إحصائيات استطلاع الرأي والنتائج
                </h1>
                <p class="mb-0 text-teal-100 font-weight-bold" style="font-size: 0.95rem;">
                    {{ $quizCompetition->title }}
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('dashboard.quiz-competitions.show', $quizCompetition) }}" class="modern-btn modern-btn-light shadow-sm text-decoration-none">
                    <i class="fas fa-arrow-right"></i>تفاصيل الاستطلاع
                </a>
                <a href="{{ route('dashboard.quiz-competitions.export', $quizCompetition) }}" class="modern-btn modern-btn-outline shadow-sm text-decoration-none" target="_blank" rel="noopener">
                    <i class="fas fa-file-excel"></i>تصدير البيانات إلى Excel
                </a>
            </div>
        </div>
    </div>

    @if(!$activeQuestion)
        <div class="card modern-card text-center py-5">
            <div class="card-body">
                <div class="mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle" style="width: 80px; height: 80px;">
                        <i class="fas fa-poll-h fa-3x"></i>
                    </span>
                </div>
                <h5 class="text-gray-800 font-weight-bold">لا توجد أسئلة أو بنود حالياً</h5>
                <p class="text-muted max-w-sm mx-auto mb-4">أضف بنود تصويت أو أسئلة في الاستطلاع لعرض الإحصائيات.</p>
                <a href="{{ route('dashboard.quiz-competitions.show', $quizCompetition) }}" class="modern-btn modern-btn-primary text-decoration-none">تفاصيل الاستطلاع</a>
            </div>
        </div>
    @else
        <div class="row">
            
            <!-- Sidebar: Questions selection list -->
            <div class="col-xl-3 col-lg-4 mb-4">
                <div class="card modern-card sticky-top quiz-responses-sidebar" style="top: 1.5rem; z-index: 10;">
                    <div class="card-header bg-white border-bottom py-3">
                        <span class="font-weight-bold text-gray-800"><i class="fas fa-poll text-teal ml-2"></i>بنود الاستطلاع</span>
                    </div>
                    <div class="list-group list-group-flush quiz-question-nav">
                        @foreach($questions as $q)
                            <a href="{{ route('dashboard.quiz-competitions.responses', array_merge($filterQs, ['quizCompetition' => $quizCompetition, 'question_id' => $q->id])) }}"
                               class="list-group-item list-group-item-action border-0 py-3 text-decoration-none d-flex flex-column {{ $q->id === $activeQuestion->id ? 'active font-weight-bold' : '' }}">
                                <span class="d-block text-xs font-weight-bold text-muted mb-1">البند {{ sprintf('%02d', $loop->iteration) }}</span>
                                <span class="d-block question-nav-preview text-gray-800 font-weight-bold text-right mb-2">{!! Str::limit(strip_tags($q->question_text), 50) !!}</span>
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mt-auto">
                                    <span class="badge-modern badge-modern-{{ $typeBadge($q) }} px-2 py-1" style="font-size:0.7rem !important;">{{ $typeLabel($q) }}</span>
                                    <span class="badge-modern badge-modern-secondary px-2 py-1" style="font-size:0.7rem !important;">{{ $q->answers_count }} مشارك</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Statistics and Responses logs area -->
            <div class="col-xl-9 col-lg-8">
                
                <!-- Stat Cards -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card quiz-stat-card h-100 py-2">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stat-circle-wrap stat-circle-teal ml-3">
                                        <i class="fas fa-paper-plane"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-weight-bold text-gray-500 mb-1">إجمالي الردود والمشاركات</div>
                                        <div class="h4 mb-0 font-weight-extrabold text-gray-850">{{ number_format($totalAnswerCount) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!in_array($activeQuestion->answer_type, ['vote', 'survey'], true))
                        <div class="col-md-4 mb-3">
                            <div class="card quiz-stat-card h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-circle-wrap stat-circle-success ml-3">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-gray-500 mb-1">إجابات صحيحة</div>
                                            <div class="h4 mb-0 font-weight-extrabold text-success">{{ number_format($correctCount) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card quiz-stat-card h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-circle-wrap stat-circle-danger ml-3">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-gray-500 mb-1">إجابات خاطئة</div>
                                            <div class="h4 mb-0 font-weight-extrabold text-danger">{{ number_format($wrongCount) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($activeQuestion->answer_type === 'survey')
                        <div class="col-md-4 mb-3">
                            <div class="card quiz-stat-card h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-circle-wrap stat-circle-success ml-3">
                                            <i class="fas fa-list-ol"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-gray-500 mb-1">بنود التقييم المضافة</div>
                                            <div class="h4 mb-0 font-weight-extrabold">{{ $activeQuestion->surveyItems->count() }} بند</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card quiz-stat-card h-100 py-2 border-right border-teal" style="border-width: 3px !important;">
                                <div class="card-body text-center d-flex flex-column justify-content-center py-2">
                                    <div class="text-teal font-weight-bold text-sm mb-1">
                                        <i class="fas fa-info-circle mr-1"></i> استطلاع للرأي / استبيان
                                    </div>
                                    <div class="text-muted text-xs">تقييمات إحصائية بدون نقاط أو فائزين</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-4 mb-3">
                            <div class="card quiz-stat-card h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-circle-wrap stat-circle-blue ml-3">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-gray-500 mb-1">عدد المصوتين</div>
                                            <div class="h4 mb-0 font-weight-extrabold">{{ number_format($totalAnswerCount) }} مصوّت</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card quiz-stat-card h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-circle-wrap stat-circle-success ml-3">
                                            <i class="fas fa-list-ul"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-gray-500 mb-1">الخيارات المتاحة</div>
                                            <div class="h4 mb-0 font-weight-extrabold">{{ $activeQuestion->choices->count() }} خيار</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                @if($lastAnswerAt)
                    <div class="text-muted small mb-3">
                        <i class="far fa-clock ml-1"></i> آخر مشاركة سجلت بتاريخ: 
                        <strong class="text-gray-800">{{ \Carbon\Carbon::parse($lastAnswerAt)->format('Y-m-d H:i') }}</strong>
                    </div>
                @endif

                <!-- Active Question Detail -->
                <div class="card modern-card mb-4 overflow-hidden">
                    <div class="card-body p-4 bg-light-gradient">
                        <span class="badge-modern badge-modern-{{ $typeBadge($activeQuestion) }} mb-2">
                            {{ $typeLabel($activeQuestion) }}
                        </span>
                        <h4 class="h5 font-weight-bold text-gray-900 question-text mb-2">
                            {!! $activeQuestion->question_text !!}
                        </h4>
                        @if($activeQuestion->description)
                            <div class="text-muted small quiz-description mb-0 mt-2">
                                {!! Str::limit(strip_tags($activeQuestion->description), 300) !!}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Chart Statistics if Type is Vote -->
                @if($activeQuestion->answer_type === 'vote' && count($voteStats))
                    <div class="card modern-card mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-percentage text-teal ml-2"></i>نتائج التصويت الحالية
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            @foreach($voteStats as $vs)
                                @php $choiceRow = $vs['choice']; @endphp
                                <div class="mb-4 last-mb-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="font-weight-bold text-gray-800" style="font-size: 0.95rem;">
                                            {!! strip_tags($choiceRow->choice_text) !!}
                                        </span>
                                        <div class="text-left font-weight-bold">
                                            <span class="text-teal" style="font-size: 1.1rem;">{{ $vs['percent'] }}%</span>
                                            <span class="text-muted text-xs font-weight-normal mr-2">({{ $vs['count'] }} صوت)</span>
                                        </div>
                                    </div>
                                    <div class="progress rounded-pill bg-light" style="height: 16px;">
                                        <div class="progress-bar bg-gradient-vote" role="progressbar"
                                             style="width: {{ min(100, $vs['percent']) }}%;"
                                             aria-valuenow="{{ $vs['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Chart Statistics if Type is Survey -->
                @if($activeQuestion->answer_type === 'survey' && count($surveySummary))
                    <div class="row mb-4">
                        @foreach($surveySummary as $sum)
                            @php $item = $sum['item']; @endphp
                            <div class="col-md-6 mb-4">
                                <div class="card survey-item-card h-100">
                                    <div class="survey-item-header">
                                        <span class="badge-modern badge-modern-teal mb-2" style="font-size: 0.7rem !important; padding: 3px 8px !important;">
                                            @if($sum['kind'] === 'rating') تقييم بالنجوم
                                            @elseif($sum['kind'] === 'number') إدخال رقمي
                                            @else رد نصي @endif
                                        </span>
                                        <strong class="d-block text-gray-900" style="font-size: 0.95rem;">
                                            {!! strip_tags($item->body_text ?: $item->labelForAdmin()) !!}
                                        </strong>
                                    </div>
                                    <div class="card-body p-4">
                                        @if($sum['kind'] === 'rating')
                                            <div class="d-flex align-items-baseline mb-3">
                                                <span class="display-4 font-weight-extrabold text-teal">{{ $sum['avg'] ?? '—' }}</span>
                                                <span class="h6 text-muted mr-2">/ 5 متوسط التقييم</span>
                                            </div>
                                            <div class="text-muted small mb-3">إجمالي المشاركين: {{ $sum['count'] }} تقييم</div>
                                            
                                            @if(!empty($sum['distribution']))
                                                @foreach($sum['distribution'] as $star => $cnt)
                                                    <div class="d-flex align-items-center mb-2 small">
                                                        <span class="text-muted font-weight-bold" style="width: 3.5rem;">{{ $star }} نجوم</span>
                                                        <div class="flex-grow-1 progress mx-3 bg-light" style="height: 10px; border-radius: 999px;">
                                                            <div class="progress-bar bg-warning" style="width: {{ $sum['max_bar'] ? round(100 * $cnt / $sum['max_bar']) : 0 }}%; border-radius: 999px;"></div>
                                                        </div>
                                                        <span class="text-gray-800 font-weight-bold" style="width: 20px; text-align: left;">{{ $cnt }}</span>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @elseif($sum['kind'] === 'number')
                                            <div class="row text-center py-2 bg-light rounded-lg mb-3">
                                                <div class="col-4">
                                                    <div class="text-xs text-muted font-weight-bold">أدنى قيمة</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-850">{{ $sum['min'] ?? '—' }}</div>
                                                </div>
                                                <div class="col-4 border-right border-left">
                                                    <div class="text-xs text-muted font-weight-bold">المتوسط الحسابي</div>
                                                    <div class="h5 mb-0 font-weight-bold text-teal">{{ $sum['avg'] ?? '—' }}</div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-xs text-muted font-weight-bold">أقصى قيمة</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-850">{{ $sum['max'] ?? '—' }}</div>
                                                </div>
                                            </div>
                                            <p class="text-muted small mb-0"><i class="fas fa-calculator mr-1"></i> إجمالي المدخلات: <strong>{{ $sum['count'] }} مشاركة</strong></p>
                                        @else
                                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                                <span class="h5 mb-0 font-weight-bold text-gray-800">{{ $sum['count'] }}</span>
                                                <span class="text-muted small">ردود نصية مسجلة</span>
                                            </div>
                                            @if(!empty($sum['samples']))
                                                <ul class="list-unstyled small text-gray-650 mb-0 survey-samples">
                                                    @foreach($sum['samples'] as $sample)
                                                        <li class="border-bottom py-2 d-flex align-items-start gap-2">
                                                            <i class="fas fa-quote-right text-teal-300 mt-1" style="font-size:0.75rem;"></i>
                                                            <span>{{ Str::limit($sample, 100) }}</span>
                                                        </li>
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

                <!-- Filter Form Card -->
                <div class="card modern-card mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 font-weight-bold text-gray-800"><i class="fas fa-sliders-h text-teal ml-2"></i>تصفية وفرز المشاركات</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="get" action="{{ route('dashboard.quiz-competitions.responses', $quizCompetition) }}" class="row align-items-end">
                            <input type="hidden" name="question_id" value="{{ $activeQuestion->id }}">
                            
                            <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                                <label class="small font-weight-bold text-gray-600 mb-2">بحث بالاسم أو رقم الجوال</label>
                                <input type="text" name="q" class="form-control modern-input" value="{{ request('q') }}" placeholder="أدخل اسم المشارك...">
                            </div>
                            
                            @if(!in_array($activeQuestion->answer_type, ['vote', 'survey'], true))
                                <div class="col-xl-2 col-md-6 mb-3 mb-xl-0">
                                    <label class="small font-weight-bold text-gray-600 mb-2">حالة الإجابة</label>
                                    <select name="correct" class="form-control modern-input">
                                        <option value="">كل الإجابات</option>
                                        <option value="1" @selected(request('correct') === '1')>إجابة صحيحة</option>
                                        <option value="0" @selected(request('correct') === '0')>إجابة خاطئة</option>
                                    </select>
                                </div>
                            @endif
                            
                            <div class="col-xl-2 col-md-6 mb-3 mb-md-0">
                                <label class="small font-weight-bold text-gray-600 mb-2">من تاريخ</label>
                                <input type="date" name="from" class="form-control modern-input" value="{{ request('from') }}">
                            </div>
                            <div class="col-xl-2 col-md-6 mb-3 mb-md-0">
                                <label class="small font-weight-bold text-gray-600 mb-2">إلى تاريخ</label>
                                <input type="date" name="to" class="form-control modern-input" value="{{ request('to') }}">
                            </div>
                            
                            <div class="col-xl-3 col-md-12 d-flex gap-2">
                                <button type="submit" class="modern-btn modern-btn-primary flex-grow-1">
                                    <i class="fas fa-search"></i>تطبيق الفلتر
                                </button>
                                <a href="{{ route('dashboard.quiz-competitions.responses', ['quizCompetition' => $quizCompetition, 'question_id' => $activeQuestion->id]) }}" class="modern-btn modern-btn-secondary text-decoration-none">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Responses Log Table -->
                <div class="card modern-card">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 font-weight-bold text-gray-800" style="font-size:1.05rem;">
                            <i class="fas fa-list-alt text-teal ml-2"></i>سجل المشاركات والتصويتات
                        </h5>
                        <span class="badge-modern badge-modern-teal">إجمالي المصفى: {{ $answers->total() }} سجل</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table modern-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px">#</th>
                                        <th>اسم العضو المشارك</th>
                                        <th>رقم الجوال</th>
                                        <th>اسم الأم</th>
                                        <th>تفاصيل الاستجابة / الإجابة</th>
                                        <th>النتيجة / النوع</th>
                                        <th>تاريخ التسجيل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($answers as $i => $answer)
                                        <tr>
                                            <td>
                                                <span class="text-gray-400 font-weight-bold">
                                                    {{ $answers->firstItem() + $i }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-gray-900">{{ $answer->user->name ?? 'مستخدم غير مسجل' }}</div>
                                            </td>
                                            <td dir="ltr" class="text-gray-600 font-weight-bold">{{ $answer->user->phone ?? '—' }}</td>
                                            <td>
                                                @if($answer->user && $answer->user->is_from_ancestry && $answer->user->mother_name)
                                                    <span class="badge badge-light border px-2 py-1 text-secondary">{{ $answer->user->mother_name }}</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td style="max-width: 320px;">
                                                <div class="text-gray-800 font-weight-bold" style="font-size: 0.92rem;">
                                                    <x-quiz-answer-cell :question="$activeQuestion" :answer="$answer" :limit="600" />
                                                </div>
                                            </td>
                                            <td>
                                                @if($activeQuestion->answer_type === 'vote')
                                                    <span class="badge-modern badge-modern-success">تصويت للرأي</span>
                                                @elseif($activeQuestion->answer_type === 'survey')
                                                    <span class="badge-modern badge-modern-teal">تقييم استبيان</span>
                                                @elseif($answer->is_correct)
                                                    <span class="badge-modern badge-modern-success">إجابة صحيحة</span>
                                                @else
                                                    <span class="badge-modern badge-modern-secondary" style="background-color: #fee2e2 !important; color: #dc2626 !important;">إجابة خاطئة</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-muted small text-nowrap">
                                                    {{ $answer->created_at->format('Y-m-d H:i') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <div class="mb-3">
                                                    <i class="fas fa-search fa-2x text-gray-300"></i>
                                                </div>
                                                <p class="text-muted">لم يتم العثور على أي ردود مطابقة لشروط البحث والتصفية.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($answers->hasPages())
                            <div class="card-footer bg-white border-top py-3">
                                {{ $answers->links() }}
                            </div>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
    @endif
</div>
@endsection
