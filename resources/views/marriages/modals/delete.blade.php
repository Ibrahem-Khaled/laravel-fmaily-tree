<div class="modal fade" id="deleteMarriageModal{{ $marriage->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteMarriageModalLabel{{ $marriage->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMarriageModalLabel{{ $marriage->id }}">تأكيد الحذف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من رغبتك في حذف سجل الزواج بين:</p>
                <p><strong>{{ $marriage->husband->name }}</strong> و <strong>{{ $marriage->wife->name }}</strong>؟</p>
                <p class="text-danger">هذا الإجراء لا يمكن التراجع عنه!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <form action="{{ route('marriages.destroy', $marriage->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
