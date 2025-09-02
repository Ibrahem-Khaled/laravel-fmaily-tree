<div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteCategoryLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryLabel{{ $category->id }}">حذف تصنيف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categories.destroy', $category) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>هل أنت متأكد من حذف التصنيف <strong>{{ $category->name }}</strong>؟</p>
                    <ul class="small text-muted mb-0">
                        <li>لن يتم الحذف إذا كان لديه أبناء أو مقالات/صور مرتبطة.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
