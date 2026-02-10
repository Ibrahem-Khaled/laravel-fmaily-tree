{{--
    Universal Item Renderer
    يعرض أي عنصر بغض النظر عن نوع القسم
    يُستقبل: $item (HomeSectionItem)
--}}
@php
    $content = $item->content ?? [];
    $settings = $item->settings ?? [];
@endphp

{{-- ======================== RICH TEXT ======================== --}}
@if ($item->item_type === 'rich_text' && isset($content['html']) && !empty(trim($content['html'])))
    <div class="glass-card rounded-2xl p-4 md:p-6 shadow-lg">
        <div class="prose prose-lg max-w-none text-right leading-relaxed dynamic-rich-text"
             style="direction: rtl;{{ isset($settings['text_align']) ? ' text-align:'.$settings['text_align'].';' : '' }}">
            {!! $content['html'] !!}
        </div>
    </div>

{{-- ======================== PLAIN TEXT ======================== --}}
@elseif ($item->item_type === 'text' && isset($content['text']) && !empty(trim($content['text'])))
    <div class="glass-card rounded-2xl p-4 md:p-6 shadow-lg">
        <div class="text-gray-700 text-sm md:text-base leading-relaxed whitespace-pre-line" style="direction: rtl;">
            {!! nl2br(e($content['text'])) !!}
        </div>
    </div>

{{-- ======================== IMAGE ======================== --}}
@elseif ($item->item_type === 'image' && $item->image_url)
    <div class="relative group overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300">
        @if (isset($content['link']) && $content['link'])
            <a href="{{ $content['link'] }}" target="_blank" class="block">
        @endif
        <img src="{{ $item->image_url }}" alt="{{ $content['alt'] ?? '' }}"
             class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105"
             loading="lazy">
        @if (isset($content['caption']) && $content['caption'])
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3 md:p-4">
                <p class="text-white text-xs md:text-sm font-semibold text-right">{{ $content['caption'] }}</p>
            </div>
        @endif
        @if (isset($content['link']) && $content['link'])
            </a>
        @endif
    </div>

{{-- ======================== VIDEO ======================== --}}
@elseif ($item->item_type === 'video')
    <div class="w-full">
        @if (isset($content['title']) && $content['title'])
            <h4 class="font-bold text-lg mb-3 text-right">{{ $content['title'] }}</h4>
        @endif
        @if ($item->youtube_url)
            @php
                preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $item->youtube_url, $ytMatches);
                $videoId = $ytMatches[1] ?? null;
            @endphp
            @if ($videoId)
                <div class="relative w-full rounded-2xl overflow-hidden shadow-lg" style="padding-bottom: 56.25%;">
                    <iframe class="absolute top-0 left-0 w-full h-full"
                            src="https://www.youtube.com/embed/{{ $videoId }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy"></iframe>
                </div>
            @endif
        @elseif ($item->video_url)
            <video controls class="w-full rounded-2xl shadow-lg" style="max-height: 500px;" preload="metadata">
                <source src="{{ $item->video_url }}" type="video/mp4">
            </video>
        @endif
    </div>

{{-- ======================== TABLE ======================== --}}
@elseif ($item->item_type === 'table' && isset($content['table_data']) && is_array($content['table_data']))
    @php
        $tableData = $content['table_data'];
        $hasHeader = $settings['has_header'] ?? true;
        $striped = $settings['striped'] ?? false;
    @endphp
    <div class="glass-card rounded-2xl p-4 md:p-6 shadow-lg overflow-hidden">
        @if (isset($content['title']) && $content['title'])
            <h4 class="font-bold text-lg mb-3 text-right">{{ $content['title'] }}</h4>
        @endif
        <div class="overflow-x-auto" style="direction: rtl;">
            <table class="w-full border-collapse text-sm md:text-base">
                @foreach ($tableData as $ri => $row)
                    @if(is_array($row))
                    <tr class="{{ $ri === 0 && $hasHeader ? 'bg-gradient-to-l from-blue-50 to-indigo-50' : ($striped && $ri % 2 === 0 && !($ri === 0 && $hasHeader) ? 'bg-gray-50' : '') }}">
                        @foreach ($row as $cell)
                            @if ($ri === 0 && $hasHeader)
                                <th class="border border-gray-200 px-3 md:px-4 py-2 md:py-3 text-right font-bold text-gray-800">{{ $cell }}</th>
                            @else
                                <td class="border border-gray-200 px-3 md:px-4 py-2 md:py-3 text-right text-gray-700">{{ $cell }}</td>
                            @endif
                        @endforeach
                    </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>

