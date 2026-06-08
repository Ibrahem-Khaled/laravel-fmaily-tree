@extends('layouts.app')

@section('title', 'إنشاء استطلاع رأي جديد')

@section('content')
@push('styles')
@include('dashboard.quiz-competitions._styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h2 mb-1 text-gray-900 font-weight-bold">
                <i class="fas fa-plus-circle text-teal mr-2"></i>إنشاء استطلاع رأي جديد
            </h1>
            <p class="text-muted mb-0">قم بإضافة استطلاع رأي جديد أو مسابقة عائلية وتحديد شروطها ورعاتها.</p>
        </div>
        <a href="{{ route('dashboard.quiz-competitions.index') }}" class="modern-btn modern-btn-secondary text-decoration-none">
            <i class="fas fa-arrow-right"></i>
            <span>العودة للقائمة</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="card modern-card">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('dashboard.quiz-competitions.store') }}" method="POST">
                @csrf

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        
                        <!-- Title field -->
                        <div class="form-group mb-4">
                            <label for="title" class="modern-label">العنوان الرئيسي للاستطلاع / الفعالية <span class="text-danger">*</span></label>
                            <input type="text" class="form-control modern-input @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title') }}" placeholder="أدخل عنواناً جذاباً ومعبراً..." required>
                            @error('title')
                                <div class="invalid-feedback font-weight-bold mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description field (Summernote) -->
                        <div class="form-group mb-4">
                            <label for="description" class="modern-label">الوصف والتعليمات العامة</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="6">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback font-weight-bold mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date range & Reveal Delay -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="form-group">
                                    <label for="start_at" class="modern-label">تاريخ ووقت البدء</label>
                                    <input type="datetime-local"
                                        class="form-control modern-input @error('start_at') is-invalid @enderror" id="start_at"
                                        name="start_at" value="{{ old('start_at') }}">
                                    @error('start_at')
                                        <div class="invalid-feedback font-weight-bold mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="form-group">
                                    <label for="end_at" class="modern-label">تاريخ ووقت الانتهاء</label>
                                    <input type="datetime-local"
                                        class="form-control modern-input @error('end_at') is-invalid @enderror" id="end_at"
                                        name="end_at" value="{{ old('end_at') }}">
                                    @error('end_at')
                                        <div class="invalid-feedback font-weight-bold mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reveal_delay_seconds" class="modern-label">مدة الانتظار لبدء التصويت (ثانية)</label>
                                    <input type="number"
                                        class="form-control modern-input @error('reveal_delay_seconds') is-invalid @enderror"
                                        id="reveal_delay_seconds" name="reveal_delay_seconds"
                                        value="{{ old('reveal_delay_seconds', 60) }}" min="0">
                                    <small class="form-text text-muted mt-2">
                                        <i class="fas fa-info-circle mr-1"></i> مهلة عد تنازلي للزائر قبل البدء (الافتراضي 60 ثانية).
                                    </small>
                                    @error('reveal_delay_seconds')
                                        <div class="invalid-feedback font-weight-bold mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sponsors Selection -->
                        <div class="form-group mb-4 p-4 rounded-lg bg-light" style="border-right: 4px solid #0d9488;">
                            <label for="sponsors" class="modern-label text-teal">
                                <i class="fas fa-handshake mr-1"></i> رعاة الفعالية والاستطلاع
                            </label>
                            <select class="form-control select2 @error('sponsors') is-invalid @enderror" id="sponsors"
                                name="sponsors[]" multiple="multiple" data-placeholder="اختر رعاة الاستطلاع...">
                                @foreach($sponsors ?? [] as $sponsor)
                                    <option value="{{ $sponsor->id }}" {{ in_array($sponsor->id, old('sponsors', [])) ? 'selected' : '' }}>
                                        {{ $sponsor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle mr-1"></i> سيتم عرض شعارات وأسماء الرعاة المحددين في واجهة استطلاع الرأي.
                            </small>
                            @error('sponsors')
                                <div class="invalid-feedback d-block font-weight-bold mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Display Order -->
                        <div class="form-group mb-4">
                            <label for="display_order" class="modern-label">أولوية ترتيب العرض</label>
                            <input type="number" class="form-control modern-input @error('display_order') is-invalid @enderror"
                                id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
                            @error('display_order')
                                <div class="invalid-feedback font-weight-bold mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Toggles/Switches -->
                        <div class="card p-3 mb-4 border bg-white" style="border-radius: 16px;">
                            <div class="form-group mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="show_draw_only" name="show_draw_only" value="1" {{ old('show_draw_only', false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="show_draw_only">تفعيل وضع السحب والقرعة فقط (إخفاء بنود وخيارات التصويت وعرض زر سحب الفائزين فقط)</label>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">تفعيل ونشر الاستطلاع للجميع فوراً</label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex align-items-center gap-3 pt-3 border-top">
                            <button type="submit" class="modern-btn modern-btn-primary">
                                <i class="fas fa-check-circle"></i>
                                <span>حفظ ونشر الاستطلاع</span>
                            </button>
                            <a href="{{ route('dashboard.quiz-competitions.index') }}" class="modern-btn modern-btn-secondary text-decoration-none">
                                <i class="fas fa-times"></i>
                                <span>إلغاء وتراجع</span>
                            </a>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function () {
        // Initialize Summernote
        $('#description').summernote({
            placeholder: 'اكتب الوصف أو الشروط والتعليمات هنا...',
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