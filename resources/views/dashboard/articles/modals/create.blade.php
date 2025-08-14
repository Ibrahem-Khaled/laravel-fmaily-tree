<div class="modal fade" id="createArticleModal" tabindex="-1" role="dialog" aria-labelledby="createArticleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createArticleModalLabel">إضافة مقال جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Article Title --}}
                    <div class="form-group">
                        <label for="title">عنوان المقال</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>

                    {{-- Article Content --}}
                    <div class="form-group">
                        <label for="content">محتوى المقال</label>
                        <textarea class="form-control" name="content" rows="5" required></textarea>
                    </div>

                    {{-- Category Selection --}}
                    <div class="form-group">
                        <label for="category_id">الفئة</label>
                        <div class="input-group">
                            <select class="form-control" name="category_id" id="category_id_select" required>
                                <option value="">-- اختر فئة --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @if ($category->children)
                                        @foreach ($category->children as $child)
                                            <option value="{{ $child->id }}">-- {{ $child->name }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" data-toggle="modal"
                                    data-target="#createCategoryModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Image Uploader --}}
                    <div class="form-group">
                        <label>صور المقال</label>
                        <div id="image-uploader-container">
                        </div>
                        <button type="button" class="btn btn-sm btn-success mt-2" id="add-image-btn">
                            <i class="fas fa-plus"></i> إضافة صورة أخرى
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">حفظ المقال</button>
                </div>
            </form>
        </div>
    </div>
</div>
