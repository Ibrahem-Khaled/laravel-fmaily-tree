<div class="modal fade edit-article-modal" id="editArticleModal{{ $article->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editArticleModalLabel{{ $article->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- @method('PUT') --}}
                <div class="modal-header">
                    <h5 class="modal-title" id="editArticleModalLabel{{ $article->id }}">تعديل المقال</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- ... حقول العنوان والمحتوى والفئة تبقى كما هي ... --}}

                    {{-- Article Title --}}
                    <div class="form-group">
                        <label for="title_{{ $article->id }}">عنوان المقال</label>
                        <input type="text" class="form-control" name="title" id="title_{{ $article->id }}" value="{{ old('title', $article->title) }}" required>
                    </div>

                    {{-- Article Content --}}
                    <div class="form-group">
                        <label for="content_{{ $article->id }}">محتوى المقال</label>
                        <textarea class="form-control" name="content" id="content_{{ $article->id }}" rows="5" required>{{ old('content', $article->content) }}</textarea>
                    </div>

                    {{-- Category Selection --}}
                    <div class="form-group">
                        <label for="category_id_{{ $article->id }}">الفئة</label>
                        <div class="input-group">
                            <select class="form-control" name="category_id" id="category_id_{{ $article->id }}" required>
                                <option value="">-- اختر فئة --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $article->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @foreach ($category->children as $child)
                                        <option value="{{ $child->id }}" {{ $article->category_id == $child->id ? 'selected' : '' }}>
                                            -- {{ $child->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Person Selection --}}
                    <div class="form-group">
                        <label for="person_id_{{ $article->id }}">الشخص</label>
                        <select class="form-control" name="person_id" id="person_id_{{ $article->id }}">
                            <option value="">-- اختر شخص --</option>
                            @foreach ($persons as $person)
                                <option value="{{ $person->id }}" {{ $article->person_id == $person->id ? 'selected' : '' }}>
                                    {{ $person->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr>

                    {{-- Image Uploader --}}
                    {{-- <div class="form-group">
                        <label>إضافة صور جديدة</label>
                        <div class="image-uploader-container">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-image-btn">
                            <i class="fas fa-plus"></i> إضافة صورة أخرى
                        </button>
                        <p class="text-muted small">سيتم إضافة الصور الجديدة إلى الصور الحالية.</p>
                    </div> --}}

                    {{-- Existing Images --}}
                    @if ($article->images->count() > 0)
                        <div>
                            <strong>الصور الحالية:</strong>
                            <div class="row mt-2">
                                @foreach ($article->images as $image)
                                    <div class="col-md-3 text-center mb-3" id="image-{{ $image->id }}">
                                        <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid rounded mb-2"
                                            alt="{{ $image->name }}">
                                        <p class="small">{{ $image->name ?? 'بدون عنوان' }}</p>
                                        <button type="button" class="btn btn-sm btn-danger delete-image-btn"
                                            data-id="{{ $image->id }}">
                                            <i class="fas fa-trash"></i> حذف
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>
