@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- العنوان والمسار --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">معرض الملفات - الفئات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active" aria-current="page">معرض الملفات</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات --}}
        <div class="row mb-4">
            <x-stats-card icon="fas fa-images" title="إجمالي ملفات المعرض" :value="$imagesCount" color="primary" />
            <x-stats-card icon="fas fa-folder-tree" title="فئات لديها ملفات" :value="$categoriesWithImages" color="warning" />
            <x-stats-card icon="fas fa-upload" title="جاهز للرفع" :value="__('متعدد الملفات')" color="info" />
        </div>

        {{-- بطاقة المعرض --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-folder-open"></i> الفئات التي تحتوي على صور
                </h6>
                <div>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#uploadImagesModal">
                        <i class="fas fa-upload"></i> رفع ملفات
                    </button>
                </div>
            </div>

            <div class="card-body">
                {{-- فلاتر + بحث --}}
                <form action="{{ route('dashboard.images.index') }}" method="GET" class="mb-4">
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-6">
                            <label>بحث باسم الفئة</label>
                            <input type="text" name="search" value="{{ $search }}" class="form-control"
                                placeholder="ابحث باسم الفئة...">
                        </div>
                        <div class="form-group col-md-6">
                            <label>&nbsp;</label>
                            <div>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> تطبيق البحث
                                </button>
                                @if($search)
                                    <a href="{{ route('dashboard.images.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> إلغاء البحث
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                {{-- عرض الفئات في جدول --}}
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">اسم الفئة</th>
                                    <th style="width: 30%;">الوصف</th>
                                    <th style="width: 20%;">الفئات الفرعية</th>
                                    <th style="width: 10%;">عدد الصور</th>
                                    <th style="width: 10%;">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                        <td>
                                            <i class="fas fa-folder-open text-primary"></i>
                                            <strong>{{ $category->name }}</strong>
                                        </td>
                                        <td>
                                            @if($category->description)
                                                <span class="text-muted">{{ Str::limit($category->description, 80) }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($category->children->count() > 0)
                                                <div>
                                                    @foreach($category->children as $child)
                                                        <a href="{{ route('dashboard.gallery.category', $child->id) }}"
                                                           class="badge badge-secondary mr-1 mb-1">
                                                            {{ $child->name }} ({{ $child->images_count }})
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $category->total_images_count ?? $category->images_count }} صورة
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('dashboard.gallery.category', $category->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $categories->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> لا توجد فئات تحتوي على صور
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- مودال رفع الصور --}}
    @include('dashboard.gallery.modals.upload')

    {{-- مودال إنشاء فئة سريع --}}
    @include('dashboard.gallery.modals.quick-category')
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .table-hover tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,0.05);
        transform: scale(1.01);
    }

    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.75em;
    }

    .select2-container {
        width: 100% !important;
    }

    .pagination {
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // إنشاء فئة سريع (AJAX)
    $('#quickCategoryForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const fd = new FormData(form);
        const btn = $('#quickCategorySubmit');
        btn.prop('disabled', true).text('جارٍ الحفظ...');
        $.ajax({
            url: "{{ route('categories.quick-store') }}",
            method: "POST",
            data: fd,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(resp) {
                if (resp.ok && resp.category) {
                    // أضف الفئة الجديدة لقائمة الاختيار داخل المودال
                    $('#uploadCategorySelect').append(new Option(resp.category.name, resp.category.id, true, true));
                    $('#quickCategoryModal').modal('hide');
                    form.reset();
                    // إعادة تحميل الصفحة لعرض الفئة الجديدة
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
            },
            error: function() {
                alert('تعذر إنشاء الفئة.');
            },
            complete: function() {
                btn.prop('disabled', false).text('حفظ الفئة');
            }
        });
    });
</script>
@endpush
