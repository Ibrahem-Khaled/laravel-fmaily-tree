{{-- ================================================================
FAMILY EVENTS – مناسبات العائلة (يُعرض القسم فقط عند وجود مناسبات)
================================================================ --}}
@if ($events && $events->count() > 0)
<section class="py-2 md:py-4 lg:py-6 bg-white mobile-section">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

        <div class="text-right mb-3 md:mb-4">
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gradient mb-1">مناسبات العائلة</h2>
            <div
                class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0 mb-1 md:mb-2">
            </div>
        </div>

            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow-lg rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 bg-white">
                            <thead class="gradient-primary text-white">
                                <tr>
                                    <th
                                        class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                        المناسبة</th>
                                    <th
                                        class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                        المدينة</th>
                                    <th
                                        class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                        الموقع</th>
                                    <th
                                        class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                        التاريخ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($events as $event)
                                    <tr
                                        class="hover:bg-gray-50 transition-colors {{ filled($event->description) ? 'cursor-pointer' : '' }} {{ !$event->is_active && Auth::check() ? 'opacity-60' : '' }}"
                                        @if (filled($event->description))
                                            role="button"
                                            tabindex="0"
                                            aria-expanded="false"
                                            aria-controls="event-desc-panel-{{ $event->id }}"
                                            aria-label="عرض أو إخفاء وصف المناسبة"
                                            data-event-desc-trigger="{{ $event->id }}"
                                            onclick="toggleEventDescription({{ $event->id }})"
                                            onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();toggleEventDescription({{ $event->id }});}"
                                        @endif>
                                        <td class="px-2 py-1.5 md:px-3 md:py-2 text-right" dir="ltr">
                                            <div class="flex items-start justify-end gap-1.5">
                                                <span
                                                    class="text-xs font-semibold text-gray-900 break-words flex-1 text-right">{{ $event->title }}</span>
                                                <div class="flex items-center gap-1 flex-shrink-0">
                                                    <i class="fas fa-calendar-alt text-green-600 text-xs"></i>
                                                    @if (filled($event->description))
                                                        <i
                                                            class="fas fa-chevron-down text-green-500 text-xs transition-transform duration-300 event-chevron-{{ $event->id }}"
                                                            aria-hidden="true"></i>
                                                    @endif
                                                    @if (!$event->is_active && Auth::check())
                                                        <span
                                                            class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-2 py-1.5 md:px-3 md:py-2 text-right">
                                            <span class="text-xs text-gray-700">{{ $event->city ?? '-' }}</span>
                                        </td>
                                        <td
                                            class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-xs font-medium text-right">
                                            @if ($event->location)
                                                <a href="{{ $event->location }}" target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="inline-flex items-center text-blue-600 hover:text-blue-800 group"
                                                    onclick="event.stopPropagation();"
                                                    title="{{ $event->location_name ?? 'فتح الموقع على الخريطة' }}">
                                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"
                                                            fill="#EA4335" stroke="#fff" stroke-width="1.5" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-right">
                                            <div class="text-xs font-medium text-gray-900 mb-1">
                                                {{ $event->event_date->format('Y-m-d') }}
                                            </div>
                                            @if ($event->show_countdown && $event->event_date->isFuture())
                                                <div class="event-countdown-{{ $event->id }} text-xs text-green-600 font-semibold text-right"
                                                    data-event-date="{{ $event->event_date->format('Y-m-d H:i:s') }}">
                                                    <span class="countdown-days"></span> يوم
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @if (filled($event->description))
                                        <tr id="event-desc-panel-{{ $event->id }}"
                                            class="event-description-row hidden"
                                            data-event-desc-panel="{{ $event->id }}">
                                            <td colspan="4" class="px-2 py-0 md:px-3">
                                                <div
                                                    class="event-description-content overflow-hidden transition-all duration-500 ease-in-out"
                                                    style="max-height: 0;">
                                                    <div
                                                        class="bg-gradient-to-r from-green-50 to-emerald-50 border-r-4 border-green-500 rounded-lg p-3 md:p-4 my-1.5 shadow-md">
                                                        <div class="flex items-start gap-2">
                                                            <i
                                                                class="fas fa-info-circle text-green-600 text-sm flex-shrink-0 mt-0.5"></i>
                                                            <div>
                                                                <h4
                                                                    class="text-xs md:text-sm font-semibold text-gray-800 mb-1.5">
                                                                    نبذة عن {{ $event->title }}</h4>
                                                                <div
                                                                    class="rich-content text-xs text-gray-700 leading-relaxed">
                                                                    {!! $event->description !!}
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

    </div>
</section>
@endif
