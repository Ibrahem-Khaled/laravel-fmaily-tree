@extends('layouts.app')

@section('title', 'إدارة الروابط المهمة')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-link text-primary mr-2"></i>إدارة الروابط المهمة
            </h1>
            <p class="text-muted mb-0">قم بإدارة الروابط المهمة التي تظهر في الصفحة الرئيسية</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus-circle mr-2"></i>إضافة رابط جديد
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left: 0.25rem solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الروابط
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-link fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-left: 0.25rem solid #1cc88a !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                روابط نشطة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="border-left: 0.25rem solid #f6c23e !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                روابط معطلة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-left: 0.25rem solid #36b9cc !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                بانتظار الموافقة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($pendingLinks) && $pendingLinks->count() > 0)
        <div class="card shadow-sm border-0 mb-4 border-right-info" style="border-right: 4px solid #36b9cc !important;">
            <div class="card-header bg-light">
                <h6 class="mb-0 font-weight-bold text-info">
                    <i class="fas fa-clock mr-2"></i>اقتراحات بانتظار الموافقة ({{ $pendingLinks->count() }})
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="list-group">
                    @foreach($pendingLinks as $link)
                        <div class="list-group-item mb-2 border rounded">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="mb-2 mb-md-0">
                                    <h6 class="font-weight-bold mb-1">{{ $link->title }}</h6>
                                    <p class="mb-1 text-muted small">{{ $link->url }}</p>
                                    @if($link->submitter)
                                        <p class="mb-0 text-muted small"><span class="font-weight-bold">من أضافه:</span> {{ $link->submitter->name }} — {{ $link->submitter->phone ?? '-' }}</p>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('dashboard.important-links.approve', $link) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i>اعتماد</button>
                                    </form>
                                    <form action="{{ route('dashboard.important-links.reject', $link) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رفض هذا الاقتراح؟');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times mr-1"></i>رفض</button>
                                    </form>
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="editLink({{ $link->id }})"><i class="fas fa-edit mr-1"></i>تعديل</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Links List -->
    @if($links->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-list mr-2"></i>الروابط ({{ $links->count() }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                            <i class="fas fa-save mr-1"></i>حفظ الترتيب
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="links-list" class="list-group">
                        @foreach($links as $link)
                            <div class="list-group-item list-group-item-action mb-2 border rounded shadow-sm link-item {{ !$link->is_active ? 'opacity-50' : '' }}"
                                 data-id="{{ $link->id }}"
                                 style="cursor: move; transition: all 0.3s ease;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <i class="fas fa-grip-vertical text-muted mr-3" style="cursor: move;" title="اسحب لإعادة الترتيب"></i>
                                        <div class="mr-3">
                                            @if($link->icon)
                                                <i class="{{ $link->icon }} text-primary" style="font-size: 1.5rem;"></i>
                                            @else
                                                <i class="fas fa-link text-primary" style="font-size: 1.5rem;"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 font-weight-bold">
                                                {{ $link->title }}
                                                <span class="badge badge-{{ $link->is_active ? 'success' : 'secondary' }} ml-2">
                                                    {{ $link->is_active ? 'نشط' : 'معطل' }}
                                                </span>
                                                <span class="badge badge-{{ ($link->status ?? 'approved') === 'pending' ? 'warning' : 'dark' }} ml-1">
                                                    {{ ($link->type ?? 'website') === 'app' ? 'تطبيق' : 'موقع' }}
                                                </span>
                                                <span class="badge badge-dark ml-1">#{{ $link->order }}</span>
                                            </h6>
                                            <p class="mb-1 text-muted small">{{ $link->url }}</p>
                                            @if($link->submitter)
                                                <p class="mb-1 text-muted small"><span class="font-weight-bold">من أضافه:</span> {{ $link->submitter->name }}</p>
                                            @endif
                                            @if($link->description)
                                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">{{ $link->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="btn-group btn-group-sm shadow-sm ml-3">
                                        <button type="button"
                                                class="btn btn-outline-info border-0"
                                                onclick="editLink({{ $link->id }})"
                                                title="تعديل"
                                                style="border-radius: 6px 0 0 6px;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('dashboard.important-links.toggle', $link) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-outline-{{ $link->is_active ? 'warning' : 'success' }} border-0"
                                                    title="{{ $link->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                <i class="fas fa-{{ $link->is_active ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('dashboard.important-links.destroy', $link) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الرابط؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger border-0"
                                                    title="حذف"
                                                    style="border-radius: 0 6px 6px 0;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-link text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد روابط حالياً</h5>
                <p class="text-muted mb-4">ابدأ بإضافة روابط جديدة</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة رابط جديد
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Modal إضافة رابط جديد -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة رابط جديد
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.important-links.store') }}" method="POST" id="addForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="title" class="font-weight-bold">
                            العنوان <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}"
                               placeholder="أدخل عنوان الرابط..."
                               required maxlength="255">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="url" class="font-weight-bold">
                            الرابط <span class="text-danger">*</span>
                        </label>
                        <input type="url" name="url" id="url"
                               class="form-control @error('url') is-invalid @enderror"
                               value="{{ old('url') }}"
                               placeholder="https://example.com"
                               required maxlength="500">
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="type" class="font-weight-bold">النوع</label>
                        <select name="type" id="type" class="form-control">
                            <option value="website" {{ old('type', 'website') === 'website' ? 'selected' : '' }}>موقع</option>
                            <option value="app" {{ old('type') === 'app' ? 'selected' : '' }}>تطبيق</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="icon" class="font-weight-bold">الأيقونة (اختياري)</label>
                        <input type="text" name="icon" id="icon"
                               class="form-control @error('icon') is-invalid @enderror"
                               value="{{ old('icon', 'fas fa-link') }}"
                               placeholder="fas fa-link"
                               maxlength="100">
                        <small class="form-text text-muted">
                            استخدم Font Awesome icons مثل: fas fa-link, fab fa-facebook, etc.
                        </small>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="font-weight-bold">الوصف (اختياري)</label>
                        <textarea name="description" id="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="أدخل وصف للرابط..."
                                  maxlength="1000">{{ old('description') }}</textarea>
                        <small class="form-text text-muted">
                            <span id="charCount">0</span> / 1000 حرف
                        </small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image" class="font-weight-bold">صورة (اختياري)</label>
                        <input type="file" name="image" id="image" accept="image/*" class="form-control-file">
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                رابط نشط
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="open_in_new_tab" id="open_in_new_tab" value="1" checked>
                            <label class="form-check-label" for="open_in_new_tab">
                                فتح في تبويب جديد
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal تعديل رابط -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit mr-2"></i>تعديل رابط
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="edit_title" class="font-weight-bold">
                            العنوان <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="edit_title"
                               class="form-control"
                               required maxlength="255">
                    </div>

                    <div class="form-group">
                        <label for="edit_url" class="font-weight-bold">
                            الرابط <span class="text-danger">*</span>
                        </label>
                        <input type="url" name="url" id="edit_url"
                               class="form-control"
                               required maxlength="500">
                    </div>

                    <div class="form-group">
                        <label for="edit_type" class="font-weight-bold">النوع</label>
                        <select name="type" id="edit_type" class="form-control">
                            <option value="website">موقع</option>
                            <option value="app">تطبيق</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_icon" class="font-weight-bold">الأيقونة (اختياري)</label>
                        <input type="text" name="icon" id="edit_icon"
                               class="form-control"
                               placeholder="fas fa-link"
                               maxlength="100">
                        <small class="form-text text-muted">
                            استخدم Font Awesome icons مثل: fas fa-link, fab fa-facebook, etc.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="edit_description" class="font-weight-bold">الوصف (اختياري)</label>
                        <textarea name="description" id="edit_description" rows="3"
                                  class="form-control"
                                  maxlength="1000"></textarea>
                        <small class="form-text text-muted">
                            <span id="editCharCount">0</span> / 1000 حرف
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="edit_image" class="font-weight-bold">صورة (اختياري - اترك فارغاً للإبقاء على الحالية)</label>
                        <input type="file" name="image" id="edit_image" accept="image/*" class="form-control-file">
                    </div>

                    <div class="form-group">
                        <label for="edit_status" class="font-weight-bold">الحالة</label>
                        <select name="status" id="edit_status" class="form-control">
                            <option value="pending">بانتظار الموافقة</option>
                            <option value="approved">معتمد</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">
                                رابط نشط
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="open_in_new_tab" id="edit_open_in_new_tab" value="1">
                            <label class="form-check-label" for="edit_open_in_new_tab">
                                فتح في تبويب جديد
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save mr-2"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Sortable functionality
    let sortable = null;
    @if($links->count() > 0)
        sortable = Sortable.create(document.getElementById('links-list'), {
            handle: '.fa-grip-vertical',
            animation: 150,
            onEnd: function() {
                document.getElementById('saveOrderBtn').style.display = 'block';
            }
        });
    @endif

    function saveOrder() {
        const items = document.querySelectorAll('.link-item');
        const orders = Array.from(items).map(item => item.getAttribute('data-id'));

        fetch('{{ route("dashboard.important-links.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ orders: orders })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حفظ الترتيب');
        });
    }

    function editLink(id) {
        const approvedLinks = @json($links->keyBy('id'));
        const pendingLinks = @json(isset($pendingLinks) ? $pendingLinks->keyBy('id') : collect());
        const allLinks = { ...approvedLinks, ...pendingLinks };
        const linkData = allLinks[id];

        if (!linkData) return;

        document.getElementById('editForm').action = '{{ route("dashboard.important-links.update", ":id") }}'.replace(':id', id);
        document.getElementById('edit_title').value = linkData.title;
        document.getElementById('edit_url').value = linkData.url;
        document.getElementById('edit_type').value = linkData.type || 'website';
        document.getElementById('edit_icon').value = linkData.icon || 'fas fa-link';
        document.getElementById('edit_description').value = linkData.description || '';
        document.getElementById('edit_status').value = linkData.status || 'approved';
        document.getElementById('edit_is_active').checked = linkData.is_active;
        document.getElementById('edit_open_in_new_tab').checked = linkData.open_in_new_tab;
        document.getElementById('editCharCount').textContent = (linkData.description || '').length;
        document.getElementById('edit_image').value = '';

        $('#editModal').modal('show');
    }

    // Character count for description
    document.getElementById('description')?.addEventListener('input', function() {
        document.getElementById('charCount').textContent = this.value.length;
    });

    document.getElementById('edit_description')?.addEventListener('input', function() {
        document.getElementById('editCharCount').textContent = this.value.length;
    });
</script>
@endpush
@endsection
