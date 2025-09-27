<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - الصفحة غير موجودة | معرض صور العائلة</title>

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
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
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

        @keyframes wiggle {

            0%,
            7% {
                transform: rotateZ(0);
            }

            15% {
                transform: rotateZ(-15deg);
            }

            20% {
                transform: rotateZ(10deg);
            }

            25% {
                transform: rotateZ(-10deg);
            }

            30% {
                transform: rotateZ(6deg);
            }

            35% {
                transform: rotateZ(-4deg);
            }

            40%,
            100% {
                transform: rotateZ(0);
            }
        }

        @keyframes glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
            }

            50% {
                box-shadow: 0 0 40px rgba(34, 197, 94, 0.6);
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

        .wiggle {
            animation: wiggle 3s infinite;
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

        /* تأثير التوهج الأخضر */
        .green-glow {
            box-shadow: 0 0 40px rgba(34, 197, 94, 0.3);
        }

        .green-glow-hover:hover {
            box-shadow: 0 0 60px rgba(34, 197, 94, 0.5);
            transform: translateY(-5px);
        }

        /* تأثير النص المتدرج */
        .gradient-text {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #4ade80 100%);
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
            background: linear-gradient(135deg, #22c55e, #16a34a);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #16a34a, #15803d);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #22c55e;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-secondary:hover {
            background: #22c55e;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3);
        }

        /* تأثير الرقم 404 */
        .error-number {
            font-size: clamp(8rem, 20vw, 20rem);
            font-weight: 900;
            line-height: 0.8;
            text-shadow: 0 0 30px rgba(34, 197, 94, 0.3);
        }

        /* شريط التمرير المخصص */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f0fdf4;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e, #16a34a);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #16a34a, #15803d);
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
            background-image: radial-gradient(circle, rgba(34, 197, 94, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden bg-dots">

    <!-- عناصر الخلفية المتحركة -->
    <div class="bg-pattern top-10 left-10 w-96 h-96 float-animation hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#22c55e"
                d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3C52.5,-73.2,69.7,-61.8,44.9,-76.6Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <div class="bg-pattern bottom-10 right-10 w-96 h-96 pulse-animation hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#4ade80"
                d="M37.5,-65.2C48.7,-57.8,57.8,-47.3,64.3,-35.1C70.8,-22.9,74.7,-9,75.6,5.7C76.5,20.4,74.4,36,67.1,48.6C59.8,61.2,47.3,70.8,33.2,75.7C19.1,80.6,3.4,80.8,-12.1,78.1C-27.6,75.4,-42.9,69.8,-55.3,60.2C-67.7,50.6,-77.2,37,-80.3,21.9C-83.4,6.8,-80.1,-9.8,-74.1,-25.3C-68.1,-40.8,-59.4,-55.2,-47.2,-62.2C-35,-69.2,-19.3,-68.8,-5.4,-60.5C8.5,-52.2,26.3,-72.6,37.5,-65.2Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <div class="bg-pattern top-1/2 left-1/2 w-32 h-32 wiggle hidden lg:block" style="--delay: 1s;">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#86efac"
                d="M57.3,-68.1C72.8,-60.9,82.3,-40.6,83.3,-19.9C84.3,0.8,76.8,21.9,65.3,40.6C53.8,59.3,38.3,75.6,19.1,80.4C-0.1,85.2,-22.9,78.5,-39.3,65.5C-55.7,52.5,-65.7,33.2,-69.4,12.4C-73.1,-8.4,-70.5,-30.7,-58.9,-48.4C-47.3,-66.1,-26.7,-79.2,-4.2,-73.7C18.3,-68.2,36.6,-44.1,57.3,-68.1Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="container mx-auto px-4 py-8 relative z-10 min-h-screen flex items-center justify-center">
        <div class="text-center max-w-4xl mx-auto">

            <!-- الرقم 404 الكبير -->
            <div class="slide-in">
                <div class="error-number gradient-text mb-8 glow-animation">
                    404
                </div>
            </div>

            <!-- العنوان الرئيسي -->
            <div class="glass-effect p-8 lg:p-12 rounded-3xl green-glow mb-8 slide-in" style="animation-delay: 0.3s;">
                <div class="relative">
                    <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold gradient-text mb-4 drop-shadow-2xl">
                        عذراً، الصفحة غير موجودة!
                    </h1>
                    <div
                        class="absolute -top-4 lg:-top-8 -right-4 lg:-right-8 w-8 lg:w-16 h-8 lg:h-16 bg-green-400 rounded-full opacity-30 float-animation">
                    </div>
                    <div
                        class="absolute -bottom-2 lg:-bottom-4 -left-4 lg:-left-8 w-6 lg:w-12 h-6 lg:h-12 bg-green-500 rounded-full opacity-30 pulse-animation">
                    </div>
                </div>

                <p class="text-lg sm:text-xl text-gray-600 mt-6 leading-relaxed">
                    يبدو أن الصفحة التي تبحث عنها قد اختفت أو تم نقلها إلى مكان آخر.
                    <br class="hidden sm:block">
                    لا تقلق، دعنا نساعدك في العثور على ما تريده!
                </p>

                <!-- الأيقونات المتحركة -->
                <div class="flex justify-center items-center gap-6 mt-8 mb-6">
                    <div class="icon-bounce" style="--delay: 0s;">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd"
                                    d="M4 5a2 2 0 012-2 1 1 0 000 2H4a1 1 0 00-1 1v7a1 1 0 001 1h12a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 100-2 2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="icon-bounce" style="--delay: 0.5s;">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center text-white shadow-xl">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="icon-bounce" style="--delay: 1s;">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الخيارات والأزرار -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-8">
                <!-- البحث -->
                <div class="glass-effect p-6 rounded-2xl green-glow-hover slide-in" style="animation-delay: 0.6s;">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">ابحث في الموقع</h3>
                    <p class="text-sm text-gray-600 mb-4">جرب البحث عن المحتوى الذي تريده</p>
                    <div class="relative">
                        <input type="text" placeholder="ابحث هنا..."
                            class="w-full px-4 py-2 pr-10 bg-white/70 border-2 border-green-200 rounded-lg text-sm focus:ring-2 focus:ring-green-300 focus:border-green-500 transition-all duration-300">
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-green-500"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- الصفحة الرئيسية -->
                <div class="glass-effect p-6 rounded-2xl green-glow-hover slide-in cursor-pointer"
                    style="animation-delay: 0.8s;" onclick="goHome()">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white mx-auto mb-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">العودة للرئيسية</h3>
                    <p class="text-sm text-gray-600">ابدأ من جديد واستكشف محتوى الموقع</p>
                </div>

                <!-- المساعدة -->
                <div class="glass-effect p-6 rounded-2xl green-glow-hover slide-in cursor-pointer"
                    style="animation-delay: 1s;" onclick="getHelp()">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">تحتاج مساعدة؟</h3>
                    <p class="text-sm text-gray-600">تواصل معنا إذا كنت تواجه مشكلة</p>
                </div>
            </div>

            <!-- الروابط السريِّع ة -->
            <div class="glass-effect p-6 rounded-2xl green-glow mb-8 slide-in" style="animation-delay: 1.2s;">
                <h3 class="text-xl font-bold gradient-text mb-4">روابط سريعة</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="#"
                        class="flex flex-col items-center p-4 rounded-xl bg-white/50 hover:bg-green-50 transition-all duration-300 hover:scale-105">
                        <svg class="w-8 h-8 text-green-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">معرض الصور</span>
                    </a>
                    <a href="#"
                        class="flex flex-col items-center p-4 rounded-xl bg-white/50 hover:bg-green-50 transition-all duration-300 hover:scale-105">
                        <svg class="w-8 h-8 text-green-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2 1 1 0 000 2H4a1 1 0 00-1 1v7a1 1 0 001 1h12a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 100-2 2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">المقالات</span>
                    </a>
                    <a href="#"
                        class="flex flex-col items-center p-4 rounded-xl bg-white/50 hover:bg-green-50 transition-all duration-300 hover:scale-105">
                        <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">العائلة</span>
                    </a>
                    <a href="#"
                        class="flex flex-col items-center p-4 rounded-xl bg-white/50 hover:bg-green-50 transition-all duration-300 hover:scale-105">
                        <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">تواصل معنا</span>
                    </a>
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
                    class="btn-secondary text-green-700 font-bold py-4 px-8 rounded-2xl flex items-center gap-3 text-lg">
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
                    💚 لا تيأس! كل ضياع يقود إلى اكتشاف جديد
                </p>
            </div>
        </div>
    </div>

    <!-- سكريبت التفاعل -->
    <script>
        function goHome() {
            // يمكنك تغيير هذا الرابط إلى الصفحة الرئيسية لموقعك
            window.location.href = '/';
        }

        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                goHome();
            }
        }

        function getHelp() {
            // يمكنك تغيير هذا إلى صفحة الدعم أو نموذج التواصل
            alert('سيتم فتح صفحة المساعدة قريباً!');
        }

        function reportIssue() {
            // يمكنك تغيير هذا إلى صفحة الإبلاغ عن مشكلة
            alert('سيتم فتح صفحة الإبلاغ عن مشكلة قريباً!');
        }
    </script>
</body>

</html>
