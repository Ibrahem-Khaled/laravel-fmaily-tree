@extends('layouts.app')

@section('title', 'إدارة استطلاعات الرأي')

@section('content')
@push('styles')
@include('dashboard.quiz-competitions._styles')
@endpush

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1 text-gray-900 font-weight-bold">
                <i class="fas fa-poll text-teal mr-2"></i>إدارة استطلاعات الرأي
            </h1>
            <p class="text-muted mb-0">أنشئ وقم بإدارة استطلاعات الرأي والمسابقات العائلية بكل سهولة وتصميم عصري.</p>
        </div>
        <a href="{{ route('dashboard.quiz-competitions.create') }}" class="modern-btn modern-btn-primary shadow-sm text-decoration-none">
            <i class="fas fa-plus"></i>
            <span>إنشاء استطلاع جديد</span>
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success border-0 alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 16px; background-color: #ecfdf5; color: #065f46;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-3" style="font-size: 1.25rem;"></i>
                <div>
                    <strong>نجاح!</strong> {{ session('success') }}
                </div>
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
                <div>
                    <strong>تنبيه!</strong> {{ session('error') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #991b1b;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card stat-card stat-card-primary h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-3">
                            <div class="text-xs font-weight-bold text-gray-500 text-uppercase mb-1">إجمالي الاستطلاعات والمسابقات</div>
                            <div class="h3 mb-0 font-weight-extrabold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon-wrap stat-icon-primary">
                                <i class="fas fa-poll-h"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card stat-card stat-card-success h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-3">
                            <div class="text-xs font-weight-bold text-gray-500 text-uppercase mb-1">الاستطلاعات الجارية والنشطة</div>
                            <div class="h3 mb-0 font-weight-extrabold text-gray-800">{{ $stats['active'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon-wrap stat-icon-success">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card stat-card stat-card-info h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-3">
                            <div class="text-xs font-weight-bold text-gray-500 text-uppercase mb-1">إجمالي بنود الاستبيان / الأسئلة</div>
                            <div class="h3 mb-0 font-weight-extrabold text-gray-800">{{ $stats['total_questions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon-wrap stat-icon-info">
                                <i class="fas fa-list-ol"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main List Card -->
    <div class="card modern-card">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 font-weight-bold text-gray-800">
                    <i class="fas fa-list-ul text-teal mr-2"></i>قائمة الاستطلاعات والمسابقات ({{ $competitions->count() }})
                </h5>
            </div>
            
            @if($competitions->count() > 0)
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th>الاستطلاع / المسابقة</th>
                                <th>عدد البنود</th>
                                <th>تاريخ البدء</th>
                                <th>تاريخ الانتهاء</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($competitions as $competition)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="font-weight-bold text-gray-900" style="font-size: 1.05rem;">
                                                {{ $competition->title }}
                                            </span>
                                            @if($competition->description)
                                                <span class="text-muted mt-1" style="font-size: 0.85rem;">
                                                    {{ Str::limit($competition->description, 60) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-modern badge-modern-info">
                                            <i class="fas fa-question-circle mr-1"></i>
                                            {{ $competition->questions->count() }} بند
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-gray-700" style="font-size: 0.9rem;">
                                            @if($competition->start_at)
                                                <i class="far fa-calendar-alt text-muted mr-1"></i>
                                                {{ $competition->start_at->format('Y-m-d H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-gray-700" style="font-size: 0.9rem;">
                                            @if($competition->end_at)
                                                <i class="far fa-calendar-times text-muted mr-1"></i>
                                                {{ $competition->end_at->format('Y-m-d H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($competition->is_active)
                                            <span class="badge-modern badge-modern-success">
                                                <span class="status-dot status-dot-active mr-1"></span>
                                                نشط ومتاح
                                            </span>
                                        @else
                                            <span class="badge-modern badge-modern-secondary">
                                                <span class="status-dot status-dot-inactive mr-1"></span>
                                                معطل / مغلق
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('dashboard.quiz-competitions.show', $competition) }}" class="btn-action btn-action-view" title="عرض التفاصيل والأسئلة">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('dashboard.quiz-competitions.edit', $competition) }}" class="btn-action btn-action-edit" title="تعديل الإعدادات">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('dashboard.quiz-competitions.destroy', $competition) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاستطلاع؟ سيتم حذف جميع الأسئلة والردود المرتبطة به نهائياً.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-action-delete" title="حذف">
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
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle" style="width: 80px; height: 80px;">
                            <i class="fas fa-poll fa-3x"></i>
                        </span>
                    </div>
                    <h5 class="text-gray-800 font-weight-bold">لا توجد استطلاعات رأي حالياً</h5>
                    <p class="text-muted max-w-sm mx-auto mb-4">ابدأ بإنشاء أول استطلاع رأي أو مسابقة عائلية للمشاركة والتفاعل.</p>
                    <a href="{{ route('dashboard.quiz-competitions.create') }}" class="modern-btn modern-btn-primary shadow-sm text-decoration-none">
                        <i class="fas fa-plus"></i>
                        <span>إنشاء استطلاع جديد</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
