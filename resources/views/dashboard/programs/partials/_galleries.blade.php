@php
    use Illuminate\Support\Str;
@endphp
{{-- Tab 2: معارض الصور --}}
<div class="tab-pane fade" id="galleries" role="tabpanel">
    <div class="card section-card">
        <div class="section-header">
            <h5><i class="fas fa-images mr-2"></i>معارض الصور</h5>
            <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#addGalleryForm">
                <i class="fas fa-plus mr-1"></i>إضافة معرض جديد
            </button>
        </div>
        <div class="collapse" id="addGalleryForm">
            <div class="form-section">
                <h6 class="font-weight-bold mb-3">إنشاء معرض جديد</h6>
                <form action="{{ route('dashboard.programs.galleries.store', $program) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold">عنوان المعرض <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" required maxlength="255" placeholder="مثال: معرض فعاليات اليوم الأول">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">وصف المعرض (اختياري)</label>
                        <textarea name="description" id="gallery_description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="وصف مختصر للمعرض...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>إنشاء المعرض
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($programGalleries->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-images"></i>
                    <h5 class="mt-3">لا توجد معارض</h5>
                    <p class="text-muted">ابدأ بإنشاء معرض جديد لإضافة صور منظمة</p>
                </div>
            @else
                <div class="row">
                    @foreach($programGalleries as $gallery)
                        <div class="col-md-6 mb-4">
                            <div class="gallery-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="flex-grow-1">
                                        <h6 class="font-weight-bold mb-1">
                                            <i class="fas fa-images text-primary mr-2"></i>{{ $gallery->title }}
                                        </h6>
                                        @if($gallery->description)
                                            <p class="text-muted small mb-0">{{ $gallery->description }}</p>
                                        @endif
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary edit-gallery-btn" 
                                                data-gallery-id="{{ $gallery->id }}"
                                                data-gallery-title="{{ htmlspecialchars($gallery->title, ENT_QUOTES, 'UTF-8') }}"
                                                data-gallery-description="{{ htmlspecialchars($gallery->description ?? '', ENT_QUOTES, 'UTF-8') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('dashboard.programs.galleries.destroy', [$program, $gallery]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المعرض؟ سيتم حذف جميع الصور الموجودة فيه أيضاً.')"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="badge badge-custom badge-info">
                                        <i class="fas fa-images mr-1"></i>{{ $gallery->images->count() }} صورة
                                    </span>
                                </div>
                                
                                @if($gallery->images->isNotEmpty())
                                    <div class="row mb-3">
                                        @foreach($gallery->images->take(4) as $image)
                                            <div class="col-3">
                                                <img src="{{ asset('storage/' . $image->path) }}" 
                                                     class="img-fluid rounded" 
                                                     style="height: 60px; width: 100%; object-fit: cover;"
                                                     alt="{{ $image->name }}">
                                            </div>
                                        @endforeach
                                        @if($gallery->images->count() > 4)
                                            <div class="col-3 d-flex align-items-center justify-content-center">
                                                <span class="badge badge-secondary">+{{ $gallery->images->count() - 4 }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                <button class="btn btn-sm btn-success w-100 mb-2" 
                                        type="button" 
                                        data-toggle="collapse" 
                                        data-target="#addGalleryMediaForm{{ $gallery->id }}">
                                    <i class="fas fa-plus-circle mr-1"></i>إضافة صور
                                </button>
                                
                                <div class="collapse mt-2" id="addGalleryMediaForm{{ $gallery->id }}">
                                    <form action="{{ route('dashboard.programs.galleries.media.store', [$program, $gallery]) }}" 
                                          method="POST" 
                                          enctype="multipart/form-data"
                                          class="bg-white p-3 rounded border">
                                        @csrf
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">اختر الصور</label>
                                            <div class="custom-file">
                                                <input type="file" name="images[]" class="custom-file-input" accept="image/*" multiple required>
                                                <label class="custom-file-label">اختر صورة أو عدة صور...</label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">عنوان موحد (اختياري)</label>
                                            <input type="text" name="title" class="form-control form-control-sm" maxlength="255">
                                        </div>
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">وصف موحد (اختياري)</label>
                                            <input type="text" name="description" class="form-control form-control-sm" maxlength="500">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary w-100">
                                            <i class="fas fa-upload mr-1"></i>رفع الصور
                                        </button>
                                    </form>
                                </div>
                                
                                @if($gallery->images->isNotEmpty())
                                    <div class="mt-3 border-top pt-3">
                                        <h6 class="small font-weight-bold mb-2">صور المعرض:</h6>
                                        <div class="list-group list-group-flush">
                                            @foreach($gallery->images as $image)
                                                <div class="list-group-item d-flex justify-content-between align-items-center p-2 border-0">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('storage/' . $image->path) }}" 
                                                             class="rounded mr-2" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                        <div>
                                                            <small class="font-weight-bold d-block">{{ $image->name ?? 'بدون عنوان' }}</small>
                                                            @if($image->description)
                                                                <small class="text-muted">{{ Str::limit($image->description, 30) }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="btn-group">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-primary edit-gallery-media-btn" 
                                                                data-media-id="{{ $image->id }}"
                                                                data-media-name="{{ htmlspecialchars($image->name ?? '', ENT_QUOTES, 'UTF-8') }}"
                                                                data-media-description="{{ htmlspecialchars($image->description ?? '', ENT_QUOTES, 'UTF-8') }}"
                                                                data-gallery-id="{{ $gallery->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('dashboard.programs.galleries.media.destroy', [$program, $gallery, $image]) }}" 
                                                              method="POST" 
                                                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')"
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

