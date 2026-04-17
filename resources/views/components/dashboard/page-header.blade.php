@props([
    'title',
    'description' => null,
])

<div class="page-header mb-4 p-4 shadow-sm border-0 d-flex justify-content-between align-items-center flex-wrap gap-3" style="border-radius: 16px;">
    <div>
        <h1 class="h3 mb-1 font-weight-bold" style="letter-spacing: -0.5px;">{{ $title }}</h1>
        @if($description)
            <p class="text-muted mb-0 small">{{ $description }}</p>
        @endif
    </div>

    @if(isset($actions))
        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{ $actions }}
        </div>
    @endif
</div>
