{{-- Tab 4: الفيديوهات --}}
<div class="tab-pane fade" id="videos" role="tabpanel">
    <div class="card section-card">
        <div class="section-header">
            <h5><i class="fab fa-youtube mr-2"></i>فيديوهات يوتيوب</h5>
            <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#addVideoForm">
                <i class="fas fa-plus mr-1"></i>إضافة فيديو
            </button>
        </div>
        <div class="collapse" id="addVideoForm">
            <div class="form-section">
                <h6 class="font-weight-bold mb-3">إضافة فيديو من يوتيوب</h6>
                <form action="{{ route('dashboard.proud-of.media.store', $item) }}" method="POST">
                    @csrf
                    <input type="hidden" name="media_type" value="youtube">
                    <div class="form-group">
                        <label class="font-weight-bold">رابط يوتيوب <span class="text-danger">*</span></label>
                        <input type="url" name="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror" 
                               value="{{ old('youtube_url') }}" required placeholder="https://www.youtube.com/watch?v=...">
                        @error('youtube_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">عنوان الفيديو (اختياري)</label>
                                <input type="text" name="title" class="form-control" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">وصف الفيديو (اختياري)</label>
                                <input type="text" name="description" class="form-control" maxlength="500">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>إضافة الفيديو
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($videoMedia->isEmpty())
                <div class="empty-state">
                    <i class="fab fa-youtube"></i>
                    <h5 class="mt-3">لا توجد فيديوهات</h5>
                    <p class="text-muted">ابدأ بإضافة فيديوهات من يوتيوب</p>
                </div>
            @else
                <div class="row">
                    @foreach($videoMedia as $video)
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="position-relative" style="padding-top: 56.25%;">
                                    <iframe src="{{ $video->getYouTubeEmbedUrl() }}" 
                                            class="position-absolute top-0 left-0 w-100 h-100" 
                                            allowfullscreen></iframe>
                                </div>
                                <div class="card-body">
                                    <h6 class="font-weight-bold mb-1">{{ $video->name ?? 'فيديو' }}</h6>
                                    @if($video->description)
                                        <p class="text-muted small mb-2">{{ $video->description }}</p>
                                    @endif
                                    <div class="d-flex gap-2">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary flex-fill edit-media-btn" 
                                                data-media-id="{{ $video->id }}"
                                                data-media-name="{{ htmlspecialchars($video->name ?? '', ENT_QUOTES, 'UTF-8') }}"
                                                data-media-description="{{ htmlspecialchars($video->description ?? '', ENT_QUOTES, 'UTF-8') }}"
                                                data-media-youtube="{{ htmlspecialchars($video->youtube_url ?? '', ENT_QUOTES, 'UTF-8') }}">
                                            <i class="fas fa-edit mr-1"></i>تعديل
                                        </button>
                                        <form action="{{ route('dashboard.proud-of.media.destroy', [$item, $video]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الفيديو؟')"
                                              class="flex-fill">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                <i class="fas fa-trash mr-1"></i>حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
