@props([
    'title',
    'value',
    'icon',
    'color' => 'primary', // primary, success, info, warning, danger
])

<div {{ $attributes->merge(['class' => 'col-xl-2 col-md-4 col-sm-6 mb-4']) }}>
    <div class="glance-card p-3 rounded-lg border bg-white shadow-sm transition-all h-100 d-flex flex-column align-items-center text-center">
        <div class="glance-icon-circle bg-light-{{ $color }} text-{{ $color }} mb-2 shadow-sm">
            <i class="fe {{ $icon }} fe-16"></i>
        </div>
        <div class="glance-value h5 mb-0 font-weight-bold dark-text-light">{{ $value }}</div>
        <div class="glance-label small text-muted font-weight-600">{{ $title }}</div>
    </div>
</div>

@once('glance-card-styles')
<style>
    .glance-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .glance-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0,0,0,0.05) !important;
        border-color: var(--primary-color) !important;
    }
    .glance-icon-circle {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Dark mode adjustments */
    .dark .glance-card {
        background-color: #232a3e !important;
        border-color: rgba(255,255,255,0.05) !important;
    }
    .dark .dark-text-light {
        color: #e9ecef !important;
    }
    
    .bg-light-primary   { background-color: rgba(78,  115, 223, 0.12); }
    .bg-light-success   { background-color: rgba(28,  200, 138, 0.12); }
    .bg-light-info      { background-color: rgba(54,  185, 204, 0.12); }
    .bg-light-warning   { background-color: rgba(246, 194, 62,  0.15); }
    .bg-light-danger    { background-color: rgba(231, 74,  59,  0.12); }
    .bg-light-secondary { background-color: rgba(108, 117, 125, 0.12); }
</style>
@endonce
