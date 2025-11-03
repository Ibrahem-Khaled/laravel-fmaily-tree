@extends('layouts.app')

@section('title', 'إدارة محتوى الموقع')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إدارة محتوى الموقع</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        {{-- نبذة العائلة --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>نبذة عن عائلة السريع
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.site-content.update-family-brief') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="family_brief_content">المحتوى</label>
                            <textarea name="content" id="family_brief_content" rows="10" 
                                class="form-control @error('content') is-invalid @enderror" 
                                required>{{ old('content', $familyBrief->content ?? '') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="family_brief_active" 
                                    class="form-check-input" value="1" 
                                    {{ ($familyBrief->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="family_brief_active">
                                    تفعيل المحتوى
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>حفظ التغييرات
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ما الجديد --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bullhorn mr-2"></i>ما الجديد
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.site-content.update-whats-new') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="whats_new_content">المحتوى</label>
                            <textarea name="content" id="whats_new_content" rows="10" 
                                class="form-control @error('content') is-invalid @enderror" 
                                required>{{ old('content', $whatsNew->content ?? '') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="whats_new_active" 
                                    class="form-check-input" value="1" 
                                    {{ ($whatsNew->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="whats_new_active">
                                    تفعيل المحتوى
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>حفظ التغييرات
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

