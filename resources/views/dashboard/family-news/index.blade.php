@extends('layouts.app')

@section('title', 'إدارة أخبار العائلة')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-newspaper text-primary mr-2"></i>إدارة أخبار العائلة
            </h1>
            <p class="text-muted mb-0">قم بإدارة أخبار العائلة وإضافة أخبار جديدة</p>
        </div>
        <a href="{{ route('dashboard.family-news.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>إضافة خبر جديد
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left: 0.25rem solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الأخبار
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-left: 0.25rem solid #1cc88a !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                أخبار نشطة
                            </div>
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
            <div class="card border-left-warning shadow h-100 py-2" style="border-left: 0.25rem solid #f6c23e !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                أخبار منشورة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['published'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-left: 0.25rem solid #36b9cc !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                إجمالي المشاهدات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_views'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- News Grid -->
    @if($news->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-newspaper mr-2"></i>الأخبار ({{ $news->count() }})
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @foreach($news as $item)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm border-0 {{ !$item->is_active ? 'opacity-50' : '' }}"
                                     style="transition: all 0.3s ease; border-radius: 12px; overflow: hidden;">
                                    @if($item->main_image_url)
                                        <div class="position-relative" style="overflow: hidden; height: 200px;">
                                            <img src="{{ $item->main_image_url }}"
                                                 alt="{{ $item->title }}"
                                                 class="w-100 h-100"
                                                 style="object-fit: cover; transition: transform 0.3s ease;">
                                            <div class="position-absolute top-0 left-0 m-2">
                                                <span class="badge badge-{{ $item->is_active ? 'success' : 'secondary' }} shadow-sm px-3 py-2" style="font-size: 0.75rem;">
                                                    <i class="fas fa-{{ $item->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                                    {{ $item->is_active ? 'نشط' : 'معطل' }}
                                                </span>
                                            </div>
                                            @if($item->images->count() > 0)
                                                <div class="position-absolute top-0 right-0 m-2">
                                                    <span class="badge badge-info shadow-sm px-2 py-1" style="font-size: 0.75rem;">
                                                        <i class="fas fa-images mr-1"></i>{{ $item->images->count() }} صور
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-gradient-to-br from-green-400 to-emerald-600" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-newspaper text-white" style="font-size: 3rem; opacity: 0.5;"></i>
                                        </div>
                                    @endif

                                    <div class="card-body p-3">
                                        <h6 class="card-title font-weight-bold mb-2 text-dark" style="font-size: 1rem;">
                                            <i class="fas fa-newspaper text-primary mr-2"></i>
                                            {{ Str::limit($item->title, 60) }}
                                        </h6>

                                        @if($item->summary)
                                            <p class="card-text text-muted small mb-2" style="font-size: 0.85rem; line-height: 1.5;">
                                                {{ Str::limit($item->summary, 100) }}
                                            </p>
                                        @endif

                                        <div class="mb-2">
                                            @if($item->published_at)
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-calendar text-primary mr-1"></i>
                                                    {{ $item->published_at->format('Y-m-d') }}
                                                </small>
                                            @endif
                                            <small class="text-muted d-block mb-1">
                                                <i class="fas fa-eye text-info mr-1"></i>{{ $item->views_count }} مشاهدة
                                            </small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <a href="{{ route('dashboard.family-news.edit', $item) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit mr-1"></i>تعديل
                                            </a>
                                            <div class="btn-group btn-group-sm shadow-sm">
                                                <form action="{{ route('dashboard.family-news.toggle', $item) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn btn-outline-{{ $item->is_active ? 'warning' : 'success' }} border-0"
                                                            title="{{ $item->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                        <i class="fas fa-{{ $item->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('dashboard.family-news.destroy', $item) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الخبر؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger border-0"
                                                            title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-newspaper text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد أخبار حالياً</h5>
                <p class="text-muted mb-4">ابدأ بإضافة أخبار جديدة</p>
                <a href="{{ route('dashboard.family-news.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة خبر جديد
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
