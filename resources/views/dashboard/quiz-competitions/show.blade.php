@extends('layouts.app')

@section('title', 'تفاصيل استطلاع الرأي')

@section('content')
@push('styles')
@include('dashboard.quiz-competitions._styles')
@endpush

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column mb-4">
        <!-- Back Navigation -->
        <div class="mb-3">
            <a href="{{ route('dashboard.quiz-competitions.index') }}" class="modern-btn modern-btn-secondary py-2 px-3 text-decoration-none">
                <i class="fas fa-arrow-right"></i>
                <span>العودة لقائمة الاستطلاعات</span>
            </a>
        </div>
        
        <div class="d-flex flex-column flex-xl-row align-items-start align-items-xl-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                    <span class="info-badge-flat">
                        <i class="fas fa-calendar-alt"></i>
                        @if($quizCompetition->start_at)
                            {{ $quizCompetition->start_at->format('Y-m-d') }}
                        @else
                            غير محدد
                        @endif
                    </span>
                    @if($quizCompetition->is_active)
                        <span class="badge-modern badge-modern-success">نشط ومتاح للتصويت</span>
                    @else
                        <span class="badge-modern badge-modern-secondary">معطل / مغلق</span>
                    @endif
                </div>
                <h1 class="h2 text-gray-900 font-weight-bold mb-1">
                    {{ $quizCompetition->title }}
                </h1>
                <p class="text-muted mb-0">إدارة البنود والأسئلة واستعراض النتائج وحظوظ المشاركين.</p>
            </div>
            
            <!-- Quick Actions Group -->
            <div class="action-header-btns mt-3 mt-xl-0">
                <a href="{{ route('quiz-competitions.show', $quizCompetition) }}" class="modern-btn modern-btn-info" target="_blank" rel="noopener" title="معاينة الاستطلاع">
                    <i class="fas fa-external-link-alt"></i>معاينة
                </a>
                <a href="{{ route('dashboard.quiz-competitions.responses', $quizCompetition) }}" class="modern-btn modern-btn-dark" title="لوحة النتائج والإحصائيات">
                    <i class="fas fa-chart-pie"></i>النتائج والإحصائيات
                </a>
                <a href="{{ route('dashboard.quiz-competitions.export', $quizCompetition) }}" class="modern-btn modern-btn-success" target="_blank" rel="noopener">
                    <i class="fas fa-file-excel"></i>تصدير النتائج
                </a>
                <form action="{{ route('dashboard.quiz-competitions.simulate-answers', $quizCompetition) }}" method="POST" class="d-inline" onsubmit="return confirm('سيقوم هذا الإجراء بإضافة تصويتات/إجابات عشوائية للمحاكاة واختبار النظام. هل أنت متأكد؟');">
                    @csrf
                    <button type="submit" class="modern-btn modern-btn-warning">
                        <i class="fas fa-vial"></i>محاكاة التصويت
                    </button>
                </form>
                <a href="{{ route('dashboard.quiz-questions.create', $quizCompetition) }}" class="modern-btn modern-btn-primary">
                    <i class="fas fa-plus"></i>إضافة سؤال / بند
                </a>
                <a href="{{ route('dashboard.quiz-competitions.edit', $quizCompetition) }}" class="modern-btn modern-btn-secondary" style="border: 1px solid #cbd5e1 !important;">
                    <i class="fas fa-cog text-muted"></i>تعديل الإعدادات
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success border-0 alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 16px; background-color: #ecfdf5; color: #065f46;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-3" style="font-size: 1.25rem;"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #065f46;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 16px; background-color: #fef2f2; color: #991b1b;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-3" style="font-size: 1.25rem;"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #991b1b;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Description Block -->
    @if($quizCompetition->description)
        <div class="card modern-card mb-4 overflow-hidden">
            <div class="card-body p-4 bg-light-gradient">
                <h6 class="font-weight-bold text-gray-800 mb-2">الوصف والتعليمات العامة:</h6>
                <div class="mb-0 quiz-description text-gray-700">{!! $quizCompetition->description !!}</div>
            </div>
        </div>
    @endif

    <!-- Questions list block -->
    <div class="card modern-card">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 font-weight-bold text-gray-800">
                    <i class="fas fa-tasks text-teal mr-2"></i>البنود والأسئلة المضافة ({{ $quizCompetition->questions->count() }})
                </h5>
            </div>
            
            @if($quizCompetition->questions->count() > 0)
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>نص البند / السؤال</th>
                                <th>نوع الاستجابة</th>
                                <th>حالة الردود والإحصائيات</th>
                                <th>الفائزون والقرعة</th>
                                <th style="width: 100px">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizCompetition->questions as $index => $question)
                                <tr>
                                    <td>
                                        <span class="font-weight-bold text-gray-400" style="font-size: 1.1rem;">
                                            {{ sprintf('%02d', $index + 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="question-text font-weight-bold text-gray-900" style="font-size: 0.98rem; max-width: 380px;">
                                            {!! Str::limit(strip_tags($question->question_text), 100) !!}
                                        </div>
                                    </td>
                                    <td>
                                        @if($question->answer_type === 'multiple_choice')
                                            <span class="badge-modern badge-modern-primary">
                                                <i class="fas fa-list-ul"></i> اختيار من متعدد
                                            </span>
                                        @elseif($question->answer_type === 'vote')
                                            <span class="badge-modern badge-modern-success">
                                                <i class="fas fa-vote-yea"></i> تصويت / استطلاع رأي
                                            </span>
                                        @elseif($question->answer_type === 'true_false')
                                            <span class="badge-modern badge-modern-warning">
                                                <i class="fas fa-check-double"></i> صح أم خطأ
                                            </span>
                                        @elseif($question->answer_type === 'ordering')
                                            <span class="badge-modern badge-modern-info">
                                                <i class="fas fa-sort-amount-down"></i> ترتيب العناصر
                                            </span>
                                        @elseif($question->answer_type === 'survey')
                                            <span class="badge-modern badge-modern-primary">
                                                <i class="fas fa-poll"></i> تقييم / استبيان
                                            </span>
                                        @else
                                            <span class="badge-modern badge-modern-secondary">
                                                <i class="fas fa-edit"></i> إجابة حرة
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($question->answers_count > 0)
                                            <a href="{{ route('dashboard.quiz-competitions.responses', ['quizCompetition' => $quizCompetition, 'question_id' => $question->id]) }}" class="btn btn-sm btn-outline-info px-3" style="border-radius: 8px; font-weight: 700;" title="عرض إحصائيات الإجابات">
                                                <i class="fas fa-chart-bar mr-1"></i>
                                                {{ $question->answers_count }} {{ $question->answer_type === 'vote' ? 'صوت' : 'مشاركة' }}
                                            </a>
                                            @if(!in_array($question->answer_type, ['vote', 'survey'], true))
                                                <small class="d-block text-emerald-600 font-weight-bold mt-1">
                                                    <i class="fas fa-check-circle mr-1"></i>{{ $question->correct_answers_count }} إجابة صحيحة
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted d-block small mb-1">لا توجد ردود بعد</span>
                                            <a href="{{ route('dashboard.quiz-competitions.responses', ['quizCompetition' => $quizCompetition, 'question_id' => $question->id]) }}" class="text-info font-weight-bold small text-decoration-none">
                                                فتح الإحصائيات <i class="fas fa-chevron-left small"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if(in_array($question->answer_type, ['vote', 'survey'], true))
                                            <span class="text-muted small">— (نوع إحصائي فقط)</span>
                                        @elseif($question->winners->count() > 0)
                                            <button type="button" class="btn btn-sm btn-success px-3" style="border-radius: 8px; font-weight: 700;" data-toggle="modal" data-target="#winnersModal{{ $question->id }}" title="عرض تفاصيل الفائزين">
                                                <i class="fas fa-trophy mr-1 text-warning"></i>
                                                عرض الفائزين ({{ $question->winners->count() }})
                                            </button>
                                            @if($question->hasEnded() && $question->correct_answers_count > 0)
                                                <form action="{{ route('dashboard.quiz-questions.select-winners', [$quizCompetition, $question]) }}" method="POST" class="d-inline ml-1" onsubmit="return confirm('سيتم تصفير قائمة الفائزين وإجراء سحب عشوائي جديد. هل أنت متأكد؟');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning p-1" style="border-radius: 6px;" title="إعادة إجراء السحب">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            @if($question->hasEnded())
                                                @if($question->correct_answers_count > 0)
                                                    <form action="{{ route('dashboard.quiz-questions.select-winners', [$quizCompetition, $question]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning font-weight-bold px-3" style="border-radius: 8px;" title="إجراء قرعة وسحب الفائزين عشوائياً">
                                                            <i class="fas fa-random mr-1"></i> إجراء السحب
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted small text-danger font-weight-bold">لا توجد إجابات صحيحة للسحب</span>
                                                @endif
                                            @else
                                                <span class="text-muted small">بانتظار إغلاق الاستطلاع</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('dashboard.quiz-questions.edit', [$quizCompetition, $question]) }}" class="action-btn-sm action-btn-sm-edit" title="تعديل السؤال والخيارات">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('dashboard.quiz-questions.destroy', [$quizCompetition, $question]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال وكل محتوياته؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn-sm action-btn-sm-delete" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Modals for Winners --}}
                @foreach($quizCompetition->questions as $question)
                    @if(!in_array($question->answer_type, ['vote', 'survey'], true) && $question->winners->count() > 0)
                    <div class="modal fade" id="winnersModal{{ $question->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content modal-content-modern">
                                <div class="modal-header modal-header-modern">
                                    <h5 class="modal-title font-weight-bold">
                                        <i class="fas fa-trophy mr-2 text-warning"></i> لوحة الفائزين والسحب
                                    </h5>
                                    <button type="button" class="close modal-close-modern" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body modal-body-modern">
                                    <div class="p-3 mb-4 rounded-lg bg-light" style="border-right: 4px solid #0d9488;">
                                        <span class="text-xs text-gray-500 font-weight-bold d-block mb-1">نص السؤال/البند المسحوب عليه:</span>
                                        <div class="text-gray-800 font-weight-bold question-text" style="font-size: 1rem;">
                                            {!! strip_tags($question->question_text) !!}
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                                        <div>
                                            <span class="badge badge-success badge-lg px-3 py-2" style="border-radius: 8px; font-size: 0.9rem;">
                                                إجمالي الفائزين حالياً: {{ $question->winners->count() }} فائز من أصل {{ $question->winners_count }}
                                            </span>
                                        </div>
                                        <div class="mt-2 mt-md-0 d-flex gap-2">
                                            @if($question->winners->count() < $question->winners_count && $question->hasEnded() && $question->correct_answers_count > $question->winners->count())
                                                <form action="{{ route('dashboard.quiz-questions.fill-winners', [$quizCompetition, $question]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-info font-weight-bold px-3 py-2" style="border-radius: 8px;" title="إكمال الفائزين الشاغرين عشوائياً">
                                                        <i class="fas fa-plus-circle mr-1"></i> إكمال المقاعد (شاغر: {{ $question->winners_count - $question->winners->count() }})
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('dashboard.quiz-questions.select-winners', [$quizCompetition, $question]) }}" method="POST" class="d-inline ml-2" onsubmit="return confirm('سيتم مسح الأسماء الحالية وإجراء قرعة جديدة بالكامل. هل أنت متأكد؟');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold px-3 py-2" style="border-radius: 8px;" title="إعادة إجراء سحب جديد">
                                                    <i class="fas fa-sync-alt mr-1"></i> إعادة سحب الكل
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info border-0 py-2 px-3 small d-flex align-items-center" style="border-radius: 12px; background-color: #eff6ff; color: #1e40af;">
                                        <i class="fas fa-info-circle mr-2" style="font-size: 1.1rem;"></i> 
                                        <span>يمكنك سحب وإفلات الصفوف لتعديل ترتيب مراكز الفائزين يدوياً بشكل فوري ومباشر.</span>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40px"></th>
                                                    <th style="width: 130px">الترتيب / المركز</th>
                                                    <th>اسم الفائز</th>
                                                    <th>رقم الجوال</th>
                                                    <th>اسم الأم (للتأكيد العائلي)</th>
                                                    <th style="width: 80px">إزالة</th>
                                                </tr>
                                            </thead>
                                            <tbody class="sortable-winners" data-question-id="{{ $question->id }}">
                                                @foreach($question->winners->sortBy('position') as $winner)
                                                    <tr class="sortable-row" data-winner-id="{{ $winner->id }}">
                                                        <td class="drag-handle text-center">
                                                            <i class="fas fa-grip-vertical"></i>
                                                        </td>
                                                        <td class="position-cell">
                                                            @if($winner->position == 1)
                                                                <span class="winner-rank winner-rank-1">
                                                                    <i class="fas fa-crown"></i> المركز الأول
                                                                </span>
                                                            @elseif($winner->position == 2)
                                                                <span class="winner-rank winner-rank-2">
                                                                    <i class="fas fa-medal"></i> المركز الثاني
                                                                </span>
                                                            @elseif($winner->position == 3)
                                                                <span class="winner-rank winner-rank-3">
                                                                    <i class="fas fa-medal"></i> المركز الثالث
                                                                </span>
                                                            @else
                                                                <span class="winner-rank winner-rank-other">
                                                                    المركز {{ $winner->position }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="font-weight-bold text-gray-900">{{ $winner->user->name ?? 'مستخدم غير معروف' }}</div>
                                                        </td>
                                                        <td dir="ltr" class="text-gray-600">{{ $winner->user->phone ?? '-' }}</td>
                                                        <td>
                                                            @if($winner->user && $winner->user->is_from_ancestry && $winner->user->mother_name)
                                                                <span class="badge badge-light text-secondary border px-2 py-1">{{ $winner->user->mother_name }}</span>
                                                            @else
                                                                <span class="text-muted small">—</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <form action="{{ route('dashboard.quiz-questions.remove-winner', $winner) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من استبعاد هذا الفائز؟');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger p-1" style="border-radius: 6px;" title="إزالة من الفائزين">
                                                                    <i class="fas fa-user-minus"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="text-right mt-4 save-order-container" id="saveOrder{{ $question->id }}" style="display: none;">
                                        <form action="{{ route('dashboard.quiz-questions.reorder-winners', [$quizCompetition, $question]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="winner_ids[]" class="winner-ids-input">
                                            <button type="submit" class="modern-btn modern-btn-primary px-4 py-2">
                                                <i class="fas fa-save mr-1"></i> حفظ الترتيب والمراكز الجديدة
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle" style="width: 80px; height: 80px;">
                            <i class="fas fa-question fa-3x"></i>
                        </span>
                    </div>
                    <h5 class="text-gray-800 font-weight-bold">لا توجد أسئلة أو بنود حالياً</h5>
                    <p class="text-muted max-w-sm mx-auto mb-4">أضف بنود استطلاع جديدة أو أسئلة للمسابقة لبدء تفاعل أفراد العائلة.</p>
                    <a href="{{ route('dashboard.quiz-questions.create', $quizCompetition) }}" class="modern-btn modern-btn-primary text-decoration-none">
                        <i class="fas fa-plus"></i>
                        <span>إضافة أول بند / سؤال</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Quiz Description Styles */
    .quiz-description {
        direction: rtl;
        text-align: right;
    }
    .quiz-description p {
        margin-bottom: 0.75rem;
    }
    .quiz-description strong,
    .quiz-description b {
        font-weight: 700;
        color: #0d9488;
    }
    .quiz-description em,
    .quiz-description i {
        font-style: italic;
    }
    .quiz-description ul,
    .quiz-description ol {
        margin-right: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .quiz-description li {
        margin-bottom: 0.5rem;
    }
    .quiz-description a {
        color: #0d9488;
        text-decoration: underline;
        transition: color 0.2s;
    }
    .quiz-description a:hover {
        color: #0f766e;
    }
    .quiz-description table {
        width: 100%;
        margin-bottom: 0.75rem;
        border-collapse: collapse;
        direction: ltr;
        text-align: left;
    }
    .quiz-description table td,
    .quiz-description table th {
        padding: 0.5rem;
        border: 1px solid #e5e7eb;
        text-align: left;
    }
    .quiz-description img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 0.5rem 0;
    }

    /* Question Text Styles */
    .question-text {
        direction: rtl;
        text-align: right;
    }
    .question-text p {
        margin-bottom: 0.5rem;
    }
    .question-text strong,
    .question-text b {
        font-weight: 700;
    }
    .question-text em,
    .question-text i {
        font-style: italic;
    }
    .question-text ul,
    .question-text ol {
        margin-right: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .question-text li {
        margin-bottom: 0.25rem;
    }
    .question-text a {
        color: #0d9488;
        text-decoration: underline;
    }
    .question-text img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 0.5rem 0;
    }
    .quiz-description iframe,
    .quiz-description video,
    .question-text iframe,
    .question-text video {
        max-width: 100%;
        height: auto;
        aspect-ratio: 16 / 9;
        border-radius: 0.5rem;
        margin: 0.5rem 0;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortableLists = document.querySelectorAll('.sortable-winners');
        
        sortableLists.forEach(list => {
            const questionId = list.dataset.questionId;
            const saveBtnContainer = document.getElementById('saveOrder' + questionId);
            
            new Sortable(list, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'bg-light',
                onEnd: function() {
                    saveBtnContainer.style.display = 'block';
                    
                    // Update hidden inputs
                    const winnerIds = Array.from(list.querySelectorAll('tr')).map(tr => tr.dataset.winnerId);
                    const container = saveBtnContainer.querySelector('form');
                    
                    // Clear existing inputs
                    container.querySelectorAll('input[name="winner_ids[]"]').forEach(el => el.remove());
                    
                    // Add new inputs
                    winnerIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'winner_ids[]';
                        input.value = id;
                        container.appendChild(input);
                    });
                }
            });
        });
    });
</script>
@endpush
