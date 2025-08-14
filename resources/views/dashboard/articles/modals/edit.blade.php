<div class="modal fade" id="editArticleModal{{ $article->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editArticleModalLabel{{ $article->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editArticleModalLabel{{ $article->id }}">تعديل المقال</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Article Title --}}
                    <div class="form-group">
                        <label for="title">عنوان المقال</label>
                        <input type="text" class="form-control" name="title" value="{{ $article->title }}"
                            required>
                    </div>

                    {{-- Article Content --}}
                    <div class="form-group">
                        <label for="content">محتوى المقال</label>
                        <textarea class="form-control" name="content" rows="5" required>{{ $article->content }}</textarea>
                    </div>

                    {{-- Category Selection --}}
                    <div class="form-group">
                        <label for="category_id">الفئة</label>
                        <div class="input-group">
                            <select class="form-control" name="category_id" required>
                                <option value="">-- اختر فئة --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $article->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                    @if ($category->children)
                                        @foreach ($category->children as $child)
                                            <option value="{{ $child->id }}"
                                                {{ $article->category_id == $child->id ? 'selected' : '' }}>--
                                                {{ $child->name }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                            {{-- زر إضافة فئة جديدة يمكن إضافته هنا بنفس طريقة مودال الإنشاء --}}
                        </div>
                    </div>

                    {{-- Person Selection --}}
                    <div class="form-group">
                        <label for="person_id">الشخص</label>
                        <select class="form-control" name="person_id">
                            <option value="">-- اختر شخص --</option>
                            @foreach ($persons as $person)
                                <option value="{{ $person->id }}"
                                    {{ $article->person_id == $person->id ? 'selected' : '' }}>
                                    {{ $person->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Image Uploader --}}
                    <div class="form-group">
                        <label>إضافة صور جديدة</label>
                        {{-- يمكنك إضافة نفس الجزء الخاص برفع الصور الديناميكي من مودال الإنشاء هنا --}}
                        <p class="text-muted small">سيتم إضافة الصور الجديدة إلى الصور الحالية.</p>
                    </div>

                    {{-- Existing Images --}}
                    <div>
                        <strong>الصور الحالية:</strong>
                        {{-- يمكنك عرض الصور الحالية مع زر حذف بجانب كل صورة --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>
