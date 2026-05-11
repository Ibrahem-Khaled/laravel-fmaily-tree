@extends('layouts.app')

@section('title', 'إضافة مجلس جديد')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-plus-circle text-primary mr-2"></i>إضافة مجلس جديد
            </h1>
            <p class="text-muted mb-0">أضف مجلساً جديداً سيظهر للعائلة في الصفحة الرئيسية</p>
        </div>
        <a href="{{ route('dashboard.councils.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
        </a>
    </div>

    <x-alerts />

    @include('dashboard.councils.partials._form', [
        'action'      => route('dashboard.councils.store'),
        'submitLabel' => 'حفظ المجلس',
        'submitIcon'  => 'fas fa-save',
    ])
</div>
@endsection
