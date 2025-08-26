<div class="modal fade" id="deleteImageModal{{ $image->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteImageModalLabel{{ $image->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteImageModalLabel{{ $image->id }}">تأكيد الحذف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                هل أنت متأكد من أنك تريد حذف هذه الصورة؟ لا يمكن التراجع عن هذا الإجراء.
                <div class="text-center my-3">
                    <img src="{{ asset('storage/' . $image->path) }}" class="img-thumbnail" width="120"
                        alt="image to delete">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <form action="{{ route('images.destroy', $image->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">نعم، احذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
