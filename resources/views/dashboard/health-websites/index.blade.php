@extends('layouts.app')

@section('title', 'إدارة المواقع الصحية')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-heartbeat text-primary mr-2"></i>إدارة المواقع الصحية
            </h1>
        </div>
        <a href="{{ route('dashboard.health-websites.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>إضافة موقع جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي المواقع</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">نشطة</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-secondary shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">غير نشطة</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.health-websites.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label>الفئة</label>
                        <select name="category" class="form-control" onchange="this.form.submit()">
                            <option value="">جميع الفئات</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>البحث</label>
                        <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="ابحث بالاسم أو الرابط...">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Websites Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الرابط</th>
                            <th>الفئة</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($websites as $website)
                            <tr>
                                <td>{{ $website->name }}</td>
                                <td><a href="{{ $website->url }}" target="_blank">{{ Str::limit($website->url, 40) }}</a></td>
                                <td>{{ $website->category ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('dashboard.health-websites.toggle', $website) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-{{ $website->is_active ? 'success' : 'secondary' }} toggle-btn"
                                                title="{{ $website->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                            <i class="fas fa-{{ $website->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                            {{ $website->is_active ? 'نشط' : 'غير نشط' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.health-websites.edit', $website) }}" class="btn btn-sm btn-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.health-websites.destroy', $website) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">لا توجد مواقع</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $websites->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .toggle-btn {
        min-width: 100px;
    }
</style>
@endpush
@endsection
