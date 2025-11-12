@extends('layouts.app')

@section('title', 'إدارة صور الصفحة الرئيسية')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-images text-primary mr-2"></i>إدارة صور الصفحة الرئيسية
            </h1>
            <p class="text-muted mb-0">قم بإدارة صور قسم "الصور" في الصفحة الرئيسية</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus-circle mr-2"></i>إضافة صور
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
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left: 0.25rem solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الصور
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-images fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-left: 0.25rem solid #1cc88a !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                صور نشطة
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

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="border-left: 0.25rem solid #f6c23e !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                صور معطلة
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
    </div>

    <!-- Gallery Images Grid -->
    @if($galleryImages->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-images mr-2"></i>صور المعرض ({{ $galleryImages->count() }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                            <i class="fas fa-save mr-1"></i>حفظ الترتيب
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="gallery-grid" class="row">
                        @foreach($galleryImages as $galleryImage)
                            <div class="col-md-6 col-lg-4 mb-4" data-id="{{ $galleryImage->id }}">
                                <div class="card h-100 shadow-sm border-0 gallery-card {{ !$galleryImage->is_active ? 'opacity-50' : '' }}" 
                                     style="transition: all 0.3s ease; border-radius: 12px; overflow: hidden;">
                                    @if($galleryImage->image_url)
                                        <div class="position-relative" style="overflow: hidden;">
                                            <img src="{{ $galleryImage->image_url }}" 
                                                 alt="{{ $galleryImage->name ?? 'صورة' }}"
                                                 class="card-img-top" 
                                                 style="height: 220px; object-fit: cover; cursor: move; transition: transform 0.3s ease;">
                                            <div class="position-absolute top-0 left-0 m-2">
                                                <span class="badge badge-{{ $galleryImage->is_active ? 'success' : 'secondary' }} shadow-sm px-3 py-2" style="font-size: 0.75rem;">
                                                    <i class="fas fa-{{ $galleryImage->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                                    {{ $galleryImage->is_active ? 'نشط' : 'معطل' }}
                                                </span>
                                            </div>
                                            <div class="position-absolute top-0 right-0 m-2">
                                                <i class="fas fa-grip-vertical text-white bg-dark rounded p-2 shadow" 
                                                   style="cursor: move; opacity: 0.9; font-size: 0.9rem;" 
                                                   title="اسحب لإعادة الترتيب"></i>
                                            </div>
                                            <div class="position-absolute bottom-0 right-0 m-2">
                                                <span class="badge badge-dark shadow-sm px-2 py-1" style="font-size: 0.75rem;">
                                                    #{{ $galleryImage->order }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="card-body p-3">
                                        <h6 class="card-title font-weight-bold mb-2 text-dark" style="font-size: 1rem;">
                                            <i class="fas fa-image text-primary mr-2"></i>
                                            <span data-name="{{ $galleryImage->name ?? 'بدون اسم' }}">{{ $galleryImage->name ?? 'بدون اسم' }}</span>
                                        </h6>
                                        
                                        @if($galleryImage->category)
                                            <p class="card-text text-muted small mb-2" style="font-size: 0.85rem;" data-category-id="{{ $galleryImage->category_id }}">
                                                <i class="fas fa-folder text-info mr-1"></i>
                                                {{ $galleryImage->category->name }}
                                            </p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div class="btn-group btn-group-sm shadow-sm">
                                                <button type="button" 
                                                        class="btn btn-outline-info border-0" 
                                                        onclick="editGalleryImage({{ $galleryImage->id }})" 
                                                        title="تعديل"
                                                        style="border-radius: 6px 0 0 6px;">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('dashboard.home-gallery.toggle', $galleryImage) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-{{ $galleryImage->is_active ? 'warning' : 'success' }} border-0"
                                                            title="{{ $galleryImage->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                        <i class="fas fa-{{ $galleryImage->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('dashboard.home-gallery.remove', $galleryImage) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
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
                <i class="fas fa-images text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد صور في المعرض</h5>
                <p class="text-muted mb-4">ابدأ بإضافة صور جديدة للمعرض</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة صور
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Modal إضافة صورة جديدة -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة صور جديدة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.home-gallery.add') }}" method="POST" enctype="multipart/form-data" id="addForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="images" class="font-weight-bold">
                            الصور <span class="text-danger">*</span>
                        </label>
                        <div class="custom-file">
                            <input type="file" name="images[]" id="images" 
                                   class="custom-file-input @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" 
                                   accept="image/*" multiple required>
                            <label class="custom-file-label" for="images">اختر صورة أو أكثر...</label>
                        </div>
                        @error('images')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            يمكنك اختيار عدة صور في نفس الوقت. الصيغ المدعومة: JPEG, PNG, JPG, GIF, WebP (حد أقصى 5MB لكل صورة)
                        </small>
                        <div class="mt-3" id="imagePreview">
                            <div class="row" id="previewContainer"></div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <span id="selectedCount">0</span> صورة محددة
                            </small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="name" class="font-weight-bold">اسم الصورة (اختياري)</label>
                        <input type="text" name="name" id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="أدخل اسم للصورة..."
                               maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id" class="font-weight-bold">الفئة (اختياري)</label>
                        <select name="category_id" id="category_id" 
                                class="form-control @error('category_id') is-invalid @enderror">
                            <option value="">-- اختر فئة --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

<!-- Modal تعديل صورة -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit mr-2"></i>تعديل الصورة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="edit_image" class="font-weight-bold">الصورة</label>
                        <div class="custom-file">
                            <input type="file" name="image" id="edit_image" 
                                   class="custom-file-input" accept="image/*">
                            <label class="custom-file-label" for="edit_image">اختر صورة جديدة (اختياري)...</label>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            اتركه فارغاً للحفاظ على الصورة الحالية
                        </small>
                        <div class="mt-2" id="editImagePreview"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_name" class="font-weight-bold">اسم الصورة (اختياري)</label>
                        <input type="text" name="name" id="edit_name" 
                               class="form-control" 
                               placeholder="أدخل اسم للصورة..."
                               maxlength="255">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_category_id" class="font-weight-bold">الفئة (اختياري)</label>
                        <select name="category_id" id="edit_category_id" class="form-control">
                            <option value="">-- اختر فئة --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
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
    const galleryGrid = document.getElementById('gallery-grid');
    if (galleryGrid) {
        new Sortable(galleryGrid, {
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
        const items = document.querySelectorAll('#gallery-grid [data-id]');
        const orders = Array.from(items).map(item => item.dataset.id);

        fetch('{{ route("dashboard.home-gallery.reorder") }}', {
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

    // تعديل صورة
    function editGalleryImage(id) {
        // جلب بيانات الصورة من DOM
        const card = document.querySelector(`[data-id="${id}"]`);
        if (!card) return;
        
        const imageUrl = card.querySelector('img')?.src;
        const nameElement = card.querySelector('[data-name]');
        const name = nameElement ? nameElement.dataset.name : '';
        const categoryElement = card.querySelector('[data-category-id]');
        const categoryId = categoryElement ? categoryElement.dataset.categoryId : '';
        
        // ملء النموذج
        document.getElementById('edit_name').value = name || '';
        document.getElementById('edit_category_id').value = categoryId || '';
        document.getElementById('editForm').action = `/dashboard/home-gallery/${id}/update`;
        
        // عرض الصورة الحالية
        if (imageUrl) {
            document.getElementById('editImagePreview').innerHTML = `
                <div class="border rounded p-2 bg-light">
                    <img src="${imageUrl}" class="img-fluid rounded" style="max-height: 200px;">
                    <small class="text-muted d-block mt-1">الصورة الحالية</small>
                </div>
            `;
        }
        
        $('#editModal').modal('show');
    }

    // معاينة الصور عند الاختيار (دعم رفع متعدد)
    document.getElementById('images').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const previewContainer = document.getElementById('previewContainer');
        const selectedCount = document.getElementById('selectedCount');
        
        // تحديث العداد
        selectedCount.textContent = files.length;
        
        // مسح المعاينة السابقة
        previewContainer.innerHTML = '';
        
        if (files.length === 0) {
            return;
        }
        
        // عرض معاينة لكل صورة
        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 col-sm-6 mb-3';
                    col.innerHTML = `
                        <div class="border rounded p-2 bg-light position-relative">
                            <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                            <small class="text-muted d-block mt-1 text-center" style="font-size: 0.75rem;">
                                ${file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name}
                            </small>
                            <small class="text-muted d-block text-center" style="font-size: 0.7rem;">
                                ${(file.size / 1024 / 1024).toFixed(2)} MB
                            </small>
                        </div>
                    `;
                    previewContainer.appendChild(col);
                };
                reader.readAsDataURL(file);
            }
        });
    });

    document.getElementById('edit_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('editImagePreview').innerHTML = `
                    <div class="border rounded p-2 bg-light">
                        <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">
                        <small class="text-muted d-block mt-1">معاينة الصورة الجديدة</small>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    });

    // تحديث تسمية ملفات الرفع
    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.files && this.files.length > 0) {
                if (this.multiple && this.files.length > 1) {
                    label.textContent = `${this.files.length} ملفات محددة`;
                } else {
                    label.textContent = this.files[0].name;
                }
            } else {
                label.textContent = input.id === 'images' ? 'اختر صورة أو أكثر...' : 'اختر ملف...';
            }
        });
    });
    
    // إعادة تعيين النموذج عند إغلاق المودال
    $('#addModal').on('hidden.bs.modal', function() {
        document.getElementById('addForm').reset();
        document.getElementById('previewContainer').innerHTML = '';
        document.getElementById('selectedCount').textContent = '0';
    });
    
    $('#editModal').on('hidden.bs.modal', function() {
        document.getElementById('editForm').reset();
        document.getElementById('editImagePreview').innerHTML = '';
    });
</script>

<style>
    .gallery-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
    }
    
    .gallery-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.2) !important;
    }
    
    .gallery-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .gallery-card .btn-group-sm .btn {
        transition: all 0.2s ease;
    }
    
    .gallery-card .btn-group-sm .btn:hover {
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
    
    .card-img-top {
        border-radius: 10px 10px 0 0;
    }
    
    .modal-content {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .modal-header {
        border-radius: 10px 10px 0 0;
    }
    
    .custom-file-label::after {
        content: "تصفح";
        right: 0;
        left: auto;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection

