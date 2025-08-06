<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تواصل العائلة - تسجيل الدخول</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Amiri:wght@700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            /* لوحة ألوان خضراء طبيعية وأنيقة */
            --primary-color: #4CAF50;
            /* أخضر طبيعي */
            --secondary-color: #2E7D32;
            /* أخضر غامق */
            --accent-color: #66BB6A;
            /* أخضر فاتح */
            --gold-color: #D4AF37;
            /* ذهبي عتيق */
            --bg-color: #F1F8E9;
            /* أخضر باهت جداً للخلفية */
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

        /* البطاقة الرئيسية */
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

        /* منطقة النموذج */
        .form-section {
            padding: 3rem;
        }

        /* منطقة الصورة الجانبية */
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

        /* الشعار */
        .logo-container {
            text-align: center;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .logo-container .fa-tree {
            font-size: 5rem;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.1));
            transition: all 0.3s ease;
        }

        .logo-container:hover .fa-tree {
            transform: scale(1.05);
            color: var(--primary-color);
        }

        /* العنوان */
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

        /* حقول الإدخال */
        .form-control {
            border-radius: 12px;
            padding: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(141, 110, 99, 0.25);
        }

        .form-label {
            color: var(--secondary-color);
            font-weight: 600;
        }

        /* زر تسجيل الدخول */
        .btn-main {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 12px;
            padding: 0.9rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(78, 52, 46, 0.3);
        }

        /* زر إنشاء حساب */
        .btn-secondary-custom {
            border: 2px solid var(--accent-color);
            color: var(--accent-color);
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: var(--accent-color);
            color: white;
            transform: translateY(-2px);
        }

        /* رابط نسيان كلمة المرور */
        .forgot-password {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-color);
        }

        /* النص الجانبي */
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
                                        <i class="fas fa-tree"></i>
                                    </div>

                                    <div class="text-center mb-4">
                                        <h2 class="welcome-text">أهلاً بك في شجرتك</h2>
                                        <p class="subtitle">سجل دخولك لتوثيق جذورك واستكشاف تراث عائلتك</p>
                                    </div>

                                    @include('components.alerts')

                                    <form action="{{ route('customLogin') }}" method="POST">
                                        @csrf
                                        <div class="form-outline mb-4">
                                            <input type="email" name="email" id="formEmail" class="form-control" />
                                            <label class="form-label" for="formEmail">البريد الإلكتروني</label>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <input type="password" name="password" id="formPassword"
                                                class="form-control" />
                                            <label class="form-label" for="formPassword">كلمة المرور</label>
                                        </div>

                                        <div class="d-flex justify-content-end align-items-center mb-4">
                                            <a class="forgot-password" href="#">هل نسيت كلمة المرور؟</a>
                                        </div>

                                        <button class="btn btn-main btn-block mb-4" type="submit">تسجيل الدخول</button>

                                        <div class="text-center">
                                            <p class="mb-2 text-muted">ليس لديك حساب؟</p>
                                            <a href="{{ route('register') }}" class="btn btn-secondary-custom">
                                                <i class="fas fa-user-plus me-2"></i>إنشاء حساب جديد
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="image-section d-flex align-items-center h-100">
                                    <div class="side-content">
                                        <h4 class="mb-4">
                                            <i class="fas fa-feather-alt me-3"></i>
                                            "معرفة تاريخنا هي معرفة أنفسنا."
                                        </h4>
                                        <p>
                                            تواصل العائلة هي أكثر من مجرد أسماء وتواريخ، إنها قصة الأجيال، روابط المحبة،
                                            والتراث الذي يجمعنا. ابدأ اليوم رحلتك في توثيق هذه القصة الخالدة.
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
</body>

</html>
