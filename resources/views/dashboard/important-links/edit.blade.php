@extends('layouts.app')

@section('title', 'تعديل رابط: ' . $importantLink->title)

@section('content')
<div class="container-fluid">
    <a href="{{ route('dashboard.important-links.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="fas fa-arrow-right ml-1"></i> العودة للقائمة
    </a>

    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h3 mb-2 mb-sm-0 text-gray-800">
            <i class="fas fa-edit text-info mr-2"></i>تعديل: {{ $importantLink->title }}
        </h1>
        @if($importantLink->status === 'pending')
            <span class="badge badge-warning badge-pill px-3 py-2">بانتظار الموافقة</span>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0 pr-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $hasPreview = $importantLink->media->isNotEmpty() || $importantLink->image;
    @endphp

    @if($hasPreview)
        <div class="card shadow-sm border-0 mb-4 border-right-primary" style="border-right: 4px solid #4e73df !important;">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 font-weight-bold text-primary">
                    <i class="fas fa-photo-video mr-2"></i>معاينة الوسائط
                </h5>
                @if($importantLink->status === 'pending')
                    <p class="small text-muted mb-0 mt-2">راجع الصور والفيديوهات قبل الاعتماد أو الرفض.</p>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($importantLink->media as $m)
                        <div class="col-md-6 col-lg-4 mb-4">
                            @if($m->kind === 'video')
                                <div class="rounded overflow-hidden bg-dark border">
                                    <video controls class="w-100" style="max-height: 320px;" src="{{ $m->file_url }}" preload="metadata"></video>
                                </div>
                                <span class="badge badge-dark mt-2">فيديو</span>
                            @else
                                <a href="{{ $m->file_url }}" target="_blank" rel="noopener">
                                    <img src="{{ $m->file_url }}" alt="" class="img-fluid rounded border shadow-sm w-100" style="max-height: 320px; object-fit: contain; background: #f8f9fc;">
                                </a>
                            @endif
                            @if($m->title)
                                <p class="font-weight-bold mt-2 mb-1">{{ $m->title }}</p>
                            @endif
                            @if($m->description)
                                <p class="text-muted small mb-0">{{ $m->description }}</p>
                            @endif
                        </div>
                    @endforeach
                    @if($importantLink->image)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <p class="small text-muted mb-2">صورة غلاف إضافية (حقل منفصل)</p>
                            <a href="{{ asset('storage/' . $importantLink->image) }}" target="_blank" rel="noopener">
                                <img src="{{ asset('storage/' . $importantLink->image) }}" alt="" class="img-fluid rounded border shadow-sm w-100" style="max-height: 320px; object-fit: contain; background: #f8f9fc;">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($importantLink->status === 'pending')
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex flex-wrap align-items-center gap-2">
                <span class="font-weight-bold text-gray-700 mr-2">إجراء سريع:</span>
                <form action="{{ route('dashboard.important-links.approve', $importantLink) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success"><i class="fas fa-check mr-1"></i>اعتماد الاقتراح</button>
                </form>
                <form action="{{ route('dashboard.important-links.reject', $importantLink) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رفض وحذف هذا الاقتراح؟');">
                    @csrf
                    <button type="submit" class="btn btn-danger"><i class="fas fa-times mr-1"></i>رفض وحذف</button>
                </form>
            </div>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h6 class="mb-0 font-weight-bold">بيانات الرابط والتعديل</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('dashboard.important-links.update', $importantLink) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('dashboard.important-links._form', ['importantLink' => $importantLink])
                <hr class="my-4">
                <button type="submit" class="btn btn-info">
                    <i class="fas fa-save mr-2"></i>حفظ التغييرات
                </button>
                <a href="{{ route('dashboard.important-links.index') }}" class="btn btn-outline-secondary mr-2">إلغاء</a>
            </form>
        </div>
    </div>
</div>

@include('dashboard.important-links._media_row_template')
@include('dashboard.important-links._form_scripts')
@endsection
