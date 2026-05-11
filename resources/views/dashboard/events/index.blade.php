@extends('layouts.app')

@section('title', 'إدارة مناسبات العائلة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-calendar-alt text-primary mr-2"></i>إدارة مناسبات العائلة
            </h1>
            <p class="text-muted mb-0">قم بإدارة مناسبات العائلة المعروضة في الموقع</p>
        </div>
        <a href="{{ route('dashboard.events.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>إضافة مناسبة جديدة
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
                                إجمالي المناسبات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
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
                                مناسبات نشطة
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
                                مناسبات قادمة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['upcoming'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                مع عداد تنازلي
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['with_countdown'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    @if($events->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-calendar-alt mr-2"></i>المناسبات ({{ $events->count() }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                            <i class="fas fa-save mr-1"></i>حفظ الترتيب
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="events-grid" class="row">
                        @foreach($events as $event)
                            <div class="col-md-6 col-lg-4 mb-4" data-id="{{ $event->id }}">
                                <div class="card h-100 shadow-sm border-0 event-card"
                                     style="transition: all 0.3s ease; border-radius: 12px; overflow: hidden;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title font-weight-bold mb-0 text-dark" style="font-size: 1rem;">
                                                <i class="fas fa-calendar-alt text-primary mr-2"></i>
                                                {{ $event->title }}
                                            </h6>
                                            <div>
                                                <i class="fas fa-grip-vertical text-muted"
                                                   style="cursor: move; opacity: 0.7; font-size: 0.9rem;"
                                                   title="اسحب لإعادة الترتيب"></i>
                                            </div>
                                        </div>

                                        @if($event->description)
                                            <p class="card-text text-muted small mb-2" style="font-size: 0.85rem; line-height: 1.5;">
                                                {{ Str::limit(strip_tags($event->description), 100) }}
                                            </p>
                                        @endif

                                        <div class="mb-2">
                                            @if($event->city)
                                                <p class="card-text text-muted small mb-1" style="font-size: 0.8rem;">
                                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                    {{ $event->city }}
                                                </p>
                                            @endif
                                            @if($event->location)
                                                <p class="card-text text-muted small mb-1" style="font-size: 0.8rem;">
                                                    <i class="fas fa-location-dot text-info mr-1"></i>
                                                    <a href="{{ $event->location }}" target="_blank" rel="noopener noreferrer" class="text-info">
                                                        عرض الموقع على الخريطة
                                                        <i class="fas fa-external-link-alt mr-1" style="font-size: 0.7rem;"></i>
                                                    </a>
                                                </p>
                                            @endif
                                            <p class="card-text text-muted small mb-1" style="font-size: 0.8rem;">
                                                <i class="fas fa-clock text-warning mr-1"></i>
                                                {{ $event->event_date->format('Y-m-d H:i') }}
                                            </p>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="badge badge-{{ $event->is_active ? 'success' : 'secondary' }} shadow-sm px-2 py-1" style="font-size: 0.75rem;">
                                                    {{ $event->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                                <span class="badge badge-dark shadow-sm px-2 py-1 ml-1" style="font-size: 0.75rem;">
                                                    #{{ $event->display_order }}
                                                </span>
                                                @if($event->show_countdown)
                                                    <span class="badge badge-info shadow-sm px-2 py-1 ml-1" style="font-size: 0.75rem;">
                                                        <i class="fas fa-hourglass-half mr-1"></i>عداد
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div class="btn-group btn-group-sm shadow-sm">
                                                <a href="{{ route('dashboard.events.edit', $event) }}"
                                                   class="btn btn-outline-info border-0"
                                                   title="تعديل"
                                                   style="border-radius: 6px 0 0 6px;">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('dashboard.events.destroy', $event) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه المناسبة؟')">
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
                <i class="fas fa-calendar-alt text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد مناسبات حالياً</h5>
                <p class="text-muted mb-4">ابدأ بإضافة مناسبة جديدة</p>
                <a href="{{ route('dashboard.events.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة مناسبة جديدة
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    (function () {
        var grid = document.getElementById('events-grid');
        if (!grid) {
            return;
        }

        Sortable.create(grid, {
            animation: 150,
            handle: '.fa-grip-vertical',
            onEnd: function () {
                document.getElementById('saveOrderBtn').style.display = 'block';
            }
        });
    })();

    function saveOrder() {
        var order = Array.from(document.getElementById('events-grid').children).map(function (el) {
            return parseInt(el.getAttribute('data-id'), 10);
        });

        fetch('{{ route("dashboard.events.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ orders: order })
        })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (data.success) {
                document.getElementById('saveOrderBtn').style.display = 'none';
                location.reload();
            }
        })
        .catch(function (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حفظ الترتيب');
        });
    }
</script>
@endpush
@endsection
