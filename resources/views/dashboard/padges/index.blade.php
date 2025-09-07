@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">إدارة الشارات (Badges)</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active" aria-current="page">الشارات</li>
                </ol>
            </nav>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الشارات --}}
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-medal" title="إجمالي الشارات" :value="$padgesCount" color="primary" />
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-check-circle" title="الشارات النشطة" :value="$activePadgesCount" color="success" />
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-times-circle" title="الشارات غير النشطة" :value="$inactivePadgesCount" color="danger" />
            </div>
        </div>

        {{-- بطاقة قائمة الشارات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الشارات</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createPadgeModal">
                    <i class="fas fa-plus fa-sm text-white-50"></i> إضافة شارة جديدة
                </button>
            </div>
            <div class="card-body">
                {{-- نموذج البحث --}}
                <form action="{{ route('padges.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو الوصف..."
                            value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>

                {{-- جدول الشارات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="padgesTable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>الاسم</th>
                                <th>الوصف</th>
                                <th>الحالة</th>
                                <th>ترتيب العرض</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($padges as $padge)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ $padge->image ? asset('storage/' . $padge->image) : asset('img/default-badge.png') }}"
                                            alt="{{ $padge->name }}" class="rounded" width="50" height="50"
                                            style="object-fit: cover;">
                                    </td>
                                    <td>
                                        <span class="badge"
                                            style="background-color: {{ $padge->color ?? '#6c757d' }}; color: white; padding: 5px 10px;">{{ $padge->name }}</span>
                                    </td>
                                    <td>{{ Str::limit($padge->description, 50, '...') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $padge->is_active ? 'success' : 'danger' }}">
                                            {{ $padge->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>{{ $padge->sort_order }}</td>
                                    <td>
                                        {{-- أزرار الإجراءات --}}
                                        <a class="btn btn-info btn-sm" href="{{ route('padges.people.index', $padge) }}"
                                            title="عرض الأشخاص">
                                            <i class="fas fa-users"></i>
                                        </a>

                                        {{-- مودال الأشخاص --}}
                                        <button class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#editPadgeModal{{ $padge->id }}" title="تعديل"><i
                                                class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#deletePadgeModal{{ $padge->id }}" title="حذف"><i
                                                class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                {{-- @include('dashboard.padges.modals.people', ['padge' => $padge]) --}}
                                {{-- تضمين المودالات لكل شارة --}}
                                @include('dashboard.padges.modals.edit', ['padge' => $padge])
                                @include('dashboard.padges.modals.delete', ['padge' => $padge])
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد شارات لعرضها حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $padges->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة شارة جديدة --}}
    @include('dashboard.padges.modals.create')
@endsection

@push('scripts')
    <script>
        // لعرض اسم الملف المختار في حقل رفع الملفات
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    </script>
@endpush
