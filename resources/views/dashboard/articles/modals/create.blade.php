<div class="modal fade" id="createArticleModal" tabindex="-1" role="dialog" aria-labelledby="createArticleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createArticleModalLabel">إضافة مقال</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>العنوان <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required maxlength="255">
                </div>

                <div class="form-group">
                    <label>المحتوى</label>
                    <textarea name="content" class="form-control" rows="5"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label>الفئة (يتم إظهار الفئات التي لديها مقالات فقط)</label>
                        <div class="input-group">
                            <select name="category_id" class="form-control" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->articles_count }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" data-toggle="modal"
                                    data-target="#quickCategoryModal">
                                    <i class="fas fa-plus"></i> فئة جديدة
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">الفئات هنا مُفلترة بـ <code>whereHas('articles')</code>.</small>
                    </div>

                    <div class="form-group col-md-4">
                        <label>الحالة</label>
                        <select name="status" class="form-control" required>
                            <option value="draft">مسودة</option>
                            <option value="published">منشورة</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>صور المقال (متعددة)</label>
                    <div class="custom-file">
                        <input type="file" name="images[]" class="custom-file-input" id="createArticleImages"
                            multiple>
                        <label class="custom-file-label" for="createArticleImages">اختر ملفات...</label>
                    </div>
                    <small class="text-muted d-block mt-1">يمكن رفع عدة صور، بحد أقصى 4MB للصورة.</small>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">إغلاق</button>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> حفظ</button>
            </div>
        </form>
    </div>
</div>
