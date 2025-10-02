{{-- مودال إضافة مستخدم --}}
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">
                    <i class="fas fa-user-plus"></i> إضافة مستخدم جديد
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        {{-- الاسم --}}
                        <div class="col-md-6 mb-3">
                            <label for="create_name" class="form-label">
                                <i class="fas fa-user"></i> الاسم الكامل <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="create_name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- البريد الإلكتروني --}}
                        <div class="col-md-6 mb-3">
                            <label for="create_email" class="form-label">
                                <i class="fas fa-envelope"></i> البريد الإلكتروني <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="create_email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- كلمة المرور --}}
                        <div class="col-md-6 mb-3">
                            <label for="create_password" class="form-label">
                                <i class="fas fa-lock"></i> كلمة المرور <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="create_password" name="password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('create_password')">
                                        <i class="fas fa-eye" id="create_password_icon"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- تأكيد كلمة المرور --}}
                        <div class="col-md-6 mb-3">
                            <label for="create_password_confirmation" class="form-label">
                                <i class="fas fa-lock"></i> تأكيد كلمة المرور <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control"
                                       id="create_password_confirmation" name="password_confirmation" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('create_password_confirmation')">
                                        <i class="fas fa-eye" id="create_password_confirmation_icon"></i>
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
                                                   id="create_role_{{ $role->id }}"
                                                   {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="create_role_{{ $role->id }}">
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
                                       id="create_email_verified" checked>
                                <label class="form-check-label" for="create_email_verified">
                                    <i class="fas fa-check-circle text-success"></i> تفعيل الحساب فوراً
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ المستخدم
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
