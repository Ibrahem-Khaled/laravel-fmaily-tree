@extends('layouts.app')

@section('title', 'تعديل مسابقة')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-edit text-primary mr-2"></i>تعديل مسابقة
            </h1>
            <p class="text-muted mb-0">قم بتعديل بيانات المسابقة</p>
        </div>
        <a href="{{ route('dashboard.competitions.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('dashboard.competitions.update', $competition) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title">عنوان المسابقة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $competition->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="game_type">نوع اللعبة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('game_type') is-invalid @enderror" id="game_type" name="game_type" value="{{ old('game_type', $competition->game_type) }}" required>
                            @error('game_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="team_size">عدد أعضاء الفريق <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('team_size') is-invalid @enderror" id="team_size" name="team_size" value="{{ old('team_size', $competition->team_size) }}" min="1" step="1" required>
                            <small class="form-text text-muted">الحد الأدنى: 1 عضو (يمكن تعديل المسابقة إلى فردية أو جماعية)</small>
                            @error('team_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">وصف المسابقة</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $competition->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="program_id">ربط ببرنامج (اختياري)</label>
                            <select class="form-control @error('program_id') is-invalid @enderror" id="program_id" name="program_id">
                                <option value="">-- اختر برنامج --</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ old('program_id', $competition->program_id) == $program->id ? 'selected' : '' }}>
                                        {{ $program->program_title ?? $program->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">يمكنك ربط هذه المسابقة ببرنامج معين لعرض المسجلين في صفحة البرنامج</small>
                            @error('program_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>التصنيفات (اختياري)</label>
                            <div class="mb-3">
                                <select class="form-control" id="category_select" name="category_ids[]" multiple style="height: 150px;">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', $competition->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">يمكنك اختيار تصنيفات موجودة (اضغط Ctrl للاختيار المتعدد)</small>
                            </div>
                            
                            <div class="mb-3">
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#quickCategoryModal">
                                    <i class="fas fa-plus"></i> إضافة تصنيف جديد
                                </button>
                                <small class="form-text text-muted d-block mt-2">أو أضف تصنيفات جديدة عبر النافذة المنبثقة</small>
                            </div>

                            <div id="selected_categories" class="mb-3">
                                <label class="d-block">التصنيفات المختارة:</label>
                                <div id="selected_categories_list" class="d-flex flex-wrap gap-2">
                                    <!-- سيتم إضافة التصنيفات المختارة هنا -->
                                </div>
                            </div>

                            @error('category_ids')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            @error('new_categories')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">تاريخ بداية التسجيل</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $competition->start_date ? $competition->start_date->format('Y-m-d') : '') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">تاريخ نهاية التسجيل</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $competition->end_date ? $competition->end_date->format('Y-m-d') : '') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $competition->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    تفعيل المسابقة
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <strong>رابط التسجيل:</strong><br>
                            <code id="registrationUrl">{{ $competition->registration_url }}</code>
                            <button type="button" class="btn btn-sm btn-outline-primary ml-2" onclick="copyToClipboard('{{ $competition->registration_url }}')">
                                <i class="fas fa-copy"></i> نسخ
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary" id="submit_competition_btn">
                        <i class="fas fa-save mr-2"></i>حفظ التعديلات
                    </button>
                    <a href="{{ route('dashboard.competitions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@include('dashboard.articles.modals.quick-category')

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('تم نسخ الرابط بنجاح!');
    }, function(err) {
        console.error('فشل نسخ الرابط:', err);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const teamSizeInput = document.getElementById('team_size');
    
    if (teamSizeInput) {
        // التحقق عند تغيير القيمة
        teamSizeInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (value < 1) {
                this.setCustomValidity('عدد أعضاء الفريق يجب أن يكون على الأقل 1');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // التحقق عند الإرسال
        const form = teamSizeInput.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const value = parseInt(teamSizeInput.value);
                if (value < 1) {
                    e.preventDefault();
                    alert('عدد أعضاء الفريق يجب أن يكون على الأقل 1');
                    teamSizeInput.focus();
                    return false;
                }
                
                // التأكد من تحديث الحقول المخفية قبل الإرسال
                if (typeof updateHiddenInputs === 'function') {
                    updateHiddenInputs();
                }
            });
        }
    }

    // إدارة التصنيفات
    const categorySelect = document.getElementById('category_select');
    const selectedCategoriesList = document.getElementById('selected_categories_list');
    const form = document.querySelector('form');
    
    // التأكد من وجود form
    if (!form) {
        console.error('Form not found!');
    }
    
    // تخزين التصنيفات المختارة
    let selectedCategories = {
        existing: []
    };

    // تحديث التصنيفات المختارة من القائمة
    function updateSelectedCategories() {
        if (!selectedCategoriesList || !categorySelect) return;
        
        selectedCategoriesList.innerHTML = '';
        selectedCategories.existing = [];

        // إضافة التصنيفات المختارة من القائمة
        Array.from(categorySelect.selectedOptions).forEach(option => {
            const categoryId = option.value;
            const categoryName = option.text;
            // التأكد من أن القيمة ليست مؤقتة
            if (!categoryId.startsWith('temp_')) {
                selectedCategories.existing.push(categoryId);
                addCategoryTag(categoryId, categoryName, 'existing');
            }
        });

        updateHiddenInputs();
    }

    // إضافة tag للتصنيف
    function addCategoryTag(id, name, type) {
        const tag = document.createElement('span');
        tag.className = 'badge badge-primary p-2 d-inline-flex align-items-center';
        tag.innerHTML = `
            ${name}
            <button type="button" class="btn btn-sm btn-link text-white p-0 ml-2" onclick="removeCategory('${id}', '${type}')" style="line-height: 1;">
                <i class="fas fa-times"></i>
            </button>
        `;
        selectedCategoriesList.appendChild(tag);
    }

    // إزالة تصنيف
    window.removeCategory = function(id, type) {
        if (type === 'existing') {
            const option = categorySelect.querySelector(`option[value="${id}"]`);
            if (option) {
                option.selected = false;
            }
            selectedCategories.existing = selectedCategories.existing.filter(catId => catId !== id);
            updateSelectedCategories();
        }
    };

    // تحديث الحقول المخفية في النموذج (لم يعد ضرورياً لأننا نستخدم name مباشرة على select)
    window.updateHiddenInputs = function() {
        // التأكد من إزالة أي خيارات مؤقتة قبل الإرسال
        const tempOptions = categorySelect.querySelectorAll('option[value^="temp_"]');
        tempOptions.forEach(option => option.remove());
        
        // Debug: عرض البيانات المرسلة
        const selectedOptions = Array.from(categorySelect.selectedOptions);
        const selectedIds = selectedOptions.map(opt => opt.value).filter(id => !id.startsWith('temp_'));
        console.log('Selected categories:', selectedIds);
        console.log('Select element name:', categorySelect.name);
    }

    // تحديث عند تغيير الاختيار في القائمة
    categorySelect.addEventListener('change', updateSelectedCategories);

    // تهيئة التصنيفات المختارة مسبقاً
    updateSelectedCategories();
    
    // التأكد من تنظيف الخيارات المؤقتة قبل الإرسال
    const submitBtn = document.getElementById('submit_competition_btn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            // إزالة أي خيارات مؤقتة قبل الإرسال
            updateHiddenInputs();
        });
    }
    
    // أيضاً عند إرسال النموذج مباشرة
    if (form) {
        form.addEventListener('submit', function(e) {
            // إزالة أي خيارات مؤقتة قبل الإرسال
            const tempOptions = categorySelect.querySelectorAll('option[value^="temp_"]');
            tempOptions.forEach(option => option.remove());
        });
    }
});

