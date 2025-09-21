<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">إدارة الرضاعة</h2>
                    <p class="text-muted mb-0">إدارة علاقات الرضاعة بين الأمهات المرضعات والأطفال المرتضعين</p>
                </div>
                <button wire:click="openCreateModal" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>إضافة علاقة رضاعة جديدة
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" wire:model.live="search" class="form-control" placeholder="البحث في الأمهات المرضعات أو الأطفال المرتضعين...">
            </div>
        </div>
        <div class="col-md-3">
            <select wire:model.live="statusFilter" class="form-select">
                <option value="all">جميع الحالات</option>
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
            </select>
        </div>
        <div class="col-md-3">
            <div class="d-flex gap-2">
                <button wire:click="sortBy('created_at')" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-sort me-1"></i>تاريخ الإضافة
                </button>
                <button wire:click="sortBy('start_date')" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-sort me-1"></i>تاريخ البداية
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Breastfeeding Relationships Table -->
    <div class="card">
        <div class="card-body">
            @if($breastfeedings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>الأم المرضعة</th>
                                <th>الطفل المرتضع</th>
                                <th>تاريخ البداية</th>
                                <th>تاريخ النهاية</th>
                                <th>المدة</th>
                                <th>الحالة</th>
                                <th>الملاحظات</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($breastfeedings as $breastfeeding)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $breastfeeding->nursingMother->avatar }}"
                                                 alt="{{ $breastfeeding->nursingMother->first_name }}"
                                                 class="rounded-circle me-2"
                                                 width="32" height="32">
                                            <div>
                                                <div class="fw-semibold">{{ $breastfeeding->nursingMother->first_name }}</div>
                                                @if($breastfeeding->nursingMother->last_name)
                                                    <small class="text-muted">{{ $breastfeeding->nursingMother->last_name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $breastfeeding->breastfedChild->avatar }}"
                                                 alt="{{ $breastfeeding->breastfedChild->first_name }}"
                                                 class="rounded-circle me-2"
                                                 width="32" height="32">
