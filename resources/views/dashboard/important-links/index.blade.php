@extends('layouts.app')

@section('title', 'إدارة الروابط المهمة')

@section('content')
@include('dashboard.important-links._styles')


<div class="container-fluid dashboard-container" dir="rtl">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="text-right">
            <h1 class="page-title mb-1">
                <i class="fas fa-link text-emerald-500 ml-2"></i>إدارة الروابط المهمة
            </h1>
            <p class="text-muted mb-0">قم بإدارة الروابط المهمة وتطبيقات الصفحة الرئيسية وتصنيفاتها</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.important-links.categories.index') }}" class="btn btn-premium-secondary d-flex align-items-center gap-2">
                <i class="fas fa-folder ml-1 text-emerald-500"></i> إدارة الفئات
            </a>
            <a href="{{ route('dashboard.important-links.create') }}" class="btn btn-premium-primary d-flex align-items-center gap-2 mr-2">
                <i class="fas fa-plus-circle ml-1"></i> إضافة رابط جديد
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-xl mb-4" role="alert" style="background-color: #ecfdf5; color: #065f46;" dir="rtl">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-lg ml-3"></i>
                <span class="font-weight-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="close text-success" data-dismiss="alert" style="opacity: 0.8;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-xl mb-4" role="alert" style="background-color: #fef2f2; color: #991b1b;" dir="rtl">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fa-lg ml-3"></i>
                <span class="font-weight-bold">{{ session('error') }}</span>
            </div>
            <button type="button" class="close text-danger" data-dismiss="alert" style="opacity: 0.8;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4 text-right" dir="rtl">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card-premium bg-white border-right-primary py-2" style="border-right: 0.35rem solid #10b981 !important;">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-emerald-600 text-uppercase mb-1">إجمالي الروابط</div>
                            <div class="h5 mb-0 font-weight-bold text-slate-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-emerald-50 text-emerald-500 rounded-circle p-3"><i class="fas fa-link fa-xl"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card-premium bg-white border-right-success py-2" style="border-right: 0.35rem solid #34d399 !important;">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">روابط نشطة</div>
                            <div class="h5 mb-0 font-weight-bold text-slate-800">{{ $stats['active'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-emerald-50 text-emerald-400 rounded-circle p-3"><i class="fas fa-check-circle fa-xl"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card-premium bg-white border-right-warning py-2" style="border-right: 0.35rem solid #f59e0b !important;">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">روابط معطلة</div>
                            <div class="h5 mb-0 font-weight-bold text-slate-800">{{ $stats['inactive'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-amber-50 text-amber-500 rounded-circle p-3"><i class="fas fa-times-circle fa-xl"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card-premium bg-white border-right-info py-2" style="border-right: 0.35rem solid #0ea5e9 !important;">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-sky-600 text-uppercase mb-1">الفئات الفعالة</div>
                            <div class="h5 mb-0 font-weight-bold text-slate-800">{{ $stats['categories'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-sky-50 text-sky-500 rounded-circle p-3"><i class="fas fa-folder fa-xl"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Suggestions --}}
    @if(isset($pendingLinks) && $pendingLinks->count() > 0)
        <div class="card card-premium mb-4 border-right-info" style="border-right: 4px solid #0ea5e9 !important;">
            <div class="card-header bg-light py-3">
                <h6 class="mb-0 font-weight-bold text-sky-600 text-right w-100">
                    <i class="fas fa-clock ml-2"></i>اقتراحات بانتظار الموافقة ({{ $pendingLinks->count() }})
                </h6>
            </div>
            <div class="card-body p-4 text-right">
                <div class="list-group">
                    @foreach($pendingLinks as $link)
                        <div class="link-item-row" style="cursor: default;">
                            <div class="link-item-content">
                                <div class="d-flex align-items-center justify-content-center bg-slate-50 text-slate-700 rounded-xl border border-slate-100/50" style="width: 48px; height: 48px; min-width: 48px;">
                                    <i class="fas fa-clock text-sky-500" style="font-size: 1.25rem;"></i>
                                </div>
                                <div class="link-item-details">
                                    <h6 class="link-item-title">
                                        {{ $link->title }}
                                        @if($link->category)
                                            <span class="badge badge-pill badge-secondary px-2.5 py-1 text-nowrap" style="background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
                                                الفئة المقترحة: {{ $link->category->name }}
                                            </span>
                                        @endif
                                    </h6>
                                    <p class="link-item-url">{{ $link->url }}</p>
                                    <div class="link-item-meta text-muted small">
                                        @if($link->submitter)
                                            <span><i class="fas fa-user ml-1 text-slate-400"></i><span class="font-weight-bold text-slate-600">المقترِح:</span> {{ $link->submitter->name }} — {{ $link->submitter->phone ?? '-' }}</span>
                                        @endif
                                        @if($link->media->isNotEmpty())
                                            <span class="text-sky-600 font-weight-bold"><i class="fas fa-photo-video ml-1"></i>{{ $link->media->count() }} وسائط مرفقة</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="link-item-actions">
                                <a href="{{ route('dashboard.important-links.edit', $link) }}" class="btn btn-premium-info btn-sm text-nowrap">
                                    <i class="fas fa-eye ml-1"></i>مراجعة
                                </a>
                                <form action="{{ route('dashboard.important-links.approve', $link) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-premium-success btn-sm text-nowrap"><i class="fas fa-check ml-1"></i>اعتماد</button>
                                </form>
                                <form action="{{ route('dashboard.important-links.reject', $link) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رفض هذا الاقتراح؟');">
                                    @csrf
                                    <button type="submit" class="btn btn-premium-danger btn-sm text-nowrap"><i class="fas fa-times ml-1"></i>رفض</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Links List Grouped by Category -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 text-right" dir="rtl">
        <div>
            <h5 class="font-weight-bold text-slate-800 mb-0">قائمة الروابط المصنفة</h5>
            <p class="text-muted small mb-0">اسحب عناصر الروابط داخل أي فئة لإعادة ترتيب ظهورها في الصفحة الرئيسية</p>
        </div>
        <button type="button" class="btn btn-premium-success d-flex align-items-center gap-2" onclick="saveOrder()" id="saveOrderBtn" style="display: none;">
            <i class="fas fa-save ml-1"></i> حفظ ترتيب الروابط المحدث
        </button>
    </div>

    @php
        $uncategorizedLinks = $links->whereNull('category_id');
    @endphp

    @if($links->count() > 0)
        <!-- Accordion for Categories -->
        <div class="accordion accordion-premium text-right" id="categoriesAccordion" dir="rtl">
            @foreach($categories as $category)
                @php
                    $categoryLinks = $links->where('category_id', $category->id);
                @endphp
                <div class="card card-premium shadow-sm mb-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" id="heading-{{ $category->id }}">
                        <h6 class="mb-0 font-weight-bold text-slate-800 flex-grow-1" style="cursor: pointer;" data-toggle="collapse" data-target="#collapse-{{ $category->id }}" aria-expanded="true" aria-controls="collapse-{{ $category->id }}">
                            <div class="d-inline-flex align-items-center justify-content-center bg-emerald-50 text-emerald-600 rounded-lg p-2 ml-3 border border-emerald-100" style="width: 34px; height: 34px;">
                                <i class="{{ $category->icon ?: 'fas fa-folder' }}"></i>
                            </div>
                            <span style="font-size: 1.05rem;">{{ $category->name }}</span>
                            <span class="badge badge-pill badge-light border ml-3 px-2.5 py-1 text-slate-600" style="font-size: 0.8rem;">{{ $categoryLinks->count() }} روابط</span>
                            @if(!$category->is_active)
                                <span class="badge badge-pill badge-warning ml-1 px-2.5 py-1" style="font-size: 0.8rem;">معطلة ومخفية</span>
                            @endif
                        </h6>
                        <a href="#collapse-{{ $category->id }}" class="text-muted ml-2" data-toggle="collapse"><i class="fas fa-chevron-down"></i></a>
                    </div>

                    <div id="collapse-{{ $category->id }}" class="collapse show" aria-labelledby="heading-{{ $category->id }}">
                        <div class="card-body p-3 bg-light/30 border-top">
                            <div id="links-list-{{ $category->id }}" class="list-group links-list-sortable">
                                @forelse($categoryLinks as $link)
                                    @include('dashboard.important-links._link_row', ['link' => $link])
                                @empty
                                    <div class="text-center py-5 text-muted bg-white border border-dashed rounded-xl">
                                        <i class="fas fa-info-circle fa-2x mb-3 text-slate-300"></i> 
                                        <p class="font-weight-bold mb-1">لا توجد روابط في هذه الفئة</p>
                                        <p class="small text-muted mb-0">يمكنك تعديل أي رابط وإسناده لهذه الفئة</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Uncategorized Links -->
            <div class="card card-premium shadow-sm mb-3">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" id="heading-uncategorized">
                    <h6 class="mb-0 font-weight-bold text-slate-800 flex-grow-1" style="cursor: pointer;" data-toggle="collapse" data-target="#collapse-uncategorized" aria-expanded="true" aria-controls="collapse-uncategorized">
                        <div class="d-inline-flex align-items-center justify-content-center bg-slate-50 text-slate-500 rounded-lg p-2 ml-3 border border-slate-200" style="width: 34px; height: 34px;">
                            <i class="fas fa-link"></i>
                        </div>
                        <span style="font-size: 1.05rem;">روابط عامة (غير مصنفة)</span>
                        <span class="badge badge-pill badge-light border ml-3 px-2.5 py-1 text-slate-600" style="font-size: 0.8rem;">{{ $uncategorizedLinks->count() }} روابط</span>
                    </h6>
                    <a href="#collapse-uncategorized" class="text-muted ml-2" data-toggle="collapse"><i class="fas fa-chevron-down"></i></a>
                </div>

                <div id="collapse-uncategorized" class="collapse show" aria-labelledby="heading-uncategorized">
                    <div class="card-body p-3 bg-light/30 border-top">
                        <div id="links-list-uncategorized" class="list-group links-list-sortable">
                            @forelse($uncategorizedLinks as $link)
                                @include('dashboard.important-links._link_row', ['link' => $link])
                            @empty
                                <div class="text-center py-5 text-muted bg-white border border-dashed rounded-xl">
                                    <i class="fas fa-info-circle fa-2x mb-3 text-slate-300"></i>
                                    <p class="font-weight-bold mb-1">لا توجد روابط غير مصنفة</p>
                                    <p class="small text-muted mb-0">جميع الروابط الحالية تم إسنادها إلى فئات</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card card-premium shadow-sm border-0">
            <div class="card-body text-center py-5">
                <div class="bg-emerald-50 text-emerald-500 rounded-full d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                    <i class="fas fa-link fa-3x"></i>
                </div>
                <h5 class="font-weight-bold text-slate-700 mb-2">لا توجد روابط حالياً</h5>
                <p class="text-muted mb-4">ابدأ بإضافة روابط جديدة لعرضها في الصفحة الرئيسية</p>
                <a href="{{ route('dashboard.important-links.create') }}" class="btn btn-premium-primary">
                    <i class="fas fa-plus-circle ml-1"></i>إضافة رابط جديد
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // تفعيل السحب والإفلات لترتيب الروابط داخل كل فئة
    document.querySelectorAll('.links-list-sortable').forEach(function(el) {
        Sortable.create(el, {
            handle: '.fa-grip-vertical',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                document.getElementById('saveOrderBtn').style.display = 'block';
            }
        });
    });

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
