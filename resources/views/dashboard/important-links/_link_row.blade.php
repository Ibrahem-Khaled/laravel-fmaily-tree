<div class="list-group-item list-group-item-action mb-2 border rounded-xl shadow-sm link-item bg-white {{ !$link->is_active ? 'opacity-60 bg-light' : '' }}"
     data-id="{{ $link->id }}"
     style="cursor: move; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); border-color: #f1f5f9;">
    <div class="d-flex justify-content-between align-items-center flex-wrap flex-md-nowrap gap-3">
        <div class="d-flex align-items-center flex-grow-1">
            <div class="sortable-handle mr-2 ml-3 text-muted" style="cursor: move;">
                <i class="fas fa-grip-vertical fa-lg"></i>
            </div>
            
            <div class="d-flex align-items-center justify-content-center bg-slate-50 text-slate-700 rounded-xl border border-slate-100/50 mr-2 ml-3" style="width: 48px; height: 48px; min-width: 48px;">
                @if($link->icon)
                    <i class="{{ $link->icon }} text-emerald-500" style="font-size: 1.25rem;"></i>
                @else
                    <i class="fas fa-link text-emerald-500" style="font-size: 1.25rem;"></i>
                @endif
            </div>

            <div class="flex-grow-1 text-right pr-2">
                <h6 class="mb-1 font-weight-bold text-slate-800" style="font-size: 1rem; letter-spacing: -0.01em;">
                    {{ $link->title }}
                    
                    <span class="badge badge-pill badge-{{ $link->is_active ? 'success' : 'secondary' }} ml-2 px-2.5 py-1" style="{{ $link->is_active ? 'background-color: #ecfdf5; color: #065f46; border: 1px solid #d1fae5;' : 'background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;' }}">
                        {{ $link->is_active ? 'نشط' : 'معطل' }}
                    </span>
                    <span class="badge badge-pill badge-{{ ($link->type ?? 'website') === 'app' ? 'dark' : 'info' }} ml-1 px-2.5 py-1" style="{{ ($link->type ?? 'website') === 'app' ? 'background-color: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0;' : 'background-color: #f0f9ff; color: #0369a1; border: 1px solid #e0f2fe;' }}">
                        {{ ($link->type ?? 'website') === 'app' ? 'تطبيق' : 'موقع' }}
                    </span>
                    <span class="badge badge-pill badge-light border ml-1 text-muted px-2.5 py-1">ترتيب #{{ $link->order }}</span>
                </h6>
                <p class="mb-1 text-muted small text-right text-truncate" dir="ltr" style="max-width: 500px;">{{ $link->url }}</p>
                @if($link->submitter)
                    <p class="mb-1 text-muted small"><span class="font-weight-bold text-slate-600"><i class="fas fa-user-circle ml-1"></i>المقترِح:</span> {{ $link->submitter->name }}</p>
                @endif
                @if($link->description)
                    <p class="mb-0 text-slate-500 small" style="line-height: 1.5;">{{ $link->description }}</p>
                @endif
            </div>
        </div>
        
        <div class="btn-group btn-group-sm shadow-sm ml-3 border rounded-lg overflow-hidden" style="border-color: #cbd5e1;">
            <a href="{{ route('dashboard.important-links.edit', $link) }}"
               class="btn btn-white text-info border-0 px-3"
               title="تعديل"
               style="background-color: #ffffff; hover: background-color: #f8fafc;">
                <i class="fas fa-edit fa-md"></i>
            </a>
            <form action="{{ route('dashboard.important-links.toggle', $link) }}"
                  method="POST" class="d-inline">
                @csrf
                <button type="submit"
                        class="btn btn-white text-{{ $link->is_active ? 'warning' : 'success' }} border-0 px-3"
                        title="{{ $link->is_active ? 'تعطيل' : 'تفعيل' }}"
                        style="background-color: #ffffff; hover: background-color: #f8fafc; border-right: 1px solid #cbd5e1; border-left: 1px solid #cbd5e1;">
                    <i class="fas fa-{{ $link->is_active ? 'eye-slash' : 'eye' }} fa-md"></i>
                </button>
            </form>
            <form action="{{ route('dashboard.important-links.destroy', $link) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الرابط؟')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="btn btn-white text-danger border-0 px-3"
                        title="حذف"
                        style="background-color: #ffffff; hover: background-color: #f8fafc;">
                    <i class="fas fa-trash fa-md"></i>
                </button>
            </form>
        </div>
    </div>
</div>
