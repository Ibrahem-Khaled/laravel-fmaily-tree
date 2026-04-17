@props([
    'roots',
])

<div class="category-tree-root">
    @forelse($roots as $node)
        <x-dashboard.category-tree-node :node="$node" :depth="0" />
    @empty
        <div class="text-center py-5 px-4 rounded-lg border border-dashed" style="border-radius: 20px; background: rgba(102, 126, 234, 0.04);">
            <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-light" style="width: 72px; height: 72px;">
                <i class="fe fe-folder text-primary" style="font-size: 2rem;"></i>
            </div>
            <h3 class="h5 font-weight-bold mb-2">لا توجد تصنيفات بعد</h3>
            <p class="text-muted mb-4 max-w-420 mx-auto" style="max-width: 28rem;">ابدأ بتصنيف جذر ثم أضف فئات فرعية بلا حدود للعمق — كل شيء منظم في شجرة واحدة.</p>
            <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">
                <i class="fe fe-plus fe-16 ml-1"></i> إنشاء أول تصنيف
            </a>
        </div>
    @endforelse
</div>
