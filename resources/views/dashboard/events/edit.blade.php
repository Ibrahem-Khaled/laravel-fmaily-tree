@extends('layouts.app')

@section('title', 'تعديل المناسبة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-edit text-info mr-2"></i>تعديل المناسبة
            </h1>
            <p class="text-muted mb-0">{{ $event->title }}</p>
        </div>
        <a href="{{ route('dashboard.events.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
        </a>
    </div>

    <x-alerts />

    @include('dashboard.events.partials._form', [
        'action'      => route('dashboard.events.update', $event),
        'submitLabel' => 'حفظ التغييرات',
        'submitIcon'  => 'fas fa-save',
        'event'       => $event,
    ])
</div>
@endsection
