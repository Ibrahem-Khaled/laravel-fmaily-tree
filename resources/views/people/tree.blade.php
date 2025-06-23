@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">شجرة العائلة</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('people.index') }}">الأشخاص</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">شجرة العائلة</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">شجرة العائلة الكاملة</h6>
                        <a href="{{ route('people.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> عرض القائمة
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="family-tree">
                            <ul>
                                @foreach ($tree as $person)
                                    @include('people.partials.tree-node', ['person' => $person])
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .family-tree {
            overflow-x: auto;
            padding: 20px 0;
        }

        .family-tree ul {
            padding-top: 20px;
            position: relative;
            transition: all 0.5s;
        }

        .family-tree li {
            float: left;
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 5px 0 5px;
            transition: all 0.5s;
        }

        .family-tree li::before,
        .family-tree li::after {
            content: '';
            position: absolute;
            top: 0;
            right: 50%;
            border-top: 1px solid #ccc;
            width: 50%;
            height: 20px;
        }

        .family-tree li::after {
            right: auto;
            left: 50%;
            border-left: 1px solid #ccc;
        }

        .family-tree li:only-child::after,
        .family-tree li:only-child::before {
            display: none;
        }

        .family-tree li:only-child {
            padding-top: 0;
        }

        .family-tree li:first-child::before,
        .family-tree li:last-child::after {
            border: 0 none;
        }

        .family-tree li:last-child::before {
            border-right: 1px solid #ccc;
            border-radius: 0 5px 0 0;
        }

        .family-tree li:first-child::after {
            border-radius: 5px 0 0 0;
        }

        .family-tree ul ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 1px solid #ccc;
            width: 0;
            height: 20px;
        }

        .family-tree li div {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            background: #fff;
            display: inline-block;
            transition: all 0.5s;
            min-width: 150px;
            max-width: 200px;
        }

        .family-tree li div:hover {
            background: #f5f5f5;
            cursor: pointer;
        }

        .family-tree li div img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 5px;
        }

        .family-tree .male div {
            border-color: #4e73df;
            background-color: #f8f9fc;
        }

        .family-tree .female div {
            border-color: #e83e8c;
            background-color: #fff5f9;
        }
    </style>
@endpush
