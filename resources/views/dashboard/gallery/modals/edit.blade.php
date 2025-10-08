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
                            <label>الصورة الحالية</label>
                            <div id="currentImagePreview" class="text-center">
                                <img id="currentImage" src="" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>استبدال الصورة (اختياري)</label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="editImageFile" accept="image/*">
                                <label class="custom-file-label" for="editImageFile">اختر صورة جديدة...</label>
                            </div>
                            <small class="text-muted d-block mt-1">إذا لم تختر صورة جديدة، سيتم الاحتفاظ بالصورة الحالية.</small>
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
