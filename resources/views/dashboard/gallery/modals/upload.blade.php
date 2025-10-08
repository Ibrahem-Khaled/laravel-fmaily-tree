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
                    <label>الصور (متعددة)</label>
                    <div class="custom-file">
                        <input type="file" name="images[]" class="custom-file-input" id="uploadImagesInput" multiple
                            required>
                        <label class="custom-file-label" for="uploadImagesInput">اختر ملفات...</label>
                    </div>
                    <small class="text-muted d-block mt-1">يمكن رفع عدة صور، بحد أقصى 4MB لكل صورة.</small>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">إغلاق</button>
                <button class="btn btn-primary" type="submit"><i class="fas fa-upload"></i> رفع</button>
            </div>
        </form>
    </div>
</div>
