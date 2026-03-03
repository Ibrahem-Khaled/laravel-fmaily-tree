@extends('layouts.app')

@section('title', 'تعديل بيانات الراعي')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-edit text-primary mr-2"></i>تعديل الراعي: {{ $sponsor->name }}
            </h1>
            <p class="text-muted mb-0">تعديل بيانات راعي المسابقات</p>
        </div>
        <a href="{{ route('dashboard.sponsors.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('dashboard.sponsors.update', $sponsor) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name">اسم الراعي <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $sponsor->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">وصف الراعي (اختياري)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $sponsor->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light p-3">
                            <div class="form-group mb-0">
                                <label for="image" class="d-block font-weight-bold mb-3">شعار الراعي (تغيير الصورة اختياري)</label>
                                <div class="text-center mb-3">
                                    <div id="imagePreview" class="w-100 bg-white border rounded" style="min-height: 200px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        @if($sponsor->image)
                                            <img src="{{ asset('storage/' . $sponsor->image) }}" alt="معاينة" class="img-fluid" style="max-height: 250px; width: auto; object-fit: contain;">
                                        @else
                                            <div id="imagePlaceholder" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted p-4">
                                                <i class="fas fa-image fa-3x mb-2 text-light"></i>
                                                <span class="small">لا توجد صورة</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="custom-file text-left">
                                    <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                    <label class="custom-file-label" for="image" data-browse="استعراض">اختر صورة جديدة...</label>
                                </div>
                                @error('image')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    الصيغ المدعومة: JPG, PNG, WEBP (الحد الأقصى: 2 ميجابايت). اترك الحقل فارغاً للاحتفاظ باللصورة الحالية.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4 border-top pt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save mr-2"></i>حفظ التعديلات
                    </button>
                    <a href="{{ route('dashboard.sponsors.index') }}" class="btn btn-light border px-4">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const img = preview.querySelector('img');
        const placeholder = document.getElementById('imagePlaceholder');
        const label = input.nextElementSibling;
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                if (img) {
                    img.src = e.target.result;
                } else {
                    if (placeholder) placeholder.remove();
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="معاينة" class="img-fluid" style="max-height: 250px; width: auto; object-fit: contain;">';
                }
                
                // Update file input label
                const fileName = input.files[0].name;
                label.innerText = fileName;
                label.classList.add('selected');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
