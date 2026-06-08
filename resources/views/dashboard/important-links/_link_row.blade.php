<div class="link-item list-group-item" data-id="{{ $link->id }}">
    <div class="link-item-row {{ !$link->is_active ? 'opacity-60 bg-light' : '' }}">
        <div class="link-item-content">
            <div class="sortable-handle text-muted" style="cursor: move;">
                <i class="fas fa-grip-vertical fa-lg"></i>
            </div>
            
            <div class="d-flex align-items-center justify-content-center bg-slate-50 text-slate-700 rounded-xl border border-slate-100/50" style="width: 48px; height: 48px; min-width: 48px;">
                @if($link->icon)
                    <i class="{{ $link->icon }} text-emerald-500" style="font-size: 1.25rem;"></i>
                @else
                    <i class="fas fa-link text-emerald-500" style="font-size: 1.25rem;"></i>
                @endif
            </div>

            <div class="link-item-details">
                <h6 class="link-item-title">
                    {{ $link->title }}
                    
                    <span class="badge badge-pill badge-{{ $link->is_active ? 'success' : 'secondary' }} px-2.5 py-1 text-nowrap" style="{{ $link->is_active ? 'background-color: #ecfdf5; color: #065f46; border: 1px solid #d1fae5;' : 'background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;' }}">
                        {{ $link->is_active ? 'نشط' : 'معطل' }}
                    </span>
                    <span class="badge badge-pill badge-{{ ($link->type ?? 'website') === 'app' ? 'dark' : 'info' }} px-2.5 py-1 text-nowrap" style="{{ ($link->type ?? 'website') === 'app' ? 'background-color: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0;' : 'background-color: #f0f9ff; color: #0369a1; border: 1px solid #e0f2fe;' }}">
                        {{ ($link->type ?? 'website') === 'app' ? 'تطبيق' : 'موقع' }}
                    </span>
                    <span class="badge badge-pill badge-light border text-muted px-2.5 py-1 text-nowrap">ترتيب #{{ $link->order }}</span>
                </h6>
                <p class="link-item-url">{{ $link->url }}</p>
                @if($link->submitter)
                    <p class="mb-1 text-muted small"><span class="font-weight-bold text-slate-600"><i class="fas fa-user-circle ml-1"></i>المقترِح:</span> {{ $link->submitter->name }}</p>
                @endif
                @if($link->description)
                    <p class="mb-0 text-slate-500 small" style="line-height: 1.5;">{{ $link->description }}</p>
                @endif
            </div>
        </div>
        
        <div class="link-item-actions">
            <div class="btn-group btn-group-sm shadow-sm border rounded-lg overflow-hidden bg-white" style="border-color: #cbd5e1;">
                <a href="{{ route('dashboard.important-links.edit', $link) }}"
                   class="btn btn-white text-info border-0 px-3"
                   title="تعديل"
                   style="hover: background-color: #f8fafc;">
                    <i class="fas fa-edit fa-md"></i>
                </a>
                <form action="{{ route('dashboard.important-links.toggle', $link) }}"
                      method="POST" class="d-inline">
                    @csrf
                    <button type="submit"
                            class="btn btn-white text-{{ $link->is_active ? 'warning' : 'success' }} border-0 px-3"
                            title="{{ $link->is_active ? 'تعطيل' : 'تفعيل' }}"
                            style="hover: background-color: #f8fafc; border-right: 1px solid #cbd5e1; border-left: 1px solid #cbd5e1;">
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
                            style="hover: background-color: #f8fafc;">
                        <i class="fas fa-trash fa-md"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
