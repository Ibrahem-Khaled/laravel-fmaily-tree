@extends('layouts.app')

@section('title', 'إدارة الدورات')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-graduation-cap text-primary mr-2"></i>إدارة الدورات
            </h1>
            <p class="text-muted mb-0">قم بإدارة الدورات التعليمية وإضافة تفاصيل لكل دورة</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus-circle mr-2"></i>إضافة دورة جديدة
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
                                إجمالي الدورات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
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
                                دورات نشطة
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
                                دورات معطلة
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
                                إجمالي الطلاب
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_students'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    @if($courses->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-book mr-2"></i>الدورات ({{ $courses->count() }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                            <i class="fas fa-save mr-1"></i>حفظ الترتيب
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="courses-grid" class="row">
                        @foreach($courses as $course)
                            <div class="col-md-6 col-lg-4 mb-4" data-id="{{ $course->id }}">
                                <div class="card h-100 shadow-sm border-0 course-card {{ !$course->is_active ? 'opacity-50' : '' }}" 
                                     style="transition: all 0.3s ease; border-radius: 12px; overflow: hidden;">
                                    @if($course->image_url)
                                        <div class="position-relative" style="overflow: hidden;">
                                            <img src="{{ $course->image_url }}" 
                                                 alt="{{ $course->title }}"
                                                 class="card-img-top" 
                                                 style="height: 220px; object-fit: cover; cursor: move; transition: transform 0.3s ease;">
                                            <div class="position-absolute top-0 left-0 m-2">
                                                <span class="badge badge-{{ $course->is_active ? 'success' : 'secondary' }} shadow-sm px-3 py-2" style="font-size: 0.75rem;">
                                                    <i class="fas fa-{{ $course->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                                    {{ $course->is_active ? 'نشط' : 'معطل' }}
                                                </span>
                                            </div>
                                            <div class="position-absolute top-0 right-0 m-2">
                                                <i class="fas fa-grip-vertical text-white bg-dark rounded p-2 shadow" 
                                                   style="cursor: move; opacity: 0.9; font-size: 0.9rem;" 
                                                   title="اسحب لإعادة الترتيب"></i>
                                            </div>
                                            <div class="position-absolute bottom-0 right-0 m-2">
                                                <span class="badge badge-dark shadow-sm px-2 py-1" style="font-size: 0.75rem;">
                                                    #{{ $course->order }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-gradient-to-br from-green-400 to-emerald-600" style="height: 220px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book-open text-white" style="font-size: 3rem; opacity: 0.5;"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="card-body p-3">
                                        <h6 class="card-title font-weight-bold mb-2 text-dark" style="font-size: 1rem;">
                                            <i class="fas fa-graduation-cap text-primary mr-2"></i>
                                            {{ $course->title }}
                                        </h6>
                                        
                                        @if($course->description)
                                            <p class="card-text text-muted small mb-2" style="font-size: 0.85rem; line-height: 1.5;">
                                                {{ Str::limit($course->description, 100) }}
                                            </p>
                                        @endif
                                        
                                        <div class="mb-2">
                                            @if($course->instructor)
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-user text-primary mr-1"></i>{{ $course->instructor }}
                                                </small>
                                            @endif
                                            @if($course->duration)
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-clock text-info mr-1"></i>{{ $course->duration }}
                                                </small>
                                            @endif
                                            @if($course->students > 0)
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-users text-success mr-1"></i>{{ $course->students }} طالب
                                                </small>
                                            @endif
                                        </div>
                                        
                                        @if($course->link)
                                            <a href="{{ $course->link }}" target="_blank" class="text-info small" style="text-decoration: none;">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                {{ Str::limit($course->link, 40) }}
                                            </a>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div class="btn-group btn-group-sm shadow-sm">
                                                <button type="button" 
                                                        class="btn btn-outline-info border-0" 
                                                        onclick="editCourse({{ $course->id }})" 
                                                        title="تعديل"
                                                        style="border-radius: 6px 0 0 6px;">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('dashboard.courses.toggle', $course) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-{{ $course->is_active ? 'warning' : 'success' }} border-0"
                                                            title="{{ $course->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                        <i class="fas fa-{{ $course->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('dashboard.courses.destroy', $course) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الدورة؟')">
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
                <i class="fas fa-graduation-cap text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد دورات حالياً</h5>
                <p class="text-muted mb-4">ابدأ بإضافة دورات جديدة</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة دورة جديدة
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Modal إضافة دورة جديدة -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة دورة جديدة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.courses.store') }}" method="POST" enctype="multipart/form-data" id="addForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="title" class="font-weight-bold">
                            اسم الدورة <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="title" 
                               class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" 
                               placeholder="أدخل اسم الدورة..."
                               required maxlength="255">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="font-weight-bold">الوصف (اختياري)</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="أدخل وصف للدورة..."
                                  maxlength="1000">{{ old('description') }}</textarea>
                        <small class="form-text text-muted">
                            <span id="charCount">0</span> / 1000 حرف
                        </small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="image" class="font-weight-bold">صورة الدورة (اختياري)</label>
                        <div class="custom-file">
                            <input type="file" name="image" id="image" 
                                   class="custom-file-input @error('image') is-invalid @enderror" 
                                   accept="image/*">
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
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instructor" class="font-weight-bold">اسم المدرب (اختياري)</label>
                                <input type="text" name="instructor" id="instructor" 
                                       class="form-control @error('instructor') is-invalid @enderror" 
                                       value="{{ old('instructor') }}" 
                                       placeholder="اسم المدرب..."
                                       maxlength="255">
                                @error('instructor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration" class="font-weight-bold">المدة (اختياري)</label>
                                <input type="text" name="duration" id="duration" 
                                       class="form-control @error('duration') is-invalid @enderror" 
                                       value="{{ old('duration') }}" 
                                       placeholder="مثال: 3 أشهر..."
                                       maxlength="255">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="students" class="font-weight-bold">عدد الطلاب (اختياري)</label>
                                <input type="number" name="students" id="students" 
                                       class="form-control @error('students') is-invalid @enderror" 
                                       value="{{ old('students', 0) }}" 
                                       min="0"
                                       placeholder="0">
                                @error('students')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="link" class="font-weight-bold">رابط التسجيل (اختياري)</label>
                                <input type="url" name="link" id="link" 
                                       class="form-control @error('link') is-invalid @enderror" 
                                       value="{{ old('link') }}" 
                                       placeholder="https://example.com" 
                                       maxlength="500">
                                @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" 
                                   class="form-check-input" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                تفعيل الدورة
                            </label>
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

<!-- Modal تعديل دورة -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit mr-2"></i>تعديل الدورة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="edit_title" class="font-weight-bold">
                            اسم الدورة <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="edit_title" 
                               class="form-control" 
                               placeholder="أدخل اسم الدورة..."
                               required maxlength="255">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_description" class="font-weight-bold">الوصف (اختياري)</label>
                        <textarea name="description" id="edit_description" rows="3" 
                                  class="form-control" 
                                  placeholder="أدخل وصف للدورة..."
                                  maxlength="1000"></textarea>
                        <small class="form-text text-muted">
                            <span id="editCharCount">0</span> / 1000 حرف
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_image" class="font-weight-bold">صورة الدورة</label>
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
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_instructor" class="font-weight-bold">اسم المدرب (اختياري)</label>
                                <input type="text" name="instructor" id="edit_instructor" 
                                       class="form-control" 
                                       placeholder="اسم المدرب..."
                                       maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_duration" class="font-weight-bold">المدة (اختياري)</label>
                                <input type="text" name="duration" id="edit_duration" 
                                       class="form-control" 
                                       placeholder="مثال: 3 أشهر..."
                                       maxlength="255">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_students" class="font-weight-bold">عدد الطلاب (اختياري)</label>
                                <input type="number" name="students" id="edit_students" 
                                       class="form-control" 
                                       min="0"
                                       placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_link" class="font-weight-bold">رابط التسجيل (اختياري)</label>
                                <input type="url" name="link" id="edit_link" 
                                       class="form-control" 
                                       placeholder="https://example.com" 
                                       maxlength="500">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="edit_is_active" 
                                   class="form-check-input" value="1">
                            <label class="form-check-label" for="edit_is_active">
                                تفعيل الدورة
                            </label>
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
    const coursesGrid = document.getElementById('courses-grid');
    if (coursesGrid) {
        new Sortable(coursesGrid, {
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
        const items = document.querySelectorAll('#courses-grid [data-id]');
        const orders = Array.from(items).map(item => item.dataset.id);

        fetch('{{ route("dashboard.courses.reorder") }}', {
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

    // تعديل دورة
    function editCourse(id) {
        fetch(`/dashboard/courses/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_title').value = data.title || '';
                document.getElementById('edit_description').value = data.description || '';
                document.getElementById('edit_instructor').value = data.instructor || '';
                document.getElementById('edit_duration').value = data.duration || '';
                document.getElementById('edit_students').value = data.students || 0;
                document.getElementById('edit_link').value = data.link || '';
                document.getElementById('edit_is_active').checked = data.is_active;
                document.getElementById('editForm').action = `/dashboard/courses/${id}/update`;
                updateCharCount('edit_description', 'editCharCount');
                
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
    updateCharCount('description', 'charCount');
    
    // إعادة تعيين النموذج عند إغلاق المودال
    $('#addModal').on('hidden.bs.modal', function() {
        document.getElementById('addForm').reset();
        document.getElementById('imagePreview').innerHTML = '';
        document.getElementById('charCount').textContent = '0';
    });
</script>

<style>
    .course-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
    }
    
    .course-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.2) !important;
    }
    
    .course-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .course-card .btn-group-sm .btn {
        transition: all 0.2s ease;
    }
    
    .course-card .btn-group-sm .btn:hover {
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

