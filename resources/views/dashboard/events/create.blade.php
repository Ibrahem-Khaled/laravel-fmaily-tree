@extends('layouts.app')

@section('title', 'إضافة مناسبة جديدة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-plus-circle text-primary mr-2"></i>إضافة مناسبة جديدة
            </h1>
            <p class="text-muted mb-0">أضف مناسبة جديدة ستظهر للعائلة في الصفحة الرئيسية</p>
        </div>
        <a href="{{ route('dashboard.events.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
        </a>
    </div>

    <x-alerts />

    @include('dashboard.events.partials._form', [
        'action'      => route('dashboard.events.store'),
        'submitLabel' => 'حفظ المناسبة',
        'submitIcon'  => 'fas fa-save',
    ])
</div>
@endsection
