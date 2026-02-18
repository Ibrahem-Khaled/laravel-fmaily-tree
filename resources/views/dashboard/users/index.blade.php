@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
@php
    \Carbon\Carbon::setLocale('ar');
@endphp
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
                    <a class="nav-link {{ $selectedRole === 'no-role' ? 'active' : '' }}"
                        href="{{ route('users.index', ['role' => 'no-role']) }}">بدون دور</a>
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
                        placeholder="ابحث بالاسم أو البريد الإلكتروني أو رقم الهاتف..." value="{{ request('search') }}">
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
                            <th>رقم الهاتف</th>
                            <th>اسم الأم</th>
                            <th>البريد الإلكتروني</th>
                            <th>العنوان</th>
                            <th>العمر</th>
                            <th>الدور</th>
                            <th>سجل منذ</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                                                 class="rounded-circle mr-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="avatar-circle mr-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $user->name }}</div>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->phone)
                                        <i class="fas fa-phone text-primary"></i> {{ $user->phone }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_from_ancestry && $user->mother_name)
                                        <span class="badge badge-info">
                                            <i class="fas fa-female"></i> {{ $user->mother_name }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->email)
                                        <i class="fas fa-envelope text-info"></i> {{ $user->email }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->address)
                                        <i class="fas fa-map-marker-alt text-danger"></i> {{ Str::limit($user->address, 30) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->age)
                                        <i class="fas fa-birthday-cake text-warning"></i> {{ $user->age }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="badge badge-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'super_admin' ? 'warning' : 'info') }}">
                                            {{ \App\Support\TranslationHelper::userRole($role->name) }}
                                        </span>
                                    @empty
                                        <span class="text-muted">بدون دور</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span class="text-muted" title="{{ $user->created_at->format('Y-m-d H:i') }}">
                                        <i class="fas fa-clock"></i> {{ $user->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $userRoles = auth()->user()->roles->pluck('name')->toArray();
                                        $canToggle = (in_array('admin', $userRoles) || in_array('super_admin', $userRoles)) && $user->id !== auth()->id();
                                    @endphp

                                    @if($canToggle)
                                        <button type="button"
                                                class="btn btn-sm btn-{{ $user->email_verified_at ? 'warning' : 'success' }} toggle-status-btn"
                                                data-user-id="{{ $user->id }}"
                                                data-current-status="{{ $user->email_verified_at ? 'active' : 'inactive' }}"
                                                title="{{ $user->email_verified_at ? 'إلغاء التفعيل' : 'تفعيل الحساب' }}">
                                            <i class="fas fa-{{ $user->email_verified_at ? 'ban' : 'check' }}"></i>
                                            {{ $user->email_verified_at ? 'مفعل' : 'غير مفعل' }}
                                        </button>
                                    @else
                                        <span class="badge badge-{{ $user->email_verified_at ? 'success' : 'warning' }}">
                                            {{ $user->email_verified_at ? 'مفعل' : 'غير مفعل' }}
                                        </span>
                                    @endif
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
                                <td colspan="10" class="text-center">لا يوجد مستخدمون</td>
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

        {{-- معالجة تفعيل/إلغاء تفعيل المستخدم --}}
        $('.toggle-status-btn').on('click', function() {
            const button = $(this);
            const userId = button.data('user-id');
            const currentStatus = button.data('current-status');

            // تعطيل الزر أثناء المعالجة
            button.prop('disabled', true);

            // إظهار رسالة تحميل
            const originalText = button.html();
            button.html('<i class="fas fa-spinner fa-spin"></i> جاري المعالجة...');

            $.ajax({
                url: `/dashboard/users/${userId}/toggle-status`,
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // تحديث الزر
                        if (response.status === 'active') {
                            button.removeClass('btn-success').addClass('btn-warning');
                            button.data('current-status', 'active');
                            button.attr('title', 'إلغاء التفعيل');
                            button.html('<i class="fas fa-ban"></i> مفعل');
                        } else {
                            button.removeClass('btn-warning').addClass('btn-success');
                            button.data('current-status', 'inactive');
                            button.attr('title', 'تفعيل الحساب');
                            button.html('<i class="fas fa-check"></i> غير مفعل');
                        }

                        // إظهار رسالة النجاح
                        showAlert('success', response.message);
                    } else {
                        showAlert('error', response.message);
                        button.html(originalText);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    const message = response ? response.message : 'حدث خطأ أثناء معالجة الطلب';
                    showAlert('error', message);
                    button.html(originalText);
                },
                complete: function() {
                    // إعادة تفعيل الزر
                    button.prop('disabled', false);
                }
            });
        });

        {{-- دالة إظهار التنبيهات --}}
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="fas ${icon}"></i> ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;

            // إزالة التنبيهات السابقة
            $('.alert').remove();

            // إضافة التنبيه الجديد
            $('.container-fluid').prepend(alertHtml);

            // إزالة التنبيه تلقائياً بعد 5 ثوان
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        }
    </script>
@endpush
