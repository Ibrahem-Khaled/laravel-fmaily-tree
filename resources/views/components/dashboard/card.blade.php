@props([
    'title' => null,
    'icon' => null,
    'padding' => 'p-4',
    'headerClass' => 'border-bottom-0',
    'footer' => null,
])

<div {{ $attributes->merge(['class' => 'card content-card shadow-sm border-0 mb-4']) }} style="border-radius: 20px;">
    @if($title || $icon)
        <div class="card-header bg-transparent {{ $headerClass }} p-4 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 font-weight-bold d-flex align-items-center gap-2" style="font-size: 1.25rem;">
                @if($icon)
                    <div class="p-2 rounded-lg bg-light d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fe {{ $icon }} text-primary"></i>
                    </div>
                @endif
                {{ $title }}
            </h5>
            @if(isset($headerAction))
                {{ $headerAction }}
            @endif
        </div>
    @endif
    
    <div class="card-body {{ $padding }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="card-footer bg-transparent border-top-0 p-4">
            {{ $footer }}
        </div>
    @endif
</div>

<style>
    .content-card {
        transition: all 0.3s ease;
    }
</style>
