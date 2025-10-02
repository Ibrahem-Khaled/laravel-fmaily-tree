@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="container-fluid">
    {{-- عنوان الصفحة ومسار التنقل --}}
    <div class="row">
        <div class="col-12">
        <h1 class="h3 mb-0 text-gray-800">إدارة المستخدمين</h1>
        <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">المستخدمين</li>
            </ol>
        </nav>
        </div>
    </div>

    @include('components.alerts')

    {{-- إحصائيات المستخدمين --}}
    <div class="row mb-4">
        {{-- إجمالي المستخدمين --}}
            <x-stats-card icon="fas fa-users" title="إجمالي المستخدمين" :value="$usersCount" color="primary" />

        {{-- المستخدمون النشطون --}}

            <x-stats-card icon="fas fa-user-check" title="المستخدمون النشطون" :value="$activeUsersCount" color="success" />

        {{-- عدد المشرفين --}}

            <x-stats-card icon="fas fa-user-shield" title="عدد المشرفين" :value="$adminsCount" color="info" />

        {{-- عدد الأدوار --}}

            <x-stats-card icon="fas fa-user-tag" title="عدد الأدوار" :value="count($roles)" color="warning" />

    </div>

    {{-- بطاقة قائمة المستخدمين --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">قائمة المستخدمين</h6>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                <i class="fas fa-plus"></i> إضافة مستخدم
            </button>
        </div>
        <div class="card-body">
            {{-- تبويب الأدوار --}}
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ $selectedRole === 'all' ? 'active' : '' }}"
                        href="{{ route('users.index') }}">الكل</a>
                </li>
                        @foreach ($roles as $role)
                            <li class="nav-item">
                                <a class="nav-link {{ $selectedRole === $role->name ? 'active' : '' }}"
                                    href="{{ route('users.index', ['role' => $role->name]) }}">
                                    {{ \App\Support\TranslationHelper::userRole($role->name) }}
                                </a>
                            </li>
                        @endforeach
            </ul>

            {{-- نموذج البحث --}}
            <form action="{{ route('users.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                        placeholder="ابحث بالاسم أو البريد الإلكتروني..." value="{{ request('search') }}">
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>
            </form>

            {{-- جدول المستخدمين --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>الاسم</th>
                            <th>الدور</th>
                            <th>البريد الإلكتروني</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle mr-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $user->name }}</div>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge badge-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'super_admin' ? 'warning' : 'info') }}">
                                            {{ \App\Support\TranslationHelper::userRole($role->name) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge badge-{{ $user->email_verified_at ? 'success' : 'warning' }}">
                                        {{ $user->email_verified_at ? 'مفعل' : 'غير مفعل' }}
                                    </span>
                                </td>
                                <td>
                                    {{-- زر عرض --}}
                                    <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                        data-target="#showUserModal{{ $user->id }}" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    {{-- زر تعديل --}}
                                    <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                        data-target="#editUserModal{{ $user->id }}" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- زر حذف --}}
                                    @php
                                        $userRoles = auth()->user()->roles->pluck('name')->toArray();
                                        $canDelete = in_array('admin', $userRoles) || in_array('super_admin', $userRoles);
                                    @endphp
                                    @if($canDelete)
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteUserModal{{ $user->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif

                                    {{-- تضمين المودالات لكل مستخدم --}}
                                    @include('dashboard.users.modals.show', ['user' => $user])
                                    @include('dashboard.users.modals.edit', ['user' => $user])
                                    @if($canDelete)
                                        @include('dashboard.users.modals.delete', ['user' => $user])
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">لا يوجد مستخدمون</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- الترقيم --}}
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

{{-- مودال إضافة مستخدم (ثابت) --}}
@include('dashboard.users.modals.create')
@endsection

@push('styles')
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(45deg, #007bff, #0056b3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
    }

    .btn-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: 1px solid transparent;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
    }

    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
    }

    .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }
</style>
@endpush

@push('scripts')
    {{-- تفعيل التولتيب الافتراضي --}}
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
