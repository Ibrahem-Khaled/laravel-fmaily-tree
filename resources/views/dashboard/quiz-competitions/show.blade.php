@extends('layouts.app')

@section('title', 'تفاصيل مسابقة الأسئلة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-question-circle text-primary mr-2"></i>{{ $quizCompetition->title }}
            </h1>
            <p class="text-muted mb-0">إدارة الأسئلة والمسابقات</p>
        </div>
        <div>
            <a href="{{ route('quiz-competitions.show', $quizCompetition) }}" class="btn btn-info shadow-sm mr-2" target="_blank" rel="noopener" title="معاينة المسابقة كما يراها الزوار">
                <i class="fas fa-external-link-alt mr-2"></i>معاينة
            </a>
            <a href="{{ route('dashboard.quiz-competitions.export', $quizCompetition) }}" class="btn btn-success shadow-sm mr-2" target="_blank" rel="noopener">
                <i class="fas fa-file-excel mr-2"></i>تصدير إلى Excel
            </a>
            <form action="{{ route('dashboard.quiz-competitions.simulate-answers', $quizCompetition) }}" method="POST" class="d-inline mr-2" onsubmit="return confirm('هذا الإجراء سيقوم بإضافة إجابات عشوائية لجميع المستخدمين الذين لم يجاوبوا. هل أنت متأكد؟');">
                @csrf
                <button type="submit" class="btn btn-warning shadow-sm">
                    <i class="fas fa-magic mr-2"></i>محاكاة الإجابات
                </button>
            </form>
            <a href="{{ route('dashboard.quiz-questions.create', $quizCompetition) }}" class="btn btn-success shadow-sm">
                <i class="fas fa-plus mr-2"></i>إضافة سؤال
            </a>
            <a href="{{ route('dashboard.quiz-competitions.edit', $quizCompetition) }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-edit mr-2"></i>تعديل المسابقة
            </a>
            <a href="{{ route('dashboard.quiz-competitions.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($quizCompetition->description)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="mb-0 quiz-description">{!! $quizCompetition->description !!}</div>
            </div>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>الأسئلة ({{ $quizCompetition->questions->count() }})
            </h6>
        </div>
        <div class="card-body">
            @if($quizCompetition->questions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نص السؤال</th>
                                <th>نوع الإجابة</th>
                                <th>وقت المسابقة</th>
                                <th>الإجابات</th>
                                <th>الفائزون</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizCompetition->questions as $index => $question)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong class="question-text">{!! Str::limit(strip_tags($question->question_text), 60) !!}</strong>
                                    </td>
                                    <td>
                                        @if($question->answer_type === 'multiple_choice')
                                            <span class="badge badge-info">اختيار من متعدد</span>
                                        @elseif($question->answer_type === 'vote')
                                            <span class="badge badge-primary">تصويت</span>
                                        @elseif($question->answer_type === 'true_false')
                                            <span class="badge badge-warning">صح / خطأ</span>
                                        @elseif($question->answer_type === 'ordering')
                                            <span class="badge badge-success">ترتيب</span>
                                        @else
                                            <span class="badge badge-secondary">إجابة حرة</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($quizCompetition->start_at && $quizCompetition->end_at)
                                            {{ $quizCompetition->start_at->format('Y-m-d H:i') }} — {{ $quizCompetition->end_at->format('Y-m-d H:i') }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($question->answers->count() > 0)
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#answersModal{{ $question->id }}" title="عرض الإجابات">
                                                <i class="fas fa-list-ol mr-1"></i>عرض {{ $question->answers->count() }} @if($question->answer_type === 'vote') تصويت @else إجابة @endif
                                            </button>
                                            @if($question->answer_type !== 'vote')
                                                <small class="d-block text-success mt-1">{{ $question->answers->where('is_correct', true)->count() }} صحيح</small>
                                            @endif
                                        @else
                                            <span class="text-muted">لا توجد إجابات</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($question->answer_type === 'vote')
                                            <span class="text-muted">—</span>
                                        @elseif($question->winners->count() > 0)
                                            <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#winnersModal{{ $question->id }}" title="عرض تفاصيل الفائزين">
                                                <i class="fas fa-trophy mr-1"></i>عرض {{ $question->winners->count() }} فائز
                                            </button>
                                            @if($question->hasEnded() && $question->answers->where('is_correct', true)->count() > 0)
                                                <form action="{{ route('dashboard.quiz-questions.select-winners', [$quizCompetition, $question]) }}" method="POST" class="d-inline mr-1" onsubmit="return confirm('سيتم حذف الفائزين الحاليين واختيار فائزين جدد عشوائياً من الإجابات الصحيحة. هل أنت متأكد؟');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="إعادة اختيار الفائزين عشوائياً">
                                                        <i class="fas fa-sync-alt mr-1"></i>إعادة اختيار الفائز
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            @if($question->hasEnded())
                                                <form action="{{ route('dashboard.quiz-questions.select-winners', [$quizCompetition, $question]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning" title="تحديد الفائزين عشوائياً من الإجابات الصحيحة">
                                                        <i class="fas fa-trophy mr-1"></i>تحديد الفائزين
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">بانتظار انتهاء الوقت</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard.quiz-questions.edit', [$quizCompetition, $question]) }}" class="btn btn-sm btn-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.quiz-questions.destroy', [$quizCompetition, $question]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- نوافذ عرض الإجابات --}}
                @foreach($quizCompetition->questions as $question)
                    @if($question->answers->count() > 0)
                    <div class="modal fade" id="answersModal{{ $question->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-list-ol mr-2"></i>إجابات السؤال
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-muted mb-3 font-weight-bold question-text">{!! Str::limit(strip_tags($question->question_text), 150) !!}</div>
                                    @if($question->description)
                                        <div class="text-muted mb-3 small quiz-description">{!! Str::limit(strip_tags($question->description), 150) !!}</div>
                                    @endif
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="badge badge-primary">الإجمالي: {{ $question->answers->count() }}</span>
                                        </div>
                                        @if($question->answer_type !== 'vote')
                                            <div class="col-md-4">
                                                <span class="badge badge-success">صحيح: {{ $question->answers->where('is_correct', true)->count() }}</span>
                                            </div>
                                            <div class="col-md-4">
                                                <span class="badge badge-danger">خاطئ: {{ $question->answers->where('is_correct', false)->count() }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>المشارك</th>
                                                    <th>رقم الهاتف</th>
                                                    <th>اسم الأم</th>
                                                    <th>الإجابة</th>
                                                    <th>النتيجة</th>
                                                    <th>التاريخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($question->answers as $i => $answer)
                                                    <tr>
                                                        <td>{{ $i + 1 }}</td>
                                                        <td>{{ $answer->user->name ?? '-' }}</td>
                                                        <td dir="ltr">{{ $answer->user->phone ?? '-' }}</td>
                                                        <td>
                                                            @if($answer->user->is_from_ancestry && $answer->user->mother_name)
                                                                <span class="badge badge-info">{{ $answer->user->mother_name }}</span>
                                                            @else
                                                                <span class="text-muted">—</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(in_array($answer->answer_type, ['multiple_choice', 'vote', 'ordering', 'true_false', 'choice']))
                                                                @php 
                                                                    $decoded = json_decode($answer->answer, true);
                                                                    $displayText = $answer->answer;
                                                                    if (is_array($decoded)) {
                                                                        if ($answer->answer_type === 'true_false') {
                                                                            $parts = [];
                                                                            foreach ($decoded as $choiceId => $val) {
                                                                                $c = $question->choices->firstWhere('id', $choiceId);
                                                                                $cText = $c ? $c->choice_text : $choiceId;
                                                                                $valText = ($val === '1' || $val === 'true' || $val === true) ? 'صح' : 'خطأ';
                                                                                $parts[] = $cText . ': ' . $valText;
                                                                            }
                                                                            $displayText = implode(' | ', $parts);
                                                                        } else {
                                                                            $choiceTexts = [];
                                                                            foreach ($decoded as $choiceId) {
                                                                                $c = $question->choices->firstWhere('id', $choiceId);
                                                                                if ($c) $choiceTexts[] = $c->choice_text;
                                                                            }
                                                                            $displayText = implode(' - ', $choiceTexts);
                                                                        }
                                                                    } else {
                                                                        $c = $question->choices->firstWhere('id', (int) $answer->answer);
                                                                        if ($c) $displayText = $c->choice_text;
                                                                    }
                                                                @endphp
                                                                {{ Str::limit($displayText, 80) }}
                                                            @else
                                                                {{ Str::limit($answer->answer, 80) }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($question->answer_type === 'vote')
                                                                <span class="badge badge-primary">تم التصويت</span>
                                                            @elseif($answer->is_correct)
                                                                <span class="badge badge-success">صحيح</span>
                                                            @else
                                                                <span class="badge badge-danger">خاطئ</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $answer->created_at->format('Y-m-d H:i') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach

                {{-- نوافذ عرض الفائزين --}}
                @foreach($quizCompetition->questions as $question)
                    @if($question->answer_type !== 'vote' && $question->winners->count() > 0)
                    <div class="modal fade" id="winnersModal{{ $question->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-trophy mr-2"></i>تفاصيل الفائزين
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-muted mb-3 font-weight-bold question-text">{!! Str::limit(strip_tags($question->question_text), 150) !!}</div>
                                    @if($question->description)
                                        <div class="text-muted mb-3 small quiz-description">{!! Str::limit(strip_tags($question->description), 150) !!}</div>
                                    @endif
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-6">
                                            <span class="badge badge-success badge-lg">عدد الفائزين الحالي: {{ $question->winners->count() }} / {{ $question->winners_count }}</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            @if($question->winners->count() < $question->winners_count && $question->hasEnded() && $question->answers->where('is_correct', true)->count() > $question->winners->count())
                                                <form action="{{ route('dashboard.quiz-questions.fill-winners', [$quizCompetition, $question]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-info" title="اختيار فائزين عشوائياً للمقاعد الشاغرة فقط">
                                                        <i class="fas fa-plus-circle mr-1"></i>إكمال الفائزين (شاغر: {{ $question->winners_count - $question->winners->count() }})
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('dashboard.quiz-questions.select-winners', [$quizCompetition, $question]) }}" method="POST" class="d-inline ml-1" onsubmit="return confirm('سيتم حذف الفائزين الحاليين واختيار فائزين جدد عشوائياً. هل أنت متأكد؟');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="إعادة تصفير واختيار جميع الفائزين">
                                                    <i class="fas fa-sync-alt mr-1"></i>إعادة اختيار الكل
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="alert alert-info py-2 small">
                                        <i class="fas fa-info-circle mr-1"></i> يمكنك سحب وإفلات الصفوف لتغيير ترتيب مراكز الفائزين يدوياً.
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40px"></th>
                                                    <th>المركز</th>
                                                    <th>الاسم</th>
                                                    <th>رقم الهاتف</th>
                                                    <th>اسم الأم</th>
                                                    <th>الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody class="sortable-winners" data-question-id="{{ $question->id }}">
                                                @foreach($question->winners->sortBy('position') as $winner)
                                                    <tr data-winner-id="{{ $winner->id }}">
                                                        <td class="drag-handle" style="cursor: move;"><i class="fas fa-grip-vertical text-muted"></i></td>
                                                        <td class="position-cell">
                                                            @if($winner->position == 1)
                                                                <span class="badge badge-warning"><i class="fas fa-crown"></i> المركز الأول</span>
                                                            @elseif($winner->position == 2)
                                                                <span class="badge badge-secondary"><i class="fas fa-medal"></i> المركز الثاني</span>
                                                            @elseif($winner->position == 3)
                                                                <span class="badge badge-info"><i class="fas fa-medal"></i> المركز الثالث</span>
                                                            @else
                                                                <span class="badge badge-light">المركز {{ $winner->position }}</span>
                                                            @endif
                                                        </td>
                                                        <td><strong>{{ $winner->user->name ?? '-' }}</strong></td>
                                                        <td dir="ltr">{{ $winner->user->phone ?? '-' }}</td>
                                                        <td>
                                                            @if($winner->user->is_from_ancestry && $winner->user->mother_name)
                                                                <span class="badge badge-info">{{ $winner->user->mother_name }}</span>
                                                            @else
                                                                <span class="text-muted">—</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <form action="{{ route('dashboard.quiz-questions.remove-winner', $winner) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من إزالة هذا الفائز؟');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="إزالة الفائز">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="text-right mt-3 save-order-container" id="saveOrder{{ $question->id }}" style="display: none;">
                                        <form action="{{ route('dashboard.quiz-questions.reorder-winners', [$quizCompetition, $question]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="winner_ids[]" class="winner-ids-input">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save mr-1"></i> حفظ الترتيب الجديد
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
                    <i class="fas fa-question fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد أسئلة في هذه المسابقة</p>
                    <a href="{{ route('dashboard.quiz-questions.create', $quizCompetition) }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i>إضافة سؤال
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
        color: #16a34a;
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
        color: #22c55e;
        text-decoration: underline;
        transition: color 0.2s;
    }
    .quiz-description a:hover {
        color: #16a34a;
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
        color: #22c55e;
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
