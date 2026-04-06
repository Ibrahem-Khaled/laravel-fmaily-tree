@extends('layouts.app')

@section('title', 'إضافة رابط مهم')

@section('content')
<div class="container-fluid">
    <a href="{{ route('dashboard.important-links.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="fas fa-arrow-right ml-1"></i> العودة للقائمة
    </a>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-primary mr-2"></i>إضافة رابط جديد
        </h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0 pr-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('dashboard.important-links.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('dashboard.important-links._form', ['importantLink' => null])
                <hr class="my-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>حفظ
                </button>
                <a href="{{ route('dashboard.important-links.index') }}" class="btn btn-outline-secondary mr-2">إلغاء</a>
            </form>
        </div>
    </div>
</div>

@include('dashboard.important-links._media_row_template')
@include('dashboard.important-links._form_scripts')
@endsection
