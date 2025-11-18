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

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}">
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

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
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
        <div class="col-xl-4 col-md-6 mb-4">
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
        <div class="col-xl-4 col-md-6 mb-4">
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
    </div>

    <!-- Products Grid -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 {{ !$product->is_active ? 'opacity-50' : '' }}" 
                                 style="border-radius: 12px; overflow: hidden;">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                                         alt="{{ $product->name }}"
                                         class="card-img-top" 
                                         style="height: 220px; object-fit: cover;">
                                @else
                                    <div class="bg-gradient-to-br from-green-400 to-emerald-600" 
                                         style="height: 220px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image text-white" style="font-size: 3rem; opacity: 0.5;"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title font-weight-bold mb-0">{{ $product->name }}</h6>
                                        <span class="badge badge-{{ $product->is_active ? 'success' : 'secondary' }}">
                                            {{ $product->is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-tag mr-1"></i>
                                        @if($product->category)
                                            {{ $product->category->name }}
                                            @if($product->subcategory)
                                                / {{ $product->subcategory->name }}
                                            @endif
                                        @else
                                            <span class="text-danger">لا توجد فئة</span>
                                        @endif
                                    </p>
                                    
                                    @if($product->owner)
                                        <p class="text-muted small mb-1">
                                            <i class="fas fa-user mr-1"></i>
                                            صاحب المنتج: {{ $product->owner->first_name }} {{ $product->owner->last_name }}
                                        </p>
                                    @endif
                                    
                                    @if($product->location)
                                        <p class="text-muted small mb-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            الموقع: {{ $product->location->name }}
                                        </p>
                                    @endif
                                    
                                    @if($product->description)
                                        <p class="card-text text-muted small mb-2">
                                            {{ Str::limit($product->description, 80) }}
                                        </p>
                                    @endif
                                    
                                    <div class="mb-2">
                                        <span class="h5 text-primary font-weight-bold">{{ number_format($product->price, 2) }} ر.س</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-edit"></i> تعديل
                                        </a>
                                        <form action="{{ route('products.toggle', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $product->is_active ? 'warning' : 'success' }}">
                                                <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" 
                                              class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    {{ $products->links() }}
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
@endsection

