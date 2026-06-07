@php
    $link = $importantLink ?? null;
    $isEdit = $link !== null;
@endphp

<div class="row text-right" dir="rtl">
    <div class="col-md-6 form-group">
        <label for="title" class="font-weight-bold">
            العنوان <span class="text-danger">*</span>
        </label>
        <input type="text" name="title" id="title"
               class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $link->title ?? '') }}"
               placeholder="أدخل عنوان الرابط..."
               required maxlength="255">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 form-group">
        <label for="category_id" class="font-weight-bold">الفئة</label>
        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
            <option value="">-- بدون فئة (عامة) --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $link->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row text-right" dir="rtl">
    <div class="{{ $isEdit ? 'col-md-4' : 'col-md-6' }} form-group">
        <label for="type" class="font-weight-bold">النوع</label>
        <select name="type" id="type" class="form-control">
            <option value="website" {{ old('type', $link->type ?? 'website') === 'website' ? 'selected' : '' }}>موقع</option>
            <option value="app" {{ old('type', $link->type ?? '') === 'app' ? 'selected' : '' }}>تطبيق</option>
        </select>
    </div>

    <div class="{{ $isEdit ? 'col-md-4' : 'col-md-6' }} form-group">
        <label for="icon" class="font-weight-bold">الأيقونة (اختياري)</label>
        <input type="text" name="icon" id="icon"
               class="form-control @error('icon') is-invalid @enderror"
               value="{{ old('icon', $link->icon ?? 'fas fa-link') }}"
               placeholder="fas fa-link"
               maxlength="100">
        <small class="form-text text-muted">Font Awesome مثل: fas fa-link</small>
        @error('icon')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if($isEdit)
        <div class="col-md-4 form-group">
            <label for="status" class="font-weight-bold">الحالة</label>
            <select name="status" id="status" class="form-control">
                <option value="pending" {{ old('status', $link->status) === 'pending' ? 'selected' : '' }}>بانتظار الموافقة</option>
                <option value="approved" {{ old('status', $link->status) === 'approved' ? 'selected' : '' }}>معتمد</option>
            </select>
        </div>
    @endif
</div>

