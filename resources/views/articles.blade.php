<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المقالات - معرض صور العائلة</title>

    {{-- قم بتضمين ملف Tailwind CSS الخاص بمشروعك --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- استيراد خطوط جميلة من Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* تطبيق الخط على كامل الصفحة */
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
            min-height: 100vh;
        }

        h1, h2, h3 {
            font-family: 'Amiri', serif;
        }

        /* تأثيرات متحركة للخلفية */
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }

        @keyframes pulse-soft {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
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

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .pulse-animation {
            animation: pulse-soft 4s ease-in-out infinite;
        }

        .slide-in {
            animation: slideIn 0.6s ease-out forwards;
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

        /* بطاقات المقالات */
        .article-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(20px);
        }

        .article-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .article-card:hover {
            transform: translateY(-10px) scale(1.02);
        }

        .article-card:hover .article-image {
            transform: scale(1.1) rotate(2deg);
        }

        .article-image {
            transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* رسومات الخلفية */
        .bg-pattern {
            position: fixed;
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }

        /* نمط العرض الشبكي */
        .grid-view {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 640px) {
            .grid-view {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .grid-view {
                grid-template-columns: repeat(3, 1fr);
                gap: 2rem;
            }
        }

        /* نمط العرض القائمة */
        .list-view {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* شريحة البحث */
        .search-bar {
            transition: all 0.3s ease;
        }

        .search-bar:focus-within {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(34, 197, 94, 0.4);
        }

        /* الفلاتر النشطة */
        .active-filter {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            transform: scale(1.05);
        }

        /* تحسينات الموبايل */
        @media (max-width: 768px) {
            .bg-pattern {
                display: none;
            }

            .grid-view {
                grid-template-columns: 1fr;
            }
        }

        /* شريحة الفلترة المنزلقة */
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

        /* تأثير التحميل */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* شارة العدد */
        .count-badge {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">

    <!-- عناصر زخرفية في الخلفية -->
    <div class="bg-pattern top-10 left-10 w-96 h-96 float-animation hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#22c55e" d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3C52.5,-73.2,69.7,-61.8,44.9,-76.6Z" transform="translate(100 100)"/>
        </svg>
    </div>

    <div class="bg-pattern bottom-10 right-10 w-96 h-96 pulse-animation hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#4ade80" d="M37.5,-65.2C48.7,-57.8,57.8,-47.3,64.3,-35.1C70.8,-22.9,74.7,-9,75.6,5.7C76.5,20.4,74.4,36,67.1,48.6C59.8,61.2,47.3,70.8,33.2,75.7C19.1,80.6,3.4,80.8,-12.1,78.1C-27.6,75.4,-42.9,69.8,-55.3,60.2C-67.7,50.6,-77.2,37,-80.3,21.9C-83.4,6.8,-80.1,-9.8,-74.1,-25.3C-68.1,-40.8,-59.4,-55.2,-47.2,-62.2C-35,-69.2,-19.3,-68.8,-5.4,-60.5C8.5,-52.2,26.3,-72.6,37.5,-65.2Z" transform="translate(100 100)"/>
        </svg>
    </div>

    <div class="container mx-auto px-4 py-4 lg:py-8 relative z-10">

        <!-- الهيدر المبدع -->
        <header class="text-center mb-8 lg:mb-16 relative">
            <div class="inline-block relative">
                <h1 class="text-3xl sm:text-5xl md:text-7xl font-bold gradient-text mb-2 lg:mb-4 drop-shadow-2xl">
                    مكتبة المقالات
                </h1>
                <div class="absolute -top-4 lg:-top-8 -right-4 lg:-right-8 w-8 lg:w-16 h-8 lg:h-16 bg-green-400 rounded-full opacity-30 float-animation"></div>
                <div class="absolute -bottom-2 lg:-bottom-4 -left-4 lg:-left-8 w-6 lg:w-12 h-6 lg:h-12 bg-green-500 rounded-full opacity-30 pulse-animation"></div>
            </div>
            <p class="text-base sm:text-xl text-gray-600 mt-2 lg:mt-4 font-light">
                <span class="inline-block px-4 lg:px-6 py-1 lg:py-2 glass-effect rounded-full">
                    📚 اكتشف قصصنا وذكرياتنا المكتوبة 📚
                </span>
            </p>
        </header>

        <!-- شريط البحث والأدوات -->
        <div class="mb-8">
            <div class="glass-effect p-4 lg:p-6 rounded-3xl green-glow">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- شريط البحث -->
                    <div class="flex-1">
                        <div class="search-bar relative">
                            <input type="text"
                                   id="searchInput"
                                   placeholder="ابحث عن مقال..."
                                   class="w-full px-4 lg:px-6 py-3 lg:py-4 pr-12 lg:pr-14 bg-white/70 border-2 border-green-200 rounded-2xl
                                          text-sm lg:text-base focus:ring-4 focus:ring-green-300 focus:border-green-500
                                          transition-all duration-300 hover:border-green-400">
                            <svg class="absolute right-3 lg:right-4 top-1/2 transform -translate-y-1/2 w-5 lg:w-6 h-5 lg:h-6 text-green-500"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- أزرار العرض والفلترة -->
                    <div class="flex gap-2 lg:gap-3">
                        <!-- أزرار نمط العرض -->
                        <div class="flex bg-white/70 rounded-xl p-1">
                            <button onclick="setViewMode('grid')"
                                    id="gridViewBtn"
                                    class="p-2 lg:p-3 rounded-lg transition-all duration-300 active-filter">
                                <svg class="w-5 lg:w-6 h-5 lg:h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"/>
                                </svg>
                            </button>
                            <button onclick="setViewMode('list')"
                                    id="listViewBtn"
                                    class="p-2 lg:p-3 rounded-lg transition-all duration-300 hover:bg-gray-100">
                                <svg class="w-5 lg:w-6 h-5 lg:h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>

                        <!-- زر الفلترة للموبايل -->
                        <button onclick="toggleFilter()"
                                class="lg:hidden px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white
                                       font-bold rounded-xl shadow-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                            <span>فلتر</span>
                        </button>
                    </div>
                </div>

                <!-- الفلاتر السريعة -->
                <div class="mt-4 flex flex-wrap gap-2">
                    <button onclick="filterByCategory('all')"
                            class="filter-chip px-4 py-2 bg-white/70 rounded-full text-sm font-medium hover:bg-green-50
                                   transition-all duration-300 active-filter">
                        جميع المقالات
                    </button>
                    @foreach($categories as $category)
                    <button onclick="filterByCategory('{{ $category->id }}')"
                            class="filter-chip px-4 py-2 bg-white/70 rounded-full text-sm font-medium hover:bg-green-50
                                   transition-all duration-300">
                        {{ $category->name }}
                        @if($category->articles_count > 0)
                        <span class="ml-2 inline-block px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">
                            {{ $category->articles_count }}
                        </span>
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            <!-- الشريط الجانبي (مخفي افتراضياً على الموبايل) -->
            <aside class="w-full lg:w-1/4">
                <div id="filterPanel" class="fixed inset-0 z-50 lg:relative lg:inset-auto hidden lg:block">
                    <div class="absolute inset-0 bg-black/50 lg:hidden" onclick="toggleFilter()"></div>
                    <div class="slide-panel absolute right-0 top-0 h-full w-80 max-w-[85vw] lg:relative lg:w-full
                                bg-white lg:bg-transparent p-4 lg:p-0 overflow-y-auto lg:overflow-visible">
                        <div class="glass-effect p-4 lg:p-6 rounded-3xl green-glow">
                            <!-- رأس الشريط الجانبي -->
                            <div class="flex items-center justify-between mb-4 lg:mb-6 border-b border-green-200 pb-4">
                                <h3 class="text-xl lg:text-2xl font-bold gradient-text">التصنيفات</h3>
                                <button onclick="toggleFilter()"
                                        class="lg:hidden p-2 rounded-full bg-red-500 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- قائمة التصنيفات -->
                            <div class="space-y-2">
                                <a href="{{ url('/articles') }}"
                                   class="block px-3 lg:px-4 py-2 lg:py-3 rounded-xl lg:rounded-2xl transition-all duration-300
                                          font-medium text-sm lg:text-base
                                          {{ !request('category') ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg scale-105' : 'bg-white/70 hover:bg-green-50 hover:scale-105 hover:shadow-md' }}">
                                    <span class="flex items-center justify-between">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H4a1 1 0 00-1 1v7a1 1 0 001 1h12a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 100-2 2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
                                            </svg>
                                            جميع المقالات
                                        </span>
                                        <span class="count-badge">{{ $totalArticles }}</span>
                                    </span>
                                </a>

                                @foreach($categories as $category)
                                <a href="{{ url('/articles?category=' . $category->id) }}"
                                   class="block px-3 lg:px-4 py-2 lg:py-3 rounded-xl lg:rounded-2xl transition-all duration-300
                                          font-medium text-sm lg:text-base
                                          {{ request('category') == $category->id ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg scale-105' : 'bg-white/70 hover:bg-green-50 hover:scale-105 hover:shadow-md' }}">
                                    <span class="flex items-center justify-between">
                                        {{ $category->name }}
                                        @if($category->articles_count > 0)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                            {{ $category->articles_count }}
                                        </span>
                                        @endif
                                    </span>
                                </a>
                                @endforeach
                            </div>

                            <!-- الكتّاب الأكثر نشاطاً -->
                            <div class="mt-8">
                                <h4 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    الكتّاب الأكثر نشاطاً
                                </h4>
                                <div class="space-y-3">
                                    @foreach($topAuthors as $author)
                                    <a href="{{ url('/articles?author=' . $author->id) }}"
                                       class="flex items-center gap-3 p-3 bg-white/50 rounded-xl hover:bg-green-50
                                              transition-all duration-300 group">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600
                                                    flex items-center justify-center text-white font-bold
                                                    group-hover:scale-110 transition-transform">
                                            {{ mb_substr($author->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800">{{ $author->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $author->articles_count }} مقال</p>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- إحصائيات سريعة -->
                            <div class="mt-8 p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl">
                                <h4 class="text-lg font-bold text-gray-700 mb-3">إحصائيات المكتبة</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">إجمالي المقالات:</span>
                                        <span class="font-bold text-green-600">{{ $totalArticles }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">عدد الكتّاب:</span>
                                        <span class="font-bold text-green-600">{{ $totalAuthors }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">عدد الصور:</span>
                                        <span class="font-bold text-green-600">{{ $totalImages }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- المحتوى الرئيسي -->
            <main class="w-full lg:w-3/4">
                @if($articles->count() > 0)
                    <!-- عرض المقالات -->
                    <div id="articlesContainer" class="grid-view">
                        @foreach($articles as $article)
                        <article class="article-card glass-effect rounded-2xl lg:rounded-3xl overflow-hidden green-glow-hover">
                            <a href="{{ url('/article/' . $article->id) }}" class="block">
                                <!-- صورة المقال -->
                                <div class="relative h-48 lg:h-56 overflow-hidden bg-gradient-to-br from-green-100 to-green-200">
                                    @if($article->images->first())
                                    <img src="{{ asset('storage/' . $article->images->first()->path) }}"
                                         alt="{{ $article->title }}"
                                         class="article-image w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H4a1 1 0 00-1 1v7a1 1 0 001 1h12a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 100-2 2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    @endif

                                    <!-- شارة القسم -->
                                    @if($article->category)
                                    <div class="absolute top-3 right-3">
                                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-medium text-green-700">
                                            {{ $article->category->name }}
                                        </span>
                                    </div>
                                    @endif

                                    <!-- عدد الصور -->
                                    @if($article->images->count() > 0)
                                    <div class="absolute bottom-3 left-3 flex items-center gap-1 px-2 py-1 bg-black/50 backdrop-blur-sm rounded-full">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
