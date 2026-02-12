@extends('layouts.app')

@section('title', 'إضافة مسابقة جديدة')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-plus-circle text-primary mr-2"></i>إضافة مسابقة جديدة
            </h1>
            <p class="text-muted mb-0">قم بإضافة مسابقة جديدة</p>
        </div>
        <a href="{{ route('dashboard.competitions.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('dashboard.competitions.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title">عنوان المسابقة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="game_type">نوع اللعبة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('game_type') is-invalid @enderror" id="game_type" name="game_type" value="{{ old('game_type') }}" placeholder="مثال: كرة قدم، كرة سلة، إلخ" required>
                            @error('game_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="team_size">عدد أعضاء الفريق <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('team_size') is-invalid @enderror" id="team_size" name="team_size" value="{{ old('team_size', 1) }}" min="1" step="1" required>
                            <small class="form-text text-muted">الحد الأدنى: 1 عضو (يمكن إنشاء مسابقة فردية أو جماعية)</small>
                            @error('team_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">وصف المسابقة</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="program_id">ربط ببرنامج (اختياري)</label>
                            <select class="form-control @error('program_id') is-invalid @enderror" id="program_id" name="program_id">
                                <option value="">-- اختر برنامج --</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                        {{ $program->program_title ?? $program->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">يمكنك ربط هذه المسابقة ببرنامج معين لعرض المسجلين في صفحة البرنامج</small>
                            @error('program_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">تاريخ بداية التسجيل</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">تاريخ نهاية التسجيل</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    تفعيل المسابقة
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>حفظ المسابقة
                    </button>
                    <a href="{{ route('dashboard.competitions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const teamSizeInput = document.getElementById('team_size');
    
    if (teamSizeInput) {
        // التحقق عند تغيير القيمة
        teamSizeInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (value < 1) {
                this.setCustomValidity('عدد أعضاء الفريق يجب أن يكون على الأقل 1');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // التحقق عند الإرسال
        const form = teamSizeInput.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const value = parseInt(teamSizeInput.value);
                if (value < 1) {
                    e.preventDefault();
                    alert('عدد أعضاء الفريق يجب أن يكون على الأقل 1');
                    teamSizeInput.focus();
                    return false;
                }
            });
        }
    }
});
</script>
@endsection
