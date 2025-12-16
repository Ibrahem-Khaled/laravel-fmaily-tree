@extends('layouts.app')

@section('title', 'تعديل موقع صحي')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-edit text-primary mr-2"></i>تعديل موقع صحي
            </h1>
        </div>
        <a href="{{ route('dashboard.health-websites.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right mr-2"></i>رجوع
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('dashboard.health-websites.update', $healthWebsite) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>اسم الموقع <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $healthWebsite->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>رابط الموقع <span class="text-danger">*</span></label>
                            <input type="url" name="url" class="form-control @error('url') is-invalid @enderror"
                                   value="{{ old('url', $healthWebsite->url) }}" required>
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>الوصف</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="4">{{ old('description', $healthWebsite->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>الفئة</label>
                            <input type="text" name="category" class="form-control"
                                   value="{{ old('category', $healthWebsite->category) }}" placeholder="مثال: تغذية، تمارين، صحة عامة">
                        </div>

                        <div class="form-check mt-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1"
                                   {{ old('is_active', $healthWebsite->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">تفعيل الموقع</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الشعار</label>
                            @if($healthWebsite->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $healthWebsite->logo) }}"
                                         alt="{{ $healthWebsite->name }}"
                                         class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control-file" accept="image/*">
                            <small class="text-muted">اتركه فارغاً للاحتفاظ بالشعار الحالي</small>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>تحديث الموقع
                    </button>
                    <a href="{{ route('dashboard.health-websites.index') }}" class="btn btn-secondary">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
