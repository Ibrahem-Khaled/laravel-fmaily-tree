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
                <p class="text-gray-600 text-xs md:text-sm mt-1">نأمل فخراً بإنجازاتهم الأكاديمية</p>
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
