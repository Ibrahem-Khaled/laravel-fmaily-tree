@extends('layouts.app')

@section('title', 'إدارة الرضاعة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @livewire('breastfeeding-management')
        </div>
    </div>
</div>
@endsection
