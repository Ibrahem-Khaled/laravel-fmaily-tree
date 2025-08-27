@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الصور</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active" aria-current="page">الصور</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الصور --}}
        <div class="row mb-4">
            {{-- إجمالي الصور --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-images" title="إجمالي الصور" :value="$imagesCount" color="primary" />
            </div>
            {{-- عدد الأقسام --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-tags" title="عدد الأقسام" :value="$categoriesCount" color="info" />
            </div>
            {{-- القسم الأكثر احتواءً للصور --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-star" title="القسم الأكثر نشاطًا" :value="$mostImagesCategory->name ?? 'لا يوجد'" color="success" />
            </div>
        </div>

        {{-- بطاقة قائمة الصور --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الصور</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createImageModal">
                    <i class="fas fa-plus"></i> إضافة صورة
                </button>
                <a href="{{ route('categories.index', ['type' => 'images']) }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> إدارة فئات الصور
                </a>
            </div>
            <div class="card-body">
                {{-- تبويب الأقسام --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedCategory === 'all' ? 'active' : '' }}"
                            href="{{ route('images.index') }}">الكل</a>
                    </li>
                    @foreach ($categories as $category)
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedCategory == $category->id ? 'active' : '' }}"
                                href="{{ route('images.index', ['category' => $category->id]) }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- نموذج البحث --}}
                <form action="{{ route('images.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث باسم الصورة..."
                            value="{{ request('search') }}">
                        @if (request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول الصور --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>معاينة</th>
                                <th>الاسم</th>
                                <th>القسم</th>
                                <th>تاريخ الإضافة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($images as $image)
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->name }}"
                                            class="img-thumbnail" width="80">
                                    </td>
                                    <td>{{ $image->name ?? 'بدون اسم' }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $image->category->name ?? 'غير مصنف' }}</span>
                                    </td>
                                    <td>{{ $image->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showImageModal{{ $image->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editImageModal{{ $image->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteImageModal{{ $image->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- تضمين المودالات لكل صورة --}}
                                        @include('dashboard.images.modals.show', ['image' => $image])
                                        @include('dashboard.images.modals.edit', [
                                            'image' => $image,
                                            'categories' => $categories,
                                        ])
                                        @include('dashboard.images.modals.delete', ['image' => $image])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد صور لعرضها.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $images->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة صورة (ثابت) --}}
    @include('dashboard.images.modals.create', ['categories' => $categories])
@endsection

@push('scripts')
    {{-- عرض اسم الملف المختار في حقول upload --}}
    <script>
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    </script>

    <script>
        $(document).ready(function() {
            // تفعيل Select2 على حقل اختيار القسم
            $('#category_selection').select2({
                // هذه الخاصية ضرورية لكي يعمل البحث داخل مودال البوتستراب
                dropdownParent: $('#createImageModal'),

                // هذه الخاصية تسمح للمستخدم بإضافة عناصر جديدة غير موجودة في القائمة
                tags: true,

                placeholder: 'اختر قسماً أو أضف واحداً جديداً',
            });
        });
    </script>
@endpush
