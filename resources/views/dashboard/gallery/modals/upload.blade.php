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

                <div id="youtube-upload-section" style="display: none;">
                    <div class="form-group">
                        <label>روابط يوتيوب</label>
                        <div id="youtube-urls-container">
                            <div class="input-group mb-2">
                                <input type="text" name="youtube_urls[]" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                                <input type="text" name="youtube_names[]" class="form-control" placeholder="اسم الفيديو (اختياري)">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeYoutubeUrl(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="addYoutubeUrl()">
                            <i class="fas fa-plus"></i> إضافة رابط آخر
                        </button>
                        <small class="text-muted d-block mt-1">يمكن إضافة عدة روابط يوتيوب.</small>
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
    const contentTypeYoutube = document.getElementById('content_type_youtube');
    const imagesSection = document.getElementById('images-upload-section');
    const youtubeSection = document.getElementById('youtube-upload-section');
    const imagesInput = document.getElementById('uploadImagesInput');

    function toggleSections() {
        if (contentTypeImages.checked) {
            imagesSection.style.display = 'block';
            youtubeSection.style.display = 'none';
            imagesInput.required = true;
        } else {
            imagesSection.style.display = 'none';
            youtubeSection.style.display = 'block';
            imagesInput.required = false;
        }
    }

    contentTypeImages.addEventListener('change', toggleSections);
    contentTypeYoutube.addEventListener('change', toggleSections);

    // Initialize
    toggleSections();
});

// إضافة رابط يوتيوب جديد
function addYoutubeUrl() {
    const container = document.getElementById('youtube-urls-container');
    const newUrlGroup = document.createElement('div');
    newUrlGroup.className = 'input-group mb-2';
    newUrlGroup.innerHTML = `
        <input type="text" name="youtube_urls[]" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
        <input type="text" name="youtube_names[]" class="form-control" placeholder="اسم الفيديو (اختياري)">
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-danger" onclick="removeYoutubeUrl(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    container.appendChild(newUrlGroup);
}

// حذف رابط يوتيوب
function removeYoutubeUrl(button) {
    const container = document.getElementById('youtube-urls-container');
    if (container.children.length > 1) {
        button.closest('.input-group').remove();
    }
}
</script>
