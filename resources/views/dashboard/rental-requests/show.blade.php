@extends('layouts.app')

@section('title', 'تفاصيل طلب الاستعارة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-eye text-primary mr-2"></i>تفاصيل طلب الاستعارة
            </h1>
        </div>
        <a href="{{ route('dashboard.rental-requests.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right mr-2"></i>رجوع
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

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">معلومات الطلب</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">المنتج</th>
                            <td>{{ $request->product->name }}</td>
                        </tr>
                        <tr>
                            <th>الاسم</th>
                            <td>{{ $request->name }}</td>
                        </tr>
                        <tr>
                            <th>رقم الهاتف</th>
                            <td>{{ $request->phone }}</td>
                        </tr>
                        @if($request->email)
                            <tr>
                                <th>البريد الإلكتروني</th>
                                <td>{{ $request->email }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>تاريخ بداية الاستعارة</th>
                            <td>{{ $request->start_date->format('Y/m/d') }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ نهاية الاستعارة</th>
                            <td>{{ $request->end_date->format('Y/m/d') }}</td>
                        </tr>
                        @if($request->notes)
                            <tr>
                                <th>ملاحظات</th>
                                <td>{{ $request->notes }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>الحالة</th>
                            <td>
                                <span class="badge badge-{{ $request->status == 'pending' ? 'warning' : ($request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'info')) }}">
                                    {{ $request->status_label }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>تاريخ الطلب</th>
                            <td>{{ $request->created_at->format('Y/m/d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">تحديث الحالة</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.rental-requests.update-status', $request) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label>الحالة</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>معلق</option>
                                <option value="approved" {{ $request->status == 'approved' ? 'selected' : '' }}>موافق عليه</option>
                                <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                                <option value="completed" {{ $request->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>ملاحظات الإدارة</label>
                            <textarea name="admin_notes" class="form-control" rows="4">{{ $request->admin_notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-2"></i>حفظ التغييرات
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
