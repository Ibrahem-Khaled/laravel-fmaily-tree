<div class="modal fade" id="uploadImagesModal" tabindex="-1" role="dialog" aria-labelledby="uploadImagesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="uploadImagesModalLabel">رفع صور إلى فئة</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>اختر الفئة</label>
                    <div class="input-group">
                        <select name="category_id" id="uploadCategorySelect" class="form-control" required>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" data-toggle="modal"
                                data-target="#quickCategoryModal">
                                <i class="fas fa-plus"></i> فئة جديدة
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>الأشخاص المذكورين في الصور (اختياري)</label>
                    <div class="mentioned-persons-upload-container">
                        <select name="mentioned_persons[]" id="mentionedPersonsSelect" class="form-control" multiple>
                            @foreach ($people as $person)
                                <option value="{{ $person->id }}">
                                    {{ $person->full_name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-2">
                            <small class="text-muted">يمكن اختيار أكثر من شخص للصورة الواحدة. الترتيب مهم - سيتم عرض الأشخاص بالترتيب الذي تختاره.</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>نوع المحتوى</label>
                    <div class="btn-group w-100" role="group" aria-label="نوع المحتوى">
                        <input type="radio" class="btn-check" name="content_type" id="content_type_images" value="images" checked>
                        <label class="btn btn-outline-primary" for="content_type_images">
                            <i class="fas fa-images"></i> صور
                        </label>

                        <input type="radio" class="btn-check" name="content_type" id="content_type_pdfs" value="pdfs">
                        <label class="btn btn-outline-warning" for="content_type_pdfs">
                            <i class="fas fa-file-pdf"></i> ملفات PDF
                        </label>

                        <input type="radio" class="btn-check" name="content_type" id="content_type_youtube" value="youtube">
                        <label class="btn btn-outline-danger" for="content_type_youtube">
                            <i class="fab fa-youtube"></i> يوتيوب
                        </label>
                    </div>
                </div>

                <div id="images-upload-section">
                    <div class="form-group">
                        <label>الصور (متعددة)</label>
                        <div class="custom-file">
                            <input type="file" name="images[]" class="custom-file-input" id="uploadImagesInput" multiple>
                            <label class="custom-file-label" for="uploadImagesInput">اختر ملفات...</label>
                        </div>
                        <small class="text-muted d-block mt-1">يمكن رفع عدة صور، بحد أقصى 150MB لكل صورة.</small>
                    </div>
                </div>

                <div id="pdfs-upload-section" style="display: none;">
                    <div class="form-group">
                        <label>ملفات PDF (متعددة)</label>
                        <div class="custom-file">
                            <input type="file" name="pdfs[]" class="custom-file-input" id="uploadPdfsInput" multiple accept=".pdf">
                            <label class="custom-file-label" for="uploadPdfsInput">اختر ملفات PDF...</label>
                        </div>
                        <small class="text-muted d-block mt-1">يمكن رفع عدة ملفات PDF، بحد أقصى 50MB لكل ملف.</small>
                    </div>

                    <div class="form-group">
                        <label>الصور المصغرة للملفات PDF (اختياري)</label>
                        <div class="custom-file">
                            <input type="file" name="thumbnails[]" class="custom-file-input" id="uploadThumbnailsInput" multiple accept="image/*">
                            <label class="custom-file-label" for="uploadThumbnailsInput">اختر صور مصغرة...</label>
                        </div>
                        <small class="text-muted d-block mt-1">يمكن رفع صور مصغرة مخصصة للملفات PDF، بحد أقصى 10MB لكل صورة.</small>
                    </div>
                </div>

                <div id="youtube-upload-section" style="display: none;">
                    <div class="form-group">
                        <label>روابط يوتيوب</label>
                        <div id="youtube-urls-container">
                            <div class="youtube-url-group mb-3">
                                <div class="input-group mb-2">
                                    <input type="text" name="youtube_urls[]" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                                    <input type="text" name="youtube_names[]" class="form-control" placeholder="اسم الفيديو (اختياري)">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-danger" onclick="removeYoutubeUrl(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="youtube_thumbnails[]" class="custom-file-input youtube-thumbnail-input" accept="image/*">
                                    <label class="custom-file-label">صورة مصغرة مخصصة (اختياري)</label>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="addYoutubeUrl()">
                            <i class="fas fa-plus"></i> إضافة رابط آخر
                        </button>
                        <small class="text-muted d-block mt-1">يمكن إضافة عدة روابط يوتيوب مع صور مصغرة مخصصة.</small>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">إغلاق</button>
                <button class="btn btn-primary" type="submit"><i class="fas fa-upload"></i> رفع</button>
            </div>
        </form>
    </div>
</div>

<script>
// التحكم في نوع المحتوى
document.addEventListener('DOMContentLoaded', function() {
    const contentTypeImages = document.getElementById('content_type_images');
    const contentTypePdfs = document.getElementById('content_type_pdfs');
    const contentTypeYoutube = document.getElementById('content_type_youtube');
    const imagesSection = document.getElementById('images-upload-section');
    const pdfsSection = document.getElementById('pdfs-upload-section');
    const youtubeSection = document.getElementById('youtube-upload-section');
    const imagesInput = document.getElementById('uploadImagesInput');
    const pdfsInput = document.getElementById('uploadPdfsInput');

    function toggleSections() {
        // إخفاء جميع الأقسام أولاً
        imagesSection.style.display = 'none';
        pdfsSection.style.display = 'none';
        youtubeSection.style.display = 'none';

        // إزالة الـ required من جميع المدخلات
        imagesInput.required = false;
        pdfsInput.required = false;

        if (contentTypeImages.checked) {
            imagesSection.style.display = 'block';
            imagesInput.required = true;
        } else if (contentTypePdfs.checked) {
            pdfsSection.style.display = 'block';
            pdfsInput.required = true;
        } else if (contentTypeYoutube.checked) {
            youtubeSection.style.display = 'block';
        }
    }

    contentTypeImages.addEventListener('change', toggleSections);
    contentTypePdfs.addEventListener('change', toggleSections);
    contentTypeYoutube.addEventListener('change', toggleSections);

    // Initialize
    toggleSections();
});

// إضافة رابط يوتيوب جديد
function addYoutubeUrl() {
    const container = document.getElementById('youtube-urls-container');
    const newUrlGroup = document.createElement('div');
    newUrlGroup.className = 'youtube-url-group mb-3';
    newUrlGroup.innerHTML = `
        <div class="input-group mb-2">
            <input type="text" name="youtube_urls[]" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
            <input type="text" name="youtube_names[]" class="form-control" placeholder="اسم الفيديو (اختياري)">
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-danger" onclick="removeYoutubeUrl(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="custom-file">
            <input type="file" name="youtube_thumbnails[]" class="custom-file-input youtube-thumbnail-input" accept="image/*">
            <label class="custom-file-label">صورة مصغرة مخصصة (اختياري)</label>
        </div>
    `;
    container.appendChild(newUrlGroup);
}

// حذف رابط يوتيوب
function removeYoutubeUrl(button) {
    const container = document.getElementById('youtube-urls-container');
    if (container.children.length > 1) {
        button.closest('.youtube-url-group').remove();
    }
}

// عرض أسماء الملفات المختارة
document.addEventListener('DOMContentLoaded', function() {
    // للصور المصغرة PDF
    const thumbnailInput = document.getElementById('uploadThumbnailsInput');
    if (thumbnailInput) {
        thumbnailInput.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.files.length > 0) {
                label.textContent = `${this.files.length} ملف مختار`;
            } else {
                label.textContent = 'اختر صور مصغرة...';
            }
        });
    }

    // للصور المصغرة يوتيوب
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('youtube-thumbnail-input')) {
            const label = e.target.nextElementSibling;
            if (e.target.files.length > 0) {
                label.textContent = e.target.files[0].name;
            } else {
                label.textContent = 'صورة مصغرة مخصصة (اختياري)';
            }
        }
    });
});
</script>
