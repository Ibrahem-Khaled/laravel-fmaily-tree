@extends('layouts.app')

@section('title', 'برنامج المشي')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-walking text-primary mr-2"></i>برنامج المشي
            </h1>
            <p class="text-muted mt-1">إحصائيات وإنجازات المشي للمستخدمين</p>
        </div>
        @can('walking-program.create')
        <a href="{{ route('dashboard.walking.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>إضافة سجل مشي
        </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Period Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        <i class="fas fa-calendar-day mr-1"></i>اليوم
                    </div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">{{ number_format($dailyStats['total_steps']) }}</div>
                    <small class="text-muted">خطوة • {{ $dailyStats['participants'] }} مشارك</small>
                    <div class="mt-2">
                        <span class="badge badge-primary">المتوسط: {{ number_format($dailyStats['average']) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        <i class="fas fa-calendar-week mr-1"></i>هذا الأسبوع
                    </div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">{{ number_format($weeklyStats['total_steps']) }}</div>
                    <small class="text-muted">خطوة • {{ $weeklyStats['participants'] }} مشارك</small>
                    <div class="mt-2">
                        <span class="badge badge-success">المتوسط: {{ number_format($weeklyStats['average']) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        <i class="fas fa-calendar-alt mr-1"></i>هذا الشهر
                    </div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlyStats['total_steps']) }}</div>
                    <small class="text-muted">خطوة • {{ $monthlyStats['participants'] }} مشارك</small>
                    <div class="mt-2">
                        <span class="badge badge-info">المتوسط: {{ number_format($monthlyStats['average']) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs: Daily | Weekly | Monthly -->
    <ul class="nav nav-tabs mb-3" id="periodTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="daily-tab" data-toggle="tab" href="#daily" role="tab">
                <i class="fas fa-calendar-day mr-1"></i>يومي
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="weekly-tab" data-toggle="tab" href="#weekly" role="tab">
                <i class="fas fa-calendar-week mr-1"></i>أسبوعي
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="monthly-tab" data-toggle="tab" href="#monthly" role="tab">
                <i class="fas fa-calendar-alt mr-1"></i>شهري
            </a>
        </li>
    </ul>

    <div class="tab-content" id="periodTabsContent">
        <!-- Daily Rankings -->
        <div class="tab-pane fade show active" id="daily" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy mr-2"></i>ترتيب اليوم - {{ $today->format('Y-m-d') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="60">الترتيب</th>
                                    <th>المستخدم</th>
                                    <th>الخطوات</th>
                                    <th>المسافة (كم)</th>
                                    <th>المدة (دقيقة)</th>
                                    @can('walking-program.update')<th>الإجراءات</th>@endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dailyRankings as $index => $record)
                                <tr>
                                    <td class="text-center font-weight-bold">
                                        @if($index + 1 == 1)
                                            <i class="fas fa-medal text-warning"></i> 1
                                        @elseif($index + 1 == 2)
                                            <i class="fas fa-medal text-secondary"></i> 2
                                        @elseif($index + 1 == 3)
                                            <i class="fas fa-medal" style="color: #cd7f32;"></i> 3
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>{{ $record->user->name ?? '-' }}</td>
                                    <td>{{ number_format($record->steps) }}</td>
                                    <td>{{ number_format($record->distance_km, 1) }}</td>
                                    <td>{{ $record->duration_minutes }}</td>
                                    @can('walking-program.update')
                                    <td>
                                        <a href="{{ route('dashboard.walking.edit', $record) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.walking.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->can('walking-program.update') || auth()->user()->can('walking-program.delete') ? 6 : 5 }}" class="text-center text-muted py-4">
                                        <i class="fas fa-walking fa-2x mb-2 d-block"></i>
                                        لا توجد سجلات مشي اليوم
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Rankings -->
        <div class="tab-pane fade" id="weekly" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-trophy mr-2"></i>ترتيب هذا الأسبوع
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="60">الترتيب</th>
                                    <th>المستخدم</th>
                                    <th>إجمالي الخطوات</th>
                                    <th>المسافة (كم)</th>
                                    <th>المدة (دقيقة)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($weeklyRankings as $index => $item)
                                <tr>
                                    <td class="text-center font-weight-bold">
                                        @if($index + 1 == 1)
                                            <i class="fas fa-medal text-warning"></i> 1
                                        @elseif($index + 1 == 2)
                                            <i class="fas fa-medal text-secondary"></i> 2
                                        @elseif($index + 1 == 3)
                                            <i class="fas fa-medal" style="color: #cd7f32;"></i> 3
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                    <td>{{ number_format($item->total_steps) }}</td>
                                    <td>{{ number_format($item->total_distance ?? 0, 1) }}</td>
                                    <td>{{ $item->total_duration ?? 0 }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        لا توجد سجلات هذا الأسبوع
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Rankings -->
        <div class="tab-pane fade" id="monthly" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-trophy mr-2"></i>ترتيب هذا الشهر
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="60">الترتيب</th>
                                    <th>المستخدم</th>
                                    <th>إجمالي الخطوات</th>
                                    <th>المسافة (كم)</th>
                                    <th>المدة (دقيقة)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyRankings as $index => $item)
                                <tr>
                                    <td class="text-center font-weight-bold">
                                        @if($index + 1 == 1)
                                            <i class="fas fa-medal text-warning"></i> 1
                                        @elseif($index + 1 == 2)
                                            <i class="fas fa-medal text-secondary"></i> 2
                                        @elseif($index + 1 == 3)
                                            <i class="fas fa-medal" style="color: #cd7f32;"></i> 3
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                    <td>{{ number_format($item->total_steps) }}</td>
                                    <td>{{ number_format($item->total_distance ?? 0, 1) }}</td>
                                    <td>{{ $item->total_duration ?? 0 }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        لا توجد سجلات هذا الشهر
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Smart Statistics -->
    <div class="card shadow-sm mt-4 border-left-warning">
        <div class="card-header py-3 bg-light">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-chart-line mr-2"></i>إحصائيات ذكية
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">أطول سلسلة أيام</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $smartStats['longest_streak'] }} يوم</div>
                            <small class="text-muted">أيام متتالية بمشي</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">أنشط يوم في الأسبوع</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $smartStats['most_active_day'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">التقدم نحو الهدف (10,000 خطوة)</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $smartStats['goal_progress'] }}%</div>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($smartStats['goal_progress'], 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">التغيير الأسبوعي</div>
                            <div class="h4 mb-0 font-weight-bold {{ $smartStats['weekly_change_percent'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $smartStats['weekly_change_percent'] >= 0 ? '+' : '' }}{{ $smartStats['weekly_change_percent'] }}%
                            </div>
                            <small class="text-muted">مقارنة بالأسبوع الماضي</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">التغيير الشهري</div>
                            <div class="h4 mb-0 font-weight-bold {{ $smartStats['monthly_change_percent'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $smartStats['monthly_change_percent'] >= 0 ? '+' : '' }}{{ $smartStats['monthly_change_percent'] }}%
                            </div>
                            <small class="text-muted">مقارنة بالشهر الماضي</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">أعلى متوسط يومي</div>
                            <ul class="list-unstyled mb-0 small">
                                @forelse($smartStats['avg_daily_per_user']->take(3) as $avg)
                                    <li>{{ $avg['user']->name ?? '-' }}: {{ number_format($avg['avg_daily']) }} خطوة/يوم</li>
                                @empty
                                    <li class="text-muted">لا توجد بيانات</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @if($smartStats['improved_users']->isNotEmpty())
            <div class="mt-3">
                <h6 class="font-weight-bold mb-2">أكثر المستخدمين تحسنًا (هذا الشهر vs الشهر الماضي)</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>المستخدم</th>
                                <th>المتوسط الشهر الحالي</th>
                                <th>المتوسط الشهر الماضي</th>
                                <th>نسبة التحسن</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($smartStats['improved_users'] as $imp)
                            <tr>
                                <td>{{ $imp['user']->name }}</td>
                                <td>{{ number_format($imp['this_month_avg']) }}</td>
                                <td>{{ number_format($imp['last_month_avg']) }}</td>
                                <td class="text-success font-weight-bold">+{{ $imp['improvement_percent'] }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
