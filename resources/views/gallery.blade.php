<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معرض صور العائلة</title>

    {{-- قم بتضمين ملف Tailwind CSS الخاص بمشروعك --}}
    {{-- مثال: <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <script src="https://cdn.tailwindcss.com"></script> {{-- للاختبار السريع فقط --}}

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

        /* تأثير البطاقات */
        .card-hover {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: scale(1.02) translateY(-8px);
        }

        /* رسومات الخلفية */
        .bg-pattern {
            position: fixed;
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }

        /* تحسينات الموبايل */
        @media (max-width: 768px) {
            .bg-pattern {
                display: none;
            }
        }

        /* تأثير الشريط الجانبي المنزلق */
        .slide-panel {
            transition: transform 0.3s ease-in-out;
        }

        .slide-panel.closed {
            transform: translateX(100%);
        }

        @media (min-width: 1024px) {
            .slide-panel.closed {
                transform: translateX(0);
            }
        }

        /* نافذة منبثقة */
        .modal-backdrop {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* صورة بملء الشاشة */
        .fullscreen-image {
            max-height: 90vh;
            max-width: 90vw;
            object-fit: contain;
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">

    <!-- عناصر زخرفية في الخلفية (مخفية على الموبايل) -->
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

    <div class="container mx-auto px-4 py-4 lg:py-8 relative z-10">

        <!-- الهيدر المبدع (محسّن للموبايل) -->
        <header class="text-center mb-8 lg:mb-16 relative">
            <div class="inline-block relative">
                <h1 class="text-3xl sm:text-5xl md:text-7xl font-bold gradient-text mb-2 lg:mb-4 drop-shadow-2xl">
                    معرض صور العائلة
                </h1>
                <div
                    class="absolute -top-4 lg:-top-8 -right-4 lg:-right-8 w-8 lg:w-16 h-8 lg:h-16 bg-green-400 rounded-full opacity-30 float-animation">
                </div>
                <div
                    class="absolute -bottom-2 lg:-bottom-4 -left-4 lg:-left-8 w-6 lg:w-12 h-6 lg:h-12 bg-green-500 rounded-full opacity-30 pulse-animation">
                </div>
            </div>
            <p class="text-base sm:text-xl text-gray-600 mt-2 lg:mt-4 font-light">
                <span class="inline-block px-4 lg:px-6 py-1 lg:py-2 glass-effect rounded-full">
                    ✨ ذكرياتنا الجميلة في مكان واحد ✨
                </span>
            </p>
        </header>

        <!-- زر الفلترة للموبايل -->
        <div class="lg:hidden mb-4">
            <button onclick="toggleFilter()"
                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 px-4 rounded-2xl
                           shadow-lg flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                    </path>
                </svg>
                <span>فتح الفلاتر</span>
            </button>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            <!-- الشريط الجانبي (مع إمكانية الإخفاء على الموبايل) -->
            <aside class="w-full lg:w-1/4">
                <!-- نافذة الفلتر للموبايل -->
                <div id="filterPanel" class="fixed inset-0 z-50 lg:relative lg:inset-auto hidden lg:block">
                    <div class="absolute inset-0 bg-black/50 lg:hidden" onclick="toggleFilter()"></div>
                    <div
                        class="slide-panel absolute right-0 top-0 h-full w-80 max-w-[85vw] lg:relative lg:w-full bg-white lg:bg-transparent p-4 lg:p-0 overflow-y-auto lg:overflow-visible">
                        <div class="glass-effect p-4 lg:p-6 rounded-3xl green-glow lg:card-hover">
                            <!-- زر الإغلاق للموبايل -->
                            <div class="flex items-center justify-between mb-4 lg:mb-6 border-b border-green-200 pb-4">
                                <h3 class="text-xl lg:text-2xl font-bold gradient-text">فلترة النتائج</h3>
                                <button onclick="toggleFilter()"
                                    class="lg:hidden p-2 rounded-full bg-red-500 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <form action="{{ url('/gallery') }}" method="GET">
                                <!-- الأقسام -->
                                <div class="mb-6">
                                    <label for="category"
                                        class="flex items-center gap-2 text-base lg:text-lg font-semibold mb-3 lg:mb-4 text-gray-700">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        الأقسام
                                    </label>
                                    <ul class="space-y-2 max-h-60 lg:max-h-none overflow-y-auto lg:overflow-visible">
                                        <li>
                                            <a href="{{ url('/gallery') }}"
                                                class="block px-3 lg:px-4 py-2 lg:py-3 rounded-xl lg:rounded-2xl transition-all duration-300 font-medium text-sm lg:text-base
                                                      {{ !$currentCategory ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg scale-105' : 'bg-white/70 hover:bg-green-50 hover:scale-105 hover:shadow-md' }}">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd"
                                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    عرض الكل
                                                </span>
                                            </a>
                                        </li>
                                        @foreach ($categories as $category)
                                            <li>
                                                <a href="{{ url('/gallery?category=' . $category->id) }}"
                                                    class="block px-3 lg:px-4 py-2 lg:py-3 rounded-xl lg:rounded-2xl transition-all duration-300 font-medium text-sm lg:text-base
                                                          {{ $currentCategory == $category->id ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg scale-105' : 'bg-white/70 hover:bg-green-50 hover:scale-105 hover:shadow-md' }}">
                                                    {{ $category->name }}
                                                </a>
                                                @if ($category->children->isNotEmpty())
                                                    <ul class="pr-4 lg:pr-6 mt-2 space-y-2">
                                                        @foreach ($category->children as $child)
                                                            <li>
                                                                <a href="{{ url('/gallery?category=' . $child->id) }}"
                                                                    class="block px-3 lg:px-4 py-1.5 lg:py-2 rounded-lg lg:rounded-xl transition-all duration-300 text-xs lg:text-sm
                                                                          {{ $currentCategory == $child->id ? 'bg-gradient-to-r from-green-400 to-green-500 text-white shadow-md scale-105' : 'bg-white/50 hover:bg-green-50 hover:scale-105' }}">
                                                                    ↳ {{ $child->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- المساهم -->
                                <div class="mb-6">
                                    <label for="person"
                                        class="flex items-center gap-2 text-base lg:text-lg font-semibold mb-3 lg:mb-4 text-gray-700">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        المساهم
                                    </label>
                                    <select name="person" id="person"
                                        class="w-full p-3 lg:p-4 bg-white/70 border-2 border-green-200 rounded-xl lg:rounded-2xl shadow-sm text-sm lg:text-base
                                                   focus:ring-4 focus:ring-green-300 focus:border-green-500 transition-all duration-300
                                                   hover:border-green-400">
                                        <option value="">كل المساهمين</option>
                                        @foreach ($authors as $author)
                                            <option value="{{ $author->id }}"
                                                {{ $currentAuthor == $author->id ? 'selected' : '' }}>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- أزرار الفلترة -->
                                <div class="flex flex-col space-y-3">
                                    <button type="submit"
                                        class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 lg:py-4 px-4 rounded-xl lg:rounded-2xl
                                                   hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg
                                                   transform hover:scale-105 active:scale-95 text-sm lg:text-base">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-4 lg:w-5 h-4 lg:h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            تطبيق الفلتر
                                        </span>
                                    </button>
                                    <a href="{{ url('/gallery') }}"
                                        class="w-full text-center bg-gradient-to-r from-gray-500 to-gray-600 text-white font-bold py-3 lg:py-4 px-4 rounded-xl lg:rounded-2xl
                                              hover:from-gray-600 hover:to-gray-700 transition-all duration-300 shadow-lg
                                              transform hover:scale-105 active:scale-95 text-sm lg:text-base">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-4 lg:w-5 h-4 lg:h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            مسح الفلتر
                                        </span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- المحتوى الرئيسي -->
            <main class="w-full lg:w-3/4">
                @if ($images->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
                        @foreach ($images as $image)
                            <div onclick="showImageOptions({{ json_encode([
                                'id' => $image->id,
                                'path' => asset('storage/' . $image->path),
                                'title' => $image->article->title ?? 'بدون عنوان',
                                'author' => $image->article->person->name ?? '',
                                'category' => $image->article->category->name ?? '',
                                'article_id' => $image->article->id ?? null,
                            ]) }})"
                                class="group relative overflow-hidden rounded-xl sm:rounded-2xl lg:rounded-3xl shadow-lg lg:shadow-xl cursor-pointer lg:green-glow-hover transition-all duration-500">
                                <!-- حاوية الصورة -->
                                <div
                                    class="aspect-square overflow-hidden bg-gradient-to-br from-green-100 to-green-200">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="صورة من الأرشيف"
                                        class="w-full h-full object-cover transition-all duration-700
                                                group-hover:scale-110 lg:group-hover:scale-125 lg:group-hover:rotate-3">
                                </div>

                                <!-- التأثير الزجاجي عند التمرير -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent
                                            opacity-0 group-hover:opacity-100 transition-all duration-500">
                                </div>

                                <!-- أيقونة العرض -->
                                <div
                                    class="absolute top-2 right-2 lg:top-4 lg:right-4 w-8 h-8 lg:w-12 lg:h-12 bg-white/90 rounded-full flex items-center justify-center
                                            transform -translate-y-20 group-hover:translate-y-0 transition-all duration-500 delay-100">
                                    <svg class="w-4 h-4 lg:w-6 lg:h-6 text-green-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>

                                <!-- معلومات الصورة -->
                                <div
                                    class="absolute bottom-0 left-0 right-0 p-3 lg:p-5 text-white
                                            transform translate-y-full group-hover:translate-y-0 transition-all duration-500">
                                    @if ($image->article && $image->article->person)
                                        <h4 class="font-bold text-sm lg:text-lg mb-1 drop-shadow-lg line-clamp-1">
                                            {{ $image->article->title ?? 'بدون عنوان' }}
                                        </h4>
                                        <div
                                            class="flex items-center gap-1 lg:gap-2 text-xs lg:text-sm text-green-200">
                                            <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="line-clamp-1">{{ $image->article->person->name }}</span>
                                        </div>
                                        @if ($image->article->category)
                                            <div class="mt-1 lg:mt-2">
                                                <span
                                                    class="inline-block px-2 lg:px-3 py-0.5 lg:py-1 bg-green-500/30 backdrop-blur-sm rounded-full text-xs">
                                                    #{{ $image->article->category->name }}
                                                </span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 lg:mt-16 flex justify-center">
                        <div class="glass-effect px-4 lg:px-8 py-2 lg:py-4 rounded-full green-glow">
                            {{ $images->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <!-- حالة عدم وجود صور -->
                    <div
                        class="flex flex-col items-center justify-center min-h-[400px] lg:min-h-[500px] glass-effect rounded-2xl lg:rounded-3xl p-8 lg:p-16 green-glow">
                        <div class="relative">
                            <div class="absolute inset-0 bg-green-400 rounded-full opacity-20 blur-3xl animate-pulse">
                            </div>
                            <svg class="w-20 h-20 lg:w-32 lg:h-32 text-green-500 relative z-10" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl lg:text-3xl font-bold gradient-text mt-6 lg:mt-8">لا توجد صور لعرضها</h3>
                        <p class="text-gray-600 mt-3 lg:mt-4 text-sm lg:text-lg text-center max-w-md">
                            حاول تغيير شروط الفلترة أو قم بإضافة صور جديدة لإثراء معرض الذكريات
                        </p>
                        <a href="{{ url('/gallery') }}"
                            class="mt-6 lg:mt-8 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 lg:py-4 px-6 lg:px-8 rounded-xl lg:rounded-2xl
                                  hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-xl
                                  transform hover:scale-105 active:scale-95 flex items-center gap-2 text-sm lg:text-base">
                            <svg class="w-4 lg:w-5 h-4 lg:h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            العودة للمعرض الرئيسي
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <!-- نافذة الخيارات المنبثقة -->
    <div id="imageOptionsModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-backdrop absolute inset-0" onclick="closeImageOptions()"></div>
        <div
            class="modal-content absolute bottom-0 left-0 right-0 md:relative md:inset-0 md:m-auto md:max-w-md bg-white rounded-t-3xl md:rounded-3xl p-6 md:p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl md:text-2xl font-bold gradient-text">خيارات الصورة</h3>
                <button onclick="closeImageOptions()"
                    class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- معاينة الصورة -->
            <div class="mb-6">
                <img id="modalImagePreview" src="" alt=""
                    class="w-full h-48 md:h-64 object-cover rounded-2xl">
            </div>

            <!-- معلومات الصورة -->
            <div class="mb-6 space-y-2">
                <h4 id="modalImageTitle" class="font-bold text-lg text-gray-800"></h4>
                <div id="modalImageAuthor" class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                    <span></span>
                </div>
                <div id="modalImageCategory"
                    class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium"></div>
            </div>

            <!-- أزرار الإجراءات -->
            <div class="flex flex-col gap-3">
                <button onclick="viewFullscreen()"
                    class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-3 px-4 rounded-2xl
                               hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg
                               transform hover:scale-105 active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                        </path>
                    </svg>
                    عرض الصورة بالحجم الكامل
                </button>

                <button id="viewArticleBtn" onclick="viewArticle()"
                    class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 px-4 rounded-2xl
                               hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg
                               transform hover:scale-105 active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    عرض تفاصيل الصورة
                </button>
            </div>
        </div>
    </div>

    <!-- نافذة عرض الصورة بالحجم الكامل -->
    <div id="fullscreenModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-backdrop absolute inset-0 flex items-center justify-center p-4" onclick="closeFullscreen()">
            <button onclick="closeFullscreen()"
                class="absolute top-4 right-4 z-10 p-3 rounded-full bg-white/90 hover:bg-white transition">
                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <img id="fullscreenImage" src="" alt="" class="fullscreen-image rounded-lg shadow-2xl">
        </div>
    </div>

    <!-- JavaScript للتفاعلات -->
    <script>
        let currentImageData = null;

        // تبديل الفلتر على الموبايل
        function toggleFilter() {
            const filterPanel = document.getElementById('filterPanel');
            const slidePanel = filterPanel.querySelector('.slide-panel');

            if (filterPanel.classList.contains('hidden')) {
                filterPanel.classList.remove('hidden');
                setTimeout(() => {
                    slidePanel.classList.remove('closed');
                }, 10);
            } else {
                slidePanel.classList.add('closed');
                setTimeout(() => {
                    filterPanel.classList.add('hidden');
                }, 300);
            }
        }

        // عرض خيارات الصورة
        function showImageOptions(imageData) {
            currentImageData = imageData;
            const modal = document.getElementById('imageOptionsModal');

            // تحديث معلومات الصورة في النافذة المنبثقة
            document.getElementById('modalImagePreview').src = imageData.path;
            document.getElementById('modalImageTitle').textContent = imageData.title;
            document.getElementById('modalImageAuthor').querySelector('span').textContent = imageData.author || 'غير محدد';

            const categoryElement = document.getElementById('modalImageCategory');
            if (imageData.category) {
                categoryElement.textContent = '#' + imageData.category;
                categoryElement.style.display = 'inline-block';
            } else {
                categoryElement.style.display = 'none';
            }

            // إظهار/إخفاء زر المقال
            const articleBtn = document.getElementById('viewArticleBtn');
            if (imageData.article_id) {
                articleBtn.style.display = 'flex';
            } else {
                articleBtn.style.display = 'none';
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.modal-content').style.transform = 'translateY(0)';
            }, 10);
        }

        // إغلاق خيارات الصورة
        function closeImageOptions() {
            const modal = document.getElementById('imageOptionsModal');
            modal.querySelector('.modal-content').style.transform = 'translateY(100%)';
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // عرض الصورة بالحجم الكامل
        function viewFullscreen() {
            if (currentImageData) {
                document.getElementById('fullscreenImage').src = currentImageData.path;
                document.getElementById('fullscreenModal').classList.remove('hidden');
                closeImageOptions();
            }
        }

        // إغلاق الصورة بالحجم الكامل
        function closeFullscreen() {
            document.getElementById('fullscreenModal').classList.add('hidden');
        }

        // عرض المقال
        function viewArticle() {
            if (currentImageData && currentImageData.article_id) {
                window.location.href = "{{ url('/article') }}/" + currentImageData.article_id;
            }
        }

        // تأثيرات تفاعلية إضافية
        document.addEventListener('DOMContentLoaded', function() {
            // إغلاق النوافذ بالضغط على ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImageOptions();
                    closeFullscreen();
                    const filterPanel = document.getElementById('filterPanel');
                    if (!filterPanel.classList.contains('hidden') && window.innerWidth < 1024) {
                        toggleFilter();
                    }
                }
            });

            // تأثير الماوس على البطاقات (للأجهزة الكبيرة)
            if (window.innerWidth >= 1024) {
                const cards = document.querySelectorAll('.group');
                cards.forEach(card => {
                    card.addEventListener('mousemove', (e) => {
                        const rect = card.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;

                        card.style.setProperty('--mouse-x', `${x}px`);
                        card.style.setProperty('--mouse-y', `${y}px`);
                    });
                });
            }

            // تحسين أداء التمرير على الموبايل
            let ticking = false;

            function updateScrolling() {
                ticking = false;
            }

            document.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(updateScrolling);
                    ticking = true;
                }
            });
        });

        // منع التمرير عند فتح النوافذ المنبثقة
        function preventScroll(prevent) {
            if (prevent) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // تحديث دوال الفتح والإغلاق
        const originalShowImageOptions = showImageOptions;
        showImageOptions = function(imageData) {
            preventScroll(true);
            originalShowImageOptions(imageData);
        };

        const originalCloseImageOptions = closeImageOptions;
        closeImageOptions = function() {
            preventScroll(false);
            originalCloseImageOptions();
        };

        const originalViewFullscreen = viewFullscreen;
        viewFullscreen = function() {
            preventScroll(true);
            originalViewFullscreen();
        };

        const originalCloseFullscreen = closeFullscreen;
        closeFullscreen = function() {
            preventScroll(false);
            originalCloseFullscreen();
        };
    </script>
</body>

</html>
