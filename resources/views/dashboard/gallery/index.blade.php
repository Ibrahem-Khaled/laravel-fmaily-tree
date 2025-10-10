@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- العنوان والمسار --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">معرض الصور (فئات فقط)</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active" aria-current="page">معرض الصور</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات --}}
        <div class="row mb-4">

                <x-stats-card icon="fas fa-images" title="إجمالي صور المعرض" :value="$imagesCount" color="primary" />

                <x-stats-card icon="fas fa-folder-tree" title="فئات لديها صور" :value="$categoriesWithImages" color="warning" />

                <x-stats-card icon="fas fa-upload" title="جاهز للرفع" :value="__('متعدد الصور')" color="info" />
        </div>

        {{-- بطاقة المعرض --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">الصور (مرتبطة بالفئات فقط)</h6>
                <div>
                    <button class="btn btn-outline-danger mr-2" id="bulkDeleteBtn">
                        <i class="fas fa-trash"></i> حذف المحدد
                    </button>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#uploadImagesModal">
                        <i class="fas fa-upload"></i> رفع صور
                    </button>
                </div>
            </div>

            <div class="card-body">
                {{-- فلاتر + بحث --}}
                <form action="{{ route('dashboard.images.index') }}" method="GET" class="mb-4">
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-4">
                            <label>بحث بالاسم</label>
                            <input type="text" name="search" value="{{ $search }}" class="form-control"
                                placeholder="ابحث باسم الصورة...">
                        </div>

                        <div class="form-group col-md-4">
                            <label>بحث عن شخص مذكور</label>
                            <select name="person_search" id="personSearchSelect" class="form-control">
                                <option value="">— اختر شخص —</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>الفئة (فقط الفئات التي لديها صور)</label>
                            <select name="category_id" class="form-control">
                                <option value="">— الكل —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ (int) $categoryId === $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }} ({{ $cat->images_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> تطبيق الفلاتر</button>
                </form>

                {{-- جدول الصور --}}
                <div class="table-responsive">
                    <form action="{{ route('images.bulk-destroy') }}" method="POST" id="bulkDeleteForm">
                        @csrf @method('DELETE')
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:40px;"><input type="checkbox" id="checkAll"></th>
                                    <th>المعاينة</th>
                                    <th>الاسم</th>
                                    <th>الفئة</th>
                                    <th>الأشخاص المذكورين</th>
                                    <th style="width:160px;">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($images as $img)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $img->id }}"></td>
                                        <td>
                                            @if($img->media_type === 'youtube' && $img->youtube_url)
                                                <div style="width:120px;height:90px;position:relative;background:#000;border-radius:4px;overflow:hidden;">
                                                    <img src="{{ $img->getYouTubeThumbnail() }}"
                                                         style="width:100%;height:100%;object-fit:cover;"
                                                         class="img-thumbnail">
                                                    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:#fff;font-size:24px;">
                                                        <i class="fab fa-youtube"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <img src="{{ $img->path ? asset('storage/' . $img->path) : asset('img/no-image.png') }}"
                                                    style="width:120px;height:90px;object-fit:cover;" class="img-thumbnail">
                                            @endif
                                        </td>
                                        <td>{{ $img->name ?? '-' }}</td>
                                        <td>{{ optional($img->category)->name ?? '-' }}</td>
                                        <td>
                                            @if($img->mentionedPersons->count() > 0)
                                                <div class="mentioned-persons-container" data-image-id="{{ $img->id }}">
                                                    <div class="sortable-list" id="sortable-{{ $img->id }}">
                                                        @foreach($img->mentionedPersons as $person)
                                                            <div class="badge badge-info mentioned-person-item"
                                                                 data-person-id="{{ $person->id }}">
                                                                <i class="fas fa-grip-vertical text-muted mr-1"></i>
                                                                {{ $person->full_name }}
                                                                <button type="button" class="btn btn-sm btn-outline-danger ml-1"
                                                                        onclick="removePersonFromImage({{ $img->id }}, {{ $person->id }})"
                                                                        style="padding: 2px 6px; font-size: 10px; border-radius: 50%;">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if($img->mentionedPersons->count() > 1)
                                                        <button type="button" class="btn btn-sm btn-outline-primary mt-1"
                                                                onclick="toggleReorderMode({{ $img->id }})">
                                                            <i class="fas fa-sort"></i> إعادة ترتيب
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">لا يوجد</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($img->media_type === 'youtube' && $img->youtube_url)
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="viewYouTube({{ $img->id }}, '{{ $img->youtube_url }}', '{{ $img->name ?? 'فيديو يوتيوب' }}')">
                                                        <i class="fab fa-youtube"></i> مشاهدة
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-info" onclick="viewImage({{ $img->id }}, '{{ $img->path ? asset('storage/' . $img->path) : asset('img/no-image.png') }}', '{{ $img->name ?? 'صورة' }}')">
                                                        <i class="fas fa-search-plus"></i> تكبير
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-success" onclick="downloadImage({{ $img->id }}, '{{ $img->name ?? 'صورة' }}')">
                                                    <i class="fas fa-download"></i> تحميل
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary" onclick="editImage({{ $img->id }})">
                                                    <i class="fas fa-edit"></i> تعديل
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">لا توجد صور</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>

                {{-- ترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $images->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال رفع الصور (فئات فقط) --}}
    @include('dashboard.gallery.modals.upload')

    {{-- مودال تعديل الصورة --}}
    @include('dashboard.gallery.modals.edit')

    {{-- مودال إنشاء فئة سريع --}}
    @include('dashboard.gallery.modals.quick-category')

    {{-- مودال عرض الصورة مع التكبير --}}
    <div class="modal fade" id="imageViewerModal" tabindex="-1" role="dialog" aria-labelledby="imageViewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageViewerModalLabel">عرض الصورة</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center">
                    <div class="image-container" style="position: relative; overflow: hidden;">
                        <img id="viewerImage" src="" alt="" style="max-width: 100%; max-height: 70vh; transition: transform 0.3s ease;">
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-secondary" onclick="zoomOut()">
                            <i class="fas fa-search-minus"></i> تصغير
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="resetZoom()">
                            <i class="fas fa-expand-arrows-alt"></i> حجم طبيعي
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="zoomIn()">
                            <i class="fas fa-search-plus"></i> تكبير
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="downloadCurrentImage()">
                            <i class="fas fa-download"></i> تحميل
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- مودال عرض فيديو يوتيوب --}}
    <div class="modal fade" id="youtubeViewerModal" tabindex="-1" role="dialog" aria-labelledby="youtubeViewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="youtubeViewerModalLabel">مشاهدة فيديو يوتيوب</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center">
                    <div class="video-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                        <iframe id="youtubeViewerIframe" src="" frameborder="0" allowfullscreen
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
                    </div>
                    <div class="mt-3">
                        <a id="youtubeViewerLink" href="" target="_blank" class="btn btn-outline-danger">
                            <i class="fab fa-youtube"></i> مشاهدة على يوتيوب
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    select[multiple] {
        min-height: 100px;
    }

    .badge {
        font-size: 0.75em;
        margin: 2px;
    }

    .badge .btn-outline-danger {
        margin-left: 5px;
        padding: 2px 6px;
        font-size: 10px;
        border-radius: 50%;
        line-height: 1;
    }

    .badge .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    .select2-container {
        width: 100% !important;
    }

    /* مودال عرض الصورة */
    .image-container {
        cursor: grab;
    }

    .image-container:active {
        cursor: grabbing;
    }

    #viewerImage {
        cursor: zoom-in;
    }

    .btn-group .btn {
        margin: 0 2px;
    }

    /* إعادة ترتيب الأشخاص */
    .mentioned-persons-container {
        min-height: 40px;
    }

    .mentioned-person-item {
        transition: all 0.3s ease;
        user-select: none;
        margin: 2px;
        display: inline-block;
    }

    .mentioned-person-item:hover {
        transform: scale(1.05);
    }

    .sortable-ghost {
        opacity: 0.4;
        background: #c8ebfb;
    }

    .sortable-chosen {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .sortable-drag {
        transform: rotate(5deg);
    }

    .reorder-mode .mentioned-person-item {
        cursor: move;
    }

    .reorder-mode .mentioned-person-item .btn-outline-danger {
        display: none;
    }

    .reorder-controls {
        margin-top: 5px;
    }

    .sortable-list {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
        min-height: 30px;
        padding: 5px;
        border: 1px dashed transparent;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .reorder-mode .sortable-list {
        border-color: #007bff;
        background-color: rgba(0, 123, 255, 0.05);
    }

    .mentioned-person-item {
        cursor: default;
        position: relative;
    }

    .reorder-mode .mentioned-person-item {
        cursor: move;
    }

    .mentioned-person-item .fa-grip-vertical {
        opacity: 0.3;
        transition: opacity 0.3s ease;
    }

    .reorder-mode .mentioned-person-item .fa-grip-vertical {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
        // تهيئة Select2 للبحث عن الأشخاص
        $(document).ready(function() {
            $('#personSearchSelect').select2({
                placeholder: 'ابحث عن شخص...',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                },
                ajax: {
                    url: "{{ route('people.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
        });

        // اختيار الكل
        document.getElementById('checkAll').addEventListener('change', function() {
            document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
        });

        // حذف جماعي
        document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
            if (confirm('حذف كل الصور المحددة؟')) {
                document.getElementById('bulkDeleteForm').submit();
            }
        });

        // تعديل صورة
        function editImage(imageId) {
            // جلب بيانات الصورة عبر AJAX
            $.ajax({
                url: `/dashboard/images/${imageId}/edit`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        const image = response.image;

                        // ملء البيانات في المودال
                        $('#editImageName').val(image.name || '');
                        $('#editImageCategory').val(image.category_id || '');
                        $('#editImageDescription').val(image.description || '');

                        // تحديث الأشخاص المذكورين
                        $('#editMentionedPersons').val(image.mentioned_persons || []);

                        // عرض المحتوى الحالي
                        if (image.media_type === 'youtube' && image.youtube_url) {
                            // عرض فيديو يوتيوب
                            $('#currentImage').hide();
                            $('#currentYoutubePreview').show();

                            // استخراج معرف الفيديو
                            let videoId = '';
                            const patterns = [
                                /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
                                /youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/
                            ];

                            for (let pattern of patterns) {
                                const match = image.youtube_url.match(pattern);
                                if (match) {
                                    videoId = match[1];
                                    break;
                                }
                            }

                            if (videoId) {
                                const embedUrl = `https://www.youtube.com/embed/${videoId}`;
                                $('#currentYoutubeIframe').attr('src', embedUrl);
                                $('#currentYoutubeLink').attr('href', image.youtube_url);
                            }

                            // تحديد نوع المحتوى
                            $('#edit_media_type_youtube').prop('checked', true);
                            $('#edit-image-section').hide();
                            $('#edit-youtube-section').show();
                            $('#editYoutubeUrl').val(image.youtube_url);
                        } else {
                            // عرض صورة
                            $('#currentYoutubePreview').hide();
                            $('#currentImage').show();

                            const imageUrl = image.path ? `{{ asset('storage/') }}/${image.path}` : '{{ asset('img/no-image.png') }}';
                            $('#currentImage').attr('src', imageUrl);

                            // تحديد نوع المحتوى
                            $('#edit_media_type_image').prop('checked', true);
                            $('#edit-youtube-section').hide();
                            $('#edit-image-section').show();
                        }

                        // تحديث رابط النموذج
                        $('#editImageForm').attr('action', `/dashboard/images/${imageId}`);

                        // فتح المودال
                        $('#editImageModal').modal('show');
                    } else {
                        alert('تعذر جلب بيانات الصورة');
                    }
                },
                error: function() {
                    alert('خطأ في الاتصال بالخادم');
                }
            });
        }

        // إظهار أسماء الملفات
        $(document).on('change', '.custom-file-input', function() {
            const names = Array.from(this.files || []).map(f => f.name).join(', ');
            $(this).next('.custom-file-label').html(names || 'اختر ملفات...');
        });

        // معالج نموذج تعديل الصورة
        $('#editImageForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            const submitBtn = $(form).find('button[type="submit"]');

            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جارٍ الحفظ...');

            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#editImageModal').modal('hide');
                        location.reload(); // إعادة تحميل الصفحة لعرض التحديثات
                    } else {
                        alert(response.message || 'تعذر حفظ التعديلات');
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessage = 'يرجى تصحيح الأخطاء التالية:\n';
                        Object.values(errors).forEach(error => {
                            errorMessage += '- ' + error[0] + '\n';
                        });
                        alert(errorMessage);
                    } else {
                        alert('خطأ في الاتصال بالخادم');
                    }
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> حفظ التعديلات');
                }
            });
        });

        // حذف شخص من صورة
        function removePersonFromImage(imageId, personId) {
            if (confirm('هل تريد حذف هذا الشخص من الصورة؟\nسيتم إزالة العلاقة بين الشخص والصورة فقط.')) {
                $.ajax({
                    url: `/dashboard/images/${imageId}/remove-person/${personId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // إعادة تحميل الصفحة لعرض التحديثات
                            location.reload();
                        } else {
                            alert(response.message || 'تعذر حذف الشخص من الصورة');
                        }
                    },
                    error: function() {
                        alert('خطأ في الاتصال بالخادم');
                    }
                });
            }
        }

        // إنشاء فئة سريع (AJAX)
        $('#quickCategoryForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const fd = new FormData(form);
            const btn = $('#quickCategorySubmit');
            btn.prop('disabled', true).text('جارٍ الحفظ...');
            $.ajax({
                url: "{{ route('categories.quick-store') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(resp) {
                    if (resp.ok && resp.category) {
                        // أضف الفئة الجديدة لقوائم الاختيار داخل المودال والفلاتر
                        const selectors = [
                            'select[name="category_id"]',
                            '#uploadCategorySelect'
                        ];
                        selectors.forEach(sel => {
                            $(sel).append(new Option(resp.category.name, resp.category.id, true,
                                true));
                        });
                        $('#quickCategoryModal').modal('hide');
                        form.reset();
                    }
                },
                error: function() {
                    alert('تعذر إنشاء الفئة.');
                },
                complete: function() {
                    btn.prop('disabled', false).text('حفظ الفئة');
                }
            });
        });

        // متغيرات التكبير
        let currentZoom = 1;
        let currentImageId = null;
        let currentImageName = '';

        // عرض الصورة في المودال
        function viewImage(imageId, imageSrc, imageName) {
            currentImageId = imageId;
            currentImageName = imageName;
            currentZoom = 1;

            $('#viewerImage').attr('src', imageSrc).attr('alt', imageName);
            $('#imageViewerModalLabel').text('عرض الصورة: ' + imageName);
            $('#viewerImage').css('transform', 'scale(1)');
            $('#imageViewerModal').modal('show');
        }

        // عرض فيديو يوتيوب في المودال
        function viewYouTube(imageId, youtubeUrl, videoName) {
            // استخراج معرف الفيديو من الرابط
            let videoId = '';
            const patterns = [
                /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/
            ];

            for (let pattern of patterns) {
                const match = youtubeUrl.match(pattern);
                if (match) {
                    videoId = match[1];
                    break;
                }
            }

            if (videoId) {
                const embedUrl = `https://www.youtube.com/embed/${videoId}`;
                $('#youtubeViewerIframe').attr('src', embedUrl);
                $('#youtubeViewerLink').attr('href', youtubeUrl);
                $('#youtubeViewerModalLabel').text('مشاهدة: ' + videoName);
                $('#youtubeViewerModal').modal('show');
            } else {
                alert('رابط يوتيوب غير صحيح');
            }
        }

        // تكبير الصورة
        function zoomIn() {
            currentZoom += 0.25;
            $('#viewerImage').css('transform', `scale(${currentZoom}) translate(${currentX}px, ${currentY}px)`);
        }

        // تصغير الصورة
        function zoomOut() {
            if (currentZoom > 0.25) {
                currentZoom -= 0.25;
                $('#viewerImage').css('transform', `scale(${currentZoom}) translate(${currentX}px, ${currentY}px)`);
            }
        }

        // إعادة تعيين التكبير
        function resetZoom() {
            currentZoom = 1;
            currentX = 0;
            currentY = 0;
            $('#viewerImage').css('transform', 'scale(1)');
        }

        // تحميل الصورة الحالية
        function downloadCurrentImage() {
            if (currentImageId) {
                downloadImage(currentImageId, currentImageName);
            }
        }

        // تحميل الصورة
        function downloadImage(imageId, imageName) {
            const link = document.createElement('a');
            link.href = `/dashboard/images/${imageId}/download`;
            link.download = imageName || 'صورة';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // تكبير بالماوس
        $('#viewerImage').on('click', function() {
            zoomIn();
        });

        // سحب الصورة
        let isDragging = false;
        let startX = 0;
        let startY = 0;
        let currentX = 0;
        let currentY = 0;

        $('#viewerImage').on('mousedown', function(e) {
            if (currentZoom > 1) {
                isDragging = true;
                startX = e.clientX - currentX;
                startY = e.clientY - currentY;
                $(this).css('cursor', 'grabbing');
            }
        });

        $(document).on('mousemove', function(e) {
            if (isDragging && currentZoom > 1) {
                currentX = e.clientX - startX;
                currentY = e.clientY - startY;
                $('#viewerImage').css('transform', `scale(${currentZoom}) translate(${currentX}px, ${currentY}px)`);
            }
        });

        $(document).on('mouseup', function() {
            isDragging = false;
            $('#viewerImage').css('cursor', 'zoom-in');
        });

        // تكبير بعجلة الماوس
        $('#viewerImage').on('wheel', function(e) {
            e.preventDefault();
            if (e.originalEvent.deltaY < 0) {
                zoomIn();
            } else {
                zoomOut();
            }
        });

        // تكبير بالكيبورد
        $(document).on('keydown', function(e) {
            if ($('#imageViewerModal').hasClass('show')) {
                if (e.key === '+' || e.key === '=') {
                    e.preventDefault();
                    zoomIn();
                } else if (e.key === '-') {
                    e.preventDefault();
                    zoomOut();
                } else if (e.key === '0') {
                    e.preventDefault();
                    resetZoom();
                }
            }
        });

        // إعادة ترتيب الأشخاص باستخدام SortableJS
        let sortableInstances = {};
        let reorderMode = false;

        function toggleReorderMode(imageId) {
            const container = $(`.mentioned-persons-container[data-image-id="${imageId}"]`);
            const sortableList = container.find('.sortable-list');

            if (reorderMode) {
                // إلغاء وضع إعادة الترتيب
                container.removeClass('reorder-mode');
                container.find('.reorder-controls').remove();

                // تدمير Sortable instance
                if (sortableInstances[imageId]) {
                    sortableInstances[imageId].destroy();
                    delete sortableInstances[imageId];
                }
                reorderMode = false;
            } else {
                // تفعيل وضع إعادة الترتيب
                container.addClass('reorder-mode');

                // إضافة أزرار التحكم
                const controls = `
                    <div class="reorder-controls">
                        <button type="button" class="btn btn-sm btn-success" onclick="saveReorder(${imageId})">
                            <i class="fas fa-check"></i> حفظ الترتيب
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="cancelReorder(${imageId})">
                            <i class="fas fa-times"></i> إلغاء
                        </button>
                    </div>
                `;
                container.append(controls);

                // إنشاء Sortable instance
                sortableInstances[imageId] = new Sortable(sortableList[0], {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    handle: '.mentioned-person-item',
                    onStart: function(evt) {
                        console.log('بدء السحب:', evt.item.textContent);
                    },
                    onEnd: function(evt) {
                        console.log('انتهاء السحب:', evt.item.textContent);
                    }
                });

                reorderMode = true;
            }
        }

        function saveReorder(imageId) {
            const container = $(`.mentioned-persons-container[data-image-id="${imageId}"]`);
            const personIds = [];

            container.find('.mentioned-person-item').each(function() {
                personIds.push($(this).data('person-id'));
            });

            console.log('ترتيب الأشخاص الجديد:', personIds);

            // إظهار مؤشر التحميل
            const saveBtn = container.find('.reorder-controls .btn-success');
            const originalText = saveBtn.html();
            saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جارٍ الحفظ...');

            $.ajax({
                url: `/dashboard/images/${imageId}/reorder-persons`,
                method: 'POST',
                data: {
                    person_ids: personIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('استجابة الخادم:', response);
                    if (response.success) {
                        alert('تم حفظ الترتيب بنجاح');
                        location.reload();
                    } else {
                        alert(response.message || 'تعذر حفظ الترتيب');
                        saveBtn.prop('disabled', false).html(originalText);
                    }
                },
                error: function(xhr) {
                    console.error('خطأ في AJAX:', xhr);
                    let errorMessage = 'خطأ في الاتصال بالخادم';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 422) {
                        errorMessage = 'البيانات المرسلة غير صحيحة';
                    } else if (xhr.status === 404) {
                        errorMessage = 'الصورة غير موجودة';
                    }

                    alert(errorMessage);
                    saveBtn.prop('disabled', false).html(originalText);
                }
            });
        }

        function cancelReorder(imageId) {
            const container = $(`.mentioned-persons-container[data-image-id="${imageId}"]`);
            container.removeClass('reorder-mode');
            container.find('.reorder-controls').remove();

            // تدمير Sortable instance
            if (sortableInstances[imageId]) {
                sortableInstances[imageId].destroy();
                delete sortableInstances[imageId];
            }

            reorderMode = false;
            location.reload(); // إعادة تحميل لاستعادة الترتيب الأصلي
        }

        // التحكم في نوع المحتوى في مودال التعديل
        $(document).ready(function() {
            $('#edit_media_type_image').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#edit-image-section').show();
                    $('#edit-youtube-section').hide();
                }
            });

            $('#edit_media_type_youtube').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#edit-image-section').hide();
                    $('#edit-youtube-section').show();
                }
            });
        });
    </script>
@endpush
