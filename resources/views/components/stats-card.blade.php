@props([
    'icon' => 'fas fa-chart-bar',
    'title' => 'العنوان',
    'value' => '0',
    'color' => 'primary',
    'subtitle' => null,
    'trend' => null,
    'trendValue' => null
])

@php
    $colorClasses = [
        'primary' => 'bg-primary',
        'success' => 'bg-success',
        'info' => 'bg-info',
        'warning' => 'bg-warning',
        'danger' => 'bg-danger',
        'secondary' => 'bg-secondary',
        'light' => 'bg-light',
        'dark' => 'bg-dark'
    ];

    $iconColors = [
        'primary' => 'text-white',
        'success' => 'text-white',
        'info' => 'text-white',
        'warning' => 'text-white',
        'danger' => 'text-white',
        'secondary' => 'text-white',
        'light' => 'text-gray-800',
        'dark' => 'text-white'
    ];

    $bgColor = $colorClasses[$color] ?? 'bg-primary';
    $iconColor = $iconColors[$color] ?? 'text-white';
@endphp

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-{{ $color }} shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-{{ $color }} text-uppercase mb-1">
                        {{ $title }}
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $value }}
                    </div>
                    @if($subtitle)
                        <div class="text-xs text-muted">
                            {{ $subtitle }}
                        </div>
                    @endif
                    @if($trend && $trendValue)
                        <div class="text-xs mt-1">
                            @if($trend === 'up')
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i> {{ $trendValue }}
                                </span>
                            @elseif($trend === 'down')
                                <span class="text-danger">
                                    <i class="fas fa-arrow-down"></i> {{ $trendValue }}
                                </span>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-minus"></i> {{ $trendValue }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-auto">
                    <i class="{{ $icon }} fa-2x {{ $iconColor }}"></i>
                </div>
            </div>
        </div>
    </div>
</div>
