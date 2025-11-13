@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')

@section('title', 'إدارة برنامج ' . ($program->program_title ?? $program->name ?? ''))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('dashboard.programs.index') }}" class="btn btn-light border shadow-sm">
                <i class="fas fa-arrow-right mr-2"></i>عودة للبرامج
            </a>
        </div>
        <div>
            <h1 class="h4 mb-0 text-gray-800">
                <i class="fas fa-tv text-primary mr-2"></i>إدارة برنامج: {{ $program->program_title ?? $program->name ?? 'برنامج' }}
            </h1>
        </div>
    </div>

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

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>بيانات البرنامج الأساسية
                    </h6>
                </div>
                <div class="card-body">
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
                                <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" data-placement="top" title="يمكنك تنسيق النص باستخدام الألوان والخطوط العريضة والمائلة وغيرها"></i>
                            </label>
                            <textarea name="program_description"
                                      id="program_description"
                                      class="form-control @error('program_description') is-invalid @enderror"
                                      placeholder="اكتب وصفاً للبرنامج...">{{ old('program_description', $program->program_description) }}</textarea>
                            <small class="form-text text-muted">
                                <i class="fas fa-lightbulb text-warning mr-1"></i>
                                يمكنك تنسيق النص: <strong>عريض</strong>، <em>مائل</em>، <span style="color: #10b981;">ألوان</span>، قوائم، روابط، وغيرها
                            </small>
                            @error('program_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name" class="font-weight-bold">
                                اسم مختصر (اختياري)
                                <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" data-placement="top" title="سيظهر هذا الاسم كعنوان قسم الصور في صفحة البرنامج العامة"></i>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $program->name) }}"
                                   maxlength="255"
                                   placeholder="مثال: معرض الصور، ذكريات البرنامج، إلخ...">
                            <small class="form-text text-muted">
                                <i class="fas fa-lightbulb text-warning mr-1"></i>
                                <strong>ملاحظة:</strong> إذا تم إدخال اسم مختصر، سيظهر كعنوان قسم الصور في صفحة البرنامج العامة بدلاً من عنوان البرنامج. إذا لم يتم إدخاله، سيتم استخدام عنوان البرنامج كعنوان للقسم.
                            </small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">الصورة الرئيسية</label>
                            <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" data-placement="top" title="هذه الصورة تظهر في الصفحة الرئيسية في قائمة البرامج"></i>
                            <div class="custom-file">
                                <input type="file"
                                       name="image"
                                       id="image"
                                       class="custom-file-input @error('image') is-invalid @enderror"
                                       accept="image/*">
                                <label class="custom-file-label" for="image">اختر صورة...</label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>الصيغ المدعومة: JPEG, PNG, JPG, GIF, WebP (حد أقصى 5MB)
                            </small>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">صورة الغلاف</label>
                            <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" data-placement="top" title="هذه الصورة تظهر في أعلى صفحة تفاصيل البرنامج (اختياري)"></i>
                            <div class="custom-file">
                                <input type="file"
                                       name="cover_image"
                                       id="cover_image"
                                       class="custom-file-input @error('cover_image') is-invalid @enderror"
                                       accept="image/*">
                                <label class="custom-file-label" for="cover_image">اختر صورة غلاف (اختياري)...</label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>إذا لم يتم اختيار صورة غلاف، سيتم استخدام الصورة الرئيسية في صفحة التفاصيل. الصيغ المدعومة: JPEG, PNG, JPG, GIF, WebP (حد أقصى 5MB)
                            </small>
                            @error('cover_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @if($program->cover_image_path)
                                <div class="mt-2">
                                    <div class="border rounded p-2 bg-light">
                                        <img src="{{ asset('storage/' . $program->cover_image_path) }}" class="img-fluid rounded" style="max-height: 150px;">
                                        <small class="text-muted d-block mt-1">صورة الغلاف الحالية</small>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="fas fa-save mr-1"></i>حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-image mr-2"></i>معاينة الصورة الرئيسية
                    </h6>
                </div>
                <div class="card-body">
                    @if($program->path)
                        <img src="{{ asset('storage/' . $program->path) }}" class="img-fluid rounded shadow-sm mb-3" alt="{{ $program->program_title }}">
                    @else
                        <div class="border rounded p-4 text-center text-muted">
                            <i class="fas fa-image fa-2x mb-2"></i>
                            <p class="mb-0">لا توجد صورة حالياً</p>
                        </div>
                    @endif
                    <p class="text-muted small mb-0">
                        تم إنشاء البرنامج بتاريخ {{ $program->created_at->format('Y-m-d') }}<br>
                        آخر تحديث {{ $program->updated_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-images mr-2"></i>معرض الصور
            </h6>
            <button class="btn btn-sm btn-outline-primary" data-toggle="collapse" data-target="#addGalleryForm" aria-expanded="false">
                <i class="fas fa-plus-circle mr-1"></i>إضافة صورة جديدة
            </button>
        </div>
        <div class="collapse" id="addGalleryForm">
            <div class="card-body">
                <form action="{{ route('dashboard.programs.media.store', $program) }}" method="POST" enctype="multipart/form-data" id="galleryUploadForm">
                    @csrf
                    <input type="hidden" name="media_type" value="image">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label class="font-weight-bold">
                                رفع الصور <span class="text-danger">*</span>
                                <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" data-placement="top" title="يمكنك اختيار عدة صور دفعة واحدة"></i>
                            </label>
                            <div class="custom-file">
                                <input type="file" name="images[]" id="imagesInput" class="custom-file-input" accept="image/*" multiple>
                                <label class="custom-file-label" for="imagesInput">اختر صورة أو عدة صور...</label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>يمكنك اختيار عدة صور دفعة واحدة (اضغط Ctrl أو Cmd أثناء الاختيار). الصيغ المدعومة: JPEG, PNG, JPG, GIF, WebP (حد أقصى 5MB لكل صورة)
                            </small>
                        </div>
                    </div>
                    <div id="imagesPreview" class="row mt-3 mb-3" style="display: none;">
                        <div class="col-12">
                            <h6 class="font-weight-bold mb-2">معاينة الصور المختارة:</h6>
                            <div class="row" id="previewContainer"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">عنوان موحد لجميع الصور (اختياري)</label>
                            <input type="text" name="title" class="form-control" maxlength="255" placeholder="سيتم تطبيقه على جميع الصور">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">وصف موحد لجميع الصور (اختياري)</label>
                            <input type="text" name="description" class="form-control" maxlength="500" placeholder="سيتم تطبيقه على جميع الصور">
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" id="uploadBtn">
                            <i class="fas fa-upload mr-1"></i>رفع الصور (<span id="imagesCount">0</span>)
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($galleryMedia->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="fas fa-images fa-2x mb-2"></i>
                    <p class="mb-0">لا توجد صور للبرنامج حالياً</p>
                </div>
            @else
                <div class="row" id="gallerySortable" data-reorder-url="{{ route('dashboard.programs.media.reorder', $program) }}">
                    @foreach($galleryMedia as $media)
                        <div class="col-md-4 mb-4" data-id="{{ $media->id }}">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="position-relative" style="overflow: hidden;">
                                    <img src="{{ asset('storage/' . $media->path) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $media->name }}">
                                    <div class="position-absolute top-0 right-0 m-2">
                                        <i class="fas fa-grip-vertical text-white bg-dark rounded p-2 shadow" style="cursor: move;" title="اسحب لإعادة الترتيب"></i>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="font-weight-bold mb-1">{{ $media->name ?? 'بدون عنوان' }}</h6>
                                    @if($media->description)
                                        <p class="text-muted small mb-2">{{ Str::limit($media->description, 80) }}</p>
                                    @endif
                                    <form action="{{ route('dashboard.programs.media.destroy', [$program, $media]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash mr-1"></i>حذف
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

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fab fa-youtube mr-2"></i>فيديوهات يوتيوب
            </h6>
            <button class="btn btn-sm btn-outline-primary" data-toggle="collapse" data-target="#addVideoForm" aria-expanded="false">
                <i class="fas fa-plus-circle mr-1"></i>إضافة فيديو جديد
            </button>
        </div>
        <div class="collapse" id="addVideoForm">
            <div class="card-body">
                <form action="{{ route('dashboard.programs.media.store', $program) }}" method="POST">
                    @csrf
                    <input type="hidden" name="media_type" value="youtube">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">عنوان الفيديو</label>
                            <input type="text" name="title" class="form-control" maxlength="255" placeholder="عنوان الفيديو">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">رابط يوتيوب <span class="text-danger">*</span></label>
                            <input type="url" name="youtube_url" class="form-control" required placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">وصف مختصر</label>
                        <textarea name="description" class="form-control" rows="2" maxlength="500" placeholder="وصف للفيديو (اختياري)"></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>إضافة الفيديو
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($videoMedia->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="fab fa-youtube fa-2x mb-2"></i>
                    <p class="mb-0">لا توجد فيديوهات يوتيوب لهذا البرنامج</p>
                </div>
            @else
                <div class="row" id="videosSortable" data-reorder-url="{{ route('dashboard.programs.media.reorder', $program) }}">
                    @foreach($videoMedia as $media)
                        <div class="col-md-6 mb-4" data-id="{{ $media->id }}">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    <div class="embed-responsive embed-responsive-16by9 rounded-top">
                                        <iframe class="embed-responsive-item" src="{{ $media->getYouTubeEmbedUrl() }}" allowfullscreen></iframe>
                                    </div>
                                    <div class="position-absolute top-0 right-0 m-2">
                                        <i class="fas fa-grip-vertical text-white bg-dark rounded p-2 shadow" style="cursor: move;" title="اسحب لإعادة الترتيب"></i>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="font-weight-bold mb-1">{{ $media->name ?? 'فيديو' }}</h6>
                                    @if($media->description)
                                        <p class="text-muted small mb-2">{{ Str::limit($media->description, 120) }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ $media->youtube_url }}" target="_blank" class="text-info small">
                                            <i class="fas fa-external-link-alt mr-1"></i>فتح في يوتيوب
                                        </a>
                                        <form action="{{ route('dashboard.programs.media.destroy', [$program, $media]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الفيديو؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
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

    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-link mr-2"></i>روابط البرنامج
            </h6>
            <button class="btn btn-sm btn-outline-primary" data-toggle="collapse" data-target="#addLinkForm" aria-expanded="false">
                <i class="fas fa-plus-circle mr-1"></i>إضافة رابط جديد
            </button>
        </div>
        <div class="collapse" id="addLinkForm">
            <div class="card-body">
                <form action="{{ route('dashboard.programs.links.store', $program) }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">عنوان الرابط <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required maxlength="255" placeholder="عنوان الرابط">
                        </div>
                        <div class="form-group col-md-5">
                            <label class="font-weight-bold">الرابط <span class="text-danger">*</span></label>
                            <input type="url" name="url" class="form-control" required maxlength="500" placeholder="https://example.com">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="font-weight-bold">وصف مختصر</label>
                            <input type="text" name="description" class="form-control" maxlength="500" placeholder="وصف للرابط">
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>إضافة الرابط
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($programLinks->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="fas fa-link fa-2x mb-2"></i>
                    <p class="mb-0">لا توجد روابط مضافة لهذا البرنامج</p>
                </div>
            @else
                <ul class="list-group list-group-flush" id="linksSortable" data-reorder-url="{{ route('dashboard.programs.links.reorder', $program) }}">
                    @foreach($programLinks as $link)
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $link->id }}">
                            <div>
                                <span class="handle mr-3 text-muted" style="cursor: move;">
                                    <i class="fas fa-grip-lines"></i>
                                </span>
                                <strong>{{ $link->title }}</strong>
                                <div class="text-muted small">{{ $link->description }}</div>
                                <a href="{{ $link->url }}" target="_blank" class="small text-info">
                                    <i class="fas fa-external-link-alt mr-1"></i>{{ $link->url }}
                                </a>
                            </div>
                            <form action="{{ route('dashboard.programs.links.destroy', [$program, $link]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الرابط؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash mr-1"></i>حذف
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        direction: rtl;
    }
    .note-editor.note-frame .note-editing-area {
        direction: rtl;
        text-align: right;
    }
    .note-editor.note-frame .note-toolbar {
        direction: rtl;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    function initSortable(elementId, reorderUrl) {
        const container = document.getElementById(elementId);
        if (!container || !reorderUrl) {
            return;
        }

        new Sortable(container, {
            handle: '.fa-grip-vertical, .handle',
            animation: 200,
            onEnd: function () {
                const ids = Array.from(container.querySelectorAll('[data-id]')).map(item => item.dataset.id);

                fetch(reorderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ orders: ids })
                }).catch(() => {
                    alert('تعذر حفظ الترتيب، يرجى إعادة المحاولة.');
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initSortable('gallerySortable', document.getElementById('gallerySortable')?.dataset.reorderUrl);
        initSortable('videosSortable', document.getElementById('videosSortable')?.dataset.reorderUrl);
        initSortable('linksSortable', document.getElementById('linksSortable')?.dataset.reorderUrl);

        // معالجة رفع عدة صور دفعة واحدة
        const imagesInput = document.getElementById('imagesInput');
        const imagesPreview = document.getElementById('imagesPreview');
        const previewContainer = document.getElementById('previewContainer');
        const imagesCount = document.getElementById('imagesCount');
        const uploadBtn = document.getElementById('uploadBtn');
        const customFileLabel = imagesInput?.nextElementSibling;

        if (imagesInput) {
            imagesInput.addEventListener('change', function () {
                const files = this.files;
                const fileCount = files.length;

                // تحديث عدد الصور في الزر
                if (imagesCount) {
                    imagesCount.textContent = fileCount;
                }

                // تحديث نص label
                if (customFileLabel) {
                    if (fileCount === 0) {
                        customFileLabel.textContent = 'اختر صورة أو عدة صور...';
                    } else if (fileCount === 1) {
                        customFileLabel.textContent = files[0].name;
                    } else {
                        customFileLabel.textContent = `${fileCount} صور مختارة`;
                    }
                }

                // عرض معاينة الصور
                if (previewContainer && imagesPreview) {
                    previewContainer.innerHTML = '';
                    
                    if (fileCount > 0) {
                        imagesPreview.style.display = 'block';
                        
                        Array.from(files).forEach((file, index) => {
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const col = document.createElement('div');
                                    col.className = 'col-md-3 col-sm-4 col-6 mb-3';
                                    
                                    col.innerHTML = `
                                        <div class="card shadow-sm border-0">
                                            <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="معاينة ${index + 1}">
                                            <div class="card-body p-2">
                                                <small class="text-muted d-block text-truncate" title="${file.name}">${file.name}</small>
                                                <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                                            </div>
                                        </div>
                                    `;
                                    
                                    previewContainer.appendChild(col);
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    } else {
                        imagesPreview.style.display = 'none';
                    }
                }
            });
        }

        // معالجة باقي حقول الرفع (للتوافق)
        document.querySelectorAll('.custom-file-input').forEach(function (input) {
            if (input.id !== 'imagesInput') {
                input.addEventListener('change', function () {
                    const label = this.nextElementSibling;
                    label.textContent = this.files && this.files.length > 0 ? this.files[0].name : 'اختر ملف...';
                });
            }
        });

        // تهيئة Bootstrap tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // تهيئة Summernote لمحرر الوصف
        $('#program_description').summernote({
            height: 350,
            direction: 'rtl',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color', 'forecolor', 'backcolor']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            fontNames: ['Tajawal', 'Amiri', 'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana'],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '36', '48'],
            placeholder: 'اكتب وصفاً للبرنامج...',
            dialogsInBody: true,
            disableDragAndDrop: false,
            callbacks: {
                onImageUpload: function(files) {
                    // يمكن إضافة معالجة رفع الصور هنا لاحقاً
                    alert('يرجى استخدام قسم معرض الصور لرفع الصور');
                },
                onInit: function() {
                    // ضبط اتجاه النص عند التهيئة
                    $('.note-editing-area').css('direction', 'rtl');
                    $('.note-editing-area').css('text-align', 'right');
                }
            }
        });

        // التحقق من الصور قبل الإرسال
        const galleryUploadForm = document.getElementById('galleryUploadForm');
        if (galleryUploadForm) {
            galleryUploadForm.addEventListener('submit', function(e) {
                const files = imagesInput?.files;
                if (!files || files.length === 0) {
                    e.preventDefault();
                    alert('يرجى اختيار صورة واحدة على الأقل');
                    return false;
                }
            });
        }
    });
</script>
@endpush

