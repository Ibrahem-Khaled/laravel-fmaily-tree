@extends('layouts.web-site.web')

@section('title', 'الرئيسية - تواصل عائلة السريع')

@push('styles')
    <style>
        /* ============================================================
                       SWIPER – Hero
                       ============================================================ */
        .heroSwiper {
            width: 100%;
            height: 100%;
        }

        .heroSwiper .swiper-slide {
            opacity: 0 !important;
            transition: opacity 0.8s ease-in-out;
        }

        .heroSwiper .swiper-slide-active {
            opacity: 1 !important;
        }

        .heroSwiper .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .heroSwiper .swiper-button-next,
        .heroSwiper .swiper-button-prev {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .heroSwiper .swiper-button-next:hover,
        .heroSwiper .swiper-button-prev:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: scale(1.15);
        }

        .heroSwiper .swiper-button-next::after,
        .heroSwiper .swiper-button-prev::after {
            font-size: 20px;
            font-weight: bold;
        }

        .heroSwiper .swiper-button-next {
            left: 20px;
            right: auto;
        }

        .heroSwiper .swiper-button-prev {
            right: 20px;
            left: auto;
        }

        .heroSwiper .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.6);
            opacity: 1;
            width: 10px;
            height: 10px;
            transition: all 0.3s ease;
        }

        .heroSwiper .swiper-pagination-bullet-active {
            background: white;
            width: 30px;
            border-radius: 5px;
        }

        /* ============================================================
                       SWIPER – Gallery & Courses (shared)
                       ============================================================ */
        .gallerySwiper,
        .coursesSwiper {
            padding: 10px 35px 30px !important;
        }

        .gallerySwiper .swiper-button-next,
        .gallerySwiper .swiper-button-prev,
        .coursesSwiper .swiper-button-next,
        .coursesSwiper .swiper-button-prev {
            color: #37a05c;
            background: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .gallerySwiper .swiper-button-next:hover,
        .gallerySwiper .swiper-button-prev:hover,
        .coursesSwiper .swiper-button-next:hover,
        .coursesSwiper .swiper-button-prev:hover {
            background: #37a05c;
            color: white;
            box-shadow: 0 6px 20px rgba(55, 160, 92, 0.4);
            transform: scale(1.1);
        }

        .gallerySwiper .swiper-button-next::after,
        .gallerySwiper .swiper-button-prev::after,
        .coursesSwiper .swiper-button-next::after,
        .coursesSwiper .swiper-button-prev::after {
            font-size: 20px;
            font-weight: bold;
        }

        .gallerySwiper .swiper-button-next,
        .coursesSwiper .swiper-button-next {
            left: 0;
            right: auto;
        }

        .gallerySwiper .swiper-button-prev,
        .coursesSwiper .swiper-button-prev {
            right: 0;
            left: auto;
        }

        .gallerySwiper .swiper-pagination-bullet,
        .coursesSwiper .swiper-pagination-bullet {
            background: #d1d5db;
            opacity: 1;
            width: 10px;
            height: 10px;
            transition: all 0.3s ease;
        }

        .gallerySwiper .swiper-pagination-bullet-active,
        .coursesSwiper .swiper-pagination-bullet-active {
            background: #37a05c;
            width: 30px;
            border-radius: 5px;
        }

        /* ============================================================
                       Gallery Modal Animations
                       ============================================================ */
        #galleryModal {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        #galleryModalImageContainer,
        #galleryModalVideoContainer {
            animation: zoomIn 0.3s ease-out;
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        #galleryModalInfo {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse-soft {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.03);
            }
        }

        /* ============================================================
                       Quiz – Rich-text content (description & question)
                       ============================================================ */
        .quiz-description,
        .question-text {
            direction: rtl;
            text-align: right;
        }

        .quiz-description p,
        .question-text p {
            margin-bottom: 0.75rem;
        }

        .quiz-description p:last-child,
        .question-text p:last-child {
            margin-bottom: 0;
        }

        .quiz-description strong,
        .quiz-description b {
            font-weight: 700;
            color: #16a34a;
        }

        .question-text strong,
        .question-text b {
            font-weight: 700;
        }

        .quiz-description em,
        .quiz-description i,
        .question-text em,
        .question-text i {
            font-style: italic;
        }

        .quiz-description ul,
        .quiz-description ol,
        .question-text ul,
        .question-text ol {
            margin-right: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .quiz-description li,
        .question-text li {
            margin-bottom: 0.5rem;
        }

        .quiz-description a,
        .question-text a {
            color: #22c55e;
            text-decoration: underline;
            transition: color 0.2s;
        }

        .quiz-description a:hover,
        .question-text a:hover {
            color: #16a34a;
        }

        .quiz-description img,
        .question-text img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 0.5rem 0;
        }

        .quiz-description table {
            width: 100%;
            margin-bottom: 0.75rem;
            border-collapse: collapse;
        }

        .quiz-description table td,
        .quiz-description table th {
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        /* ============================================================
                       Responsive overrides
                       ============================================================ */
        @media (max-width: 768px) {

            .heroSwiper .swiper-button-next,
            .heroSwiper .swiper-button-prev {
                width: 35px;
                height: 35px;
            }

            .heroSwiper .swiper-button-next::after,
            .heroSwiper .swiper-button-prev::after {
                font-size: 16px;
            }

            .heroSwiper .swiper-button-next {
                left: 10px;
            }

            .heroSwiper .swiper-button-prev {
                right: 10px;
            }

            .person-name-overlay {
                opacity: 1 !important;
            }

            .gallerySwiper,
            .coursesSwiper {
                padding: 8px 30px 25px !important;
            }

            .gallerySwiper .swiper-button-next,
            .gallerySwiper .swiper-button-prev,
            .coursesSwiper .swiper-button-next,
            .coursesSwiper .swiper-button-prev {
                width: 35px;
                height: 35px;
            }

            .gallerySwiper .swiper-button-next::after,
            .gallerySwiper .swiper-button-prev::after,
            .coursesSwiper .swiper-button-next::after,
            .coursesSwiper .swiper-button-prev::after {
                font-size: 16px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ================================================================
    HERO – Slideshow
    ================================================================ --}}
    <section class="relative h-[180px] sm:h-[220px] md:h-[280px] lg:h-[320px] overflow-hidden">

        @if ($latestImages->count() > 0)
            <div class="swiper heroSwiper">
                <div class="swiper-wrapper">
                    @foreach ($latestImages->take(10) as $slide)
                        <div class="swiper-slide">
                            @if ($slide->link)
                                <a href="{{ $slide->link }}" target="_blank" class="block relative w-full h-full">
                                @else
                                    <div class="relative w-full h-full">
                            @endif

                            @if ($slide->image_url)
                                <img src="{{ $slide->image_url }}" alt="{{ $slide->title ?? 'صورة' }}"
                                    class="w-full h-full object-cover">
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                            @if ($slide->title || $slide->description)
                                <div class="absolute bottom-4 md:bottom-6 right-4 left-4 md:right-6 md:left-6 z-10">
                                    <div class="glass-effect rounded-xl p-3 md:p-4 max-w-2xl animate-fade-in-up">
                                        @if ($slide->title)
                                            <h2
                                                class="text-white text-base md:text-lg lg:text-xl font-bold mb-1 md:mb-2 drop-shadow-lg">
                                                {{ $slide->title }}
                                            </h2>
                                        @endif
                                        @if ($slide->description)
                                            <p class="text-white/95 text-xs md:text-sm line-clamp-2 mb-2 drop-shadow">
                                                {{ $slide->description }}
                                            </p>
                                        @endif
                                        @if ($slide->link)
                                            <span
                                                class="inline-flex items-center gap-1.5 text-white text-xs md:text-sm bg-white/20 px-3 py-1.5 rounded-lg hover:bg-white/30 transition-all">
                                                <span>اكتشف المزيد</span>
                                                <i class="fas fa-arrow-left text-xs"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if ($slide->link)
                                </a>
                            @else
                        </div>
                    @endif
                </div>
        @endforeach
        </div>
        <div class="swiper-button-next hero-next"></div>
        <div class="swiper-button-prev hero-prev"></div>
        <div class="swiper-pagination hero-pagination"></div>
        </div>
    @else
        <div class="absolute inset-0 gradient-primary flex items-center justify-center">
            <div class="text-center text-white px-4">
                <i class="fas fa-images text-4xl md:text-5xl mb-4 opacity-40 animate-float"></i>
                <p class="text-base md:text-lg font-semibold">لا توجد صور في السلايدشو حالياً</p>
            </div>
        </div>
        @endif

    </section>

    {{-- ================================================================
    FAMILY BRIEF
    ================================================================ --}}
    @if ($familyBrief)
        <section class="py-3 md:py-6 lg:py-8 bg-white relative overflow-hidden">
            <div class="absolute top-0 left-0 w-48 h-48 bg-green-100 rounded-full blur-3xl opacity-20"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
                <div class="text-right mb-3 md:mb-5">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">نبذة عن العائلة
                    </h2>
                </div>
                <div class="glass-card rounded-2xl p-3 md:p-4 lg:p-6 shadow-lg">
                    <div class="text-gray-700 text-sm md:text-base leading-relaxed whitespace-pre-line">
                        {!! nl2br(e($familyBrief)) !!}
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ================================================================
    RAMADAN QUIZ – Active competition OR next event countdown
    ================================================================ --}}
    @include('partials.home-quiz-competition')



     {{-- ================================================================
    FAMILY NEWS – Magazine-style redesign
    ================================================================ --}}
    @if ($familyNews && $familyNews->count() > 0)
        <style>
            .news-card-accent { position: relative; }
            .news-card-accent::before {
                content: '';
                position: absolute;
                top: 0; right: 0; bottom: 0;
                width: 4px;
                border-radius: 0 8px 8px 0;
                background: linear-gradient(180deg, #22c55e 0%, #059669 50%, #0d9488 100%);
                transition: width 0.3s ease;
            }
            .news-card-accent:hover::before { width: 6px; }

            .news-hero-card {
                background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 30%, #ffffff 100%);
                border: 1px solid rgba(34,197,94,0.15);
            }
            .news-hero-card:hover {
                border-color: rgba(34,197,94,0.35);
                box-shadow: 0 20px 60px -12px rgba(34,197,94,0.15);
            }

            .news-item-card {
                background: #fff;
                border: 1px solid #f3f4f6;
                transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }
            .news-item-card:hover {
                border-color: rgba(34,197,94,0.25);
                transform: translateY(-2px);
                box-shadow: 0 12px 40px -8px rgba(0,0,0,0.1);
            }

            .news-date-badge {
                background: linear-gradient(135deg, #22c55e, #059669);
                color: white;
                font-size: 10px;
                padding: 3px 10px;
                border-radius: 20px;
                font-weight: 600;
                white-space: nowrap;
            }

            .news-icon-float {
                position: absolute;
                opacity: 0.04;
                font-size: 80px;
                top: -10px;
                left: -10px;
                transform: rotate(-15deg);
                transition: all 0.5s ease;
                pointer-events: none;
            }
            .news-item-card:hover .news-icon-float,
            .news-hero-card:hover .news-icon-float {
                opacity: 0.08;
                transform: rotate(-5deg) scale(1.1);
            }

            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(15px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .news-animate { animation: fadeInUp 0.5s ease forwards; opacity: 0; }

            .news-views-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                background: #f9fafb;
                border: 1px solid #e5e7eb;
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 10px;
                color: #6b7280;
            }
        </style>

        <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-b from-white via-gray-50/50 to-white relative overflow-hidden">
            {{-- Decorative background blobs --}}
            <div class="absolute top-0 right-0 w-72 h-72 bg-green-100 rounded-full blur-3xl opacity-15"></div>
            <div class="absolute bottom-0 left-0 w-56 h-56 bg-emerald-100 rounded-full blur-3xl opacity-10"></div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

                {{-- Section Header --}}
                <div class="text-right mb-4 md:mb-6">
                    <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200 rounded-full px-3 py-1 mb-2">
                        <span class="text-green-700 text-[10px] md:text-xs font-semibold">آخر الأخبار</span>
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                    </div>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-1">
                        أخبار العائلة
                    </h2>
                    <p class="text-gray-500 text-xs md:text-sm">تابع آخر أخبار وأحداث ومستجدات العائلة</p>
                </div>

                @php
                    $firstNews = $familyNews->first();
                    $restNews = $familyNews->skip(1);
                    $accentColors = ['#22c55e','#0ea5e9','#8b5cf6','#f59e0b','#ef4444','#ec4899','#14b8a6','#6366f1'];
                    $newsIcons = ['fa-bullhorn','fa-star','fa-bell','fa-bolt','fa-heart','fa-gem','fa-fire','fa-rocket'];
                @endphp

                {{-- Hero / Featured News Card --}}
                <a href="{{ route('family-news.show', $firstNews->id) }}"
                   class="group block news-hero-card rounded-2xl overflow-hidden mb-4 md:mb-5 transition-all duration-500 news-animate {{ !$firstNews->is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}"
                   style="animation-delay: 0.05s;">
                    <div class="flex flex-col md:flex-row-reverse">
                        {{-- Image or decorative panel --}}
                        @if ($firstNews->main_image_url)
                            <div class="relative w-full md:w-2/5 h-44 md:h-auto md:min-h-[220px] overflow-hidden flex-shrink-0">
                                <img src="{{ $firstNews->main_image_url }}" alt="{{ $firstNews->title }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-l from-transparent to-black/10 md:bg-gradient-to-r"></div>
                                @if ($firstNews->images->count() > 0)
                                    <div class="absolute top-2 left-2 z-10">
                                        <span class="bg-black/40 text-white text-[9px] px-2 py-1 rounded-full font-bold backdrop-blur-sm">
                                            <i class="fas fa-images mr-1"></i>{{ $firstNews->images->count() }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="hidden md:flex w-2/5 items-center justify-center relative overflow-hidden" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                                <i class="fas fa-newspaper text-green-300 text-7xl opacity-30 group-hover:opacity-50 transition-opacity duration-500"></i>
                                <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-green-200 rounded-full blur-2xl opacity-40"></div>
                            </div>
                        @endif

                        {{-- Content --}}
                        <div class="flex-1 p-4 md:p-6 flex flex-col justify-center relative">
                            <i class="fas fa-bullhorn news-icon-float"></i>

                            @if (!$firstNews->is_active && Auth::check())
                                <span class="absolute top-3 left-3 bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                            @endif

                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                @if ($firstNews->published_at)
                                    <span class="news-date-badge">
                                        <i class="fas fa-calendar-alt ml-1"></i>
                                        {{ $firstNews->published_at->format('Y/m/d') }}
                                    </span>
                                @endif
                                <span class="news-views-badge">
                                    <i class="fas fa-eye"></i>
                                    {{ $firstNews->views_count }}
                                </span>
                            </div>

                            <h3 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors duration-300 leading-snug">
                                {{ $firstNews->title }}
                            </h3>

                            <p class="text-gray-600 text-xs md:text-sm mb-3 line-clamp-3 leading-relaxed">
                                {{ $firstNews->summary ?? Str::limit(strip_tags($firstNews->content), 200) }}
                            </p>

                            <div class="flex items-center gap-2 text-green-600 text-xs md:text-sm font-semibold">
                                <span class="group-hover:underline">اقرأ التفاصيل</span>
                                <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform duration-300"></i>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Remaining News Grid --}}
                @if ($restNews->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                        @foreach ($restNews as $idx => $news)
                            @php
                                $colorIdx = $idx % count($accentColors);
                                $accent = $accentColors[$colorIdx];
                                $icon = $newsIcons[$colorIdx];
                            @endphp
                            <a href="{{ route('family-news.show', $news->id) }}"
                               class="group block news-item-card news-card-accent rounded-xl overflow-hidden news-animate {{ !$news->is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}"
                               style="animation-delay: {{ 0.1 + ($idx * 0.07) }}s;">
                               <div class="relative">
                                    {{-- Decorative floating icon --}}
                                    <i class="fas {{ $icon }} news-icon-float" style="color: {{ $accent }};"></i>

                                    {{-- Colored accent bar override per card --}}
                                    <style>.news-item-card:nth-child({{ $idx + 1 }})::before { background: linear-gradient(180deg, {{ $accent }}, {{ $accent }}88); }</style>

                                    @if ($news->main_image_url)
                                        <div class="relative h-32 md:h-36 overflow-hidden">
                                            <img src="{{ $news->main_image_url }}" alt="{{ $news->title }}"
                                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                                            @if ($news->images->count() > 0)
                                                <div class="absolute top-2 left-2">
                                                    <span class="bg-black/40 text-white text-[8px] px-2 py-0.5 rounded-full backdrop-blur-sm">
                                                        <i class="fas fa-images mr-0.5"></i>{{ $news->images->count() }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="p-3 md:p-4">
                                        @if (!$news->is_active && Auth::check())
                                            <span class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold mb-1 inline-block">معطل</span>
                                        @endif

                                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                                            @if ($news->published_at)
                                                <span class="news-date-badge" style="background: linear-gradient(135deg, {{ $accent }}, {{ $accent }}cc);">
                                                    {{ $news->published_at->format('Y/m/d') }}
                                                </span>
                                            @endif
                                            <span class="news-views-badge">
                                                <i class="fas fa-eye"></i>
                                                {{ $news->views_count }}
                                            </span>
                                        </div>

                                        <h3 class="text-sm md:text-base font-bold text-gray-800 mb-1.5 line-clamp-2 group-hover:text-green-600 transition-colors duration-300 leading-snug">
                                            {{ $news->title }}
                                        </h3>

                                        <p class="text-gray-500 text-[11px] md:text-xs mb-2 line-clamp-2 leading-relaxed">
                                            {{ $news->summary ?? Str::limit(strip_tags($news->content), 100) }}
                                        </p>

                                        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                            <span class="text-xs font-semibold group-hover:underline transition-colors duration-300" style="color: {{ $accent }};">
                                                اقرأ المزيد
                                                <i class="fas fa-arrow-left text-[9px] mr-0.5 group-hover:-translate-x-1 inline-block transition-transform duration-300"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

            </div>
        </section>
    @endif

    {{-- ================================================================
    DYNAMIC SECTIONS – Universal renderer
    ================================================================ --}}
    @if (isset($dynamicSections) && $dynamicSections->count() > 0)
        @foreach ($dynamicSections as $section)
            @php
                $ss          = $section->settings ?? [];
                $bgColor     = $ss['background_color'] ?? null;
                $txtColor    = $ss['text_color'] ?? null;
                $padTop      = $ss['padding_top'] ?? null;
                $padBottom   = $ss['padding_bottom'] ?? null;
                $showTitle   = $ss['show_title'] ?? true;
                $subtitle    = $ss['subtitle'] ?? null;
                $description = $ss['description'] ?? null;
                $icon        = $ss['icon'] ?? null;
                $columns     = $ss['columns'] ?? 3;

                // ── Build per-element typography styles ──────────────────────
                $buildTypoStyle = function (string $prefix) use ($ss): string {
                    $style = '';
                    if (!empty($ss["{$prefix}_color"]))  $style .= "color:{$ss["{$prefix}_color"]};";
                    if (!empty($ss["{$prefix}_size"]))   $style .= "font-size:{$ss["{$prefix}_size"]};";
                    if (!empty($ss["{$prefix}_weight"])) $style .= "font-weight:{$ss["{$prefix}_weight"]};";
                    if (!empty($ss["{$prefix}_align"]))  $style .= "text-align:{$ss["{$prefix}_align"]};";
                    return $style;
                };

                $titleStyle       = $buildTypoStyle('title');
                $subtitleStyle    = $buildTypoStyle('subtitle');
                $descriptionStyle = $buildTypoStyle('description');

                // Default align for description if not set
                if (empty($ss['description_align'])) {
                    $descriptionStyle .= 'text-align:right;';
                }

                // ── Section-wide styles ──────────────────────────────────────
                $sectionStyle = '';
                if ($bgColor && $bgColor !== '#ffffff') {
                    $sectionStyle .= "background-color:{$bgColor};";
                }
                if ($txtColor && $txtColor !== '#333333') {
                    $sectionStyle .= "color:{$txtColor};";
                }
                if ($padTop !== null) {
                    $sectionStyle .= "padding-top:{$padTop}px;";
                }
                if ($padBottom !== null) {
                    $sectionStyle .= "padding-bottom:{$padBottom}px;";
                }

                $colsMap = [
                    1 => 'grid-cols-1',
                    2 => 'grid-cols-1 sm:grid-cols-2',
                    3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
                    4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
                    6 => 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-6',
                ];
                $colsClass = $colsMap[$columns] ?? $colsMap[3];

                $layoutStyle = $ss['layout_style'] ?? 'grid';
                $isGrid = in_array($section->section_type, ['gallery', 'cards', 'stats']);
                $isTwoCol = in_array($section->section_type, ['text_with_image']);

                $hasSourceItems =
                    isset($section->content_source_items) &&
                    $section->content_source_items &&
                    $section->content_source_items->count() > 0;
                $hasManualItems = $section->items->count() > 0;

                // حساب كلاس الـ layout بناءً على نمط العرض المختار
                if ($layoutStyle === 'horizontal') {
                    $layoutClass = 'flex flex-row gap-3 md:gap-4 overflow-x-auto pb-4';
                } elseif ($layoutStyle === 'vertical') {
                    $layoutClass = 'flex flex-col gap-3 md:gap-4';
                } else {
                    $layoutClass = $isGrid
                        ? 'grid ' . $colsClass . ' gap-3 md:gap-4'
                        : ($isTwoCol
                            ? 'grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 items-center'
                            : 'space-y-4');
                }
            @endphp

            @if ($hasSourceItems || $hasManualItems)
                <section class="py-3 md:py-6 lg:py-8 {{ $section->css_classes ?? '' }} relative overflow-hidden"
                    @if ($sectionStyle) style="{{ $sectionStyle }}" @endif>
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

                        @if ($showTitle && $section->title)
                            <div class="mb-3 md:mb-5" style="{{ $titleStyle ? '' : 'text-align:right;' }}">
                                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold mb-2 {{ $titleStyle ? '' : 'text-gradient section-title' }}"
                                    @if ($titleStyle) style="{{ $titleStyle }}" @endif>
                                    @if ($icon)
                                        <i class="{{ $icon }} mr-2"></i>
                                    @endif
                                    {{ $section->title }}
                                </h2>
                                @if ($subtitle)
                                    <p class="text-sm md:text-base mt-1 {{ $subtitleStyle ? '' : 'text-gray-500' }}"
                                       @if ($subtitleStyle) style="{{ $subtitleStyle }}" @endif>{{ $subtitle }}</p>
                                @endif
                                @if ($description)
                                    <p class="text-xs md:text-sm mt-1 max-w-2xl {{ $descriptionStyle ? '' : 'text-gray-400' }}"
                                       style="{{ $descriptionStyle }}">
                                        {{ $description }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <div class="dynamic-section-content {{ $layoutClass }}">
                            @if ($hasSourceItems)
                                @foreach ($section->content_source_items as $entity)
                                    @include('partials.home-section-entity-card', [
                                        'entity' => $entity,
                                        'sourceType' => $section->content_source_type,
                                        'layoutStyle' => $layoutStyle,
                                    ])
                                @endforeach
                            @else
                                @foreach ($section->items as $item)
                                    @include('partials.home-section-item', ['item' => $item])
                                @endforeach
                            @endif
                        </div>

                    </div>
                </section>
            @endif
        @endforeach
    @endif

    {{-- ================================================================
    GALLERY – "اخترنا لك"
    ================================================================ --}}
    {{-- <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-br from-gray-50 to-green-50/50 relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-emerald-100 rounded-full blur-3xl opacity-30"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

            <div class="text-right mb-3 md:mb-5">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">اخترنا لك</h2>
            </div>

            @if ($latestGalleryImages->count() > 0)
            <div class="swiper gallerySwiper">
                <div class="swiper-wrapper">
                    @foreach ($latestGalleryImages as $img)
                    @php
                    $imgSrc =
                    $img->image_url ??
                    ((isset($img->image_path) ? asset('storage/' . $img->image_path) : null) ??
                    (isset($img->path) ? asset('storage/' . $img->path) : ''));
                    @endphp
                    <div class="swiper-slide">
                        <div class="relative group overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer gallery-item
                                                    {{ isset($img->is_active) && !$img->is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}"
                            data-media-type="{{ $img->media_type ?? 'image' }}" data-image-url="{{ $imgSrc }}"
                            data-youtube-url="{{ $img->youtube_url ?? '' }}" data-image-name="{{ $img->name ?? 'صورة' }}"
                            data-category-name="{{ $img->category->name ?? '' }}">

                            @if ($imgSrc)
                            <img src="{{ $imgSrc }}" alt="{{ $img->name ?? 'صورة' }}"
                                class="w-full h-32 md:h-40 lg:h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                            @endif

                            @if (isset($img->is_active) && !$img->is_active && Auth::check())
                            <div class="absolute top-2 right-2 z-10">
                                <span
                                    class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                            </div>
                            @endif

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-2 right-2 left-2">
                                    <p class="text-white text-xs md:text-sm font-bold truncate drop-shadow-lg">
                                        {{ $img->name ?? 'صورة' }}</p>
                                    @if ($img->category)
                                    <p class="text-white/90 text-xs mt-0.5">{{ $img->category->name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="swiper-button-next gallery-next"></div>
                <div class="swiper-button-prev gallery-prev"></div>
                <div class="swiper-pagination gallery-pagination"></div>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-images text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500 text-sm md:text-base">لا توجد صور متاحة حالياً</p>
            </div>
            @endif

        </div>
    </section> --}}

    {{-- ================================================================
    FAMILY COUNCILS
    ================================================================ --}}
    <section class="py-2 md:py-4 lg:py-6 bg-white mobile-section">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

            <div class="text-right mb-3 md:mb-4">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gradient mb-1">مجالس العائلة</h2>
                <div
                    class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0 mb-1 md:mb-2">
                </div>
            </div>

            @if ($councils && $councils->count() > 0)
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden shadow-lg rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 bg-white">
                                <thead class="gradient-primary text-white">
                                    <tr>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            المجلس</th>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            المدينة</th>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            الموقع</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($councils as $council)
                                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer {{ !$council->is_active && Auth::check() ? 'opacity-60' : '' }}"
                                            onclick="toggleCouncilDescription({{ $council->id }})">
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 text-right" dir="ltr">
                                                <div class="flex items-center justify-end gap-1">
                                                    <span
                                                        class="text-xs font-semibold text-gray-900">{{ $council->name }}</span>
                                                    <i class="fas fa-building text-green-600 ml-1.5 text-xs"></i>
                                                    @if ($council->description)
                                                        <i
                                                            class="fas fa-chevron-down text-green-500 text-xs transition-transform duration-300 council-chevron-{{ $council->id }}"></i>
                                                    @endif
                                                    @if (!$council->is_active && Auth::check())
                                                        <span
                                                            class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 text-right">
                                                <span class="text-xs text-gray-700">{{ $council->address ?? '-' }}</span>
                                            </td>
                                            <td
                                                class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-xs font-medium text-right">
                                                @if ($council->google_map_url)
                                                    <a href="{{ $council->google_map_url }}" target="_blank"
                                                        class="text-blue-600 hover:text-blue-900 inline-flex items-center"
                                                        onclick="event.stopPropagation();" title="فتح في جوجل ماب">
                                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"
                                                                fill="#EA4335" stroke="#fff" stroke-width="1.5" />
                                                        </svg>
                                                        <span class="mr-1 hidden lg:inline text-xs">الخريطة</span>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($council->description)
                                            <tr
                                                class="council-description-row council-description-{{ $council->id }} hidden">
                                                <td colspan="3" class="px-2 py-0 md:px-3">
                                                    <div
                                                        class="council-description-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                                                        <div
                                                            class="bg-gradient-to-r from-green-50 to-emerald-50 border-r-4 border-green-500 rounded-lg p-3 md:p-4 my-1.5 shadow-md">
                                                            <div class="flex items-start gap-2">
                                                                <i
                                                                    class="fas fa-info-circle text-green-600 text-sm flex-shrink-0 mt-0.5"></i>
                                                                <div>
                                                                    <h4
                                                                        class="text-xs md:text-sm font-semibold text-gray-800 mb-1.5">
                                                                        نبذة عن {{ $council->name }}</h4>
                                                                    <p
                                                                        class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                                                                        {{ $council->description }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <i class="fas fa-building text-gray-400 text-3xl md:text-4xl mb-3"></i>
                    <p class="text-gray-600 text-sm md:text-base">لا توجد مجالس متاحة حالياً</p>
                </div>
            @endif

        </div>
    </section>

    {{-- ================================================================
    FAMILY EVENTS – مناسبات العائلة
    ================================================================ --}}
    <section class="py-2 md:py-4 lg:py-6 bg-white mobile-section">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

            <div class="text-right mb-3 md:mb-4">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gradient mb-1">مناسبات العائلة</h2>
                <div
                    class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0 mb-1 md:mb-2">
                </div>
            </div>

            @if ($events && $events->count() > 0)
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden shadow-lg rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 bg-white">
                                <thead class="gradient-primary text-white">
                                    <tr>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            المناسبة</th>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            المدينة</th>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            الموقع</th>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($events as $event)
                                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer {{ !$event->is_active && Auth::check() ? 'opacity-60' : '' }}"
                                            onclick="toggleEventDescription({{ $event->id }})">
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 text-right" dir="ltr">
                                                <div class="flex items-start justify-end gap-1.5">
                                                    <span
                                                        class="text-xs font-semibold text-gray-900 break-words flex-1 text-right">{{ $event->title }}</span>
                                                    <div class="flex items-center gap-1 flex-shrink-0">
                                                        <i class="fas fa-calendar-alt text-green-600 text-xs"></i>
                                                        @if ($event->description)
                                                            <i
                                                                class="fas fa-chevron-down text-green-500 text-xs transition-transform duration-300 event-chevron-{{ $event->id }}"></i>
                                                        @endif
                                                        @if (!$event->is_active && Auth::check())
                                                            <span
                                                                class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 text-right">
                                                <span class="text-xs text-gray-700">{{ $event->city ?? '-' }}</span>
                                            </td>
                                            <td
                                                class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-xs font-medium text-right">
                                                @if ($event->location)
                                                    <a href="{{ $event->location }}" target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="inline-flex items-center text-blue-600 hover:text-blue-800 group"
                                                        onclick="event.stopPropagation();"
                                                        title="{{ $event->location_name ?? 'فتح الموقع على الخريطة' }}">
                                                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"
                                                                fill="#EA4335" stroke="#fff" stroke-width="1.5" />
                                                        </svg>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-right">
                                                <div class="text-xs font-medium text-gray-900 mb-1">
                                                    {{ $event->event_date->format('Y-m-d') }}
                                                </div>
                                                @if ($event->show_countdown && $event->event_date->isFuture())
                                                    <div class="event-countdown-{{ $event->id }} text-xs text-green-600 font-semibold text-right"
                                                        data-event-date="{{ $event->event_date->format('Y-m-d H:i:s') }}">
                                                        <span class="countdown-days"></span> يوم
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($event->description)
                                            <tr
                                                class="event-description-row event-description-{{ $event->id }} hidden">
                                                <td colspan="4" class="px-2 py-0 md:px-3">
                                                    <div
                                                        class="event-description-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                                                        <div
                                                            class="bg-gradient-to-r from-green-50 to-emerald-50 border-r-4 border-green-500 rounded-lg p-3 md:p-4 my-1.5 shadow-md">
                                                            <div class="flex items-start gap-2">
                                                                <i
                                                                    class="fas fa-info-circle text-green-600 text-sm flex-shrink-0 mt-0.5"></i>
                                                                <div>
                                                                    <h4
                                                                        class="text-xs md:text-sm font-semibold text-gray-800 mb-1.5">
                                                                        نبذة عن {{ $event->title }}</h4>
                                                                    <p
                                                                        class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                                                                        {{ $event->description }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <i class="fas fa-calendar-alt text-gray-400 text-3xl md:text-4xl mb-3"></i>
                    <p class="text-gray-600 text-sm md:text-base">لا توجد مناسبات متاحة حالياً</p>
                </div>
            @endif

        </div>
    </section>

    {{-- ================================================================
    FAMILY PROGRAMS
    ================================================================ --}}
    <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-br from-green-50 to-emerald-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-200 rounded-full blur-3xl opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

            <div class="text-right mb-3 md:mb-5">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">برامج العائلة</h2>
                <p class="text-gray-600 text-xs md:text-sm mt-1">فعاليات وأنشطة متنوعة</p>
            </div>

            @if ($programs && $programs->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1.5 md:gap-2.5">
                    @foreach ($programs as $program)
                        <a href="{{ route('programs.show', $program) }}"
                            class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 bg-white card-hover
                                                                  {{ !$program->program_is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}">
                            <div class="aspect-square p-1.5 md:p-2">
                                <img src="{{ asset('storage/' . $program->path) }}"
                                    alt="{{ $program->program_title ?? ($program->name ?? 'برنامج') }}"
                                    class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                            </div>
                            @if (!$program->program_is_active && Auth::check())
                                <div class="absolute top-2 right-2 z-10">
                                    <span
                                        class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-2 right-2 left-2 text-center">
                                    <p class="text-white text-xs md:text-sm font-bold line-clamp-2 drop-shadow-lg">
                                        {{ $program->program_title ?? ($program->name ?? 'برنامج') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm md:text-base">لا توجد برامج متاحة حالياً</p>
                </div>
            @endif

        </div>
    </section>

    {{-- ================================================================
    PROUD OF
    ================================================================ --}}
    <!-- @if ($proudOf && $proudOf->count() > 0)
                        <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-br from-green-50 to-emerald-50 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-200 rounded-full blur-3xl opacity-20"></div>
                            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

                                <div class="text-right mb-3 md:mb-5">
                                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">نفخر بهم</h2>
                                    <p class="text-gray-600 text-xs md:text-sm mt-1">إنجازات وإبداعات مميزة</p>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1.5 md:gap-2.5">
                                    @foreach ($proudOf as $item)
    <a href="{{ route('programs.show', $item) }}"
                                            class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 bg-white card-hover
                                      {{ !$item->proud_of_is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}">
                                            <div class="aspect-square p-1.5 md:p-2">
                                                <img src="{{ asset('storage/' . $item->path) }}"
                                                    alt="{{ $item->proud_of_title ?? ($item->name ?? 'عنصر') }}"
                                                    class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                            </div>
                                            @if (!$item->proud_of_is_active && Auth::check())
    <div class="absolute top-2 right-2 z-10">
                                                    <span
                                                        class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                                </div>
    @endif
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <div class="absolute bottom-2 right-2 left-2 text-center">
                                                    <p class="text-white text-xs md:text-sm font-bold line-clamp-2 drop-shadow-lg">
                                                        {{ $item->proud_of_title ?? ($item->name ?? 'عنصر') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
    @endforeach
                                </div>

                            </div>
                        </section>
                    @endif -->

    {{-- ================================================================
    ACADEMIC DEGREES – Graduates
    ================================================================ --}}
    @php
        $bachelorGraduates = $latestGraduates->where('degree_type', 'bachelor')->take(10);
        $masterGraduates = $latestGraduates->where('degree_type', 'master')->take(10);
        $phdGraduates = $latestGraduates->where('degree_type', 'phd')->take(10);
        $bachelorCategoryId = $bachelorGraduates->first()?->category_id;
        $masterCategoryId = $masterGraduates->first()?->category_id;
        $phdCategoryId = $phdGraduates->first()?->category_id;
    @endphp

    @if (
        (isset($bachelorTotalCount) && $bachelorTotalCount > 0) ||
            (isset($masterTotalCount) && $masterTotalCount > 0) ||
            (isset($phdTotalCount) && $phdTotalCount > 0))
        <section class="py-3 md:py-6 lg:py-8 bg-white relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-yellow-100 rounded-full blur-3xl opacity-20"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

                <div class="text-right mb-3 md:mb-5">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">الشهادات العلمية
                    </h2>
                    <p class="text-gray-600 text-xs md:text-sm mt-1">نفخر بإنجازاتهم الأكاديمية</p>
                </div>

                <div class="grid grid-cols-3 gap-2 md:gap-3 mb-4">
                    @if (isset($phdTotalCount) && $phdTotalCount > 0 && $phdCategoryId)
                        <a href="{{ route('gallery.articles', ['category' => $phdCategoryId]) }}"
                            class="group relative overflow-hidden rounded-xl shadow-lg bg-gradient-to-br from-yellow-100 via-yellow-50 to-amber-100 p-1.5 md:p-4 card-hover border-2 border-yellow-200">
                            <div class="text-center">
                                <div
                                    class="w-10 h-10 md:w-16 md:h-16 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-1 md:mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-graduation-cap text-white text-base md:text-2xl"></i>
                                </div>
                                <h3 class="text-xs md:text-xl font-bold text-yellow-800 mb-0.5">الدكتوراه</h3>
                                <div class="text-lg md:text-4xl font-bold text-yellow-600 mb-0.5">{{ $phdTotalCount }}
                                </div>
                                <p class="text-yellow-700 text-[10px] md:text-xs">خريج</p>
                            </div>
                        </a>
                    @endif

                    @if (isset($masterTotalCount) && $masterTotalCount > 0 && $masterCategoryId)
                        <a href="{{ route('gallery.articles', ['category' => $masterCategoryId]) }}"
                            class="group relative overflow-hidden rounded-xl shadow-lg bg-gradient-to-br from-indigo-100 via-indigo-50 to-purple-100 p-1.5 md:p-4 card-hover border-2 border-indigo-200">
                            <div class="text-center">
                                <div
                                    class="w-10 h-10 md:w-16 md:h-16 bg-indigo-400 rounded-full flex items-center justify-center mx-auto mb-1 md:mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-user-graduate text-white text-base md:text-2xl"></i>
                                </div>
                                <h3 class="text-xs md:text-xl font-bold text-indigo-800 mb-0.5">الماجستير</h3>
                                <div class="text-lg md:text-4xl font-bold text-indigo-600 mb-0.5">{{ $masterTotalCount }}
                                </div>
                                <p class="text-indigo-700 text-[10px] md:text-xs">خريج</p>
                            </div>
                        </a>
                    @endif

                    @if (isset($bachelorTotalCount) && $bachelorTotalCount > 0 && $bachelorCategoryId)
                        <a href="{{ route('gallery.articles', ['category' => $bachelorCategoryId]) }}"
                            class="group relative overflow-hidden rounded-xl shadow-lg bg-gradient-to-br from-green-100 via-green-50 to-emerald-100 p-1.5 md:p-4 card-hover border-2 border-green-200">
                            <div class="text-center">
                                <div
                                    class="w-10 h-10 md:w-16 md:h-16 bg-green-400 rounded-full flex items-center justify-center mx-auto mb-1 md:mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-award text-white text-base md:text-2xl"></i>
                                </div>
                                <h3 class="text-xs md:text-xl font-bold text-green-800 mb-0.5">البكالوريوس</h3>
                                <div class="text-lg md:text-4xl font-bold text-green-600 mb-0.5">{{ $bachelorTotalCount }}
                                </div>
                                <p class="text-green-700 text-[10px] md:text-xs">خريج</p>
                            </div>
                        </a>
                    @endif
                </div>

            </div>
        </section>
    @endif

    {{-- ================================================================
    COURSES – أكاديمية السريع
    ================================================================ --}}
    <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-br from-gray-50 to-green-50/50 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-green-200 rounded-full blur-3xl opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

            <div class="text-right mb-3 md:mb-5">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">دورات أكاديمية
                    السريع</h2>
                <p class="text-gray-600 text-xs md:text-sm mt-1">تعلم وتطور مع دوراتنا المتميزة</p>
            </div>

            @if ($courses->count() > 0)
                <div class="swiper coursesSwiper">
                    <div class="swiper-wrapper">
                        @foreach ($courses as $course)
                            <div class="swiper-slide">
                                <div
                                    class="glass-card rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 h-full flex flex-col
                                                                            {{ !$course->is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}">

                                    <div class="relative h-32 md:h-36 gradient-primary overflow-hidden">
                                        @if ($course->image_url)
                                            <img src="{{ $course->image_url }}" alt="{{ $course->title }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <i
                                                    class="fas fa-book-open text-white text-4xl opacity-30 animate-float"></i>
                                            </div>
                                        @endif
                                        @if (!$course->is_active && Auth::check())
                                            <div class="absolute top-2 right-2 z-10">
                                                <span
                                                    class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-2.5 md:p-3 flex-1 flex flex-col">
                                        <h3 class="text-sm md:text-base font-bold text-gray-800 mb-1 line-clamp-2">
                                            {{ $course->title }}
                                        </h3>
                                        <p class="text-gray-600 text-xs mb-2 line-clamp-2 flex-1">
                                            {{ $course->description ?? 'دورة تدريبية متميزة' }}
                                        </p>

                                        <div class="space-y-0.5 mb-2">
                                            @if ($course->instructor)
                                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                    <i class="fas fa-user text-green-500"></i>
                                                    <span>{{ $course->instructor }}</span>
                                                </div>
                                            @endif
                                            @if ($course->duration)
                                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                    <i class="fas fa-clock text-green-500"></i>
                                                    <span>{{ $course->duration }}</span>
                                                </div>
                                            @endif
                                            @if ($course->students > 0)
                                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                    <i class="fas fa-users text-green-500"></i>
                                                    <span>{{ $course->students }} طالب</span>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($course->link)
                                            <a href="{{ $course->link }}" target="_blank"
                                                class="w-full py-2 px-3 gradient-secondary text-white rounded-lg text-xs md:text-sm font-semibold hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                                <span>سجل الآن</span>
                                                <i class="fas fa-arrow-left"></i>
                                            </a>
                                        @else
                                            <button
                                                class="w-full py-2 px-3 gradient-secondary text-white rounded-lg text-xs md:text-sm font-semibold hover:shadow-lg transition-all">
                                                قريباً
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next courses-next"></div>
                    <div class="swiper-button-prev courses-prev"></div>
                    <div class="swiper-pagination courses-pagination"></div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-book-open text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm md:text-base">لا توجد دورات متاحة حالياً</p>
                </div>
            @endif

        </div>
    </section>

    {{-- ================================================================
    GALLERY MODAL
    ================================================================ --}}
    <div id="galleryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/95 backdrop-blur-sm"
        style="display:none;">
        <div class="relative w-full h-full flex items-center justify-center p-2 md:p-4">

            {{-- Close button --}}
            <button id="closeGalleryModal"
                class="absolute top-2 right-2 md:top-4 md:right-4 z-50 text-white bg-red-600/90 hover:bg-red-600 rounded-full p-2.5 md:p-3 shadow-2xl hover:scale-110 active:scale-95 transition-all">
                <i class="fas fa-times text-lg md:text-2xl"></i>
            </button>

            <div
                class="md:hidden absolute top-2 left-2 z-40 bg-black/60 text-white text-[10px] px-2 py-1 rounded-lg backdrop-blur-sm">
                <i class="fas fa-hand-pointer mr-1"></i> اضغط للخروج
            </div>

            <div class="relative max-w-7xl w-full h-full flex items-center justify-center">
                <div id="galleryModalImageContainer" class="hidden w-full h-full flex items-center justify-center">
                    <img id="galleryModalImage" src="" alt=""
                        class="max-w-full max-h-full object-contain rounded-lg cursor-pointer" onclick="Gallery.close()">
                </div>
                <div id="galleryModalVideoContainer" class="hidden w-full h-full flex items-center justify-center">
                    <div class="w-full" style="max-width:90vw; aspect-ratio:16/9;">
                        <iframe id="galleryModalVideo" src="" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen class="w-full h-full rounded-lg"></iframe>
                    </div>
                </div>
            </div>

            <div id="galleryModalInfo"
                class="absolute bottom-2 md:bottom-4 right-2 left-2 md:right-4 md:left-4 text-white text-center bg-black/70 backdrop-blur-md rounded-lg p-3 md:p-4">
                <h3 id="galleryModalTitle" class="text-base md:text-xl font-bold mb-1"></h3>
                <p id="galleryModalCategory" class="text-xs md:text-sm text-gray-300 mb-2"></p>
                <button onclick="Gallery.close()"
                    class="md:hidden mt-2 bg-red-600 hover:bg-red-700 text-white text-xs px-4 py-2 rounded-lg font-semibold">
                    <i class="fas fa-times mr-1"></i> إغلاق
                </button>
                <p class="hidden md:block text-xs text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i> اضغط على X أو اضغط ESC للخروج
                </p>
            </div>

        </div>
    </div>

    <a href="https://wa.me/966539895800" target="_blank"
        style="
     position: fixed;
     bottom: 24px;
     left: 24px;
     width: 58px;
     height: 58px;
     background: linear-gradient(135deg, #25d366, #128c7e);
     border-radius: 50%;
     display: flex;
     align-items: center;
     justify-content: center;
     box-shadow: 0 6px 20px rgba(37,211,102,0.45);
     text-decoration: none;
     z-index: 9999;
     transition: transform 0.2s ease, box-shadow 0.2s ease;
   "
        onmouseover="this.style.transform='scale(1.12)'; this.style.boxShadow='0 10px 28px rgba(37,211,102,0.6)'"
        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 6px 20px rgba(37,211,102,0.45)'">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 32 32" fill="white">
            <path
                d="M16 2C8.268 2 2 8.268 2 16c0 2.478.675 4.8 1.85 6.8L2 30l7.4-1.825A13.94 13.94 0 0 0 16 30c7.732 0 14-6.268 14-14S23.732 2 16 2zm0 25.5a11.44 11.44 0 0 1-5.85-1.6l-.42-.25-4.39 1.08 1.1-4.27-.27-.44A11.47 11.47 0 0 1 4.5 16C4.5 9.648 9.648 4.5 16 4.5S27.5 9.648 27.5 16 22.352 27.5 16 27.5zm6.29-8.57c-.345-.172-2.04-1.006-2.355-1.12-.315-.115-.545-.172-.775.172-.23.345-.89 1.12-1.09 1.35-.2.23-.4.258-.745.086-.345-.172-1.456-.537-2.773-1.71-1.025-.914-1.717-2.043-1.917-2.388-.2-.345-.021-.531.15-.703.154-.155.345-.4.517-.6.172-.2.23-.345.345-.575.115-.23.057-.43-.029-.602-.086-.172-.775-1.868-1.062-2.558-.28-.672-.564-.58-.775-.59l-.66-.011c-.23 0-.602.086-.917.43s-1.204 1.177-1.204 2.87 1.232 3.33 1.404 3.56c.172.23 2.426 3.705 5.877 5.195.821.355 1.463.567 1.962.725.824.262 1.574.225 2.167.137.661-.099 2.04-.834 2.327-1.638.287-.804.287-1.493.2-1.638-.086-.144-.316-.23-.66-.4z" />
        </svg>
    </a>

@endsection

@push('scripts')
    <!-- SortableJS for Drag and Drop Ordering -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        /* ================================================================
           SWIPERS
           ================================================================ */
        document.addEventListener('DOMContentLoaded', function() {
            var totalSlides = {{ $latestImages->count() ?? 0 }};
            var totalImages = {{ $latestGalleryImages->count() ?? 0 }};
            var totalCourses = {{ $courses->count() ?? 0 }};

            // Hero
            if (totalSlides > 0 && typeof Swiper !== 'undefined') {
                new Swiper('.heroSwiper', {
                    slidesPerView: 1,
                    loop: totalSlides > 1,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    speed: 1000,
                    pagination: {
                        el: '.hero-pagination',
                        clickable: true,
                        dynamicBullets: true
                    },
                    navigation: {
                        nextEl: '.hero-next',
                        prevEl: '.hero-prev'
                    },
                    keyboard: {
                        enabled: true
                    },
                });
            }

            // Gallery & Courses – wait one frame so Swiper gets correct dimensions
            var initSwipers = function() {
                if (totalImages > 0 && typeof Swiper !== 'undefined' && document.querySelector(
                        '.gallerySwiper')) {
                    new Swiper('.gallerySwiper', {
                        slidesPerView: 2,
                        spaceBetween: 15,
                        loop: totalImages > 4,
                        autoplay: {
                            delay: 4000,
                            disableOnInteraction: false
                        },
                        pagination: {
                            el: '.gallery-pagination',
                            clickable: true,
                            dynamicBullets: true
                        },
                        navigation: {
                            nextEl: '.gallery-next',
                            prevEl: '.gallery-prev'
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 3,
                                spaceBetween: 20
                            },
                            1024: {
                                slidesPerView: 4,
                                spaceBetween: 24
                            },
                            1280: {
                                slidesPerView: 5,
                                spaceBetween: 24
                            },
                        },
                    });
                }
                if (totalCourses > 0 && typeof Swiper !== 'undefined' && document.querySelector(
                        '.coursesSwiper')) {
                    new Swiper('.coursesSwiper', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: totalCourses > 3,
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false
                        },
                        pagination: {
                            el: '.courses-pagination',
                            clickable: true,
                            dynamicBullets: true
                        },
                        navigation: {
                            nextEl: '.courses-next',
                            prevEl: '.courses-prev'
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 2,
                                spaceBetween: 20
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 24
                            },
                        },
                    });
                }
            };

            requestAnimationFrame
                ?
                requestAnimationFrame(function() {
                    setTimeout(initSwipers, 50);
                }) :
                setTimeout(initSwipers, 100);
        });

        /* ================================================================
           ACCORDION – Councils & Events
           ================================================================ */
        function toggleAccordion(type, id) {
            var row = document.querySelector('.' + type + '-description-' + id);
            var content = row && row.querySelector('.' + type + '-description-content');
            var chevron = document.querySelector('.' + type + '-chevron-' + id);
            if (!row || !content) return;

            var isHidden = row.classList.contains('hidden');
            if (isHidden) {
                row.classList.remove('hidden');
                setTimeout(function() {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }, 10);
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            } else {
                content.style.maxHeight = '0';
                if (chevron) chevron.style.transform = 'rotate(0deg)';
                setTimeout(function() {
                    row.classList.add('hidden');
                }, 500);
            }
        }

        function toggleCouncilDescription(id) {
            toggleAccordion('council', id);
        }

        function toggleEventDescription(id) {
            toggleAccordion('event', id);
        }

        /* ================================================================
           EVENTS – Day countdown
           ================================================================ */
        function updateEventCountdowns() {
            document.querySelectorAll('[class*="event-countdown-"]').forEach(function(el) {
                var dateStr = el.getAttribute('data-event-date');
                if (!dateStr) return;
                var diff = new Date(dateStr) - new Date();
                if (diff <= 0) {
                    el.innerHTML = '<span class="text-red-600">انتهت المناسبة</span>';
                    return;
                }
                var daysEl = el.querySelector('.countdown-days');
                if (daysEl) daysEl.textContent = Math.floor(diff / 86400000);
            });
        }
        updateEventCountdowns();
        setInterval(updateEventCountdowns, 60000);

        /* ================================================================
           GALLERY MODAL
           ================================================================ */
        var Gallery = (function() {
            var modal = document.getElementById('galleryModal');
            var imageWrapper = document.getElementById('galleryModalImageContainer');
            var videoWrapper = document.getElementById('galleryModalVideoContainer');
            var imgEl = document.getElementById('galleryModalImage');
            var iframeEl = document.getElementById('galleryModalVideo');
            var titleEl = document.getElementById('galleryModalTitle');
            var catEl = document.getElementById('galleryModalCategory');

            function extractYoutubeId(url) {
                var patterns = [
                    /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
                    /youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/,
                ];
                for (var i = 0; i < patterns.length; i++) {
                    var m = url.match(patterns[i]);
                    if (m) return m[1];
                }
                return null;
            }

            function open(item) {
                var type = item.getAttribute('data-media-type');
                var imgUrl = item.getAttribute('data-image-url');
                var ytUrl = item.getAttribute('data-youtube-url');
                var name = item.getAttribute('data-image-name');
                var category = item.getAttribute('data-category-name');

                titleEl.textContent = name;
                catEl.textContent = category || '';

                if (type === 'youtube' && ytUrl) {
                    var vid = extractYoutubeId(ytUrl);
                    if (vid) {
                        iframeEl.src = 'https://www.youtube.com/embed/' + vid + '?autoplay=1';
                        imageWrapper.classList.add('hidden');
                        videoWrapper.classList.remove('hidden');
                    } else {
                        imgEl.src = imgUrl;
                        videoWrapper.classList.add('hidden');
                        imageWrapper.classList.remove('hidden');
                    }
                } else {
                    imgEl.src = imgUrl;
                    videoWrapper.classList.add('hidden');
                    iframeEl.src = '';
                    imageWrapper.classList.remove('hidden');
                }

                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function close() {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = '';
                iframeEl.src = '';
                imageWrapper.classList.add('hidden');
                videoWrapper.classList.add('hidden');
            }

            // Bind events after DOM ready
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.gallery-item').forEach(function(item) {
                    item.addEventListener('click', function() {
                        open(this);
                    });
                });

                document.getElementById('closeGalleryModal')
                    .addEventListener('click', close);

                modal.addEventListener('click', function(e) {
                    if (e.target === modal) close();
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') close();
                });

                // Swipe-down to close
                var sy = 0;
                modal.addEventListener('touchstart', function(e) {
                    sy = e.changedTouches[0].screenY;
                }, {
                    passive: true
                });
                modal.addEventListener('touchend', function(e) {
                    if (sy - e.changedTouches[0].screenY > 100) close();
                }, {
                    passive: true
                });
            });

            return {
                open: open,
                close: close
            };
        })();

        /* ================================================================
           QUIZ – Next event countdown  (بدون reload – AJAX عند الانتهاء)
           ================================================================ */
        @if (isset($nextQuizEvent) && $nextQuizEvent)
            (function() {
                var el = document.getElementById('quizCountdownTarget');
                if (!el) return;

                var target = parseInt(el.value, 10);
                var ids = ['days', 'hours', 'minutes', 'seconds'];
                var finished = false;
                var timer;

                function zeroAll() {
                    ids.forEach(function(id) {
                        var node = document.getElementById('countdown-' + id);
                        if (node) node.textContent = '0';
                    });
                }

                /* ── جلب المسابقة النشطة بـ AJAX وحقنها بدون reload ── */
                function fetchActiveQuiz() {
                    var wrapper = document.getElementById('quizCountdownSection');

                    /* حالة تحميل مؤقتة */
                    if (wrapper) {
                        wrapper.innerHTML =
                            '<div class="glass-card rounded-3xl p-6 text-center shadow-lg" style="box-shadow:0 0 30px rgba(34,197,94,.15);">' +
                            '<div class="inline-flex items-center gap-2 text-green-600 text-sm font-medium">' +
                            '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">' +
                            '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                            '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>' +
                            '</svg><span>جاري تحميل المسابقة...</span></div></div>';
                    }

                    fetch(window.location.href, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html'
                            }
                        })
                        .then(function(res) {
                            return res.text();
                        })
                        .then(function(html) {
                            var parser = new DOMParser();
                            var doc = parser.parseFromString(html, 'text/html');
                            var newActive = doc.getElementById('activeQuizSection');

                            if (newActive) {
                                /* المسابقة بدأت → استبدل قسم العداد بكتلة المسابقة */
                                if (wrapper) wrapper.outerHTML = newActive.outerHTML;

                                /* شغّل عداد نهاية المسابقة */
                                var endInput = document.getElementById('aqEndTime');
                                if (endInput) startActiveQuizTimer(parseInt(endInput.value, 10));

                                /* شغّل عداد كشف الأسئلة */
                                var visInput = document.getElementById('aqQuestionsVisibleAt');
                                if (visInput) startRevealCountdown(parseInt(visInput.value, 10));
                            } else {
                                /* لم تبدأ بعد → إعادة المحاولة بعد 10 ثوانٍ */
                                var current = document.getElementById('quizCountdownSection');
                                if (current) {
                                    current.innerHTML =
                                        '<div class="glass-card rounded-3xl p-4 text-center shadow-lg border-2 border-amber-200 bg-amber-50">' +
                                        '<i class="fas fa-clock text-amber-500 text-2xl mb-2 block"></i>' +
                                        '<p class="text-amber-800 font-medium text-sm">جاري تجهيز المسابقة، سيتم التحديث تلقائياً...</p>' +
                                        '</div>';
                                }
                                setTimeout(fetchActiveQuiz, 10000);
                            }
                        })
                        .catch(function() {
                            setTimeout(fetchActiveQuiz, 15000);
                        });
                }

                /* ── العداد الرئيسي ── */
                function tick() {
                    var diff = target - Date.now();

                    if (diff <= 0 && !finished) {
                        finished = true;
                        clearInterval(timer);
                        zeroAll();
                        setTimeout(fetchActiveQuiz, 500);
                        return;
                    }

                    var d = Math.floor(diff / 86400000);
                    var h = Math.floor((diff % 86400000) / 3600000);
                    var m = Math.floor((diff % 3600000) / 60000);
                    var s = Math.floor((diff % 60000) / 1000);

                    [d, h, m, s].forEach(function(val, i) {
                        var node = document.getElementById('countdown-' + ids[i]);
                        if (node) node.textContent = val;
                    });
                }

                tick();
                timer = setInterval(tick, 1000);
            })();
        @endif

        /* ================================================================
           QUIZ – Active competition helpers
           (دوال global عشان AJAX يقدر يستدعيها بعد حقن HTML جديد)
           ================================================================ */

        /* عداد h:m:s لنهاية المسابقة الفعلية */
        function startActiveQuizTimer(endTimestamp) {
            function pad(n) {
                return n.toString().padStart(2, '0');
            }

            function tick() {
                var diff = endTimestamp - Date.now();
                var hEl = document.getElementById('aq-hours');
                var mEl = document.getElementById('aq-minutes');
                var sEl = document.getElementById('aq-seconds');
                if (!hEl) return; // العنصر اتشال من DOM
                if (diff <= 0) {
                    hEl.textContent = '00';
                    mEl.textContent = '00';
                    sEl.textContent = '00';
                    window.location.reload(); // انتهاء المسابقة الفعلية → reload
                    return;
                }
                hEl.textContent = pad(Math.floor(diff / 3600000));
                mEl.textContent = pad(Math.floor((diff % 3600000) / 60000));
                sEl.textContent = pad(Math.floor((diff % 60000) / 1000));
            }
            tick();
            setInterval(tick, 1000);
        }

        /* عداد الثواني حتى ظهور نص الأسئلة */
        function startRevealCountdown(visibleAtTimestamp) {
            var banner = document.getElementById('activeQuizQuestionsCountdown');
            var descs = document.getElementById('activeQuizDescriptionsOnlyBlock');
            var block = document.getElementById('activeQuizQuestionsBlock');
            var display = document.getElementById('aqQuestionsSeconds');
            if (!banner || !block || !display) return;

            function reveal() {
                banner.style.display = 'none';
                if (descs) descs.style.display = 'none';
                block.style.display = '';
            }

            var iv = setInterval(function() {
                var remaining = Math.ceil((visibleAtTimestamp - Date.now()) / 1000);
                if (remaining <= 0) {
                    clearInterval(iv);
                    display.textContent = '0';
                    reveal(); // فقط كشف الأسئلة – بدون reload
                } else {
                    display.textContent = remaining;
                }
            }, 1000);

            /* تشغيل فوري قبل أول tick */
            var init = Math.ceil((visibleAtTimestamp - Date.now()) / 1000);
            display.textContent = init > 0 ? init : '0';
            if (init <= 0) {
                clearInterval(iv);
                reveal();
            }
        }

        /* تشغيل عند تحميل الصفحة لو المسابقة موجودة أصلاً من السيرفر */
        @if (isset($activeQuizCompetition) && $activeQuizCompetition)
            (function() {
                var endInput = document.getElementById('aqEndTime');
                if (endInput) startActiveQuizTimer(parseInt(endInput.value, 10));

                var visInput = document.getElementById('aqQuestionsVisibleAt');
                if (visInput) startRevealCountdown(parseInt(visInput.value, 10));
            })();
        @endif
        /* ================================================================
           QUIZ – Home Forms Javascript Checkbox Validation helpers
           ================================================================ */

        function validateHomeQuiz(event, buttonElement) {
            const form = buttonElement.closest('form');
            if (!form) return;

            const orderingTotal = form.querySelector('.ordering-total-count');
            if (orderingTotal) {
                const total = parseInt(orderingTotal.value);
                const placed = form.querySelectorAll('input[name="answer[]"].ordering-img-hidden').length;
                if (placed < total) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'تنبيه',
                        text: `يجب وضع جميع الصور (${total}) في المربعات قبل الإرسال.`,
                        confirmButtonColor: '#22c55e',
                        confirmButtonText: 'حسناً'
                    });
                    return false;
                }
            }

            const requiredCountInput = form.querySelector('.required-choices-count');
            if (requiredCountInput) {
                const requiredCount = parseInt(requiredCountInput.value);
                const checkedBoxes = form.querySelectorAll('input[type="checkbox"][name="answer[]"]:checked');

                if (checkedBoxes.length !== requiredCount) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'تنبيه',
                        text: `الرجاء اختيار عدد ${requiredCount} إجابات كما هو مطلوب بالسؤال.`,
                        confirmButtonColor: '#22c55e',
                        confirmButtonText: 'حسناً'
                    });
                    return false;
                }
            }

            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Checkbox limits constraint listener
            function attachCheckboxListeners() {
                const forms = document.querySelectorAll('#activeQuizQuestionsBlock form');
                forms.forEach(form => {
                    const requiredCountInput = form.querySelector('.required-choices-count');
                    if (requiredCountInput) {
                        const requiredCount = parseInt(requiredCountInput.value);
                        const checkboxes = form.querySelectorAll('input[type="checkbox"][name="answer[]"]');

                        checkboxes.forEach(cb => {
                            // Only add if not already added
                            if (!cb.hasAttribute('data-listener-attached')) {
                                cb.addEventListener('change', function() {
                                    const checkedCount = form.querySelectorAll(
                                        'input[type="checkbox"][name="answer[]"]:checked'
                                        ).length;
                                    if (checkedCount > requiredCount) {
                                        this.checked = false;
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'تم تجاوز الحد الأقصى',
                                            text: `لا يمكنك اختيار أكثر من ${requiredCount} إجابات.`,
                                            confirmButtonColor: '#22c55e',
                                            confirmButtonText: 'حسناً',
                                            toast: true,
                                            position: 'top-end',
                                            showConfirmButton: false,
                                            timer: 3000
                                        });
                                    }
                                });
                                cb.setAttribute('data-listener-attached', 'true');
                            }
                        });
                    }
                });
            }

            // Run on load
            attachCheckboxListeners();

            function syncSlotStates(zone, sourceGrid) {
                if (!zone) return;
                var qId = zone.getAttribute('data-question-id');
                var input = document.getElementById('orderInput-' + qId);
                if (!input) return;

                var ids = [];
                // Query all slots across all grid containers in this zone
                zone.querySelectorAll('.ordering-slot').forEach(function(slot) {
                    var img = slot.querySelector('.ordering-img-item');
                    slot.classList.toggle('has-image', !!img);
                    if (img) ids.push(img.getAttribute('data-id'));
                });
                input.value = ids.join(',');

                var parent = input.parentNode;
                parent.querySelectorAll('input.ordering-img-hidden').forEach(function(h) {
                    h.remove();
                });
                ids.forEach(function(id) {
                    var h = document.createElement('input');
                    h.type = 'hidden';
                    h.name = 'answer[]';
                    h.value = id;
                    h.className = 'ordering-img-hidden';
                    parent.insertBefore(h, input);
                });
            }

            function initImageOrdering() {
                document.querySelectorAll('.ordering-source-grid:not(.sortable-initialized)').forEach(function(
                    sourceGrid) {
                    var qId = sourceGrid.closest('.ordering-source-zone').getAttribute('data-question-id');
                    var targetZone = document.querySelector('.ordering-target-zone[data-question-id="' + qId + '"]');
                    if (!targetZone) return;
                    
                    var groupName = 'imgOrder-' + qId;

                    new Sortable(sourceGrid, {
                        group: {
                            name: groupName,
                            pull: 'clone',
                            put: true
                        },
                        animation: 150,
                        sort: false,
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        fallbackOnBody: true
                    });

                    targetZone.querySelectorAll('.ordering-slot').forEach(function(slot) {
                        if (slot.classList.contains('slot-init')) return;

                        new Sortable(slot, {
                            group: {
                                name: groupName,
                                put: function() {
                                    return !slot.querySelector('.ordering-img-item');
                                }
                            },
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            fallbackOnBody: true,
                            onAdd: function(evt) {
                                if (evt.from === sourceGrid) {
                                    var orig = sourceGrid.querySelector('[data-id="' +
                                        evt.item.getAttribute('data-id') + '"]');
                                    if (orig && orig !== evt.item) orig.remove();
                                }
                                syncSlotStates(targetZone, sourceGrid);
                            },
                            onRemove: function(evt) {
                                if (evt.to !== sourceGrid) {
                                    sourceGrid.appendChild(evt.item);
                                }
                                syncSlotStates(targetZone, sourceGrid);
                            }
                        });

                        slot.addEventListener('dragenter', function() {
                            slot.classList.add('drag-hover');
                        });
                        slot.addEventListener('dragleave', function() {
                            slot.classList.remove('drag-hover');
                        });
                        slot.addEventListener('drop', function() {
                            slot.classList.remove('drag-hover');
                        });

                        slot.classList.add('slot-init');
                    });

                    sourceGrid.classList.add('sortable-initialized');
                });
            }

            function initSortables() {
                var lists = document.querySelectorAll('.sortable-list:not(.sortable-initialized)');
                lists.forEach(function(list) {
                    new Sortable(list, {
                        animation: 150,
                        ghostClass: 'bg-green-50',
                        chosenClass: 'sortable-chosen'
                    });
                    list.classList.add('sortable-initialized');
                });
                initImageOrdering();
            }
            initSortables();

            // Setup a MutationObserver to re-attach if AJAX replaces the content
            const observerTarget = document.getElementById('quizCountdownSection') || document.getElementById(
                'activeQuizSection');
            if (observerTarget && observerTarget.parentNode) {
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.addedNodes.length > 0) {
                            attachCheckboxListeners();
                            initSortables();
                        }
                    });
                });
                observer.observe(observerTarget.parentNode, {
                    childList: true,
                    subtree: true
                });
            }
        });
    </script>
@endpush
