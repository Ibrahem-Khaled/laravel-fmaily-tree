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
                <p class="mb-0">{{ $quizCompetition->description }}</p>
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
                                        <strong>{{ Str::limit($question->question_text, 60) }}</strong>
                                    </td>
                                    <td>
                                        @if($question->answer_type === 'multiple_choice')
                                            <span class="badge badge-info">اختيار من متعدد</span>
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
                                                <i class="fas fa-list-ol mr-1"></i>عرض {{ $question->answers->count() }} إجابة
                                            </button>
                                            <small class="d-block text-success mt-1">{{ $question->answers->where('is_correct', true)->count() }} صحيح</small>
                                        @else
                                            <span class="text-muted">لا توجد إجابات</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($question->winners->count() > 0)
                                            @foreach($question->winners as $winner)
                                                <span class="badge badge-success">{{ $winner->user->name ?? '-' }}</span>
                                            @endforeach
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
                                    <p class="text-muted mb-3 font-weight-bold">{{ Str::limit($question->question_text, 150) }}</p>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="badge badge-primary">الإجمالي: {{ $question->answers->count() }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="badge badge-success">صحيح: {{ $question->answers->where('is_correct', true)->count() }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="badge badge-danger">خاطئ: {{ $question->answers->where('is_correct', false)->count() }}</span>
                                        </div>
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
                                                            @if($answer->answer_type === 'choice')
                                                                @php $choice = $question->choices->firstWhere('id', (int) $answer->answer); @endphp
                                                                {{ $choice ? $choice->choice_text : $answer->answer }}
                                                            @else
                                                                {{ Str::limit($answer->answer, 80) }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($answer->is_correct)
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
