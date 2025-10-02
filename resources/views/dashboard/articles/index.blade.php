@extends('layouts.app')

@push('styles')
    <style>
        /* شبكة صور مرتّبة */
        .thumbs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: .75rem;
        }

        .thumb-card {
            border: 1px solid #e9ecef;
            border-radius: .5rem;
            padding: .5rem;
            text-align: center;
        }

        .thumb-img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: .25rem;
        }

        /* صف المرفق */
        .attach-list {
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .attach-row {
            display: flex;
            align-items: center;
            gap: .75rem;
            border: 1px solid #e9ecef;
            border-radius: .5rem;
            padding: .5rem .75rem;
        }

        .attach-icon {
            font-size: 1.1rem;
            color: #6c757d;
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
        }

        .attach-actions>*:not(:last-child) {
            margin-inline-end: .5rem;
        }

        /* RTL/LTR friendly */

        /* تحسين مظهر custom-file في RTL */
        html[dir="rtl"] .custom-file-label::after {
            left: .75rem;
            right: auto;
        }

        html[dir="rtl"] .custom-file-label {
            text-align: right;
            padding-right: 1rem;
        }

        .thumbs-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: .75rem;
  }
  .thumb-card { border: 1px solid #e9ecef; border-radius: .5rem; padding: .5rem; text-align: center; }
  .thumb-img { width: 100%; height: 80px; object-fit: cover; border-radius: .25rem; }
  .attach-list { display: flex; flex-direction: column; gap: .5rem; }
  .attach-row { display: flex; align-items: center; gap: .75rem; border: 1px solid #e9ecef; border-radius: .5rem; padding: .5rem .75rem; }
  .attach-icon { font-size: 1.1rem; color: #6c757d; }
  .attach-meta { flex: 1; min-width: 0; }
  .attach-name { font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .attach-actions > *:not(:last-child) { margin-inline-end: .5rem; }
  html[dir="rtl"] .custom-file-label::after { left: .75rem; right: auto; }
  html[dir="rtl"] .custom-file-label { text-align: right; padding-right: 1rem; }
    </style>
@endpush


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

                <x-stats-card icon="fas fa-newspaper" title="إجمالي المقالات" :value="$articlesCount" color="primary" />

                <x-stats-card icon="fas fa-check-circle" title="المنشورة" :value="$publishedCount" color="success" />

                <x-stats-card icon="fas fa-pencil-alt" title="المسودات" :value="$draftCount" color="warning" />

                <x-stats-card icon="fas fa-folder-tree" title="عدد الفئات" :value="$categoriesCount" color="info" />
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

                {{-- فلاتر الفئات (فقط الفئات التي لديها مقالات) --}}
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
                                <th>المرفقات</th>
                                <th style="width: 190px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($articles as $article)
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

                                    {{-- الصور --}}
                                    <td>
                                        @if (isset($article->images_count))
                                            <span class="badge badge-info">{{ $article->images_count }}</span>
                                        @else
                                            <span class="badge badge-info">{{ $article->images()->count() }}</span>
                                        @endif

                                        <button class="btn btn-sm btn-outline-secondary ml-2" data-toggle="modal"
                                            data-target="#imagesModal{{ $article->id }}">
                                            إدارة الصور
                                        </button>
                                    </td>

                                    {{-- المرفقات --}}
                                    <td>
                                        @if (isset($article->attachments_count))
                                            <span class="badge badge-secondary">{{ $article->attachments_count }}</span>
                                        @else
                                            <span
                                                class="badge badge-secondary">{{ $article->attachments()->count() }}</span>
                                        @endif

                                        {{-- نفتح مودال التعديل لإدارة المرفقات (رفع/تنزيل/حذف) --}}
                                        <button class="btn btn-sm btn-outline-secondary ml-2" data-toggle="modal"
                                            data-target="#editArticleModal{{ $article->id }}">
                                            إدارة المرفقات
                                        </button>
                                    </td>

                                    {{-- الإجراءات --}}
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showArticleModal{{ $article->id }}" title="عرض"
                                            data-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <button class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editArticleModal{{ $article->id }}" title="تعديل"
                                            data-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteArticleModal{{ $article->id }}" title="حذف"
                                            data-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>

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

                                        {{-- إدارة الصور (مودال منفصل لو عندك) --}}
                                        @include('dashboard.articles.modals.images', [
                                            'article' => $article,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد مقالات</td>
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
