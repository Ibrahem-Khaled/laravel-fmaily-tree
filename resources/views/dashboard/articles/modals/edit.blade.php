<div class="modal fade" id="editArticleModal{{ $article->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editArticleModalLabel{{ $article->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">تعديل مقال: {{ $article->title }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>العنوان</label>
                    <input type="text" name="title" class="form-control" required maxlength="255"
                        value="{{ $article->title }}">
                </div>

                <div class="form-group">
                    <label>المحتوى</label>
                    <textarea name="content" class="form-control" rows="5">{{ $article->content }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>الفئة</label>
                        <select name="category_id" class="form-control" required>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ $article->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>الحالة</label>
                        <select name="status" class="form-control" required>
                            <option value="draft" {{ $article->status === 'draft' ? 'selected' : '' }}>مسودة</option>
                            <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>منشورة
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>إضافة صور جديدة (اختياري)</label>
                    <div class="custom-file">
                        <input type="file" name="images[]" class="custom-file-input"
                            id="editArticleImages{{ $article->id }}" multiple>
                        <label class="custom-file-label" for="editArticleImages{{ $article->id }}">اختر
                            ملفات...</label>
                    </div>
                </div>

                <div>
                    <label class="d-block">صور حالية:</label>
                    <div class="d-flex flex-wrap">
                        @forelse($article->images as $img)
                            <div class="border rounded p-1 mr-2 mb-2 text-center">
                                <img src="{{ asset('storage/' . $img->path) }}"
                                    style="width:100px; height:75px; object-fit:cover;">
                                <form action="{{ route('images.destroy', $img) }}" method="POST"
                                    class="mt-1">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </div>
                        @empty
                            <span class="text-muted">لا توجد صور</span>
                        @endforelse
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">إغلاق</button>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> حفظ</button>
            </div>
        </form>
    </div>
</div>
