@extends('layouts.app')

@section('title', 'إضافة منتج جديد')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-plus-circle text-primary mr-2"></i>إضافة منتج جديد
            </h1>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right mr-2"></i>رجوع
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <h5 class="mb-3">معلومات المنتج</h5>

                        <div class="form-group">
                            <label>اسم المنتج <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>الوصف</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>المميزات (كل مميزة في سطر)</label>
                            <textarea name="features" class="form-control" rows="5"
                                      placeholder="مثال:&#10;جودة عالية&#10;سعر مناسب&#10;تصميم عصري"></textarea>
                            <small class="text-muted">يمكنك إضافة مميزات متعددة، كل مميزة في سطر منفصل</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>السعر (ر.س) <span class="text-danger">*</span></label>
                                    <input type="number" name="price" step="0.01" min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price') }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>الصورة الرئيسية</label>
                                    <input type="file" name="main_image" class="form-control-file" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5 class="mb-3">معرض الصور والفيديو</h5>
                                <div class="form-group">
                                    <label>صور إضافية للمنتج</label>
                                    <input type="file" name="images[]" class="form-control-file" accept="image/*" multiple>
                                    <small class="text-muted">يمكنك اختيار عدة صور معاً</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>فيديو محلي</label>
                                    <input type="file" name="video_file" class="form-control-file" accept="video/mp4,video/mpeg,video/quicktime">
                                    <small class="text-muted">الحد الأقصى 20 ميجابايت (mp4, mpeg, mov)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>رابط فيديو يوتيوب</label>
                                    <input type="url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
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
                                            <option value="{{ $cat->id }}" {{ old('product_category_id') == $cat->id ? 'selected' : '' }}>
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
                                            <option value="{{ $person->id }}" {{ old('owner_id') == $person->id ? 'selected' : '' }}>
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
                                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
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
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">تفعيل المنتج</label>
                        </div>

                        <div class="form-check mt-2">
                            <input type="hidden" name="is_rental" value="0">
                            <input type="checkbox" name="is_rental" class="form-check-input" id="isRental" value="1" {{ old('is_rental') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isRental">منتج للتأجير (سيظهر في متجر الاستعارة فقط)</label>
                        </div>

                        <h5 class="mb-3 mt-4">المواعيد المتاحة</h5>

                        <div class="form-check mb-3">
                            <input type="hidden" name="available_all_week" value="0">
                            <input type="checkbox" name="available_all_week" class="form-check-input" id="availableAllWeek" value="1" {{ old('available_all_week') ? 'checked' : '' }} onchange="toggleAvailabilityOptions()">
                            <label class="form-check-label" for="availableAllWeek">متاح طوال الأسبوع</label>
                        </div>

                        <div id="allWeekTimes" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>وقت البداية</label>
                                        <input type="time" name="all_week_start_time" class="form-control" value="{{ old('all_week_start_time') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>وقت النهاية</label>
                                        <input type="time" name="all_week_end_time" class="form-control" value="{{ old('all_week_end_time') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="specificSlots">
                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-success" onclick="addAvailabilitySlot()">
                                    <i class="fas fa-plus mr-1"></i>إضافة موعد محدد
                                </button>
                            </div>
                            <div id="availabilitySlotsContainer">
                                <!-- سيتم إضافة المواعيد هنا ديناميكياً -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h5 class="mb-3">معلومات التواصل</h5>

                        <div class="form-group">
                            <label>رقم الهاتف</label>
                            <input type="text" name="contact_phone" class="form-control"
                                   value="{{ old('contact_phone') }}" placeholder="05xxxxxxxx">
                        </div>

                        <div class="form-group">
                            <label>واتساب</label>
                            <input type="text" name="contact_whatsapp" class="form-control"
                                   value="{{ old('contact_whatsapp') }}" placeholder="05xxxxxxxx">
                        </div>

                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="contact_email" class="form-control"
                                   value="{{ old('contact_email') }}">
                        </div>

                        <div class="form-group">
                            <label>إنستقرام</label>
                            <input type="text" name="contact_instagram" class="form-control"
                                   value="{{ old('contact_instagram') }}" placeholder="@username">
                        </div>

                        <div class="form-group">
                            <label>فيسبوك</label>
                            <input type="text" name="contact_facebook" class="form-control"
                                   value="{{ old('contact_facebook') }}" placeholder="رابط الصفحة">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>حفظ المنتج
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
var oldCategoryId = {{ old('product_category_id') ?? 'null' }};
var oldSubcategoryId = {{ old('product_subcategory_id') ?? 'null' }};

// دالة لتحديث الفئات الفرعية - يجب أن تكون global
function updateSubcategories() {
    var categorySelect = document.getElementById('categorySelect');
    var subcategorySelect = document.getElementById('subcategorySelect');
    
    if (!categorySelect || !subcategorySelect) {
        console.log('Elements not found');
        return;
    }
    
    var categoryId = categorySelect.value;
    
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
                
                // تحديد الفئة الفرعية القديمة إذا كانت موجودة
                if (oldSubcategoryId && parseInt(sub.id) === parseInt(oldSubcategoryId) && categoryIdNum === parseInt(oldCategoryId)) {
                    option.selected = true;
                }
                
                subcategorySelect.appendChild(option);
            }
        }
    }
}

