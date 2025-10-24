<div class="modal fade" id="editImageModal" tabindex="-1" role="dialog" aria-labelledby="editImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="editImageForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editImageModalLabel">تعديل الملف</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>اسم الملف</label>
                            <input type="text" name="name" id="editImageName" class="form-control" maxlength="255">
                        </div>

                        <div class="form-group">
                            <label>الفئة</label>
                            <select name="category_id" id="editImageCategory" class="form-control">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>المحتوى الحالي</label>
                            <div id="currentMediaPreview" class="text-center">
                                <img id="currentImage" src="" style="max-width: 200px; max-height: 150px;" class="img-thumbnail" style="display: none;">
                                <div id="currentPdfPreview" style="display: none;">
                                    <div class="border rounded p-3 bg-light">
                                        <svg class="w-12 h-12 text-danger mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                        </svg>
                                        <div class="text-sm text-muted">ملف PDF</div>
                                        <div id="currentPdfSize" class="text-xs text-muted"></div>
                                    </div>
                                </div>
                                <div id="currentYoutubePreview" style="display: none;">
                                    <iframe id="currentYoutubeIframe" width="200" height="150" frameborder="0" allowfullscreen></iframe>
                                    <div class="mt-2">
                                        <a id="currentYoutubeLink" href="" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="fab fa-youtube"></i> مشاهدة على يوتيوب
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>نوع المحتوى الجديد</label>
                            <div class="btn-group w-100" role="group" aria-label="نوع المحتوى">
                                <input type="radio" class="btn-check" name="edit_media_type" id="edit_media_type_image" value="image">
                                <label class="btn btn-outline-primary" for="edit_media_type_image">
                                    <i class="fas fa-images"></i> صورة
                                </label>

                                <input type="radio" class="btn-check" name="edit_media_type" id="edit_media_type_pdf" value="pdf">
                                <label class="btn btn-outline-warning" for="edit_media_type_pdf">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </label>

                                <input type="radio" class="btn-check" name="edit_media_type" id="edit_media_type_youtube" value="youtube">
                                <label class="btn btn-outline-danger" for="edit_media_type_youtube">
                                    <i class="fab fa-youtube"></i> يوتيوب
                                </label>
                            </div>
                        </div>

                        <div id="edit-image-section">
                            <div class="form-group">
                                <label>استبدال الصورة (اختياري)</label>
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input" id="editImageFile" accept="image/*">
                                    <label class="custom-file-label" for="editImageFile">اختر صورة جديدة...</label>
                                </div>
                                <small class="text-muted d-block mt-1">إذا لم تختر صورة جديدة، سيتم الاحتفاظ بالصورة الحالية.</small>
                            </div>
                        </div>

                        <div id="edit-pdf-section" style="display: none;">
                            <div class="form-group">
                                <label>استبدال ملف PDF (اختياري)</label>
                                <div class="custom-file">
                                    <input type="file" name="pdf" class="custom-file-input" id="editPdfFile" accept=".pdf">
                                    <label class="custom-file-label" for="editPdfFile">اختر ملف PDF جديد...</label>
                                </div>
                                <small class="text-muted d-block mt-1">إذا لم تختر ملف جديد، سيتم الاحتفاظ بالملف الحالي.</small>
                            </div>

                            <div class="form-group">
                                <label>صورة مصغرة مخصصة (اختياري)</label>
                                <div class="custom-file">
                                    <input type="file" name="pdf_thumbnail" class="custom-file-input" id="editThumbnailFile" accept="image/*">
                                    <label class="custom-file-label" for="editThumbnailFile">اختر صورة مصغرة...</label>
                                </div>
                                <small class="text-muted d-block mt-1">صورة مصغرة مخصصة لملف PDF، بحد أقصى 10MB.</small>
                                <div class="mt-2">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="remove_pdf_thumbnail" value="1" class="form-check-input">
                                        حذف الصورة المصغرة الحالية
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="edit-youtube-section" style="display: none;">
                            <div class="form-group">
                                <label>رابط يوتيوب</label>
                                <input type="text" name="youtube_url" id="editYoutubeUrl" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                                <small class="text-muted d-block mt-1">أدخل رابط فيديو يوتيوب صحيح.</small>
                            </div>

                            <div class="form-group">
                                <label>صورة مصغرة مخصصة (اختياري)</label>
                                <div class="custom-file">
                                    <input type="file" name="youtube_thumbnail" class="custom-file-input" id="editYoutubeThumbnailFile" accept="image/*">
                                    <label class="custom-file-label" for="editYoutubeThumbnailFile">اختر صورة مصغرة...</label>
                                </div>
                                <small class="text-muted d-block mt-1">صورة مصغرة مخصصة لفيديو يوتيوب، بحد أقصى 10MB.</small>
                                <div class="mt-2">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="remove_youtube_thumbnail" value="1" class="form-check-input">
                                        حذف الصورة المصغرة الحالية
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>الأشخاص المذكورين في الملف (اختياري)</label>
                    <div class="mentioned-persons-edit-container">
                        <select name="mentioned_persons[]" id="editMentionedPersons" class="form-control" multiple>
                            @foreach ($people as $person)
                                <option value="{{ $person->id }}">
                                    {{ $person->full_name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-2">
                            <small class="text-muted">يمكن اختيار أكثر من شخص للملف الواحد. الترتيب مهم - سيتم عرض الأشخاص بالترتيب الذي تختاره.</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>وصف الملف (اختياري)</label>
                    <textarea name="description" id="editImageDescription" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// التحكم في نوع المحتوى في نموذج التعديل
document.addEventListener('DOMContentLoaded', function() {
    const editMediaTypeImage = document.getElementById('edit_media_type_image');
    const editMediaTypePdf = document.getElementById('edit_media_type_pdf');
    const editMediaTypeYoutube = document.getElementById('edit_media_type_youtube');
    const editImageSection = document.getElementById('edit-image-section');
    const editPdfSection = document.getElementById('edit-pdf-section');
    const editYoutubeSection = document.getElementById('edit-youtube-section');

    function toggleEditSections() {
        // إخفاء جميع الأقسام أولاً
        editImageSection.style.display = 'none';
        editPdfSection.style.display = 'none';
        editYoutubeSection.style.display = 'none';

        if (editMediaTypeImage.checked) {
            editImageSection.style.display = 'block';
        } else if (editMediaTypePdf.checked) {
            editPdfSection.style.display = 'block';
        } else if (editMediaTypeYoutube.checked) {
            editYoutubeSection.style.display = 'block';
        }
    }

    editMediaTypeImage.addEventListener('change', toggleEditSections);
    editMediaTypePdf.addEventListener('change', toggleEditSections);
    editMediaTypeYoutube.addEventListener('change', toggleEditSections);

    // Initialize
    toggleEditSections();

    // عرض أسماء الملفات المختارة
    document.addEventListener('change', function(e) {
        if (e.target.id === 'editThumbnailFile') {
            const label = e.target.nextElementSibling;
            if (e.target.files.length > 0) {
                label.textContent = e.target.files[0].name;
            } else {
                label.textContent = 'اختر صورة مصغرة...';
            }
        }

        if (e.target.id === 'editYoutubeThumbnailFile') {
            const label = e.target.nextElementSibling;
            if (e.target.files.length > 0) {
                label.textContent = e.target.files[0].name;
            } else {
                label.textContent = 'اختر صورة مصغرة...';
            }
        }
    });
});
</script>
