{{-- ================================================================
FAMILY PROGRAMS — قسم كامل لكل فئة، عنوان القسم = اسم الفئة
================================================================ --}}
@if (isset($programCategories) && $programCategories->count() > 0)
    @foreach ($programCategories as $group)
        <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-br from-green-50 to-emerald-50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-200 rounded-full blur-3xl opacity-20"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
                <div class="text-right mb-3 md:mb-5">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">{{ $group['title'] }}</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1.5 md:gap-2.5">
                    @foreach ($group['programs'] as $program)
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
            </div>
        </section>
    @endforeach
@else
    <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-br from-green-50 to-emerald-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-200 rounded-full blur-3xl opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
            <div class="text-right mb-3 md:mb-5">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">برامج العائلة</h2>
            </div>
            <div class="text-center py-8">
                <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500 text-sm md:text-base">لا توجد برامج متاحة حالياً</p>
            </div>
        </div>
    </section>
@endif
