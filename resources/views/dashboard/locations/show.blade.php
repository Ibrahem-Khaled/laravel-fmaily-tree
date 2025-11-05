@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">عرض المكان</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('locations.index') }}">الأماكن</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">عرض</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">معلومات المكان</h6>
                <div>
                    <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <a href="{{ route('locations.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-right"></i> رجوع
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>الاسم</h5>
                        <p class="text-muted">{{ $location->display_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>عدد الأشخاص</h5>
                        <p class="text-muted">
                            <span class="badge badge-info" style="font-size: 1rem;">
                                <i class="fas fa-users"></i> {{ $location->persons_count }}
                            </span>
                        </p>
                    </div>
                </div>

                @if($location->country || $location->city)
                    <div class="row">
                        @if($location->country)
                            <div class="col-md-6">
                                <h5>الدولة</h5>
                                <p class="text-muted">{{ $location->country }}</p>
                            </div>
                        @endif
                        @if($location->city)
                            <div class="col-md-6">
                                <h5>المدينة</h5>
                                <p class="text-muted">{{ $location->city }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                @if($location->description)
                    <div class="row">
                        <div class="col-12">
                            <h5>الوصف</h5>
                            <p class="text-muted">{{ $location->description }}</p>
                        </div>
                    </div>
                @endif

                <hr>

                @if($location->persons->count() > 0)
                    <h5>الأشخاص المرتبطين بهذا المكان</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>الجنس</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($location->persons as $person)
                                    <tr>
                                        <td>
                                            <a href="{{ route('people.show', $person) }}">{{ $person->full_name }}</a>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $person->gender == 'male' ? 'primary' : 'pink' }}">
                                                {{ $person->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('people.show', $person) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        لا يوجد أشخاص مرتبطين بهذا المكان حالياً.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

