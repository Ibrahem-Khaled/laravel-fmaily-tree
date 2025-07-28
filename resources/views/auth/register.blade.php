<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #64ff45;
            --secondary-color: #30af24;
            --accent-color: #0c8b00;
            --gold-color: #f6e05e;
            --success-color: #38a169;
            --gradient-start: #64ff45;
            --gradient-end: #62ff54;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            text-align: right;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* خلفية متحركة */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
        }

        .animated-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(246, 224, 94, 0.2) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* تأثيرات الجزيئات المتحركة */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: particleFloat 15s infinite linear;
        }

        .particle:nth-child(1) {
            width: 4px;
            height: 4px;
            left: 10%;
            animation-delay: 0s;
        }

        .particle:nth-child(2) {
            width: 6px;
            height: 6px;
            left: 20%;
            animation-delay: 2s;
        }

        .particle:nth-child(3) {
            width: 3px;
            height: 3px;
            left: 30%;
            animation-delay: 4s;
        }

        .particle:nth-child(4) {
            width: 5px;
            height: 5px;
            left: 40%;
            animation-delay: 6s;
        }

        .particle:nth-child(5) {
            width: 4px;
            height: 4px;
            left: 50%;
            animation-delay: 8s;
        }

        .particle:nth-child(6) {
            width: 7px;
            height: 7px;
            left: 60%;
            animation-delay: 10s;
        }

        .particle:nth-child(7) {
            width: 3px;
            height: 3px;
            left: 70%;
            animation-delay: 12s;
        }

        .particle:nth-child(8) {
            width: 5px;
            height: 5px;
            left: 80%;
            animation-delay: 14s;
        }

        .particle:nth-child(9) {
            width: 4px;
            height: 4px;
            left: 90%;
            animation-delay: 16s;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* البطاقة الرئيسية */
        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow:
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            overflow: hidden;
            transform: translateY(0);
            transition: all 0.3s ease;
            margin: 2rem 0;
        }

        .main-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 35px 60px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.15);
        }

        /* منطقة النموذج */
        .form-section {
            padding: 2.5rem;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-section::-webkit-scrollbar {
            width: 6px;
        }

        .form-section::-webkit-scrollbar-track {
            background: rgba(26, 54, 93, 0.1);
            border-radius: 3px;
        }

        .form-section::-webkit-scrollbar-thumb {
            background: var(--accent-color);
            border-radius: 3px;
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(26, 54, 93, 0.02) 0%, rgba(255, 255, 255, 0.05) 100%);
            pointer-events: none;
        }

        /* منطقة الصورة الجانبية */
        .image-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
            position: relative;
            overflow: hidden;
            min-height: 600px;
        }

        .image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="growth" patternUnits="userSpaceOnUse" width="25" height="25"><path d="M0 25L12.5 12.5L25 0M-6.25 6.25L6.25 -6.25M18.75 31.25L31.25 18.75" stroke="rgba(255,255,255,0.1)" stroke-width="1.5"/></pattern></defs><rect width="100" height="100" fill="url(%23growth)"/></svg>');
            opacity: 0.3;
        }

        .image-section::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(246, 224, 94, 0.1) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* الشعار */
        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .logo-container img {
            width: 180px;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
            transition: all 0.3s ease;
        }

        .logo-container:hover img {
            transform: scale(1.05);
            filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.15));
        }

        /* العنوان */
        .welcome-text {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            position: relative;
        }

        .welcome-text::after {
            content: '';
            position: absolute;
            bottom: -5px;
            right: 50%;
            transform: translateX(50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--gold-color), var(--accent-color));
            border-radius: 2px;
        }

        .subtitle {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 1.5rem;
            opacity: 0.8;
        }

        /* حقول الإدخال */
        .form-outline {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(26, 54, 93, 0.1);
            border-radius: 12px;
            padding: 0.9rem 1.3rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(49, 130, 206, 0.25), 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .form-control.is-valid {
            border-color: var(--success-color);
            box-shadow: 0 0 0 0.2rem rgba(56, 161, 105, 0.25);
        }

        .form-control.is-invalid {
            border-color: var(--error-color);
            box-shadow: 0 0 0 0.2rem rgba(229, 62, 62, 0.25);
        }

        .form-label {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* تحسين شكل الأيقونات في اللايبل */
        .form-label i {
            color: var(--accent-color);
            font-size: 0.9rem;
        }

        /* أزرار */
        .btn-register {
            background: linear-gradient(135deg, var(--success-color) 0%, #48bb78 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(56, 161, 105, 0.3);
        }

        .btn-login {
            background: linear-gradient(135deg, transparent 0%, rgba(26, 54, 93, 0.05) 100%);
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 12px;
            padding: 0.7rem 1.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 54, 93, 0.2);
        }

        /* تنبيهات الأخطاء والنجاح */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .alert-danger {
            background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }

        .alert-danger ul {
            margin: 0;
            padding-right: 1rem;
        }

        .alert-danger li {
            margin-bottom: 0.3rem;
        }

        /* النص الجانبي */
        .side-content {
            padding: 2.5rem;
            position: relative;
            z-index: 2;
            color: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .side-content h4 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .side-content p {
            font-size: 1.05rem;
            line-height: 1.7;
            opacity: 0.95;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
        }

        /* أيقونات النمو والاستثمار */
        .growth-icons {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: flex;
            gap: 1rem;
            z-index: 2;
        }

        .growth-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            backdrop-filter: blur(10px);
            animation: iconFloat 6s ease-in-out infinite;
        }

        .growth-icon:nth-child(2) {
            animation-delay: 2s;
        }

        .growth-icon:nth-child(3) {
            animation-delay: 4s;
        }

        @keyframes iconFloat {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-10px) rotate(5deg);
            }

            66% {
                transform: translateY(5px) rotate(-3deg);
            }
        }

        /* مؤشر قوة كلمة المرور */
        .password-strength {
            margin-top: 0.5rem;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.8rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .password-strength.weak {
            background: rgba(229, 62, 62, 0.1);
            color: var(--error-color);
        }

        .password-strength.medium {
            background: rgba(246, 224, 94, 0.2);
            color: #d69e2e;
        }

        .password-strength.strong {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success-color);
        }

        /* تحسينات الاستجابة */
        @media (max-width: 992px) {
            .form-section {
                padding: 2rem;
                max-height: none;
            }

            .side-content {
                padding: 2rem;
                text-align: center;
                min-height: 400px;
            }

            .growth-icons {
                top: 1rem;
                left: 50%;
                transform: translateX(-50%);
            }
        }

        @media (max-width: 768px) {
            .welcome-text {
                font-size: 1.4rem;
            }

            .side-content h4 {
                font-size: 1.7rem;
            }

            .logo-container img {
                width: 140px;
            }

            .form-section {
                padding: 1.5rem;
            }
        }

        /* تأثيرات التحقق الفوري */
        .validation-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .validation-icon.show {
            opacity: 1;
        }

        .validation-icon.valid {
            color: var(--success-color);
        }

        .validation-icon.invalid {
            color: var(--error-color);
        }
    </style>
