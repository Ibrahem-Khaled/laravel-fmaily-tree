@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الأشخاص</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الأشخاص</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الأشخاص --}}
        <div class="row mb-4">
            {{-- البطاقات الإحصائية --}}
            <div class="col-xl-2 col-md-4 mb-4">
                <x-stats-card icon="fas fa-users" title="إجمالي الأشخاص" :value="$stats['total']" color="primary" />
            </div>
            <div class="col-xl-2 col-md-4 mb-4">
                <x-stats-card icon="fas fa-male" title="عدد الذكور" :value="$stats['male']" color="info" />
            </div>
            <div class="col-xl-2 col-md-4 mb-4">
                <x-stats-card icon="fas fa-female" title="عدد الإناث" :value="$stats['female']" color="pink" />
            </div>
            <div class="col-xl-2 col-md-4 mb-4">
                <x-stats-card icon="fas fa-heart" title="عدد الأحياء" :value="$stats['living']" color="success" />
            </div>
            <div class="col-xl-2 col-md-4 mb-4">
                <x-stats-card icon="fas fa-camera" title="أشخاص لديهم صور" :value="$stats['with_photos']" color="warning" />
            </div>
            <div class="col-xl-2 col-md-4 mb-4">
                <a href="{{ route('family-tree') }}" class="btn btn-dark btn-block h-100 py-3">
                    <i class="fas fa-tree"></i> عرض شجرة العائلة
                </a>
            </div>
        </div>

        {{-- بطاقة قائمة الأشخاص --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الأشخاص</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createPersonModal">
                    <i class="fas fa-plus"></i> إضافة شخص
                </button>
            </div>
            <div class="card-body">
                {{-- ✅ تعديل تبويبات الجنس: إضافة قيمة البحث الحالية إلى الروابط --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('gender') ? 'active' : '' }}"
                            href="{{ route('people.index', ['search' => request('search')]) }}">الكل</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('gender') === 'male' ? 'active' : '' }}"
                            href="{{ route('people.index', ['gender' => 'male', 'search' => request('search')]) }}">الذكور</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('gender') === 'female' ? 'active' : '' }}"
                            href="{{ route('people.index', ['gender' => 'female', 'search' => request('search')]) }}">الإناث</a>
                    </li>
                </ul>

                {{-- ✅ تعديل نموذج البحث: إضافة حقل خفي للجنس للحفاظ على الفلتر --}}
                <form action="{{ route('people.index') }}" method="GET" class="mb-4">
                    @if (request('gender'))
                        <input type="hidden" name="gender" value="{{ request('gender') }}">
                    @endif
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="ابحث بالاسم الأول أو الأخير..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول الأشخاص --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>الاسم</th>
                                <th>الام</th>
                                <th>الجنس</th>
                                <th>العمر</th>
                                <th>فترة الحياة</th>
                                <th>المهنة</th>
                                <th>المكان</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($people as $person)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $person->avatar }}" alt="{{ $person->full_name }}"
                                                class="rounded-circle mr-2" width="40" height="40">
                                            {{ $person->full_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#">
                                            {{ $person->mother ? $person->mother->full_name : 'غير معروف' }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $person->gender == 'male' ? 'primary' : 'pink' }}">
                                            {{ $person->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                        </span>
                                    </td>
                                    <td>{{ $person->age ?? 'غير معروف' }}</td>
                                    <td>{{ $person->life_span ?? 'غير معروف' }}</td>
                                    <td>{{ $person->occupation ?? '-' }}</td>
                                    <td>{{ $person->location ?? '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editPersonModal{{ $person->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deletePersonModal{{ $person->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @include('people.modals.edit', ['person' => $person])
                                        @include('people.modals.delete', ['person' => $person])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا يوجد أشخاص مطابقين لنتيجة البحث</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{-- لم نعد بحاجة إلى appends() هنا لأننا استخدمنا withQueryString() في المتحكم --}}
                    {{ $people->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة شخص (ثابت) --}}
    @include('people.modals.create')
@endsection

@push('scripts')
    <script>
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endpush
