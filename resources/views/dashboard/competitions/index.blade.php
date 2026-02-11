@extends('layouts.app')

@section('title', 'إدارة المسابقات')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-trophy text-primary mr-2"></i>إدارة المسابقات
            </h1>
            <p class="text-muted mb-0">قم بإدارة المسابقات وإضافة مسابقات جديدة</p>
        </div>
        <a href="{{ route('dashboard.competitions.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>إضافة مسابقة جديدة
        </a>
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي المسابقات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trophy fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">المسابقات النشطة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">إجمالي الفرق</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_teams'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">الفرق المكتملة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['complete_teams'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Competitions Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>المسابقات ({{ $competitions->count() }})
            </h6>
        </div>
        <div class="card-body">
            @if($competitions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>العنوان</th>
                                <th>نوع اللعبة</th>
                                <th>حجم الفريق</th>
                                <th>البرنامج المرتبط</th>
                                <th>تاريخ البداية</th>
                                <th>تاريخ النهاية</th>
                                <th>الفرق</th>
                                <th>رابط التسجيل</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($competitions as $competition)
                                <tr>
                                    <td>
                                        <strong>{{ $competition->title }}</strong>
                                        @if($competition->description)
                                            <br><small class="text-muted">{{ Str::limit($competition->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $competition->game_type }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $competition->team_size }} عضو</span>
                                    </td>
                                    <td>
                                        @if($competition->program)
                                            <span class="badge badge-purple">
                                                <i class="fas fa-link mr-1"></i>
                                                {{ $competition->program->program_title ?? $competition->program->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($competition->start_date)
                                            {{ $competition->start_date->format('Y-m-d') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($competition->end_date)
                                            {{ $competition->end_date->format('Y-m-d') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $competition->teams->count() }} فريق
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('{{ $competition->registration_url }}')" title="نسخ الرابط">
                                            <i class="fas fa-link"></i> نسخ الرابط
                                        </button>
                                    </td>
                                    <td>
                                        @if($competition->is_active)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i>نشط
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-ban mr-1"></i>معطل
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('dashboard.competitions.show', $competition) }}" class="btn btn-sm btn-info" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('dashboard.competitions.edit', $competition) }}" class="btn btn-sm btn-primary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('dashboard.competitions.destroy', $competition) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المسابقة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
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
                    <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد مسابقات حالياً</p>
                    <a href="{{ route('dashboard.competitions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i>إضافة مسابقة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('تم نسخ الرابط بنجاح!');
    }, function(err) {
        console.error('فشل نسخ الرابط:', err);
    });
}
</script>
@endsection
