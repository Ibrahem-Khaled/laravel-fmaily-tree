@extends('layouts.app')

@section('title', 'مجموعات الاشعارات')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-users text-primary mr-2"></i>مجموعات الاشعارات
            </h1>
            <p class="text-muted mt-1">إدارة المجموعات لإرسال الدعوات بسرعة</p>
        </div>
        <a href="{{ route('dashboard.notification-groups.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus mr-2"></i>مجموعة جديدة
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الوصف</th>
                            <th>عدد الأشخاص</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $g)
                        <tr>
                            <td>{{ $g->name }}</td>
                            <td>{{ Str::limit($g->description, 60) }}</td>
                            <td>{{ $g->persons_count }}</td>
                            <td>
                                <a href="{{ route('dashboard.notification-groups.edit', $g) }}" class="btn btn-sm btn-primary">تعديل / إدارة الأعضاء</a>
                                <form action="{{ route('dashboard.notification-groups.destroy', $g) }}" method="post" class="d-inline" onsubmit="return confirm('حذف المجموعة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">لا توجد مجموعات. أنشئ مجموعة جديدة.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
