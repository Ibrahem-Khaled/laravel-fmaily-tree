@extends('layouts.app')

@section('title', 'إضافة قسم جديد')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-plus-circle text-primary mr-2"></i>إضافة قسم جديد
            </h1>
            <p class="text-muted mb-0">قم بإنشاء قسم ديناميكي جديد للصفحة الرئيسية</p>
        </div>
        <a href="{{ route('dashboard.home-sections.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('dashboard.home-sections.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="title" class="font-weight-bold">
                        عنوان القسم <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="title" 
                           class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title') }}" 
                           placeholder="مثال: قسم الترحيب، قسم الأخبار، إلخ..."
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        هذا العنوان يظهر فقط في لوحة التحكم
                    </small>
                </div>

                <div class="form-group">
                    <label for="section_type" class="font-weight-bold">
                        نوع القسم <span class="text-danger">*</span>
                    </label>
                    <select name="section_type" id="section_type" 
                            class="form-control @error('section_type') is-invalid @enderror" 
                            required>
                        <option value="">-- اختر نوع القسم --</option>
                        <option value="custom" {{ old('section_type') == 'custom' ? 'selected' : '' }}>مخصص</option>
                        <option value="text" {{ old('section_type') == 'text' ? 'selected' : '' }}>نص</option>
                        <option value="gallery" {{ old('section_type') == 'gallery' ? 'selected' : '' }}>معرض صور</option>
                        <option value="video_section" {{ old('section_type') == 'video_section' ? 'selected' : '' }}>قسم فيديو</option>
                        <option value="text_with_image" {{ old('section_type') == 'text_with_image' ? 'selected' : '' }}>نص مع صورة</option>
                        <option value="buttons" {{ old('section_type') == 'buttons' ? 'selected' : '' }}>أزرار وروابط</option>
                        <option value="mixed" {{ old('section_type') == 'mixed' ? 'selected' : '' }}>محتوى مختلط</option>
                    </select>
                    @error('section_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" id="is_active" 
                               class="form-check-input" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            تفعيل القسم
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="css_classes" class="font-weight-bold">
                        فئات CSS مخصصة (اختياري)
                    </label>
                    <input type="text" name="css_classes" id="css_classes" 
                           class="form-control @error('css_classes') is-invalid @enderror" 
                           value="{{ old('css_classes') }}" 
                           placeholder="مثال: bg-gradient-primary py-5">
                    @error('css_classes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        يمكنك إضافة فئات CSS مخصصة للقسم
                    </small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>إنشاء القسم
                    </button>
                    <a href="{{ route('dashboard.home-sections.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

