<div class="modal fade" id="deleteArticleModal{{ $article->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteArticleModalLabel{{ $article->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('articles.destroy', $article->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteArticleModalLabel{{ $article->id }}">تأكيد الحذف</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد من رغبتك في حذف هذا المقال؟</p>
                    <p><strong>{{ $article->title }}</strong></p>
                    <p class="text-danger">لا يمكن التراجع عن هذا الإجراء، سيتم حذف جميع الصور المرتبطة به أيضًا.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">نعم، قم بالحذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
