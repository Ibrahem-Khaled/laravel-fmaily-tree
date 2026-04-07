@extends('layouts.app')

@section('title', 'فئات البرامج')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb bg-transparent p-0 mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.programs.index') }}">البرامج</a></li>
                    <li class="breadcrumb-item active" aria-current="page">فئات البرامج</li>
                </ol>
            </nav>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-folder-open text-primary mr-2"></i>فئات برامج العائلة
            </h1>
            <p class="text-muted mb-0">تجميع البرامج في الصفحة الرئيسية تحت عناوين الفئات — تُدار من هنا ضمن قسم البرامج</p>
        </div>
        <div>
            <a href="{{ route('dashboard.programs.index') }}" class="btn btn-light border shadow-sm mr-2">
                <i class="fas fa-arrow-right mr-1"></i>قائمة البرامج
            </a>
            @can('programs.create')
                <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة فئة
                </button>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left: 0.25rem solid #4e73df !important;">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الفئات</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-left-success shadow h-100 py-2" style="border-left: 0.25rem solid #1cc88a !important;">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">فئات نشطة</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 font-weight-bold text-primary"><i class="fas fa-list mr-2"></i>القائمة</h6>
        </div>
        <div class="card-body p-0">
            @if($categories->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                    <p>لا توجد فئات بعد. أضف فئة ثم اربط البرامج بها من صفحة إدارة البرامج.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>الترتيب</th>
                                <th>الاسم</th>
                                <th>البرامج</th>
                                <th>الحالة</th>
                                <th class="text-left">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                                <tr>
                                    <td>{{ $cat->sort_order }}</td>
                                    <td class="font-weight-bold">{{ $cat->name }}</td>
                                    <td>{{ $cat->programs_count }}</td>
                                    <td>
                                        @if($cat->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-secondary">معطل</span>
                                        @endif
                                    </td>
                                    <td class="text-left">
                                        @can('programs.update')
                                            <form action="{{ route('dashboard.program-categories.toggle', $cat) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary" title="تبديل التفعيل">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="{{ $cat->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endcan
                                        @can('programs.delete')
                                            <form action="{{ route('dashboard.program-categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف الفئة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@can('programs.create')
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>إضافة فئة</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('dashboard.program-categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">اسم الفئة <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required maxlength="255" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">وصف (اختياري)</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">الترتيب</label>
                        <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order') }}" placeholder="يُحدد تلقائياً إن تُرك فارغاً">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="add_is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="add_is_active">فئة نشطة (تظهر في الموقع)</label>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@can('programs.update')
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>تعديل فئة</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">اسم الفئة <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required maxlength="255">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">وصف (اختياري)</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">الترتيب</label>
                        <input type="number" name="sort_order" id="edit_sort_order" class="form-control" min="0">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="edit_is_active">
                        <label class="form-check-label" for="edit_is_active">فئة نشطة</label>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تحديث</button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    $categoriesPayload = $categories->map(fn ($c) => [
        'id' => $c->id,
        'name' => $c->name,
        'description' => $c->description ?? '',
        'sort_order' => $c->sort_order,
        'is_active' => (bool) $c->is_active,
        'update_url' => route('dashboard.program-categories.update', $c),
    ]);
@endphp
<script>
const programCategoriesPayload = @json($categoriesPayload);
document.querySelectorAll('.edit-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = parseInt(this.getAttribute('data-id'), 10);
        var row = programCategoriesPayload.find(function(x) { return x.id === id; });
        if (!row) return;
        document.getElementById('editForm').action = row.update_url;
        document.getElementById('edit_name').value = row.name;
        document.getElementById('edit_description').value = row.description;
        document.getElementById('edit_sort_order').value = row.sort_order;
        document.getElementById('edit_is_active').checked = row.is_active;
        $('#editModal').modal('show');
    });
});
</script>
@endcan
@endsection
