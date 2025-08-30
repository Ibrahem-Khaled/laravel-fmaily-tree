<div class="modal fade" id="deleteArticleModal{{ $article->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('articles.destroy', $article) }}" method="POST" class="modal-content">
            @csrf @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title">حذف مقال</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                هل أنت متأكد من حذف المقال "<strong>{{ $article->title }}</strong>"؟ سيتم حذف الصور المرتبطة.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">إلغاء</button>
                <button class="btn btn-danger" type="submit"><i class="fas fa-trash"></i> حذف</button>
            </div>
        </form>
    </div>
</div>
