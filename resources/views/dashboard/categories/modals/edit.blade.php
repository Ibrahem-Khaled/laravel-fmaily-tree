<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">تعديل القسم:
                        {{ $category->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name{{ $category->id }}">اسم القسم <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name{{ $category->id }}" name="name"
                            value="{{ $category->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="parent_id{{ $category->id }}">القسم الرئيسي (اختياري)</label>
                        <select class="form-control" id="parent_id{{ $category->id }}" name="parent_id">
                            <option value="">-- قسم رئيسي --</option>
                            @foreach ($mainCategories as $parent)
                                @if ($parent->id != $category->id)
                                    {{-- لا يمكن أن يكون القسم أب لنفسه --}}
                                    <option value="{{ $parent->id }}" @selected($category->parent_id == $parent->id)>
                                        {{ $parent->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description{{ $category->id }}">الوصف</label>
                        <textarea class="form-control" id="description{{ $category->id }}" name="description" rows="3">{{ $category->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>الصورة الحالية</label>
                        <div>
                            <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('img/default-image.png') }}"
                                alt="{{ $category->name }}" class="img-thumbnail" width="150">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image{{ $category->id }}">تغيير الصورة</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image{{ $category->id }}"
                                name="image" accept="image/*">
                            <label class="custom-file-label" for="image{{ $category->id }}">اختر صورة جديدة...</label>
                        </div>
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