<div>
                                                <div class="fw-semibold">{{ $breastfeeding->breastfedChild->first_name }}</div>
                                                @if($breastfeeding->breastfedChild->last_name)
                                                    <small class="text-muted">{{ $breastfeeding->breastfedChild->last_name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($breastfeeding->start_date)
                                            <span class="badge bg-info">{{ $breastfeeding->start_date->format('Y-m-d') }}</span>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($breastfeeding->end_date)
                                            <span class="badge bg-warning">{{ $breastfeeding->end_date->format('Y-m-d') }}</span>
                                        @else
                                            <span class="text-muted">مستمر</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($breastfeeding->duration_in_months)
                                            <span class="badge bg-success">{{ $breastfeeding->duration_in_months }} شهر</span>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($breastfeeding->is_active)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($breastfeeding->notes)
                                            <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                                  title="{{ $breastfeeding->notes }}">
                                                {{ Str::limit($breastfeeding->notes, 50) }}
                                            </span>
                                        @else
                                            <span class="text-muted">لا توجد ملاحظات</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button wire:click="edit({{ $breastfeeding->id }})"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="toggleStatus({{ $breastfeeding->id }})"
                                                    class="btn btn-sm btn-outline-{{ $breastfeeding->is_active ? 'warning' : 'success' }}"
                                                    title="{{ $breastfeeding->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                <i class="fas fa-{{ $breastfeeding->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                            <button wire:click="delete({{ $breastfeeding->id }})"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $breastfeedings->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-baby fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">لا توجد علاقات رضاعة</h4>
                    <p class="text-muted">ابدأ بإضافة علاقة رضاعة جديدة</p>
                    <button wire:click="openCreateModal" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>إضافة علاقة رضاعة جديدة
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">إضافة علاقة رضاعة جديدة</h5>
                        <button type="button" wire:click="closeModals" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="create">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nursing_mother_id" class="form-label">الأم المرضعة <span class="text-danger">*</span></label>
                                    <select wire:model="nursing_mother_id" class="form-select @error('nursing_mother_id') is-invalid @enderror" id="nursing_mother_id">
                                        <option value="">اختر الأم المرضعة</option>
                                        @foreach($nursingMothers as $mother)
                                            <option value="{{ $mother->id }}">{{ $mother->first_name }} {{ $mother->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('nursing_mother_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="breastfed_child_id" class="form-label">الطفل المرتضع <span class="text-danger">*</span></label>
                                    <select wire:model="breastfed_child_id" class="form-select @error('breastfed_child_id') is-invalid @enderror" id="breastfed_child_id">
                                        <option value="">اختر الطفل المرتضع</option>
                                        @foreach($breastfedChildren as $child)
                                            <option value="{{ $child->id }}">{{ $child->first_name }} {{ $child->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('breastfed_child_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">تاريخ البداية</label>
                                    <input type="date" wire:model="start_date" class="form-control @error('start_date') is-invalid @enderror" id="start_date">
                                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">تاريخ النهاية</label>
                                    <input type="date" wire:model="end_date" class="form-control @error('end_date') is-invalid @enderror" id="end_date">
                                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">ملاحظات</label>
                                <textarea wire:model="notes" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3" placeholder="أي ملاحظات إضافية حول علاقة الرضاعة..."></textarea>
                                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input wire:model="is_active" class="form-check-input" type="checkbox" id="is_active">
                                    <label class="form-check-label" for="is_active">
                                        تفعيل العلاقة
                                    </label>
                                </div>
                            </div>
                            @error('relationship') <div class="alert alert-danger">{{ $message }}</div> @enderror
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModals" class="btn btn-secondary">إلغاء</button>
                        <button type="button" wire:click="create" class="btn btn-primary">إضافة</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">تعديل علاقة الرضاعة</h5>
                        <button type="button" wire:click="closeModals" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="update">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_nursing_mother_id" class="form-label">الأم المرضعة <span class="text-danger">*</span></label>
                                    <select wire:model="nursing_mother_id" class="form-select @error('nursing_mother_id') is-invalid @enderror" id="edit_nursing_mother_id">
                                        <option value="">اختر الأم المرضعة</option>
                                        @foreach($nursingMothers as $mother)
                                            <option value="{{ $mother->id }}">{{ $mother->first_name }} {{ $mother->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('nursing_mother_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_breastfed_child_id" class="form-label">الطفل المرتضع <span class="text-danger">*</span></label>
                                    <select wire:model="breastfed_child_id" class="form-select @error('breastfed_child_id') is-invalid @enderror" id="edit_breastfed_child_id">
                                        <option value="">اختر الطفل المرتضع</option>
                                        @foreach($breastfedChildren as $child)
                                            <option value="{{ $child->id }}">{{ $child->first_name }} {{ $child->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('breastfed_child_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_start_date" class="form-label">تاريخ البداية</label>
                                    <input type="date" wire:model="start_date" class="form-control @error('start_date') is-invalid @enderror" id="edit_start_date">
                                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_end_date" class="form-label">تاريخ النهاية</label>
                                    <input type="date" wire:model="end_date" class="form-control @error('end_date') is-invalid @enderror" id="edit_end_date">
                                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_notes" class="form-label">ملاحظات</label>
                                <textarea wire:model="notes" class="form-control @error('notes') is-invalid @enderror" id="edit_notes" rows="3" placeholder="أي ملاحظات إضافية حول علاقة الرضاعة..."></textarea>
                                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input wire:model="is_active" class="form-check-input" type="checkbox" id="edit_is_active">
                                    <label class="form-check-label" for="edit_is_active">
                                        تفعيل العلاقة
                                    </label>
                                </div>
                            </div>
                            @error('relationship') <div class="alert alert-danger">{{ $message }}</div> @enderror
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModals" class="btn btn-secondary">إلغاء</button>
                        <button type="button" wire:click="update" class="btn btn-primary">تحديث</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">تأكيد الحذف</h5>
                        <button type="button" wire:click="closeModals" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد من أنك تريد حذف علاقة الرضاعة هذه؟</p>
                        <p class="text-muted">هذا الإجراء لا يمكن التراجع عنه.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModals" class="btn btn-secondary">إلغاء</button>
                        <button type="button" wire:click="confirmDelete" class="btn btn-danger">حذف</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
