<div class="modal fade" id="quickCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="quickCategoryForm" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إنشاء فئة جديدة</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="form-group">
                    <label>الاسم <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required maxlength="255">
                </div>
                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>فئة أب (اختياري)</label>
                    <select name="parent_id" class="form-control">
                        <option value="">— بدون —</option>
                        @foreach (\App\Models\Category::orderBy('sort_order')->orderBy('name')->get() as $pc)
                            <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>الترتيب</label>
                    <input type="number" name="sort_order" class="form-control" min="0" value="0">
                </div>
                <div class="form-group">
                    <label>صورة (اختياري)</label>
                    <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="catImgInput">
                        <label class="custom-file-label" for="catImgInput">اختر صورة...</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button id="quickCategorySubmit" type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ
                    الفئة</button>
            </div>
        </form>
    </div>
</div>
