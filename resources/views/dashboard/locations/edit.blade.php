@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">تعديل المكان</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('locations.index') }}">الأماكن</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">تعديل</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">معلومات المكان</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('locations.update', $location) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                            @if(session('similar_locations'))
                                <hr>
                                <strong>الأماكن المشابهة:</strong>
                                <ul class="mb-0">
                                    @foreach(session('similar_locations') as $similar)
                                        <li>{{ $similar['location']->name }} (نسبة التشابه: {{ round($similar['similarity'] * 100) }}%)</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="name">اسم المكان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $location->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">الدولة</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" id="country"
                                    name="country" value="{{ old('country', $location->country) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">المدينة</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                                    name="city" value="{{ old('city', $location->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">الوصف</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="3">{{ old('description', $location->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <strong>عدد الأشخاص المرتبطين:</strong> {{ $location->persons_count }}
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ التغييرات
                        </button>
                        <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