// إنشاء فئة سريع via AJAX (نفس الطريقة المستخدمة في articles)
$('#quickCategoryForm').on('submit', function(e) {
    e.preventDefault();
    const form = this;
    const fd = new FormData(form);
    const btn = $('#quickCategorySubmit');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> جارٍ الحفظ...');

    $.ajax({
        url: "{{ route('categories.quick-store') }}",
        method: "POST",
        data: fd,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(resp) {
            if (resp.ok && resp.category) {
                // إضافة الفئة الجديدة للقائمة المنسدلة
                const categorySelect = document.getElementById('category_select');
                if (categorySelect) {
                    // التحقق من عدم وجود التصنيف في القائمة
                    const existingOption = categorySelect.querySelector(`option[value="${resp.category.id}"]`);
                    if (!existingOption) {
                        const option = document.createElement('option');
                        option.value = resp.category.id;
                        option.text = resp.category.name;
                        option.selected = true;
                        categorySelect.appendChild(option);
                    } else {
                        // إذا كان موجوداً، حدده فقط
                        existingOption.selected = true;
                    }
                    
                    // إضافة للقائمة المختارة مباشرة
                    if (typeof selectedCategories !== 'undefined' && selectedCategories.existing) {
                        if (!selectedCategories.existing.includes(resp.category.id.toString())) {
                            selectedCategories.existing.push(resp.category.id.toString());
                        }
                    }
                    
                    // تحديث التصنيفات المختارة والحقول المخفية
                    if (typeof updateSelectedCategories === 'function') {
                        updateSelectedCategories();
                    }
                }
                
                // إغلاق الـ modal وإعادة تعيين النموذج
                $('#quickCategoryModal').modal('hide');
                form.reset();
                $('.custom-file-label').text('اختر صورة...');
                
                // رسالة نجاح
                if (typeof toastr !== 'undefined') {
                    toastr.success('تم إنشاء التصنيف بنجاح');
                } else {
                    alert('تم إنشاء التصنيف بنجاح');
                }
            } else {
                alert('حدث خطأ أثناء إنشاء التصنيف');
            }
        },
        error: function(xhr) {
            let message = 'تعذر إنشاء التصنيف. تحقق من المدخلات.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                message = Object.values(xhr.responseJSON.errors).flat().join('\n');
            }
            alert(message);
        },
        complete: function() {
            btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> حفظ الفئة');
        }
    });
});
</script>
@endpush
@endsection
