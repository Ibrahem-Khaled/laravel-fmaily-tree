<div class="modal fade" id="showArticleModal{{ $article->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">عرض مقال: {{ $article->title }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p><strong>الحالة:</strong> {{ $article->status === 'published' ? 'منشورة' : 'مسودة' }}</p>
                <p><strong>الفئة:</strong> {{ optional($article->category)->name ?? '-' }}</p>
                <p><strong>الكاتب:</strong> {{ optional($article->person)->name ?? '-' }}</p>
                <hr>
                <div>{!! nl2br(e($article->content)) !!}</div>
                <hr>
                <h6 class="mb-2">الصور ({{ $article->images->count() }})</h6>
                <div class="d-flex flex-wrap">
                    @forelse($article->images as $img)
                        <div class="mr-2 mb-2">
                            <img src="{{ $img->path ? asset('storage/' . $img->path) : asset('img/no-image.png') }}"
                                class="img-thumbnail" style="width:120px; height:90px; object-fit:cover;">
                        </div>
                    @empty
                        <span class="text-muted">لا توجد صور</span>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
