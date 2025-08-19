<div class="modal fade" id="showArticleModal{{ $article->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showArticleModalLabel{{ $article->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showArticleModalLabel{{ $article->id }}">تفاصيل المقال:
                    {{ $article->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5><strong>العنوان:</strong> {{ $article->title }}</h5>
                <hr>
                <p><strong>الفئة:</strong> {{ $article->category->name ?? 'غير محدد' }}</p>
                @if ($article->person)
                    <p><strong>الكاتب:</strong> {{ $article->person->first_name }}</p>
                @endif
                <hr>
                <div>
                    <strong>المحتوى:</strong>
                    <div class="mt-2 p-3 bg-light border rounded">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                </div>
                <hr>
                <div>
                    <strong>الصور:</strong>
                    <div class="row mt-2">
                        @forelse($article->images as $image)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="card-img-top"
                                        alt="{{ $image->name ?? $article->title }}">
                                    @if ($image->name)
                                        <div class="card-footer text-muted small">
                                            {{ $image->name }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p>لا توجد صور مرفقة مع هذا المقال.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
