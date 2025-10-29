<div class="modal fade" id="deleteStoryModal{{ $story->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteStoryModalLabel{{ $story->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteStoryModalLabel{{ $story->id }}">تأكيد الحذف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                هل أنت متأكد من رغبتك في حذف القصة "<strong>{{ $story->title }}</strong>"؟ لا يمكن التراجع عن هذا
                الإجراء.
            </div>
            <div class="modal-footer">
                <form action="{{ route('stories.destroy', $story->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">نعم، قم بالحذف</button>
                </form>
            </div>
        </div>
    </div>
</div>


