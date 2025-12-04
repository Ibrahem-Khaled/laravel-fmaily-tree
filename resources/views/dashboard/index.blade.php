@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt text-primary mr-2"></i>لوحة التحكم
            </h1>
            <p class="text-muted mt-2">نظرة شاملة على عائلة السريع - أعضاء العائلة فقط</p>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge badge-success mr-2">
                <i class="fas fa-check-circle mr-1"></i>عائلة السريع
            </span>
            <span class="text-muted">
                <i class="fas fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::now()->format('Y/m/d') }}
            </span>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="row mb-4">
        <!-- إجمالي الأشخاص -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <i class="fas fa-users mr-1"></i>إجمالي الأشخاص
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['totalPeople']) }}</div>
                            <div class="mt-2">
                                <small class="text-success font-weight-bold">
                                    <i class="fas fa-heartbeat mr-1"></i>{{ $stats['alivePeople'] }} أحياء
                                </small>
                                <span class="text-muted mx-1">•</span>
                                <small class="text-muted">
                                    <i class="fas fa-headstone mr-1"></i>{{ $stats['deceasedPeople'] }} متوفين
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-lg bg-primary">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الذكور والإناث -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                <i class="fas fa-venus-mars mr-1"></i>التوزيع حسب الجنس
                            </div>
                            <div class="row mt-2">
                                <div class="col-6 text-center">
                                    <div class="h4 mb-0 font-weight-bold text-primary">{{ number_format($stats['maleCount']) }}</div>
                                    <small class="text-muted">ذكور</small>
                                </div>
                                <div class="col-6 text-center">
                                    <div class="h4 mb-0 font-weight-bold text-danger">{{ number_format($stats['femaleCount']) }}</div>
                                    <small class="text-muted">إناث</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-lg bg-info">
                                <i class="fas fa-venus-mars text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الزيجات -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <i class="fas fa-ring mr-1"></i>إجمالي الزيجات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['totalMarriages']) }}</div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-check mr-1"></i>{{ $stats['marriagesThisMonth'] }} هذا الشهر
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-lg bg-success">
                                <i class="fas fa-ring text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الأجيال -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <i class="fas fa-sitemap mr-1"></i>عدد الأجيال
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['totalGenerations']) }}</div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-layer-group mr-1"></i>أجيال متتالية
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle-lg bg-warning">
                                <i class="fas fa-sitemap text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <i class="fas fa-heartbeat mr-1"></i>الأحياء
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-success">{{ number_format($stats['alivePeople']) }}</div>
                        </div>
                        <div class="icon-circle-md bg-success">
                            <i class="fas fa-heartbeat text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">
                                <i class="fas fa-headstone mr-1"></i>المتوفين
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-600">{{ number_format($stats['deceasedPeople']) }}</div>
                        </div>
                        <div class="icon-circle-md bg-gray-400">
                            <i class="fas fa-headstone text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <i class="fas fa-user-plus mr-1"></i>إضافات هذا الشهر
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-primary">{{ number_format($stats['peopleAddedThisMonth']) }}</div>
                        </div>
                        <div class="icon-circle-md bg-primary">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                <i class="fas fa-award mr-1"></i>أشخاص بأوسمة
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-info">{{ number_format($stats['peopleWithBadges']) }}</div>
                        </div>
                        <div class="icon-circle-md bg-info">
                            <i class="fas fa-award text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content & Media Statistics -->
    <div class="row mb-4">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar mr-2"></i>إحصائيات المحتوى والأنشطة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-box text-center p-3 border rounded hover-lift">
                                <div class="icon-circle-md bg-primary mx-auto mb-2">
                                    <i class="fas fa-images text-white"></i>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['totalImages']) }}</div>
                                <small class="text-muted">صورة</small>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-box text-center p-3 border rounded hover-lift">
                                <div class="icon-circle-md bg-info mx-auto mb-2">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['totalArticles']) }}</div>
                                <small class="text-muted">مقال</small>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-box text-center p-3 border rounded hover-lift">
                                <div class="icon-circle-md bg-success mx-auto mb-2">
                                    <i class="fas fa-tv text-white"></i>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['activePrograms']) }}</div>
                                <small class="text-muted">برنامج نشط</small>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-box text-center p-3 border rounded hover-lift">
                                <div class="icon-circle-md bg-warning mx-auto mb-2">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['activeEvents']) }}</div>
                                <small class="text-muted">مناسبة نشطة</small>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-box text-center p-3 border rounded hover-lift">
                                <div class="icon-circle-md bg-danger mx-auto mb-2">
                                    <i class="fas fa-building text-white"></i>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['totalCouncils']) }}</div>
                                <small class="text-muted">مجلس</small>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-box text-center p-3 border rounded hover-lift">
                                <div class="icon-circle-md bg-secondary mx-auto mb-2">
                                    <i class="fas fa-award text-white"></i>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['totalBadges']) }}</div>
                                <small class="text-muted">وسام</small>
                            </div>
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
            <div class="card shadow mb-4 hover-lift">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calendar-day mr-2"></i>حدث في مثل هذا اليوم...
                    </h6>
                    <span class="badge badge-light">{{ \Carbon\Carbon::now()->format('d M') }}</span>
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
                                <div class="list-group-item d-flex align-items-center border-0 px-0 py-3">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning shadow-sm">
                                            <i class="fas fa-birthday-cake text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-gray-800">
                                            <i class="fas fa-gift text-warning mr-1"></i>ميلاد {{ $person->full_name }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-calendar mr-1"></i>عام {{ $person->birth_date->year }}
                                            <span class="mx-1">•</span>
                                            <i class="fas fa-birthday-cake mr-1"></i>{{ $person->birth_date->age }} سنة
                                        </div>
                                    </div>
                                    <a href="{{ route('people.show', $person) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            @endforeach

                            @foreach($events['marriagesToday'] as $marriage)
                                <div class="list-group-item d-flex align-items-center border-0 px-0 py-3">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-info shadow-sm">
                                            <i class="fas fa-handshake text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-gray-800">
                                            <i class="fas fa-ring text-info mr-1"></i>زواج {{ $marriage->husband->full_name }} و {{ $marriage->wife->full_name }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-calendar mr-1"></i>عام {{ $marriage->married_at->year }}
                                            <span class="mx-1">•</span>
                                            <i class="fas fa-clock mr-1"></i>{{ $marriage->married_at->diffInYears(\Carbon\Carbon::now()) }} سنة مضت
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow mb-4 hover-lift">
                <div class="card-header py-3 bg-gradient-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bolt mr-2"></i>إجراءات سريعة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('sila') }}" class="btn btn-primary btn-block btn-icon-split shadow-sm hover-lift">
                                <span class="icon text-white-50">
                                    <i class="fas fa-sitemap"></i>
                                </span>
                                <span class="text">عرض شجرة العائلة</span>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('dashboard.visit-logs.index') }}" class="btn btn-success btn-block btn-icon-split shadow-sm hover-lift">
                                <span class="icon text-white-50">
                                    <i class="fas fa-eye"></i>
                                </span>
                                <span class="text">سجل الزيارات</span>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('gallery.index') }}" class="btn btn-info btn-block btn-icon-split shadow-sm hover-lift">
                                <span class="icon text-white-50">
                                    <i class="fas fa-images"></i>
                                </span>
                                <span class="text">معرض الصور</span>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('people.index') }}" class="btn btn-warning btn-block btn-icon-split shadow-sm hover-lift">
                                <span class="icon text-white-50">
                                    <i class="fas fa-users"></i>
                                </span>
                                <span class="text">إدارة الأشخاص</span>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('dashboard.programs.index') }}" class="btn btn-secondary btn-block btn-icon-split shadow-sm hover-lift">
                                <span class="icon text-white-50">
                                    <i class="fas fa-tv"></i>
                                </span>
                                <span class="text">إدارة البرامج</span>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('dashboard.events.index') }}" class="btn btn-danger btn-block btn-icon-split shadow-sm hover-lift">
                                <span class="icon text-white-50">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <span class="text">إدارة المناسبات</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fun Facts Card -->
            <div class="card shadow mb-4 border-left-info hover-lift">
                <div class="card-header py-3 bg-gradient-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-lightbulb mr-2"></i>حقائق شيقة عن العائلة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($funFact['personWithMostChildren'])
                            <div class="col-md-4 mb-3">
                                <div class="card border-left-warning h-100 shadow-sm hover-lift">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-2">
                                            <i class="fas fa-trophy mr-1"></i>أكثر شخص لديه أبناء
                                        </div>
                                        <div class="h6 mb-1 font-weight-bold text-gray-800">{{ $funFact['personWithMostChildren']->full_name }}</div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-warning">
                                                <i class="fas fa-child mr-1"></i>{{ $funFact['personWithMostChildren']->children_count ?? 0 }} طفل
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($funFact['oldestPerson'])
                            <div class="col-md-4 mb-3">
                                <div class="card border-left-success h-100 shadow-sm hover-lift">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-2">
                                            <i class="fas fa-crown mr-1"></i>أكبر شخص سناً
                                        </div>
                                        <div class="h6 mb-1 font-weight-bold text-gray-800">{{ $funFact['oldestPerson']->full_name }}</div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-success">
                                                <i class="fas fa-birthday-cake mr-1"></i>{{ $funFact['oldestPerson']->birth_date->age }} سنة
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($funFact['latestMarriage'])
                            <div class="col-md-4 mb-3">
                                <div class="card border-left-danger h-100 shadow-sm hover-lift">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-2">
                                            <i class="fas fa-heart mr-1"></i>أحدث زواج
                                        </div>
                                        <div class="h6 mb-1 font-weight-bold text-gray-800">
                                            {{ $funFact['latestMarriage']->husband->full_name }} و {{ $funFact['latestMarriage']->wife->full_name }}
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-danger">
                                                <i class="fas fa-clock mr-1"></i>{{ $funFact['latestMarriage']->married_at->diffForHumans() }}
                                            </span>
                                        </div>
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
            <div class="card shadow mb-4 hover-lift">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user-clock mr-2"></i>أضيفوا مؤخراً
                    </h6>
                    <span class="badge badge-light">{{ $recentlyAdded->count() }}</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($recentlyAdded as $person)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-0 py-2">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary shadow-sm">
                                            <span class="text-white font-weight-bold">{{ mb_substr($person->first_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-gray-800">{{ $person->full_name }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock mr-1"></i>{{ $person->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <a href="{{ route('people.show', $person) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted border-0 py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                لم يتم إضافة أشخاص بعد
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Upcoming Birthdays Card -->
            <div class="card shadow mb-4 hover-lift">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-gift mr-2"></i>ايام ميلاد قادمة
                    </h6>
                    <span class="badge badge-light">هذا الشهر</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($upcomingBirthdays as $person)
                            <li class="list-group-item px-0 border-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-warning shadow-sm">
                                                <i class="fas fa-birthday-cake text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-gray-800">{{ $person->full_name }}</div>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar mr-1"></i>{{ $person->birth_date->format('d M') }}
                                                </small>
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
                            <li class="list-group-item text-center text-muted border-0 py-4">
                                <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                                لا توجد ايام ميلاد قادمة
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

.icon-circle-md {
    height: 3rem;
    width: 3rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-circle-lg {
    height: 4rem;
    width: 4rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #36b9cc 0%, #1cc88a 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f6c23e 0%, #e74a3b 100%);
}

.stat-box {
    transition: all 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
</style>
@endsection
