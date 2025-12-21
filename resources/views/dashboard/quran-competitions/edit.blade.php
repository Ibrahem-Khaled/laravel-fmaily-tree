@extends('layouts.app')

@section('title', 'تعديل مسابقة')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-edit text-primary mr-2"></i>تعديل مسابقة
            </h1>
            <p class="text-muted mb-0">تعديل معلومات المسابقة</p>
        </div>
        <a href="{{ route('dashboard.quran-competitions.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('dashboard.quran-competitions.update', $quranCompetition) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title">عنوان المسابقة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $quranCompetition->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="hijri_year">السنة الهجرية <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('hijri_year') is-invalid @enderror" id="hijri_year" name="hijri_year" value="{{ old('hijri_year', $quranCompetition->hijri_year) }}" placeholder="مثال: 1445" required>
                            @error('hijri_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">وصف المسابقة</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $quranCompetition->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">تاريخ البداية</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $quranCompetition->start_date?->format('Y-m-d')) }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">تاريخ النهاية</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $quranCompetition->end_date?->format('Y-m-d')) }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="display_order">ترتيب العرض</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', $quranCompetition->display_order) }}" min="0">
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $quranCompetition->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    تفعيل المسابقة
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cover_image">صورة الغلاف</label>
                            @if($quranCompetition->cover_image)
                                <div class="mb-3">
                                    <img src="{{ $quranCompetition->cover_image_url }}" alt="Cover" class="img-thumbnail" style="max-width: 100%; max-height: 300px;">
                                    <p class="text-muted mt-2"><small>الصورة الحالية</small></p>
                                </div>
                            @endif
                            <input type="file" class="form-control-file @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*" onchange="previewImage(this)">
                            @error('cover_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الحد الأقصى: 2 ميجابايت</small>
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 100%; max-height: 300px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>حفظ التعديلات
                    </button>
                    <a href="{{ route('dashboard.quran-competitions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection

