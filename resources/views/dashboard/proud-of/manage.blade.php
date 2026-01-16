@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')

@section('title', 'إدارة عنصر ' . ($item->proud_of_title ?? $item->name ?? ''))

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
            <a href="{{ route('dashboard.proud-of.index') }}" class="btn btn-light border shadow-sm">
                <i class="fas fa-arrow-right mr-2"></i>عودة للعناصر
            </a>
        </div>
        <div>
            <h2 class="h4 mb-0 text-gray-800">
                <i class="fas fa-star text-primary mr-2"></i>{{ $item->proud_of_title ?? $item->name ?? 'عنصر' }}
            </h2>
            <small class="text-muted">آخر تحديث: {{ $item->updated_at->diffForHumans() }}</small>
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
    <ul class="nav nav-tabs mb-4" id="itemTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab">
                <i class="fas fa-info-circle mr-2"></i>معلومات العنصر
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
    <div class="tab-content" id="itemTabsContent">
        @include('dashboard.proud-of.partials._info')
        @include('dashboard.proud-of.partials._galleries')
        @include('dashboard.proud-of.partials._images')
        @include('dashboard.proud-of.partials._videos')
        @include('dashboard.proud-of.partials._links')
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
                        <textarea name="description" id="edit_gallery_description" class="form-control" rows="3"></textarea>
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

<!-- Modal تعديل صورة في معرض -->
<div class="modal fade" id="editGalleryMediaModal" tabindex="-1" role="dialog">
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
            <form id="editGalleryMediaForm" method="POST" enctype="multipart/form-data" action="">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">عنوان الصورة</label>
                        <input type="text" name="name" id="edit_gallery_media_name" class="form-control" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">وصف الصورة</label>
                        <textarea name="description" id="edit_gallery_media_description" class="form-control" rows="3" maxlength="500"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">استبدال الصورة (اختياري)</label>
                        <div class="custom-file">
                            <input type="file" name="image" id="edit_gallery_media_image" class="custom-file-input" accept="image/*">
                            <label class="custom-file-label" for="edit_gallery_media_image">اختر صورة جديدة...</label>
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
        $('#proud_of_description').summernote({
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

        // Summernote editor for gallery description (add form)
        $('#gallery_description').summernote({
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

        // Initialize Summernote for edit gallery description modal
        $('#edit_gallery_description').summernote({
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

            const itemId = {{ $item->id }};
            document.getElementById('editMediaForm').action = '{{ url("/dashboard/proud-of") }}/' + itemId + '/media/' + mediaId + '/update';
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
                // Use summernote('code', value) to set the content
                $('#edit_gallery_description').summernote('code', galleryDescription);

                const itemId = {{ $item->id }};
                document.getElementById('editGalleryForm').action = '{{ url("/dashboard/proud-of") }}/' + itemId + '/galleries/' + galleryId + '/update';

                $('#editGalleryModal').modal('show');
            });
        });

        // Extract Summernote content before form submission
        $('#editGalleryForm').on('submit', function() {
            const content = $('#edit_gallery_description').summernote('code');
            $('#edit_gallery_description').val(content);
        });

        // Extract Summernote content from add gallery form before submission
        $(document).on('submit', 'form[action*="galleries.store"]', function() {
            if ($('#gallery_description').length) {
                try {
                    const content = $('#gallery_description').summernote('code');
                    $('#gallery_description').val(content);
                } catch(e) {
                    // If summernote is not initialized, use regular value
                    console.log('Summernote not initialized, using regular value');
                }
            }
        });

        // Edit gallery media
        document.querySelectorAll('.edit-gallery-media-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const mediaId = this.getAttribute('data-media-id');
                const mediaName = this.getAttribute('data-media-name') || '';
                const mediaDescription = this.getAttribute('data-media-description') || '';
                const galleryId = this.getAttribute('data-gallery-id');

                document.getElementById('edit_gallery_media_name').value = mediaName;
                document.getElementById('edit_gallery_media_description').value = mediaDescription;

                const itemId = {{ $item->id }};
                document.getElementById('editGalleryMediaForm').action = '{{ url("/dashboard/proud-of") }}/' + itemId + '/galleries/' + galleryId + '/media/' + mediaId + '/update';

                $('#editGalleryMediaModal').modal('show');
            });
        });
    });
</script>
@endpush