<div class="row text-right" dir="rtl">
    <div class="col-md-12 form-group">
        <label for="url" class="font-weight-bold" id="url_label_main">
            الرابط <span class="text-danger" id="url_required_mark">*</span>
        </label>
        <input type="url" name="url" id="url"
               class="form-control @error('url') is-invalid @enderror"
               value="{{ old('url', $link->url ?? '') }}"
               placeholder="https://example.com"
               maxlength="500">
        <small class="form-text text-muted" id="url_hint_website">رابط الموقع الرئيسي.</small>
        <small class="form-text text-muted d-none" id="url_hint_app">رابط عام اختياري (صفحة هبوط أو موقع المطوّر).</small>
        @error('url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div id="app_urls_box" class="d-none border rounded-xl p-3 mb-3 bg-light text-right" style="border-color: var(--premium-border) !important;" dir="rtl">
    <p class="small font-weight-bold text-muted mb-3"><i class="fas fa-store-alt ml-1"></i>روابط المتاجر (للتطبيق — يكفي رابط واحد من العام أو iOS أو أندرويد)</p>
    <div class="row">
        <div class="col-md-6 form-group mb-2">
            <label for="url_ios" class="font-weight-bold">رابط iOS (App Store)</label>
            <input type="url" name="url_ios" id="url_ios" class="form-control @error('url_ios') is-invalid @enderror"
                   value="{{ old('url_ios', $link->url_ios ?? '') }}" placeholder="https://apps.apple.com/..." maxlength="500">
            @error('url_ios')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 form-group mb-0">
            <label for="url_android" class="font-weight-bold">رابط أندرويد (Google Play)</label>
            <input type="url" name="url_android" id="url_android" class="form-control @error('url_android') is-invalid @enderror"
                   value="{{ old('url_android', $link->url_android ?? '') }}" placeholder="https://play.google.com/..." maxlength="500">
            @error('url_android')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="row text-right" dir="rtl">
    <div class="col-md-12 form-group">
        <label for="description" class="font-weight-bold">الوصف (اختياري)</label>
        <textarea name="description" id="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror"
                  placeholder="أدخل وصف للرابط..."
                  maxlength="1000">{{ old('description', $link->description ?? '') }}</textarea>
        <small class="form-text text-muted"><span id="charCount">0</span> / 1000 حرف</small>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-section-title mt-4 text-right" dir="rtl">
    <i class="fas fa-cog ml-1 text-success"></i>
    <span>الإعدادات والملحقات</span>
</div>

<div class="row text-right" dir="rtl">
    <div class="col-md-6 mb-3">
        <div class="toggle-switch-card">
            <div class="custom-control custom-switch custom-switch-premium">
                <input type="checkbox" class="custom-control-input" name="is_active" id="is_active" value="1"
                       @checked(old('is_active', $isEdit ? $link->is_active : true))>
                <label class="custom-control-label" for="is_active">تفعيل ونشر الرابط</label>
            </div>
            <span class="small text-muted"><i class="fas fa-eye ml-1"></i>يظهر للجميع</span>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="toggle-switch-card">
            <div class="custom-control custom-switch custom-switch-premium">
                <input type="checkbox" class="custom-control-input" name="open_in_new_tab" id="open_in_new_tab" value="1"
                       @checked(old('open_in_new_tab', $isEdit ? $link->open_in_new_tab : true))>
                <label class="custom-control-label" for="open_in_new_tab">فتح في نافذة جديدة</label>
            </div>
            <span class="small text-muted"><i class="fas fa-external-link-alt ml-1"></i>في المتصفح</span>
        </div>
    </div>
</div>

<div class="row text-right" dir="rtl">
    <div class="col-md-6 mb-4">
        <div class="form-group mb-0">
            <label class="font-weight-bold mb-2">صورة غلاف رئيسية (اختياري)</label>
            <div class="custom-upload-zone">
                <i class="fas fa-cloud-upload-alt text-success mb-2"></i>
                <span class="font-weight-bold text-slate-800">اسحب صورة الغلاف هنا أو انقر للاختيار</span>
                <span class="small text-muted mt-1">يدعم صيغ JPG, PNG, WebP (الحد الأقصى: 2 ميجابايت)</span>
                <input type="file" name="image" id="image" accept="image/*">
            </div>
            <small class="form-text text-muted mt-2" id="image_selected_filename" style="display:none; color: #10b981; font-weight: bold;"></small>
            @error('image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="form-group mb-0">
            <label class="font-weight-bold mb-2">وسائط ومعاينات إضافية (اختياري)</label>
            <small class="form-text text-muted mb-2">يمكنك إرفاق صور ومعاينات فيديو للرابط.</small>
            <div id="media_new_container"></div>
            <button type="button" class="btn btn-sm btn-outline-success mt-1 w-100 py-3 rounded-xl font-weight-bold" id="addMediaRowBtn">
                <i class="fas fa-plus ml-1"></i>إضافة ملف وسائط جديد
            </button>
        </div>
    </div>
</div>

@if($isEdit)
    <div class="form-group mt-3 text-right" dir="rtl">
        <label class="font-weight-bold d-block mb-3"><i class="fas fa-photo-video ml-1 text-success"></i>الوسائط الحالية المرفوعة</label>
        @if($link->media->isEmpty() && !$link->image)
            <p class="text-muted small mb-0">لا توجد وسائط مرفوعة حالياً.</p>
        @else
            <div class="row">
                @foreach($link->media as $m)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="media-preview-item">
                            <div class="rounded overflow-hidden bg-dark border text-center mb-2" style="height: 120px;">
                                @if($m->kind === 'video')
                                    <video controls class="h-100 w-100" src="{{ $m->file_url }}"></video>
                                @else
                                    <img src="{{ $m->file_url }}" alt="" class="h-100 w-100" style="object-fit: contain;">
                                @endif
                            </div>
                            <div class="text-right mb-2">
                                @if($m->title)
                                    <p class="font-weight-bold mb-1 text-slate-800 text-truncate" style="font-size: 0.9rem;">{{ $m->title }}</p>
                                @endif
                                <span class="badge badge-pill badge-light border">{{ $m->kind === 'video' ? 'فيديو' : 'صورة' }}</span>
                            </div>
                            <div class="custom-control custom-checkbox text-danger text-right pr-0">
                                <input type="checkbox" name="delete_media_ids[]" value="{{ $m->id }}" id="delete_media-{{ $m->id }}" class="custom-control-input">
                                <label class="custom-control-label text-danger font-weight-bold" for="delete_media-{{ $m->id }}">تحديد للحذف</label>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($link->image)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="media-preview-item">
                            <div class="rounded overflow-hidden bg-light border text-center mb-2" style="height: 120px;">
                                <img src="{{ asset('storage/' . $link->image) }}" alt="" class="h-100 w-100" style="object-fit: contain;">
                            </div>
                            <div class="text-right mb-2">
                                <p class="font-weight-bold mb-1 text-slate-800">صورة غلاف (عمود قديم)</p>
                                <span class="badge badge-pill badge-light border">صورة غلاف</span>
                            </div>
                            <p class="small text-muted mb-0">يمكن استبدالها برفع صورة جديدة من حقل الغلاف أعلاه.</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endif
