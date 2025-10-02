@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">إدارة الأدوار</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active" aria-current="page">الأدوار</li>
                </ol>
            </nav>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الأدوار --}}
        <div class="row mb-4">
            <x-stats-card icon="fas fa-user-tag" title="إجمالي الأدوار" :value="$totalRoles" color="primary" />
            <x-stats-card icon="fas fa-check-circle" title="الأدوار النشطة" :value="$activeRoles" color="success" />

            <x-stats-card icon="fas fa-times-circle" title="الأدوار غير النشطة" :value="$inactiveRoles" color="danger" />
        </div>

        {{-- بطاقة قائمة الأدوار --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الأدوار</h6>
                <button class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#createRoleModal">
                    <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                    <span class="text">إضافة دور جديد</span>
                </button>
            </div>
            <div class="card-body">
                {{-- تبويبات الفلترة --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $statusFilter === 'all' ? 'active' : '' }}"
                            href="{{ route('roles.index') }}">الكل</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $statusFilter === 'active' ? 'active' : '' }}"
                            href="{{ route('roles.index', ['status' => 'active']) }}">نشط</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $statusFilter === 'inactive' ? 'active' : '' }}"
                            href="{{ route('roles.index', ['status' => 'inactive']) }}">غير نشط</a>
                    </li>
                </ul>

                {{-- نموذج البحث --}}
                <form action="{{ route('roles.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو الوصف..."
                            value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول الأدوار --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>الوصف</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->description ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $role->is_active ? 'success' : 'danger' }}">
                                            {{ $role->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>{{ $role->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editRoleModal{{ $role->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteRoleModal{{ $role->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- تضمين المودالات لكل دور --}}
                                        @include('dashboard.roles.modals.edit', ['role' => $role])
                                        @include('dashboard.roles.modals.delete', ['role' => $role])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد أدوار لعرضها.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $roles->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة دور (ثابت) --}}
    @include('dashboard.roles.modals.create')
@endsection

@push('scripts')
    <script>
        // تفعيل التولتيب الافتراضي
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
