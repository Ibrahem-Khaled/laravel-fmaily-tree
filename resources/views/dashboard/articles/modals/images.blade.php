<div class="modal fade" id="imagesModal{{ $article->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">معرض صور المقال: {{ $article->title }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('articles.images.store', $article) }}" method="POST"
                    enctype="multipart/form-data" class="mb-3">
                    @csrf
                    <div class="custom-file">
                        <input type="file" name="images[]" class="custom-file-input"
                            id="quickUpload{{ $article->id }}" multiple required>
                        <label class="custom-file-label" for="quickUpload{{ $article->id }}">اختر صورًا...</label>
                    </div>
                    <button class="btn btn-sm btn-primary mt-3"><i class="fas fa-upload"></i> رفع</button>
                </form>

                <div class="d-flex flex-wrap">
                    @forelse($article->images as $img)
                        <div class="border rounded p-1 mr-2 mb-2 text-center">
                            <img src="{{ asset('storage/' . $img->path) }}"
                                style="width:120px; height:90px; object-fit:cover;">
                            <form action="{{ route('images.destroy', $img) }}" method="POST" class="mt-1">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    @empty
                        <span class="text-muted">لا توجد صور بعد.</span>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
