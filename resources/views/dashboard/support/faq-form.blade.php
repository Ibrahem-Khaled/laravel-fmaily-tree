@extends('layouts.app')

@section('title', $mode === 'create' ? 'إضافة سؤال شائع' : 'تعديل سؤال شائع')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('dashboard.support.index') }}" class="btn btn-sm btn-link text-muted px-0">
            <i class="fas fa-arrow-right ml-1"></i> العودة لإدارة الدعم الفني
        </a>
        <h1 class="h4 text-gray-800 mt-2">{{ $mode === 'create' ? 'إضافة سؤال شائع' : 'تعديل سؤال شائع' }}</h1>
    </div>

    <div class="card shadow col-lg-8 px-0">
        <div class="card-body">
            <form method="POST"
                action="{{ $mode === 'create' ? route('dashboard.support.faqs.store') : route('dashboard.support.faqs.update', $faq) }}">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="question">السؤال</label>
                    <input type="text" name="question" id="question" required maxlength="500"
                        class="form-control @error('question') is-invalid @enderror"
                        value="{{ old('question', $faq->question) }}">
                    @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="answer">الإجابة</label>
                    <textarea name="answer" id="answer" rows="8" required
                        class="form-control @error('answer') is-invalid @enderror">{{ old('answer', $faq->answer) }}</textarea>
                    @error('answer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="sort_order">الترتيب</label>
                        <input type="number" name="sort_order" id="sort_order" min="0"
                            class="form-control @error('sort_order') is-invalid @enderror"
                            value="{{ old('sort_order', $faq->sort_order ?? 0) }}">
                        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group col-md-6 d-flex align-items-end pb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                                {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">نشط في الموقع العام</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i> {{ $mode === 'create' ? 'إضافة' : 'حفظ التعديلات' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
