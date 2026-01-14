{{-- Tab 1: معلومات العنصر --}}
<div class="tab-pane fade show active" id="info" role="tabpanel">
    <div class="row">
        <div class="col-lg-8">
            <div class="card section-card">
                <div class="card-body p-4">
                    <h5 class="mb-4 font-weight-bold">
                        <i class="fas fa-edit text-primary mr-2"></i>تعديل بيانات العنصر
                    </h5>
                    <form action="{{ route('dashboard.proud-of.update', $item) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="proud_of_title" class="font-weight-bold">
                                عنوان العنصر <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="proud_of_title"
                                   id="proud_of_title"
                                   class="form-control @error('proud_of_title') is-invalid @enderror"
                                   value="{{ old('proud_of_title', $item->proud_of_title) }}"
                                   required
                                   maxlength="255">
                            @error('proud_of_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="proud_of_description" class="font-weight-bold">
                                وصف العنصر
                            </label>
                            <textarea name="proud_of_description"
                                      id="proud_of_description"
                                      class="form-control @error('proud_of_description') is-invalid @enderror"
                                      rows="5"
                                      placeholder="اكتب وصفاً للعنصر...">{{ old('proud_of_description', $item->proud_of_description) }}</textarea>
                            @error('proud_of_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name" class="font-weight-bold">
                                اسم مختصر (اختياري)
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $item->name) }}"
                                   maxlength="255"
                                   placeholder="مثال: معرض الصور، ذكريات">
                            <small class="form-text text-muted">
                                سيظهر كعنوان قسم الصور في صفحة العنصر العامة
                            </small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">الصورة الرئيسية</label>
                                    <div class="custom-file">
                                        <input type="file"
                                               name="image"
                                               id="image"
                                               class="custom-file-input @error('image') is-invalid @enderror"
                                               accept="image/*">
                                        <label class="custom-file-label" for="image">اختر صورة...</label>
                                    </div>
                                    <small class="form-text text-muted">JPEG, PNG, JPG, GIF, WebP (حد أقصى 5MB)</small>
                                    @error('image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">صورة الغلاف (اختياري)</label>
                                    <div class="custom-file">
                                        <input type="file"
                                               name="cover_image"
                                               id="cover_image"
                                               class="custom-file-input @error('cover_image') is-invalid @enderror"
                                               accept="image/*">
                                        <label class="custom-file-label" for="cover_image">اختر صورة غلاف...</label>
                                    </div>
                                    <small class="form-text text-muted">تظهر في أعلى صفحة التفاصيل</small>
                                    @error('cover_image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if($item->path || $item->cover_image_path)
                            <div class="row mb-3">
                                @if($item->path)
                                    <div class="col-md-6">
                                        <label class="font-weight-bold small">الصورة الرئيسية الحالية:</label>
                                        <img src="{{ asset('storage/' . $item->path) }}" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                                    </div>
                                @endif
                                @if($item->cover_image_path)
                                    <div class="col-md-6">
                                        <label class="font-weight-bold small">صورة الغلاف الحالية:</label>
                                        <img src="{{ asset('storage/' . $item->cover_image_path) }}" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="fas fa-save mr-2"></i>حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card section-card">
                <div class="card-body text-center">
                    @if($item->path)
                        <img src="{{ asset('storage/' . $item->path) }}" class="img-fluid rounded shadow mb-3" alt="{{ $item->proud_of_title }}">
                    @else
                        <div class="empty-state py-4">
                            <i class="fas fa-image"></i>
                            <p class="mb-0">لا توجد صورة</p>
                        </div>
                    @endif
                    <div class="text-muted small">
                        <p class="mb-1"><strong>تاريخ الإنشاء:</strong><br>{{ $item->created_at->format('Y-m-d') }}</p>
                        <p class="mb-0"><strong>آخر تحديث:</strong><br>{{ $item->updated_at->diffForHumans() }}</p>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('programs.show', $item) }}" target="_blank" class="btn btn-success btn-sm">
                            <i class="fas fa-eye mr-1"></i>معاينة الصفحة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
