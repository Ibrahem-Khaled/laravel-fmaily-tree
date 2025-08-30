<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - معرض صور العائلة</title>

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

        /* المحتوى النصي */
        .article-content {
            font-family: 'Tajawal', sans-serif;
            line-height: 2;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content h2 {
            font-size: 1.75rem;
            font-weight: bold;
            margin: 2rem 0 1rem 0;
            color: #16a34a;
        }

        .article-content h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 1.5rem 0 0.75rem 0;
            color: #22c55e;
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

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .pulse-animation {
            animation: pulse-soft 4s ease-in-out infinite;
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

        /* تأثير النص المتدرج */
        .gradient-text {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #4ade80 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* معرض الصور */
        .image-grid {
            display: grid;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .image-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .image-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }
        }

        /* تأثير الصور */
        .image-container {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .image-container:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s ease;
        }

        .image-container:hover img {
            transform: scale(1.15);
        }

        /* Lightbox */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }

        .lightbox.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .lightbox-content {
            position: relative;
            max-width: 90vw;
            max-height: 90vh;
        }

        .lightbox-image {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 0.5rem;
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            border: none;
            padding: 1rem;
            cursor: pointer;
            border-radius: 50%;
            transition: all 0.3s;
            font-size: 1.5rem;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-nav:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }

        .lightbox-prev {
            left: 2rem;
        }

        .lightbox-next {
            right: 2rem;
        }

        .lightbox-close {
            position: absolute;
            top: 2rem;
            right: 2rem;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            border: none;
            padding: 0.75rem;
            cursor: pointer;
            border-radius: 50%;
            transition: all 0.3s;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-close:hover {
            background: white;
            transform: rotate(90deg);
        }

        .image-counter {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 2rem;
            font-size: 0.9rem;
        }

        /* رسومات الخلفية */
        .bg-pattern {
            position: fixed;
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }

        /* شريط التقدم للقراءة */
        .reading-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: linear-gradient(90deg, #22c55e, #4ade80);
            z-index: 1000;
            transition: width 0.3s ease;
        }

        /* أزرار المشاركة */
        .share-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            cursor: pointer;
        }

        .share-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        /* شارات/أوسمة - تصميم خاص */
        .badge-card {
            position: relative;
            overflow: hidden;
        }

        .badge-ribbon {
            position: absolute;
            top: 0.75rem;
            left: -2.5rem;
            transform: rotate(-30deg);
            color: white;
            padding: 0.35rem 3rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .tier-gold {
            background: linear-gradient(135deg, #f59e0b, #fbbf24, #f59e0b);
        }

        .tier-silver {
            background: linear-gradient(135deg, #9ca3af, #d1d5db, #9ca3af);
        }

        .tier-bronze {
            background: linear-gradient(135deg, #b45309, #d97706, #b45309);
        }

        .tier-chip {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
            font-weight: 700;
        }

        .tier-chip.gold {
            background: #fff7ed;
            color: #b45309;
            border: 1px solid #fdba74;
        }

        .tier-chip.silver {
            background: #f8fafc;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .tier-chip.bronze {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .grade-chip {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 0.75rem;
            font-weight: 800;
        }

        .grade-S {
            background: #ecfeff;
            color: #0e7490;
            border: 1px solid #a5f3fc;
        }

        .grade-A {
            background: #ecfccb;
            color: #3f6212;
            border: 1px solid #bef264;
        }

        .grade-B {
            background: #fef9c3;
            color: #854d0e;
            border: 1px solid #fde68a;
        }

        .badge-icon {
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
        }

        /* حلقة تقدم دائرية */
        .ring {
            --val: 0;
            width: 76px;
            height: 76px;
            padding: 8px;
            border-radius: 9999px;
            background:
                radial-gradient(closest-side, white 66%, transparent 67% 100%),
                conic-gradient(#22c55e calc(var(--val) * 1%), #e5e7eb 0);
        }

        .ring-inner {
            width: 100%;
            height: 100%;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #16a34a;
        }

        /* أدوات التصفية */
        .filter-btn {
            border: 1px solid #d1fae5;
        }

        .filter-btn.active {
            background: linear-gradient(90deg, #22c55e, #16a34a);
            color: #fff;
            border-color: transparent;
        }

        /* تحسينات الموبايل */
        @media (max-width: 768px) {
            .bg-pattern {
                display: none;
            }

            .lightbox-nav {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .lightbox-prev {
                left: 1rem;
            }

            .lightbox-next {
                right: 1rem;
            }

            .article-content {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    <!-- شريط التقدم للقراءة -->
    <div class="reading-progress" id="readingProgress"></div>

    <!-- عناصر زخرفية في الخلفية -->
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

    <div class="container mx-auto px-4 py-4 lg:py-8 relative z-10 max-w-6xl">

        <!-- زر الرجوع -->
        <div class="mb-6">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 px-4 py-2 glass-effect rounded-full hover:bg-white/95 transition-all duration-300 group">
                <svg class="w-5 h-5 text-green-600 transform group-hover:-translate-x-1 transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="text-gray-700 font-medium">العودة للخلف</span>
            </a>
        </div>

        <!-- المحتوى الرئيسي -->
        <article class="glass-effect rounded-3xl overflow-hidden green-glow">

            <!-- رأس المقال -->
            <header class="p-6 lg:p-10 bg-gradient-to-br from-green-50 to-green-100/50">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold gradient-text mb-4 leading-relaxed">
                    {{ $article->title }}
                </h1>

                <!-- معلومات المقال -->
                <div class="flex flex-wrap items-center gap-4 text-sm lg:text-base text-gray-600">
                    @if ($article->person)
                        <div class="flex items-center gap-2">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold">
                                {{ mb_substr($article->person->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $article->person->name }}</p>
                                <p class="text-xs text-gray-500">المساهم</p>
                            </div>
                        </div>
                    @endif

                    @if ($article->category)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                {{ $article->category->name }}
                            </span>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-500">{{ $article->created_at->format('d/m/Y') }}</span>
                    </div>

                    @if ($article->images->count() > 0)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-500">{{ $article->images->count() }} صورة</span>
                        </div>
                    @endif
                </div>
            </header>

            <!-- قسم الأوسمة المبهِر -->
            @php
                // لو مفيش أوسمة جاية من الكنترولر، هنحط مجموعة وهمية بشكل ذكي
                $badges = isset($badges)
                    ? collect($badges)
                    : collect([
                        [
                            'title' => 'مُصوِّر العائلة المُبدع',
                            'tier' => 'gold', // gold | silver | bronze
                            'level' => 85, // 0..100
                            'graded' => true,
                            'grade' => 'A', // S | A | B
                            'desc' => 'إسهام مميز في توثيق لحظات العائلة بلقطات إبداعية ذات جودة عالية.',
                        ],
                        [
                            'title' => 'حافظ الذكريات',
                            'tier' => 'silver',
                            'level' => 60,
                            'graded' => false,
                            'desc' => 'تنظيم ورفع الأرشيف القديم وإضافة وصف وصناديق معلومات لكل صورة.',
                        ],
                        [
                            'title' => 'مُنسِّق الألبومات',
                            'tier' => 'bronze',
                            'level' => 45,
                            'graded' => false,
                            'desc' => 'إنشاء ألبومات موضوعية وربطها بالأماكن والأشخاص والتواريخ.',
                        ],
                        [
                            'title' => 'سفير التراث',
                            'tier' => 'gold',
                            'level' => 90,
                            'graded' => true,
                            'grade' => 'S',
                            'desc' => 'الحفاظ على قصص العائلة القديمة ومشاركتها بأسلوب جذاب.',
                        ],
                        [
                            'title' => 'خبير التلوين',
                            'tier' => 'silver',
                            'level' => 72,
                            'graded' => true,
                            'grade' => 'B',
                            'desc' => 'ترميم الصور بالأبيض والأسود وتلوينها بشكل احترافي.',
                        ],
                    ]);
            @endphp

            <section
                class="px-6 lg:px-10 py-8 bg-gradient-to-t from-green-50/40 to-transparent border-t border-green-100">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <h2 class="text-2xl lg:text-3xl font-bold gradient-text flex items-center gap-3">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0H9m6 0v1a3 3 0 11-6 0v-1" />
                        </svg>
                        أوسمة وإنجازات
                    </h2>

                    <!-- أدوات التصفية -->
                    <div class="flex flex-wrap items-center gap-2">
                        <button class="filter-btn active px-3 py-1 rounded-full text-sm" data-filter="all">الكل</button>
                        <button class="filter-btn px-3 py-1 rounded-full text-sm" data-filter="gold">ذهبي</button>
                        <button class="filter-btn px-3 py-1 rounded-full text-sm" data-filter="silver">فضي</button>
                        <button class="filter-btn px-3 py-1 rounded-full text-sm" data-filter="bronze">برونزي</button>
                        <button class="filter-btn px-3 py-1 rounded-full text-sm" data-filter="graded">بدرجات</button>
                    </div>
                </div>

                <div id="badgesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($badges as $b)
                        @php
                            $tier = $b['tier'];
                            $tierName = ['gold' => 'ذهبي', 'silver' => 'فضي', 'bronze' => 'برونزي'][$tier] ?? 'مستوى';
                            $tierChip = ['gold' => 'gold', 'silver' => 'silver', 'bronze' => 'bronze'][$tier] ?? 'gold';
                            $level = max(0, min(100, (int) ($b['level'] ?? 0)));
                            $graded = (bool) ($b['graded'] ?? false);
                            $grade = $b['grade'] ?? null;
                        @endphp

                        <div class="badge-card glass-effect rounded-2xl p-5 green-glow-hover"
                            data-tier="{{ $tier }}" data-graded="{{ $graded ? 'true' : 'false' }}">

                            <!-- شريط شريطي مائل بالرُتبة -->
                            <div
                                class="badge-ribbon {{ $tier === 'gold' ? 'tier-gold' : ($tier === 'silver' ? 'tier-silver' : 'tier-bronze') }}">
                                {{ $tierName }}
                            </div>

                            <div class="flex items-start gap-4">
                                <!-- أيقونة وسام (إكليل/كأس) -->
                                <div class="shrink-0">
                                    <div
                                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-green-600 badge-icon" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path
                                                d="M5 3a1 1 0 00-1 1v3a5 5 0 004 4.9V14H6a2 2 0 100 4h12a2 2 0 100-4h-2v-2.1A5 5 0 0020 7V4a1 1 0 00-1-1H5zm1 2h12v2a3 3 0 11-6 0H6V5z" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- بيانات الوسام -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-lg font-extrabold text-gray-800">{{ $b['title'] }}</h3>
                                        <span class="tier-chip {{ $tierChip }}">{{ $tierName }}</span>
                                        @if ($graded && $grade)
                                            <span class="grade-chip grade-{{ $grade }}">درجة
                                                {{ $grade }}</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-600 mt-1 text-sm">{{ $b['desc'] ?? '' }}</p>

                                    <!-- تقدم المستوى -->
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="ring" style="--val: {{ $level }}">
                                            <div class="ring-inner">{{ $level }}%</div>
                                        </div>

                                        <!-- نجوم بسيطة حسب المستوى -->
                                        @php $stars = (int) round($level/33); @endphp
                                        <div class="flex items-center gap-1" aria-label="تقييم تقريبي">
                                            @for ($i = 1; $i <= 3; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $stars ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.802 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.802-2.034a1 1 0 00-1.175 0l-2.802 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- ملاحظة صغيرة -->
                <p class="text-xs text-gray-500 mt-4">* الأوسمة المعروضة افتراضية لعرض التصميم ويمكن استبدالها ببيانات
                    حقيقية من قاعدة البيانات.</p>
            </section>

            <!-- محتوى المقال -->
            @if ($article->content)
                <div class="p-6 lg:p-10">
                    <div class="article-content text-gray-700 leading-relaxed">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                </div>
            @endif

            <!-- معرض الصور -->
            @if ($article->images->count() > 0)
                <div class="p-6 lg:p-10 bg-gradient-to-t from-green-50/30 to-transparent">
                    <h2 class="text-2xl lg:text-3xl font-bold gradient-text mb-6 flex items-center gap-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                clip-rule="evenodd" />
                        </svg>
                        معرض الصور
                        <span class="text-sm font-normal text-gray-500">({{ $article->images->count() }} صورة)</span>
                    </h2>

                    <div class="image-grid">
                        @foreach ($article->images as $index => $image)
                            <div class="image-container glass-effect p-2"
                                onclick="openLightbox({{ $index }})">
                                <div class="aspect-square overflow-hidden rounded-lg">
                                    <img src="{{ asset('storage/' . $image->path) }}"
                                        alt="{{ $image->name ?? 'صورة من المقال' }}"
                                        class="w-full h-full object-cover">
                                </div>
                                @if ($image->name)
                                    <p class="mt-2 text-sm text-gray-600 text-center px-2">{{ $image->name }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- تذييل المقال -->
            <footer class="p-6 lg:p-10 bg-gradient-to-r from-green-50 to-green-100/50 border-t border-green-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        العودة للخلف
                    </a>
                </div>
            </footer>
        </article>

        <!-- مقالات ذات صلة -->
        @if (isset($relatedArticles) && $relatedArticles->count() > 0)
            <section class="mt-12">
                <h3 class="text-2xl lg:text-3xl font-bold gradient-text mb-6 text-center">مقالات ذات صلة</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($relatedArticles->take(3) as $related)
                        <a href="{{ url('/article/' . $related->id) }}"
                            class="glass-effect rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105 block">
                            @if ($related->images->first())
                                <div
                                    class="aspect-video overflow-hidden bg-gradient-to-br from-green-100 to-green-200">
                                    <img src="{{ asset('storage/' . $related->images->first()->path) }}"
                                        alt="{{ $related->title }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div
                                    class="aspect-video bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                            <div class="p-4">
                                <h4 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $related->title }}
                                </h4>
                                @if ($related->person)
                                    <p class="text-sm text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $related->person->name }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
        <div class="lightbox-content">
            <img class="lightbox-image" id="lightboxImage" alt="الصور">
            <button class="lightbox-nav lightbox-prev" onclick="prevImage()" aria-label="السابق">&#8249;</button>
            <button class="lightbox-nav lightbox-next" onclick="nextImage()" aria-label="التالي">&#8250;</button>
            <button class="lightbox-close" onclick="closeLightbox()" aria-label="إغلاق">✕</button>
            <div class="image-counter" id="imageCounter">1 / 1</div>
        </div>
    </div>

    <script>
        // شريط التقدم للقراءة
        const progress = document.getElementById('readingProgress');
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const percentage = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            progress.style.width = percentage + '%';
        });

        // Lightbox
        const images = [
            @if ($article->images && $article->images->count() > 0)
                @foreach ($article->images as $im)
                    "{{ asset('storage/' . $im->path) }}",
                @endforeach
            @endif
        ];
        let currentIndex = 0;
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightboxImage');
        const imageCounter = document.getElementById('imageCounter');

        function openLightbox(i) {
            currentIndex = i;
            updateLightbox();
            lightbox.classList.add('active');
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateLightbox();
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % images.length;
            updateLightbox();
        }

        function updateLightbox() {
            lightboxImg.src = images[currentIndex];
            imageCounter.textContent = `${currentIndex + 1} / ${images.length}`;
        }

        // تصفية الأوسمة
        const filterButtons = document.querySelectorAll('.filter-btn');
        const badgesGrid = document.getElementById('badgesGrid');
        const badgeCards = badgesGrid ? Array.from(badgesGrid.children) : [];

        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const f = btn.getAttribute('data-filter');
                badgeCards.forEach(card => {
                    const tier = card.getAttribute('data-tier');
                    const graded = card.getAttribute('data-graded') === 'true';
                    let show = true;
                    if (f === 'graded') show = graded;
                    else if (f !== 'all') show = (tier === f);
                    card.style.display = show ? '' : 'none';
                });
            });
        });
    </script>
</body>

</html>
