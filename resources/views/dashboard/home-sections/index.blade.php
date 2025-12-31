@extends('layouts.app')

@section('title', 'إدارة الأقسام الديناميكية')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-th-large text-primary mr-2"></i>إدارة الأقسام الديناميكية
            </h1>
            <p class="text-muted mb-0">قم بإدارة أقسام الصفحة الرئيسية الديناميكية</p>
        </div>
        <a href="{{ route('dashboard.home-sections.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>إضافة قسم جديد
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
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left: 0.25rem solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الأقسام
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-th-large fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-left: 0.25rem solid #1cc88a !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                أقسام نشطة
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

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="border-left: 0.25rem solid #f6c23e !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                أقسام معطلة
                            </div>
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

    <!-- Sections List -->
    @if($sections->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-th-large mr-2"></i>الأقسام ({{ $sections->count() }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                            <i class="fas fa-save mr-1"></i>حفظ الترتيب
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="sections-list">
                        @foreach($sections as $section)
                            <div class="card mb-3 shadow-sm border-0 section-card {{ !$section->is_active ? 'opacity-50' : '' }}" 
                                 data-id="{{ $section->id }}"
                                 style="transition: all 0.3s ease; border-radius: 12px; cursor: move;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-grip-vertical text-muted mr-3" style="cursor: move;" title="اسحب لإعادة الترتيب"></i>
                                                <h6 class="mb-0 font-weight-bold text-dark">
                                                    {{ $section->title }}
                                                </h6>
                                                <span class="badge badge-{{ $section->is_active ? 'success' : 'secondary' }} ml-2">
                                                    {{ $section->is_active ? 'نشط' : 'معطل' }}
                                                </span>
                                                <span class="badge badge-info ml-2">
                                                    {{ $section->section_type }}
                                                </span>
                                                <span class="badge badge-dark ml-2">
                                                    #{{ $section->display_order }}
                                                </span>
                                            </div>
                                            <div class="ml-5">
                                                <small class="text-muted">
                                                    <i class="fas fa-list mr-1"></i>
                                                    {{ $section->items->count() }} عنصر
                                                </small>
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('dashboard.home-sections.edit', $section) }}" 
                                               class="btn btn-outline-info border-0" 
                                               title="تعديل"
                                               style="border-radius: 6px 0 0 6px;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('dashboard.home-sections.toggle', $section) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-outline-{{ $section->is_active ? 'warning' : 'success' }} border-0"
                                                        title="{{ $section->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $section->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('dashboard.home-sections.destroy', $section) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟ سيتم حذف جميع العناصر المرتبطة به.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger border-0" 
                                                        title="حذف"
                                                        style="border-radius: 0 6px 6px 0;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
                <i class="fas fa-th-large text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد أقسام</h5>
                <p class="text-muted mb-4">ابدأ بإضافة قسم جديد</p>
                <a href="{{ route('dashboard.home-sections.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة قسم جديد
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Sortable JS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    // تفعيل السحب والإفلات
    const sectionsList = document.getElementById('sections-list');
    if (sectionsList) {
        new Sortable(sectionsList, {
            handle: '.fa-grip-vertical',
            animation: 300,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                document.getElementById('saveOrderBtn').style.display = 'inline-block';
            }
        });
    }

    // حفظ الترتيب
    function saveOrder() {
        const items = document.querySelectorAll('#sections-list [data-id]');
        const orders = Array.from(items).map(item => item.dataset.id);

        fetch('{{ route("dashboard.home-sections.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ orders: orders })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم حفظ الترتيب بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ أثناء حفظ الترتيب');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حفظ الترتيب');
        });
    }
</script>

<style>
    .section-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
    }
    
    .section-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
    }
    
    .sortable-ghost {
        opacity: 0.4;
        background: #e9ecef;
    }
    
    .sortable-chosen {
        cursor: grabbing;
    }
    
    .btn-group-sm .btn {
        transition: all 0.2s ease;
    }
    
    .btn-group-sm .btn:hover {
        transform: scale(1.1);
        z-index: 10;
    }
</style>
@endsection

