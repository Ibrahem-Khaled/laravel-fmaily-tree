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
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        }

        .slide-item {
            min-width: 100%;
            flex-shrink: 0;
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
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
                min-height: 33vh;
                max-height: 33vh;
                overflow-y: auto;
            }

            h2 {
                font-size: 1.25rem !important;
                margin-bottom: 0.5rem !important;
            }

            .container {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            .mb-8, .mb-12 {
                margin-bottom: 1rem !important;
            }

            .py-12, .py-16, .py-20 {
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
            }
        }

        /* تأثيرات إبداعية */
        .coming-soon-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
        }

        .coming-soon-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            background-size: 200% 100%;
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="bg-gray-50">
    @include('partials.main-header')

    {{-- Hero Section with Slideshow --}}
    <section class="relative h-[120px] sm:h-[180px] md:h-[240px] lg:h-[320px] overflow-hidden gradient-bg mobile-section">
        <div class="absolute inset-0 slide-container" id="slideContainer">
            @if($latestImages->count() > 0)
                @foreach($latestImages->take(10) as $index => $slideshowImage)
                    <div class="slide-item h-full relative">
                        @if($slideshowImage->image_url)
                            <img src="{{ $slideshowImage->image_url }}"
                                 alt="{{ $slideshowImage->title ?? 'صورة' }}"
                                 class="w-full h-full object-cover opacity-90">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                        @if($slideshowImage->title || $slideshowImage->description)
                            <div class="absolute bottom-4 md:bottom-6 right-3 left-3 md:right-6 md:left-6">
                                <div class="glass-effect rounded-lg p-2 md:p-3 lg:p-4 max-w-xl">
                                    @if($slideshowImage->title)
                                        <h2 class="text-white text-sm md:text-base lg:text-lg font-bold">{{ $slideshowImage->title }}</h2>
                                    @endif
                                    @if($slideshowImage->description)
                                        <p class="text-white/90 text-xs md:text-sm line-clamp-2">{{ $slideshowImage->description }}</p>
                                    @endif
                                    @if($slideshowImage->link)
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
        @if($latestImages->count() > 0)
            <div class="absolute bottom-3 md:bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
                @foreach($latestImages->take(10) as $index => $slideshowImage)
                    <button class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-white/50 hover:bg-white transition-all slide-dot {{ $index === 0 ? 'bg-white' : '' }}"
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

    {{-- What's New Section --}}
    <section class="py-6 md:py-12 lg:py-16 bg-white mobile-section">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
            <div class="text-right mb-4 md:mb-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient mb-2 md:mb-4">الصور</h2>
                <div class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0"></div>
            </div>

            {{-- آخر 8 صور من المعرض - Carousel --}}
            @if($latestGalleryImages->count() > 0)
                <div class="relative mb-6">
                    {{-- Navigation Buttons --}}
                    <button onclick="slideGallery('prev')"
                            class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:shadow-xl transition-all hover:bg-gray-50"
                            id="gallery-prev-btn"
                            aria-label="السابق">
                        <i class="fas fa-chevron-right text-green-600 text-lg"></i>
                    </button>
                    <button onclick="slideGallery('next')"
                            class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-lg hover:shadow-xl transition-all hover:bg-gray-50"
                            id="gallery-next-btn"
                            aria-label="التالي">
                        <i class="fas fa-chevron-left text-green-600 text-lg"></i>
                    </button>

                    {{-- Gallery Container --}}
                    <div class="overflow-hidden rounded-lg" id="gallery-wrapper">
                        <div class="flex transition-transform duration-500 ease-in-out" id="gallery-container" style="transform: translateX(0);">
                            @foreach($latestGalleryImages as $galleryImage)
                                <div class="gallery-slide flex-shrink-0 w-1/2 sm:w-1/4 px-2">
                                        <div class="relative group overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                                        <img src="{{ asset('storage/' . $galleryImage->path) }}"
                                             alt="{{ $galleryImage->name ?? 'صورة' }}"
                                             class="w-full h-24 md:h-32 lg:h-40 object-cover transition-transform duration-300 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="absolute bottom-2 right-2 left-2">
                                                <p class="text-white text-xs md:text-sm font-semibold truncate">
                                                    {{ $galleryImage->name ?? 'صورة' }}
                                                </p>
                                                @if($galleryImage->category)
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
                    </div>

                    {{-- Dots Indicator --}}
                    <div class="flex justify-center gap-2 mt-4" id="gallery-dots">
                        {{-- Dots will be generated dynamically by JavaScript --}}
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Family Programs Section --}}
    {{-- <section class="py-6 md:py-12 lg:py-16 bg-gradient-to-br from-green-50 via-white to-emerald-50 mobile-section relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 right-0 w-96 h-96 bg-green-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-400 rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
            <div class="text-right mb-4 md:mb-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient mb-2 md:mb-4">برامج عائلة السريع</h2>
                <div class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0 mb-2 md:mb-4"></div>
            </div>

            <div class="text-center py-8 md:py-12 lg:py-16 ">
                <div class="coming-soon-card w-full inline-block rounded-xl md:rounded-2xl shadow-lg md:shadow-xl p-6 md:p-10 lg:p-12 max-w-lg mx-auto relative overflow-hidden shimmer">
                    <div class="relative z-10">
                        <div class="mb-4 md:mb-6">
                            <div class="inline-block relative">
                                <i class="fas fa-hourglass-half text-green-500 text-4xl md:text-5xl lg:text-6xl animate-pulse"></i>
                                <div class="absolute inset-0 bg-green-400 rounded-full blur-xl opacity-30 animate-ping"></div>
                            </div>
                        </div>
                        <h3 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 mb-2 md:mb-3">قريباً</h3>
                        <div class="mt-4 md:mt-6 flex justify-center gap-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                            <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    {{-- Courses Section --}}
    {{-- <section class="py-6 md:py-12 lg:py-16 bg-white mobile-section">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <div class="text-right mb-4 md:mb-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient mb-2 md:mb-4">دورات اكادمية السريع</h2>
                <div class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0 mb-2 md:mb-4"></div>
                <p class="text-gray-600 text-sm md:text-base">اكتشف مجموعة متنوعة من الدورات التعليمية والثقافية</p>
            </div>

            @if($courses->count() > 0)
                <div class="relative">
                    <button onclick="slideCourses('prev')"
                            class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-3 shadow-lg hover:shadow-xl transition-all hover:bg-gray-50"
                            id="courses-prev-btn"
                            aria-label="السابق">
                        <i class="fas fa-chevron-right text-green-600 text-xl"></i>
                    </button>
                    <button onclick="slideCourses('next')"
                            class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-3 shadow-lg hover:shadow-xl transition-all hover:bg-gray-50"
                            id="courses-next-btn"
                            aria-label="التالي">
                        <i class="fas fa-chevron-left text-green-600 text-xl"></i>
                    </button>

                    <div class="overflow-hidden rounded-lg">
                        <div class="flex transition-transform duration-500 ease-in-out" id="courses-container" style="transform: translateX(0);">
                            @foreach($courses as $course)
                                <div class="course-slide flex-shrink-0 w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-2 md:px-3">
                                    <div class="course-card bg-white rounded-lg md:rounded-xl shadow-lg overflow-hidden h-full">
                                        <div class="relative h-32 md:h-40 lg:h-48 bg-gradient-to-br from-green-400 to-emerald-600">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <i class="fas fa-book-open text-white text-3xl md:text-5xl lg:text-6xl opacity-30"></i>
                                            </div>
                                            @if($course->image_url)
                                                <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>

                                        <div class="p-3 md:p-4 lg:p-6">
                                            <h3 class="text-base md:text-lg lg:text-xl font-bold text-gray-800 mb-1 md:mb-2 line-clamp-2">{{ $course->title }}</h3>

                                            <div class="mb-2 md:mb-4">
                                                <p class="text-gray-600 text-xs md:text-sm course-description-short line-clamp-2" id="desc-short-{{ $loop->index }}">
                                                    {{ $course->description ?? '' }}
                                                </p>
                                                <p class="text-gray-600 text-xs md:text-sm course-description-full hidden" id="desc-full-{{ $loop->index }}">
                                                    {{ $course->description ?? '' }}
                                                </p>
                                                @if($course->description && strlen($course->description) > 80)
                                                    <button onclick="toggleDescription({{ $loop->index }})"
                                                            class="text-green-600 hover:text-green-700 text-xs font-semibold mt-1 flex items-center gap-1 transition-colors course-toggle-btn"
                                                            id="toggle-btn-{{ $loop->index }}">
                                                        <span class="toggle-text">عرض المزيد</span>
                                                        <i class="fas fa-chevron-down toggle-icon text-xs"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            <div class="space-y-1 md:space-y-1.5 mb-2 md:mb-4">
                                                @if($course->instructor)
                                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                        <i class="fas fa-user text-green-500 text-xs"></i>
                                                        <span class="truncate">{{ $course->instructor }}</span>
                                                    </div>
                                                @endif
                                                @if($course->duration)
                                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                        <i class="fas fa-clock text-green-500 text-xs"></i>
                                                        <span>{{ $course->duration }}</span>
                                                    </div>
                                                @endif
                                                @if($course->students > 0)
                                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                        <i class="fas fa-users text-green-500 text-xs"></i>
                                                        <span>{{ $course->students }} طالب</span>
                                                    </div>
                                                @endif
                                            </div>

                                            @if($course->link)
                                                <a href="{{ $course->link }}" target="_blank" class="w-full py-1.5 md:py-2 px-3 md:px-4 gradient-bg text-white rounded-lg text-xs md:text-sm font-semibold hover:opacity-90 transition-all flex items-center justify-center">
                                                    <i class="fas fa-external-link-alt ml-2 text-xs"></i>سجل الآن
                                                </a>
                                            @else
                                                <button class="w-full py-1.5 md:py-2 px-3 md:px-4 gradient-bg text-white rounded-lg text-xs md:text-sm font-semibold hover:opacity-90 transition-all">
                                                    <i class="fas fa-plus-circle ml-2 text-xs"></i>سجل الآن
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @php
                        $totalCourses = $courses->count();
                        $coursesPerView = 4; // Show 4 courses per slide on desktop
                        $maxDots = max(1, ceil($totalCourses / $coursesPerView));
                    @endphp
                    @if($maxDots > 1)
                        <div class="flex justify-center gap-2 mt-6" id="courses-dots">
                            @for($i = 0; $i < $maxDots; $i++)
                                <button onclick="goToCoursesSlide({{ $i }})"
                                        class="w-2 h-2 rounded-full transition-all {{ $i === 0 ? 'bg-green-600 w-6' : 'bg-gray-300' }}"
                                        id="courses-dot-{{ $i }}"
                                        aria-label="انتقل للشريحة {{ $i + 1 }}"></button>
                            @endfor
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-book-open text-gray-400 text-6xl mb-4"></i>
                    <p class="text-gray-600 text-lg">لا توجد دورات متاحة حالياً</p>
                </div>
            @endif
        </div>
    </section> --}}

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

    <script>
        // Gallery Carousel functionality
        let currentGallerySlide = 0;
        const galleryContainer = document.getElementById('gallery-container');
        const galleryWrapper = document.getElementById('gallery-wrapper');
        const gallerySlides = document.querySelectorAll('.gallery-slide');
        const totalGalleryImages = {{ $latestGalleryImages->count() ?? 0 }};

        // Touch/swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        let isDragging = false;
        let startX = 0;
        let scrollLeft = 0;

        function updateGallerySlidesPerView() {
            return window.innerWidth >= 640 ? 4 : 2;
        }

        function slideGallery(direction) {
            const slidesPerView = updateGallerySlidesPerView();
            const maxSlides = Math.ceil(totalGalleryImages / slidesPerView);

            if (direction === 'next') {
                if (currentGallerySlide < maxSlides - 1) {
                    currentGallerySlide++;
                } else {
                    currentGallerySlide = 0; // Loop back to start
                }
            } else {
                if (currentGallerySlide > 0) {
                    currentGallerySlide--;
                } else {
                    currentGallerySlide = maxSlides - 1; // Loop to end
                }
            }

            updateGalleryPosition();
            updateGalleryDots();
            updateGalleryButtons();
        }

        function goToGallerySlide(index) {
            const slidesPerView = updateGallerySlidesPerView();
            const maxSlides = Math.ceil(totalGalleryImages / slidesPerView);

            if (index >= 0 && index < maxSlides) {
                currentGallerySlide = index;
                updateGalleryPosition();
                updateGalleryDots();
                updateGalleryButtons();
            }
        }

        function updateGalleryPosition() {
            if (!galleryContainer) return;

            const slidesPerView = updateGallerySlidesPerView();
            // Calculate translateX based on the number of slides to move
            // Each slide group shows 'slidesPerView' images
            // On mobile: w-1/2 means 50% per image, so 2 images = 100%
            // On desktop: w-1/4 means 25% per image, so 4 images = 100%
            // So we move by 100% per slide group
            const translateX = -(currentGallerySlide * 100);

            galleryContainer.style.transform = `translateX(${translateX}%)`;
        }

        function createGalleryDots() {
            const slidesPerView = updateGallerySlidesPerView();
            const maxSlides = Math.ceil(totalGalleryImages / slidesPerView);
            const dotsContainer = document.getElementById('gallery-dots');

            if (!dotsContainer) return;

            // Clear existing dots
            dotsContainer.innerHTML = '';

            // Create dots dynamically
            for (let i = 0; i < maxSlides; i++) {
                const dot = document.createElement('button');
                dot.onclick = () => goToGallerySlide(i);
                dot.className = `w-2 h-2 rounded-full transition-all ${i === currentGallerySlide ? 'bg-green-600 w-6' : 'bg-gray-300'}`;
                dot.id = `gallery-dot-${i}`;
                dot.setAttribute('aria-label', `انتقل للشريحة ${i + 1}`);
                dotsContainer.appendChild(dot);
            }
        }

        function updateGalleryDots() {
            const slidesPerView = updateGallerySlidesPerView();
            const maxSlides = Math.ceil(totalGalleryImages / slidesPerView);
            const dots = document.querySelectorAll('[id^="gallery-dot-"]');

            // Recreate dots if count changed (e.g., on resize)
            if (dots.length !== maxSlides) {
                createGalleryDots();
                return;
            }

            dots.forEach((dot, index) => {
                if (index === currentGallerySlide) {
                    dot.classList.remove('bg-gray-300', 'w-2');
                    dot.classList.add('bg-green-600', 'w-6');
                } else {
                    dot.classList.remove('bg-green-600', 'w-6');
                    dot.classList.add('bg-gray-300', 'w-2');
                }
            });
        }

        function updateGalleryButtons() {
            const slidesPerView = updateGallerySlidesPerView();
            const maxSlides = Math.ceil(totalGalleryImages / slidesPerView);

            const prevBtn = document.getElementById('gallery-prev-btn');
            const nextBtn = document.getElementById('gallery-next-btn');

            // يمكن إخفاء الأزرار إذا كان هناك شريحة واحدة فقط
            if (maxSlides <= 1) {
                if (prevBtn) prevBtn.style.display = 'none';
                if (nextBtn) nextBtn.style.display = 'none';
            } else {
                if (prevBtn) prevBtn.style.display = 'block';
                if (nextBtn) nextBtn.style.display = 'block';
            }
        }

        // Touch/swipe event handlers for mobile
        if (galleryWrapper) {
            galleryWrapper.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
                isDragging = true;
                galleryContainer.style.transition = 'none';
            }, { passive: true });

            galleryWrapper.addEventListener('touchmove', function(e) {
                if (!isDragging) return;
                e.preventDefault();
                const currentX = e.changedTouches[0].screenX;
                const diff = touchStartX - currentX;
                const wrapperWidth = galleryWrapper.offsetWidth;
                const translatePercent = (diff / wrapperWidth) * 100;
                const baseTranslate = -(currentGallerySlide * 100);
                galleryContainer.style.transform = `translateX(${baseTranslate + translatePercent}%)`;
            }, { passive: false });

            galleryWrapper.addEventListener('touchend', function(e) {
                if (!isDragging) return;
                touchEndX = e.changedTouches[0].screenX;
                isDragging = false;
                galleryContainer.style.transition = 'transform 0.5s ease-in-out';

                const swipeThreshold = 50; // Minimum distance for swipe
                const diff = touchStartX - touchEndX;

                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        // Swipe left - next
                        slideGallery('next');
                    } else {
                        // Swipe right - prev
                        slideGallery('prev');
                    }
                } else {
                    // Reset position if swipe wasn't significant
                    updateGalleryPosition();
                }
            }, { passive: true });
        }

        // Update on window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                currentGallerySlide = 0;
                createGalleryDots();
                updateGalleryPosition();
                updateGalleryButtons();
            }, 250);
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (totalGalleryImages > 0) {
                createGalleryDots();
                updateGalleryPosition();
                updateGalleryButtons();
            }
        });
    </script>

    <script>
        // Courses Carousel functionality
        let currentCoursesSlide = 0;
        const coursesContainer = document.getElementById('courses-container');
        const totalCourses = {{ $courses->count() ?? 0 }};

        function updateCoursesSlidesPerView() {
            if (window.innerWidth >= 1280) return 4; // xl
            if (window.innerWidth >= 1024) return 3; // lg
            if (window.innerWidth >= 640) return 2;  // sm
            return 1; // mobile
        }

        function slideCourses(direction) {
            const slidesPerView = updateCoursesSlidesPerView();
            const maxSlides = Math.ceil(totalCourses / slidesPerView);

            if (direction === 'next') {
                currentCoursesSlide = (currentCoursesSlide + 1) % maxSlides;
            } else {
                currentCoursesSlide = (currentCoursesSlide - 1 + maxSlides) % maxSlides;
            }

            updateCoursesPosition();
            updateCoursesDots();
            updateCoursesButtons();
        }

        function goToCoursesSlide(index) {
            currentCoursesSlide = index;
            updateCoursesPosition();
            updateCoursesDots();
            updateCoursesButtons();
        }

        function updateCoursesPosition() {
            const slidesPerView = updateCoursesSlidesPerView();
            const slideWidth = 100 / slidesPerView;
            const translateX = -(currentCoursesSlide * slideWidth * slidesPerView);

            if (coursesContainer) {
                coursesContainer.style.transform = `translateX(${translateX}%)`;
            }
        }

        function updateCoursesDots() {
            const dots = document.querySelectorAll('[id^="courses-dot-"]');
            dots.forEach((dot, index) => {
                if (index === currentCoursesSlide) {
                    dot.classList.remove('bg-gray-300', 'w-2');
                    dot.classList.add('bg-green-600', 'w-6');
                } else {
                    dot.classList.remove('bg-green-600', 'w-6');
                    dot.classList.add('bg-gray-300', 'w-2');
                }
            });
        }

        function updateCoursesButtons() {
            const slidesPerView = updateCoursesSlidesPerView();
            const maxSlides = Math.ceil(totalCourses / slidesPerView);

            const prevBtn = document.getElementById('courses-prev-btn');
            const nextBtn = document.getElementById('courses-next-btn');

            if (maxSlides <= 1) {
                if (prevBtn) prevBtn.style.display = 'none';
                if (nextBtn) nextBtn.style.display = 'none';
            } else {
                if (prevBtn) prevBtn.style.display = 'block';
                if (nextBtn) nextBtn.style.display = 'block';
            }
        }

        // Update on window resize
        let resizeTimerCourses;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimerCourses);
            resizeTimerCourses = setTimeout(function() {
                currentCoursesSlide = 0;
                updateCoursesPosition();
                updateCoursesDots();
                updateCoursesButtons();
            }, 250);
        });

        // Initialize courses carousel on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (totalCourses > 0) {
                updateCoursesDots();
                updateCoursesButtons();
            }
        });
    </script>

</html>

