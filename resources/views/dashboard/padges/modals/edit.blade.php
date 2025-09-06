<div class="modal fade" id="editPadgeModal{{ $padge->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editPadgeModalLabel{{ $padge->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPadgeModalLabel{{ $padge->id }}">تعديل الشارة: {{ $padge->name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('padges.update', $padge->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name{{ $padge->id }}">اسم الشارة <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name{{ $padge->id }}" name="name"
                            value="{{ old('name', $padge->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description{{ $padge->id }}">الوصف</label>
                        <textarea class="form-control" id="description{{ $padge->id }}" name="description" rows="3">{{ old('description', $padge->description) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color{{ $padge->id }}">اللون</label>
                                <input type="color" class="form-control" id="color{{ $padge->id }}" name="color"
                                    value="{{ old('color', $padge->color) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sort_order{{ $padge->id }}">ترتيب العرض</label>
                                <input type="number" class="form-control" id="sort_order{{ $padge->id }}"
                                    name="sort_order" value="{{ old('sort_order', $padge->sort_order) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>الصورة الحالية</label>
                        <div>
                            <img src="{{ $padge->image ? asset('storage/' . $padge->image) : asset('img/default-badge.png') }}"
                                alt="{{ $padge->name }}" class="rounded mb-2" width="80" height="80"
                                style="object-fit: cover;">
                        </div>
                        <label>تغيير الصورة</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image{{ $padge->id }}"
                                name="image" accept="image/*">
                            <label class="custom-file-label" for="image{{ $padge->id }}">اختر صورة جديدة...</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active_edit{{ $padge->id }}"
                                name="is_active" value="1" {{ $padge->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active_edit{{ $padge->id }}">تفعيل
                                الشارة</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">تحديث</button>
                </div>
            </form>
        </div>
    </div>
</div>
