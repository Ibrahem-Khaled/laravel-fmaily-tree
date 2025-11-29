@extends('layouts.app')

@section('title', 'إعدادات حماية الموقع')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shield-alt mr-2"></i>إعدادات حماية الموقع
        </h1>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">لوحة التحكم</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">إعدادات حماية الموقع</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog mr-2"></i>إعدادات حماية الموقع
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.site-password-settings.update') }}" method="POST" id="settingsForm">
                        @csrf
                        
                        {{-- تفعيل/تعطيل الحماية --}}
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type="checkbox" 
                                       name="enabled" 
                                       id="enabled" 
                                       class="form-check-input" 
                                       value="1"
                                       {{ $settings['enabled'] ? 'checked' : '' }}
                                       onchange="togglePasswordFields()">
                                <label class="form-check-label" for="enabled">
                                    <strong>تفعيل حماية الموقع</strong>
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                عند التفعيل، سيتم طلب إدخال كلمة مرور للوصول إلى الموقع
                            </small>
                        </div>

                        <hr>

                        {{-- كلمة المرور --}}
                        <div class="form-group" id="passwordGroup">
                            <label for="password">
                                كلمة المرور <span class="text-danger" id="passwordRequired">*</span>
                            </label>
                            <input type="text" 
                                   name="password" 
                                   id="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   value="{{ old('password', $settings['password']) }}"
                                   placeholder="أدخل كلمة المرور (أرقام فقط)"
                                   maxlength="{{ $settings['password_length'] }}"
                                   pattern="[0-9]*"
                                   inputmode="numeric">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                يجب أن تكون كلمة المرور أرقام فقط
                            </small>
                        </div>

                        {{-- طول كلمة المرور --}}
                        <div class="form-group" id="passwordLengthGroup">
                            <label for="password_length">
                                طول كلمة المرور (عدد الأرقام) <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   name="password_length" 
                                   id="password_length" 
                                   class="form-control @error('password_length') is-invalid @enderror" 
                                   value="{{ old('password_length', $settings['password_length']) }}"
                                   min="4" 
                                   max="10" 
                                   required
                                   onchange="updatePasswordMaxLength()">
                            @error('password_length')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                يجب أن يكون الطول بين 4 و 10 أرقام
                            </small>
                        </div>

                        {{-- مدة صلاحية الجلسة --}}
                        <div class="form-group" id="sessionTimeoutGroup">
                            <label for="session_timeout">
                                مدة صلاحية الجلسة (بالدقائق) <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   name="session_timeout" 
                                   id="session_timeout" 
                                   class="form-control @error('session_timeout') is-invalid @enderror" 
                                   value="{{ old('session_timeout', $settings['session_timeout']) }}"
                                   min="5" 
                                   max="1440" 
                                   required>
                            @error('session_timeout')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                بعد انتهاء هذه المدة، سيتم طلب إدخال كلمة المرور مرة أخرى (من 5 دقائق إلى 1440 دقيقة = 24 ساعة)
                            </small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>حفظ التغييرات
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- معلومات إضافية --}}
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle mr-2"></i>معلومات
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold mb-3">حول حماية الموقع:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            كلمة المرور يجب أن تكون أرقام فقط
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            يمكن تحديد طول كلمة المرور من 4 إلى 10 أرقام
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            بعد انتهاء مدة الجلسة، سيتم طلب إدخال كلمة المرور مرة أخرى
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            جميع صفحات الموقع محمية ما عدا صفحة إدخال كلمة المرور
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>تحذير
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <strong>تنبيه:</strong> عند تفعيل الحماية، تأكد من حفظ كلمة المرور في مكان آمن. 
                        إذا نسيت كلمة المرور، يمكنك تعطيل الحماية من ملف .env مباشرة.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordFields() {
        const enabled = document.getElementById('enabled').checked;
        const passwordInput = document.getElementById('password');
        const passwordRequired = document.getElementById('passwordRequired');
        
        if (enabled) {
            passwordInput.required = true;
            passwordRequired.style.display = 'inline';
        } else {
            passwordInput.required = false;
            passwordRequired.style.display = 'none';
        }
    }

    function updatePasswordMaxLength() {
        const length = document.getElementById('password_length').value;
        const passwordInput = document.getElementById('password');
        passwordInput.maxLength = length;
        passwordInput.placeholder = `أدخل كلمة المرور (${length} أرقام)`;
    }

    // تهيئة الحقول عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        togglePasswordFields();
        updatePasswordMaxLength();
    });

    // منع إدخال أي شيء غير الأرقام في حقل كلمة المرور
    document.getElementById('password').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection

