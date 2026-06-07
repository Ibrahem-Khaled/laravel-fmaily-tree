@extends('layouts.app')

@section('title', 'إضافة رابط مهم')

@section('content')
@include('dashboard.important-links._styles')


<div class="container-fluid dashboard-container">
    <div class="mb-3 text-right">
        <a href="{{ route('dashboard.important-links.index') }}" class="btn btn-premium-secondary btn-sm py-2 px-3">
            <i class="fas fa-arrow-right ml-1"></i> العودة للقائمة
        </a>
    </div>

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 text-right">
        <h1 class="page-title w-100 mb-0">
            <i class="fas fa-plus-circle text-emerald-500 ml-2"></i>إضافة رابط جديد
        </h1>
    </div>

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

    <div class="card card-premium mb-4">
        <div class="card-body p-4 text-right" dir="rtl">
            <form action="{{ route('dashboard.important-links.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('dashboard.important-links._form', ['importantLink' => null])
                <hr class="my-4" style="border-color: #f1f5f9;">
                <div class="d-flex justify-content-start gap-2 flex-wrap">
                    <button type="submit" class="btn btn-premium-primary">
                        <i class="fas fa-save ml-2"></i>حفظ وإضافة الرابط
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
