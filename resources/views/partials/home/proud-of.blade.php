{{-- ================================================================
PROUD OF (Currently disabled in main template)
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
