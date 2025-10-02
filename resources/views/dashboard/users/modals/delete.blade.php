{{-- مودال حذف المستخدم --}}
<div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">
                    <i class="fas fa-exclamation-triangle"></i> تأكيد حذف المستخدم
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h5 class="text-danger">هل أنت متأكد من حذف هذا المستخدم؟</h5>
                </div>

                {{-- معلومات المستخدم --}}
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title text-primary">
                            <i class="fas fa-user"></i> معلومات المستخدم
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>الاسم:</strong> {{ $user->name }}
                                </p>
                                <p class="mb-2">
                                    <strong>البريد الإلكتروني:</strong> {{ $user->email }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>معرف المستخدم:</strong> #{{ $user->id }}
                                </p>
                                <p class="mb-2">
                                    <strong>تاريخ الإنشاء:</strong> {{ $user->created_at->format('Y-m-d') }}
                                </p>
                            </div>
                        </div>

                        {{-- الأدوار --}}
                        <div class="mt-3">
                            <strong>الأدوار:</strong>
                            <div class="mt-1">
                                @forelse($user->roles as $role)
                                    <span class="badge badge-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'super_admin' ? 'warning' : 'info') }} mr-1">
                                        {{ \App\Support\TranslationHelper::userRole($role->name) }}
                                    </span>
                                @empty
                                    <span class="text-muted">لا توجد أدوار</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- تحذير --}}
                <div class="alert alert-warning mt-3">
                    <h6><i class="fas fa-exclamation-triangle"></i> تحذير مهم</h6>
                    <ul class="mb-0">
                        <li>هذا الإجراء لا يمكن التراجع عنه</li>
                        <li>سيتم حذف جميع البيانات المرتبطة بهذا المستخدم</li>
                        <li>سيتم إلغاء جميع الصلاحيات والأدوار</li>
                        @if($user->id === auth()->id())
                            <li class="text-danger"><strong>تحذير: أنت تحاول حذف حسابك الخاص!</strong></li>
                        @endif
                    </ul>
                </div>

                {{-- تأكيد الحذف --}}
                <div class="form-group">
                    <label for="confirm_delete_{{ $user->id }}" class="form-label">
                        اكتب <strong>"حذف"</strong> للتأكيد:
                    </label>
                    <input type="text" class="form-control" id="confirm_delete_{{ $user->id }}"
                           placeholder="اكتب 'حذف' هنا" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> إلغاء
                </button>
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" id="deleteForm{{ $user->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn{{ $user->id }}" disabled>
                        <i class="fas fa-trash"></i> حذف المستخدم
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // التحقق من النص المدخل لتأكيد الحذف
        $('#confirm_delete_{{ $user->id }}').on('input', function() {
            const inputValue = $(this).val().trim();
            const confirmBtn = $('#confirmDeleteBtn{{ $user->id }}');

            if (inputValue === 'حذف') {
                confirmBtn.prop('disabled', false);
                confirmBtn.removeClass('btn-secondary').addClass('btn-danger');
            } else {
                confirmBtn.prop('disabled', true);
                confirmBtn.removeClass('btn-danger').addClass('btn-secondary');
            }
        });

        // تأكيد إضافي قبل الحذف
        $('#deleteForm{{ $user->id }}').on('submit', function(e) {
            const userName = '{{ $user->name }}';
            const isCurrentUser = {{ $user->id === auth()->id() ? 'true' : 'false' }};

            if (isCurrentUser) {
                e.preventDefault();
                alert('لا يمكنك حذف حسابك الخاص!');
                return false;
            }

            if (!confirm(`هل أنت متأكد تماماً من حذف المستخدم "${userName}"؟\n\nهذا الإجراء لا يمكن التراجع عنه!`)) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush
