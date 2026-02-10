@extends('layouts.app')

@section('title', 'تعديل سجل المشي')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-edit text-primary mr-2"></i>تعديل سجل المشي
            </h1>
        </div>
        <a href="{{ route('dashboard.walking.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right mr-2"></i>رجوع
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('dashboard.walking.update', $walking) }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>المستخدم <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $walking->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>التاريخ <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                                   value="{{ old('date', $walking->date->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>عدد الخطوات <span class="text-danger">*</span></label>
                            <input type="number" name="steps" class="form-control @error('steps') is-invalid @enderror"
                                   value="{{ old('steps', $walking->steps) }}" min="0" required>
                            @error('steps')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>المسافة (كم)</label>
                            <input type="number" name="distance_km" class="form-control @error('distance_km') is-invalid @enderror"
                                   value="{{ old('distance_km', $walking->distance_km) }}" min="0" step="0.01">
                            @error('distance_km')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>المدة (دقيقة)</label>
                            <input type="number" name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror"
                                   value="{{ old('duration_minutes', $walking->duration_minutes) }}" min="0">
                            @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $walking->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>حفظ التعديلات
                    </button>
                    <a href="{{ route('dashboard.walking.index') }}" class="btn btn-secondary">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
