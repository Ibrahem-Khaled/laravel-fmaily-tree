@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- العنوان والمسار --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة المقالات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active" aria-current="page">المقالات</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <x-stats-card icon="fas fa-newspaper" title="إجمالي المقالات" :value="$articlesCount" color="primary" />
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <x-stats-card icon="fas fa-check-circle" title="المنشورة" :value="$publishedCount" color="success" />
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <x-stats-card icon="fas fa-pencil-alt" title="المسودات" :value="$draftCount" color="warning" />
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <x-stats-card icon="fas fa-folder-tree" title="عدد الفئات" :value="$categoriesCount" color="info" />
            </div>
        </div>

        {{-- بطاقة القائمة --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">قائمة المقالات</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createArticleModal">
                    <i class="fas fa-plus"></i> إضافة مقال
                </button>
            </div>

            <div class="card-body">
                {{-- تبويبات الحالة --}}
                <ul class="nav nav-tabs mb-4">
                    @php $tabs = ['all'=>'الكل','published'=>'منشورة','draft'=>'مسودات']; @endphp
                    @foreach ($tabs as $key => $label)
                        <li class="nav-item">
                            <a class="nav-link {{ $status === $key ? 'active' : '' }}"
                                href="{{ route('articles.index', array_merge(request()->except('page'), ['status' => $key])) }}">
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- فلاتر فئات (فقط الفئات التي لديها مقالات via whereHas) --}}
                <div class="mb-3">
                    <div class="d-flex flex-wrap align-items-center">
                        <a class="badge badge-{{ !$categoryId ? 'primary' : 'light' }} mr-2 mb-2"
                            href="{{ route('articles.index', array_merge(request()->except(['page', 'category_id']), ['category_id' => null])) }}">
                            كل الفئات
                        </a>
                        @foreach ($categories as $cat)
                            <a class="badge badge-{{ (int) $categoryId === $cat->id ? 'primary' : 'light' }} mr-2 mb-2"
                                href="{{ route('articles.index', array_merge(request()->except('page'), ['category_id' => $cat->id])) }}">
                                {{ $cat->name }} <small class="text-muted">({{ $cat->articles_count }})</small>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- البحث --}}
                <form action="{{ route('articles.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث بالعنوان أو المحتوى..."
                            value="{{ $search }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="status" value="{{ $status }}">
                    @if ($categoryId)
                        <input type="hidden" name="category_id" value="{{ $categoryId }}">
                    @endif
                </form>

                {{-- الجدول --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>العنوان</th>
                                <th>الحالة</th>
                                <th>الفئة</th>
                                <th>الكاتب</th>
                                <th>الصور</th>
                                <th style="width: 170px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $article)
                                <tr>
                                    <td>{{ $article->title }}</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $article->status === 'published' ? 'success' : 'secondary' }}">
                                            {{ $article->status === 'published' ? 'منشورة' : 'مسودة' }}
                                        </span>
                                    </td>
                                    <td>{{ optional($article->category)->name ?? '-' }}</td>
                                    <td>{{ optional($article->person)->name ?? '-' }}</td>
                                    <td>
                                        {{-- إن كنت تستخدم withCount('images') في الكنترولر: --}}
                                        @if (isset($article->images_count))
                                            <span class="badge badge-info">{{ $article->images_count }}</span>
                                        @else
                                            <span class="badge badge-info">{{ $article->images()->count() }}</span>
                                        @endif
                                        <button class="btn btn-sm btn-outline-secondary ml-2" data-toggle="modal"
                                            data-target="#imagesModal{{ $article->id }}">إدارة الصور</button>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showArticleModal{{ $article->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editArticleModal{{ $article->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteArticleModal{{ $article->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        @include('dashboard.articles.modals.show', ['article' => $article])

                                        {{-- مرّر $people لمودال التعديل لو كان يحتاجه داخل include --}}
                                        @include('dashboard.articles.modals.edit', [
                                            'article' => $article,
                                            'categories' => $categories,
                                            'people' => $people ?? null,
                                        ])

                                        @include('dashboard.articles.modals.delete', [
                                            'article' => $article,
                                        ])
                                        @include('dashboard.articles.modals.images', [
                                            'article' => $article,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد مقالات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إنشاء مقال: مرّر أيضاً $people ليستفيد من قائمة الناشرين --}}
    @include('dashboard.articles.modals.create', [
        'categories' => $categories,
        'people' => $people ?? null,
    ])

    {{-- مودال إنشاء فئة سريع --}}
    @include('dashboard.articles.modals.quick-category')
@endsection

@push('scripts')
    <script>
        // عرض أسماء الملفات المختارة
        $('.custom-file-input').on('change', function() {
            const names = Array.from(this.files || []).map(f => f.name).join(', ');
            $(this).next('.custom-file-label').html(names || 'اختر ملفات...');
        });

        // تفعيل التولتيب
        $('[data-toggle="tooltip"]').tooltip();

        // إنشاء فئة سريع via AJAX
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
                        const $selects = $('select[name="category_id"]');
                        $selects.each(function() {
                            $(this).append(new Option(resp.category.name, resp.category.id,
                                true, true));
                        });
                        $('#quickCategoryModal').modal('hide');
                        form.reset();
                    }
                },
                error: function() {
                    alert('تعذر إنشاء الفئة. تحقق من المدخلات.');
                },
                complete: function() {
                    btn.prop('disabled', false).text('حفظ الفئة');
                }
            });
        });
    </script>
@endpush
