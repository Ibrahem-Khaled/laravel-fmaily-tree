@extends('layouts.app') {{-- تأكد من أن هذا هو ملف التخطيط الرئيسي لديك --}}

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الأقسام</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">لوحة التحكم</a> {{-- ضع هنا رابط لوحة التحكم الرئيسية --}}
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الأقسام</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts') {{-- تأكد من وجود هذا الملف لعرض رسائل النجاح والخطأ --}}

        {{-- إحصائيات الأقسام --}}
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-sitemap" title="إجمالي الأقسام" :value="$stats['total']" color="primary" />
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-layer-group" title="الأقسام الرئيسية" :value="$stats['main']" color="success" />
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <x-stats-card icon="fas fa-stream" title="الأقسام الفرعية" :value="$stats['sub']" color="info" />
            </div>
        </div>

        {{-- بطاقة قائمة الأقسام --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الأقسام</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createCategoryModal">
                    <i class="fas fa-plus"></i> إضافة قسم
                </button>
            </div>
            <div class="card-body">
                {{-- تبويب الأقسام الرئيسية للفلترة --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedParent === 'all' ? 'active' : '' }}"
                            href="{{ route('categories.index') }}">الكل</a>
                    </li>
                    @foreach ($mainCategories as $parent)
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedParent == $parent->id ? 'active' : '' }}"
                                href="{{ route('categories.index', ['parent_id' => $parent->id]) }}">
                                {{ $parent->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>

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

                {{-- جدول الأقسام --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>الصورة</th>
                                <th>الاسم</th>
                                <th>القسم الرئيسي</th>
                                <th>الوصف</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>
                                        <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('img/default-image.png') }}"
                                            alt="{{ $category->name }}" class="img-fluid rounded" width="60">
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        @if ($category->parent)
                                            <span class="badge badge-info">{{ $category->parent->name }}</span>
                                        @else
                                            <span class="badge badge-success">قسم رئيسي</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($category->description, 50) ?? '-' }}</td>
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

                                        {{-- تضمين المودالات لكل قسم --}}
                                        @include('dashboard.categories.modals.show', [
                                            'category' => $category,
                                        ])
                                        @include('dashboard.categories.modals.edit', [
                                            'category' => $category,
                                            'mainCategories' => $mainCategories,
                                        ])
                                        @include('dashboard.categories.modals.delete', [
                                            'category' => $category,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد أقسام لعرضها.</td>
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

    {{-- مودال إضافة قسم جديد (ثابت) --}}
    @include('dashboard.categories.modals.create', ['mainCategories' => $mainCategories])
@endsection

@push('scripts')
    {{-- سكربت لعرض اسم الملف المختار في حقول رفع الصور --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName || 'اختر صورة...');
            });
        });
    </script>
@endpush
