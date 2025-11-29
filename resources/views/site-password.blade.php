<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حماية الموقع - إدخال كلمة المرور</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Amiri:wght@700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #2E7D32;
            --accent-color: #66BB6A;
            --gold-color: #D4AF37;
            --bg-color: #F1F8E9;
            --success-color: #38a169;
            --error-color: #c53030;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            text-align: right;
            background-color: var(--bg-color);
            background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23D7CCC8" fill-opacity="0.2"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
            min-height: 100vh;
        }

        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .main-card:hover {
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .form-section {
            padding: 3rem;
        }

        .image-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            position: relative;
            overflow: hidden;
            color: white;
        }

        .image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M 50,50 m -48,0 a 48,48 0 1,1 96,0 a 48,48 0 1,1 -96,0" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1" /><path d="M 50,2 v 96" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1" /><path d="M 2,50 h 96" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1" /><path d="M 20,20 l 60,60" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1" /><path d="M 20,80 l 60,-60" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1" /></svg>');
            opacity: 0.5;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .logo-container .fa-lock {
            font-size: 5rem;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.1));
            transition: all 0.3s ease;
        }

        .logo-container:hover .fa-lock {
            transform: scale(1.05);
            color: var(--primary-color);
        }

        .welcome-text {
            font-family: 'Amiri', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .subtitle {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2.5rem;
        }

        .password-input-container {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            direction: ltr;
        }

        .password-digit {
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

        .password-digit:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
            outline: none;
        }

        .password-digit.filled {
            border-color: var(--primary-color);
            background: #f0f8f0;
        }

        .btn-main {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 12px;
            padding: 0.9rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(46, 125, 50, 0.3);
        }

        .btn-main:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .side-content {
            padding: 3rem;
            position: relative;
            z-index: 2;
        }

        .side-content h4 {
            font-family: 'Amiri', serif;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .side-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            opacity: 0.9;
        }

        .alert {
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background-color: #fee;
            border: 1px solid #fcc;
            color: var(--error-color);
        }

        .alert-success {
            background-color: #efe;
            border: 1px solid #cfc;
            color: var(--success-color);
        }

        .password-hint {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>

    <section class="min-vh-100 d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12">
                    <div class="main-card">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="form-section">
                                    <div class="logo-container">
                                        <i class="fas fa-lock"></i>
                                    </div>

                                    <div class="text-center mb-4">
                                        <h2 class="welcome-text">حماية الموقع</h2>
                                        <p class="subtitle">يرجى إدخال كلمة المرور للوصول إلى الموقع</p>
                                    </div>

                                    @if(session('error'))
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('site.password.verify') }}" method="POST" id="passwordForm">
                                        @csrf
                                        
                                        <div class="password-hint">
                                            <p>أدخل {{ $passwordLength }} أرقام</p>
                                        </div>

                                        <div class="password-input-container" id="passwordContainer">
                                            @for($i = 0; $i < $passwordLength; $i++)
                                                <input type="text" 
                                                       class="password-digit" 
                                                       maxlength="1" 
                                                       pattern="[0-9]" 
                                                       inputmode="numeric"
                                                       autocomplete="off"
                                                       data-index="{{ $i }}"
                                                       @if($i === 0) autofocus @endif>
                                            @endfor
                                        </div>

                                        <input type="hidden" name="password" id="hiddenPassword">

                                        <button type="submit" class="btn btn-main" id="submitBtn" disabled>
                                            <i class="fas fa-unlock me-2"></i>
                                            التحقق من كلمة المرور
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="image-section d-flex align-items-center h-100">
                                    <div class="side-content">
                                        <h4 class="mb-4">
                                            <i class="fas fa-shield-alt me-3"></i>
                                            "الأمان أولاً"
                                        </h4>
                                        <p>
                                            هذا الموقع محمي بكلمة مرور لضمان الخصوصية والأمان. 
                                            يرجى إدخال كلمة المرور المكونة من {{ $passwordLength }} أرقام للوصول إلى المحتوى.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const digits = document.querySelectorAll('.password-digit');
            const hiddenInput = document.getElementById('hiddenPassword');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('passwordForm');
            const passwordLength = {{ $passwordLength }};

            // التحقق من اكتمال كلمة المرور
            function checkPassword() {
                let password = '';
                digits.forEach(digit => {
                    password += digit.value;
                });

                hiddenInput.value = password;
                
                if (password.length === passwordLength) {
                    submitBtn.disabled = false;
                    digits.forEach(d => {
                        if (d.value) {
                            d.classList.add('filled');
                        }
                    });
                } else {
                    submitBtn.disabled = true;
                    digits.forEach(d => {
                        d.classList.remove('filled');
                    });
                }
            }

            // التعامل مع إدخال الأرقام
            digits.forEach((digit, index) => {
                digit.addEventListener('input', function(e) {
                    // السماح بالأرقام فقط
                    if (!/^[0-9]$/.test(e.target.value)) {
                        e.target.value = '';
                        return;
                    }

                    // الانتقال للحقل التالي تلقائياً
                    if (e.target.value && index < digits.length - 1) {
                        digits[index + 1].focus();
                    }

                    checkPassword();
                });

                digit.addEventListener('keydown', function(e) {
                    // التعامل مع Backspace
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        digits[index - 1].focus();
                    }
                });

                digit.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
                    
                    if (pastedData.length === passwordLength) {
                        pastedData.split('').forEach((char, i) => {
                            if (digits[i]) {
                                digits[i].value = char;
                            }
                        });
                        checkPassword();
                        digits[passwordLength - 1].focus();
                    }
                });
            });

            // إرسال النموذج عند اكتمال كلمة المرور
            form.addEventListener('submit', function(e) {
                const password = hiddenInput.value;
                if (password.length !== passwordLength) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</body>

</html>

