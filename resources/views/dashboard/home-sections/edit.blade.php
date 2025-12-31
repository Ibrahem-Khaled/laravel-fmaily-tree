@extends('layouts.app')

@section('title', 'تعديل قسم: ' . $homeSection->title)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-edit text-primary mr-2"></i>تعديل قسم: {{ $homeSection->title }}
            </h1>
            <p class="text-muted mb-0">قم بتعديل القسم وإدارة عناصره</p>
        </div>
        <a href="{{ route('dashboard.home-sections.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Section Info -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle mr-2"></i>معلومات القسم
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.home-sections.update', $homeSection) }}" method="POST">
                        @csrf
                        @method('POST')
                        
                        <div class="form-group">
                            <label for="title" class="font-weight-bold">عنوان القسم</label>
                            <input type="text" name="title" id="title" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $homeSection->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="section_type" class="font-weight-bold">نوع القسم</label>
                            <select name="section_type" id="section_type" 
                                    class="form-control @error('section_type') is-invalid @enderror" 
                                    required>
                                <option value="custom" {{ old('section_type', $homeSection->section_type) == 'custom' ? 'selected' : '' }}>مخصص</option>
                                <option value="text" {{ old('section_type', $homeSection->section_type) == 'text' ? 'selected' : '' }}>نص</option>
                                <option value="gallery" {{ old('section_type', $homeSection->section_type) == 'gallery' ? 'selected' : '' }}>معرض صور</option>
                                <option value="video_section" {{ old('section_type', $homeSection->section_type) == 'video_section' ? 'selected' : '' }}>قسم فيديو</option>
                                <option value="text_with_image" {{ old('section_type', $homeSection->section_type) == 'text_with_image' ? 'selected' : '' }}>نص مع صورة</option>
                                <option value="buttons" {{ old('section_type', $homeSection->section_type) == 'buttons' ? 'selected' : '' }}>أزرار وروابط</option>
                                <option value="mixed" {{ old('section_type', $homeSection->section_type) == 'mixed' ? 'selected' : '' }}>محتوى مختلط</option>
                            </select>
                            @error('section_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" 
                                       class="form-check-input" value="1" 
                                       {{ old('is_active', $homeSection->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    تفعيل القسم
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="css_classes" class="font-weight-bold">فئات CSS مخصصة</label>
                            <input type="text" name="css_classes" id="css_classes" 
                                   class="form-control @error('css_classes') is-invalid @enderror" 
                                   value="{{ old('css_classes', $homeSection->css_classes) }}">
                            @error('css_classes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-2"></i>حفظ التغييرات
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Section Items -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>عناصر القسم ({{ $homeSection->items->count() }})
                    </h6>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addItemModal">
                        <i class="fas fa-plus mr-1"></i>إضافة عنصر
                    </button>
                </div>
                <div class="card-body">
                    @if($homeSection->items->count() > 0)
                        <div id="items-list">
                            @foreach($homeSection->items as $item)
                                <div class="card mb-3 shadow-sm border-0 item-card" data-id="{{ $item->id }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-grip-vertical text-muted mr-3" style="cursor: move;"></i>
                                                    <span class="badge badge-info mr-2">{{ $item->item_type }}</span>
                                                    <span class="badge badge-dark">#{{ $item->display_order }}</span>
                                                </div>
                                                @if($item->item_type == 'image' && $item->image_url)
                                                    <img src="{{ $item->image_url }}" alt="Item" class="img-thumbnail" style="max-height: 100px;">
                                                @elseif($item->item_type == 'text' && isset($item->content['text']))
                                                    <p class="mb-0 text-muted">{{ \Illuminate\Support\Str::limit($item->content['text'], 100) }}</p>
                                                @elseif($item->item_type == 'video' && $item->youtube_url)
                                                    <small class="text-muted"><i class="fas fa-video mr-1"></i>فيديو يوتيوب</small>
                                                @endif
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-info border-0" onclick="editItem({{ $item->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('dashboard.home-section-items.destroy', [$homeSection, $item]) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا العنصر؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger border-0">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-list text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">لا توجد عناصر في هذا القسم</p>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItemModal">
                                <i class="fas fa-plus mr-2"></i>إضافة عنصر
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal إضافة عنصر -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle mr-2"></i>إضافة عنصر جديد
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.home-section-items.store', $homeSection) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="item_type" class="font-weight-bold">نوع العنصر <span class="text-danger">*</span></label>
                        <select name="item_type" id="item_type" class="form-control" required>
                            <option value="">-- اختر نوع العنصر --</option>
                            <option value="text">نص</option>
                            <option value="image">صورة</option>
                            <option value="video">فيديو</option>
                            <option value="button">زر</option>
                        </select>
                    </div>

                    <div class="form-group" id="media-group" style="display: none;">
                        <label for="media" class="font-weight-bold">الملف</label>
                        <input type="file" name="media" id="media" class="form-control-file">
                        <small class="form-text text-muted">الصور والفيديوهات (حد أقصى 10MB)</small>
                    </div>

                    <div class="form-group" id="youtube-group" style="display: none;">
                        <label for="youtube_url" class="font-weight-bold">رابط يوتيوب</label>
                        <input type="url" name="youtube_url" id="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                    </div>

                    <div class="form-group" id="text-content-group" style="display: none;">
                        <label for="text_content" class="font-weight-bold">النص</label>
                        <textarea name="content[text]" id="text_content" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // إظهار/إخفاء الحقول حسب نوع العنصر
    document.getElementById('item_type').addEventListener('change', function() {
        const type = this.value;
        document.getElementById('media-group').style.display = (type === 'image' || type === 'video') ? 'block' : 'none';
        document.getElementById('youtube-group').style.display = (type === 'video') ? 'block' : 'none';
        document.getElementById('text-content-group').style.display = (type === 'text') ? 'block' : 'none';
    });

    // Drag & Drop للعناصر
    const itemsList = document.getElementById('items-list');
    if (itemsList) {
        new Sortable(itemsList, {
            handle: '.fa-grip-vertical',
            animation: 300,
            onEnd: function() {
                const items = document.querySelectorAll('#items-list [data-id]');
                const orders = Array.from(items).map(item => item.dataset.id);
                
                fetch('{{ route("dashboard.home-section-items.reorder", $homeSection) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ orders: orders })
                });
            }
        });
    }

    function editItem(id) {
        // يمكن إضافة منطق التعديل هنا
        alert('ميزة التعديل قيد التطوير');
    }
</script>
@endsection

