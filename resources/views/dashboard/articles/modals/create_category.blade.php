<div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة فئة جديدة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="category-ajax-alert"></div>
                <div class="form-group">
                    <label>اسم الفئة</label>
                    <input type="text" id="new_category_name" class="form-control">
                </div>
                <div class="form-group">
                    <label>فئة رئيسية (اختياري)</label>
                    <select id="new_category_parent" class="form-control">
                        <option value="">-- بدون فئة رئيسية --</option>
                        {{-- يتم تعبئة الخيارات من القائمة الرئيسية عبر JS --}}
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary" id="save-category-ajax-btn">حفظ الفئة</button>
            </div>
        </div>
    </div>
</div>
