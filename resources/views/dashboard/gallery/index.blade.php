@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- العنوان والمسار --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">معرض الصور (فئات فقط)</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active" aria-current="page">معرض الصور</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات --}}
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-images" title="إجمالي صور المعرض" :value="$imagesCount" color="primary" />
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-folder-tree" title="فئات لديها صور" :value="$categoriesWithImages" color="warning" />
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-upload" title="جاهز للرفع" :value="__('متعدد الصور')" color="info" />
            </div>
        </div>

        {{-- بطاقة المعرض --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">الصور (مرتبطة بالفئات فقط)</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#uploadImagesModal">
                    <i class="fas fa-upload"></i> رفع صور
                </button>
            </div>

            <div class="card-body">
                {{-- فلاتر + بحث --}}
                <form action="{{ route('dashboard.images.index') }}" method="GET" class="mb-4">
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-6">
                            <label>بحث بالاسم</label>
                            <input type="text" name="search" value="{{ $search }}" class="form-control"
                                placeholder="ابحث باسم الصورة...">
                        </div>

                        <div class="form-group col-md-6">
                            <label>الفئة (فقط الفئات التي لديها صور)</label>
                            <select name="category_id" class="form-control">
                                <option value="">— الكل —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ (int) $categoryId === $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }} ({{ $cat->images_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> تطبيق الفلاتر</button>
                </form>

                {{-- جدول الصور --}}
                <div class="table-responsive">
                    <form action="{{ route('images.bulk-destroy') }}" method="POST" id="bulkDeleteForm">
                        @csrf @method('DELETE')
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:40px;"><input type="checkbox" id="checkAll"></th>
                                    <th>المعاينة</th>
                                    <th>الاسم</th>
                                    <th>الفئة</th>
                                    <th style="width:160px;">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($images as $img)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $img->id }}"></td>
                                        <td>
                                            <img src="{{ $img->path ? asset('storage/' . $img->path) : asset('img/no-image.png') }}"
                                                style="width:120px;height:90px;object-fit:cover;" class="img-thumbnail">
                                        </td>
                                        <td>{{ $img->name ?? '-' }}</td>
                                        <td>{{ optional($img->category)->name ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('images.destroy', $img) }}" method="POST"
                                                onsubmit="return confirm('حذف هذه الصورة؟');" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i>
                                                    حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">لا توجد صور</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>

                {{-- ترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $images->links() }}
                </div>

                {{-- زر حذف جماعي --}}
                <div class="mt-3">
                    <button class="btn btn-outline-danger" id="bulkDeleteBtn">
                        <i class="fas fa-trash"></i> حذف المحدد
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- مودال رفع الصور (فئات فقط) --}}
    @include('dashboard.gallery.modals.upload')

    {{-- مودال إنشاء فئة سريع --}}
    @include('dashboard.gallery.modals.quick-category')
@endsection

@push('scripts')
    <script>
        // اختيار الكل
        document.getElementById('checkAll').addEventListener('change', function() {
            document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
        });

        // حذف جماعي
        document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
            if (confirm('حذف كل الصور المحددة؟')) {
                document.getElementById('bulkDeleteForm').submit();
            }
        });

        // إظهار أسماء الملفات
        $(document).on('change', '.custom-file-input', function() {
            const names = Array.from(this.files || []).map(f => f.name).join(', ');
            $(this).next('.custom-file-label').html(names || 'اختر ملفات...');
        });

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
                        // أضف الفئة الجديدة لقوائم الاختيار داخل المودال والفلاتر
                        const selectors = [
                            'select[name="category_id"]',
                            '#uploadCategorySelect'
                        ];
                        selectors.forEach(sel => {
                            $(sel).append(new Option(resp.category.name, resp.category.id, true,
                                true));
                        });
                        $('#quickCategoryModal').modal('hide');
                        form.reset();
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
