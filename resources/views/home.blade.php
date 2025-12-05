<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرئيسية - تواصل عائلة السريِّع</title>
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

        /* Gradient Backgrounds */
        .gradient-primary {
            background: linear-gradient(135deg, #145147 0%, #37a05c 50%, #2d8a4e 100%);
        }

        .gradient-secondary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* Hero Slideshow */
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

        /* Gallery & Courses Swiper */
        .gallerySwiper,
        .coursesSwiper {
            padding: 15px 40px 40px !important;
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

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-fade-in-scale {
            animation: fadeInScale 0.6s ease-out forwards;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* Card Hover Effects */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Section Title */
        .section-title {
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            right: 0;
            width: 60%;
            height: 4px;
            background: linear-gradient(90deg, #37a05c 0%, transparent 100%);
            border-radius: 2px;
        }

        /* Text Gradient */
        .text-gradient {
            background: linear-gradient(135deg, #145147 0%, #37a05c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Degree Cards */
        .degree-card {
            position: relative;
            overflow: hidden;
        }

        .degree-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            transition: all 0.6s ease;
            opacity: 0;
        }

        .degree-card:hover::before {
            opacity: 1;
            transform: scale(1.2);
        }

        /* Responsive */
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

            /* إظهار الأسماء دائماً على الهواتف (لا يوجد hover) */
            .person-name-overlay {
                opacity: 1 !important;
            }

            .gallerySwiper,
            .coursesSwiper {
                padding: 15px 40px 40px !important;
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
</head>

<body class="bg-gradient-to-br from-gray-50 via-green-50/30 to-emerald-50/50">
    @include('partials.main-header')

    {{-- Hero Section with Slideshow --}}
    <section class="relative h-[180px] sm:h-[220px] md:h-[280px] lg:h-[320px] overflow-hidden">
        @if ($latestImages->count() > 0)
            <div class="swiper heroSwiper">
                <div class="swiper-wrapper">
                    @foreach ($latestImages->take(10) as $slideshowImage)
                        <div class="swiper-slide">
                            @if ($slideshowImage->link)
                                <a href="{{ $slideshowImage->link }}" target="_blank"
                                    class="block relative w-full h-full">
                                @else
                                    <div class="relative w-full h-full">
                            @endif
                            @if ($slideshowImage->image_url)
                                <img src="{{ $slideshowImage->image_url }}" alt="{{ $slideshowImage->title ?? 'صورة' }}"
                                    class="w-full h-full object-cover">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent">
                            </div>
                            @if ($slideshowImage->title || $slideshowImage->description)
                                <div class="absolute bottom-4 md:bottom-6 right-4 left-4 md:right-6 md:left-6 z-10">
                                    <div class="glass-effect rounded-xl p-3 md:p-4 max-w-2xl animate-fade-in-up">
                                        @if ($slideshowImage->title)
                                            <h2
                                                class="text-white text-base md:text-lg lg:text-xl font-bold mb-1 md:mb-2 drop-shadow-lg">
                                                {{ $slideshowImage->title }}
                                            </h2>
                                        @endif
                                        @if ($slideshowImage->description)
                                            <p class="text-white/95 text-xs md:text-sm line-clamp-2 mb-2 drop-shadow">
                                                {{ $slideshowImage->description }}
                                            </p>
                                        @endif
                                        @if ($slideshowImage->link)
                                            <span
                                                class="inline-flex items-center gap-1.5 text-white text-xs md:text-sm bg-white/20 px-3 py-1.5 rounded-lg hover:bg-white/30 transition-all">
                                                <span>اكتشف المزيد</span>
                                                <i class="fas fa-arrow-left text-xs"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if ($slideshowImage->link)
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

    {{-- Born Today Section --}}
    @if ($birthdayPersons && $birthdayPersons->count() > 0)
        <section class="py-6 md:py-8 lg:py-10 bg-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-48 h-48 bg-green-100 rounded-full blur-3xl opacity-30"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
                <div class="text-right mb-6 md:mb-8">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">
                        ولد في مثل هذا اليوم
                    </h2>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 md:gap-3">
                    @foreach ($birthdayPersons as $person)
                        <a href="{{ route('people.profile.show', $person->id) }}"
                            class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 bg-white card-hover">
                            <div class="aspect-square">
                                <img src="{{ $person->avatar }}" alt="{{ $person->full_name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300 person-name-overlay">
                                <div class="absolute bottom-3 right-3 left-3 text-center">
                                    <p class="text-white text-sm md:text-base font-bold truncate drop-shadow-lg mb-1">
                                        {{ $person->full_name }}
                                    </p>

                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Gallery Section --}}
    <section class="py-6 md:py-8 lg:py-10 bg-gradient-to-br from-gray-50 to-green-50/50 relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-emerald-100 rounded-full blur-3xl opacity-30"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
            <div class="text-right mb-6 md:mb-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">
                    📸 معرض الصور
                </h2>
                <p class="text-gray-600 text-xs md:text-sm mt-2">لحظات جميلة من حياة العائلة</p>
            </div>

            @if ($latestGalleryImages->count() > 0)
                <div class="swiper gallerySwiper">
                    <div class="swiper-wrapper">
                        @foreach ($latestGalleryImages as $galleryImage)
                            <div class="swiper-slide">
                                <div
                                    class="relative group overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 {{ !$galleryImage->is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}">
                                    @if (isset($galleryImage->image_url))
                                        <img src="{{ $galleryImage->image_url }}"
                                            alt="{{ $galleryImage->name ?? 'صورة' }}"
                                            class="w-full h-32 md:h-40 lg:h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <img src="{{ asset('storage/' . $galleryImage->path) }}"
                                            alt="{{ $galleryImage->name ?? 'صورة' }}"
                                            class="w-full h-32 md:h-40 lg:h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                    @endif
                                    @if (!$galleryImage->is_active && Auth::check())
                                        <div class="absolute top-2 right-2 z-10">
                                            <span class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">
                                                معطل
                                            </span>
                                        </div>
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-2 right-2 left-2">
                                            <p class="text-white text-xs md:text-sm font-bold truncate drop-shadow-lg">
                                                {{ $galleryImage->name ?? 'صورة' }}
                                            </p>
                                            @if ($galleryImage->category)
                                                <p class="text-white/90 text-xs mt-0.5">
                                                    {{ $galleryImage->category->name }}
                                                </p>
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
    </section>

    {{-- Family Councils Section --}}
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
                                        <th scope="col"
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            المجلس</th>
                                        <th scope="col"
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            المدينة</th>
                                        <th scope="col"
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            الموقع</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($councils as $council)
                                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer council-row {{ !$council->is_active && Auth::check() ? 'opacity-60' : '' }}"
                                            data-council-id="{{ $council->id }}"
                                            onclick="toggleCouncilDescription({{ $council->id }})">
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-right"
                                                dir="ltr">
                                                <div class="flex items-center justify-end">
                                                    <span
                                                        class="text-xs font-semibold text-gray-900">{{ $council->name }}</span>
                                                    <i class="fas fa-building text-green-600 ml-1.5 text-xs"></i>
                                                    @if ($council->description)
                                                        <i
                                                            class="fas fa-chevron-down text-green-500 mr-1.5 text-xs transition-transform duration-300 council-chevron-{{ $council->id }}"></i>
                                                    @endif
                                                    @if (!$council->is_active && Auth::check())
                                                        <span class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold mr-1">
                                                            معطل
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 text-right">
                                                <span class="text-xs text-gray-700">
                                                    {{ $council->address ?? '-' }}
                                                </span>
                                            </td>

                                            <td
                                                class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-xs font-medium text-right">
                                                @if ($council->google_map_url)
                                                    <a href="{{ $council->google_map_url }}" target="_blank"
                                                        class="text-blue-600 hover:text-blue-900 transition-colors inline-flex items-center"
                                                        title="فتح في جوجل ماب" onclick="event.stopPropagation();">
                                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="#EA4335" stroke="#FFFFFF" stroke-width="1.5"/>
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
                                                                <div class="flex-shrink-0 mt-0.5">
                                                                    <i
                                                                        class="fas fa-info-circle text-green-600 text-sm"></i>
                                                                </div>
                                                                <div class="flex-1">
                                                                    <h4
                                                                        class="text-xs md:text-sm font-semibold text-gray-800 mb-1.5 flex items-center gap-1.5">
                                                                        <span>نبذة عن {{ $council->name }}</span>
                                                                    </h4>
                                                                    <div
                                                                        class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                                                                        {{ $council->description }}
                                                                    </div>
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

    {{-- مناسبات العائلة --}}
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
                                            <th scope="col"
                                                class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                                المناسبة</th>
                                            <th scope="col"
                                                class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                                المدينة</th>
                                            <th scope="col"
                                                class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                                الموقع</th>
                                            <th scope="col"
                                                class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                                التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($events as $event)
                                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer event-row {{ !$event->is_active && Auth::check() ? 'opacity-60' : '' }}"
                                                data-event-id="{{ $event->id }}"
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
                                                                <span class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">
                                                                    معطل
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-2 py-1.5 md:px-3 md:py-2 text-right">
                                                    <span class="text-xs text-gray-700">
                                                        {{ $event->city ?? '-' }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-xs font-medium text-right">
                                                    @if ($event->location)
                                                        <div class="relative inline-block">
                                                            <a href="{{ $event->location }}" target="_blank" rel="noopener noreferrer"
                                                                class="inline-flex items-center justify-center text-blue-600 hover:text-blue-800 group cursor-pointer transition-colors"
                                                                onclick="event.stopPropagation();"
                                                                title="{{ $event->location_name ?? 'فتح الموقع على الخريطة' }}">
                                                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="#EA4335" stroke="#FFFFFF" stroke-width="1.5"/>
                                                                </svg>
                                                            </a>

                                                        </div>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-right">
                                                    <div class="text-right">
                                                        <div class="text-xs font-medium text-gray-900 mb-1">
                                                            {{ $event->event_date->format('Y-m-d') }}
                                                        </div>
                                                        @if ($event->show_countdown && $event->event_date->isFuture())
                                                            <div class="event-countdown-{{ $event->id }} text-xs text-green-600 font-semibold text-right"
                                                                data-event-date="{{ $event->event_date->format('Y-m-d H:i:s') }}">
                                                                <span class="countdown-days"></span> يوم
                                                            </div>
                                                        @endif
                                                    </div>
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
                                                                    <div class="flex-shrink-0 mt-0.5">
                                                                        <i
                                                                            class="fas fa-info-circle text-green-600 text-sm"></i>
                                                                    </div>
                                                                    <div class="flex-1">
                                                                        <h4
                                                                            class="text-xs md:text-sm font-semibold text-gray-800 mb-1.5 flex items-center gap-1.5">
                                                                            <span>نبذة عن {{ $event->title }}</span>
                                                                        </h4>
                                                                        <div
                                                                            class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                                                                            {{ $event->description }}
                                                                        </div>
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

    {{-- Family Programs Section --}}
    <section class="py-6 md:py-8 lg:py-10 bg-gradient-to-br from-green-50 to-emerald-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-200 rounded-full blur-3xl opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
            <div class="text-right mb-6 md:mb-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">
                    🎯 برامج العائلة
                </h2>
                <p class="text-gray-600 text-xs md:text-sm mt-2">فعاليات وأنشطة متنوعة</p>
            </div>

            @if ($programs && $programs->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-3">
                    @foreach ($programs as $program)
                        <a href="{{ route('programs.show', $program) }}"
                            class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 bg-white card-hover {{ !$program->program_is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}">
                            <div class="aspect-square p-2 md:p-3">
                                <img src="{{ asset('storage/' . $program->path) }}"
                                    alt="{{ $program->program_title ?? ($program->name ?? 'برنامج') }}"
                                    class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                            </div>
                            @if (!$program->program_is_active && Auth::check())
                                <div class="absolute top-2 right-2 z-10">
                                    <span class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">
                                        معطل
                                    </span>
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

    {{-- Proud Of Section --}}
    {{-- <section class="py-4 md:py-6 lg:py-8 bg-gradient-to-br from-green-50 to-emerald-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-200 rounded-full blur-3xl opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
            <div class="text-right mb-4 md:mb-6">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gradient section-title mb-2">
                    🌟 نفتخر بهم
                </h2>
                <p class="text-gray-600 text-xs md:text-sm mt-2">فعاليات وأنشطة متنوعة</p>
            </div>

            @if ($proudOf && $proudOf->count() > 0)
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-7 gap-2 md:gap-3">
                    @foreach ($proudOf as $item)
                        <a href="{{ route('programs.show', $item) }}"
                            class="group relative overflow-hidden rounded-full shadow-lg hover:shadow-2xl transition-all duration-500 bg-white card-hover {{ !$item->proud_of_is_active ? 'opacity-60 grayscale' : '' }}">
                            <div class="aspect-square">
                                <img src="{{ asset('storage/' . $item->path) }}"
                                    alt="{{ $item->proud_of_title ?? ($item->name ?? 'عنصر') }}"
                                    class="w-full h-full object-cover rounded-full transition-transform duration-500 group-hover:scale-110">
                            </div>
                            @if (!$item->proud_of_is_active && Auth::check())
                                <div class="absolute top-1 left-1 z-10">
                                    <span class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">
                                        معطل
                                    </span>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-full">
                                <div class="absolute bottom-2 right-2 left-2 text-center">
                                    <p class="text-white text-xs font-bold line-clamp-2 drop-shadow-lg">
                                        {{ $item->proud_of_title ?? ($item->name ?? 'عنصر') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm md:text-base">لا توجد عناصر متاحة حالياً</p>
                </div>
            @endif
        </div>
    </section> --}}

    {{-- Graduates Section --}}
    @php
        $bachelorGraduates = $latestGraduates->where('degree_type', 'bachelor')->take(10);
        $masterGraduates = $latestGraduates->where('degree_type', 'master')->take(10);
        $phdGraduates = $latestGraduates->where('degree_type', 'phd')->take(10);

        // جلب أول فئة من كل نوع لاستخدامها في الروابط
        $bachelorCategoryId = $bachelorGraduates->first()?->category_id;
        $masterCategoryId = $masterGraduates->first()?->category_id;
        $phdCategoryId = $phdGraduates->first()?->category_id;
    @endphp

    @if (
        (isset($bachelorTotalCount) && $bachelorTotalCount > 0) ||
            (isset($masterTotalCount) && $masterTotalCount > 0) ||
            (isset($phdTotalCount) && $phdTotalCount > 0))
        <section class="py-6 md:py-8 lg:py-10 bg-white relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-yellow-100 rounded-full blur-3xl opacity-20"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
                <div class="text-right mb-6 md:mb-8">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">
                        🎓 الشهادات العلمية
                    </h2>
                    <p class="text-gray-600 text-xs md:text-sm mt-2">نفخر بإنجازاتهم الأكاديمية</p>
                </div>

                <div class="grid grid-cols-3 gap-2 md:gap-4 mb-6">
                    @if (isset($phdTotalCount) && $phdTotalCount > 0 && $phdCategoryId)
                        <a href="{{ route('gallery.articles', ['category' => $phdCategoryId]) }}"
                            class="degree-card group relative overflow-hidden rounded-xl shadow-lg bg-gradient-to-br from-yellow-100 via-yellow-50 to-amber-100 p-2 md:p-6 card-hover border-2 border-yellow-200">
                            <div class="relative z-10 text-center">
                                <div
                                    class="w-10 h-10 md:w-16 md:h-16 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-1 md:mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-graduation-cap text-white text-base md:text-2xl"></i>
                                </div>
                                <h3 class="text-xs md:text-xl font-bold text-yellow-800 mb-0.5 md:mb-1">الدكتوارة</h3>
                                <div class="text-lg md:text-4xl font-bold text-yellow-600 mb-0.5 md:mb-1">
                                    {{ $phdTotalCount }}</div>
                                <p class="text-yellow-700 text-[10px] md:text-xs">خريج</p>
                            </div>
                        </a>
                    @endif

                    @if (isset($masterTotalCount) && $masterTotalCount > 0 && $masterCategoryId)
                        <a href="{{ route('gallery.articles', ['category' => $masterCategoryId]) }}"
                            class="degree-card group relative overflow-hidden rounded-xl shadow-lg bg-gradient-to-br from-indigo-100 via-indigo-50 to-purple-100 p-2 md:p-6 card-hover border-2 border-indigo-200">
                            <div class="relative z-10 text-center">
                                <div
                                    class="w-10 h-10 md:w-16 md:h-16 bg-indigo-400 rounded-full flex items-center justify-center mx-auto mb-1 md:mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-user-graduate text-white text-base md:text-2xl"></i>
                                </div>
                                <h3 class="text-xs md:text-xl font-bold text-indigo-800 mb-0.5 md:mb-1"> الماجستير</h3>
                                <div class="text-lg md:text-4xl font-bold text-indigo-600 mb-0.5 md:mb-1">
                                    {{ $masterTotalCount }}</div>
                                <p class="text-indigo-700 text-[10px] md:text-xs">خريج</p>
                            </div>
                        </a>
                    @endif

                    @if (isset($bachelorTotalCount) && $bachelorTotalCount > 0 && $bachelorCategoryId)
                        <a href="{{ route('gallery.articles', ['category' => $bachelorCategoryId]) }}"
                            class="degree-card group relative overflow-hidden rounded-xl shadow-lg bg-gradient-to-br from-green-100 via-green-50 to-emerald-100 p-2 md:p-6 card-hover border-2 border-green-200">
                            <div class="relative z-10 text-center">
                                <div
                                    class="w-10 h-10 md:w-16 md:h-16 bg-green-400 rounded-full flex items-center justify-center mx-auto mb-1 md:mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-award text-white text-base md:text-2xl"></i>
                                </div>
                                <h3 class="text-xs md:text-xl font-bold text-green-800 mb-0.5 md:mb-1"> البكالوريوس
                                </h3>
                                <div class="text-lg md:text-4xl font-bold text-green-600 mb-0.5 md:mb-1">
                                    {{ $bachelorTotalCount }}</div>
                                <p class="text-green-700 text-[10px] md:text-xs">خريج</p>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- Courses Section --}}
    <section class="py-6 md:py-8 lg:py-10 bg-gradient-to-br from-gray-50 to-green-50/50 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-green-200 rounded-full blur-3xl opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
            <div class="text-right mb-6 md:mb-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">
                    📚 دورات أكاديمية السريع
                </h2>
                <p class="text-gray-600 text-xs md:text-sm mt-2">تعلم وتطور مع دوراتنا المتميزة</p>
            </div>

            @if ($courses->count() > 0)
                <div class="swiper coursesSwiper">
                    <div class="swiper-wrapper">
                        @foreach ($courses as $course)
                            <div class="swiper-slide">
                                <div
                                    class="glass-card rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 h-full flex flex-col {{ !$course->is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}">
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
                                                <span class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">
                                                    معطل
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-3 md:p-4 flex-1 flex flex-col">
                                        <h3 class="text-base md:text-lg font-bold text-gray-800 mb-2 line-clamp-2">
                                            {{ $course->title }}</h3>

                                        <p class="text-gray-600 text-xs mb-3 line-clamp-2 flex-1">
                                            {{ $course->description ?? 'دورة تدريبية متميزة' }}
                                        </p>

                                        <div class="space-y-1 mb-3">
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

    {{-- @include('partials.main-footer') --}}

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Hero Slideshow
        document.addEventListener('DOMContentLoaded', function() {
            const totalSlides = {{ $latestImages->count() ?? 0 }};
            if (totalSlides > 0) {
                new Swiper('.heroSwiper', {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: totalSlides > 1,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    speed: 1000,
                    pagination: {
                        el: '.hero-pagination',
                        clickable: true,
                        dynamicBullets: true,
                    },
                    navigation: {
                        nextEl: '.hero-next',
                        prevEl: '.hero-prev',
                    },
                    keyboard: {
                        enabled: true,
                    },
                });
            }
        });

        // Gallery Swiper
        document.addEventListener('DOMContentLoaded', function() {
            const totalImages = {{ $latestGalleryImages->count() ?? 0 }};
            if (totalImages > 0) {
                new Swiper('.gallerySwiper', {
                    slidesPerView: 2,
                    spaceBetween: 15,
                    loop: totalImages > 4,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.gallery-pagination',
                        clickable: true,
                        dynamicBullets: true,
                    },
                    navigation: {
                        nextEl: '.gallery-next',
                        prevEl: '.gallery-prev',
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 3,
                            spaceBetween: 20,
                        },
                        1024: {
                            slidesPerView: 4,
                            spaceBetween: 24,
                        },
                        1280: {
                            slidesPerView: 5,
                            spaceBetween: 24,
                        },
                    },
                });
            }
        });

        // Courses Swiper
        document.addEventListener('DOMContentLoaded', function() {
            const totalCourses = {{ $courses->count() ?? 0 }};
            if (totalCourses > 0) {
                new Swiper('.coursesSwiper', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: totalCourses > 3,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.courses-pagination',
                        clickable: true,
                        dynamicBullets: true,
                    },
                    navigation: {
                        nextEl: '.courses-next',
                        prevEl: '.courses-prev',
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

        // Toggle Council Description
        function toggleCouncilDescription(councilId) {
            const descriptionRow = document.querySelector(`.council-description-${councilId}`);
            const content = descriptionRow?.querySelector('.council-description-content');
            const chevron = document.querySelector(`.council-chevron-${councilId}`);

            if (!descriptionRow || !content) return;

            const isHidden = descriptionRow.classList.contains('hidden');

            if (isHidden) {
                // Show description
                descriptionRow.classList.remove('hidden');
                // Force reflow to ensure transition works
                setTimeout(() => {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }, 10);
                if (chevron) {
                    chevron.style.transform = 'rotate(180deg)';
                }
            } else {
                // Hide description
                content.style.maxHeight = '0';
                if (chevron) {
                    chevron.style.transform = 'rotate(0deg)';
                }
                // Wait for animation to complete before hiding row
                setTimeout(() => {
                    descriptionRow.classList.add('hidden');
                }, 500);
            }
        }

        // Toggle Event Description
        function toggleEventDescription(eventId) {
            const descriptionRow = document.querySelector(`.event-description-${eventId}`);
            const content = descriptionRow?.querySelector('.event-description-content');
            const chevron = document.querySelector(`.event-chevron-${eventId}`);

            if (!descriptionRow || !content) return;

            const isHidden = descriptionRow.classList.contains('hidden');

            if (isHidden) {
                // Show description
                descriptionRow.classList.remove('hidden');
                // Force reflow to ensure transition works
                setTimeout(() => {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }, 10);
                if (chevron) {
                    chevron.style.transform = 'rotate(180deg)';
                }
            } else {
                // Hide description
                content.style.maxHeight = '0';
                if (chevron) {
                    chevron.style.transform = 'rotate(0deg)';
                }
                // Wait for animation to complete before hiding row
                setTimeout(() => {
                    descriptionRow.classList.add('hidden');
                }, 500);
            }
        }

        // Countdown Timer for Events
        function updateEventCountdowns() {
            document.querySelectorAll('[class*="event-countdown-"]').forEach(function(countdownElement) {
                const eventDateStr = countdownElement.getAttribute('data-event-date');
                if (!eventDateStr) return;

                const eventDate = new Date(eventDateStr);
                const now = new Date();
                const diff = eventDate - now;

                if (diff <= 0) {
                    countdownElement.innerHTML = '<span class="text-red-600">انتهت المناسبة</span>';
                    return;
                }

                // حساب الأيام فقط
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));

                const daysSpan = countdownElement.querySelector('.countdown-days');
                if (daysSpan) daysSpan.textContent = days;
            });
        }

        // Update countdown every minute
        setInterval(updateEventCountdowns, 60000);
        // Initial update
        updateEventCountdowns();

    </script>
</body>

</html>
