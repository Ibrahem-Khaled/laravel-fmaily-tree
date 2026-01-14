{{-- Tab 5: الروابط --}}
<div class="tab-pane fade" id="links" role="tabpanel">
    <div class="card section-card">
        <div class="section-header">
            <h5><i class="fas fa-link mr-2"></i>الروابط المفيدة</h5>
            <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#addLinkForm">
                <i class="fas fa-plus mr-1"></i>إضافة رابط
            </button>
        </div>
        <div class="collapse" id="addLinkForm">
            <div class="form-section">
                <h6 class="font-weight-bold mb-3">إضافة رابط جديد</h6>
                <form action="{{ route('dashboard.proud-of.links.store', $item) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold">عنوان الرابط <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" required maxlength="255" placeholder="مثال: موقع العنصر الرسمي">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">الرابط <span class="text-danger">*</span></label>
                        <input type="url" name="url" class="form-control @error('url') is-invalid @enderror" 
                               value="{{ old('url') }}" required placeholder="https://example.com">
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">وصف الرابط (اختياري)</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="2" maxlength="500" placeholder="وصف مختصر للرابط...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>إضافة الرابط
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($programLinks->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-link"></i>
                    <h5 class="mt-3">لا توجد روابط</h5>
                    <p class="text-muted">ابدأ بإضافة روابط مفيدة للعنصر</p>
                </div>
            @else
                <div class="list-group">
                    @foreach($programLinks as $link)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="font-weight-bold mb-1">
                                    <i class="fas fa-link text-primary mr-2"></i>{{ $link->title }}
                                </h6>
                                @if($link->description)
                                    <p class="text-muted small mb-1">{{ $link->description }}</p>
                                @endif
                                <a href="{{ $link->url }}" target="_blank" class="small text-info">
                                    <i class="fas fa-external-link-alt mr-1"></i>{{ $link->url }}
                                </a>
                            </div>
                            <form action="{{ route('dashboard.proud-of.links.destroy', [$item, $link]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الرابط؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash mr-1"></i>حذف
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
