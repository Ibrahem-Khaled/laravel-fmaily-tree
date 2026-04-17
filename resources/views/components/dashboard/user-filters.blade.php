@props([
    'action',
])

<form action="{{ $action }}" method="GET" class="row mb-4 align-items-end">
    <div class="col-md-4 mb-3 mb-md-0">
        <small class="text-muted d-block mb-1">بحث</small>
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="الاسم، البريد أو الهاتف…" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit" aria-label="بحث">
                    <i class="fe fe-search fe-16"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3 mb-md-0">
        <small class="text-muted d-block mb-1">الدور</small>
        <select name="role" class="form-control" onchange="this.form.submit()">
            <option value="">كل الأدوار</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مدير</option>
            <option value="merchant" {{ request('role') == 'merchant' ? 'selected' : '' }}>تاجر</option>
            <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>عميل</option>
            <option value="driver" {{ request('role') == 'driver' ? 'selected' : '' }}>مندوب</option>
        </select>
    </div>
    <div class="col-md-3 mb-3 mb-md-0">
        <small class="text-muted d-block mb-1">الحالة</small>
        <select name="status" class="form-control" onchange="this.form.submit()">
            <option value="">كل الحالات</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
            <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>محظور</option>
        </select>
    </div>
</form>
