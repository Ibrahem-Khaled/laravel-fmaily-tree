@extends('layouts.app')

@section('title', 'إدارة فئات الروابط المهمة')

@section('content')
@include('dashboard.important-links._styles')


<div class="container-fluid dashboard-container">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="text-right">
            <h1 class="page-title mb-1">
                <i class="fas fa-folder text-emerald-500 ml-2"></i>إدارة فئات الروابط
            </h1>
            <p class="text-muted mb-0">قم بإضافة وتعديل وترتيب الفئات الخاصة بتطبيقات وروابط الصفحة الرئيسية</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.important-links.index') }}" class="btn btn-premium-secondary d-flex align-items-center gap-2">
                <i class="fas fa-arrow-right ml-1"></i> العودة للروابط
            </a>
            <button class="btn btn-premium-primary d-flex align-items-center gap-2 mr-2" data-toggle="modal" data-target="#createCategoryModal">
                <i class="fas fa-plus-circle ml-1"></i>إضافة فئة جديدة
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-xl mb-4" role="alert" style="background-color: #ecfdf5; color: #065f46;" dir="rtl">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-lg ml-3"></i>
                <span class="font-weight-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="close text-success" data-dismiss="alert" aria-label="Close" style="opacity: 0.8;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Categories Card -->
    <div class="card card-premium mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 font-weight-bold text-slate-800 text-right w-100"><i class="fas fa-list ml-2 text-emerald-500"></i>فئات الروابط المهمة ({{ $categories->count() }})</h6>
        </div>
        <div class="card-body p-0">
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-premium text-right" width="100%" cellspacing="0" dir="rtl">
                        <thead>
                            <tr>
                                <th width="60" class="text-center"><i class="fas fa-grip-vertical"></i></th>
                                <th>اسم الفئة</th>
                                <th>الأيقونة</th>
                                <th>الوصف</th>
                                <th>عدد الروابط</th>
                                <th>الحالة</th>
                                <th width="200" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-categories">
                            @foreach($categories as $category)
                                <tr data-id="{{ $category->id }}" class="{{ !$category->is_active ? 'table-secondary opacity-75' : '' }}">
                                    <td class="sortable-handle text-center">
                                        <i class="fas fa-grip-vertical fa-lg"></i>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-slate-900" style="font-size: 1rem;">{{ $category->name }}</span>
                                    </td>
                                    <td>
                                        @if($category->icon)
                                            <div class="d-inline-flex align-items-center justify-content-center bg-emerald-50 text-emerald-600 rounded-lg p-2 mr-2 border border-emerald-100" style="width: 38px; height: 38px;">
                                                <i class="{{ $category->icon }} fa-lg"></i>
                                            </div>
                                            <code class="text-emerald-700 bg-emerald-50/50 px-2 py-1 rounded small ml-2 border border-emerald-100/30">{{ $category->icon }}</code>
                                        @else
                                            <span class="text-muted small">لا يوجد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-slate-500" style="font-size: 0.9rem;">{{ $category->description ?: 'لا يوجد وصف' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-light border font-weight-bold px-3 py-2 text-slate-700">
                                            <i class="fas fa-link ml-1 text-emerald-500"></i> {{ $category->links_count }} روابط
                                        </span>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch custom-switch-premium">
                                            <input type="checkbox" class="custom-control-input toggle-active" 
                                                   id="toggle-{{ $category->id }}" 
                                                   data-id="{{ $category->id }}"
                                                   {{ $category->is_active ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="toggle-{{ $category->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-premium-info d-flex align-items-center gap-1" data-toggle="modal" data-target="#editCategoryModal-{{ $category->id }}" title="تعديل">
                                                <i class="fas fa-edit ml-1"></i>تعديل
                                            </button>
                                            <form action="{{ route('dashboard.important-links.categories.destroy', $category) }}" method="POST" class="d-inline mr-2" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟ سيتم تحويل كافة الروابط المضافة لها لتصبح (بدون فئة) ولن يتم حذفها.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-premium-danger d-flex align-items-center gap-1" title="حذف">
                                                    <i class="fas fa-trash ml-1"></i>حذف
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Category Modal -->
                                <div class="modal fade modal-premium" id="editCategoryModal-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel-{{ $category->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('dashboard.important-links.categories.update', $category) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title font-weight-bold text-slate-800 w-100 text-right" id="editModalLabel-{{ $category->id }}">تعديل الفئة: {{ $category->name }}</h5>
                                                    <button type="button" class="close ml-0 mr-auto text-dark" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-right" dir="rtl">
                                                    <div class="form-group mb-4">
                                                        <label class="font-weight-bold text-slate-700 mb-2">اسم الفئة <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" required class="form-control form-control-premium text-right" value="{{ $category->name }}">
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label class="font-weight-bold text-slate-700 mb-2">أيقونة الفئة (اختياري)</label>
                                                        <input type="text" name="icon" class="form-control form-control-premium text-right" placeholder="مثال: fas fa-mobile-alt" value="{{ $category->icon }}">
                                                        <small class="form-text text-muted mt-1">Font Awesome مثل: fas fa-mobile-alt</small>
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label class="font-weight-bold text-slate-700 mb-2">الوصف (اختياري)</label>
                                                        <textarea name="description" rows="3" class="form-control form-control-premium text-right" placeholder="وصف مخصصة للفئة...">{{ $category->description }}</textarea>
                                                    </div>
                                                    <div class="form-group mb-0">
                                                        <div class="custom-control custom-switch custom-switch-premium pr-0">
                                                            <input type="checkbox" class="custom-control-input" name="is_active" id="edit_is_active-{{ $category->id }}" value="1" {{ $category->is_active ? 'checked' : '' }}>
                                                            <label class="custom-control-label font-weight-bold text-slate-700" for="edit_is_active-{{ $category->id }}">تفعيل وتنشيط الفئة</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-end gap-2 bg-light">
                                                    <button type="button" class="btn btn-premium-secondary" data-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-premium-primary">حفظ التعديلات</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="bg-emerald-50 text-emerald-500 rounded-full d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-folder-open fa-3x"></i>
                    </div>
                    <h5 class="font-weight-bold text-slate-700 mb-2">لا توجد فئات حالياً</h5>
                    <p class="text-muted mb-4">ابدأ بإضافة فئة جديدة للروابط المهمة لتتمكن من تنظيمها</p>
                    <button class="btn btn-premium-primary" data-toggle="modal" data-target="#createCategoryModal">
                        <i class="fas fa-plus-circle ml-1"></i>إضافة فئة جديدة
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade modal-premium" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('dashboard.important-links.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold text-slate-800 w-100 text-right" id="createModalLabel">إضافة فئة جديدة</h5>
                    <button type="button" class="close ml-0 mr-auto text-dark" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-right" dir="rtl">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-slate-700 mb-2">اسم الفئة <span class="text-danger">*</span></label>
                        <input type="text" name="name" required class="form-control form-control-premium text-right" placeholder="أدخل اسم الفئة المميّز...">
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-slate-700 mb-2">أيقونة الفئة (اختياري)</label>
                        <input type="text" name="icon" class="form-control form-control-premium text-right" placeholder="مثال: fas fa-mobile-alt" value="fas fa-folder">
                        <small class="form-text text-muted mt-1">Font Awesome مثل: fas fa-folder</small>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-slate-700 mb-2">الوصف (اختياري)</label>
                        <textarea name="description" rows="3" class="form-control form-control-premium text-right" placeholder="أدخل وصفاً مختصراً للفئة الجديدة..."></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <div class="custom-control custom-switch custom-switch-premium pr-0">
                            <input type="checkbox" class="custom-control-input" name="is_active" id="is_active" value="1" checked>
                            <label class="custom-control-label font-weight-bold text-slate-700" for="is_active">تفعيل الفئة تلقائياً</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end gap-2 bg-light">
                    <button type="button" class="btn btn-premium-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-premium-primary">إضافة فئة</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Drag and Drop لإعادة ترتيب الفئات
    @if($categories->count() > 0)
        var sortable = Sortable.create(document.getElementById('sortable-categories'), {
            handle: '.sortable-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                var items = [];
                $('#sortable-categories tr').each(function(index) {
                    items.push({
                        id: $(this).data('id'),
                        sort_order: index
                    });
                });

                $.ajax({
                    url: '{{ route("dashboard.important-links.categories.reorder") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        items: items
                    },
                    success: function(response) {
                        if (!response.success) {
                            alert('حدث خطأ أثناء حفظ الترتيب');
                        }
                    },
                    error: function() {
                        alert('حدث خطأ في الاتصال بالخادم');
                    }
                });
            }
        });
    @endif

    // تبديل حالة تفعيل الفئة
    $('.toggle-active').on('change', function() {
        var checkbox = $(this);
        var id = checkbox.data('id');
        var isChecked = checkbox.is(':checked');

        $.ajax({
            url: '{{ url("/dashboard/important-links-categories") }}/' + id + '/toggle',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    var row = checkbox.closest('tr');
                    if (response.is_active) {
                        row.removeClass('table-secondary opacity-75');
                    } else {
                        row.addClass('table-secondary opacity-75');
                    }
                } else {
                    checkbox.prop('checked', !isChecked);
                    alert('حدث خطأ أثناء تحديث الحالة');
                }
            },
            error: function() {
                checkbox.prop('checked', !isChecked);
                alert('حدث خطأ في الاتصال بالخادم');
            }
        });
    });
</script>
@endpush
@endsection
