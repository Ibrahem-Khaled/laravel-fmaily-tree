<template id="importantLinkMediaRowTpl">
    <div class="media-row border rounded p-3 mb-2 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small font-weight-bold text-secondary">ملف وسائط</span>
            <button type="button" class="btn btn-sm btn-outline-danger remove-media-row" tabindex="-1"><i class="fas fa-times"></i></button>
        </div>
        <div class="form-group mb-2">
            <input type="file" name="media_files[]" class="form-control-file">
        </div>
        <div class="form-group mb-2">
            <label class="small font-weight-bold mb-0">النوع</label>
            <select name="media_kinds[]" class="form-control form-control-sm">
                <option value="image">صورة</option>
                <option value="video">فيديو</option>
            </select>
        </div>
        <div class="form-group mb-2">
            <label class="small font-weight-bold mb-0">عنوان (اختياري)</label>
            <input type="text" name="media_titles[]" class="form-control form-control-sm" maxlength="255" placeholder="عنوان">
        </div>
        <div class="form-group mb-0">
            <label class="small font-weight-bold mb-0">وصف (اختياري)</label>
            <textarea name="media_descriptions[]" class="form-control form-control-sm" rows="2" maxlength="2000" placeholder="وصف"></textarea>
        </div>
    </div>
</template>
