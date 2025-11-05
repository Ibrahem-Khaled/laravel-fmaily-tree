@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الأصناف</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الأصناف</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- بطاقة قائمة الأصناف --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الأصناف</h6>
                <div>
                    <button class="btn btn-danger btn-sm" id="deleteEmptyCategoriesBtn" title="حذف الأصناف بدون علاقات">
                        <i class="fas fa-trash"></i> حذف الأصناف بدون علاقات
                    </button>
                    {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#createCategoryModal">
                        <i class="fas fa-plus fa-sm"></i> إضافة صنف جديد
                    </button> --}}
                </div>
            </div>
            <div class="card-body">
                {{-- نموذج البحث --}}
                <form action="{{ route('categories.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو الوصف..."
                            value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول الأصناف --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>الاسم</th>
                                <th>الصنف الأب</th>
                                <th>عدد المقالات</th>
                                <th>عدد الصور</th>
                                <th>عدد الأصناف الفرعية</th>
                                <th>ترتيب الفرز</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('img/default-category.png') }}"
                                                alt="{{ $category->name }}" class="rounded mr-3" width="60"
                                                height="60" style="object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 font-weight-bold">{{ $category->name }}</h6>
                                                <small
                                                    class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($category->parent)
                                            <span
                                                class="badge badge-pill badge-info p-2">{{ $category->parent->name }}</span>
                                        @else
                                            <span class="badge badge-pill badge-secondary p-2">رئيسي</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-success" style="font-size: 0.9rem;">
                                            <i class="fas fa-file-alt"></i> {{ $category->articles_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info" style="font-size: 0.9rem;">
                                            <i class="fas fa-image"></i> {{ $category->images_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning" style="font-size: 0.9rem;">
                                            <i class="fas fa-folder"></i> {{ $category->children_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td><span class="badge badge-primary"
                                            style="font-size: 1rem;">{{ $category->sort_order }}</span></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showCategoryModal{{ $category->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editCategoryModal{{ $category->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteCategoryModal{{ $category->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- تضمين المودالات لكل صنف --}}
                                @include('dashboard.categories.modals.show', ['category' => $category])
                                @include('dashboard.categories.modals.edit', [
                                    'category' => $category,
                                    'allCategories' => $allCategories,
                                ])
                                @include('dashboard.categories.modals.delete', ['category' => $category])

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد أصناف لعرضها.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $categories->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة صنف جديد (ثابت) --}}
    {{-- @include('dashboard.categories.modals.create', ['allCategories' => $allCategories]) --}}
@endsection

@push('scripts')
    <script>
        // عرض اسم الملف المختار في حقول رفع الصور
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        // تفعيل التولتيب الافتراضي
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })

        // حذف الأصناف بدون علاقات
        $('#deleteEmptyCategoriesBtn').on('click', function() {
            if (!confirm('هل أنت متأكد من حذف جميع الأصناف التي لا تحتوي على مقالات أو صور أو أصناف فرعية؟')) {
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري الحذف...');

            $.ajax({
                url: '{{ route("categories.delete-empty") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('تم حذف ' + response.deleted_count + ' صنف بدون علاقات بنجاح.');
                        location.reload();
                    } else {
                        alert('حدث خطأ: ' + (response.message || 'لم يتم حذف أي أصناف'));
                        btn.prop('disabled', false).html('<i class="fas fa-trash"></i> حذف الأصناف بدون علاقات');
                    }
                },
                error: function(xhr) {
                    var errorMsg = 'حدث خطأ أثناء الحذف';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                    btn.prop('disabled', false).html('<i class="fas fa-trash"></i> حذف الأصناف بدون علاقات');
                }
            });
        });
    </script>
@endpush
