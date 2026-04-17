@props(['role'])

@php
    $labels = [
        'admin' => 'مدير',
        'merchant' => 'تاجر',
        'customer' => 'عميل',
        'driver' => 'مندوب',
    ];
    $label = $labels[$role] ?? $role;
@endphp

<span class="badge badge-pill badge-outline-primary">{{ $label }}</span>