{{-- ======================== CARD ======================== --}}
@elseif ($item->item_type === 'card')
    @php $cardColor = $settings['card_color'] ?? 'white'; @endphp
    <div class="group glass-card rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden {{ $cardColor !== 'white' ? 'text-white' : '' }}"
         @if($cardColor !== 'white') style="background: var(--color-{{ $cardColor }}, #4e73df);" @endif>
        @if ($item->image_url)
            <div class="relative overflow-hidden" style="height: 180px;">
                <img src="{{ $item->image_url }}" alt="{{ $content['title'] ?? '' }}"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                     loading="lazy">
            </div>
        @endif
        <div class="p-4 md:p-5 text-right">
            @if (isset($content['icon']) && $content['icon'])
                <div class="mb-3">
                    <i class="{{ $content['icon'] }} text-2xl {{ $cardColor !== 'white' ? '' : 'text-blue-600' }}"></i>
                </div>
            @endif
            @if (isset($content['title']) && $content['title'])
                <h4 class="font-bold text-lg mb-2">{{ $content['title'] }}</h4>
            @endif
            @if (isset($content['description']) && $content['description'])
                <p class="text-sm {{ $cardColor !== 'white' ? 'opacity-90' : 'text-gray-600' }} leading-relaxed mb-3">
                    {{ $content['description'] }}
                </p>
            @endif
            @if (isset($content['link']) && $content['link'])
                <a href="{{ $content['link'] }}"
                   class="inline-flex items-center gap-1 text-sm font-semibold {{ $cardColor !== 'white' ? 'text-white underline' : 'text-blue-600 hover:text-blue-800' }} transition-colors">
                    {{ $content['button_text'] ?? 'اقرأ المزيد' }}
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
            @endif
        </div>
    </div>

{{-- ======================== BUTTON ======================== --}}
@elseif ($item->item_type === 'button' && isset($content['text']) && !empty(trim($content['text'])))
    @php
        $btnColor = $settings['btn_color'] ?? 'primary';
        $btnSize = $settings['btn_size'] ?? 'md';
        $btnBlock = $settings['btn_block'] ?? false;
        $newTab = $settings['new_tab'] ?? false;
        $sizeClass = $btnSize === 'lg' ? 'text-lg px-8 py-4' : ($btnSize === 'sm' ? 'text-sm px-4 py-2' : 'px-6 py-3');
    @endphp
    <div class="{{ $btnBlock ? 'w-full' : 'text-center' }}">
        <a href="{{ $content['url'] ?? '#' }}"
           @if($newTab) target="_blank" @endif
           class="inline-flex items-center gap-2 {{ $sizeClass }} gradient-primary text-white rounded-xl font-semibold hover:shadow-lg transition-all duration-300 {{ $btnBlock ? 'w-full justify-center' : '' }}">
            @if (isset($content['icon']) && $content['icon'])
                <i class="{{ $content['icon'] }}"></i>
            @endif
            {{ $content['text'] }}
        </a>
    </div>

{{-- ======================== HTML CUSTOM ======================== --}}
@elseif ($item->item_type === 'html' && isset($content['html']) && !empty(trim($content['html'])))
    <div class="dynamic-html-content" style="direction: rtl;">
        {!! $content['html'] !!}
    </div>

{{-- ======================== SPACER / DIVIDER ======================== --}}
@elseif ($item->item_type === 'spacer')
    @php
        $height = $settings['height'] ?? 40;
        $showLine = $settings['show_line'] ?? false;
    @endphp
    <div class="{{ isset($section) && in_array($section->section_type ?? '', ['gallery','cards','stats']) ? 'col-span-full' : '' }}"
         style="height: {{ $height }}px; display: flex; align-items: center; justify-content: center;">
        @if ($showLine)
            <hr class="w-full border-gray-200" style="border-top-width: 1px;">
        @endif
    </div>
@endif
