<!-- مودال إضافة دور جديد -->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleModalLabel">إضافة دور جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">اسم الدور</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">الوصف (اختياري)</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>الصلاحيات</label>
                        <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                            @foreach($permissions as $group => $groupPermissions)
                                <div class="mb-3">
                                    <h6 class="font-weight-bold text-primary mb-2">
                                        <i class="fas fa-folder"></i> {{ ucfirst($group) }}
                                    </h6>
                                    <div class="row">
                                        @foreach($groupPermissions as $permission)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                        name="permissions[]" 
                                                        value="{{ $permission->id }}" 
                                                        id="perm_create_{{ $permission->id }}">
                                                    <label class="form-check-label" for="perm_create_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                        <small class="form-text text-muted">
                            <button type="button" class="btn btn-sm btn-link p-0" onclick="selectAllPermissions('create')">تحديد الكل</button> |
                            <button type="button" class="btn btn-sm btn-link p-0" onclick="deselectAllPermissions('create')">إلغاء تحديد الكل</button>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
