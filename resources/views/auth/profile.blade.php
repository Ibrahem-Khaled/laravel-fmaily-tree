@extends('layouts.app')

@section('content')
<div class="container-fluid" dir="rtl">
    <!-- عنوان الصفحة -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-key mr-2"></i>تغيير كلمة المرور
        </h1>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-right mr-1"></i>العودة للوحة التحكم
        </a>
    </div>

    @include('components.alerts')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- بطاقة تغيير كلمة المرور -->
            <div class="card shadow mb-4" id="change-password">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-lock mr-2"></i>تغيير كلمة المرور
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.update.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- كلمة المرور الحالية -->
                        <div class="form-group mb-4">
                            <label for="current_password" class="font-weight-bold text-gray-700">
                                <i class="fas fa-key mr-1 text-primary"></i>كلمة المرور الحالية
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control @error('current_password') is-invalid @enderror" 
                                    id="current_password" 
                                    name="current_password" 
                                    placeholder="أدخل كلمة المرور الحالية"
                                    required
                                    autofocus
                                >
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye" id="current_password_icon"></i>
                                    </button>
                                </div>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- كلمة المرور الجديدة -->
                        <div class="form-group mb-4">
                            <label for="new_password" class="font-weight-bold text-gray-700">
                                <i class="fas fa-lock mr-1 text-success"></i>كلمة المرور الجديدة
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control @error('new_password') is-invalid @enderror" 
                                    id="new_password" 
                                    name="new_password" 
                                    placeholder="أدخل كلمة المرور الجديدة (8 أحرف على الأقل)"
                                    required
                                    minlength="8"
                                >
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                        <i class="fas fa-eye" id="new_password_icon"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                يجب أن تحتوي على 8 أحرف على الأقل وتتضمن أحرف وأرقام
                            </small>
                            @error('new_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- تأكيد كلمة المرور الجديدة -->
                        <div class="form-group mb-4">
                            <label for="new_password_confirmation" class="font-weight-bold text-gray-700">
                                <i class="fas fa-check-circle mr-1 text-info"></i>تأكيد كلمة المرور الجديدة
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                    id="new_password_confirmation" 
                                    name="new_password_confirmation" 
                                    placeholder="أعد إدخال كلمة المرور الجديدة"
                                    required
                                    minlength="8"
                                >
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                        <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                                    </button>
                                </div>
                            </div>
                            @error('new_password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- مؤشر قوة كلمة المرور -->
                        <div class="mb-4">
                            <label class="small text-muted">قوة كلمة المرور:</label>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small id="passwordStrengthText" class="text-muted"></small>
                        </div>

                        <!-- أزرار الإجراء -->
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save mr-2"></i>حفظ التغييرات
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times mr-2"></i>إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle mr-2"></i>معلومات الحساب
                    </h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">
                            <i class="fas fa-user mr-2 text-primary"></i>الاسم:
                        </dt>
                        <dd class="col-sm-8">
                            <strong>{{ Auth::user()->name }}</strong>
                        </dd>

                        <dt class="col-sm-4">
                            <i class="fas fa-envelope mr-2 text-info"></i>البريد الإلكتروني:
                        </dt>
                        <dd class="col-sm-8">
                            {{ Auth::user()->email }}
                        </dd>

                        @if(Auth::user()->phone)
                        <dt class="col-sm-4">
                            <i class="fas fa-phone mr-2 text-success"></i>رقم الهاتف:
                        </dt>
                        <dd class="col-sm-8">
                            {{ Auth::user()->phone }}
                        </dd>
                        @endif

                        <dt class="col-sm-4">
                            <i class="fas fa-calendar mr-2 text-warning"></i>تاريخ التسجيل:
                        </dt>
                        <dd class="col-sm-8">
                            {{ Auth::user()->created_at->format('Y-m-d') }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // تبديل إظهار/إخفاء كلمة المرور
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '_icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // التحقق من قوة كلمة المرور
    document.getElementById('new_password').addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('passwordStrengthText');
        
        let strength = 0;
        let strengthLabel = '';
        let strengthColor = '';
        
        // فحص طول كلمة المرور
        if (password.length >= 8) strength += 20;
        if (password.length >= 12) strength += 10;
        
        // فحص الأحرف الكبيرة
        if (/[A-Z]/.test(password)) strength += 20;
        
        // فحص الأحرف الصغيرة
        if (/[a-z]/.test(password)) strength += 20;
        
        // فحص الأرقام
        if (/[0-9]/.test(password)) strength += 15;
        
        // فحص الأحرف الخاصة
        if (/[^A-Za-z0-9]/.test(password)) strength += 15;
        
        // تحديد التصنيف والألوان
        if (strength < 30) {
            strengthLabel = 'ضعيفة جداً';
            strengthColor = 'danger';
        } else if (strength < 50) {
            strengthLabel = 'ضعيفة';
            strengthColor = 'warning';
        } else if (strength < 70) {
            strengthLabel = 'متوسطة';
            strengthColor = 'info';
        } else if (strength < 85) {
            strengthLabel = 'قوية';
            strengthColor = 'success';
        } else {
            strengthLabel = 'قوية جداً';
            strengthColor = 'success';
        }
        
        // تحديث شريط التقدم
        strengthBar.style.width = strength + '%';
        strengthBar.className = 'progress-bar bg-' + strengthColor;
        strengthText.textContent = strengthLabel;
        
        // إخفاء النص إذا كانت كلمة المرور فارغة
        if (password.length === 0) {
            strengthText.textContent = '';
            strengthBar.style.width = '0%';
        }
    });

    // التحقق من تطابق كلمة المرور
    document.getElementById('new_password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('new_password').value;
        const confirmation = this.value;
        
        if (confirmation.length > 0) {
            if (password !== confirmation) {
                this.setCustomValidity('كلمة المرور غير متطابقة');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid', 'is-valid');
        }
    });
</script>
@endpush

@push('styles')
<style>
    .card {
        border-radius: 0.5rem;
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .form-group label {
        margin-bottom: 0.5rem;
    }
    
    .input-group-append button {
        border-left: 0;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    #passwordStrength {
        transition: width 0.3s ease, background-color 0.3s ease;
    }
    
    .is-valid {
        border-color: #28a745;
    }
    
    .is-invalid {
        border-color: #dc3545;
    }
</style>
@endpush
@endsection
