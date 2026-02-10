@extends('layouts.app')

@section('title', 'إدارة أقسام الصفحة الرئيسية')

@push('styles')
<style>
    .section-card-item {
        border: 2px solid #e8ecf1;
        border-radius: 16px;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        margin-bottom: 16px;
    }
    .section-card-item:hover {
        border-color: #4e73df;
        box-shadow: 0 8px 25px rgba(78, 115, 223, 0.15);
        transform: translateY(-2px);
    }
    .section-card-item.inactive {
        opacity: 0.6;
        border-color: #e2e2e2;
    }
    .section-card-item.inactive:hover {
        opacity: 0.9;
    }
    .section-header-row {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        gap: 16px;
    }
    .drag-handle {
        color: #c8ccd4;
        cursor: grab;
        font-size: 1.2rem;
        transition: color 0.2s;
        flex-shrink: 0;
    }
    .drag-handle:hover { color: #4e73df; }
    .drag-handle:active { cursor: grabbing; }
    .section-info { flex: 1; min-width: 0; }
    .section-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .section-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 6px;
    }
    .section-meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 0.8rem;
        color: #718096;
    }
    .section-actions {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
    }
    .section-actions .btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        padding: 0;
        transition: all 0.2s ease;
    }
    .section-actions .btn:hover {
        transform: scale(1.1);
    }
    .order-badge {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        background: #f0f2f8;
        color: #4e73df;
        flex-shrink: 0;
    }
    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
    }
    .items-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.72rem;
        font-weight: 600;
        background: #edf2f7;
        color: #4a5568;
    }
    
    .sortable-ghost {
        opacity: 0.3;
        background: #e9ecef !important;
        border-color: #4e73df !important;
    }
    .sortable-chosen {
        cursor: grabbing;
        box-shadow: 0 12px 30px rgba(0,0,0,0.2) !important;
    }
    .save-order-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 14px 24px;
        display: none;
        align-items: center;
        justify-content: center;
        gap: 16px;
        z-index: 1050;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.2);
        animation: slideUp 0.3s ease;
    }
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    .section-type-colors {
        rich_text: '#4e73df', gallery: '#1cc88a', cards: '#36b9cc',
        table: '#f6c23e', text_with_image: '#e74a3b', video_section: '#fd7e14',
        hero: '#6f42c1', buttons: '#20c997', stats: '#17a2b8',
        divider: '#858796', custom: '#5a5c69', mixed: '#e83e8c',
        text: '#5a5c69'
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-magic text-primary mr-2"></i>بناء الصفحة الرئيسية
            </h1>
            <p class="text-muted mb-0">قم بتصميم أقسام الصفحة الرئيسية بالسحب والإفلات</p>
        </div>
        <a href="{{ route('dashboard.home-sections.create') }}" class="btn btn-primary shadow" style="border-radius: 10px;">
            <i class="fas fa-plus-circle mr-2"></i>قسم جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <x-stats-card
            icon="fas fa-th-large"
            title="إجمالي الأقسام"
            :value="$stats['total'] ?? 0"
            color="primary"
        />
        <x-stats-card
            icon="fas fa-check-circle"
            title="أقسام نشطة"
            :value="$stats['active'] ?? 0"
            color="success"
        />
        <x-stats-card
            icon="fas fa-eye-slash"
            title="أقسام معطلة"
            :value="$stats['inactive'] ?? 0"
            color="warning"
        />
    </div>

    <!-- Sections List -->
    @if($sections->count() > 0)
        <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-layer-group mr-2"></i>أقسام الصفحة الرئيسية
                        <span class="badge badge-light ml-1">{{ $sections->count() }}</span>
                    </h6>
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <small class="text-muted">
                            <i class="fas fa-grip-vertical mr-1"></i>اسحب لتغيير الترتيب
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div id="sections-list">
                    @php
                        $typeColors = [
                            'rich_text' => '#4e73df', 'gallery' => '#1cc88a', 'cards' => '#36b9cc',
                            'table' => '#f6c23e', 'text_with_image' => '#e74a3b', 'video_section' => '#fd7e14',
                            'hero' => '#6f42c1', 'buttons' => '#20c997', 'stats' => '#17a2b8',
                            'divider' => '#858796', 'custom' => '#5a5c69', 'mixed' => '#e83e8c',
                            'text' => '#5a5c69',
                        ];
                        $typeNames = [
                            'rich_text' => 'نص غني', 'gallery' => 'معرض صور', 'cards' => 'بطاقات',
                            'table' => 'جدول', 'text_with_image' => 'نص + صورة', 'video_section' => 'فيديو',
                            'hero' => 'بانر', 'buttons' => 'أزرار', 'stats' => 'إحصائيات',
                            'divider' => 'فاصل', 'custom' => 'HTML', 'mixed' => 'مختلط',
                            'text' => 'نص',
                        ];
                    @endphp
                    @foreach($sections as $section)
                        <div class="section-card-item {{ !$section->is_active ? 'inactive' : '' }}" 
                             data-id="{{ $section->id }}">
                            <div class="section-header-row">
                                <i class="fas fa-grip-vertical drag-handle" title="اسحب لتغيير الترتيب"></i>
                                
                                <div class="order-badge">{{ $section->display_order }}</div>
                                
                                <div class="section-info">
                                    <div class="section-title-row">
                                        <h6 class="section-title">{{ $section->title }}</h6>
                                        <span class="type-badge" style="background: {{ $typeColors[$section->section_type] ?? '#5a5c69' }}15; color: {{ $typeColors[$section->section_type] ?? '#5a5c69' }};">
                                            {{ $typeNames[$section->section_type] ?? $section->section_type }}
                                        </span>
                                        <span class="badge badge-{{ $section->is_active ? 'success' : 'secondary' }}" style="font-size: 0.68rem;">
                                            {{ $section->is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </div>
                                    <div class="section-meta">
                                        <span class="section-meta-item">
                                            <i class="fas fa-puzzle-piece"></i>
                                            {{ $section->items->count() }} عنصر
                                        </span>
                                        @if(isset($section->settings['subtitle']) && $section->settings['subtitle'])
                                            <span class="section-meta-item">
                                                <i class="fas fa-align-right"></i>
                                                {{ Str::limit($section->settings['subtitle'], 40) }}
                                            </span>
                                        @endif
                                        <span class="section-meta-item">
                                            <i class="fas fa-clock"></i>
                                            {{ $section->updated_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="section-actions">
                                    <a href="{{ route('dashboard.home-sections.edit', $section) }}" 
                                       class="btn btn-outline-primary" title="بناء / تعديل">
                                        <i class="fas fa-magic"></i>
                                    </a>
                                    <form action="{{ route('dashboard.home-sections.toggle', $section) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-{{ $section->is_active ? 'warning' : 'success' }}" 
                                                title="{{ $section->is_active ? 'تعطيل' : 'تفعيل' }}">
                                            <i class="fas fa-{{ $section->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.home-sections.duplicate', $section) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-info" title="نسخ">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.home-sections.destroy', $section) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('هل أنت متأكد؟ سيتم حذف القسم وجميع عناصره.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="حذف">
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
    @else
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-body text-center py-5">
                <div style="width: 100px; height: 100px; background: #eef2ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fas fa-magic text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h4 class="text-gray-800 font-weight-bold mb-2">ابدأ بناء صفحتك!</h4>
                <p class="text-muted mb-4">قم بإنشاء أقسام مخصصة للصفحة الرئيسية بسهولة تامة</p>
                <a href="{{ route('dashboard.home-sections.create') }}" class="btn btn-primary btn-lg shadow" style="border-radius: 12px;">
                    <i class="fas fa-plus-circle mr-2"></i>إنشاء أول قسم
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Save Order Bar (Fixed Bottom) -->
<div class="save-order-bar" id="saveOrderBar">
    <i class="fas fa-arrows-alt" style="font-size: 1.2rem;"></i>
    <span class="font-weight-bold">تم تغيير الترتيب</span>
    <button type="button" class="btn btn-light btn-sm font-weight-bold" onclick="saveOrder()" style="border-radius: 8px;">
        <i class="fas fa-save mr-1"></i>حفظ الترتيب
    </button>
    <button type="button" class="btn btn-outline-light btn-sm" onclick="location.reload()" style="border-radius: 8px;">
        <i class="fas fa-undo mr-1"></i>تراجع
    </button>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    const sectionsList = document.getElementById('sections-list');
    if (sectionsList) {
        new Sortable(sectionsList, {
            handle: '.drag-handle',
            animation: 300,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                document.getElementById('saveOrderBar').style.display = 'flex';
                // Update order badges
                document.querySelectorAll('#sections-list .section-card-item').forEach((item, index) => {
                    const badge = item.querySelector('.order-badge');
                    if (badge) badge.textContent = index + 1;
                });
            }
        });
    }

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
                document.getElementById('saveOrderBar').style.display = 'none';
                // Show success toast
                const toast = document.createElement('div');
                toast.style.cssText = 'position:fixed;bottom:20px;left:20px;background:#1cc88a;color:#fff;padding:12px 24px;border-radius:10px;z-index:9999;font-weight:600;box-shadow:0 4px 12px rgba(0,0,0,0.15);';
                toast.innerHTML = '<i class="fas fa-check-circle mr-2"></i>تم حفظ الترتيب بنجاح';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2500);
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
@endpush
