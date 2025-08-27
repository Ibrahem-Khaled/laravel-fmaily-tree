<div class="modal fade" id="createImageModal" tabindex="-1" role="dialog" aria-labelledby="createImageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createImageModalLabel">إضافة صورة جديدة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">اسم الصورة (اختياري)</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="category_selection">القسم</label>
                        {{-- تم تعديل name و id --}}
                        <select class="form-control" id="category_selection" name="category_selection"
                            style="width: 100%;">
                            <option></option> {{-- Select2 يستخدم هذا الخيار الفارغ لعرض الـ placeholder --}}
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="path">ملف الصورة</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="path" name="path" required>
                            <label class="custom-file-label" for="path">اختر ملف...</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
