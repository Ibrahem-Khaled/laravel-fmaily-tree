@extends('layouts.app')

@section('title', 'إدارة مجالس العائلة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-building text-primary mr-2"></i>إدارة مجالس العائلة
            </h1>
            <p class="text-muted mb-0">قم بإدارة مجالس العائلة المعروضة في الموقع</p>
        </div>
        <a href="{{ route('dashboard.councils.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>إضافة مجلس جديد
        </a>
    </div>

    <x-alerts />

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left: 0.25rem solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي المجالس
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
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
                                مجالس نشطة
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
                                مجالس غير نشطة
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-left: 0.25rem solid #36b9cc !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                مجالس بصور
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['with_images'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-images fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Councils Grid -->
    @if($councils->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-building mr-2"></i>المجالس ({{ $councils->count() }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                            <i class="fas fa-save mr-1"></i>حفظ الترتيب
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="councils-grid" class="row">
                        @foreach($councils as $council)
                            <div class="col-md-6 col-lg-4 mb-4" data-id="{{ $council->id }}">
                                <div class="card h-100 shadow-sm border-0 council-card"
                                     style="transition: all 0.3s ease; border-radius: 12px; overflow: hidden;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title font-weight-bold mb-0 text-dark" style="font-size: 1rem;">
                                                <i class="fas fa-building text-primary mr-2"></i>
                                                {{ $council->name }}
                                            </h6>
                                            <div>
                                                <i class="fas fa-grip-vertical text-muted"
                                                   style="cursor: move; opacity: 0.7; font-size: 0.9rem;"
                                                   title="اسحب لإعادة الترتيب"></i>
                                            </div>
                                        </div>

                                        @if($council->description)
                                            <p class="card-text text-muted small mb-2" style="font-size: 0.85rem; line-height: 1.5;">
                                                {{ Str::limit(strip_tags($council->description), 100) }}
                                            </p>
                                        @endif

                                        @if($council->address)
                                            <p class="card-text text-muted small mb-2" style="font-size: 0.8rem;">
                                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                {{ Str::limit($council->address, 80) }}
                                            </p>
                                        @endif

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="badge badge-{{ $council->is_active ? 'success' : 'secondary' }} shadow-sm px-2 py-1" style="font-size: 0.75rem;">
                                                    {{ $council->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                                <span class="badge badge-dark shadow-sm px-2 py-1 ml-1" style="font-size: 0.75rem;">
                                                    #{{ $council->display_order }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="badge badge-info shadow-sm px-2 py-1" style="font-size: 0.75rem;">
                                                    <i class="fas fa-images mr-1"></i>{{ $council->images->count() }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div class="btn-group btn-group-sm shadow-sm">
                                                <a href="{{ route('dashboard.councils.manage', $council) }}"
                                                   class="btn btn-outline-primary border-0"
                                                   title="إدارة الصور"
                                                   style="border-radius: 6px 0 0 6px;">
                                                    <i class="fas fa-folder-open"></i>
                                                </a>
                                                <a href="{{ route('dashboard.councils.edit', $council) }}"
                                                   class="btn btn-outline-info border-0"
                                                   title="تعديل"
                                                   style="border-radius: 0;">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('dashboard.councils.destroy', $council) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المجلس؟')">
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
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-building text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد مجالس حالياً</h5>
                <p class="text-muted mb-4">ابدأ بإضافة مجلس جديد</p>
                <a href="{{ route('dashboard.councils.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة مجلس جديد
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    (function () {
        var grid = document.getElementById('councils-grid');
        if (!grid) {
            return;
        }

        new Sortable(grid, {
            handle: '.fa-grip-vertical',
            animation: 300,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function () {
                document.getElementById('saveOrderBtn').style.display = 'inline-block';
            }
        });
    })();

    function saveOrder() {
        var items = document.querySelectorAll('#councils-grid [data-id]');
        var orders = Array.from(items).map(function (item) { return item.dataset.id; });

        fetch('{{ route("dashboard.councils.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ orders: orders })
        })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (data.success) {
                alert('تم حفظ الترتيب بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ أثناء حفظ الترتيب');
            }
        })
        .catch(function (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حفظ الترتيب');
        });
    }
</script>
@endpush

@push('styles')
<style>
    .council-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
    }
    .council-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.2) !important;
    }
    .council-card .btn-group-sm .btn {
        transition: all 0.2s ease;
    }
    .council-card .btn-group-sm .btn:hover {
        transform: scale(1.1);
        z-index: 10;
    }
    .sortable-ghost {
        opacity: 0.4;
        background: #e9ecef;
    }
    .sortable-chosen {
        cursor: grabbing;
    }
</style>
@endpush
@endsection
