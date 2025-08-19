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
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }

        @keyframes pulse-soft {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
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
            from { opacity: 0; }
            to { opacity: 1; }
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

        /* أزرار التنقل في Lightbox */
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

        /* عداد الصور */
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

    <!-- شريط التقدم للقراءة -->
    <div class="reading-progress" id="readingProgress"></div>

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

    <div class="container mx-auto px-4 py-4 lg:py-8 relative z-10 max-w-6xl">

        <!-- زر الرجوع -->
        <div class="mb-6">
            <a href="{{ url('/gallery') }}" class="inline-flex items-center gap-2 px-4 py-2 glass-effect rounded-full hover:bg-white/95 transition-all duration-300 group">
                <svg class="w-5 h-5 text-green-600 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="text-gray-700 font-medium">العودة للمعرض</span>
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
                    @if($article->person)
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold">
                            {{ mb_substr($article->person->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $article->person->name }}</p>
                            <p class="text-xs text-gray-500">المساهم</p>
                        </div>
                    </div>
                    @endif

                    @if($article->category)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                            {{ $article->category->name }}
                        </span>
                    </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-500">{{ $article->created_at->format('d/m/Y') }}</span>
                    </div>

                    @if($article->images->count() > 0)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-500">{{ $article->images->count() }} صورة</span>
                    </div>
                    @endif
                </div>

                <!-- أزرار المشاركة -->
                <div class="flex items-center gap-3 mt-6">
                    <span class="text-gray-600 text-sm font-medium">شارك:</span>
                    <button onclick="shareOnFacebook()" class="share-button bg-blue-50 hover:bg-blue-100">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </button>
                    <button onclick="shareOnTwitter()" class="share-button bg-sky-50 hover:bg-sky-100">
                        <svg class="w-5 h-5 text-sky-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </button>
                    <button onclick="shareOnWhatsApp()" class="share-button bg-green-50 hover:bg-green-100">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 16.546c-.24.69-.718 1.266-1.363 1.644-.618.362-1.346.544-2.087.544-1.034 0-2.055-.337-2.917-.977l-3.254.855.869-3.174c-.705-.915-1.081-2.025-1.081-3.162 0-2.908 2.368-5.276 5.276-5.276 1.41 0 2.735.549 3.73 1.544a5.244 5.244 0 011.545 3.73c0 .977-.267 1.922-.718 2.732z"/>
                        </svg>
                    </button>
                    <button onclick="copyLink()" class="share-button bg-gray-50 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- محتوى المقال -->
            @if($article->content)
            <div class="p-6 lg:p-10">
                <div class="article-content text-gray-700 leading-relaxed">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </div>
            @endif

            <!-- معرض الصور -->
            @if($article->images->count() > 0)
            <div class="p-6 lg:p-10 bg-gradient-to-t from-green-50/30 to-transparent">
                <h2 class="text-2xl lg:text-3xl font-bold gradient-text mb-6 flex items-center gap-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                    </svg>
                    معرض الصور
                    <span class="text-sm font-normal text-gray-500">({{ $article->images->count() }} صورة)</span>
                </h2>

                <div class="image-grid">
                    @foreach($article->images as $index => $image)
                    <div class="image-container glass-effect p-2" onclick="openLightbox({{ $index }})">
                        <div class="aspect-square overflow-hidden rounded-lg">
                            <img src="{{ asset('storage/' . $image->path) }}"
                                 alt="{{ $image->name ?? 'صورة من المقال' }}"
                                 class="w-full h-full object-cover">
                        </div>
                        @if($image->name)
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
                    <div class="text-center sm:text-right">
                        <p class="text-gray-600 mb-2">هل أعجبك هذا المقال؟</p>
                        <div class="flex gap-2 justify-center sm:justify-start">
                            <button class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                </svg>
                                أعجبني
                            </button>
                            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition-colors">
                                تعليق
                            </button>
                        </div>
                    </div>

                    <a href="{{ url('/gallery') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        العودة للمعرض
                    </a>
                </div>
            </footer>
        </article>

        <!-- مقالات ذات صلة -->
        @if(isset($relatedArticles) && $relatedArticles->count() > 0)
        <section class="mt-12">
            <h3 class="text-2xl lg:text-3xl font-bold gradient-text mb-6 text-center">مقالات ذات صلة</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedArticles->take(3) as $related)
                <a href="{{ url('/article/' . $related->id) }}" class="glass-effect rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105 block">
                    @if($related->images->first())
                    <div class="aspect-video overflow-hidden bg-gradient-to-br from-green-100 to-green-200">
                        <img src="{{ asset('storage/' . $related->images->first()->path) }}"
                             alt="{{ $related->title }}"
                             class="w-full h-full object-cover">
                    </div>
                    @else
                    <div class="aspect-video bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                        <svg class="w-16 h-16 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    @endif
                    <div class="p-4">
                        <h4 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $related->title }}</h4>
                        @if($related->person)
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
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
</main>