</head>

<body>
    <!-- خلفية متحركة -->
    <div class="animated-bg"></div>

    <!-- جزيئات متحركة -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <section class="min-vh-100 d-flex align-items-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-11 col-lg-12">
                    <div class="main-card">
                        <div class="row g-0">
                            <!-- منطقة النموذج -->
                            <div class="col-lg-6">
                                <div class="form-section">
                                    <!-- الشعار -->
                                    <div class="logo-container">
                                        <img src="{{ asset('assets/img/logo-ct.png') }}" alt="شعار المنصة">
                                    </div>

                                    <!-- العنوان -->
                                    {{-- <div class="text-center mb-4">
                                        <h2 class="welcome-text">انضم إلى رحلة النجاح</h2>
                                        <p class="subtitle">أنشئ حسابك الآن وابدأ تعلم أسرار النجاح في عالم المال
                                            والاستثمار</p>
                                    </div> --}}

                                    <!-- تنبيهات الأخطاء -->
                                    @if (session('error'))
                                        <div class="alert alert-danger text-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <i class="fas fa-times-circle me-2"></i>
                                            <strong>يرجى تصحيح الأخطاء التالية:</strong>
                                            <ul class="mt-2 mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <!-- النموذج -->
                                    <form method="POST" action="{{ route('customRegister') }}" id="registerForm">
                                        @csrf

                                        <div class="form-outline">
                                            <label class="form-label" for="formName">
                                                <i class="fas fa-user"></i>الاسم الكامل
                                            </label>
                                            <input type="text" name="name" id="formName" class="form-control"
                                                placeholder="أدخل اسمك الكامل" required />
                                            <i class="validation-icon fas fa-check valid"></i>
                                            <i class="validation-icon fas fa-times invalid"></i>
                                        </div>

                                        <div class="form-outline">
                                            <label class="form-label" for="formEmail">
                                                <i class="fas fa-envelope"></i>البريد الإلكتروني
                                            </label>
                                            <input type="email" name="email" id="formEmail" class="form-control"
                                                placeholder="أدخل بريدك الإلكتروني" required />
                                            <i class="validation-icon fas fa-check valid"></i>
                                            <i class="validation-icon fas fa-times invalid"></i>
                                        </div>

                                        <div class="form-outline">
                                            <label class="form-label" for="formPhone">
                                                <i class="fas fa-phone"></i>رقم الهاتف
                                            </label>
                                            <input type="text" name="phone" id="formPhone" class="form-control"
                                                placeholder="أدخل رقم هاتفك" required />
                                            <i class="validation-icon fas fa-check valid"></i>
                                            <i class="validation-icon fas fa-times invalid"></i>
                                        </div>

                                        <div class="form-outline">
                                            <label class="form-label" for="formAddress">
                                                <i class="fas fa-map-marker-alt"></i>العنوان
                                            </label>
                                            <input type="text" name="address" id="formAddress"
                                                class="form-control" placeholder="أدخل عنوانك" required />
                                            <i class="validation-icon fas fa-check valid"></i>
                                            <i class="validation-icon fas fa-times invalid"></i>
                                        </div>

                                        <div class="form-outline">
                                            <label class="form-label" for="formPassword">
                                                <i class="fas fa-lock"></i>كلمة المرور
                                            </label>
                                            <input type="password" name="password" id="formPassword"
                                                class="form-control" placeholder="أدخل كلمة مرور قوية" required />
                                            <div id="passwordStrength" class="password-strength"
                                                style="display: none;"></div>
                                        </div>

                                        <div class="form-outline">
                                            <label class="form-label" for="formPasswordConfirmation">
                                                <i class="fas fa-lock"></i>تأكيد كلمة المرور
                                            </label>
                                            <input type="password" name="password_confirmation"
                                                id="formPasswordConfirmation" class="form-control"
                                                placeholder="أعد إدخال كلمة المرور" required />
                                            <i class="validation-icon fas fa-check valid"></i>
                                            <i class="validation-icon fas fa-times invalid"></i>
                                        </div>

                                        <div class="mb-4">
                                            <button class="btn btn-register" type="submit">
                                                <i class="fas fa-user-plus me-2"></i>إنشاء حساب جديد
                                            </button>
                                        </div>

                                        <div class="text-center">
                                            <p class="mb-3 text-muted">لديك حساب بالفعل؟</p>
                                            <a href="{{ route('login') }}" class="text-decoration-none">
                                                <button type="button" class="btn btn-login">
                                                    <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
                                                </button>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- منطقة الصورة والمحتوى الجانبي -->
                            <div class="col-lg-6">
                                <div class="image-section d-flex align-items-center">
                                    <!-- أيقونات النمو -->
                                    <div class="growth-icons">
                                        <div class="growth-icon">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="growth-icon">
                                            <i class="fas fa-seedling"></i>
                                        </div>
                                        <div class="growth-icon">
                                            <i class="fas fa-trophy"></i>
                                        </div>
                                    </div>

                                    <div class="side-content">
                                        {{-- <h4 class="mb-4">
                                            <i class="fas fa-rocket me-3"></i>
                                            ابدأ رحلتك نحو الحرية المالية
                                        </h4>
                                        <p class="mb-4">
                                            انضم إلى مجتمع من المتعلمين الطموحين الذين يسعون لتطوير مهاراتهم المالية
                                            وبناء مستقبل مالي مشرق من خلال التعليم المتخصص.
                                        </p>
                                        <div class="d-flex flex-column gap-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-star me-3 text-warning"></i>
                                                <span>محتوى تعليمي عالي الجودة من خبراء المجال</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-users me-3 text-warning"></i>
                                                <span>مجتمع نشط من المتعلمين والخبراء</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-certificate me-3 text-warning"></i>
                                                <span>شهادات معتمدة عند إتمام الدورات</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-headset me-3 text-warning"></i>
                                                <span>دعم فني متواصل على مدار الساعة</span>
                                            </div> --}}
                                        </div>
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
            // التحقق من صحة الحقول في الوقت الفعلي
            const form = document.getElementById('registerForm');
            const inputs = form.querySelectorAll('.form-control');

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    validateField(this);
                });

                input.addEventListener('blur', function() {
                    validateField(this);
                });
            });

            function validateField(field) {
                const fieldType = field.type;
                const fieldValue = field.value.trim();
                const fieldName = field.name;

                let isValid = false;

                switch (fieldName) {
                    case 'name':
                        isValid = fieldValue.length >= 2 && /^[\u0600-\u06FF\sA-Za-z]+$/.test(
                        fieldValue); // للتحقق من الأسماء العربية والإنجليزية
                        break;
                    case 'email':
                        isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fieldValue);
                        break;
                    case 'phone':
                        isValid = /^[\d\s+]{10,15}$/.test(fieldValue);
                        break;
                    case 'address':
                        isValid = fieldValue.length >= 5;
                        break;
                    case 'password':
                        isValid = fieldValue.length >= 8;
                        updatePasswordStrength(fieldValue);
                        break;
                    case 'password_confirmation':
                        const password = document.getElementById('formPassword').value;
                        isValid = fieldValue === password;
                        break;
                    default:
                        isValid = fieldValue.length > 0;
                }

                // تحديث حالة الحقل
                updateFieldValidation(field, isValid);
            }

            function updateFieldValidation(field, isValid) {
                const validIcon = field.nextElementSibling;
                const invalidIcon = validIcon.nextElementSibling;

                field.classList.remove('is-valid', 'is-invalid');

                if (field.value.trim() === '') {
                    validIcon.classList.remove('show');
                    invalidIcon.classList.remove('show');
                    return;
                }

                if (isValid) {
                    field.classList.add('is-valid');
                    validIcon.classList.add('show');
                    invalidIcon.classList.remove('show');
                } else {
                    field.classList.add('is-invalid');
                    validIcon.classList.remove('show');
                    invalidIcon.classList.add('show');
                }
            }

            // مؤشر قوة كلمة المرور
            function updatePasswordStrength(password) {
                const strengthIndicator = document.getElementById('passwordStrength');

                if (password.length === 0) {
                    strengthIndicator.style.display = 'none';
                    return;
                }

                strengthIndicator.style.display = 'block';

                // حساب القوة
                let strength = 0;

                // طول كلمة المرور
                if (password.length >= 8) strength += 1;
                if (password.length >= 12) strength += 1;

                // أحرف متنوعة
                if (/[A-Z]/.test(password)) strength += 1;
                if (/[a-z]/.test(password)) strength += 1;
                if (/\d/.test(password)) strength += 1;
                if (/[^A-Za-z0-9]/.test(password)) strength += 1;

                // تصنيف القوة
                strengthIndicator.className = 'password-strength';

                if (strength <= 3) {
                    strengthIndicator.textContent = 'ضعيفة';
                    strengthIndicator.classList.add('weak');
                } else if (strength <= 5) {
                    strengthIndicator.textContent = 'متوسطة';
                    strengthIndicator.classList.add('medium');
                } else {
                    strengthIndicator.textContent = 'قوية';
                    strengthIndicator.classList.add('strong');
                }
            }

            // التحقق من تطابق كلمة المرور عند الإرسال
            form.addEventListener('submit', function(e) {
                let isValid = true;

                inputs.forEach(input => {
                    if (input.value.trim() === '' || input.classList.contains('is-invalid')) {
                        isValid = false;
                        input.classList.add('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();

                    // عرض تنبيه للأخطاء
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'alert alert-danger';
                    errorAlert.innerHTML = `
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>يرجى تصحيح الأخطاء في النموذج قبل الإرسال</strong>
                    `;

                    const formSection = document.querySelector('.form-section');
                    formSection.insertBefore(errorAlert, formSection.firstChild);

                    // التمرير إلى الأعلى لعرض الخطأ
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>
