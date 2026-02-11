{{-- مودال عرض تفاصيل المستخدم --}}
<div class="modal fade" id="showUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="showUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="showUserModalLabel{{ $user->id }}">
                    <i class="fas fa-user"></i> تفاصيل المستخدم: {{ $user->name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{-- معلومات أساسية --}}
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle"></i> المعلومات الأساسية
                        </h6>

                        <div class="mb-3">
                            <label class="font-weight-bold">الاسم الكامل:</label>
                            <p class="text-muted">{{ $user->name }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="font-weight-bold">البريد الإلكتروني:</label>
                            <p class="text-muted">
                                <i class="fas fa-envelope"></i> {{ $user->email ?? '-' }}
                                @if($user->email_verified_at)
                                    <span class="badge badge-success ml-2">مفعل</span>
                                @else
                                    <span class="badge badge-warning ml-2">غير مفعل</span>
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="font-weight-bold">رقم الهاتف:</label>
                            <p class="text-muted">
                                <i class="fas fa-phone"></i> {{ $user->phone ?? '-' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="font-weight-bold">العنوان:</label>
                            <p class="text-muted">
                                <i class="fas fa-map-marker-alt"></i> {{ $user->address ?? '-' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="font-weight-bold">العمر:</label>
                            <p class="text-muted">
                                <i class="fas fa-birthday-cake"></i> {{ $user->age ?? '-' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="font-weight-bold">الصورة الشخصية:</label>
                            <p class="text-muted">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                                         class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                @else
                                    <span class="text-muted">لا توجد صورة</span>
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="font-weight-bold">معرف المستخدم:</label>
                            <p class="text-muted">#{{ $user->id }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="font-weight-bold">تاريخ الإنشاء:</label>
                            <p class="text-muted">
                                <i class="fas fa-calendar"></i> {{ $user->created_at->format('Y-m-d H:i') }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="font-weight-bold">آخر تحديث:</label>
                            <p class="text-muted">
                                <i class="fas fa-clock"></i> {{ $user->updated_at->format('Y-m-d H:i') }}
                            </p>
                        </div>
                    </div>

                    {{-- الأدوار والصلاحيات --}}
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user-shield"></i> الأدوار والصلاحيات
                        </h6>

                        <div class="mb-3">
                            <label class="font-weight-bold">الأدوار:</label>
                            <div>
                                @forelse($user->roles as $role)
                                    <span class="badge badge-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'super_admin' ? 'warning' : 'info') }} mr-1 mb-1">
                                        {{ \App\Support\TranslationHelper::userRole($role->name) }}
                                    </span>
                                @empty
                                    <span class="text-muted">لا توجد أدوار</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="font-weight-bold">الصلاحيات:</label>
                            <div>
                                @forelse($user->getAllPermissions() as $permission)
                                    <span class="badge badge-light mr-1 mb-1">{{ $permission->name }}</span>
                                @empty
                                    <span class="text-muted">لا توجد صلاحيات مباشرة</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="font-weight-bold">حالة الحساب:</label>
                            <div>
                                @if($user->email_verified_at)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> مفعل
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle"></i> غير مفعل
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- إحصائيات إضافية --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-chart-bar"></i> إحصائيات المستخدم
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-calendar-alt text-primary fa-2x mb-2"></i>
                                        <h6>عضو منذ</h6>
                                        <p class="mb-0">{{ $user->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-clock text-info fa-2x mb-2"></i>
                                        <h6>آخر نشاط</h6>
                                        <p class="mb-0">{{ $user->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-tag text-success fa-2x mb-2"></i>
                                        <h6>عدد الأدوار</h6>
                                        <p class="mb-0">{{ $user->roles->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> إغلاق
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#editUserModal{{ $user->id }}">
                    <i class="fas fa-edit"></i> تعديل المستخدم
                </button>
            </div>
        </div>
    </div>
</div>
