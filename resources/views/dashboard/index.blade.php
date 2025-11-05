@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt text-primary mr-2"></i>لوحة التحكم
            </h1>
            <p class="text-muted mt-2">نظرة شاملة على عائلة السريع</p>
        </div>
        <div class="d-flex align-items-center">
            <span class="text-muted mr-3">
                <i class="fas fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::now()->format('Y/m/d') }}
            </span>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="row mb-4">
        <!-- إجمالي الأشخاص -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الأشخاص</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['totalPeople']) }}</div>
                            <div class="mt-2">
                                <small class="text-success font-weight-bold">{{ $stats['alivePeople'] }} أحياء</small>
                                <span class="text-muted mx-1">•</span>
                                <small class="text-muted">{{ $stats['deceasedPeople'] }} متوفين</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الذكور والإناث -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">التوزيع حسب الجنس</div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="h4 mb-0 font-weight-bold text-primary">{{ number_format($stats['maleCount']) }}</div>
                                    <small class="text-muted">ذكور</small>
                                </div>
                                <div class="col-6">
                                    <div class="h4 mb-0 font-weight-bold text-danger">{{ number_format($stats['femaleCount']) }}</div>
                                    <small class="text-muted">إناث</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-venus-mars fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الزيجات -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">إجمالي الزيجات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['totalMarriages']) }}</div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-check mr-1"></i>{{ $stats['marriagesThisMonth'] }} هذا الشهر
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ring fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإضافات الحديثة -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">إضافات حديثة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['peopleAddedThisWeek']) }}</div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-chart-line mr-1"></i>{{ $stats['peopleAddedThisMonth'] }} هذا الشهر
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics Row -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">الأشخاص الأحياء</div>
                            <div class="h4 mb-0 font-weight-bold text-success">{{ number_format($stats['alivePeople']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heartbeat fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">المتوفين</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-600">{{ number_format($stats['deceasedPeople']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-headstone fa-2x text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">الزيجات هذا الشهر</div>
                            <div class="h4 mb-0 font-weight-bold text-primary">{{ number_format($stats['marriagesThisMonth']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ring fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">

        <!-- Left Column -->
        <div class="col-lg-8">

            <!-- Events Today Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-day mr-2"></i>حدث في مثل هذا اليوم...
                    </h6>
                    <span class="badge badge-primary">{{ \Carbon\Carbon::now()->format('d M') }}</span>
                </div>
                <div class="card-body">
                    @if($events['birthsToday']->isEmpty() && $events['marriagesToday']->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">لا توجد أحداث مسجلة في مثل هذا اليوم</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                        @foreach($events['birthsToday'] as $person)
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-birthday-cake text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-gray-800">ميلاد {{ $person->full_name }}</div>
                                        <div class="text-muted small">عام {{ $person->birth_date->year }} • {{ $person->birth_date->age }} سنة</div>
                                </div>
                            </div>
                        @endforeach

                        @foreach($events['marriagesToday'] as $marriage)
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-info">
                                            <i class="fas fa-handshake text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-gray-800">
                                            زواج {{ $marriage->husband->full_name }} و {{ $marriage->wife->full_name }}
                                        </div>
                                        <div class="text-muted small">
                                            عام {{ $marriage->married_at->year }} • {{ $marriage->married_at->diffInYears(\Carbon\Carbon::now()) }} سنة مضت
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>إجراءات سريعة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('sila') }}" class="btn btn-primary btn-block btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-sitemap"></i>
                                </span>
                                <span class="text">عرض شجرة العائلة</span>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('dashboard.visit-logs.index') }}" class="btn btn-success btn-block btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-eye"></i>
                                </span>
                                <span class="text">سجل الزيارات</span>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('gallery.index') }}" class="btn btn-info btn-block btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-images"></i>
                                </span>
                                <span class="text">معرض الصور</span>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('people.index') }}" class="btn btn-warning btn-block btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-users"></i>
                                </span>
                                <span class="text">إدارة الأشخاص</span>
                    </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fun Facts Card -->
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-lightbulb mr-2"></i>حقائق شيقة عن العائلة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($funFact['personWithMostChildren'])
                            <div class="col-md-4 mb-3">
                                <div class="card border-left-warning h-100">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            <i class="fas fa-trophy mr-1"></i>أكثر شخص لديه أبناء
                                        </div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $funFact['personWithMostChildren']->full_name }}</div>
                                        <small class="text-muted">{{ $funFact['personWithMostChildren']->children_count ?? 0 }} طفل</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($funFact['oldestPerson'])
                            <div class="col-md-4 mb-3">
                                <div class="card border-left-success h-100">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            <i class="fas fa-crown mr-1"></i>أكبر شخص سناً
                                        </div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $funFact['oldestPerson']->full_name }}</div>
                                        <small class="text-muted">{{ $funFact['oldestPerson']->birth_date->age }} سنة</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($funFact['latestMarriage'])
                            <div class="col-md-4 mb-3">
                                <div class="card border-left-danger h-100">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            <i class="fas fa-heart mr-1"></i>أحدث زواج
                                        </div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            {{ $funFact['latestMarriage']->husband->full_name }} و {{ $funFact['latestMarriage']->wife->full_name }}
                                        </div>
                                        <small class="text-muted">{{ $funFact['latestMarriage']->married_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                 </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="col-lg-4">

            <!-- Recently Added Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-clock mr-2"></i>أضيفوا مؤخراً
                    </h6>
                    <span class="badge badge-primary">{{ $recentlyAdded->count() }}</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($recentlyAdded as $person)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <span class="text-white font-weight-bold">{{ mb_substr($person->first_name, 0, 1) }}</span>
                                        </div>
                            </div>
                                    <div>
                                        <div class="font-weight-bold text-gray-800">{{ $person->full_name }}</div>
                            <small class="text-muted">{{ $person->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                        </li>
                        @empty
                            <li class="list-group-item text-center text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                لم يتم إضافة أشخاص بعد
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Upcoming Birthdays Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-gift mr-2"></i>أعياد ميلاد قادمة
                    </h6>
                    <span class="badge badge-info">هذا الشهر</span>
                </div>
                <div class="card-body">
                     <ul class="list-group list-group-flush">
                        @forelse($upcomingBirthdays as $person)
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-warning">
                                                <i class="fas fa-birthday-cake text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-gray-800">{{ $person->full_name }}</div>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <small class="text-muted">{{ $person->birth_date->format('d M') }}</small>
                                                @if($person->birth_date->isToday())
                                                    <span class="badge badge-warning">اليوم!</span>
                                                @elseif($person->birth_date->isTomorrow())
                                                    <span class="badge badge-info">غداً</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="h6 mb-0 font-weight-bold text-primary">{{ $person->birth_date->age }}</div>
                                        <small class="text-muted">سنة</small>
                                    </div>
                                </div>
                        </li>
                        @empty
                            <li class="list-group-item text-center text-muted">
                                <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                                لا توجد أعياد ميلاد قادمة
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>

    </div>

</div>

<style>
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
