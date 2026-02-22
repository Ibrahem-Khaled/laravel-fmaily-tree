@extends('layouts.app')

@section('title', 'إضافة خبر جديد')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-plus-circle text-primary mr-2"></i>إضافة خبر جديد
            </h1>
            <p class="text-muted mb-0">أضف خبر جديد للعائلة</p>
        </div>
        <a href="{{ route('dashboard.family-news.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة
        </a>
    </div>

    <x-alerts />

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('dashboard.family-news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="title" class="font-weight-bold">
                        عنوان الخبر <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}"
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
                              placeholder="أدخل ملخص قصير للخبر (سيظهر في الصفحة الرئيسية)..."
                              maxlength="500">{{ old('summary') }}</textarea>
                    <small class="form-text text-muted">
                        <span id="summaryCharCount">0</span> / 500 حرف
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
                              required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="main_image" class="font-weight-bold">الصورة الرئيسية (اختياري)</label>
                    <div class="custom-file">
                        <input type="file" name="main_image" id="main_image"
                               class="custom-file-input @error('main_image') is-invalid @enderror"
                               accept="image/*">
                        <label class="custom-file-label" for="main_image">اختر صورة...</label>
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
                            <label for="published_at" class="font-weight-bold">تاريخ النشر (اختياري)</label>
                            <input type="datetime-local" name="published_at" id="published_at"
                                   class="form-control @error('published_at') is-invalid @enderror"
                                   value="{{ old('published_at') }}">
                            <small class="form-text text-muted">
                                اتركه فارغاً للنشر فوراً
                            </small>
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
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label font-weight-bold" for="is_active">
                                    تفعيل الخبر
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>حفظ الخبر
                    </button>
                    <a href="{{ route('dashboard.family-news.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>إلغاء
                    </a>
                </div>
            </form>
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
