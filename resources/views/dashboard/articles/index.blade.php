@extends('layouts.app')

@push('styles')
    <style>
        /* Premium dashboard styles */
        .table-premium {
            border-collapse: separate;
            border-spacing: 0 10px;
            width: 100%;
        }
        .table-premium thead th {
            border: none !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #64748b;
            padding: 16px;
        }
        body.dark .table-premium thead th {
            color: #94a3b8;
        }
        .table-premium tbody tr {
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        body.dark .table-premium tbody tr {
            background-color: #1e293b;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }
        .table-premium tbody td {
            border: none !important;
            padding: 16px;
            vertical-align: middle;
            color: #334155;
        }
        body.dark .table-premium tbody td {
            color: #cbd5e1;
        }
        .table-premium tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            background-color: #f8fafc;
        }
        body.dark .table-premium tbody tr:hover {
            background-color: #243049;
            box-shadow: 0 12px 20px rgba(0,0,0,0.4) !important;
        }
        
        .table-premium tr td:first-child, 
        .table-premium tr th:first-child {
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }
        .table-premium tr td:last-child, 
        .table-premium tr th:last-child {
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
        }

        /* Pill Tabs */
        .nav-pills-custom {
            background: #f1f5f9;
            padding: 6px;
            border-radius: 14px;
            display: inline-flex;
        }
        body.dark .nav-pills-custom {
            background: #0f172a;
        }
        .nav-pills-custom .nav-link {
            border-radius: 10px;
            font-weight: 600;
            padding: 8px 20px;
            color: #64748b;
            border: none;
            transition: all 0.2s ease;
        }
        .nav-pills-custom .nav-link.active {
            background-color: #4f46e5;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
        }
        body.dark .nav-pills-custom .nav-link {
            color: #94a3b8;
        }
        body.dark .nav-pills-custom .nav-link.active {
            background-color: #6366f1;
            color: #ffffff;
        }

        /* Category Badges */
        .badge-filter {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            text-decoration: none !important;
            border: 1px solid transparent;
            display: inline-block;
        }
        .badge-filter-active {
            background-color: #4f46e5;
            color: white !important;
            box-shadow: 0 4px 8px rgba(79, 70, 229, 0.2);
        }
        body.dark .badge-filter-active {
            background-color: #6366f1;
        }
        .badge-filter-inactive {
            background-color: #ffffff;
            color: #475569 !important;
            border-color: #e2e8f0;
        }
        body.dark .badge-filter-inactive {
            background-color: #1e293b;
            color: #cbd5e1 !important;
            border-color: #334155;
        }
        .badge-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        /* Search input styling */
        .search-container .form-control {
            border: 1px solid #e2e8f0;
            padding: 12px 18px;
            height: auto;
            transition: all 0.3s ease;
        }
        body.dark .search-container .form-control {
            background-color: #0f172a;
            border-color: #1e293b;
            color: #cbd5e1;
        }
        .search-container .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }
        
        html[dir="rtl"] .search-container .form-control {
            border-top-right-radius: 12px !important;
            border-bottom-right-radius: 12px !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }
        html[dir="rtl"] .search-container .btn {
            border-top-left-radius: 12px !important;
            border-bottom-left-radius: 12px !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        /* Thumbnail preview */
        .thumbnail-preview {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 2px solid #ffffff;
        }
        body.dark .thumbnail-preview {
            border-color: #1e293b;
        }
        .thumbnail-preview:hover {
            transform: scale(1.1) rotate(2deg);
        }
        .thumbnail-fallback {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: #f1f5f9;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        body.dark .thumbnail-fallback {
            background: #0f172a;
            color: #818cf8;
        }

        /* Grid for edit photo upload previews */
        .thumbs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: .75rem;
        }
        .thumb-card {
            border: 1px solid #e2e8f0;
            border-radius: .75rem;
            padding: .5rem;
            text-align: center;
            background: #ffffff;
        }
        body.dark .thumb-card {
            background: #0f172a;
            border-color: #1e293b;
        }
        .thumb-img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: .5rem;
        }
        .attach-list {
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }
        .attach-row {
            display: flex;
            align-items: center;
            gap: .75rem;
            border: 1px solid #e2e8f0;
            border-radius: .75rem;
            padding: .5rem .75rem;
            background: #ffffff;
        }
        body.dark .attach-row {
            background: #0f172a;
            border-color: #1e293b;
        }
        .attach-icon {
            font-size: 1.2rem;
            color: #64748b;
        }
        .attach-meta {
            flex: 1;
            min-width: 0;
        }
        .attach-name {
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.9rem;
        }
        .attach-actions>*:not(:last-child) {
            margin-inline-end: .5rem;
        }
        html[dir="rtl"] .custom-file-label::after {
            left: .75rem;
            right: auto;
        }
        html[dir="rtl"] .custom-file-label {
            text-align: right;
            padding-right: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        {{-- العنوان والمسار والزر باستخدام المكون الجاهز --}}
        <x-dashboard.page-header 
            title="إدارة المقالات" 
            description="استعرض، أنشئ، وحرر المقالات والمنشورات الخاصة بشجرة العائلة.">
            <x-slot name="actions">
                <button class="btn btn-primary shadow-sm d-flex align-items-center gap-2 px-4 py-2" data-toggle="modal" data-target="#createArticleModal" style="border-radius: 12px; font-weight: 600;">
                    <i class="fe fe-plus-circle font-weight-bold" style="font-size: 1.1rem;"></i>
                    <span>إضافة مقال جديد</span>
                </button>
            </x-slot>
        </x-dashboard.page-header>

        @include('components.alerts')

        {{-- إحصائيات --}}
        <div class="row mb-4">
            <x-dashboard.stat-card icon="fe-file-text" title="إجمالي المقالات" :value="$articlesCount" gradient="primary" />
            <x-dashboard.stat-card icon="fe-check-circle" title="المنشورة" :value="$publishedCount" gradient="success" />
            <x-dashboard.stat-card icon="fe-edit-3" title="المسودات" :value="$draftCount" gradient="warning" />
            <x-dashboard.stat-card icon="fe-folder" title="عدد الفئات" :value="$categoriesCount" gradient="info" />
        </div>

        {{-- بطاقة القائمة --}}
        <x-dashboard.card title="قائمة المقالات" icon="fe-book-open">
            {{-- فلترة المقالات والبحث في سطر واحد لتوفير المساحة وتنظيم الواجهة --}}
            <div class="row align-items-center mb-4 gap-3">
                <div class="col-lg-6 col-md-12">
                    <ul class="nav nav-pills nav-pills-custom">
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
                </div>
                <div class="col-lg-6 col-md-12 search-container">
                    <form action="{{ route('articles.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control px-3" placeholder="ابحث بالعنوان أو المحتوى..."
                                value="{{ $search }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary px-4" type="submit">
                                    <i class="fe fe-search mr-1"></i> بحث
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="{{ $status }}">
                        @if ($categoryId)
                            <input type="hidden" name="category_id" value="{{ $categoryId }}">
                        @endif
                    </form>
                </div>
            </div>

            {{-- فلاتر الفئات (فقط الفئات التي لديها مقالات) --}}
            <div class="mb-4 d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted small font-weight-bold mr-2"><i class="fe fe-filter"></i> الفئات:</span>
                <a class="badge-filter {{ !$categoryId ? 'badge-filter-active' : 'badge-filter-inactive' }}"
                    href="{{ route('articles.index', array_merge(request()->except(['page', 'category_id']), ['category_id' => null])) }}">
                    كل الفئات
                </a>
                @foreach ($categories as $cat)
                    <a class="badge-filter {{ (int) $categoryId === $cat->id ? 'badge-filter-active' : 'badge-filter-inactive' }}"
                        href="{{ route('articles.index', array_merge(request()->except('page'), ['category_id' => $cat->id])) }}">
                        {{ $cat->name }} <small class="opacity-75">({{ $cat->articles_count }})</small>
                    </a>
                @endforeach
            </div>

            {{-- الجدول --}}
            <div class="table-responsive">
                <table class="table table-premium table-hover">
                    <thead>
                        <tr>
                            <th style="width: 80px;">الصورة</th>
                            <th>المقال</th>
                            <th>الحالة</th>
                            <th>الفئة</th>
                            <th>الكاتب</th>
                            <th>الصور</th>
                            <th>المرفقات</th>
                            <th style="width: 160px;" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($articles as $article)
                            <tr>
                                {{-- صورة الغلاف مصغرة --}}
                                <td>
                                    @php
                                        $firstImage = $article->images->first();
                                    @endphp
                                    @if ($firstImage)
                                        <img src="{{ asset('storage/' . $firstImage->path) }}" alt="غلاف المقال" class="thumbnail-preview" loading="lazy">
                                    @else
                                        <div class="thumbnail-fallback">
                                            <i class="fe fe-file-text"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- عنوان المقال والتاريخ --}}
                                <td>
                                    <div class="font-weight-bold" style="font-size: 1.05rem; line-height: 1.4;">{{ $article->title }}</div>
                                    <div class="text-muted small mt-1 d-flex align-items-center gap-1">
                                        <i class="fe fe-calendar" style="font-size: 0.85rem;"></i>
                                        <span>{{ $article->created_at ? $article->created_at->translatedFormat('d M Y') : 'تاريخ غير محدد' }}</span>
                                    </div>
                                </td>

                                {{-- الحالة --}}
                                <td>
                                    @if ($article->status === 'published')
                                        <span class="badge px-3 py-1 text-success bg-success-light" style="border-radius: 30px; font-weight: 600; font-size: 0.8rem; background: rgba(40, 167, 69, 0.15);">
                                            منشور
                                        </span>
                                    @else
                                        <span class="badge px-3 py-1 text-secondary bg-secondary-light" style="border-radius: 30px; font-weight: 600; font-size: 0.8rem; background: rgba(108, 117, 125, 0.15);">
                                            مسودة
                                        </span>
                                    @endif
                                </td>

                                {{-- الفئة --}}
                                <td>
                                    @if($article->category)
                                        <span class="badge px-2 py-1 text-primary" style="background: rgba(79, 70, 229, 0.1); border-radius: 6px;">
                                            {{ $article->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- الكاتب --}}
                                <td>
                                    @if($article->person)
                                        <div class="d-flex align-items-center gap-1 text-secondary">
                                            <i class="fe fe-user text-muted" style="font-size: 0.9rem;"></i>
                                            <span>{{ $article->person->full_name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- الصور --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge badge-info shadow-sm" style="border-radius: 8px; font-size: 0.85rem; padding: 5px 10px;">
                                            {{ $article->images_count ?? $article->images->count() }}
                                        </span>
                                        <button class="btn btn-sm btn-link text-decoration-none p-0 font-weight-bold text-primary" data-toggle="modal"
                                            data-target="#imagesModal{{ $article->id }}">
                                            إدارة الصور
                                        </button>
                                    </div>
                                </td>

                                {{-- المرفقات --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge badge-secondary shadow-sm" style="border-radius: 8px; font-size: 0.85rem; padding: 5px 10px;">
                                            {{ $article->attachments_count ?? $article->attachments->count() }}
                                        </span>
                                        <button class="btn btn-sm btn-link text-decoration-none p-0 font-weight-bold text-primary" data-toggle="modal"
                                            data-target="#editArticleModal{{ $article->id }}">
                                            إدارة المرفقات
                                        </button>
                                    </div>
                                </td>

                                {{-- الإجراءات --}}
                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <button class="btn btn-sm btn-light border-0 d-flex align-items-center justify-content-center shadow-sm" 
                                            data-toggle="modal" data-target="#showArticleModal{{ $article->id }}" 
                                            title="عرض" style="width: 32px; height: 32px; border-radius: 50%;">
                                            <i class="fe fe-eye text-info"></i>
                                        </button>

                                        <button class="btn btn-sm btn-light border-0 d-flex align-items-center justify-content-center shadow-sm" 
                                            data-toggle="modal" data-target="#editArticleModal{{ $article->id }}" 
                                            title="تعديل" style="width: 32px; height: 32px; border-radius: 50%;">
                                            <i class="fe fe-edit text-primary"></i>
                                        </button>

                                        <button class="btn btn-sm btn-light border-0 d-flex align-items-center justify-content-center shadow-sm" 
                                            data-toggle="modal" data-target="#deleteArticleModal{{ $article->id }}" 
                                            title="حذف" style="width: 32px; height: 32px; border-radius: 50%;">
                                            <i class="fe fe-trash text-danger"></i>
                                        </button>
                                    </div>

                                    {{-- المودالات الخاصة بكل مقال --}}
                                    @include('dashboard.articles.modals.show', ['article' => $article])

                                    @include('dashboard.articles.modals.edit', [
                                        'article' => $article,
                                        'categories' => $categories,
                                        'people' => $people ?? null,
                                    ])

                                    @include('dashboard.articles.modals.delete', [
                                        'article' => $article,
                                    ])

                                    {{-- إدارة الصور (مودال منفصل) --}}
                                    @include('dashboard.articles.modals.images', [
                                        'article' => $article,
                                    ])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fe fe-folder-minus d-block mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                                    لا توجد مقالات تطابق هذا البحث أو الفئة.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $articles->links() }}
            </div>
        </x-dashboard.card>
    </div>

    {{-- مودال إنشاء مقال --}}
    @include('dashboard.articles.modals.create', [
        'categories' => $categories,
        'people' => $people ?? null,
    ])

    {{-- مودال إنشاء فئة سريع --}}
    @include('dashboard.articles.modals.quick-category')
@endsection

@push('scripts')
    <script>
        // عرض أسماء الملفات المختارة في حقول الرفع
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
    <script>
        // لا شيء خاص بالفيديو هنا حاليًا؛ إدارة الفيديو تتم عبر الحقول في المودال
    </script>
@endpush
