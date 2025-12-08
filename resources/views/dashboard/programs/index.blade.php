@extends('layouts.app')

@section('title', 'إدارة البرامج')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-tv text-primary mr-2"></i>إدارة برامج السريع
            </h1>
            <p class="text-muted mb-0">قم بإدارة البرامج المعروضة في الصفحة الرئيسية</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus-circle mr-2"></i>إضافة برنامج جديد
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
                                إجمالي البرامج
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tv fa-2x text-gray-300"></i>
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
                                برامج مفعلة
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
                                برامج معطلة
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
                                برامج حديثة (30 يوم)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['recent'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Programs Table -->
    @if($programs->count() > 0)
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-tv mr-2"></i>البرامج ({{ $programs->count() }})
                    </h6>
                    <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                        <i class="fas fa-save mr-1"></i>حفظ الترتيب
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="programsTable">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">
                                    <i class="fas fa-grip-vertical text-muted"></i>
                                </th>
                                <th style="width: 60px;" class="text-center">#</th>
                                <th style="width: 100px;">الصورة</th>
                                <th>عنوان البرنامج</th>
                                <th>الوصف</th>
                                <th style="width: 100px;" class="text-center">الحالة</th>
                                <th style="width: 80px;" class="text-center">الترتيب</th>
                                <th style="width: 200px;" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="programsTableBody">
                            @foreach($programs as $index => $program)
                                <tr data-id="{{ $program->id }}" style="cursor: move;">
                                    <td class="text-center align-middle">
                                        <i class="fas fa-grip-vertical text-muted" style="cursor: grab;"></i>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-secondary">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="align-middle">
                                        @if($program->path)
                                            <img src="{{ asset('storage/' . $program->path) }}"
                                                 alt="{{ $program->program_title ?? $program->name }}"
                                                 class="img-thumbnail rounded"
                                                 style="width: 80px; height: 80px; object-fit: cover; {{ $program->program_is_active ? '' : 'opacity: 0.5; filter: grayscale(100%);' }}">
                                        @else
                                            <div class="bg-gradient-primary d-flex align-items-center justify-content-center rounded"
                                                 style="width: 80px; height: 80px;">
                                                <i class="fas fa-tv text-white" style="font-size: 1.5rem; opacity: 0.5;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <strong class="text-dark">{{ $program->program_title ?? ($program->name ?? 'برنامج #' . $program->id) }}</strong>
                                        @if($program->program_id)
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-sitemap text-info mr-1"></i>
                                                برنامج فرعي
                                            </small>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($program->program_description)
                                            <small class="text-muted">
                                                {{ Str::limit(strip_tags($program->program_description), 80) }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($program->program_is_active)
                                            <span class="badge badge-success badge-pill px-3 py-1">
                                                <i class="fas fa-check-circle mr-1"></i>مفعل
                                            </span>
                                        @else
                                            <span class="badge badge-warning badge-pill px-3 py-1">
                                                <i class="fas fa-times-circle mr-1"></i>معطل
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-dark badge-pill px-3 py-1">
                                            {{ $program->program_order }}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('programs.show', $program) }}"
                                               target="_blank"
                                               class="btn btn-outline-success"
                                               title="معاينة"
                                               data-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('dashboard.programs.manage', $program) }}"
                                               class="btn btn-outline-primary"
                                               title="إدارة التفاصيل"
                                               data-toggle="tooltip">
                                                <i class="fas fa-folder-open"></i>
                                            </a>
                                            {{-- <a href="{{ route('dashboard.programs.manage', $program) }}"
                                               class="btn btn-outline-info"
                                               title="تعديل"
                                               data-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a> --}}
                                            <form action="{{ route('dashboard.programs.toggle', $program) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn {{ $program->program_is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                        title="{{ $program->program_is_active ? 'إلغاء التفعيل' : 'تفعيل' }}"
                                                        data-toggle="tooltip"
                                                        onclick="return confirm('هل أنت متأكد من {{ $program->program_is_active ? 'إلغاء تفعيل' : 'تفعيل' }} هذا البرنامج؟')">
                                                    <i class="fas {{ $program->program_is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('dashboard.programs.destroy', $program) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا البرنامج؟ سيتم حذف جميع البيانات المرتبطة به.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-outline-danger"
                                                        title="حذف"
                                                        data-toggle="tooltip">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-tv text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد برامج حالياً</h5>
                <p class="text-muted mb-4">ابدأ بإضافة برامج جديدة</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة برنامج جديد
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Modal إضافة برنامج جديد -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة برنامج جديد
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.programs.store') }}" method="POST" enctype="multipart/form-data" id="addForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="image" class="font-weight-bold">
                            الصورة <span class="text-danger">*</span>
                        </label>
                        <div class="custom-file">
                            <input type="file" name="image" id="image"
                                   class="custom-file-input @error('image') is-invalid @enderror"
                                   accept="image/*" required>
                            <label class="custom-file-label" for="image">اختر صورة...</label>
                        </div>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            الصيغ المدعومة: JPEG, PNG, JPG, GIF, WebP (حد أقصى 5MB)
                        </small>
                        <div class="mt-2" id="imagePreview"></div>
                    </div>

                    <div class="form-group">
                        <label for="program_title" class="font-weight-bold">
                            عنوان البرنامج <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="program_title" id="program_title"
                               class="form-control @error('program_title') is-invalid @enderror"
                               value="{{ old('program_title') }}"
                               placeholder="أدخل عنوان البرنامج..."
                               required maxlength="255">
                        @error('program_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="program_description" class="font-weight-bold">وصف البرنامج (اختياري)</label>
                        <textarea name="program_description" id="program_description" rows="3"
                                  class="form-control @error('program_description') is-invalid @enderror"
                                  placeholder="أدخل وصف للبرنامج..."
                                  maxlength="1000">{{ old('program_description') }}</textarea>
                        <small class="form-text text-muted">
                            <span id="charCount">0</span> / 1000 حرف
                        </small>
                        @error('program_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name" class="font-weight-bold">اسم الصورة (اختياري)</label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="اسم الصورة..."
                               maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i>إضافة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sortable JS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    // تفعيل السحب والإفلات
    const programsTableBody = document.getElementById('programsTableBody');
    if (programsTableBody) {
        new Sortable(programsTableBody, {
            handle: '.fa-grip-vertical',
            animation: 300,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                document.getElementById('saveOrderBtn').style.display = 'inline-block';
            }
        });
    }

    // حفظ الترتيب
    function saveOrder() {
        const rows = document.querySelectorAll('#programsTableBody tr[data-id]');
        const orders = Array.from(rows).map(row => row.dataset.id);

        fetch('{{ route("dashboard.programs.reorder") }}', {
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
                alert('تم حفظ الترتيب بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ أثناء حفظ الترتيب');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حفظ الترتيب');
        });
    }

    // معاينة الصورة عند الاختيار
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').innerHTML = `
                    <div class="border rounded p-2 bg-light">
                        <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    });

    // عداد الأحرف
    function updateCharCount(textareaId, counterId) {
        const textarea = document.getElementById(textareaId);
        const counter = document.getElementById(counterId);
        if (textarea && counter) {
            textarea.addEventListener('input', function() {
                counter.textContent = this.value.length;
            });
            counter.textContent = textarea.value.length;
        }
    }

    // تحديث تسمية ملفات الرفع
    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.files && this.files.length > 0) {
                label.textContent = this.files[0].name;
            } else {
                label.textContent = 'اختر ملف...';
            }
        });
    });

    // تهيئة عداد الأحرف
    updateCharCount('program_description', 'charCount');

    // إعادة تعيين النموذج عند إغلاق المودال
    $('#addModal').on('hidden.bs.modal', function() {
        document.getElementById('addForm').reset();
        document.getElementById('imagePreview').innerHTML = '';
        document.getElementById('charCount').textContent = '0';
    });

    // تفعيل tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<style>
    #programsTable tbody tr {
        transition: all 0.2s ease;
    }

    #programsTable tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .sortable-ghost {
        opacity: 0.4;
        background: #e9ecef !important;
    }

    .sortable-chosen {
        cursor: grabbing !important;
    }

    .sortable-chosen td {
        cursor: grabbing !important;
    }

    .btn-group-sm .btn {
        border-radius: 0;
    }

    .btn-group-sm .btn:first-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }

    .btn-group-sm .btn:last-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }

    .custom-file-label::after {
        content: "تصفح";
        right: 0;
        left: auto;
    }

    .badge-pill {
        border-radius: 50rem;
    }

    .img-thumbnail {
        border: 2px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .img-thumbnail:hover {
        border-color: #007bff;
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>
@endsection
