<!-- مودال تعديل الدور -->
<div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editRoleModalLabel{{ $role->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel{{ $role->id }}">تعديل الدور: {{ $role->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name{{ $role->id }}">اسم الدور</label>
                        <input type="text" class="form-control" id="name{{ $role->id }}" name="name"
                            value="{{ $role->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description{{ $role->id }}">الوصف (اختياري)</label>
                        <textarea class="form-control" id="description{{ $role->id }}" name="description" rows="3">{{ $role->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="is_active{{ $role->id }}">الحالة</label>
                        <select class="form-control" id="is_active{{ $role->id }}" name="is_active">
                            <option value="1" {{ $role->is_active ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ !$role->is_active ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
