<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">تعديل الصنف: {{ $category->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name-{{ $category->id }}">اسم الصنف <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name-{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description-{{ $category->id }}">الوصف</label>
                        <textarea class="form-control" id="description-{{ $category->id }}" name="description" rows="3">{{ $category->description }}</textarea>
                    </div>
                    <div class="form-row">
                         <div class="form-group col-md-6">
                            <label for="parent_id-{{ $category->id }}">الصنف الأب</label>
                            <select class="form-control" id="parent_id-{{ $category->id }}" name="parent_id">
                                <option value="">-- صنف رئيسي --</option>
                                @foreach($allCategories as $cat)
                                     @if($cat->id !== $category->id) {{-- منع اختيار الصنف نفسه كأب --}}
                                        <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort_order-{{ $category->id }}">ترتيب الفرز <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="sort_order-{{ $category->id }}" name="sort_order" value="{{ $category->sort_order }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active-{{ $category->id }}" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active-{{ $category->id }}">مفعل</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>الصورة الحالية:</label>
                        <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('img/default-category.png') }}" alt="{{ $category->name }}" class="img-thumbnail" width="100">
                    </div>
                    <div class="form-group">
                        <label for="image-{{ $category->id }}">تغيير الصورة</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image-{{ $category->id }}" name="image">
                            <label class="custom-file-label" for="image-{{ $category->id }}">اختر ملف جديد...</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">تحديث</button>
                </div>
            </form>
        </div>
    </div>
</div>
