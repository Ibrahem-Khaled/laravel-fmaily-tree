<div class="modal fade" id="editImageModal{{ $image->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editImageModalLabel{{ $image->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editImageModalLabel{{ $image->id }}">تعديل الصورة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('images.update', $image->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group text-center">
                        <img src="{{ asset('storage/' . $image->path) }}" class="img-thumbnail" width="150"
                            alt="current image">
                    </div>
                    <div class="form-group">
                        <label for="name{{ $image->id }}">اسم الصورة</label>
                        <input type="text" class="form-control" id="name{{ $image->id }}" name="name"
                            value="{{ old('name', $image->name) }}">
                    </div>
                    <div class="form-group">
                        <label for="category_id{{ $image->id }}">القسم</label>
                        <select class="form-control" id="category_id{{ $image->id }}" name="category_id">
                            <option value="">-- اختر قسماً --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $image->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="path{{ $image->id }}">تغيير ملف الصورة (اختياري)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="path{{ $image->id }}"
                                name="path">
                            <label class="custom-file-label" for="path{{ $image->id }}">اختر ملف جديد...</label>
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
