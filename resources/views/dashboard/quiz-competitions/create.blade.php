@extends('layouts.app')

@section('title', 'إضافة مسابقة أسئلة جديدة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-plus-circle text-primary mr-2"></i>إضافة مسابقة أسئلة جديدة
            </h1>
            <p class="text-muted mb-0">قم بإضافة مسابقة أسئلة جديدة</p>
        </div>
        <a href="{{ route('dashboard.quiz-competitions.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('dashboard.quiz-competitions.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title">عنوان المسابقة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">وصف المسابقة</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_at">بداية المسابقة</label>
                                    <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror" id="start_at" name="start_at" value="{{ old('start_at') }}">
                                    @error('start_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_at">نهاية المسابقة</label>
                                    <input type="datetime-local" class="form-control @error('end_at') is-invalid @enderror" id="end_at" name="end_at" value="{{ old('end_at') }}">
                                    @error('end_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reveal_delay_seconds">تأخير إظهار السؤال (بالثواني)</label>
                                    <input type="number" class="form-control @error('reveal_delay_seconds') is-invalid @enderror" id="reveal_delay_seconds" name="reveal_delay_seconds" value="{{ old('reveal_delay_seconds', 60) }}" min="0">
                                    <small class="form-text text-muted">عدد الثواني التي يجب أن ينتظرها الزائر قبل رؤية السؤال (الافتراضي 60 ثانية).</small>
                                    @error('reveal_delay_seconds')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3 border p-3 bg-light rounded">
                            <label for="sponsors" class="font-weight-bold text-primary">
                                <i class="fas fa-handshake mr-1"></i> رعاة المسابقة
                            </label>
                            <select class="form-control select2 @error('sponsors') is-invalid @enderror" id="sponsors" name="sponsors[]" multiple="multiple" data-placeholder="اختر رعاة المسابقة...">
                                @foreach($sponsors ?? [] as $sponsor)
                                    <option value="{{ $sponsor->id }}" {{ in_array($sponsor->id, old('sponsors', [])) ? 'selected' : '' }}>
                                        {{ $sponsor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle mr-1"></i> يمكنك اختيار أكثر من راعي للمسابقة وسيظهرون في صفحة عرض المسابقة.
                            </small>
                            @error('sponsors')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="display_order">ترتيب العرض</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">تفعيل المسابقة</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>حفظ المسابقة
                    </button>
                    <a href="{{ route('dashboard.quiz-competitions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        // Summernote editor
        $('#description').summernote({
            height: 200,
            direction: 'rtl',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush
