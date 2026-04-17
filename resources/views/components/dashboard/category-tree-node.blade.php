@props([
    'node',
    'depth' => 0,
])

@php
    /** @var \App\Models\Category $node */
    $children = $node->getRelation('tree_children') ?? collect();
    $hasChildren = $children->isNotEmpty();
    $panelId = 'cat-branch-' . $node->id;
@endphp

<div class="category-tree-node mb-2" data-depth="{{ $depth }}">
    <div
        class="category-node-card d-flex flex-wrap align-items-center justify-content-between gap-2 p-3 border-0 shadow-sm"
        style="
            border-radius: 14px;
            margin-inline-start: {{ $depth * 1.15 }}rem;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.06) 0%, rgba(118, 75, 162, 0.04) 100%);
            border-inline-start: 3px solid rgba(79, 172, 254, {{ 0.35 + min($depth, 4) * 0.12 }}) !important;
        "
    >
        <div class="d-flex align-items-center gap-3 flex-grow-1 min-w-0">
            @if($hasChildren)
                <button
                    type="button"
                    class="btn btn-sm btn-light rounded-pill p-0 d-flex align-items-center justify-content-center category-tree-toggle"
                    style="width: 32px; height: 32px;"
                    data-toggle="collapse"
                    data-target="#{{ $panelId }}"
                    aria-expanded="true"
                    aria-controls="{{ $panelId }}"
                >
                    <i class="fe fe-chevron-down small text-primary"></i>
                </button>
            @else
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light" style="width: 32px; height: 32px;">
                    <i class="fe fe-circle text-muted" style="font-size: 6px;"></i>
                </span>
            @endif

            <div class="min-w-0">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <span class="font-weight-bold text-truncate">{{ $node->name }}</span>
                    @if($depth === 0)
                        <span class="badge badge-pill px-2 py-1" style="background: rgba(79, 172, 254, 0.2); color: #4facfe;">جذر</span>
                    @else
                        <span class="badge badge-pill px-2 py-1 bg-light text-muted">مستوى {{ $depth + 1 }}</span>
                    @endif
                    @if($node->is_active)
                        <span class="badge badge-pill badge-success">نشط</span>
                    @else
                        <span class="badge badge-pill badge-secondary">متوقف</span>
                    @endif
                </div>
                @if($node->description)
                    <p class="text-muted small mb-0 mt-1 text-truncate" style="max-width: 42rem;">{{ $node->description }}</p>
                @endif
                <div class="small text-muted mt-1">
                    <span class="ml-2"><i class="fe fe-hash fe-12"></i> {{ $node->slug }}</span>
                    <span class="ml-2"><i class="fe fe-bar-chart-2 fe-12"></i> ترتيب {{ $node->sort_order }}</span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center flex-wrap gap-1">
            <a href="{{ route('dashboard.categories.create', ['parent_id' => $node->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                <i class="fe fe-plus fe-12"></i> فرع
            </a>
            <a href="{{ route('dashboard.categories.edit', $node) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="fe fe-edit-2 fe-12"></i> تعديل
            </a>
            @if(! $hasChildren)
                <form action="{{ route('dashboard.categories.destroy', $node) }}" method="post" class="d-inline" onsubmit="return confirm('حذف هذا التصنيف؟ لا يمكن التراجع.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                        <i class="fe fe-trash-2 fe-12"></i> حذف
                    </button>
                </form>
            @else
                <span class="btn btn-sm btn-light rounded-pill text-muted" title="احذف الفروع أولاً">
                    <i class="fe fe-lock fe-12"></i>
                </span>
            @endif
        </div>
    </div>

    @if($hasChildren)
        <div id="{{ $panelId }}" class="collapse show mt-2">
            @foreach($children as $child)
                <x-dashboard.category-tree-node :node="$child" :depth="$depth + 1" />
            @endforeach
        </div>
    @endif
</div>

@once
    @push('styles')
        <style>
            .category-tree-node .category-tree-toggle.collapsed .fe-chevron-down {
                transform: rotate(-90deg);
            }
            .category-tree-node .category-tree-toggle .fe-chevron-down {
                transition: transform 0.2s ease;
            }
        </style>
    @endpush
@endonce
