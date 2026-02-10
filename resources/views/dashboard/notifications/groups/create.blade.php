@extends('layouts.app')

@section('title', 'مجموعة جديدة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-users text-primary mr-2"></i>مجموعة جديدة
            </h1>
        </div>
        <a href="{{ route('dashboard.notification-groups.index') }}" class="btn btn-secondary btn-sm">العودة للمجموعات</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('dashboard.notification-groups.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="name">اسم المجموعة</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="description">الوصف (اختياري)</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">إنشاء المجموعة</button>
                <a href="{{ route('dashboard.notification-groups.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection
