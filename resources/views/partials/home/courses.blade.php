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
