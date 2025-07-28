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
        }

        .main-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 35px 60px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.15);
        }

        /* منطقة النموذج */
        .form-section {
            padding: 3rem;
            position: relative;
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
        }

        .image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="chart" patternUnits="userSpaceOnUse" width="20" height="20"><path d="M0 20L20 0M-5 5L5 -5M15 25L25 15" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23chart)"/></svg>');
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
            margin-bottom: 2rem;
            position: relative;
        }

        .logo-container img {
            width: 200px;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
            transition: all 0.3s ease;
        }

        .logo-container:hover img {
            transform: scale(1.05);
            filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.15));
        }

        /* العنوان */
        .welcome-text {
            font-size: 1.8rem;
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
            font-size: 1rem;
            color: #666;
            margin-bottom: 2rem;
            opacity: 0.8;
        }

        /* حقول الإدخال */
        .form-outline {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(26, 54, 93, 0.1);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(49, 130, 206, 0.25), 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .form-label {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* أزرار */
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(26, 54, 93, 0.3);
        }

        .btn-register {
            background: linear-gradient(135deg, transparent 0%, rgba(26, 54, 93, 0.05) 100%);
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 54, 93, 0.2);
        }

        /* رابط نسيان كلمة المرور */
        .forgot-password {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .forgot-password::after {
            content: '';
            position: absolute;
            bottom: -2px;
            right: 0;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: width 0.3s ease;
        }

        .forgot-password:hover::after {
            width: 100%;
        }

        .forgot-password:hover {
            color: var(--primary-color);
        }

        /* تنبيهات الأخطاء */
        .alert {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
        }

        /* النص الجانبي */
        .side-content {
            padding: 3rem;
            position: relative;
            z-index: 2;
            color: white;
        }

        .side-content h4 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .side-content p {
            font-size: 1.1rem;
            line-height: 1.7;
            opacity: 0.95;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
        }

        /* أيقونات مالية */
        .financial-icons {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: flex;
            gap: 1rem;
            z-index: 2;
        }

        .financial-icon {
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

        .financial-icon:nth-child(2) {
            animation-delay: 2s;
        }

        .financial-icon:nth-child(3) {
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

        /* تحسينات الاستجابة */
        @media (max-width: 992px) {
            .form-section {
                padding: 2rem;
            }

            .side-content {
                padding: 2rem;
                text-align: center;
            }

            .financial-icons {
                top: 1rem;
                left: 50%;
                transform: translateX(-50%);
            }
        }

        @media (max-width: 768px) {
            .welcome-text {
                font-size: 1.5rem;
            }

            .side-content h4 {
                font-size: 1.8rem;
            }

            .logo-container img {
                width: 150px;
            }
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

    <section class="min-vh-100 d-flex align-items-center py-5">
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
                                        <h2 class="welcome-text">مرحباً بك مجدداً</h2>
                                        <p class="subtitle">سجل دخولك لاستكمال رحلتك التعليمية في عالم المال والأعمال
                                        </p>
                                    </div> --}}

                                    <!-- النموذج -->
                                    <form action="{{ route('customLogin') }}" method="POST">
                                        @csrf

                                        @if (session('error'))
                                            <div class="alert text-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                {{ session('error') }}
                                            </div>
                                        @endif

                                        <div class="form-outline">
                                            <label class="form-label" for="form2Example11">
                                                <i class="fas fa-envelope me-2"></i>البريد الإلكتروني
                                            </label>
                                            <input type="email" name="email" id="form2Example11"
                                                class="form-control" placeholder="أدخل بريدك الإلكتروني" required />
                                        </div>

                                        <div class="form-outline">
                                            <label class="form-label" for="form2Example22">
                                                <i class="fas fa-lock me-2"></i>كلمة المرور
                                            </label>
                                            <input type="password" name="password" id="form2Example22"
                                                class="form-control" placeholder="أدخل كلمة المرور" required />
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <a class="forgot-password" href="{{ route('forgetPassword') }}">
                                                <i class="fas fa-key me-1"></i>هل نسيت كلمة المرور؟
                                            </a>
                                        </div>

                                        <button class="btn btn-login mb-4" type="submit">
                                            <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
                                        </button>

                                        <div class="text-center">
                                            <p class="mb-3 text-muted">ليس لديك حساب؟</p>
                                            <a href="{{ route('register') }}" class="text-decoration-none">
                                                <button type="button" class="btn btn-register">
                                                    <i class="fas fa-user-plus me-2"></i>إنشاء حساب جديد
                                                </button>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- منطقة الصورة والمحتوى الجانبي -->
                            <div class="col-lg-6">
                                <div class="image-section d-flex align-items-center min-vh-100">
                                    <!-- أيقونات مالية -->
                                    <div class="financial-icons">
                                        <div class="financial-icon">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="financial-icon">
                                            <i class="fas fa-coins"></i>
                                        </div>
                                        <div class="financial-icon">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                    </div>

                                    <div class="side-content">
                                        {{-- <h4 class="mb-4">
                                            <i class="fas fa-graduation-cap me-3"></i>
                                            تعلم فن إدارة المال والاستثمار
                                        </h4>
                                        <p class="mb-4">
                                            انضم إلى آلاف المتعلمين الذين يطورون مهاراتهم المالية معنا.
                                            نوفر لك محتوى تعليمي عالي الجودة في:
                                        </p> --}}
                                        {{-- <div class="d-flex flex-column gap-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle me-3 text-warning"></i>
                                                <span>التحليل المالي والاستثماري</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle me-3 text-warning"></i>
                                                <span>إدارة المحافظ الاستثمارية</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle me-3 text-warning"></i>
                                                <span>ريادة الأعمال والتخطيط المالي</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle me-3 text-warning"></i>
                                                <span>الأسواق المالية والعملات الرقمية</span>
                                            </div>
                                        </div> --}}
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
        // تحسين تجربة المستخدم
        document.addEventListener('DOMContentLoaded', function() {
            // تأثير التركيز على حقول الإدخال
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });

            // تحسين أداء الأنيميشن
            const particles = document.querySelectorAll('.particle');
            particles.forEach((particle, index) => {
                particle.style.animationDelay = `${index * 2}s`;
            });
        });
    </script>
</body>

</html>
