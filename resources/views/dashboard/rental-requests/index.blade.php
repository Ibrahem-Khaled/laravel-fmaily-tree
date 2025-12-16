@extends('layouts.app')

@section('title', 'إدارة طلبات الاستعارة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-hand-holding text-primary mr-2"></i>إدارة طلبات الاستعارة
            </h1>
        </div>
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
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الطلبات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">معلقة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">موافق عليها</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">مكتملة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.rental-requests.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label>الحالة</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>معلق</option>
                            <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>موافق عليه</option>
                            <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>البحث</label>
                        <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="ابحث بالاسم، الهاتف، أو اسم المنتج...">
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

    <!-- Requests Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>المستخدم</th>
                            <th>التواريخ</th>
                            <th>الحالة</th>
                            <th>تاريخ الطلب</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td>{{ $request->product->name }}</td>
                                <td>{{ $request->name }}<br><small class="text-muted">{{ $request->phone }}</small></td>
                                <td>
                                    <small>من: {{ $request->start_date->format('Y/m/d') }}</small><br>
                                    <small>إلى: {{ $request->end_date->format('Y/m/d') }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $request->status == 'pending' ? 'warning' : ($request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'info')) }}">
                                        {{ $request->status_label }}
                                    </span>
                                </td>
                                <td>{{ $request->created_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    <a href="{{ route('dashboard.rental-requests.show', $request) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> عرض
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">لا توجد طلبات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
