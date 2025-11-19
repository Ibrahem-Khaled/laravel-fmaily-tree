<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرئيسية - تواصل عائلة السريِّع</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        body {
            font-family: 'Alexandria', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #145147 0%, #37a05c 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .slide-container {
            display: flex;
            transition: transform 1s ease-in-out;
            width: 100%;
            height: 100%;
            position: relative;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }

        .slide-item {
            min-width: 100%;
            flex-shrink: 0;
            height: 100%;
            position: relative;
        }

        .slide-item img {
            display: block !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            min-height: 100% !important;
            object-fit: cover !important;
            -webkit-object-fit: cover !important;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }

        .course-card {
            transition: all 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .text-gradient {
            background: linear-gradient(135deg, #145147 0%, #37a05c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* تحسين التصميم العام - تصغير العناصر للشاشات الصغيرة */
        @media (max-width: 640px) {
            section {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
                min-height: auto !important;
                max-height: none !important;
                overflow-y: visible !important;
                overflow-x: visible !important;
            }

            .mobile-section {
                overflow: visible !important;
                overflow-x: visible !important;
                overflow-y: visible !important;
            }

            h2 {
                font-size: 1.25rem !important;
                margin-bottom: 0.5rem !important;
            }

            .container {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            .mb-8,
            .mb-12 {
                margin-bottom: 0.5rem !important;
            }

            .mb-2 {
                margin-bottom: 0.75rem !important;
            }

            .mb-4 {
                margin-bottom: 1.25rem !important;
            }

            .py-12,
            .py-16,
            .py-20 {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }

            .py-3 {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }

            .py-6 {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }

            .mb-6 {
                margin-bottom: 0.75rem !important;
            }

            .gap-2 {
                gap: 0.5rem !important;
            }

            .gap-3 {
                gap: 0.75rem !important;
            }

            .space-y-2>*+* {
                margin-top: 0.5rem !important;
            }

            .space-y-3>*+* {
                margin-top: 0.75rem !important;
            }

            .space-y-4>*+* {
                margin-top: 0.75rem !important;
            }

            .grid {
                overflow: visible !important;
                overflow-x: visible !important;
                overflow-y: visible !important;
            }

            .container {
                overflow: visible !important;
                overflow-x: visible !important;
                overflow-y: visible !important;
            }
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Swiper Custom Styles */
        .gallerySwiper {
            padding: 10px 40px 40px 40px !important;
        }

        .gallerySwiper .swiper-button-next,
        .gallerySwiper .swiper-button-prev {
            color: #37a05c;
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .gallerySwiper .swiper-button-next:hover,
        .gallerySwiper .swiper-button-prev:hover {
            background: #f3f4f6;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .gallerySwiper .swiper-button-next::after,
        .gallerySwiper .swiper-button-prev::after {
            font-size: 18px;
            font-weight: bold;
        }

        .gallerySwiper .swiper-button-next {
            left: 0;
            right: auto;
        }

        .gallerySwiper .swiper-button-prev {
            right: 0;
            left: auto;
        }

        .gallerySwiper .swiper-pagination-bullet {
            background: #d1d5db;
            opacity: 1;
            width: 8px;
            height: 8px;
            transition: all 0.3s ease;
        }

        .gallerySwiper .swiper-pagination-bullet-active {
            background: #37a05c;
            width: 24px;
            border-radius: 4px;
        }

        @media (max-width: 640px) {
            .gallerySwiper {
                padding: 10px 30px 30px 30px !important;
            }

            .gallerySwiper .swiper-button-next,
            .gallerySwiper .swiper-button-prev {
                width: 32px;
                height: 32px;
            }

            .gallerySwiper .swiper-button-next::after,
            .gallerySwiper .swiper-button-prev::after {
                font-size: 14px;
            }
        }

        /* Courses Swiper Custom Styles */
        .coursesSwiper {
            padding: 10px 40px 40px 40px !important;
        }

        .coursesSwiper .swiper-button-next,
        .coursesSwiper .swiper-button-prev {
            color: #37a05c;
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .coursesSwiper .swiper-button-next:hover,
        .coursesSwiper .swiper-button-prev:hover {
            background: #f3f4f6;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .coursesSwiper .swiper-button-next::after,
        .coursesSwiper .swiper-button-prev::after {
            font-size: 18px;
            font-weight: bold;
        }

        .coursesSwiper .swiper-button-next {
            left: 0;
            right: auto;
        }

        .coursesSwiper .swiper-button-prev {
            right: 0;
            left: auto;
        }

        .coursesSwiper .swiper-pagination-bullet {
            background: #d1d5db;
            opacity: 1;
            width: 8px;
            height: 8px;
            transition: all 0.3s ease;
        }

        .coursesSwiper .swiper-pagination-bullet-active {
            background: #37a05c;
            width: 24px;
            border-radius: 4px;
        }

        @media (max-width: 640px) {
            .coursesSwiper {
                padding: 10px 30px 30px 30px !important;
            }

            .coursesSwiper .swiper-button-next,
            .coursesSwiper .swiper-button-prev {
                width: 32px;
                height: 32px;
            }

            .coursesSwiper .swiper-button-next::after,
            .coursesSwiper .swiper-button-prev::after {
                font-size: 14px;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    @include('partials.main-header')

    {{-- Hero Section with Slideshow --}}
    <section
        class="relative h-[180px] sm:h-[200px] md:h-[260px] lg:h-[340px] overflow-hidden gradient-bg mobile-section">
        <div class="absolute inset-0 slide-container" id="slideContainer">
            @if ($latestImages->count() > 0)
                @foreach ($latestImages->take(10) as $index => $slideshowImage)
                    <div class="slide-item h-full relative">
                        @if ($slideshowImage->image_url)
                            <img src="{{ $slideshowImage->image_url }}" alt="{{ $slideshowImage->title ?? 'صورة' }}"
                                class="w-full h-full object-cover opacity-90"
                                style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%; min-height: 100%;">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                        @if ($slideshowImage->title || $slideshowImage->description)
                            <div class="absolute bottom-4 md:bottom-6 right-3 left-3 md:right-6 md:left-6">
                                <div class="glass-effect rounded-lg p-2 md:p-3 lg:p-4 max-w-xl">
                                    @if ($slideshowImage->title)
                                        <h2 class="text-white text-sm md:text-base lg:text-lg font-bold">
                                            {{ $slideshowImage->title }}</h2>
                                    @endif
                                    @if ($slideshowImage->description)
                                        <p class="text-white/90 text-xs md:text-sm line-clamp-2">
                                            {{ $slideshowImage->description }}</p>
                                    @endif
                                    @if ($slideshowImage->link)
                                        <a href="{{ $slideshowImage->link }}" target="_blank"
                                            class="inline-block mt-1 md:mt-2 text-white text-xs md:text-sm hover:underline">
                                            <i class="fas fa-external-link-alt ml-1"></i>المزيد
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="slide-item h-full flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <i class="fas fa-images text-4xl md:text-6xl mb-4 opacity-50"></i>
                        <p class="text-base md:text-xl">لا توجد صور في السلايدشو حالياً</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Navigation Dots --}}
        @if ($latestImages->count() > 0)
            <div class="absolute bottom-3 md:bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
                @foreach ($latestImages->take(10) as $index => $slideshowImage)
                    <button
                        class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-white/50 hover:bg-white transition-all slide-dot {{ $index === 0 ? 'bg-white' : '' }}"
                        onclick="goToSlide({{ $index }})"
                        aria-label="انتقل للشريحة {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Family Brief Section --}}
    {{-- <section class="py-6 md:py-12 lg:py-16 bg-white mobile-section">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
            <div class="text-right mb-4 md:mb-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient mb-2 md:mb-4">نبذة عن عائلة السريع</h2>
                <div class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0"></div>
            </div>
            <div class="rounded-lg md:rounded-xl p-4 md:p-6 lg:p-8">
                <div class="prose prose-sm sm:prose-base md:prose-lg max-w-none text-gray-700">
                    <div class="family-brief-short line-clamp-2 text-sm md:text-base" id="family-brief-short">
                        {!! nl2br(e($familyBrief)) !!}
                    </div>
                    <div class="family-brief-full hidden text-sm md:text-base" id="family-brief-full">
                        {!! nl2br(e($familyBrief)) !!}
                    </div>
                    <button onclick="toggleSection('family-brief')"
                            class="text-green-600 hover:text-green-700 text-xs md:text-sm font-semibold mt-2 md:mt-3 flex items-center gap-1 transition-colors"
                            id="toggle-family-brief-btn">
                        <span class="toggle-text">عرض المزيد</span>
                        <i class="fas fa-chevron-down toggle-icon text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </section> --}}

    {{-- Born Today Section --}}
    @if ($birthdayPersons && $birthdayPersons->count() > 0)
        <section class="py-2 md:py-6 lg:py-8 bg-white mobile-section">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <div class="text-right mb-4 md:mb-6">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient mb-1 md:mb-2">ولد في مثل هذا
                        اليوم</h2>
                    <div
                        class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0 mb-1 md:mb-2">
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 md:gap-3">
                    @foreach ($birthdayPersons as $person)
                        <a href="{{ route('people.profile.show', $person->id) }}"
                            class="relative group overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 bg-white flex flex-col items-center justify-center h-24 sm:h-32 md:h-40 lg:h-48">
                            @if ($person->photo_url)
                                <img src="{{ asset('storage/' . $person->photo_url) }}" alt="{{ $person->full_name }}"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                                    <i
                                        class="fas {{ $person->gender == 'male' ? 'fa-male' : 'fa-female' }} text-white text-3xl md:text-5xl opacity-50"></i>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                <div class="absolute bottom-2 right-2 left-2">
                                    <p
                                        class="text-white text-xs sm:text-sm font-semibold truncate drop-shadow-lg text-center">
                                        {{ $person->full_name }}
                                    </p>
                                    @if ($person->birth_date)
                                        <p class="text-white/80 text-xs truncate text-center mt-1">
                                            {{ $person->birth_date->format('Y-m-d') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- What's New Section --}}
    <section class="py-2 md:py-6 lg:py-8 bg-white mobile-section">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
            <div class="text-right mb-4 md:mb-6">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient mb-1 md:mb-2">الصور</h2>
                <div
                    class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0">
                </div>
            </div>

            {{-- آخر 8 صور من المعرض - Swiper Carousel --}}
            @if ($latestGalleryImages->count() > 0)
                <div class="relative mb-3 md:mb-6">
                    <!-- Swiper -->
                    <div class="swiper gallerySwiper">
                        <div class="swiper-wrapper">
                            @foreach ($latestGalleryImages as $galleryImage)
                                <div class="swiper-slide">
                                    <div
                                        class="relative group overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 px-2">
                                        @if (isset($galleryImage->image_url))
                                            {{-- HomeGalleryImage --}}
                                            <img src="{{ $galleryImage->image_url }}"
                                                alt="{{ $galleryImage->name ?? 'صورة' }}"
                                                class="w-full h-24 md:h-32 lg:h-40 object-cover transition-transform duration-300 group-hover:scale-110 rounded-lg">
                                        @else
                                            {{-- Image from gallery --}}
                                            <img src="{{ asset('storage/' . $galleryImage->path) }}"
                                                alt="{{ $galleryImage->name ?? 'صورة' }}"
                                                class="w-full h-24 md:h-32 lg:h-40 object-cover transition-transform duration-300 group-hover:scale-110 rounded-lg">
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                                            <div class="absolute bottom-2 right-2 left-2">
                                                <p class="text-white text-xs md:text-sm font-semibold truncate">
                                                    {{ $galleryImage->name ?? 'صورة' }}
                                                </p>
                                                @if ($galleryImage->category)
                                                    <p class="text-white/80 text-xs truncate">
                                                        {{ $galleryImage->category->name }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Navigation buttons -->
                        <div class="swiper-button-next gallery-next"></div>
                        <div class="swiper-button-prev gallery-prev"></div>
                        <!-- Pagination -->
                        <div class="swiper-pagination gallery-pagination"></div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Family Councils Section --}}
    <section class="py-2 md:py-6 lg:py-8 bg-white mobile-section">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <div class="text-right mb-4 md:mb-6">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient mb-1 md:mb-2">مجالس العائلة</h2>
                <div
                    class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0 mb-1 md:mb-2">
                </div>
            </div>

            @if ($councils && $councils->count() > 0)
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden shadow-lg rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 bg-white">
                                <thead class="gradient-bg text-white">
                                    <tr>
                                        <th scope="col"
                                            class="px-2 py-2 md:px-4 md:py-3 text-xs md:text-sm font-bold uppercase tracking-wider text-right">
                                            المجلس</th>
                                        <th scope="col"
                                            class="px-2 py-2 md:px-4 md:py-3 text-xs md:text-sm font-bold uppercase tracking-wider text-right">
                                            المدينة</th>
                                        <th scope="col"
                                            class="px-2 py-2 md:px-4 md:py-3 text-xs md:text-sm font-bold uppercase tracking-wider text-right">
                                            الموقع</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($councils as $council)
                                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer council-row"
                                            data-council-id="{{ $council->id }}"
                                            onclick="toggleCouncilDescription({{ $council->id }})">
                                            <td class="px-2 py-2 md:px-4 md:py-3 whitespace-nowrap text-right"
                                                dir="ltr">
                                                <div class="flex items-center justify-end">
                                                    <span
                                                        class="text-xs md:text-sm font-semibold text-gray-900">{{ $council->name }}</span>
                                                    <i
                                                        class="fas fa-building text-green-600 ml-2 text-sm md:text-base"></i>
                                                    @if ($council->description)
                                                        <i
                                                            class="fas fa-chevron-down text-green-500 mr-2 text-xs transition-transform duration-300 council-chevron-{{ $council->id }}"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-2 py-2 md:px-4 md:py-3 text-right">
                                                <span class="text-xs md:text-sm text-gray-700">
                                                    {{ $council->address ?? '-' }}
                                                </span>
                                            </td>

                                            <td
                                                class="px-2 py-2 md:px-4 md:py-3 whitespace-nowrap text-xs md:text-sm font-medium text-right">
                                                @if ($council->google_map_url)
                                                    <a href="{{ $council->google_map_url }}" target="_blank"
                                                        class="text-blue-600 hover:text-blue-900 transition-colors"
                                                        title="فتح في جوجل ماب" onclick="event.stopPropagation();">
                                                        <i class="fas fa-map-marked-alt"></i>
                                                        <span class="mr-1 hidden lg:inline">الخريطة</span>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($council->description)
                                            <tr
                                                class="council-description-row council-description-{{ $council->id }} hidden">
                                                <td colspan="3" class="px-2 py-0 md:px-4">
                                                    <div
                                                        class="council-description-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                                                        <div
                                                            class="bg-gradient-to-r from-green-50 to-emerald-50 border-r-4 border-green-500 rounded-lg p-4 md:p-6 my-2 shadow-md">
                                                            <div class="flex items-start gap-3">
                                                                <div class="flex-shrink-0 mt-1">
                                                                    <i
                                                                        class="fas fa-info-circle text-green-600 text-lg"></i>
                                                                </div>
                                                                <div class="flex-1">
                                                                    <h4
                                                                        class="text-sm md:text-base font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                                                        <span>نبذة عن {{ $council->name }}</span>
                                                                    </h4>
                                                                    <div
                                                                        class="text-xs md:text-sm text-gray-700 leading-relaxed whitespace-pre-line">
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
                <div class="text-center py-8 md:py-12">
                    <i class="fas fa-building text-gray-400 text-4xl md:text-6xl mb-4"></i>
                    <p class="text-gray-600 text-base md:text-lg">لا توجد مجالس متاحة حالياً</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Family Programs Section --}}
    <section
        class="py-2 md:py-6 lg:py-8 bg-gradient-to-br from-green-50 via-white to-emerald-50 mobile-section relative">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 right-0 w-96 h-96 bg-green-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-400 rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
            <div class="text-right mb-4 md:mb-6">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient mb-1 md:mb-2">برامج العائلة</h2>
                <div
                    class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0 mb-1 md:mb-2">
                </div>
            </div>

            {{-- برامج السريع - صور ملتصقة --}}
            @if ($programs && $programs->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-3">
                    @foreach ($programs as $program)
                        <a href="{{ route('programs.show', $program) }}"
                            class="relative group overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 bg-white flex items-center justify-center h-24 sm:h-32 md:h-40 lg:h-48">
                            <img src="{{ asset('storage/' . $program->path) }}"
                                alt="{{ $program->program_title ?? ($program->name ?? 'برنامج') }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105 p-2 sm:p-3">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                <div class="absolute bottom-2 right-2 left-2">
                                    <p class="text-white text-xs sm:text-sm font-semibold truncate drop-shadow-lg">
                                        {{ $program->program_title ?? ($program->name ?? 'برنامج') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 md:py-12">
                    <i class="fas fa-calendar-alt text-gray-400 text-4xl md:text-6xl mb-4"></i>
                    <p class="text-gray-600 text-base md:text-lg">لا توجد برامج متاحة حالياً</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Latest Graduates Section --}}
    @php
        $bachelorGraduates = $latestGraduates->where('degree_type', 'bachelor')->take(10);
        $masterGraduates = $latestGraduates->where('degree_type', 'master')->take(10);
        $phdGraduates = $latestGraduates->where('degree_type', 'phd')->take(10);
    @endphp

    @if (($bachelorGraduates->count() > 0 || $masterGraduates->count() > 0 || $phdGraduates->count() > 0))
        <section
            class="py-2 md:py-6 lg:py-8 bg-gradient-to-br from-green-50 via-white to-emerald-50 mobile-section relative">
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-0 right-0 w-96 h-96 bg-green-400 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-400 rounded-full blur-3xl"></div>
            </div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
                <div class="text-right mb-4 md:mb-6">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient mb-1 md:mb-2">آخر الخريجين</h2>
                    <div
                        class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0 mb-1 md:mb-2">
                    </div>
                </div>

                <!-- حملة البكالوريوس -->
                @if ($bachelorGraduates->count() > 0)
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-lg md:text-xl font-bold text-green-600 mb-3 md:mb-4 text-right">
                            <i class="fas fa-graduation-cap mr-2"></i>حملة البكالوريوس
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-3">
                            @foreach ($bachelorGraduates as $article)
                                @if ($article->person)
                                    <a href="{{ route('article.show', $article->id) }}"
                                        class="relative group overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 bg-white flex flex-col items-center justify-center h-24 sm:h-32 md:h-40 lg:h-48">
                                        @if ($article->person->photo_url)
                                            <img src="{{ asset('storage/' . $article->person->photo_url) }}"
                                                alt="{{ $article->person->full_name }}"
                                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                        @else
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                                                <i
                                                    class="fas {{ $article->person->gender == 'male' ? 'fa-male' : 'fa-female' }} text-white text-3xl md:text-5xl opacity-50"></i>
                                            </div>
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                            <div class="absolute bottom-2 right-2 left-2">
                                                <p
                                                    class="text-white text-xs sm:text-sm font-semibold truncate drop-shadow-lg text-center">
                                                    {{ $article->person->full_name }}
                                                </p>
                                                @if ($article->category)
                                                    <p class="text-white/80 text-xs truncate text-center mt-1">
                                                        {{ $article->category->name }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- حملة الماجستير -->
                @if ($masterGraduates->count() > 0)
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-lg md:text-xl font-bold text-indigo-600 mb-3 md:mb-4 text-right">
                            <i class="fas fa-graduation-cap mr-2"></i>حملة الماجستير
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-3">
                            @foreach ($masterGraduates as $article)
                                @if ($article->person)
                                    <a href="{{ route('article.show', $article->id) }}"
                                        class="relative group overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 bg-white flex flex-col items-center justify-center h-24 sm:h-32 md:h-40 lg:h-48">
                                        @if ($article->person->photo_url)
                                            <img src="{{ asset('storage/' . $article->person->photo_url) }}"
                                                alt="{{ $article->person->full_name }}"
                                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                        @else
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center">
                                                <i
                                                    class="fas {{ $article->person->gender == 'male' ? 'fa-male' : 'fa-female' }} text-white text-3xl md:text-5xl opacity-50"></i>
                                            </div>
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                            <div class="absolute bottom-2 right-2 left-2">
                                                <p
                                                    class="text-white text-xs sm:text-sm font-semibold truncate drop-shadow-lg text-center">
                                                    {{ $article->person->full_name }}
                                                </p>
                                                @if ($article->category)
                                                    <p class="text-white/80 text-xs truncate text-center mt-1">
                                                        {{ $article->category->name }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- حملة الدكتوراه -->
                @if ($phdGraduates->count() > 0)
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-lg md:text-xl font-bold text-yellow-600 mb-3 md:mb-4 text-right">
                            <i class="fas fa-graduation-cap mr-2"></i>حملة الدكتوراه
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-3">
                            @foreach ($phdGraduates as $article)
                                @if ($article->person)
                                    <a href="{{ route('article.show', $article->id) }}"
                                        class="relative group overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 bg-white flex flex-col items-center justify-center h-24 sm:h-32 md:h-40 lg:h-48">
                                        @if ($article->person->photo_url)
                                            <img src="{{ asset('storage/' . $article->person->photo_url) }}"
                                                alt="{{ $article->person->full_name }}"
                                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                        @else
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center">
                                                <i
                                                    class="fas {{ $article->person->gender == 'male' ? 'fa-male' : 'fa-female' }} text-white text-3xl md:text-5xl opacity-50"></i>
                                            </div>
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                            <div class="absolute bottom-2 right-2 left-2">
                                                <p
                                                    class="text-white text-xs sm:text-sm font-semibold truncate drop-shadow-lg text-center">
                                                    {{ $article->person->full_name }}
                                                </p>
                                                @if ($article->category)
                                                    <p class="text-white/80 text-xs truncate text-center mt-1">
                                                        {{ $article->category->name }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- زر عرض المزيد -->
                <div class="text-center mt-4 md:mt-6">
                    <a href="{{ route('gallery.articles') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                        <span>عرض المزيد</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Old Family Programs Section (commented) --}}
    {{-- <section class="py-6 md:py-12 lg:py-16 bg-gradient-to-br from-green-50 via-white to-emerald-50 mobile-section relative overflow-hidden">

    {{-- Courses Section --}}
    <section class="py-2 md:py-6 lg:py-8 bg-white mobile-section">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <div class="text-right mb-4 md:mb-6">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient mb-1 md:mb-2">دورات أكاديمية
                    السريع</h2>
                <div
                    class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0">
                </div>
            </div>

            @if ($courses->count() > 0)
                <div class="relative mb-3 md:mb-6">
                    <!-- Swiper -->
                    <div class="swiper coursesSwiper">
                        <div class="swiper-wrapper">
                            @foreach ($courses as $course)
                                <div class="swiper-slide">
                                    <div
                                        class="course-card bg-white rounded-lg md:rounded-xl shadow-lg overflow-hidden h-full">
                                        <div
                                            class="relative h-32 md:h-40 lg:h-48 bg-gradient-to-br from-green-400 to-emerald-600">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <i
                                                    class="fas fa-book-open text-white text-3xl md:text-5xl lg:text-6xl opacity-30"></i>
                                            </div>
                                            @if ($course->image_url)
                                                <img src="{{ $course->image_url }}" alt="{{ $course->title }}"
                                                    class="w-full h-full object-cover">
                                            @endif
                                        </div>

                                        <div class="p-3 md:p-4 lg:p-6">
                                            <h3
                                                class="text-base md:text-lg lg:text-xl font-bold text-gray-800 mb-1 md:mb-2 line-clamp-2">
                                                {{ $course->title }}</h3>

                                            <div class="mb-2 md:mb-4">
                                                <p class="text-gray-600 text-xs md:text-sm course-description-short line-clamp-2"
                                                    id="desc-short-{{ $loop->index }}">
                                                    {{ $course->description ?? '' }}
                                                </p>
                                                <p class="text-gray-600 text-xs md:text-sm course-description-full hidden"
                                                    id="desc-full-{{ $loop->index }}">
                                                    {{ $course->description ?? '' }}
                                                </p>
                                                @if ($course->description && strlen($course->description) > 80)
                                                    <button onclick="toggleDescription({{ $loop->index }})"
                                                        class="text-green-600 hover:text-green-700 text-xs font-semibold mt-1 flex items-center gap-1 transition-colors course-toggle-btn"
                                                        id="toggle-btn-{{ $loop->index }}">
                                                        <span class="toggle-text">عرض المزيد</span>
                                                        <i class="fas fa-chevron-down toggle-icon text-xs"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            <div class="space-y-1 md:space-y-1.5 mb-2 md:mb-4">
                                                @if ($course->instructor)
                                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                        <i class="fas fa-user text-green-500 text-xs"></i>
                                                        <span class="truncate">{{ $course->instructor }}</span>
                                                    </div>
                                                @endif
                                                @if ($course->duration)
                                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                        <i class="fas fa-clock text-green-500 text-xs"></i>
                                                        <span>{{ $course->duration }}</span>
                                                    </div>
                                                @endif
                                                @if ($course->students > 0)
                                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                        <i class="fas fa-users text-green-500 text-xs"></i>
                                                        <span>{{ $course->students }} طالب</span>
                                                    </div>
                                                @endif
                                            </div>

                                            @if ($course->link)
                                                <a href="{{ $course->link }}" target="_blank"
                                                    class="w-full py-1.5 md:py-2 px-3 md:px-4 gradient-bg text-white rounded-lg text-xs md:text-sm font-semibold hover:opacity-90 transition-all flex items-center justify-center">
                                                    <i class="fas fa-external-link-alt ml-2 text-xs"></i>سجل الآن
                                                </a>
                                            @else
                                                <button
                                                    class="w-full py-1.5 md:py-2 px-3 md:px-4 gradient-bg text-white rounded-lg text-xs md:text-sm font-semibold hover:opacity-90 transition-all">
                                                    <i class="fas fa-plus-circle ml-2 text-xs"></i>سجل الآن
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Navigation buttons -->
                        <div class="swiper-button-next courses-next"></div>
                        <div class="swiper-button-prev courses-prev"></div>
                        <!-- Pagination -->
                        <div class="swiper-pagination courses-pagination"></div>
                    </div>
                </div>
            @else
                <div class="text-center py-8 md:py-12">
                    <i class="fas fa-book-open text-gray-400 text-4xl md:text-6xl mb-4"></i>
                    <p class="text-gray-600 text-base md:text-lg">لا توجد دورات متاحة حالياً</p>
                </div>
            @endif
        </div>
    </section>


    <script>
        // Enhanced slideshow functionality
        let currentSlide = 0;
        const slideContainer = document.getElementById('slideContainer');
        const slides = slideContainer?.querySelectorAll('.slide-item') || [];
        const dots = document.querySelectorAll('.slide-dot');

        function goToSlide(index) {
            if (slides.length === 0 || index < 0 || index >= slides.length) return;

            currentSlide = index;
            const translateValue = -index * 100;

            if (slideContainer) {
                slideContainer.style.transform = `translateX(${translateValue}%)`;
            }

            // Update active dot
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.remove('bg-white/50');
                    dot.classList.add('bg-white');
                } else {
                    dot.classList.remove('bg-white');
                    dot.classList.add('bg-white/50');
                }
            });
        }

        // Auto-advance slides every 5 seconds
        if (slides.length > 0) {
            setInterval(() => {
                currentSlide = (currentSlide + 1) % slides.length;
                goToSlide(currentSlide);
            }, 5000);

            // Initialize first slide
            goToSlide(0);
        }

        // Toggle course description expand/collapse
        function toggleDescription(index) {
            const shortDesc = document.getElementById(`desc-short-${index}`);
            const fullDesc = document.getElementById(`desc-full-${index}`);
            const toggleBtn = document.getElementById(`toggle-btn-${index}`);
            const toggleText = toggleBtn.querySelector('.toggle-text');
            const toggleIcon = toggleBtn.querySelector('.toggle-icon');

            if (shortDesc.classList.contains('hidden')) {
                // Collapse
                shortDesc.classList.remove('hidden');
                fullDesc.classList.add('hidden');
                toggleText.textContent = 'عرض المزيد';
                toggleIcon.classList.remove('fa-chevron-up');
                toggleIcon.classList.add('fa-chevron-down');
            } else {
                // Expand
                shortDesc.classList.add('hidden');
                fullDesc.classList.remove('hidden');
                toggleText.textContent = 'عرض أقل';
                toggleIcon.classList.remove('fa-chevron-down');
                toggleIcon.classList.add('fa-chevron-up');
            }
        }

        // Toggle section expand/collapse (Family Brief & What's New)
        function toggleSection(sectionName) {
            const shortDiv = document.getElementById(`${sectionName}-short`);
            const fullDiv = document.getElementById(`${sectionName}-full`);
            const toggleBtn = document.getElementById(`toggle-${sectionName}-btn`);
            const toggleText = toggleBtn.querySelector('.toggle-text');
            const toggleIcon = toggleBtn.querySelector('.toggle-icon');

            if (shortDiv.classList.contains('hidden')) {
                // Collapse
                shortDiv.classList.remove('hidden');
                fullDiv.classList.add('hidden');
                toggleText.textContent = 'عرض المزيد';
                toggleIcon.classList.remove('fa-chevron-up');
                toggleIcon.classList.add('fa-chevron-down');
            } else {
                // Expand
                shortDiv.classList.add('hidden');
                fullDiv.classList.remove('hidden');
                toggleText.textContent = 'عرض أقل';
                toggleIcon.classList.remove('fa-chevron-down');
                toggleIcon.classList.add('fa-chevron-up');
            }
        }

        // Hide toggle button if description is short
        document.addEventListener('DOMContentLoaded', function() {
            // Check course descriptions
            const toggleButtons = document.querySelectorAll('.course-toggle-btn');
            toggleButtons.forEach((btn, index) => {
                const shortDesc = document.getElementById(`desc-short-${index}`);

                if (shortDesc) {
                    // Check if the description is truncated (scrollHeight > clientHeight)
                    // For line-clamp-2, if scrollHeight is not much greater than clientHeight, it means no truncation
                    const isTruncated = shortDesc.scrollHeight > shortDesc.clientHeight + 10;

                    if (!isTruncated) {
                        btn.style.display = 'none';
                    }
                }
            });

            // Check family brief
            const familyBriefBtn = document.getElementById('toggle-family-brief-btn');
            const familyBriefShort = document.getElementById('family-brief-short');
            if (familyBriefShort && familyBriefBtn) {
                const isTruncated = familyBriefShort.scrollHeight > familyBriefShort.clientHeight + 10;
                if (!isTruncated) {
                    familyBriefBtn.style.display = 'none';
                }
            }

            // Check what's new
            const whatsNewBtn = document.getElementById('toggle-whats-new-btn');
            const whatsNewShort = document.getElementById('whats-new-short');
            if (whatsNewShort && whatsNewBtn) {
                const isTruncated = whatsNewShort.scrollHeight > whatsNewShort.clientHeight + 10;
                if (!isTruncated) {
                    whatsNewBtn.style.display = 'none';
                }
            }
        });
    </script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Initialize Gallery Swiper
        document.addEventListener('DOMContentLoaded', function() {
            const totalImages = {{ $latestGalleryImages->count() ?? 0 }};
            const gallerySwiper = new Swiper('.gallerySwiper', {
                slidesPerView: 2,
                spaceBetween: 10,
                loop: totalImages > 4,
                autoplay: {
                    delay: 5000,
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
                        slidesPerView: 4,
                        spaceBetween: 15,
                    },
                },
            });
        });
    </script>

    <script>
        // Initialize Courses Swiper
        document.addEventListener('DOMContentLoaded', function() {
            const totalCourses = {{ $courses->count() ?? 0 }};
            if (totalCourses > 0) {
                const coursesSwiper = new Swiper('.coursesSwiper', {
                    slidesPerView: 1,
                    spaceBetween: 15,
                    loop: totalCourses > 4,
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
                            spaceBetween: 15,
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 20,
                        },
                        1280: {
                            slidesPerView: 4,
                            spaceBetween: 20,
                        },
                    },
                });
            }
        });
    </script>

    <script>
        // Council Description Toggle
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
    </script>

</html>
