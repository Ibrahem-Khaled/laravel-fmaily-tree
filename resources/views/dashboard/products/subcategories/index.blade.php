@extends('layouts.app')

@section('title', 'إدارة الفئات الفرعية')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-layer-group text-primary mr-2"></i>إدارة الفئات الفرعية
            </h1>
            <p class="text-muted mb-0">قم بإدارة الفئات الفرعية للمنتجات</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus-circle mr-2"></i>إضافة فئة فرعية جديدة
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filter -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('products.subcategories.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label>الفئة الرئيسية</label>
                        <select name="category_id" class="form-control" onchange="this.form.submit()">
                            <option value="">جميع الفئات</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الفئات الفرعية</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">فئات فرعية نشطة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subcategories Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>الفئات الفرعية ({{ $subcategories->count() }})
            </h6>
        </div>
        <div class="card-body">
            @if($subcategories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الصورة</th>
                                <th>الاسم</th>
                                <th>الفئة الرئيسية</th>
                                <th>المنتجات</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subcategories as $subcategory)
                                <tr>
                                    <td>
                                        @if($subcategory->image)
                                            <img src="{{ asset('storage/' . $subcategory->image) }}" alt="{{ $subcategory->name }}" 
                                                 class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px; border-radius: 4px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">{{ $subcategory->name }}</td>
                                    <td>
                                        @if($subcategory->category)
                                            <span class="badge badge-primary">{{ $subcategory->category->name }}</span>
                                        @else
                                            <span class="badge badge-danger">لا توجد فئة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $subcategory->products_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $subcategory->is_active ? 'success' : 'secondary' }}">
                                            {{ $subcategory->is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-info" onclick="editSubcategory({{ $subcategory->id }})" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('products.subcategories.toggle', $subcategory) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-{{ $subcategory->is_active ? 'warning' : 'success' }}" 
                                                        title="{{ $subcategory->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $subcategory->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('products.subcategories.destroy', $subcategory) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة الفرعية؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-layer-group text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-muted">لا توجد فئات فرعية حالياً</h5>
                    <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#addModal">
                        <i class="fas fa-plus-circle mr-2"></i>إضافة فئة فرعية جديدة
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('products.subcategories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">إضافة فئة فرعية جديدة</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>الفئة الرئيسية <span class="text-danger">*</span></label>
                        <select name="product_category_id" class="form-control" required>
                            <option value="">اختر الفئة الرئيسية</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>اسم الفئة الفرعية <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>الوصف</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>الصورة</label>
                        <input type="file" name="image" class="form-control-file" accept="image/*">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="addIsActive" checked>
                        <label class="form-check-label" for="addIsActive">تفعيل الفئة الفرعية</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">تعديل الفئة الفرعية</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>الفئة الرئيسية <span class="text-danger">*</span></label>
                        <select name="product_category_id" id="editCategoryId" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>اسم الفئة الفرعية <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>الوصف</label>
                        <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>الصورة</label>
                        <input type="file" name="image" class="form-control-file" accept="image/*">
                        <small class="text-muted">اتركه فارغاً للاحتفاظ بالصورة الحالية</small>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="editIsActive">
                        <label class="form-check-label" for="editIsActive">تفعيل الفئة الفرعية</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تحديث</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const subcategories = @json($subcategories);
    
    function editSubcategory(id) {
        const subcategory = subcategories.find(s => s.id === id);
        if (!subcategory) return;
        
        document.getElementById('editName').value = subcategory.name;
        document.getElementById('editDescription').value = subcategory.description || '';
        document.getElementById('editCategoryId').value = subcategory.product_category_id;
        document.getElementById('editIsActive').checked = subcategory.is_active;
        document.getElementById('editForm').action = `{{ url('dashboard/products/subcategories') }}/${id}`;
        
        $('#editModal').modal('show');
    }
</script>
@endpush
@endsection

