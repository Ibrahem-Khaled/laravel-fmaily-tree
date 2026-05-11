@extends('layouts.app')

@section('title', 'تعديل المجلس')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-edit text-info mr-2"></i>تعديل المجلس
            </h1>
            <p class="text-muted mb-0">{{ $council->name }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('dashboard.councils.manage', $council) }}" class="btn btn-outline-primary shadow-sm">
                <i class="fas fa-images mr-2"></i>إدارة الصور
            </a>
            <a href="{{ route('dashboard.councils.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
            </a>
        </div>
    </div>

    <x-alerts />

    @include('dashboard.councils.partials._form', [
        'action'      => route('dashboard.councils.update', $council),
        'submitLabel' => 'حفظ التغييرات',
        'submitIcon'  => 'fas fa-save',
        'council'     => $council,
    ])
</div>
@endsection
