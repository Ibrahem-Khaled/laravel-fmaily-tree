<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }} - أخبار العائلة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        body {
            font-family: 'Alexandria', sans-serif;
        }

        .gradient-primary {
            background: linear-gradient(135deg, #145147 0%, #37a05c 50%, #2d8a4e 100%);
        }

        .text-gradient {
            background: linear-gradient(135deg, #145147 0%, #37a05c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ─── Hero image parallax-like overlay ─── */
        .article-hero {
            position: relative;
            overflow: hidden;
        }
        .article-hero img {
            transition: transform 0.5s ease;
        }
        .article-hero:hover img {
            transform: scale(1.03);
        }

        /* ─── Article body styling ─── */
        .article-body {
            font-size: 1rem;
            line-height: 2;
            color: #374151;
        }
        .article-body p { margin-bottom: 1rem; }

        /* ─── Pull-quote / summary ─── */
        .pull-quote {
            position: relative;
            padding: 1.25rem 1.5rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            border-right: 5px solid #22c55e;
        }
        .pull-quote::before {
            content: '\201C';
            position: absolute;
            top: -8px;
            right: 12px;
            font-size: 4rem;
            color: #22c55e;
            opacity: 0.15;
            font-family: Georgia, serif;
            line-height: 1;
        }

        /* ─── Meta badge ─── */
        .meta-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        /* ─── Lightbox ─── */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.92);
            cursor: pointer;
            animation: lbFadeIn 0.3s ease;
        }
        @keyframes lbFadeIn { from { opacity: 0; } to { opacity: 1; } }

        .lightbox-content {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%; max-height: 90%;
            animation: lbZoom 0.3s ease;
        }
        @keyframes lbZoom { from { opacity: 0; transform: translate(-50%, -50%) scale(0.92); } to { opacity: 1; transform: translate(-50%, -50%) scale(1); } }

        .lightbox-content img {
            width: 100%; height: auto;
            border-radius: 12px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.5);
        }

        .lightbox-close {
            position: absolute;
            top: 20px; right: 30px;
            color: #fff;
            font-size: 36px;
            cursor: pointer;
            width: 44px; height: 44px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
        }
        .lightbox-close:hover {
            background: rgba(255,255,255,0.25);
            transform: rotate(90deg);
        }

        /* ─── Gallery Swiper ─── */
        .gallerySwiper {
            padding: 15px 40px 40px !important;
        }
        .gallerySwiper .swiper-button-next,
        .gallerySwiper .swiper-button-prev {
            color: #37a05c;
            background: white;
            width: 42px; height: 42px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
            transition: all 0.3s ease;
        }
        .gallerySwiper .swiper-button-next:hover,
        .gallerySwiper .swiper-button-prev:hover {
            background: #37a05c;
            color: white;
            box-shadow: 0 6px 20px rgba(55, 160, 92, 0.35);
        }
        .gallerySwiper .swiper-button-next::after,
        .gallerySwiper .swiper-button-prev::after { font-size: 18px; }
        .gallerySwiper .swiper-button-next { left: 0; right: auto; }
        .gallerySwiper .swiper-button-prev { right: 0; left: auto; }
        .gallerySwiper .swiper-pagination-bullet {
            background: #d1d5db; opacity: 1;
            width: 8px; height: 8px;
        }
        .gallerySwiper .swiper-pagination-bullet-active {
            background: #22c55e;
            width: 24px;
            border-radius: 4px;
        }

        /* ─── Gallery thumbnail ─── */
        .gallery-thumb {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            cursor: pointer;
        }
        .gallery-thumb img {
            transition: transform 0.5s ease;
        }
        .gallery-thumb:hover img {
            transform: scale(1.08);
        }
        .gallery-thumb .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .gallery-thumb:hover .overlay { opacity: 1; }

        /* ─── Related news card ─── */
        .related-card {
            border: 1px solid #f3f4f6;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .related-card:hover {
            border-color: rgba(34,197,94,0.3);
            transform: translateY(-3px);
            box-shadow: 0 12px 40px -8px rgba(0,0,0,0.1);
        }
        .related-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0;
            width: 4px;
            border-radius: 0 8px 8px 0;
            transition: width 0.3s ease;
        }
        .related-card:hover::before { width: 5px; }

        /* ─── Fade-in animation ─── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp 0.6s ease forwards; }

        /* ─── Share button ─── */
        .share-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid;
        }
        .share-btn:hover { transform: translateY(-1px); }

        @media (max-width: 768px) {
            .gallerySwiper { padding: 10px 35px 35px !important; }
            .gallerySwiper .swiper-button-next,
            .gallerySwiper .swiper-button-prev {
                width: 32px; height: 32px;
            }
            .gallerySwiper .swiper-button-next::after,
            .gallerySwiper .swiper-button-prev::after { font-size: 14px; }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 via-green-50/30 to-emerald-50/50 min-h-screen">
    @include('partials.main-header')

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl py-6 md:py-8 lg:py-10">

        {{-- ─── Breadcrumb ─── --}}
        <nav class="mb-5 animate-in" style="animation-delay: 0.05s;">
            <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm rounded-full px-4 py-2 shadow-sm border border-gray-100">
                <a href="{{ route('home') }}" class="text-green-600 hover:text-green-700 text-xs md:text-sm transition-colors">
                    <i class="fas fa-home ml-1"></i>الرئيسية
                </a>
                <i class="fas fa-chevron-left text-gray-300 text-[8px]"></i>
                <span class="text-gray-500 text-xs md:text-sm">أخبار العائلة</span>
                <i class="fas fa-chevron-left text-gray-300 text-[8px]"></i>
                <span class="text-gray-700 text-xs md:text-sm font-medium truncate max-w-[150px] md:max-w-xs">{{ $news->title }}</span>
            </div>
        </nav>

        {{-- ─── Article Card ─── --}}
        <article class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden mb-6 md:mb-8 animate-in" style="animation-delay: 0.1s;">

            {{-- Hero Image --}}
            @if ($news->main_image_url)
                <div class="article-hero relative h-56 sm:h-64 md:h-80 lg:h-96">
                    <img src="{{ $news->main_image_url }}" alt="{{ $news->title }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

                    {{-- Title overlay on image --}}
                    <div class="absolute bottom-0 right-0 left-0 p-5 md:p-8">
                        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-white leading-tight drop-shadow-lg">
                            {{ $news->title }}
                        </h1>
                    </div>
                </div>
            @else
                {{-- Decorative header when no image --}}
                <div class="relative overflow-hidden" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 40%, #d1fae5 100%);">
                    <div class="absolute top-0 left-0 w-40 h-40 bg-green-200 rounded-full blur-3xl opacity-40"></div>
                    <div class="absolute bottom-0 right-0 w-56 h-56 bg-emerald-200 rounded-full blur-3xl opacity-30"></div>
                    <div class="absolute top-4 left-8 opacity-[0.04]">
                        <i class="fas fa-newspaper text-8xl md:text-9xl text-green-800 transform -rotate-12"></i>
                    </div>
                    <div class="relative p-6 md:p-10 lg:p-12">
                        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 leading-tight max-w-3xl">
                            {{ $news->title }}
                        </h1>
                    </div>
                </div>
            @endif

            {{-- Article Content --}}
            <div class="p-5 md:p-8 lg:p-10">

                {{-- Meta badges --}}
                <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-5">
                    @if ($news->published_at)
                        <span class="meta-badge bg-green-50 text-green-700 border border-green-200">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $news->published_at->format('Y/m/d') }}
                        </span>
                        <span class="meta-badge bg-blue-50 text-blue-600 border border-blue-200">
                            <i class="fas fa-clock"></i>
                            {{ $news->published_at->diffForHumans() }}
                        </span>
                    @endif
                    <span class="meta-badge bg-gray-50 text-gray-600 border border-gray-200">
                        <i class="fas fa-eye"></i>
                        {{ $news->views_count }} مشاهدة
                    </span>
                    @if ($news->images && $news->images->count() > 0)
                        <span class="meta-badge bg-purple-50 text-purple-600 border border-purple-200">
                            <i class="fas fa-images"></i>
                            {{ $news->images->count() }} صور
                        </span>
                    @endif
                </div>

                {{-- Divider --}}
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex-1 h-px bg-gradient-to-l from-green-300 to-transparent"></div>
                    <i class="fas fa-leaf text-green-300 text-xs"></i>
                    <div class="flex-1 h-px bg-gradient-to-r from-green-300 to-transparent"></div>
                </div>

                {{-- Summary / Pull-quote --}}
                @if ($news->summary)
                    <div class="pull-quote mb-6">
                        <p class="text-gray-700 text-sm md:text-base leading-relaxed font-medium">{{ $news->summary }}</p>
                    </div>
                @endif

                {{-- Main Content --}}
                <div class="article-body mb-6">
                    <div class="whitespace-pre-line">{{ $news->content }}</div>
                </div>

                {{-- Divider --}}
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-1 h-px bg-gradient-to-l from-gray-200 to-transparent"></div>
                    <i class="fas fa-leaf text-gray-200 text-xs"></i>
                    <div class="flex-1 h-px bg-gradient-to-r from-gray-200 to-transparent"></div>
                </div>

                {{-- Share & Actions --}}
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-gray-400 text-xs ml-1">مشاركة:</span>
                    <button onclick="shareOnWhatsApp()" class="share-btn bg-green-50 text-green-600 border-green-200 hover:bg-green-100">
                        <i class="fab fa-whatsapp"></i>
                        <span class="hidden sm:inline">واتساب</span>
                    </button>
                    <button onclick="shareOnTwitter()" class="share-btn bg-sky-50 text-sky-600 border-sky-200 hover:bg-sky-100">
                        <i class="fab fa-twitter"></i>
                        <span class="hidden sm:inline">تويتر</span>
                    </button>
                    <button onclick="copyLink()" id="copyLinkBtn" class="share-btn bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100">
                        <i class="fas fa-link"></i>
                        <span class="hidden sm:inline">نسخ الرابط</span>
                    </button>
                </div>
            </div>
        </article>

        {{-- ─── Gallery Section ─── --}}
        @if ($news->images && $news->images->count() > 0)
            <section class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden mb-6 md:mb-8 animate-in" style="animation-delay: 0.2s;">
                <div class="p-5 md:p-8">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-green-200/50">
                            <i class="fas fa-images text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg md:text-xl font-bold text-gray-800">معرض الصور</h2>
                            <p class="text-gray-400 text-[11px]">{{ $news->images->count() }} صورة</p>
                        </div>
                    </div>

                    <div class="swiper gallerySwiper">
                        <div class="swiper-wrapper">
                            @foreach ($news->images as $image)
                                <div class="swiper-slide">
                                    <div class="gallery-thumb shadow-md"
                                         onclick="openLightbox('{{ $image->image_url }}', '{{ $image->caption ?? '' }}')">
                                        <img src="{{ $image->image_url }}" alt="{{ $image->caption ?? 'صورة' }}"
                                             class="w-full h-44 md:h-56 object-cover">
                                        <div class="overlay">
                                            <div class="text-center">
                                                <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-2">
                                                    <i class="fas fa-search-plus text-white text-lg"></i>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($image->caption)
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                                                <p class="text-white text-xs md:text-sm font-medium">{{ $image->caption }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next gallery-next"></div>
                        <div class="swiper-button-prev gallery-prev"></div>
                        <div class="swiper-pagination gallery-pagination"></div>
                    </div>
                </div>
            </section>
        @endif

        {{-- ─── Related News ─── --}}
        @if ($relatedNews && $relatedNews->count() > 0)
            <section class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden animate-in" style="animation-delay: 0.3s;">
                <div class="p-5 md:p-8">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-200/50">
                            <i class="fas fa-newspaper text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg md:text-xl font-bold text-gray-800">أخبار أخرى</h2>
                            <p class="text-gray-400 text-[11px]">قد تهمك أيضاً</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                        @php
                            $relatedAccents = ['#22c55e','#0ea5e9','#8b5cf6','#f59e0b','#ef4444','#ec4899'];
                        @endphp
                        @foreach ($relatedNews as $rIdx => $related)
                            @php $rAccent = $relatedAccents[$rIdx % count($relatedAccents)]; @endphp
                            <a href="{{ route('family-news.show', $related->id) }}"
                               class="group related-card relative flex gap-3 p-3 md:p-4 bg-white hover:bg-gray-50/50 transition-all"
                               style="--accent: {{ $rAccent }};">
                                <style>.related-card:nth-child({{ $rIdx + 1 }})::before { background: {{ $rAccent }}; }</style>

                                @if ($related->main_image_url)
                                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl overflow-hidden flex-shrink-0 shadow-sm">
                                        <img src="{{ $related->main_image_url }}" alt="{{ $related->title }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    </div>
                                @else
                                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl flex-shrink-0 flex items-center justify-center relative overflow-hidden"
                                         style="background: linear-gradient(135deg, {{ $rAccent }}15, {{ $rAccent }}08);">
                                        <i class="fas fa-newspaper text-2xl" style="color: {{ $rAccent }}; opacity: 0.25;"></i>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-800 text-sm md:text-base mb-1 line-clamp-2 group-hover:text-green-600 transition-colors leading-snug">
                                        {{ $related->title }}
                                    </h3>
                                    @if ($related->summary)
                                        <p class="text-gray-500 text-[11px] md:text-xs line-clamp-2 mb-1.5 leading-relaxed">{{ Str::limit($related->summary, 80) }}</p>
                                    @endif
                                    <div class="flex items-center gap-3 text-[10px] text-gray-400">
                                        @if ($related->published_at)
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ $related->published_at->format('Y/m/d') }}
                                            </span>
                                        @endif
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-eye"></i>
                                            {{ $related->views_count }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

    </main>

    {{-- ─── Lightbox ─── --}}
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close"><i class="fas fa-times"></i></span>
        <div class="lightbox-content">
            <img id="lightbox-img" src="" alt="">
            <p id="lightbox-caption" class="text-white text-center mt-4 text-sm"></p>
        </div>
    </div>

    {{-- ─── Scripts ─── --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Gallery Swiper
        @if ($news->images && $news->images->count() > 0)
            document.addEventListener('DOMContentLoaded', function() {
                const totalImages = {{ $news->images->count() }};
                if (totalImages > 0) {
                    new Swiper('.gallerySwiper', {
                        slidesPerView: 1,
                        spaceBetween: 16,
                        loop: totalImages > 3,
                        pagination: {
                            el: '.gallery-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.gallery-next',
                            prevEl: '.gallery-prev',
                        },
                        breakpoints: {
                            640: { slidesPerView: 2, spaceBetween: 16 },
                            1024: { slidesPerView: 3, spaceBetween: 20 },
                        },
                    });
                }
            });
        @endif

        // Lightbox
        function openLightbox(imageSrc, caption) {
            const lightbox = document.getElementById('lightbox');
            document.getElementById('lightbox-img').src = imageSrc;
            document.getElementById('lightbox-caption').textContent = caption || '';
            lightbox.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });

        // Share functions
        function shareOnWhatsApp() {
            window.open('https://wa.me/?text=' + encodeURIComponent(document.title + ' ' + window.location.href), '_blank');
        }
        function shareOnTwitter() {
            window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + '&url=' + encodeURIComponent(window.location.href), '_blank');
        }
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                var btn = document.getElementById('copyLinkBtn');
                var orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i><span>تم النسخ!</span>';
                btn.classList.remove('bg-gray-50', 'text-gray-600', 'border-gray-200');
                btn.classList.add('bg-green-50', 'text-green-600', 'border-green-200');
                setTimeout(function() {
                    btn.innerHTML = orig;
                    btn.classList.remove('bg-green-50', 'text-green-600', 'border-green-200');
                    btn.classList.add('bg-gray-50', 'text-gray-600', 'border-gray-200');
                }, 2000);
            });
        }
    </script>
</body>

</html>
