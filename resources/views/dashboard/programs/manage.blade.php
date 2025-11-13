@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')

@section('title', 'إدارة برنامج ' . ($program->program_title ?? $program->name ?? ''))

@section('content')
<style>
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        padding: 12px 24px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link:hover {
        border-bottom-color: #dee2e6;
        color: #495057;
    }
    .nav-tabs .nav-link.active {
        border-bottom-color: #007bff;
        color: #007bff;
        background: transparent;
    }
    .section-card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-radius: 12px;
        margin-bottom: 24px;
    }
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 16px 24px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .section-header h5 {
        margin: 0;
        font-weight: 600;
    }
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px;
    }
    .media-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        background: white;
    }
    .media-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    .media-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .gallery-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        background: #f8f9fa;
        transition: all 0.3s;
    }
    .gallery-card:hover {
        border-color: #007bff;
        box-shadow: 0 4px 12px rgba(0,123,255,0.15);
    }
    .form-section {
        background: #f8f9fa;
        padding: 24px;
        border-radius: 12px;
        margin-bottom: 24px;
    }
    .badge-custom {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 500;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.3;
    }
</style>

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('dashboard.programs.index') }}" class="btn btn-light border shadow-sm">
                <i class="fas fa-arrow-right mr-2"></i>عودة للبرامج
            </a>
        </div>
        <div>
            <h2 class="h4 mb-0 text-gray-800">
                <i class="fas fa-tv text-primary mr-2"></i>{{ $program->program_title ?? $program->name ?? 'برنامج' }}
            </h2>
            <small class="text-muted">آخر تحديث: {{ $program->updated_at->diffForHumans() }}</small>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>حدثت أخطاء، يرجى التحقق من المدخلات.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <ul class="nav nav-tabs mb-4" id="programTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab">
                <i class="fas fa-info-circle mr-2"></i>معلومات البرنامج
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="galleries-tab" data-toggle="tab" href="#galleries" role="tab">
                <i class="fas fa-images mr-2"></i>معارض الصور
                @if($programGalleries->count() > 0)
                    <span class="badge badge-primary ml-2">{{ $programGalleries->count() }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="images-tab" data-toggle="tab" href="#images" role="tab">
                <i class="fas fa-photo-video mr-2"></i>الصور العامة
                @if($galleryMedia->count() > 0)
                    <span class="badge badge-primary ml-2">{{ $galleryMedia->count() }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="videos-tab" data-toggle="tab" href="#videos" role="tab">
                <i class="fab fa-youtube mr-2"></i>الفيديوهات
                @if($videoMedia->count() > 0)
                    <span class="badge badge-primary ml-2">{{ $videoMedia->count() }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="links-tab" data-toggle="tab" href="#links" role="tab">
                <i class="fas fa-link mr-2"></i>الروابط
                @if($programLinks->count() > 0)
                    <span class="badge badge-primary ml-2">{{ $programLinks->count() }}</span>
                @endif
            </a>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content" id="programTabsContent">
        {{-- Tab 1: معلومات البرنامج --}}
        <div class="tab-pane fade show active" id="info" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card section-card">
                        <div class="card-body p-4">
                            <h5 class="mb-4 font-weight-bold">
                                <i class="fas fa-edit text-primary mr-2"></i>تعديل بيانات البرنامج
                            </h5>
                            <form action="{{ route('dashboard.programs.update', $program) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="program_title" class="font-weight-bold">
                                        عنوان البرنامج <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="program_title"
                                           id="program_title"
                                           class="form-control @error('program_title') is-invalid @enderror"
                                           value="{{ old('program_title', $program->program_title) }}"
                                           required
                                           maxlength="255">
                                    @error('program_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="program_description" class="font-weight-bold">
                                        وصف البرنامج
                                    </label>
                                    <textarea name="program_description"
                                              id="program_description"
                                              class="form-control @error('program_description') is-invalid @enderror"
                                              rows="5"
                                              placeholder="اكتب وصفاً للبرنامج...">{{ old('program_description', $program->program_description) }}</textarea>
                                    @error('program_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">
                                        اسم مختصر (اختياري)
                                    </label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $program->name) }}"
                                           maxlength="255"
                                           placeholder="مثال: معرض الصور، ذكريات البرنامج">
                                    <small class="form-text text-muted">
                                        سيظهر كعنوان قسم الصور في صفحة البرنامج العامة
                                    </small>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">الصورة الرئيسية</label>
                                            <div class="custom-file">
                                                <input type="file"
                                                       name="image"
                                                       id="image"
                                                       class="custom-file-input @error('image') is-invalid @enderror"
                                                       accept="image/*">
                                                <label class="custom-file-label" for="image">اختر صورة...</label>
                                            </div>
                                            <small class="form-text text-muted">JPEG, PNG, JPG, GIF, WebP (حد أقصى 5MB)</small>
                                            @error('image')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">صورة الغلاف (اختياري)</label>
                                            <div class="custom-file">
                                                <input type="file"
                                                       name="cover_image"
                                                       id="cover_image"
                                                       class="custom-file-input @error('cover_image') is-invalid @enderror"
                                                       accept="image/*">
                                                <label class="custom-file-label" for="cover_image">اختر صورة غلاف...</label>
                                            </div>
                                            <small class="form-text text-muted">تظهر في أعلى صفحة التفاصيل</small>
                                            @error('cover_image')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if($program->path || $program->cover_image_path)
                                    <div class="row mb-3">
                                        @if($program->path)
                                            <div class="col-md-6">
                                                <label class="font-weight-bold small">الصورة الرئيسية الحالية:</label>
                                                <img src="{{ asset('storage/' . $program->path) }}" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                                            </div>
                                        @endif
                                        @if($program->cover_image_path)
                                            <div class="col-md-6">
                                                <label class="font-weight-bold small">صورة الغلاف الحالية:</label>
                                                <img src="{{ asset('storage/' . $program->cover_image_path) }}" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                        <i class="fas fa-save mr-2"></i>حفظ التغييرات
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card section-card">
                        <div class="card-body text-center">
                            @if($program->path)
                                <img src="{{ asset('storage/' . $program->path) }}" class="img-fluid rounded shadow mb-3" alt="{{ $program->program_title }}">
                            @else
                                <div class="empty-state py-4">
                                    <i class="fas fa-image"></i>
                                    <p class="mb-0">لا توجد صورة</p>
                                </div>
                            @endif
                            <div class="text-muted small">
                                <p class="mb-1"><strong>تاريخ الإنشاء:</strong><br>{{ $program->created_at->format('Y-m-d') }}</p>
                                <p class="mb-0"><strong>آخر تحديث:</strong><br>{{ $program->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" maxlength="1000" placeholder="وصف مختصر للمعرض...">{{ old('description') }}</textarea>
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
                        <form action="{{ route('dashboard.programs.media.store', $program) }}" method="POST">
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
                                                <form action="{{ route('dashboard.programs.media.destroy', [$program, $video]) }}" 
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
                        <form action="{{ route('dashboard.programs.links.store', $program) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold">عنوان الرابط <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title') }}" required maxlength="255" placeholder="مثال: موقع البرنامج الرسمي">
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
                            <p class="text-muted">ابدأ بإضافة روابط مفيدة للبرنامج</p>
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
                                    <form action="{{ route('dashboard.programs.links.destroy', [$program, $link]) }}" 
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
    </div>
</div>

<!-- Modal تعديل معرض -->
<div class="modal fade" id="editGalleryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>تعديل المعرض
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editGalleryForm" method="POST" action="">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">عنوان المعرض <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_gallery_title" class="form-control" maxlength="255" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">وصف المعرض</label>
                        <textarea name="description" id="edit_gallery_description" class="form-control" rows="3" maxlength="1000"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal تعديل صورة -->
<div class="modal fade" id="editMediaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>تعديل الصورة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editMediaForm" method="POST" enctype="multipart/form-data" action="">
                @csrf
                <input type="hidden" id="edit_media_id" name="media_id" value="">
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">عنوان الصورة</label>
                        <input type="text" name="name" id="edit_media_name" class="form-control" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">وصف الصورة</label>
                        <textarea name="description" id="edit_media_description" class="form-control" rows="3" maxlength="500"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">استبدال الصورة (اختياري)</label>
                        <div class="custom-file">
                            <input type="file" name="image" id="edit_media_image" class="custom-file-input" accept="image/*">
                            <label class="custom-file-label" for="edit_media_image">اختر صورة جديدة...</label>
                        </div>
                        <small class="form-text text-muted">اتركه فارغاً للحفاظ على الصورة الحالية</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        // Summernote editor
        $('#program_description').summernote({
            height: 200,
            direction: 'rtl',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Images preview
        $('#imagesInput').on('change', function() {
            const files = this.files;
            const preview = $('#imagesPreview');
            const container = $('#previewContainer');
            const count = $('#imagesCount');
            
            container.empty();
            count.text(files.length);
            
            if (files.length > 0) {
                preview.show();
                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = $('<div class="col-md-3 mb-2"></div>');
                            col.html(`
                                <div class="border rounded p-2">
                                    <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 100px;">
                                    <small class="d-block text-center mt-1">${file.name}</small>
                                </div>
                            `);
                            container.append(col);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                preview.hide();
            }
        });

        // Custom file input labels
        $('.custom-file-input').on('change', function() {
            const fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });

        // Edit media
        function editMedia(mediaId, mediaName, mediaDescription) {
            document.getElementById('edit_media_id').value = mediaId;
            document.getElementById('edit_media_name').value = mediaName || '';
            document.getElementById('edit_media_description').value = mediaDescription || '';
            
            const programId = {{ $program->id }};
            document.getElementById('editMediaForm').action = '{{ url("/dashboard/programs") }}/' + programId + '/media/' + mediaId + '/update';
        }

        document.querySelectorAll('.edit-media-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const mediaId = this.getAttribute('data-media-id');
                const mediaName = this.getAttribute('data-media-name') || '';
                const mediaDescription = this.getAttribute('data-media-description') || '';
                editMedia(mediaId, mediaName, mediaDescription);
                $('#editMediaModal').modal('show');
            });
        });

        // Edit gallery
        document.querySelectorAll('.edit-gallery-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const galleryId = this.getAttribute('data-gallery-id');
                const galleryTitle = this.getAttribute('data-gallery-title') || '';
                const galleryDescription = this.getAttribute('data-gallery-description') || '';
                
                document.getElementById('edit_gallery_title').value = galleryTitle;
                document.getElementById('edit_gallery_description').value = galleryDescription;
                
                const programId = {{ $program->id }};
                document.getElementById('editGalleryForm').action = '{{ url("/dashboard/programs") }}/' + programId + '/galleries/' + galleryId + '/update';
                
                $('#editGalleryModal').modal('show');
            });
        });
    });
</script>
@endpush
