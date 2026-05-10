@extends('layouts.app')

@section('title', $mode === 'create' ? 'إضافة قناة تواصل' : 'تعديل قناة تواصل')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('dashboard.support.index') }}" class="btn btn-sm btn-link text-muted px-0">
            <i class="fas fa-arrow-right ml-1"></i> العودة لإدارة الدعم الفني
        </a>
        <h1 class="h4 text-gray-800 mt-2">{{ $mode === 'create' ? 'إضافة قناة تواصل' : 'تعديل قناة تواصل' }}</h1>
    </div>

    <div class="card shadow col-lg-8 px-0">
        <div class="card-body">
            <form method="POST"
                action="{{ $mode === 'create' ? route('dashboard.support.channels.store') : route('dashboard.support.channels.update', $channel) }}">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="label">التسمية المعروضة</label>
                    <input type="text" name="label" id="label" required maxlength="255"
                        class="form-control @error('label') is-invalid @enderror"
                        value="{{ old('label', $channel->label) }}">
                    @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="type">نوع القناة</label>
                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                        @foreach(\App\Models\SupportChannel::typeLabels() as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $channel->type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="value">القيمة</label>
                    <input type="text" name="value" id="value" required maxlength="1024"
                        class="form-control @error('value') is-invalid @enderror"
                        value="{{ old('value', $channel->value) }}"
                        placeholder="بريد، رقم هاتف، رابط، أو رقم واتساب">
                    @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="form-text text-muted">للواتساب يمكن إدخال الرقم مع رمز الدولة بدون + أو لصق رابط wa.me.</small>
                </div>

                <div class="form-group">
                    <label for="icon">أيقونة Font Awesome (اختياري)</label>
                    <input type="text" name="icon" id="icon" maxlength="128"
                        class="form-control @error('icon') is-invalid @enderror"
                        value="{{ old('icon', $channel->icon) }}"
                        placeholder="مثال: fab fa-whatsapp">
                    @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="sort_order">الترتيب</label>
                        <input type="number" name="sort_order" id="sort_order" min="0"
                            class="form-control @error('sort_order') is-invalid @enderror"
                            value="{{ old('sort_order', $channel->sort_order ?? 0) }}">
                        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group col-md-6 d-flex align-items-end pb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                                {{ old('is_active', $channel->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">نشط في الموقع العام</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> {{ $mode === 'create' ? 'إضافة' : 'حفظ التعديلات' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
