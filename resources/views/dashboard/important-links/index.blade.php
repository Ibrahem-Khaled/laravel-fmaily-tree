@extends('layouts.app')

@section('title', 'إدارة الروابط المهمة')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-link text-primary mr-2"></i>إدارة الروابط المهمة
            </h1>
            <p class="text-muted mb-0">قم بإدارة الروابط المهمة التي تظهر في الصفحة الرئيسية</p>
        </div>
        <a href="{{ route('dashboard.important-links.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>إضافة رابط جديد
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
                                إجمالي الروابط
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-link fa-2x text-gray-300"></i>
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
                                روابط نشطة
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
                                روابط معطلة
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
                                بانتظار الموافقة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($pendingLinks) && $pendingLinks->count() > 0)
        <div class="card shadow-sm border-0 mb-4 border-right-info" style="border-right: 4px solid #36b9cc !important;">
            <div class="card-header bg-light">
                <h6 class="mb-0 font-weight-bold text-info">
                    <i class="fas fa-clock mr-2"></i>اقتراحات بانتظار الموافقة ({{ $pendingLinks->count() }})
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="list-group">
                    @foreach($pendingLinks as $link)
                        <div class="list-group-item mb-2 border rounded">
                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                <div class="mb-2 mb-md-0 flex-grow-1">
                                    <h6 class="font-weight-bold mb-1">{{ $link->title }}</h6>
                                    <p class="text-muted small mb-1">
                                        @if($link->type === 'app')
                                            @if($link->url)<span class="d-block">عام: {{ \Illuminate\Support\Str::limit($link->url, 60) }}</span>@endif
                                            @if($link->url_ios)<span class="d-block">iOS: {{ \Illuminate\Support\Str::limit($link->url_ios, 60) }}</span>@endif
                                            @if($link->url_android)<span class="d-block">أندرويد: {{ \Illuminate\Support\Str::limit($link->url_android, 60) }}</span>@endif
                                        @else
                                            {{ $link->url }}
                                        @endif
                                    </p>
                                    @if($link->submitter)
                                        <p class="mb-0 text-muted small"><span class="font-weight-bold">من أضافه:</span> {{ $link->submitter->name }} — {{ $link->submitter->phone ?? '-' }}</p>
                                    @endif
                                    @if($link->media->isNotEmpty())
                                        <p class="mb-0 small text-info mt-1"><i class="fas fa-photo-video mr-1"></i>{{ $link->media->count() }} وسائط — افتح المراجعة لمعاينتها</p>
                                    @endif
                                </div>
                                <div class="d-flex flex-wrap align-items-center gap-1">
                                    <a href="{{ route('dashboard.important-links.edit', $link) }}" class="btn btn-info btn-sm mb-1">
                                        <i class="fas fa-eye mr-1"></i>مراجعة ومعاينة
                                    </a>
                                    <form action="{{ route('dashboard.important-links.approve', $link) }}" method="POST" class="d-inline mb-1">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i>اعتماد</button>
                                    </form>
                                    <form action="{{ route('dashboard.important-links.reject', $link) }}" method="POST" class="d-inline mb-1" onsubmit="return confirm('هل أنت متأكد من رفض هذا الاقتراح؟');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times mr-1"></i>رفض</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Links List -->
    @if($links->count() > 0)
        <div class="mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-list mr-2"></i>الروابط ({{ $links->count() }})
                        </h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
                            <i class="fas fa-save mr-1"></i>حفظ الترتيب
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="links-list" class="list-group">
                        @foreach($links as $link)
                            <div class="list-group-item list-group-item-action mb-2 border rounded shadow-sm link-item {{ !$link->is_active ? 'opacity-50' : '' }}"
                                 data-id="{{ $link->id }}"
                                 style="cursor: move; transition: all 0.3s ease;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <i class="fas fa-grip-vertical text-muted mr-3" style="cursor: move;" title="اسحب لإعادة الترتيب"></i>
                                        <div class="mr-3">
                                            @if($link->icon)
                                                <i class="{{ $link->icon }} text-primary" style="font-size: 1.5rem;"></i>
                                            @else
                                                <i class="fas fa-link text-primary" style="font-size: 1.5rem;"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 font-weight-bold">
                                                {{ $link->title }}
                                                <span class="badge badge-{{ $link->is_active ? 'success' : 'secondary' }} ml-2">
                                                    {{ $link->is_active ? 'نشط' : 'معطل' }}
                                                </span>
                                                <span class="badge badge-{{ ($link->status ?? 'approved') === 'pending' ? 'warning' : 'dark' }} ml-1">
                                                    {{ ($link->type ?? 'website') === 'app' ? 'تطبيق' : 'موقع' }}
                                                </span>
                                                <span class="badge badge-dark ml-1">#{{ $link->order }}</span>
                                            </h6>
                                            <p class="mb-1 text-muted small">{{ \Illuminate\Support\Str::limit($link->url, 80) }}</p>
                                            @if($link->submitter)
                                                <p class="mb-1 text-muted small"><span class="font-weight-bold">من أضافه:</span> {{ $link->submitter->name }}</p>
                                            @endif
                                            @if($link->description)
                                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">{{ $link->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="btn-group btn-group-sm shadow-sm ml-3">
                                        <a href="{{ route('dashboard.important-links.edit', $link) }}"
                                           class="btn btn-outline-info border-0"
                                           title="تعديل"
                                           style="border-radius: 6px 0 0 6px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.important-links.toggle', $link) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-outline-{{ $link->is_active ? 'warning' : 'success' }} border-0"
                                                    title="{{ $link->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                <i class="fas fa-{{ $link->is_active ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('dashboard.important-links.destroy', $link) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الرابط؟')">
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-link text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">لا توجد روابط حالياً</h5>
                <p class="text-muted mb-4">ابدأ بإضافة روابط جديدة</p>
                <a href="{{ route('dashboard.important-links.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة رابط جديد
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    let sortable = null;
    @if($links->count() > 0)
        sortable = Sortable.create(document.getElementById('links-list'), {
            handle: '.fa-grip-vertical',
            animation: 150,
            onEnd: function() {
                document.getElementById('saveOrderBtn').style.display = 'block';
            }
        });
    @endif

    function saveOrder() {
        const items = document.querySelectorAll('.link-item');
        const orders = Array.from(items).map(item => item.getAttribute('data-id'));

        fetch('{{ route("dashboard.important-links.reorder") }}', {
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
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حفظ الترتيب');
        });
    }
</script>
@endpush
@endsection
