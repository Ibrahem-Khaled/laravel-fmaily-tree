@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- عنوان الصفحة -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">لوحة التحكم</h1>
        {{-- يمكنك إضافة زر لطباعة تقرير هنا في المستقبل --}}
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> توليد تقرير</a>
    </div>

    <!-- الصف الأول: بطاقات الإحصائيات الرئيسية -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الأشخاص</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['totalPeople'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">عدد الزيجات المسجلة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['totalMarriages'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ring fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">عدد الأجيال</div>
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $stats['totalGenerations'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sitemap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الصف الثاني: المحتوى الرئيسي -->
    <div class="row">

        <!-- العمود الأيسر -->
        <div class="col-lg-7">

            <!-- حدث في مثل هذا اليوم -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-day mr-2"></i>حدث في مثل هذا اليوم...</h6>
                </div>
                <div class="card-body">
                    @if($events['birthsToday']->isEmpty() && $events['marriagesToday']->isEmpty())
                        <p class="text-center text-muted">لا توجد أحداث مسجلة في مثل هذا اليوم.</p>
                    @else
                        @foreach($events['birthsToday'] as $person)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-birthday-cake fa-fw mr-3 text-warning"></i>
                                <div>
                                    ميلاد <strong>{{ $person->full_name }}</strong>
                                    <small class="text-muted d-block">عام {{ $person->birth_date->year }}</small>
                                </div>
                            </div>
                        @endforeach
                        @foreach($events['marriagesToday'] as $marriage)
                             <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-handshake fa-fw mr-3 text-info"></i>
                                <div>
                                    زواج <strong>{{ $marriage->husband->full_name }}</strong> و <strong>{{ $marriage->wife->full_name }}</strong>
                                    <small class="text-muted d-block">عام {{ $marriage->married_at->year }}</small>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- أزرار إجراءات سريعة -->
            <div class="card shadow mb-4">
                 <div class="card-body text-center">
                     <a href="#" class="btn btn-primary btn-icon-split mx-2" data-toggle="modal" data-target="#createPersonModal">
                        <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                        <span class="text">إضافة شخص جديد</span>
                    </a>
                     <a href="{{-- route('family-tree') --}}" class="btn btn-success btn-icon-split mx-2">
                        <span class="icon text-white-50"><i class="fas fa-tree"></i></span>
                        <span class="text">عرض تواصل العائلة</span>
                    </a>
                 </div>
            </div>

        </div>

        <!-- العمود الأيمن -->
        <div class="col-lg-5">

            <!-- أفراد أضيفوا مؤخراً -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-clock mr-2"></i>أضيفوا مؤخراً</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($recentlyAdded as $person)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="{{ $person->avatar }}" class="rounded-circle mr-2" width="35" height="35">
                                <a href="{{-- route('people.show', $person->id) --}}">{{ $person->full_name }}</a>
                            </div>
                            <small class="text-muted">{{ $person->created_at->diffForHumans() }}</small>
                        </li>
                        @empty
                        <li class="list-group-item text-center">لم يتم إضافة أشخاص بعد.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- أعياد ميلاد قادمة -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-gift mr-2"></i>أعياد ميلاد قادمة (هذا الشهر)</h6>
                </div>
                <div class="card-body">
                     <ul class="list-group list-group-flush">
                        @forelse($upcomingBirthdays as $person)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user-circle mr-2 text-gray-400"></i>{{ $person->full_name }}</span>
                            <span class="badge badge-info badge-pill">{{ $person->birth_date->format('d M') }}</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center">لا توجد أعياد ميلاد قادمة.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
{{-- تأكد من وجود مودال إضافة شخص إذا كنت تستخدمه --}}
{{-- @include('dashboard.people.modals.create') --}}
@endsection