// عند تحميل الصفحة
window.onload = function() {
    var categorySelect = document.getElementById('categorySelect');
    var subcategorySelect = document.getElementById('subcategorySelect');
    
    if (categorySelect && subcategorySelect) {
        // تشغيل عند تحميل الصفحة إذا كانت هناك فئة محددة
        if (categorySelect.value || oldCategoryId) {
            updateSubcategories();
        }
    }
    
    // التحقق من حالة "متاح طوال الأسبوع" عند التحميل
    toggleAvailabilityOptions();
};

// إدارة المواعيد
var slotCounter = 0;

function toggleAvailabilityOptions() {
    var availableAllWeek = document.getElementById('availableAllWeek');
    var allWeekTimes = document.getElementById('allWeekTimes');
    var specificSlots = document.getElementById('specificSlots');
    
    if (availableAllWeek.checked) {
        allWeekTimes.style.display = 'block';
        specificSlots.style.display = 'none';
    } else {
        allWeekTimes.style.display = 'none';
        specificSlots.style.display = 'block';
    }
}

function addAvailabilitySlot() {
    slotCounter++;
    var container = document.getElementById('availabilitySlotsContainer');
    var slotHtml = `
        <div class="card mb-3 slot-item" data-slot-id="${slotCounter}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>اليوم</label>
                            <select name="availability_slots[${slotCounter}][day_of_week]" class="form-control" required>
                                <option value="">اختر اليوم</option>
                                <option value="saturday">السبت</option>
                                <option value="sunday">الأحد</option>
                                <option value="monday">الإثنين</option>
                                <option value="tuesday">الثلاثاء</option>
                                <option value="wednesday">الأربعاء</option>
                                <option value="thursday">الخميس</option>
                                <option value="friday">الجمعة</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>وقت البداية</label>
                            <input type="time" name="availability_slots[${slotCounter}][start_time]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>وقت النهاية</label>
                            <input type="time" name="availability_slots[${slotCounter}][end_time]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="form-check">
                                <input type="hidden" name="availability_slots[${slotCounter}][is_active]" value="0">
                                <input type="checkbox" name="availability_slots[${slotCounter}][is_active]" class="form-check-input" value="1" checked>
                                <label class="form-check-label">نشط</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-sm btn-danger btn-block" onclick="removeSlot(${slotCounter})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', slotHtml);
}

function removeSlot(slotId) {
    var slotItem = document.querySelector(`.slot-item[data-slot-id="${slotId}"]`);
    if (slotItem) {
        slotItem.remove();
    }
}
</script>
@endpush
@endsection

