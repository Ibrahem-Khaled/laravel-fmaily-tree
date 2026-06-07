<template id="importantLinkMediaRowTpl">
    <div class="media-row border rounded-xl p-3 mb-3 bg-white" style="border-color: var(--premium-border) !important;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="small font-weight-bold text-success"><i class="fas fa-file-image ml-1"></i>ملف وسائط جديد</span>
            <button type="button" class="btn btn-sm btn-outline-danger remove-media-row" tabindex="-1"><i class="fas fa-trash-alt"></i></button>
        </div>
        <div class="row text-right" dir="rtl">
            <div class="col-md-6 form-group mb-3">
                <label class="small font-weight-bold mb-1">اختر الملف</label>
                <input type="file" name="media_files[]" class="form-control-file">
            </div>
            <div class="col-md-6 form-group mb-3">
                <label class="small font-weight-bold mb-1">نوع الملف</label>
                <select name="media_kinds[]" class="form-control form-control-sm">
                    <option value="image">صورة</option>
                    <option value="video">فيديو</option>
                </select>
            </div>
            <div class="col-md-12 form-group mb-3">
                <label class="small font-weight-bold mb-1">عنوان الملف (اختياري)</label>
                <input type="text" name="media_titles[]" class="form-control form-control-sm" maxlength="255" placeholder="أدخل عنواناً للملف...">
            </div>
            <div class="col-md-12 form-group mb-0">
                <label class="small font-weight-bold mb-1">وصف الملف (اختياري)</label>
                <textarea name="media_descriptions[]" class="form-control form-control-sm" rows="2" maxlength="2000" placeholder="أدخل وصفاً للملف..."></textarea>
            </div>
        </div>
    </div>
</template>
