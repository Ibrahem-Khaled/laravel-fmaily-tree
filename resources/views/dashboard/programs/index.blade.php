@extends('layouts.app')

@section('title', 'إدارة البرامج')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-tv text-primary mr-2"></i>إدارة برامج السريع
            </h1>
            <p class="text-muted mb-0">قم بإدارة البرامج المعروضة في الصفحة الرئيسية</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus-circle mr-2"></i>إضافة برنامج جديد
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

    <!-- Programs Grid -->
    @if($programs->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-tv mr-2"></i>البرامج ({{ $programs->count() }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                            <i class="fas fa-save mr-1"></i>حفظ الترتيب
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="programs-grid" class="row">
                        @foreach($programs as $program)
                            <div class="col-md-6 col-lg-4 mb-4" data-id="{{ $program->id }}">
                                <div class="card h-100 shadow-sm border-0 program-card" 
                                     style="transition: all 0.3s ease; border-radius: 10px;">
                                    @if($program->path)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $program->path) }}" 
                                                 alt="{{ $program->program_title ?? $program->name }}"
                                                 class="card-img-top" 
                                                 style="height: 200px; object-fit: cover; cursor: move;">
                                            <div class="position-absolute top-0 right-0 m-2">
                                                <i class="fas fa-grip-vertical text-white bg-dark rounded p-2" 
                                                   style="cursor: move; opacity: 0.8;" 
                                                   title="اسحب لإعادة الترتيب"></i>
                                            </div>
                                            <div class="position-absolute bottom-0 right-0 m-2">
                                                <span class="badge badge-dark shadow-sm">
                                                    #{{ $program->program_order }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-gradient-to-br from-green-400 to-emerald-600" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-tv text-white" style="font-size: 3rem; opacity: 0.5;"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="card-body">
                                        <h6 class="card-title font-weight-bold mb-2">
                                            {{ $program->program_title ?? $program->name ?? 'برنامج' }}
                                        </h6>
                                        
                                        @if($program->program_description)
                                            <p class="card-text text-muted small mb-2" style="font-size: 0.85rem;">
                                                {{ Str::limit($program->program_description, 100) }}
                                            </p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" 
                                                        class="btn btn-outline-info" 
                                                        onclick="editProgram({{ $program->id }})" 
                                                        title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('dashboard.programs.destroy', $program) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا البرنامج؟')">
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
                <i class="fas fa-tv text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد برامج حالياً</h5>
                <p class="text-muted mb-4">ابدأ بإضافة برامج جديدة</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة برنامج جديد
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Modal إضافة برنامج جديد -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة برنامج جديد
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.programs.store') }}" method="POST" enctype="multipart/form-data" id="addForm">
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
                        <label for="program_title" class="font-weight-bold">
                            عنوان البرنامج <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="program_title" id="program_title" 
                               class="form-control @error('program_title') is-invalid @enderror" 
                               value="{{ old('program_title') }}" 
                               placeholder="أدخل عنوان البرنامج..."
                               required maxlength="255">
                        @error('program_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="program_description" class="font-weight-bold">وصف البرنامج (اختياري)</label>
                        <textarea name="program_description" id="program_description" rows="3" 
                                  class="form-control @error('program_description') is-invalid @enderror" 
                                  placeholder="أدخل وصف للبرنامج..."
                                  maxlength="1000">{{ old('program_description') }}</textarea>
                        <small class="form-text text-muted">
                            <span id="charCount">0</span> / 1000 حرف
                        </small>
                        @error('program_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="name" class="font-weight-bold">اسم الصورة (اختياري)</label>
                        <input type="text" name="name" id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="اسم الصورة..."
                               maxlength="255">
                        @error('name')
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

<!-- Modal تعديل برنامج -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit mr-2"></i>تعديل البرنامج
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
                        <label for="edit_program_title" class="font-weight-bold">
                            عنوان البرنامج <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="program_title" id="edit_program_title" 
                               class="form-control" 
                               placeholder="أدخل عنوان البرنامج..."
                               required maxlength="255">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_program_description" class="font-weight-bold">وصف البرنامج (اختياري)</label>
                        <textarea name="program_description" id="edit_program_description" rows="3" 
                                  class="form-control" 
                                  placeholder="أدخل وصف للبرنامج..."
                                  maxlength="1000"></textarea>
                        <small class="form-text text-muted">
                            <span id="editCharCount">0</span> / 1000 حرف
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_name" class="font-weight-bold">اسم الصورة (اختياري)</label>
                        <input type="text" name="name" id="edit_name" 
                               class="form-control" 
                               placeholder="اسم الصورة..."
                               maxlength="255">
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
    const programsGrid = document.getElementById('programs-grid');
    if (programsGrid) {
        new Sortable(programsGrid, {
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
        const items = document.querySelectorAll('#programs-grid [data-id]');
        const orders = Array.from(items).map(item => item.dataset.id);

        fetch('{{ route("dashboard.programs.reorder") }}', {
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

    // تعديل برنامج
    function editProgram(id) {
        fetch(`/dashboard/programs/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_program_title').value = data.program_title || '';
                document.getElementById('edit_program_description').value = data.program_description || '';
                document.getElementById('edit_name').value = data.name || '';
                document.getElementById('editForm').action = `/dashboard/programs/${id}/update`;
                updateCharCount('edit_program_description', 'editCharCount');
                
                // عرض الصورة الحالية
                if (data.image_url) {
                    document.getElementById('editImagePreview').innerHTML = `
                        <div class="border rounded p-2 bg-light">
                            <img src="${data.image_url}" class="img-fluid rounded" style="max-height: 200px;">
                            <small class="text-muted d-block mt-1">الصورة الحالية</small>
                        </div>
                    `;
                }
                
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
    updateCharCount('program_description', 'charCount');
    
    // إعادة تعيين النموذج عند إغلاق المودال
    $('#addModal').on('hidden.bs.modal', function() {
        document.getElementById('addForm').reset();
        document.getElementById('imagePreview').innerHTML = '';
        document.getElementById('charCount').textContent = '0';
    });
</script>

<style>
    .program-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
    }
    
    .program-card:hover {
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




