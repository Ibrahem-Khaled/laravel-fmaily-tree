{{-- ================================================================
GALLERY – "اخترنا لك" (Currently disabled in main template)
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
