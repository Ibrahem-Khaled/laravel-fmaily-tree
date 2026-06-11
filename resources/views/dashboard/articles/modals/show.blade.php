<div class="modal fade" id="showArticleModal{{ $article->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $article->title }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>الفئة:</strong> <span class="badge badge-light px-2 py-1 text-primary">{{ optional($article->category)->name ?? '-' }}</span></p>
                        <p class="mb-2"><strong>الحالة:</strong> 
                            @if ($article->status === 'published')
                                <span class="badge px-3 py-1 text-success bg-success-light" style="border-radius: 30px; font-weight: 600; font-size: 0.8rem; background: rgba(40, 167, 69, 0.15);">
                                    منشور
                                </span>
                            @else
                                <span class="badge px-3 py-1 text-secondary bg-secondary-light" style="border-radius: 30px; font-weight: 600; font-size: 0.8rem; background: rgba(108, 117, 125, 0.15);">
                                    مسودة
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>الناشر:</strong> <span>{{ optional($article->person)->full_name ?? '-' }}</span></p>
                        <p class="mb-2"><strong>تاريخ النشر:</strong> <span>{{ $article->created_at ? $article->created_at->translatedFormat('d M Y') : '-' }}</span></p>
                    </div>
                </div>
                <hr>
                <div class="modal-article-content text-justify" style="line-height: 1.8; font-size: 1.05rem;">
                    {!! nl2br(e($article->content)) !!}
                </div>
                
                @if ($article->images->isNotEmpty())
                    <div class="mt-4">
                        <h6 class="font-weight-bold"><i class="fe fe-image mr-1"></i> الصور المرفقة:</h6>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($article->images as $img)
                                <a href="{{ asset('storage/' . $img->path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $img->path) }}" class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if ($article->videos->isNotEmpty())
                    <div class="mt-4">
                        <h6 class="font-weight-bold"><i class="fe fe-video mr-1"></i> الفيديوهات المرفقة:</h6>
                        <div class="list-group mt-2">
                            @foreach($article->videos as $v)
                                <a href="{{ $v->url }}" target="_blank" class="list-group-item list-group-item-action py-2">
                                    <i class="fe fe-youtube text-danger mr-2"></i> {{ $v->url }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
