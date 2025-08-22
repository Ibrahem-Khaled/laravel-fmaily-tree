{{-- resources/views/dashboard/articles/modals/add_images.blade.php --}}
<div class="modal fade" id="addImagesModal" tabindex="-1" role="dialog" aria-labelledby="addImagesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('articles.images.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addImagesModalLabel">إضافة صور لمقال موجود</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- حقل اختيار المقال --}}
                    <div class="form-group">
                        <label for="article_id_modal"><strong>اختر المقال <span
                                    class="text-danger">*</span></strong></label>
                        {{-- نستخدم هنا المتغير الذي أضفناه في الكونترولر --}}
                        <select class="form-control" id="article_id_modal" name="article_id" required>
                            <option value="">-- يرجى اختيار مقال --</option>
                            @foreach ($allArticlesForModal as $article)
                                <option value="{{ $article->id }}">
                                    {{ $article->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr>

                    {{-- حقول رفع الصور الديناميكية --}}
                    <div class="form-group">
                        <label><strong>إضافة صور جديدة <span class="text-danger">*</span></strong></label>
                        {{-- استخدمنا class فريد هنا لتجنب التعارض --}}
                        <div class="add-images-uploader-container">
                            {{-- سيتم إضافة حقول الصور هنا بواسطة JavaScript --}}
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-new-image-btn">
                            <i class="fas fa-plus"></i> إضافة حقل صورة آخر
                        </button>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">حفظ الصور</button>
                </div>
            </form>
        </div>
    </div>
</div>
