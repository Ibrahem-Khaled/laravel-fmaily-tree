<div class="modal fade" id="createPadgeModal" tabindex="-1" role="dialog" aria-labelledby="createPadgeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPadgeModalLabel">إضافة شارة جديدة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('padges.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">اسم الشارة <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">الوصف</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color">اللون</label>
                                <input type="color" class="form-control" id="color" name="color"
                                    value="{{ old('color', '#6c757d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sort_order">ترتيب العرض</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order"
                                    value="{{ old('sort_order', 0) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>صورة الشارة</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image"
                                accept="image/*">
                            <label class="custom-file-label" for="image">اختر صورة...</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active_create" name="is_active"
                                value="1" checked>
                            <label class="custom-control-label" for="is_active_create">تفعيل الشارة</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
