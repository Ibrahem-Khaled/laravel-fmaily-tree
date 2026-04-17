@props([
    'title',
    'subtitle' => null,
])

@once('dashboard-glass-hero-css')
    <style>
        .listings-admin-hero {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 40%, #0f3460 100%);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        }
        /* Ensure text-white isn't overridden by light theme */
        .listings-admin-hero, 
        .listings-admin-hero h1, 
        .listings-admin-hero p, 
        .listings-admin-hero .h3 {
            color: #ffffff !important;
        }
        .listings-admin-hero-glow {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 20%, rgba(99, 102, 241, 0.4), transparent 45%),
                radial-gradient(circle at 80% 10%, rgba(16, 185, 129, 0.3), transparent 40%);
            pointer-events: none;
        }
        .listings-admin-hero .z-index-1 {
            z-index: 1;
        }
        .listings-admin-hero h1 {
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            letter-spacing: -0.5px;
        }
    </style>
@endonce

<div {{ $attributes->merge(['class' => 'listings-admin-hero mb-4 p-4 text-white position-relative overflow-hidden rounded-lg shadow-lg']) }}>
    <div class="position-relative z-index-1">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="h3 mb-2 font-weight-bold text-white">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="mb-0 opacity-90 small text-white">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    {{ $actions }}
                </div>
            @endisset
        </div>
        @unless ($slot->isEmpty())
            <div class="mt-4">
                {{ $slot }}
            </div>
        @endunless
    </div>
    <div class="listings-admin-hero-glow" aria-hidden="true"></div>
</div>
