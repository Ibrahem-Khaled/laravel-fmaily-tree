@props([
    'category' => null,
    'parents' => [],
    'prefillParentId' => null,
])

@php
    $isEdit = $category !== null;
@endphp

<div class="row">
    <div class="col-lg-8">
        <x-dashboard.card title="البيانات الأساسية" icon="fe-edit-3" class="mb-4">
            <div class="form-group mb-4">
                <label for="category_name" class="font-weight-bold">اسم التصنيف <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="name"
                    id="category_name"
                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                    style="border-radius: 12px;"
                    value="{{ old('name', $category?->name) }}"
                    required
                    maxlength="255"
                    placeholder="مثال: إلكترونيات، أثاث، خدمات..."
                />
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-0">
                <label for="category_description" class="font-weight-bold">الوصف</label>
                <textarea
                    name="description"
                    id="category_description"
                    rows="4"
                    class="form-control @error('description') is-invalid @enderror"
                    style="border-radius: 12px;"
                    placeholder="وصف اختياري يظهر في لوحة التحكم فقط"
                >{{ old('description', $category?->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-0 mt-4">
                <label for="category_image" class="font-weight-bold">صورة التصنيف</label>
                <input
                    type="file"
                    name="image"
                    id="category_image"
                    accept="image/*"
                    class="form-control @error('image') is-invalid @enderror"
                    style="border-radius: 12px;"
                />
                @error('image')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">صورة اختيارية تُعرض في الموقع (حد أقصى 2 ميجابايت).</small>
                @if($isEdit && $category->imageUrl())
                    <div class="mt-3">
                        <span class="d-block small text-muted mb-1">الصورة الحالية</span>
                        <img src="{{ $category->imageUrl() }}" alt="" class="img-fluid rounded border" style="max-height: 140px; object-fit: contain;" />
                    </div>
                @endif
            </div>
        </x-dashboard.card>
    </div>

    <div class="col-lg-4">
        <x-dashboard.card title="التسلسل والظهور" icon="fe-sliders" class="mb-4">
            <div class="form-group mb-4">
                <label for="category_parent_id" class="font-weight-bold">الفئة الأب</label>
                <select
                    name="parent_id"
                    id="category_parent_id"
                    class="form-control @error('parent_id') is-invalid @enderror"
                    style="border-radius: 12px;"
                >
                    <option value="">— تصنيف جذر (بدون أب) —</option>
                    @foreach($parents as $p)
                        <option
                            value="{{ $p['id'] }}"
                            @selected((string) old('parent_id', $category?->parent_id ?? $prefillParentId) === (string) $p['id'])
                        >{{ $p['label'] }}</option>
                    @endforeach
                </select>
                @error('parent_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">اتركه فارغاً ليكون التصنيف في أعلى الشجرة.</small>
            </div>

            <div class="form-group mb-4">
                <label for="category_sort_order" class="font-weight-bold">ترتيب العرض</label>
                <input
                    type="number"
                    name="sort_order"
                    id="category_sort_order"
                    min="0"
                    class="form-control @error('sort_order') is-invalid @enderror"
                    style="border-radius: 12px;"
                    value="{{ old('sort_order', $category?->sort_order ?? 0) }}"
                />
                @error('sort_order')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-0">
                <label class="font-weight-bold d-block mb-2">الحالة</label>
                <input type="hidden" name="is_active" value="0" />
                <div class="custom-control custom-switch">
                    <input
                        type="checkbox"
                        class="custom-control-input"
                        id="category_is_active"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $category?->is_active ?? true))
                    />
                    <label class="custom-control-label" for="category_is_active">تصنيف نشط</label>
                </div>
                @error('is_active')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </x-dashboard.card>

        @if($isEdit)
            <x-dashboard.card title="معلومات النظام" icon="fe-info" padding="p-4">
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2"><span class="font-weight-bold text-body">المعرّف:</span> {{ $category->id }}</li>
                    <li class="mb-2"><span class="font-weight-bold text-body">المسار:</span> <code class="small">{{ $category->slug }}</code></li>
                    <li class="mb-0"><span class="font-weight-bold text-body">آخر تحديث:</span> {{ $category->updated_at?->diffForHumans() }}</li>
                </ul>
            </x-dashboard.card>
        @endif
    </div>
</div>

<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mt-2">
    <a href="{{ route('dashboard.categories.index') }}" class="btn btn-outline-primary border rounded-pill px-4">
        <i class="fe fe-arrow-right fe-16 ml-1"></i> إلغاء والعودة
    </a>
    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
        <i class="fe fe-save fe-16 ml-1"></i> {{ $isEdit ? 'حفظ التعديلات' : 'إنشاء التصنيف' }}
    </button>
</div>
