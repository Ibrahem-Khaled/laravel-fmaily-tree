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
                                                                <div
                                                                    class="rich-content text-xs text-gray-700 leading-relaxed">
                                                                    {!! $council->description !!}
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
