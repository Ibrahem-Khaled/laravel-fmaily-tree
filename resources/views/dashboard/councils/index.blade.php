@extends('layouts.app')

@section('title', 'إدارة مجالس العائلة')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-building text-primary mr-2"></i>إدارة مجالس العائلة
            </h1>
            <p class="text-muted mb-0">قم بإدارة مجالس العائلة المعروضة في الموقع</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus-circle mr-2"></i>إضافة مجلس جديد
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left: 0.25rem solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي المجالس
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-left: 0.25rem solid #1cc88a !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                مجالس نشطة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="border-left: 0.25rem solid #f6c23e !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                مجالس غير نشطة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-left: 0.25rem solid #36b9cc !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                مجالس بصور
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['with_images'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-images fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Councils Grid -->
    @if($councils->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-building mr-2"></i>المجالس ({{ $councils->count() }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                            <i class="fas fa-save mr-1"></i>حفظ الترتيب
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="councils-grid" class="row">
                        @foreach($councils as $council)
                            <div class="col-md-6 col-lg-4 mb-4" data-id="{{ $council->id }}">
                                <div class="card h-100 shadow-sm border-0 council-card" 
                                     style="transition: all 0.3s ease; border-radius: 12px; overflow: hidden;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title font-weight-bold mb-0 text-dark" style="font-size: 1rem;">
                                                <i class="fas fa-building text-primary mr-2"></i>
                                                {{ $council->name }}
                                            </h6>
                                            <div>
                                                <i class="fas fa-grip-vertical text-muted" 
                                                   style="cursor: move; opacity: 0.7; font-size: 0.9rem;" 
                                                   title="اسحب لإعادة الترتيب"></i>
                                            </div>
                                        </div>
                                        
                                        @if($council->description)
                                            <p class="card-text text-muted small mb-2" style="font-size: 0.85rem; line-height: 1.5;">
                                                {{ Str::limit($council->description, 100) }}
                                            </p>
                                        @endif

                                        @if($council->address)
                                            <p class="card-text text-muted small mb-2" style="font-size: 0.8rem;">
                                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                {{ Str::limit($council->address, 80) }}
                                            </p>
                                        @endif

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="badge badge-{{ $council->is_active ? 'success' : 'secondary' }} shadow-sm px-2 py-1" style="font-size: 0.75rem;">
                                                    {{ $council->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                                <span class="badge badge-dark shadow-sm px-2 py-1 ml-1" style="font-size: 0.75rem;">
                                                    #{{ $council->display_order }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="badge badge-info shadow-sm px-2 py-1" style="font-size: 0.75rem;">
                                                    <i class="fas fa-images mr-1"></i>{{ $council->images->count() }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div class="btn-group btn-group-sm shadow-sm">
                                                <a href="{{ route('dashboard.councils.manage', $council) }}"
                                                   class="btn btn-outline-primary border-0"
                                                   title="إدارة التفاصيل"
                                                   style="border-radius: 6px 0 0 6px;">
                                                    <i class="fas fa-folder-open"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-info border-0" 
                                                        onclick="editCouncil({{ $council->id }})" 
                                                        title="تعديل"
                                                        style="border-radius: 0;">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('dashboard.councils.destroy', $council) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المجلس؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger border-0" 
                                                            title="حذف"
                                                            style="border-radius: 0 6px 6px 0;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-building text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد مجالس حالياً</h5>
                <p class="text-muted mb-4">ابدأ بإضافة مجلس جديد</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة مجلس جديد
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Modal إضافة مجلس جديد -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة مجلس جديد
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.councils.store') }}" method="POST" id="addForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="name" class="font-weight-bold">
                            اسم المكان <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="أدخل اسم المكان..."
                               required maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="font-weight-bold">وصف المكان (اختياري)</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="أدخل وصف للمكان..."
                                  maxlength="10000">{{ old('description') }}</textarea>
                        <small class="form-text text-muted">
                            <span id="charCount">0</span> / 10000 حرف
                        </small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address" class="font-weight-bold">عنوان المكان (اختياري)</label>
                        <input type="text" name="address" id="address" 
                               class="form-control @error('address') is-invalid @enderror" 
                               value="{{ old('address') }}" 
                               placeholder="أدخل عنوان المكان..."
                               maxlength="500">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="google_map_url" class="font-weight-bold">رابط جوجل ماب (اختياري)</label>
                        <input type="url" name="google_map_url" id="google_map_url" 
                               class="form-control @error('google_map_url') is-invalid @enderror" 
                               value="{{ old('google_map_url') }}" 
                               placeholder="https://maps.google.com/..."
                               maxlength="1000">
                        @error('google_map_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            رابط جوجل ماب للمكان
                        </small>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="working_days_from" class="font-weight-bold">أيام العمل - من (اختياري)</label>
                            <input type="text" name="working_days_from" id="working_days_from" 
                                   class="form-control @error('working_days_from') is-invalid @enderror" 
                                   value="{{ old('working_days_from') }}" 
                                   placeholder="مثال: السبت"
                                   maxlength="50">
                            @error('working_days_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                مثال: السبت، الأحد، إلخ
                            </small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="working_days_to" class="font-weight-bold">أيام العمل - إلى (اختياري)</label>
                            <input type="text" name="working_days_to" id="working_days_to" 
                                   class="form-control @error('working_days_to') is-invalid @enderror" 
                                   value="{{ old('working_days_to') }}" 
                                   placeholder="مثال: الخميس"
                                   maxlength="50">
                            @error('working_days_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                مثال: الخميس، الجمعة، إلخ
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                            <label class="custom-control-label" for="is_active">نشط</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i>إضافة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal تعديل مجلس -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit mr-2"></i>تعديل المجلس
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="edit_name" class="font-weight-bold">
                            اسم المكان <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="edit_name" 
                               class="form-control" 
                               placeholder="أدخل اسم المكان..."
                               required maxlength="255">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_description" class="font-weight-bold">وصف المكان (اختياري)</label>
                        <textarea name="description" id="edit_description" rows="3" 
                                  class="form-control" 
                                  placeholder="أدخل وصف للمكان..."
                                  maxlength="10000"></textarea>
                        <small class="form-text text-muted">
                            <span id="editCharCount">0</span> / 10000 حرف
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="edit_address" class="font-weight-bold">عنوان المكان (اختياري)</label>
                        <input type="text" name="address" id="edit_address" 
                               class="form-control" 
                               placeholder="أدخل عنوان المكان..."
                               maxlength="500">
                    </div>

                    <div class="form-group">
                        <label for="edit_google_map_url" class="font-weight-bold">رابط جوجل ماب (اختياري)</label>
                        <input type="url" name="google_map_url" id="edit_google_map_url" 
                               class="form-control" 
                               placeholder="https://maps.google.com/..."
                               maxlength="1000">
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            رابط جوجل ماب للمكان
                        </small>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit_working_days_from" class="font-weight-bold">أيام العمل - من (اختياري)</label>
                            <input type="text" name="working_days_from" id="edit_working_days_from" 
                                   class="form-control" 
                                   placeholder="مثال: السبت"
                                   maxlength="50">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                مثال: السبت، الأحد، إلخ
                            </small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_working_days_to" class="font-weight-bold">أيام العمل - إلى (اختياري)</label>
                            <input type="text" name="working_days_to" id="edit_working_days_to" 
                                   class="form-control" 
                                   placeholder="مثال: الخميس"
                                   maxlength="50">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                مثال: الخميس، الجمعة، إلخ
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="edit_is_active" name="is_active">
                            <label class="custom-control-label" for="edit_is_active">نشط</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save mr-1"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sortable JS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    // تفعيل السحب والإفلات
    const councilsGrid = document.getElementById('councils-grid');
    if (councilsGrid) {
        new Sortable(councilsGrid, {
            handle: '.fa-grip-vertical',
            animation: 300,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                document.getElementById('saveOrderBtn').style.display = 'inline-block';
            }
        });
    }

    // حفظ الترتيب
    function saveOrder() {
        const items = document.querySelectorAll('#councils-grid [data-id]');
        const orders = Array.from(items).map(item => item.dataset.id);

        fetch('{{ route("dashboard.councils.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ orders: orders })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم حفظ الترتيب بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ أثناء حفظ الترتيب');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حفظ الترتيب');
        });
    }

    // تعديل مجلس
    function editCouncil(id) {
        fetch(`/dashboard/councils/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_name').value = data.name || '';
                document.getElementById('edit_description').value = data.description || '';
                document.getElementById('edit_address').value = data.address || '';
                document.getElementById('edit_google_map_url').value = data.google_map_url || '';
                document.getElementById('edit_working_days_from').value = data.working_days_from || '';
                document.getElementById('edit_working_days_to').value = data.working_days_to || '';
                document.getElementById('edit_is_active').checked = data.is_active || false;
                document.getElementById('editForm').action = `/dashboard/councils/${id}/update`;
                updateCharCount('edit_description', 'editCharCount');
                
                $('#editModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء تحميل البيانات');
            });
    }

    // عداد الأحرف
    function updateCharCount(textareaId, counterId) {
        const textarea = document.getElementById(textareaId);
        const counter = document.getElementById(counterId);
        if (textarea && counter) {
            textarea.addEventListener('input', function() {
                counter.textContent = this.value.length;
            });
            counter.textContent = textarea.value.length;
        }
    }

    // تهيئة عداد الأحرف
    updateCharCount('description', 'charCount');
    
    // إعادة تعيين النموذج عند إغلاق المودال
    $('#addModal').on('hidden.bs.modal', function() {
        document.getElementById('addForm').reset();
        document.getElementById('charCount').textContent = '0';
    });
</script>

<style>
    .council-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
    }
    
    .council-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.2) !important;
    }
    
    .council-card .btn-group-sm .btn {
        transition: all 0.2s ease;
    }
    
    .council-card .btn-group-sm .btn:hover {
        transform: scale(1.1);
        z-index: 10;
    }
    
    .sortable-ghost {
        opacity: 0.4;
        background: #e9ecef;
    }
    
    .sortable-chosen {
        cursor: grabbing;
    }
    
    .modal-content {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .modal-header {
        border-radius: 10px 10px 0 0;
    }
</style>
@endsection

