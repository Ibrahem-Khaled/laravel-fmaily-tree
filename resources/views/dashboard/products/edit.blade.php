@extends('layouts.app')

@section('title', 'تعديل المنتج')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-edit text-primary mr-2"></i>تعديل المنتج
            </h1>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right mr-2"></i>رجوع
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <h5 class="mb-3">معلومات المنتج</h5>

                        <div class="form-group">
                            <label>اسم المنتج <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>الوصف</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="4">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>المميزات (كل مميزة في سطر)</label>
                            <textarea name="features" class="form-control" rows="5"
                                      placeholder="مثال:&#10;جودة عالية&#10;سعر مناسب&#10;تصميم عصري">@if($product->features){{ implode("\n", $product->features) }}@endif</textarea>
                            <small class="text-muted">يمكنك إضافة مميزات متعددة، كل مميزة في سطر منفصل</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>السعر (ر.س) <span class="text-danger">*</span></label>
                                    <input type="number" name="price" step="0.01" min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price', $product->price) }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>الصورة الرئيسية</label>
                                    @if($product->main_image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $product->main_image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" name="main_image" class="form-control-file" accept="image/*">
                                    <small class="text-muted">اتركه فارغاً للاحتفاظ بالصورة الحالية</small>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">الفئات</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>الفئة الرئيسية <span class="text-danger">*</span></label>
                                    <select name="product_category_id" id="categorySelect"
                                            class="form-control @error('product_category_id') is-invalid @enderror" required>
                                        <option value="">اختر الفئة</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                    {{ old('product_category_id', $product->product_category_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>الفئة الفرعية</label>
                                    <select name="product_subcategory_id" id="subcategorySelect"
                                            class="form-control">
                                        <option value="">اختر الفئة الفرعية</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">معلومات إضافية</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>صاحب المنتج</label>
                                    <select name="owner_id" class="form-control @error('owner_id') is-invalid @enderror">
                                        <option value="">اختر صاحب المنتج</option>
                                        @foreach($persons as $person)
                                            <option value="{{ $person->id }}"
                                                    {{ old('owner_id', $product->owner_id) == $person->id ? 'selected' : '' }}>
                                                {{ $person->first_name }} {{ $person->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('owner_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>الموقع/المكان</label>
                                    <select name="location_id" class="form-control @error('location_id') is-invalid @enderror">
                                        <option value="">اختر الموقع</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}"
                                                    {{ old('location_id', $product->location_id) == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('location_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-check mt-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">تفعيل المنتج</label>
                        </div>

                        <div class="form-check mt-2">
                            <input type="hidden" name="is_rental" value="0">
                            <input type="checkbox" name="is_rental" class="form-check-input" id="isRental" value="1"
                                   {{ old('is_rental', $product->is_rental) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isRental">منتج للتأجير (سيظهر في متجر الاستعارة فقط)</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h5 class="mb-3">معلومات التواصل</h5>

                        <div class="form-group">
                            <label>رقم الهاتف</label>
                            <input type="text" name="contact_phone" class="form-control"
                                   value="{{ old('contact_phone', $product->contact_phone) }}" placeholder="05xxxxxxxx">
                        </div>

                        <div class="form-group">
                            <label>واتساب</label>
                            <input type="text" name="contact_whatsapp" class="form-control"
                                   value="{{ old('contact_whatsapp', $product->contact_whatsapp) }}" placeholder="05xxxxxxxx">
                        </div>

                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="contact_email" class="form-control"
                                   value="{{ old('contact_email', $product->contact_email) }}">
                        </div>

                        <div class="form-group">
                            <label>إنستقرام</label>
                            <input type="text" name="contact_instagram" class="form-control"
                                   value="{{ old('contact_instagram', $product->contact_instagram) }}" placeholder="@username">
                        </div>

                        <div class="form-group">
                            <label>فيسبوك</label>
                            <input type="text" name="contact_facebook" class="form-control"
                                   value="{{ old('contact_facebook', $product->contact_facebook) }}" placeholder="رابط الصفحة">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>تحديث المنتج
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const subcategories = @json($subcategories);
    const currentCategoryId = {{ $product->product_category_id }};
    const currentSubcategoryId = {{ $product->product_subcategory_id ?? 'null' }};

    function updateSubcategories() {
        const categoryId = document.getElementById('categorySelect').value;
        const subcategorySelect = document.getElementById('subcategorySelect');

        subcategorySelect.innerHTML = '<option value="">اختر الفئة الفرعية</option>';

        if (categoryId) {
            const filtered = subcategories.filter(s => s.product_category_id == categoryId);
            filtered.forEach(sub => {
                const option = document.createElement('option');
                option.value = sub.id;
                option.textContent = sub.name;
                if (currentSubcategoryId && sub.id == currentSubcategoryId) {
                    option.selected = true;
                }
                subcategorySelect.appendChild(option);
            });
        }
    }

    document.getElementById('categorySelect').addEventListener('change', updateSubcategories);

    // Initialize on page load
    updateSubcategories();
</script>
@endpush
@endsection

