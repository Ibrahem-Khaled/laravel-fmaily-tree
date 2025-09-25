<div class="modal fade" id="createArticleModal" tabindex="-1" role="dialog" aria-labelledby="createArticleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createArticleModalLabel">إضافة مقال</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                {{-- العنوان --}}
                <div class="form-group">
                    <label>العنوان <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required maxlength="255"
                        value="{{ old('title') }}">
                </div>

                {{-- المحتوى --}}
                <div class="form-group">
                    <label>المحتوى</label>
                    <textarea name="content" class="form-control" rows="5">{{ old('content') }}</textarea>
                </div>

                <div class="form-row">
                    {{-- الفئة --}}
                    <div class="form-group col-md-5">
                        <label>الفئة (يتم إظهار الفئات التي لديها مقالات فقط)</label>
                        <div class="input-group">
                            <select name="category_id" class="form-control" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }} ({{ $cat->articles_count }})
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

                    {{-- الحالة --}}
                    <div class="form-group col-md-3">
                        <label>الحالة</label>
                        <select name="status" class="form-control" required>
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>مسودة
                            </option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>منشورة
                            </option>
                        </select>
                    </div>

                    {{-- الناشر (الشخص) --}}
                    <div class="form-group col-md-4">
                        <label>الناشر</label>
                        <select name="person_id" class="form-control">
                            <option value="">— بدون ناشر —</option>
                            @isset($people)
                                @foreach ($people as $p)
                                    <option value="{{ $p->id }}" {{ old('person_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->full_name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        <small class="text-muted d-block">اختياري. إن تركته فارغًا فسيُحفظ المقال بدون ناشر.</small>
                    </div>
                </div>

                {{-- صور متعددة --}}
                <div class="form-group">
                    <label>صور المقال (متعددة)</label>
                    <div class="custom-file">
                        <input type="file" name="images[]" class="custom-file-input" id="createArticleImages"
                            multiple>
                        <label class="custom-file-label" for="createArticleImages">اختر ملفات...</label>
                    </div>
                    <small class="text-muted d-block mt-1">يمكن رفع عدة صور، بحد أقصى 4MB للصورة.</small>
                </div>

                <div class="form-group">
                    <label>مرفقات المقال (متعددة)</label>
                    <div class="custom-file">
                        <input type="file" name="attachments[]" class="custom-file-input"
                            id="createArticleAttachments" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.zip">
                        <label class="custom-file-label" for="createArticleAttachments">اختر ملفات...</label>
                    </div>
                    <small class="text-muted d-block mt-1">
                        الصيغ المسموحة: PDF, DOC/DOCX, XLS/XLSX, ZIP — وبحد أقصى 10MB لكل ملف (طبقناها في الـ
                        FormRequest).
                    </small>
                </div>

                {{-- روابط فيديو يوتيوب (واحد في كل سطر) --}}
                <div class="form-group">
                    <label>روابط الفيديو (YouTube) — ضع كل رابط في سطر منفصل</label>
                    <textarea name="videos_text" class="form-control" rows="4" placeholder="مثال:\nhttps://www.youtube.com/watch?v=XXXXXXXXX\nhttps://youtu.be/XXXXXXXXX"></textarea>
                    <small class="text-muted d-block mt-1">ندعم روابط watch?v= و youtu.be و shorts/</small>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">إغلاق</button>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> حفظ</button>
            </div>
        </form>
    </div>
</div>
