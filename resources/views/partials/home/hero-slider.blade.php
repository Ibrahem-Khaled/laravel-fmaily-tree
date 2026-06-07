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
