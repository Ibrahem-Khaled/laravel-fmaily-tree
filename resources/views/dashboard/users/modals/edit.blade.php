{{-- مودال تعديل المستخدم --}}
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">
                    <i class="fas fa-user-edit"></i> تعديل المستخدم: {{ $user->name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        {{-- الاسم --}}
                        <div class="col-md-6 mb-3">
                            <label for="edit_name_{{ $user->id }}" class="form-label">
                                <i class="fas fa-user"></i> الاسم الكامل <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="edit_name_{{ $user->id }}" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- البريد الإلكتروني --}}
                        <div class="col-md-6 mb-3">
                            <label for="edit_email_{{ $user->id }}" class="form-label">
                                <i class="fas fa-envelope"></i> البريد الإلكتروني <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="edit_email_{{ $user->id }}" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- كلمة المرور الجديدة (اختيارية) --}}
                        <div class="col-md-6 mb-3">
                            <label for="edit_password_{{ $user->id }}" class="form-label">
                                <i class="fas fa-lock"></i> كلمة المرور الجديدة
                                <small class="text-muted">(اتركها فارغة للاحتفاظ بالكلمة الحالية)</small>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="edit_password_{{ $user->id }}" name="password">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('edit_password_{{ $user->id }}')">
                                        <i class="fas fa-eye" id="edit_password_{{ $user->id }}_icon"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- تأكيد كلمة المرور --}}
                        <div class="col-md-6 mb-3">
                            <label for="edit_password_confirmation_{{ $user->id }}" class="form-label">
                                <i class="fas fa-lock"></i> تأكيد كلمة المرور الجديدة
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control"
                                       id="edit_password_confirmation_{{ $user->id }}" name="password_confirmation">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('edit_password_confirmation_{{ $user->id }}')">
                                        <i class="fas fa-eye" id="edit_password_confirmation_{{ $user->id }}_icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- الأدوار --}}
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-user-tag"></i> الأدوار <span class="text-danger">*</span>
                            </label>
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="roles[]" value="{{ $role->name }}"
                                                   id="edit_role_{{ $user->id }}_{{ $role->id }}"
                                                   {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_role_{{ $user->id }}_{{ $role->id }}">
                                                <span class="badge badge-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'super_admin' ? 'warning' : 'info') }}">
                                                    {{ \App\Support\TranslationHelper::userRole($role->name) }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- تفعيل الحساب --}}
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input type="hidden" name="email_verified" value="0">
                                <input class="form-check-input" type="checkbox"
                                       name="email_verified" value="1"
                                       id="edit_email_verified_{{ $user->id }}"
                                       {{ $user->email_verified_at ? 'checked' : '' }}>
                                <label class="form-check-label" for="edit_email_verified_{{ $user->id }}">
                                    <i class="fas fa-check-circle text-success"></i> تفعيل الحساب
                                </label>
                            </div>
                        </div>

                        {{-- معلومات إضافية --}}
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> معلومات المستخدم</h6>
                                <small>
                                    <strong>تاريخ الإنشاء:</strong> {{ $user->created_at->format('Y-m-d H:i') }}<br>
                                    <strong>آخر تحديث:</strong> {{ $user->updated_at->format('Y-m-d H:i') }}<br>
                                    <strong>معرف المستخدم:</strong> #{{ $user->id }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '_icon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
