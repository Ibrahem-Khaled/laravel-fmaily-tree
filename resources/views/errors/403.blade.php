<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - الوصول ممنوع | معرض صور العائلة</title>

    {{-- قم بتضمين ملف Tailwind CSS الخاص بمشروعك --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- استيراد خطوط جميلة من Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
        /* تطبيق الخط على كامل الصفحة */
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 50%, #fecaca 100%);
            min-height: 100vh;
        }

        h1,
        h2,
        h3 {
            font-family: 'Amiri', serif;
        }

        /* تأثيرات متحركة للخلفية */
        @keyframes float {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }

            100% {
                transform: translateY(0px) rotate(360deg);
            }
        }

        @keyframes pulse-soft {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 0.6;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce-gentle {

            0%,
            100% {
                transform: translateY(0);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }

            50% {
                transform: translateY(-10px);
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }

        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(239, 68, 68, 0.6);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .pulse-animation {
            animation: pulse-soft 4s ease-in-out infinite;
        }

        .slide-in {
            animation: slideIn 0.6s ease-out forwards;
        }

        .bounce-gentle {
            animation: bounce-gentle 2s infinite;
        }

        .shake-animation {
            animation: shake 0.5s ease-in-out;
        }

        .glow-animation {
            animation: glow 3s ease-in-out infinite;
        }

        /* تأثير الزجاج المصنفر */
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* تأثير التوهج الأحمر */
        .red-glow {
            box-shadow: 0 0 40px rgba(239, 68, 68, 0.3);
        }

        .red-glow-hover:hover {
            box-shadow: 0 0 60px rgba(239, 68, 68, 0.5);
            transform: translateY(-5px);
        }

        /* تأثير النص المتدرج */
        .gradient-text {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 50%, #f87171 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* رسومات الخلفية */
        .bg-pattern {
            position: fixed;
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }

        /* تأثير الأزرار */
        .btn-primary {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #ef4444;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-secondary:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        /* تأثير الرقم 403 */
        .error-number {
            font-size: clamp(8rem, 20vw, 20rem);
            font-weight: 900;
            line-height: 0.8;
            text-shadow: 0 0 30px rgba(239, 68, 68, 0.3);
        }

        /* شريط التمرير المخصص */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #fef2f2;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #ef4444, #dc2626);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #dc2626, #b91c1c);
        }

        /* تحسينات الموبايل */
        @media (max-width: 768px) {
            .bg-pattern {
                display: none;
            }

            .error-number {
                font-size: clamp(4rem, 15vw, 8rem);
            }
        }

        /* تأثير الأيقونات المتحركة */
        .icon-bounce {
            animation: bounce-gentle 2s infinite;
            animation-delay: var(--delay, 0s);
        }

        /* تأثيرات إضافية للخلفية */
        .bg-dots {
            background-image: radial-gradient(circle, rgba(239, 68, 68, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden bg-dots">

    <!-- عناصر الخلفية المتحركة -->
    <div class="bg-pattern top-10 left-10 w-96 h-96 float-animation hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#ef4444"
                d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3C52.5,-73.2,69.7,-61.8,44.9,-76.6Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <div class="bg-pattern bottom-10 right-10 w-96 h-96 pulse-animation hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#f87171"
                d="M37.5,-65.2C48.7,-57.8,57.8,-47.3,64.3,-35.1C70.8,-22.9,74.7,-9,75.6,5.7C76.5,20.4,74.4,36,67.1,48.6C59.8,61.2,47.3,70.8,33.2,75.7C19.1,80.6,3.4,80.8,-12.1,78.1C-27.6,75.4,-42.9,69.8,-55.3,60.2C-67.7,50.6,-77.2,37,-80.3,21.9C-83.4,6.8,-80.1,-9.8,-74.1,-25.3C-68.1,-40.8,-59.4,-55.2,-47.2,-62.2C-35,-69.2,-19.3,-68.8,-5.4,-60.5C8.5,-52.2,26.3,-72.6,37.5,-65.2Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="container mx-auto px-4 py-8 relative z-10 min-h-screen flex items-center justify-center">
        <div class="text-center max-w-4xl mx-auto">

            <!-- الرقم 403 الكبير -->
            <div class="slide-in">
                <div class="error-number gradient-text mb-8 glow-animation">
                    403
                </div>
            </div>

            <!-- العنوان الرئيسي -->
            <div class="glass-effect p-8 lg:p-12 rounded-3xl red-glow mb-8 slide-in" style="animation-delay: 0.3s;">
                <div class="relative">
                    <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold gradient-text mb-4 drop-shadow-2xl">
                        الوصول ممنوع!
                    </h1>
                    <div
                        class="absolute -top-4 lg:-top-8 -right-4 lg:-right-8 w-8 lg:w-16 h-8 lg:h-16 bg-red-400 rounded-full opacity-30 float-animation">
                    </div>
                    <div
                        class="absolute -bottom-2 lg:-bottom-4 -left-4 lg:-left-8 w-6 lg:w-12 h-6 lg:h-12 bg-red-500 rounded-full opacity-30 pulse-animation">
                    </div>
                </div>

                <p class="text-lg sm:text-xl text-gray-600 mt-6 leading-relaxed">
                    عذراً، ليس لديك الصلاحية للوصول إلى هذه الصفحة.
                    <br class="hidden sm:block">
                    إذا كنت تعتقد أن هذا خطأ، يرجى التواصل مع المدير.
                </p>

                <!-- الأيقونات المتحركة -->
                <div class="flex justify-center items-center gap-6 mt-8 mb-6">
                    <div class="icon-bounce" style="--delay: 0s;">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                    <div class="icon-bounce shake-animation" style="--delay: 0.5s;">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center text-white shadow-xl">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                    </div>
                    <div class="icon-bounce" style="--delay: 1s;">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الخيارات والأزرار -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-8">
                <!-- الصفحة الرئيسية -->
                <div class="glass-effect p-6 rounded-2xl red-glow-hover slide-in cursor-pointer"
                    style="animation-delay: 0.6s;" onclick="goHome()">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center text-white mx-auto mb-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">العودة للرئيسية</h3>
                    <p class="text-sm text-gray-600">ابدأ من جديد واستكشف محتوى الموقع</p>
                </div>

                <!-- الصفحة السابقة -->
                <div class="glass-effect p-6 rounded-2xl red-glow-hover slide-in cursor-pointer"
                    style="animation-delay: 0.8s;" onclick="goBack()">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">الصفحة السابقة</h3>
                    <p class="text-sm text-gray-600">ارجع إلى الصفحة التي كنت فيها</p>
                </div>

                <!-- التواصل مع المدير -->
                <div class="glass-effect p-6 rounded-2xl red-glow-hover slide-in cursor-pointer"
                    style="animation-delay: 1s;" onclick="contactAdmin()">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">تواصل مع المدير</h3>
                    <p class="text-sm text-gray-600">إذا كنت تعتقد أن هذا خطأ</p>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="glass-effect p-6 rounded-2xl red-glow mb-8 slide-in" style="animation-delay: 1.2s;">
                <h3 class="text-xl font-bold gradient-text mb-4">معلومات مفيدة</h3>
                <div class="text-right space-y-3">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-gray-700 font-medium">لماذا ظهرت هذه الرسالة؟</p>
                            <p class="text-sm text-gray-600 mt-1">قد تحتاج إلى صلاحيات إضافية للوصول إلى هذه الصفحة. يرجى التواصل مع مدير النظام.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-gray-700 font-medium">ما الذي يمكنني فعله؟</p>
                            <p class="text-sm text-gray-600 mt-1">يمكنك العودة إلى الصفحة الرئيسية أو التواصل مع مدير النظام لطلب الصلاحيات المطلوبة.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الأزرار الرئيسية -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 slide-in"
                style="animation-delay: 1.4s;">
                <button onclick="goHome()"
                    class="btn-primary text-white font-bold py-4 px-8 rounded-2xl shadow-xl flex items-center gap-3 text-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    العودة للرئيسية
                </button>

                <button onclick="goBack()"
                    class="btn-secondary text-red-700 font-bold py-4 px-8 rounded-2xl flex items-center gap-3 text-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    الصفحة السابقة
                </button>
            </div>

            <!-- رسالة تشجيعية -->
            <div class="mt-8 slide-in" style="animation-delay: 1.6s;">
                <p class="text-gray-500 text-sm">
                    🔒 الأمان أولاً! نحن نحمي محتوى الموقع
                </p>
            </div>
        </div>
    </div>

    <!-- سكريبت التفاعل -->
    <script>
        function goHome() {
            window.location.href = '/';
        }

        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                goHome();
            }
        }

        function contactAdmin() {
            // يمكنك تغيير هذا إلى صفحة التواصل أو البريد الإلكتروني
            window.location.href = 'mailto:admin@familytree.com?subject=طلب صلاحيات الوصول';
        }
    </script>
</body>

</html>
