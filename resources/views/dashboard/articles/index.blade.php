@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
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

        {{-- إحصائيات المقالات --}}
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-file-alt" title="إجمالي المقالات" :value="$articlesCount" color="primary" />
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-folder" title="إجمالي الفئات" :value="$categoriesCount" color="success" />
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                {{-- يمكنك إضافة إحصائية ثالثة هنا --}}
            </div>
        </div>

        {{-- بطاقة قائمة المقالات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">قائمة المقالات</h6>
                <div>
                    {{-- ===== الزر الجديد لفتح مودال إضافة الصور ===== --}}
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addImagesModal">
                        <i class="fas fa-images"></i> إضافة صور لمقال
                    </button>

                    <button class="btn btn-primary" data-toggle="modal" data-target="#createArticleModal">
                        <i class="fas fa-plus"></i> إضافة مقال جديد
                    </button>

                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> إدارة الفئات
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- تبويب الفئات --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ !$selectedCategory || $selectedCategory === 'all' ? 'active' : '' }}"
                            href="{{ route('articles.index') }}">الكل</a>
                    </li>
                    @foreach ($mainCategories as $category)
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedCategory == $category->id ? 'active' : '' }}"
                                href="{{ route('articles.index', ['category' => $category->id]) }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- جدول المقالات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>العنوان</th>
                                <th>الفئة</th>
                                <th>عدد الصور</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $article)
                                <tr>
                                    <td>{{ $article->title }}</td>
                                    <td>{{ $article->category->name ?? '-' }}</td>
                                    <td><span class="badge badge-info">{{ $article->images->count() }}</span></td>
                                    <td>{{ $article->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showArticleModal{{ $article->id }}" title="عرض"><i
                                                class="fas fa-eye"></i></button>
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editArticleModal{{ $article->id }}" title="تعديل"><i
                                                class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteArticleModal{{ $article->id }}" title="حذف"><i
                                                class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                {{-- تضمين المودالات لكل مقال --}}
                                @include('dashboard.articles.modals.show')
                                @include('dashboard.articles.modals.edit')
                                @include('dashboard.articles.modals.delete')
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد مقالات لعرضها.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $articles->links() }}
            </div>
        </div>
    </div>

    {{-- مودال إضافة مقال (ثابت) --}}
    @include('dashboard.articles.modals.create')
    {{-- مودال إضافة فئة (ثابت) --}}
    @include('dashboard.articles.modals.create_category')

    {{-- ===== تضمين مودال إضافة الصور الجديد ===== --}}
    @include('dashboard.articles.modals.add_images')
@endsection

@push('scripts')
    @include('dashboard.articles.scripts')
@endpush
