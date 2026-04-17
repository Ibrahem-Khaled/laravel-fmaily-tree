@props([
    'stats' => [],
])

@php
    $total = $stats['total'] ?? 0;
    $roots = $stats['roots'] ?? 0;
    $active = $stats['active'] ?? 0;
    $maxDepth = $stats['max_depth'] ?? 0;
@endphp

<div class="row">
    <x-dashboard.stat-card
        title="إجمالي التصنيفات"
        :value="number_format($total)"
        icon="fe-layers"
        gradient="primary"
        description="كل المستويات في الشجرة"
    />
    <x-dashboard.stat-card
        title="تصنيفات جذرية"
        :value="number_format($roots)"
        icon="fe-git-branch"
        gradient="info"
        description="بدون فئة أب"
    />
    <x-dashboard.stat-card
        title="نشطة"
        :value="number_format($active)"
        icon="fe-check-circle"
        gradient="success"
        description="ظاهرة للاستخدام"
    />
    <x-dashboard.stat-card
        title="أقصى عمق"
        :value="(string) $maxDepth"
        icon="fe-trending-down"
        gradient="warning"
        description="مستويات من الجذر للورقة"
    />
</div>
