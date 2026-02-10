@extends('layouts.app')

@section('title', 'الاشعارات')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-bell text-primary mr-2"></i>الاشعارات
            </h1>
            <p class="text-muted mt-1">إرسال دعوات واتساب وإدارة المجموعات وسجل الإرسال</p>
        </div>
    </div>

    <div class="row">
        @can('notifications.send')
        <div class="col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        <i class="fas fa-paper-plane mr-1"></i>إرسال دعوة
                    </div>
                    <p class="text-muted small">إرسال رسالة أو صورة أو فيديو أو صوت عبر واتساب لأشخاص أو مجموعة.</p>
                    <a href="{{ route('dashboard.notifications.send') }}" class="btn btn-primary btn-sm">فتح صفحة الإرسال</a>
                </div>
            </div>
        </div>
        @endcan
        @can('notifications.manage-groups')
        <div class="col-md-4 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        <i class="fas fa-users mr-1"></i>المجموعات
                    </div>
                    <p class="text-muted small">إنشاء مجموعات من الأشخاص لإعادة استخدامها عند الإرسال.</p>
                    <a href="{{ route('dashboard.notification-groups.index') }}" class="btn btn-success btn-sm">إدارة المجموعات</a>
                </div>
            </div>
        </div>
        @endcan
        <div class="col-md-4 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        <i class="fas fa-history mr-1"></i>سجل الإرسال
                    </div>
                    <p class="text-muted small">عرض سجل الإشعارات المرسلة وحالتها.</p>
                    <a href="{{ route('dashboard.notifications.logs') }}" class="btn btn-info btn-sm">عرض السجل</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
