@extends('layouts.web-site.web')

@section('title', 'حماية الموقع - إدخال كلمة المرور')

@push('styles')
<style>
    :root {
        --site-pw-primary: #4CAF50;
        --site-pw-secondary: #2E7D32;
        --site-pw-bg: #F1F8E9;
        --site-pw-success: #38a169;
        --site-pw-error: #c53030;
    }
    .site-password-page body { background-color: var(--site-pw-bg); }
    .main-card-pw {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        border-radius: 24px;
        overflow: hidden;
    }
    .form-section-pw { padding: 3rem; }
    .image-section-pw {
        background: linear-gradient(135deg, var(--site-pw-primary) 0%, var(--site-pw-secondary) 100%);
        color: white;
    }
    .password-digit-pw {
        width: 60px;
        height: 70px;
        text-align: center;
        font-size: 2rem;
        font-weight: 700;
        border: 2px solid #ddd;
        border-radius: 12px;
        background: white;
        transition: all 0.3s ease;
    }
    .password-digit-pw:focus {
        border-color: var(--site-pw-primary);
        box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
        outline: none;
    }
    .password-digit-pw.filled {
        border-color: var(--site-pw-primary);
        background: #f0f8f0;
    }
    .btn-main-pw {
        background: linear-gradient(135deg, var(--site-pw-primary) 0%, var(--site-pw-secondary) 100%);
        border: none;
        border-radius: 12px;
        padding: 0.9rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: white;
        width: 100%;
        transition: all 0.3s ease;
    }
    .btn-main-pw:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(46, 125, 50, 0.3);
    }
    .btn-main-pw:disabled { opacity: 0.6; cursor: not-allowed; }
    .alert-pw { border-radius: 12px; padding: 1rem; margin-bottom: 1rem; }
    .alert-danger-pw { background: #fee; border: 1px solid #fcc; color: var(--site-pw-error); }
    .alert-success-pw { background: #efe; border: 1px solid #cfc; color: var(--site-pw-success); }
</style>
@endpush

@section('content')
<section class="min-h-screen flex items-center py-12 bg-[var(--site-pw-bg)]">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <div class="main-card-pw">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <div class="form-section-pw">
                        <div class="text-center mb-6">
                            <i class="fas fa-lock text-5xl text-[var(--site-pw-secondary)] mb-4 block"></i>
                            <h2 class="text-2xl font-bold text-[var(--site-pw-secondary)] mb-2">حماية الموقع</h2>
                            <p class="text-gray-600">يرجى إدخال كلمة المرور للوصول إلى الموقع</p>
                        </div>

                        @if(session('error'))
                            <div class="alert-pw alert-danger-pw">
                                <i class="fas fa-exclamation-circle ml-2"></i>{{ session('error') }}
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="alert-pw alert-success-pw">
                                <i class="fas fa-check-circle ml-2"></i>{{ session('success') }}
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert-pw alert-danger-pw">
                                <ul class="mb-0 list-none pr-4">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('site.password.verify') }}" method="POST" id="passwordForm">
                            @csrf
                            <p class="text-center text-gray-600 text-sm mb-4">أدخل {{ $passwordLength }} أرقام</p>
                            <div class="flex justify-center gap-3 flex-wrap mb-6" id="passwordContainer" dir="ltr">
                                @for($i = 0; $i < $passwordLength; $i++)
                                    <input type="text"
                                           class="password-digit-pw"
                                           maxlength="1"
                                           pattern="[0-9]"
                                           inputmode="numeric"
                                           autocomplete="off"
                                           data-index="{{ $i }}"
                                           @if($i === 0) autofocus @endif>
                                @endfor
                            </div>
                            <input type="hidden" name="password" id="hiddenPassword">
                            <button type="submit" class="btn-main-pw" id="submitBtn" disabled>
                                <i class="fas fa-unlock ml-2"></i>التحقق من كلمة المرور
                            </button>
                        </form>
                    </div>
                    <div class="image-section-pw hidden lg:flex items-center p-8">
                        <div>
                            <h4 class="text-2xl font-bold mb-4">
                                <i class="fas fa-shield-alt ml-3"></i>"الأمان أولاً"
                            </h4>
                            <p class="text-white/95 text-lg leading-relaxed">
                                هذا الموقع محمي بكلمة مرور لضمان الخصوصية والأمان.
                                يرجى إدخال كلمة المرور المكونة من {{ $passwordLength }} أرقام للوصول إلى المحتوى.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var digits = document.querySelectorAll('.password-digit-pw');
    var hiddenInput = document.getElementById('hiddenPassword');
    var submitBtn = document.getElementById('submitBtn');
    var form = document.getElementById('passwordForm');
    var passwordLength = {{ $passwordLength }};

    function checkPassword() {
        var password = '';
        digits.forEach(function(digit) { password += digit.value; });
        hiddenInput.value = password;
        if (password.length === passwordLength) {
            submitBtn.disabled = false;
            digits.forEach(function(d) { if (d.value) d.classList.add('filled'); });
        } else {
            submitBtn.disabled = true;
            digits.forEach(function(d) { d.classList.remove('filled'); });
        }
    }

    digits.forEach(function(digit, index) {
        digit.addEventListener('input', function(e) {
            if (!/^[0-9]$/.test(e.target.value)) { e.target.value = ''; return; }
            if (e.target.value && index < digits.length - 1) digits[index + 1].focus();
            checkPassword();
        });
        digit.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) digits[index - 1].focus();
        });
        digit.addEventListener('paste', function(e) {
            e.preventDefault();
            var pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
            if (pastedData.length === passwordLength) {
                pastedData.split('').forEach(function(char, i) {
                    if (digits[i]) digits[i].value = char;
                });
                checkPassword();
                digits[passwordLength - 1].focus();
            }
        });
    });

    form.addEventListener('submit', function(e) {
        if (hiddenInput.value.length !== passwordLength) { e.preventDefault(); return false; }
    });
});
</script>
@endpush
