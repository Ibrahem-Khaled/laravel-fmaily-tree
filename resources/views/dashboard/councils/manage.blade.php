@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')

@section('title', 'إدارة مجلس ' . $council->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('dashboard.councils.index') }}" class="btn btn-light border shadow-sm">
                <i class="fas fa-arrow-right mr-2"></i>عودة للمجالس
            </a>
        </div>
        <div>
            <h1 class="h4 mb-0 text-gray-800">
                <i class="fas fa-building text-primary mr-2"></i>إدارة مجلس: {{ $council->name }}
            </h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>حدثت أخطاء، يرجى التحقق من المدخلات.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>بيانات المجلس الأساسية
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.councils.update', $council) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">
                                اسم المكان <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $council->name) }}"
                                   required
                                   maxlength="255">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="font-weight-bold">وصف المكان</label>
                            <textarea name="description"
                                      id="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      maxlength="10000"
                                      placeholder="اكتب وصفاً للمكان...">{{ old('description', $council->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address" class="font-weight-bold">عنوان المكان</label>
                            <input type="text"
                                   name="address"
                                   id="address"
                                   class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address', $council->address) }}"
                                   maxlength="500"
                                   placeholder="أدخل عنوان المكان...">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="google_map_url" class="font-weight-bold">رابط جوجل ماب</label>
                            <input type="url"
                                   name="google_map_url"
                                   id="google_map_url"
                                   class="form-control @error('google_map_url') is-invalid @enderror"
                                   value="{{ old('google_map_url', $council->google_map_url) }}"
                                   maxlength="1000"
                                   placeholder="https://maps.google.com/...">
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
                                <input type="text"
                                       name="working_days_from"
                                       id="working_days_from"
                                       class="form-control @error('working_days_from') is-invalid @enderror"
                                       value="{{ old('working_days_from', $council->working_days_from) }}"
                                       maxlength="50"
                                       placeholder="مثال: السبت">
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
                                <input type="text"
                                       name="working_days_to"
                                       id="working_days_to"
                                       class="form-control @error('working_days_to') is-invalid @enderror"
                                       value="{{ old('working_days_to', $council->working_days_to) }}"
                                       maxlength="50"
                                       placeholder="مثال: الخميس">
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
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ $council->is_active ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">نشط</label>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="fas fa-save mr-1"></i>حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>معلومات المجلس
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-0">
                        تم إنشاء المجلس بتاريخ {{ $council->created_at->format('Y-m-d') }}<br>
                        آخر تحديث {{ $council->updated_at->diffForHumans() }}
                    </p>
                    <hr>
                    <p class="mb-2">
                        <strong>الحالة:</strong>
                        <span class="badge badge-{{ $council->is_active ? 'success' : 'secondary' }}">
                            {{ $council->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </p>
                    <p class="mb-2">
                        <strong>عدد الصور:</strong>
                        <span class="badge badge-info">{{ $council->images->count() }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>ترتيب العرض:</strong>
                        <span class="badge badge-dark">#{{ $council->display_order }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-images mr-2"></i>صور المجلس
            </h6>
            <button class="btn btn-sm btn-outline-primary" data-toggle="collapse" data-target="#addImageForm" aria-expanded="false">
                <i class="fas fa-plus-circle mr-1"></i>إضافة صورة جديدة
            </button>
        </div>
        <div class="collapse" id="addImageForm">
            <div class="card-body">
                <form action="{{ route('dashboard.councils.images.store', $council) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">الصورة <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" required accept="image/*">
                                <label class="custom-file-label">اختر صورة...</label>
                            </div>
                            <small class="form-text text-muted">
                                الصيغ المدعومة: JPEG, PNG, JPG, GIF, WebP (حد أقصى 5MB)
                            </small>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">وصف الصورة (اختياري)</label>
                            <textarea name="description" class="form-control" rows="2" maxlength="500" placeholder="وصف للصورة"></textarea>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload mr-1"></i>رفع الصورة
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($council->images->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="fas fa-images fa-2x mb-2"></i>
                    <p class="mb-0">لا توجد صور للمجلس حالياً</p>
                </div>
            @else
                <div class="row" id="imagesSortable" data-reorder-url="{{ route('dashboard.councils.images.reorder', $council) }}">
                    @foreach($council->images as $image)
                        <div class="col-md-4 mb-4" data-id="{{ $image->id }}">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="position-relative" style="overflow: hidden;">
                                    <img src="{{ $image->image_url }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $image->description ?? 'صورة' }}">
                                    <div class="position-absolute top-0 right-0 m-2">
                                        <i class="fas fa-grip-vertical text-white bg-dark rounded p-2 shadow" style="cursor: move;" title="اسحب لإعادة الترتيب"></i>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    @if($image->description)
                                        <p class="text-muted small mb-2">{{ Str::limit($image->description, 80) }}</p>
                                    @endif
                                    <form action="{{ route('dashboard.councils.images.destroy', [$council, $image]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash mr-1"></i>حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Sortable JS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    // تفعيل السحب والإفلات للصور
    const imagesSortable = document.getElementById('imagesSortable');
    if (imagesSortable) {
        new Sortable(imagesSortable, {
            handle: '.fa-grip-vertical',
            animation: 300,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                const items = document.querySelectorAll('#imagesSortable [data-id]');
                const orders = Array.from(items).map(item => item.dataset.id);
                const reorderUrl = imagesSortable.dataset.reorderUrl;

                fetch(reorderUrl, {
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
                        // يمكن إضافة إشعار هنا
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
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
</script>

<style>
    .sortable-ghost {
        opacity: 0.4;
        background: #e9ecef;
    }
    
    .sortable-chosen {
        cursor: grabbing;
    }
</style>
@endsection

