@extends('layouts.app')

@section('title', 'تفاصيل المسابقة')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-quran text-primary mr-2"></i>{{ $quranCompetition->title }}
            </h1>
            <p class="text-muted mb-0">إدارة الفائزين والوسائط للمسابقة</p>
        </div>
        <div>
            <a href="{{ route('dashboard.quran-competitions.edit', $quranCompetition) }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-edit mr-2"></i>تعديل المسابقة
            </a>
            <a href="{{ route('dashboard.quran-competitions.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Competition Info -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>معلومات المسابقة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>السنة الهجرية:</strong> {{ $quranCompetition->hijri_year }} هـ
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>الحالة:</strong>
                            @if($quranCompetition->is_active)
                                <span class="badge badge-success">نشط</span>
                            @else
                                <span class="badge badge-secondary">معطل</span>
                            @endif
                        </div>
                        @if($quranCompetition->start_date)
                            <div class="col-md-6 mb-3">
                                <strong>تاريخ البداية:</strong> {{ $quranCompetition->start_date->format('Y-m-d') }}
                            </div>
                        @endif
                        @if($quranCompetition->end_date)
                            <div class="col-md-6 mb-3">
                                <strong>تاريخ النهاية:</strong> {{ $quranCompetition->end_date->format('Y-m-d') }}
                            </div>
                        @endif
                        @if($quranCompetition->description)
                            <div class="col-12 mb-3">
                                <strong>الوصف:</strong>
                                <p class="mt-2">{{ $quranCompetition->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if($quranCompetition->cover_image)
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 font-weight-bold text-primary">
                            <i class="fas fa-image mr-2"></i>صورة الغلاف
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $quranCompetition->cover_image_url }}" alt="Cover" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Winners Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-trophy mr-2"></i>الفائزون ({{ $quranCompetition->winners->count() }})
            </h6>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addWinnerModal">
                <i class="fas fa-plus mr-2"></i>إضافة فائز
            </button>
        </div>
        <div class="card-body">
            @if($quranCompetition->winners->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>المركز</th>
                                <th>الاسم</th>
                                <th>الفئة</th>
                                <th>ملاحظات</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quranCompetition->winners->sortBy('position') as $winner)
                                <tr>
                                    <td>
                                        <span class="badge badge-warning">{{ $winner->position_name }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $winner->person->avatar }}" alt="{{ $winner->person->full_name }}" class="rounded-circle mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            <strong>{{ $winner->person->full_name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $winner->category ?? '-' }}</td>
                                    <td>{{ $winner->notes ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('dashboard.quran-competitions.remove-winner', $winner) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الفائز؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا يوجد فائزون حالياً</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Media Section -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-images mr-2"></i>الوسائط ({{ $quranCompetition->media->count() }})
            </h6>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addMediaModal">
                <i class="fas fa-plus mr-2"></i>إضافة وسائط
            </button>
        </div>
        <div class="card-body">
            @if($quranCompetition->media->count() > 0)
                <div class="row">
                    @foreach($quranCompetition->media->sortBy('sort_order') as $media)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                @if($media->media_type === 'youtube')
                                    <div class="position-relative">
                                        <img src="{{ $media->youtube_thumbnail }}" alt="{{ $media->caption }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="fab fa-youtube fa-3x text-danger"></i>
                                        </div>
                                    </div>
                                @elseif($media->media_type === 'video')
                                    <video class="card-img-top" style="height: 200px; object-fit: cover;" controls>
                                        <source src="{{ $media->file_url }}" type="video/mp4">
                                    </video>
                                @else
                                    <img src="{{ $media->file_url }}" alt="{{ $media->caption }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <p class="card-text">{{ $media->caption ?? 'بدون وصف' }}</p>
                                    <small class="text-muted">نوع: {{ $media->media_type }}</small>
                                    <form action="{{ route('dashboard.quran-competitions.remove-media', $media) }}" method="POST" class="mt-2" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوسائط؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-block">
                                            <i class="fas fa-trash mr-2"></i>حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد وسائط حالياً</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Winner Modal -->
<div class="modal fade" id="addWinnerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة فائز</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.quran-competitions.add-winner', $quranCompetition) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="person_id">الشخص <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="person_id" name="person_id" required>
                            <option value="">اختر الشخص</option>
                            @foreach($persons as $person)
                                <option value="{{ $person->id }}">{{ $person->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="position">المركز <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="position" name="position" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="category">الفئة</label>
                        <input type="text" class="form-control" id="category" name="category" placeholder="مثال: حفظ كامل، حفظ جزئي">
                    </div>
                    <div class="form-group">
                        <label for="notes">ملاحظات</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
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

<!-- Add Media Modal -->
<div class="modal fade" id="addMediaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة وسائط</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.quran-competitions.add-media', $quranCompetition) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="media_type">نوع الوسائط <span class="text-danger">*</span></label>
                        <select class="form-control" id="media_type" name="media_type" required onchange="toggleMediaInputs()">
                            <option value="image">صورة</option>
                            <option value="video">فيديو</option>
                            <option value="youtube">يوتيوب</option>
                        </select>
                    </div>
                    <div class="form-group" id="fileInputGroup">
                        <label for="file">الملف <span class="text-danger">*</span></label>
                        <input type="file" class="form-control-file" id="file" name="file" accept="image/*,video/*">
                        <small class="form-text text-muted">الحد الأقصى: 10 ميجابايت</small>
                    </div>
                    <div class="form-group" id="youtubeInputGroup" style="display: none;">
                        <label for="youtube_url">رابط يوتيوب <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="youtube_url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <div class="form-group">
                        <label for="caption">الوصف</label>
                        <input type="text" class="form-control" id="caption" name="caption">
                    </div>
                    <div class="form-group">
                        <label for="sort_order">ترتيب العرض</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="0" min="0">
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

<script>
    function toggleMediaInputs() {
        const mediaType = document.getElementById('media_type').value;
        const fileInputGroup = document.getElementById('fileInputGroup');
        const youtubeInputGroup = document.getElementById('youtubeInputGroup');
        
        if (mediaType === 'youtube') {
            fileInputGroup.style.display = 'none';
            youtubeInputGroup.style.display = 'block';
            document.getElementById('file').removeAttribute('required');
            document.getElementById('youtube_url').setAttribute('required', 'required');
        } else {
            fileInputGroup.style.display = 'block';
            youtubeInputGroup.style.display = 'none';
            document.getElementById('file').setAttribute('required', 'required');
            document.getElementById('youtube_url').removeAttribute('required');
        }
    }

    // Initialize Select2
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            language: 'ar',
            dir: 'rtl'
        });
    });
</script>
@endsection

