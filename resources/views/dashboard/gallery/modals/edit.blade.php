<div class="modal fade" id="editImageModal" tabindex="-1" role="dialog" aria-labelledby="editImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="editImageForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editImageModalLabel">تعديل الصورة</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>اسم الصورة</label>
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

                        <div id="edit-youtube-section" style="display: none;">
                            <div class="form-group">
                                <label>رابط يوتيوب</label>
                                <input type="text" name="youtube_url" id="editYoutubeUrl" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                                <small class="text-muted d-block mt-1">أدخل رابط فيديو يوتيوب صحيح.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>الأشخاص المذكورين في الصورة (اختياري)</label>
                    <div class="mentioned-persons-edit-container">
                        <select name="mentioned_persons[]" id="editMentionedPersons" class="form-control" multiple>
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
                    <label>وصف الصورة (اختياري)</label>
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
