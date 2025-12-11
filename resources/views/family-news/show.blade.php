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

        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            cursor: pointer;
        }

        .lightbox-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
        }

        .lightbox-content img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .lightbox-close:hover {
            color: #fff;
        }

        .gallerySwiper {
            padding: 15px 40px 40px !important;
        }

        .gallerySwiper .swiper-button-next,
        .gallerySwiper .swiper-button-prev {
            color: #37a05c;
            background: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .gallerySwiper .swiper-button-next:hover,
        .gallerySwiper .swiper-button-prev:hover {
            background: #37a05c;
            color: white;
        }

        .gallerySwiper .swiper-button-next {
            left: 0;
            right: auto;
        }

        .gallerySwiper .swiper-button-prev {
            right: 0;
            left: auto;
        }

        @media (max-width: 768px) {
            .gallerySwiper {
                padding: 15px 40px 40px !important;
            }

            .gallerySwiper .swiper-button-next,
            .gallerySwiper .swiper-button-prev {
                width: 35px;
                height: 35px;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 via-green-50/30 to-emerald-50/50">
    @include('partials.main-header')

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl py-6 md:py-8 lg:py-10">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <a href="{{ route('home') }}" class="text-green-600 hover:text-green-700 text-sm">
                <i class="fas fa-home mr-1"></i>الرئيسية
            </a>
            <span class="text-gray-400 mx-2">/</span>
            <span class="text-gray-600 text-sm">أخبار العائلة</span>
        </nav>

        <!-- News Article -->
        <article class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <!-- Header Image -->
            @if ($news->main_image_url)
                <div class="relative h-64 md:h-80 lg:h-96 overflow-hidden">
                    <img src="{{ $news->main_image_url }}" alt="{{ $news->title }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
            @endif

            <!-- Content -->
            <div class="p-6 md:p-8">
                <!-- Title -->
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                    {{ $news->title }}
                </h1>

                <!-- Meta Information -->
                <div class="flex flex-wrap items-center gap-4 mb-6 pb-4 border-b border-gray-200">
                    @if ($news->published_at)
                        <div class="flex items-center gap-2 text-gray-600 text-sm">
                            <i class="fas fa-calendar text-green-600"></i>
                            <span>{{ $news->published_at->format('Y-m-d') }}</span>
                        </div>
                    @endif
                    <div class="flex items-center gap-2 text-gray-600 text-sm">
                        <i class="fas fa-eye text-green-600"></i>
                        <span>{{ $news->views_count }} مشاهدة</span>
                    </div>
                </div>

                <!-- Summary -->
                @if ($news->summary)
                    <div class="bg-green-50 border-r-4 border-green-500 rounded-lg p-4 mb-6">
                        <p class="text-gray-700 leading-relaxed">{{ $news->summary }}</p>
                    </div>
                @endif

                <!-- Main Content -->
                <div class="prose prose-lg max-w-none mb-6">
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $news->content }}
                    </div>
                </div>
            </div>
        </article>

        <!-- Gallery Section -->
        @if ($news->images && $news->images->count() > 0)
            <section class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                <div class="p-6 md:p-8">
                    <h2 class="text-xl md:text-2xl font-bold text-gradient mb-6">
                        <i class="fas fa-images mr-2"></i>معرض الصور
                    </h2>

                    <div class="swiper gallerySwiper">
                        <div class="swiper-wrapper">
                            @foreach ($news->images as $image)
                                <div class="swiper-slide">
                                    <div class="relative group overflow-hidden rounded-xl shadow-md cursor-pointer"
                                        onclick="openLightbox('{{ $image->image_url }}', '{{ $image->caption ?? '' }}')">
                                        <img src="{{ $image->image_url }}" alt="{{ $image->caption ?? 'صورة' }}"
                                            class="w-full h-48 md:h-64 object-cover transition-transform duration-300 group-hover:scale-110">
                                        @if ($image->caption)
                                            <div
                                                class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                                                <p class="text-white text-sm font-semibold">{{ $image->caption }}</p>
                                            </div>
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 flex items-center justify-center">
                                            <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                        </div>
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

        <!-- Related News -->
        @if ($relatedNews && $relatedNews->count() > 0)
            <section class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 md:p-8">
                    <h2 class="text-xl md:text-2xl font-bold text-gradient mb-6">
                        <i class="fas fa-newspaper mr-2"></i>أخبار أخرى
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($relatedNews as $related)
                            <a href="{{ route('family-news.show', $related->id) }}"
                                class="group flex gap-4 p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-200 hover:border-green-300">
                                @if ($related->main_image_url)
                                    <img src="{{ $related->main_image_url }}" alt="{{ $related->title }}"
                                        class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                                @else
                                    <div class="w-24 h-24 gradient-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-newspaper text-white text-2xl opacity-50"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 mb-2 group-hover:text-green-600 transition-colors line-clamp-2">
                                        {{ $related->title }}
                                    </h3>
                                    @if ($related->summary)
                                        <p class="text-gray-600 text-sm line-clamp-2">{{ $related->summary }}</p>
                                    @endif
                                    <div class="mt-2 text-xs text-gray-500">
                                        <i class="fas fa-eye mr-1"></i>{{ $related->views_count }} مشاهدة
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    <!-- Lightbox -->
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close">&times;</span>
        <div class="lightbox-content">
            <img id="lightbox-img" src="" alt="">
            <p id="lightbox-caption" class="text-white text-center mt-4"></p>
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Gallery Swiper
        @if ($news->images && $news->images->count() > 0)
            document.addEventListener('DOMContentLoaded', function() {
                const totalImages = {{ $news->images->count() }};
                if (totalImages > 0) {
                    new Swiper('.gallerySwiper', {
                        slidesPerView: 1,
                        spaceBetween: 20,
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
                            640: {
                                slidesPerView: 2,
                                spaceBetween: 20,
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 24,
                            },
                        },
                    });
                }
            });
        @endif

        // Lightbox Functions
        function openLightbox(imageSrc, caption) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            const lightboxCaption = document.getElementById('lightbox-caption');

            lightboxImg.src = imageSrc;
            lightboxCaption.textContent = caption || '';
            lightbox.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close lightbox on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>
</body>

</html>
