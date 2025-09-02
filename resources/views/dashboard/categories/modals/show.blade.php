<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editCategoryLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryLabel{{ $category->id }}">تعديل تصنيف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>الاسم</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $category->name) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>الأب (اختياري)</label>
                            <select name="parent_id" class="form-control">
                                <option value="">— بدون —</option>
                                @foreach (\App\Models\Category::where('id', '!=', $category->id)->orderBy('name')->get() as $parent)
                                    <option value="{{ $parent->id }}"
                                        {{ (int) old('parent_id', $category->parent_id) === (int) $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>الوصف</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>الترتيب</label>
                            <input type="number" name="sort_order" class="form-control" min="0"
                                value="{{ old('sort_order', $category->sort_order) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>الصورة (اختياري)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image{{ $category->id }}"
                                    name="image" accept="image/*">
                                <label class="custom-file-label" for="image{{ $category->id }}">اختر ملفًا...</label>
                            </div>
                            @if ($category->image)
                                <small class="form-text text-muted mt-1">الحالي: <a
                                        href="{{ asset('storage/' . $category->image) }}" target="_blank">عرض
                                        الصورة</a></small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
