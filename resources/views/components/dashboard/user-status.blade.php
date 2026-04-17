@props(['status'])

@switch($status)
    @case('active')
        <span class="dot dot-lg bg-success mr-2"></span>
        <small class="text-muted">نشط</small>
        @break
    @case('inactive')
        <span class="dot dot-lg bg-warning mr-2"></span>
        <small class="text-muted">غير نشط</small>
        @break
    @case('banned')
        <span class="dot dot-lg bg-danger mr-2"></span>
        <small class="text-muted">محظور</small>
        @break
    @default
        <span class="dot dot-lg bg-secondary mr-2"></span>
        <small class="text-muted">{{ $status }}</small>
@endswitch
