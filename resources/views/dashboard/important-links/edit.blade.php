@extends('layouts.app')

@section('title', 'تعديل رابط: ' . $importantLink->title)

@section('content')
@include('dashboard.important-links._styles')


<div class="container-fluid dashboard-container">
    <div class="mb-3 text-right">
        <a href="{{ route('dashboard.important-links.index') }}" class="btn btn-premium-secondary btn-sm py-2 px-3">
            <i class="fas fa-arrow-right ml-1"></i> العودة للقائمة
        </a>
    </div>

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 text-right" dir="rtl">
        <h1 class="page-title mb-2 mb-sm-0">
            <i class="fas fa-edit text-emerald-500 ml-2"></i>تعديل الرابط: <span class="text-slate-600">{{ $importantLink->title }}</span>
        </h1>
        @if($importantLink->status === 'pending')
            <span class="badge badge-warning badge-pill px-3 py-2 font-weight-bold" style="background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a;">بانتظار الموافقة</span>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-xl mb-4" role="alert" style="background-color: #ecfdf5; color: #065f46;" dir="rtl">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-lg ml-3"></i>
                <span class="font-weight-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="close text-success" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0 rounded-xl mb-4 text-right" style="background-color: #fef2f2; color: #991b1b;" dir="rtl">
            <h6 class="font-weight-bold mb-2"><i class="fas fa-exclamation-circle ml-2"></i>يرجى تصحيح الأخطاء التالية:</h6>
            <ul class="mb-0 pr-4">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $hasPreview = $importantLink->media->isNotEmpty() || $importantLink->image;
    @endphp

    {{-- Media Previews --}}
    @if($hasPreview)
        <div class="card card-premium mb-4 border-right-primary text-right" style="border-right: 4px solid #10b981 !important;" dir="rtl">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 font-weight-bold text-slate-800">
                    <i class="fas fa-photo-video ml-2 text-emerald-500"></i>معاينة الوسائط والملحقات
                </h5>
                @if($importantLink->status === 'pending')
                    <p class="small text-muted mb-0 mt-2">راجع الملحقات والصور المقترحة بعناية قبل اتخاذ قرار الاعتماد.</p>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($importantLink->media as $m)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="preview-box p-2">
                                @if($m->kind === 'video')
                                    <div class="rounded-lg overflow-hidden bg-dark border">
                                        <video controls class="w-100" style="max-height: 240px;" src="{{ $m->file_url }}" preload="metadata"></video>
                                    </div>
                                    <span class="badge badge-dark mt-2 font-weight-bold px-2 py-1"><i class="fas fa-video ml-1"></i> فيديو</span>
                                @else
                                    <a href="{{ $m->file_url }}" target="_blank" rel="noopener">
                                        <img src="{{ $m->file_url }}" alt="" class="img-fluid rounded border shadow-sm w-100" style="max-height: 240px; object-fit: contain; background: #f8f9fc;">
                                    </a>
                                    <span class="badge badge-info mt-2 font-weight-bold px-2 py-1"><i class="fas fa-image ml-1"></i> صورة</span>
                                @endif
                                @if($m->title)
                                    <p class="font-weight-bold mt-2 mb-1 text-slate-800" style="font-size: 0.95rem;">{{ $m->title }}</p>
                                @endif
                                @if($m->description)
                                    <p class="text-slate-500 small mb-0">{{ $m->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @if($importantLink->image)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="preview-box p-3">
                                <p class="small text-muted font-weight-bold mb-2">صورة الغلاف (الحقل القديم)</p>
                                <a href="{{ asset('storage/' . $importantLink->image) }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('storage/' . $importantLink->image) }}" alt="" class="img-fluid rounded border shadow-sm w-100" style="max-height: 240px; object-fit: contain; background: #f8f9fc;">
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Quick actions for pending --}}
    @if($importantLink->status === 'pending')
        <div class="card card-premium mb-4 text-right" dir="rtl">
            <div class="card-body d-flex flex-wrap align-items-center gap-3">
                <span class="font-weight-bold text-slate-700 ml-3"><i class="fas fa-magic ml-1 text-emerald-500"></i>إجراء سريع للاقتراح:</span>
                <form action="{{ route('dashboard.important-links.approve', $importantLink) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-premium-success"><i class="fas fa-check ml-1"></i>اعتماد ونشر الرابط</button>
                </form>
                <form action="{{ route('dashboard.important-links.reject', $importantLink) }}" method="POST" class="d-inline mr-2" onsubmit="return confirm('هل أنت متأكد من رفض وحذف هذا الاقتراح؟');">
                    @csrf
                    <button type="submit" class="btn btn-premium-danger"><i class="fas fa-times ml-1"></i>رفض وحذف نهائياً</button>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit Form --}}
    <div class="card card-premium mb-4">
        <div class="card-header bg-light py-3 text-right">
            <h6 class="mb-0 font-weight-bold text-slate-800"><i class="fas fa-edit ml-2 text-emerald-500"></i>بيانات الرابط وتعديلها</h6>
        </div>
        <div class="card-body p-4 text-right" dir="rtl">
            <form action="{{ route('dashboard.important-links.update', $importantLink) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('dashboard.important-links._form', ['importantLink' => $importantLink])
                <hr class="my-4" style="border-color: #f1f5f9;">
                <div class="d-flex justify-content-start gap-2 flex-wrap">
                    <button type="submit" class="btn btn-premium-primary">
                        <i class="fas fa-save ml-2"></i>حفظ التغييرات
                    </button>
                    <a href="{{ route('dashboard.important-links.index') }}" class="btn btn-premium-secondary mr-2">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>

@include('dashboard.important-links._media_row_template')
@include('dashboard.important-links._form_scripts')
@endsection
