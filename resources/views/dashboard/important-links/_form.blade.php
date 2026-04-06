@php
    $link = $importantLink ?? null;
    $isEdit = $link !== null;
@endphp

<div class="form-group">
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

<div class="form-group">
    <label for="type" class="font-weight-bold">النوع</label>
    <select name="type" id="type" class="form-control">
        <option value="website" {{ old('type', $link->type ?? 'website') === 'website' ? 'selected' : '' }}>موقع</option>
        <option value="app" {{ old('type', $link->type ?? '') === 'app' ? 'selected' : '' }}>تطبيق</option>
    </select>
</div>

<div class="form-group">
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

<div id="app_urls_box" class="d-none border rounded p-3 mb-3 bg-light">
    <p class="small font-weight-bold text-muted mb-2">روابط المتاجر (للتطبيق — يكفي رابط واحد من العام أو iOS أو أندرويد)</p>
    <div class="form-group mb-2">
        <label for="url_ios" class="font-weight-bold">رابط iOS (App Store)</label>
        <input type="url" name="url_ios" id="url_ios" class="form-control @error('url_ios') is-invalid @enderror"
               value="{{ old('url_ios', $link->url_ios ?? '') }}" placeholder="https://apps.apple.com/..." maxlength="500">
        @error('url_ios')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group mb-0">
        <label for="url_android" class="font-weight-bold">رابط أندرويد (Google Play)</label>
        <input type="url" name="url_android" id="url_android" class="form-control @error('url_android') is-invalid @enderror"
               value="{{ old('url_android', $link->url_android ?? '') }}" placeholder="https://play.google.com/..." maxlength="500">
        @error('url_android')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-group">
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

<div class="form-group">
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

@if($isEdit)
    <div class="form-group">
        <label class="font-weight-bold d-block">الوسائط الحالية</label>
        <p class="small text-muted mb-2">حدد «حذف» لإزالة ملف من السيرفر عند الحفظ.</p>
        @if($link->media->isEmpty() && !$link->image)
            <p class="text-muted small mb-0">لا توجد وسائط مرفوعة.</p>
        @else
            <div class="border rounded p-3 bg-light">
                @foreach($link->media as $m)
                    <div class="d-flex align-items-start flex-wrap mb-3 pb-3 border-bottom">
                        <label class="mb-0 mr-3 d-flex align-items-center">
                            <input type="checkbox" name="delete_media_ids[]" value="{{ $m->id }}" class="mr-2"> حذف
                        </label>
                        <div class="flex-grow-1">
                            @if($m->kind === 'video')
                                <video controls class="rounded border bg-dark" style="max-width:100%;max-height:200px;" src="{{ $m->file_url }}"></video>
                                <span class="badge badge-dark ml-2">فيديو</span>
                            @else
                                <img src="{{ $m->file_url }}" alt="" class="img-thumbnail mr-2" style="max-height:120px;max-width:120px;object-fit:cover;">
                            @endif
                            @if($m->title)<p class="small font-weight-bold mb-0 mt-2">{{ $m->title }}</p>@endif
                            @if($m->description)<p class="small text-muted mb-0">{{ $m->description }}</p>@endif
                        </div>
                    </div>
                @endforeach
                @if($link->image)
                    <p class="small text-muted mb-0"><i class="fas fa-image mr-1"></i> صورة غلاف إضافية (عمود قديم): يمكن استبدالها من الحقل أدناه.</p>
                @endif
            </div>
        @endif
    </div>
@endif

<div class="form-group">
    <label class="font-weight-bold d-block">إضافة وسائط جديدة (صور / فيديو)</label>
    <small class="form-text text-muted d-block mb-2">اختياري — عنوان ووصف لكل ملف.</small>
    <div id="media_new_container"></div>
    <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="addMediaRowBtn">
        <i class="fas fa-plus mr-1"></i>إضافة ملف وسائط
    </button>
</div>

<div class="form-group">
    <label for="image" class="font-weight-bold">صورة غلاف إضافية (اختياري — الطريقة القديمة)</label>
    <input type="file" name="image" id="image" accept="image/*" class="form-control-file">
    @error('image')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

@if($isEdit)
    <div class="form-group">
        <label for="status" class="font-weight-bold">الحالة</label>
        <select name="status" id="status" class="form-control">
            <option value="pending" {{ old('status', $link->status) === 'pending' ? 'selected' : '' }}>بانتظار الموافقة</option>
            <option value="approved" {{ old('status', $link->status) === 'approved' ? 'selected' : '' }}>معتمد</option>
        </select>
    </div>
@endif

<div class="form-group">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
               @checked(old('is_active', $isEdit ? $link->is_active : true))>
        <label class="form-check-label" for="is_active">رابط نشط</label>
    </div>
</div>

<div class="form-group mb-0">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="open_in_new_tab" id="open_in_new_tab" value="1"
               @checked(old('open_in_new_tab', $isEdit ? $link->open_in_new_tab : true))>
        <label class="form-check-label" for="open_in_new_tab">فتح في تبويب جديد</label>
    </div>
</div>
