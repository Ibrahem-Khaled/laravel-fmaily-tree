@extends('layouts.app')

@section('title', 'إدارة السلايدشو')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-images text-primary mr-2"></i>إدارة السلايدشو
            </h1>
            <p class="text-muted mb-0">قم بإدارة صور السلايدشو الرئيسية وإضافة تفاصيل لكل صورة</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus-circle mr-2"></i>إضافة شريحة جديدة
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

    <!-- Slideshow Images Grid -->
    @if($slideshowImages->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-sliders-h mr-2"></i>صور السلايدشو ({{ $slideshowImages->count() }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                            <i class="fas fa-save mr-1"></i>حفظ الترتيب
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="slideshow-grid" class="row">
                        @foreach($slideshowImages as $slideshowImage)
                            <div class="col-md-6 col-lg-4 mb-4" data-id="{{ $slideshowImage->id }}">
                                <div class="card h-100 shadow-sm border-0 slideshow-card {{ !$slideshowImage->is_active ? 'opacity-50' : '' }}" 
                                     style="transition: all 0.3s ease;">
                                    @if($slideshowImage->image_url)
                                        <div class="position-relative">
                                            <img src="{{ $slideshowImage->image_url }}" 
                                                 alt="{{ $slideshowImage->title ?? 'صورة' }}"
                                                 class="card-img-top" 
                                                 style="height: 200px; object-fit: cover; cursor: move;">
                                            <div class="position-absolute top-0 left-0 m-2">
                                                <span class="badge badge-{{ $slideshowImage->is_active ? 'success' : 'secondary' }} shadow-sm">
                                                    <i class="fas fa-{{ $slideshowImage->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                                    {{ $slideshowImage->is_active ? 'نشط' : 'معطل' }}
                                                </span>
                                            </div>
                                            <div class="position-absolute top-0 right-0 m-2">
                                                <i class="fas fa-grip-vertical text-white bg-dark rounded p-2" 
                                                   style="cursor: move; opacity: 0.8;" 
                                                   title="اسحب لإعادة الترتيب"></i>
                                            </div>
                                            <div class="position-absolute bottom-0 right-0 m-2">
                                                <span class="badge badge-dark shadow-sm">
                                                    #{{ $slideshowImage->order }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="card-body">
                                        <h6 class="card-title font-weight-bold mb-2">
                                            {{ $slideshowImage->title ?? 'بدون عنوان' }}
                                        </h6>
                                        
                                        @if($slideshowImage->description)
                                            <p class="card-text text-muted small mb-2" style="font-size: 0.85rem;">
                                                {{ Str::limit($slideshowImage->description, 100) }}
                                            </p>
                                        @endif
                                        
                                        @if($slideshowImage->link)
                                            <div class="mb-2">
                                                <a href="{{ $slideshowImage->link }}" target="_blank" 
                                                   class="text-info small">
                                                    <i class="fas fa-external-link-alt mr-1"></i>
                                                    {{ Str::limit($slideshowImage->link, 40) }}
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" 
                                                        class="btn btn-outline-info" 
                                                        onclick="editSlideshowImage({{ $slideshowImage->id }})" 
                                                        title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('dashboard.slideshow.toggle', $slideshowImage) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-{{ $slideshowImage->is_active ? 'warning' : 'success' }}"
                                                            title="{{ $slideshowImage->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                        <i class="fas fa-{{ $slideshowImage->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('dashboard.slideshow.remove', $slideshowImage) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الشريحة؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger" 
                                                            title="حذف">
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
                <h5 class="mt-3 text-muted">لا توجد صور في السلايدشو</h5>
                <p class="text-muted mb-4">ابدأ بإضافة صور جديدة للسلايدشو</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة شريحة جديدة
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Modal إضافة شريحة جديدة -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة شريحة جديدة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.slideshow.add') }}" method="POST" enctype="multipart/form-data" id="addForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="image" class="font-weight-bold">
                            الصورة <span class="text-danger">*</span>
                        </label>
                        <div class="custom-file">
                            <input type="file" name="image" id="image" 
                                   class="custom-file-input @error('image') is-invalid @enderror" 
                                   accept="image/*" required>
                            <label class="custom-file-label" for="image">اختر صورة...</label>
                        </div>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            الصيغ المدعومة: JPEG, PNG, JPG, GIF, WebP (حد أقصى 5MB)
                        </small>
                        <div class="mt-2" id="imagePreview"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="title" class="font-weight-bold">العنوان (اختياري)</label>
                        <input type="text" name="title" id="title" 
                               class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" 
                               placeholder="أدخل عنوان للشريحة..."
                               maxlength="255">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="font-weight-bold">الوصف (اختياري)</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="أدخل وصف للشريحة..."
                                  maxlength="1000">{{ old('description') }}</textarea>
                        <small class="form-text text-muted">
                            <span id="charCount">0</span> / 1000 حرف
                        </small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="link" class="font-weight-bold">رابط (اختياري)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-link"></i></span>
                            </div>
                            <input type="url" name="link" id="link" 
                                   class="form-control @error('link') is-invalid @enderror" 
                                   value="{{ old('link') }}" 
                                   placeholder="https://example.com" 
                                   maxlength="500">
                        </div>
                        @error('link')
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

<!-- Modal تعديل شريحة -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit mr-2"></i>تعديل الشريحة
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
                        <label for="edit_title" class="font-weight-bold">العنوان (اختياري)</label>
                        <input type="text" name="title" id="edit_title" 
                               class="form-control" 
                               placeholder="أدخل عنوان للشريحة..."
                               maxlength="255">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_description" class="font-weight-bold">الوصف (اختياري)</label>
                        <textarea name="description" id="edit_description" rows="3" 
                                  class="form-control" 
                                  placeholder="أدخل وصف للشريحة..."
                                  maxlength="1000"></textarea>
                        <small class="form-text text-muted">
                            <span id="editCharCount">0</span> / 1000 حرف
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_link" class="font-weight-bold">رابط (اختياري)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-link"></i></span>
                            </div>
                            <input type="url" name="link" id="edit_link" 
                                   class="form-control" 
                                   placeholder="https://example.com" 
                                   maxlength="500">
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
    const slideshowGrid = document.getElementById('slideshow-grid');
    if (slideshowGrid) {
        new Sortable(slideshowGrid, {
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
        const items = document.querySelectorAll('#slideshow-grid [data-id]');
        const orders = Array.from(items).map(item => item.dataset.id);

        fetch('{{ route("dashboard.slideshow.reorder") }}', {
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
    function editSlideshowImage(id) {
        fetch(`/dashboard/slideshow/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_title').value = data.title || '';
                document.getElementById('edit_description').value = data.description || '';
                document.getElementById('edit_link').value = data.link || '';
                document.getElementById('editForm').action = `/dashboard/slideshow/${id}/update`;
                updateCharCount('edit_description', 'editCharCount');
                $('#editModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء تحميل البيانات');
            });
    }

    // معاينة الصورة عند الاختيار
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').innerHTML = `
                    <div class="border rounded p-2 bg-light">
                        <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
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

    // تحديث تسمية ملفات الرفع
    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.files && this.files.length > 0) {
                label.textContent = this.files[0].name;
            } else {
                label.textContent = 'اختر ملف...';
            }
        });
    });

    // تهيئة عداد الأحرف
    updateCharCount('description', 'charCount');
    
    // إعادة تعيين النموذج عند إغلاق المودال
    $('#addModal').on('hidden.bs.modal', function() {
        document.getElementById('addForm').reset();
        document.getElementById('imagePreview').innerHTML = '';
        document.getElementById('charCount').textContent = '0';
    });
</script>

<style>
    .slideshow-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
    }
    
    .slideshow-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
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
