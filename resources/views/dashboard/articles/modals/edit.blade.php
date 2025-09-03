<div class="modal fade" id="editArticleModal{{ $article->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editArticleModalLabel{{ $article->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">تعديل مقال: {{ $article->title }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                {{-- معلومات أساسية --}}
                <div class="form-group mb-3">
                    <label class="mb-1">العنوان</label>
                    <input type="text" name="title" class="form-control" required maxlength="255"
                        value="{{ old('title', $article->title) }}">
                </div>

                <div class="form-group mb-4">
                    <label class="mb-1">المحتوى</label>
                    <textarea name="content" class="form-control" rows="5">{{ old('content', $article->content) }}</textarea>
                </div>

                <div class="form-row mb-4">
                    <div class="form-group col-md-4">
                        <label class="mb-1">الفئة</label>
                        <select name="category_id" class="form-control" required>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="mb-1">الحالة</label>
                        <select name="status" class="form-control" required>
                            <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>
                                مسودة</option>
                            <option value="published"
                                {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>منشورة</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="mb-1">الناشر</label>
                        <select name="person_id" class="form-control">
                            <option value="">— بدون ناشر —</option>
                            @isset($people)
                                @foreach ($people as $p)
                                    <option value="{{ $p->id }}"
                                        {{ (int) old('person_id', $article->person_id) === $p->id ? 'selected' : '' }}>
                                        {{ $p->full_name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>

                {{-- إدارة الصور --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <strong>الصور</strong>
                        <span class="badge badge-info">{{ $article->images->count() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="mb-1 d-block">إضافة صور جديدة (اختياري)</label>
                            <div class="custom-file">
                                <input type="file" name="images[]" class="custom-file-input"
                                    id="editArticleImages{{ $article->id }}" multiple>
                                <label class="custom-file-label" for="editArticleImages{{ $article->id }}">اختر
                                    ملفات...</label>
                            </div>
                            <small class="text-muted d-block mt-1">حد أقصى 4MB للصورة (تطبَّق عبر الـ
                                FormRequest).</small>
                        </div>

                        {{-- شبكة الصور الحالية --}}
                        <div class="thumbs-grid">
                            @forelse($article->images as $img)
                                <div class="thumb-card">
                                    <img src="{{ asset('storage/' . $img->path) }}" alt="صورة" class="thumb-img">
                                    {{-- زر الحذف يربط بنموذج حذف مستقل خارج المودال --}}
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-block mt-2"
                                        form="img-del-{{ $img->id }}">
                                        <i class="fas fa-trash-alt"></i> حذف
                                    </button>
                                </div>
                            @empty
                                <span class="text-muted">لا توجد صور</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- إدارة المرفقات --}}
                <div class="card mb-2 shadow-sm">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <strong>المرفقات</strong>
                        <span class="badge badge-secondary">{{ $article->attachments->count() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="mb-1 d-block">إضافة مرفقات جديدة (اختياري)</label>
                            <div class="custom-file">
                                <input type="file" name="attachments[]" class="custom-file-input"
                                    id="editArticleAttachments{{ $article->id }}" multiple
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.zip">
                                <label class="custom-file-label" for="editArticleAttachments{{ $article->id }}">اختر
                                    ملفات...</label>
                            </div>
                            <small class="text-muted d-block mt-1">
                                الصيغ المسموحة: PDF, DOC/DOCX, XLS/XLSX, ZIP — حد أقصى 10MB لكل ملف.
                            </small>
                        </div>

                        <div class="attach-list">
                            @forelse($article->attachments as $att)
                                <div class="attach-row">
                                    <div class="attach-icon"><i class="fas fa-paperclip"></i></div>
                                    <div class="attach-meta">
                                        <div class="attach-name">{{ $att->file_name }}</div>
                                        <div class="attach-type text-muted small">{{ $att->mime_type }}</div>
                                    </div>
                                    <div class="attach-actions">
                                        <a href="{{ route('attachments.download', $att) }}"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-download"></i> تنزيل
                                        </a>
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            form="att-del-{{ $att->id }}"
                                            onclick="return confirm('حذف هذا المرفق؟');">
                                            <i class="fas fa-trash-alt"></i> حذف
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <span class="text-muted">لا توجد مرفقات</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">إغلاق</button>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> حفظ</button>
            </div>
        </form>
    </div>
</div>

{{-- نماذج حذف "مستقلة" خارج المودال لتفادي تداخل النماذج --}}
@foreach ($article->images as $img)
    <form id="img-del-{{ $img->id }}" action="{{ route('images.destroy', $img) }}" method="POST"
        class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach

@foreach ($article->attachments as $att)
    <form id="att-del-{{ $att->id }}" action="{{ route('attachments.destroy', $att) }}" method="POST"
        class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach
