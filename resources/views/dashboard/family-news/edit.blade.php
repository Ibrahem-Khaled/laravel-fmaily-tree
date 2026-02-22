@extends('layouts.app')

@section('title', 'تعديل خبر')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-edit text-primary mr-2"></i>تعديل خبر
            </h1>
            <p class="text-muted mb-0">تعديل: {{ $familyNews->title }}</p>
        </div>
        <a href="{{ route('dashboard.family-news.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-edit mr-2"></i>معلومات الخبر
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.family-news.update', $familyNews) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title" class="font-weight-bold">
                                عنوان الخبر <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $familyNews->title) }}"
                                   placeholder="أدخل عنوان الخبر..."
                                   required maxlength="255">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="summary" class="font-weight-bold">ملخص الخبر (اختياري)</label>
                            <textarea name="summary" id="summary" rows="3"
                                      class="form-control @error('summary') is-invalid @enderror"
                                      placeholder="أدخل ملخص قصير للخبر..."
                                      maxlength="500">{{ old('summary', $familyNews->summary) }}</textarea>
                            <small class="form-text text-muted">
                                <span id="summaryCharCount">{{ strlen(old('summary', $familyNews->summary ?? '')) }}</span> / 500 حرف
                            </small>
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content" class="font-weight-bold">
                                محتوى الخبر <span class="text-danger">*</span>
                            </label>
                            <textarea name="content" id="content" rows="10"
                                      class="form-control @error('content') is-invalid @enderror"
                                      placeholder="أدخل محتوى الخبر الكامل..."
                                      required>{{ old('content', $familyNews->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="main_image" class="font-weight-bold">الصورة الرئيسية</label>
                            @if($familyNews->main_image_url)
                                <div class="mb-2">
                                    <img src="{{ $familyNews->main_image_url }}" class="img-thumbnail" style="max-width: 300px; max-height: 300px;" alt="الصورة الحالية">
                                    <p class="text-muted small mt-1">الصورة الحالية</p>
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="main_image" id="main_image"
                                       class="custom-file-input @error('main_image') is-invalid @enderror"
                                       accept="image/*">
                                <label class="custom-file-label" for="main_image">اختر صورة جديدة (اختياري)...</label>
                            </div>
                            @error('main_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                الصيغ المدعومة: JPEG, PNG, JPG, GIF, WebP (حد أقصى 5MB)
                            </small>
                            <div class="mt-2" id="mainImagePreview"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="published_at" class="font-weight-bold">تاريخ النشر</label>
                                    <input type="datetime-local" name="published_at" id="published_at"
                                           class="form-control @error('published_at') is-invalid @enderror"
                                           value="{{ old('published_at', $familyNews->published_at ? $familyNews->published_at->format('Y-m-d\TH:i') : '') }}">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check mt-4">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" id="is_active" value="1"
                                               class="form-check-input"
                                               {{ old('is_active', $familyNews->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label font-weight-bold" for="is_active">
                                            تفعيل الخبر
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>حفظ التغييرات
                            </button>
                            <a href="{{ route('dashboard.family-news.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- إدارة الصور الفرعية -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-images mr-2"></i>الصور الفرعية
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.family-news.add-image', $familyNews) }}" method="POST" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <div class="form-group">
                            <label for="image" class="font-weight-bold small">إضافة صورة فرعية</label>
                            <div class="custom-file">
                                <input type="file" name="image" id="image"
                                       class="custom-file-input"
                                       accept="image/*" required>
                                <label class="custom-file-label" for="image">اختر صورة...</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="caption" class="font-weight-bold small">وصف الصورة (اختياري)</label>
                            <input type="text" name="caption" id="caption"
                                   class="form-control form-control-sm"
                                   placeholder="وصف الصورة..."
                                   maxlength="255">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary btn-block">
                            <i class="fas fa-plus mr-1"></i>إضافة صورة
                        </button>
                    </form>

                    <hr>

                    @if($familyNews->images->count() > 0)
                        <div class="images-list">
                            @foreach($familyNews->images as $image)
                                <div class="mb-3 p-2 border rounded" style="background: #f8f9fa;">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="{{ $image->image_url }}" class="img-thumbnail mr-2" style="width: 80px; height: 80px; object-fit: cover;" alt="صورة">
                                        <div class="flex-grow-1">
                                            @if($image->caption)
                                                <p class="mb-0 small text-muted">{{ $image->caption }}</p>
                                            @else
                                                <p class="mb-0 small text-muted">بدون وصف</p>
                                            @endif
                                            <small class="text-muted">ترتيب: {{ $image->display_order }}</small>
                                        </div>
                                    </div>
                                    <form action="{{ route('dashboard.family-news.delete-image', $image) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash mr-1"></i>حذف
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center small">لا توجد صور فرعية</p>
                    @endif
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>معلومات
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2">
                        <strong>عدد المشاهدات:</strong> {{ $familyNews->views_count }}
                    </p>
                    <p class="small mb-2">
                        <strong>تاريخ الإنشاء:</strong> {{ $familyNews->created_at->format('Y-m-d H:i') }}
                    </p>
                    <p class="small mb-0">
                        <strong>آخر تحديث:</strong> {{ $familyNews->updated_at->format('Y-m-d H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // معاينة الصورة الرئيسية
    document.getElementById('main_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('mainImagePreview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="max-width: 300px; max-height: 300px;" alt="معاينة الصورة">
                `;
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    });

    // عداد أحرف الملخص
    document.getElementById('summary').addEventListener('input', function() {
        document.getElementById('summaryCharCount').textContent = this.value.length;
    });
</script>
@endpush
@endsection
