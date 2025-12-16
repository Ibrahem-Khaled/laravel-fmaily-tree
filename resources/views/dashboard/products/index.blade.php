@extends('layouts.app')

@section('title', 'إدارة المنتجات')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-shopping-bag text-primary mr-2"></i>إدارة المنتجات
            </h1>
            <p class="text-muted mb-0">قم بإدارة منتجات الأسر المنتجة</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>إضافة منتج جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي المنتجات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">منتجات نشطة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">منتجات معطلة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">منتجات مؤجرة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rental'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $type === 'all' ? 'active' : '' }}"
                       href="{{ route('products.index', array_merge(request()->query(), ['type' => 'all'])) }}">
                        <i class="fas fa-list mr-1"></i>جميع المنتجات
                        <span class="badge badge-secondary">{{ $stats['total'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type === 'rental' ? 'active' : '' }}"
                       href="{{ route('products.index', array_merge(request()->query(), ['type' => 'rental'])) }}">
                        <i class="fas fa-hand-holding mr-1"></i>المنتجات المؤجرة
                        <span class="badge badge-info">{{ $stats['rental'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type === 'normal' ? 'active' : '' }}"
                       href="{{ route('products.index', array_merge(request()->query(), ['type' => 'normal'])) }}">
                        <i class="fas fa-shopping-cart mr-1"></i>المنتجات العادية
                        <span class="badge badge-primary">{{ $stats['normal'] ?? 0 }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}">
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="row">
                    <div class="col-md-3">
                        <label>الفئة</label>
                        <select name="category_id" class="form-control" onchange="this.form.submit()">
                            <option value="">جميع الفئات</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>الفئة الفرعية</label>
                        <select name="subcategory_id" class="form-control" onchange="this.form.submit()">
                            <option value="">جميع الفئات الفرعية</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ $subcategoryId == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>البحث</label>
                        <input type="text" name="search" class="form-control" placeholder="ابحث عن منتج..." value="{{ $search }}">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="productsTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="80">الصورة</th>
                                <th>اسم المنتج</th>
                                <th>الفئة</th>
                                <th>السعر</th>
                                <th>النوع</th>
                                <th>الحالة</th>
                                <th width="200">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr class="{{ !$product->is_active ? 'table-secondary' : '' }}">
                                    <td>
                                        @if($product->main_image)
                                            <img src="{{ asset('storage/' . $product->main_image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="img-thumbnail"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        @if($product->description)
                                            <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                        @endif
                                        @if($product->owner)
                                            <br><small class="text-info">
                                                <i class="fas fa-user"></i> {{ $product->owner->first_name }} {{ $product->owner->last_name }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <span class="badge badge-secondary">{{ $product->category->name }}</span>
                                            @if($product->subcategory)
                                                <br><span class="badge badge-light">{{ $product->subcategory->name }}</span>
                                            @endif
                                        @else
                                            <span class="text-danger">لا توجد فئة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($product->price, 2) }} ر.س</strong>
                                    </td>
                                    <td>
                                        @if($product->is_rental)
                                            <span class="badge badge-info">
                                                <i class="fas fa-hand-holding"></i> مؤجر
                                            </span>
                                        @else
                                            <span class="badge badge-primary">
                                                <i class="fas fa-shopping-cart"></i> عادي
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('products.toggle', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-{{ $product->is_active ? 'success' : 'secondary' }} toggle-btn"
                                                    title="{{ $product->is_active ? 'معطل' : 'فعّل' }}">
                                                <i class="fas fa-{{ $product->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                                {{ $product->is_active ? 'نشط' : 'معطل' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('products.edit', $product) }}"
                                               class="btn btn-sm btn-info"
                                               title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-muted">لا توجد منتجات حالياً</h5>
                    <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle mr-2"></i>إضافة منتج جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        padding: 0.75rem 1.5rem;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #dee2e6;
        color: #495057;
    }

    .nav-tabs .nav-link.active {
        border-bottom-color: #007bff;
        color: #007bff;
        font-weight: 600;
    }

    .nav-tabs .nav-link .badge {
        margin-right: 5px;
    }

    #productsTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    .toggle-btn {
        min-width: 90px;
    }

    .table-secondary {
        opacity: 0.7;
    }
</style>
@endpush
@endsection
