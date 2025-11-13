@php
    use Illuminate\Support\Str;
@endphp
{{-- Tab 3: الصور العامة --}}
<div class="tab-pane fade" id="images" role="tabpanel">
    <div class="card section-card">
        <div class="section-header">
            <h5><i class="fas fa-photo-video mr-2"></i>الصور العامة</h5>
            <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#addImageForm">
                <i class="fas fa-plus mr-1"></i>إضافة صور
            </button>
        </div>
        <div class="collapse" id="addImageForm">
            <div class="form-section">
                <h6 class="font-weight-bold mb-3">رفع صور جديدة</h6>
                <form action="{{ route('dashboard.programs.media.store', $program) }}" method="POST" enctype="multipart/form-data" id="galleryUploadForm">
                    @csrf
                    <input type="hidden" name="media_type" value="image">
                    <div class="form-group">
                        <label class="font-weight-bold">اختر الصور <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" name="images[]" id="imagesInput" class="custom-file-input" accept="image/*" multiple>
                            <label class="custom-file-label" for="imagesInput">اختر صورة أو عدة صور...</label>
                        </div>
                        <small class="form-text text-muted">يمكنك اختيار عدة صور دفعة واحدة. الصيغ المدعومة: JPEG, PNG, JPG, GIF, WebP (حد أقصى 5MB لكل صورة)</small>
                    </div>
                    <div id="imagesPreview" class="row mt-3 mb-3" style="display: none;">
                        <div class="col-12">
                            <h6 class="font-weight-bold mb-2">معاينة الصور المختارة:</h6>
                            <div class="row" id="previewContainer"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">عنوان موحد (اختياري)</label>
                                <input type="text" name="title" class="form-control" maxlength="255" placeholder="سيتم تطبيقه على جميع الصور">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">وصف موحد (اختياري)</label>
                                <input type="text" name="description" class="form-control" maxlength="500" placeholder="سيتم تطبيقه على جميع الصور">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <i class="fas fa-upload mr-1"></i>رفع الصور (<span id="imagesCount">0</span>)
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($galleryMedia->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-images"></i>
                    <h5 class="mt-3">لا توجد صور</h5>
                    <p class="text-muted">ابدأ برفع صور جديدة للبرنامج</p>
                </div>
            @else
                <div class="media-grid">
                    @foreach($galleryMedia as $media)
                        <div class="media-card" data-id="{{ $media->id }}">
                            <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->name }}">
                            <div class="p-3">
                                <h6 class="font-weight-bold mb-1">{{ $media->name ?? 'بدون عنوان' }}</h6>
                                @if($media->description)
                                    <p class="text-muted small mb-2">{{ Str::limit($media->description, 60) }}</p>
                                @endif
                                <div class="action-buttons">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary flex-fill edit-media-btn" 
                                            data-media-id="{{ $media->id }}"
                                            data-media-name="{{ htmlspecialchars($media->name ?? '', ENT_QUOTES, 'UTF-8') }}"
                                            data-media-description="{{ htmlspecialchars($media->description ?? '', ENT_QUOTES, 'UTF-8') }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('dashboard.programs.media.destroy', [$program, $media]) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')"
                                          class="flex-fill">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

