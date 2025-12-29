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

                        <div class="mt-4">
                            <h5 class="mb-3">معرض الصور والفيديو الحالة</h5>
                            
                            @if($product->media->count() > 0)
                                <div class="row mb-4">
                                    @foreach($product->media as $media)
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100 shadow-sm border-0 bg-light">
                                                <div class="card-body p-2 text-center position-relative">
                                                    @if($media->media_type === 'image')
                                                        <img src="{{ asset('storage/' . $media->file_path) }}" class="img-fluid rounded mb-2" style="height: 100px; object-fit: cover;">
                                                    @elseif($media->media_type === 'video')
                                                        <div class="bg-dark text-white rounded d-flex align-items-center justify-center mb-2" style="height: 100px;">
                                                            <i class="fas fa-video fa-2x"></i>
                                                        </div>
                                                    @elseif($media->media_type === 'youtube')
                                                        <div class="bg-danger text-white rounded d-flex align-items-center justify-center mb-2" style="height: 100px;">
                                                            <i class="fab fa-youtube fa-2x"></i>
                                                        </div>
                                                    @endif
                                                    <div class="form-check text-danger">
                                                        <input class="form-check-input" type="checkbox" name="delete_media[]" value="{{ $media->id }}" id="media_{{ $media->id }}">
                                                        <label class="form-check-label small font-weight-bold" for="media_{{ $media->id }}">حذف</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="bg-light p-3 rounded mb-4">
                                <h6 class="font-weight-bold mb-3">إضافة وسائط جديدة</h6>
                                <div class="form-group">
                                    <label>صور إضافية</label>
                                    <input type="file" name="images[]" class="form-control-file" accept="image/*" multiple>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>فيديو محلي</label>
                                            <input type="file" name="video_file" class="form-control-file" accept="video/mp4,video/mpeg,video/quicktime">
                                            @php $videoMedia = $product->media->where('media_type', 'video')->first(); @endphp
                                            @if($videoMedia)
                                                <small class="text-info d-block">يوجد فيديو حالي (سيتم استبداله إذا رفعت جديداً)</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>رابط يوتيوب</label>
                                            @php $youtubeMedia = $product->media->where('media_type', 'youtube')->first(); @endphp
                                            <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $youtubeMedia ? $youtubeMedia->youtube_url : '') }}" placeholder="https://www.youtube.com/watch?v=...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">الفئات</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>الفئة الرئيسية <span class="text-danger">*</span></label>
                                    <select name="product_category_id" id="categorySelect"
                                            class="form-control @error('product_category_id') is-invalid @enderror" required
                                            onchange="updateSubcategories()">
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
// البيانات من السيرفر
var subcategoriesData = @json($subcategories);
var currentCategoryId = {{ $product->product_category_id ?? 'null' }};
var currentSubcategoryId = {{ $product->product_subcategory_id ?? 'null' }};

// دالة لتحديث الفئات الفرعية - يجب أن تكون global
function updateSubcategories() {
    var categorySelect = document.getElementById('categorySelect');
    var subcategorySelect = document.getElementById('subcategorySelect');
    
    if (!categorySelect || !subcategorySelect) {
        console.log('Elements not found');
        return;
    }
    
    var categoryId = categorySelect.value;
    var previousSubcategoryId = subcategorySelect.value;

    // مسح القائمة
    subcategorySelect.innerHTML = '<option value="">اختر الفئة الفرعية</option>';

    if (categoryId && subcategoriesData && subcategoriesData.length > 0) {
        var categoryIdNum = parseInt(categoryId);
        
        // البحث عن الفئات الفرعية التابعة لهذه الفئة
        for (var i = 0; i < subcategoriesData.length; i++) {
            var sub = subcategoriesData[i];
            var subCategoryId = parseInt(sub.product_category_id);
            
            if (subCategoryId === categoryIdNum) {
                var option = document.createElement('option');
                option.value = sub.id;
                option.textContent = sub.name;
                
                // تحديد الفئة الفرعية الحالية فقط إذا كانت تابعة للفئة المختارة
                if (currentSubcategoryId && parseInt(sub.id) === parseInt(currentSubcategoryId) && categoryIdNum === parseInt(currentCategoryId)) {
                    option.selected = true;
                }
                
                subcategorySelect.appendChild(option);
            }
        }

        // إذا تم تغيير الفئة الرئيسية والفئة الفرعية الحالية لا تتبع الفئة الجديدة، قم بإعادة تعيينها
        if (categoryIdNum !== parseInt(currentCategoryId) && previousSubcategoryId) {
            var found = false;
            for (var j = 0; j < subcategoriesData.length; j++) {
                if (parseInt(subcategoriesData[j].id) === parseInt(previousSubcategoryId) && 
                    parseInt(subcategoriesData[j].product_category_id) === categoryIdNum) {
                    found = true;
                    break;
                }
            }
            if (!found) {
                subcategorySelect.value = '';
            }
        }
    } else {
        subcategorySelect.value = '';
    }
}

// عند تحميل الصفحة
window.onload = function() {
    var categorySelect = document.getElementById('categorySelect');
    var subcategorySelect = document.getElementById('subcategorySelect');
    
    if (categorySelect && subcategorySelect) {
        // تشغيل عند تحميل الصفحة
        updateSubcategories();
    }
};
</script>
@endpush
@endsection

