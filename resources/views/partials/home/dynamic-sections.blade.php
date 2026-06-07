{{-- ================================================================
DYNAMIC SECTIONS – Universal renderer
================================================================ --}}
@if (isset($dynamicSections) && $dynamicSections->count() > 0)
    @foreach ($dynamicSections as $section)
        @php
            $ss          = $section->settings ?? [];
            $bgColor     = $ss['background_color'] ?? null;
            $txtColor    = $ss['text_color'] ?? null;
            $padTop      = $ss['padding_top'] ?? null;
            $padBottom   = $ss['padding_bottom'] ?? null;
            $showTitle   = $ss['show_title'] ?? true;
            $subtitle    = $ss['subtitle'] ?? null;
            $description = $ss['description'] ?? null;
            $icon        = $ss['icon'] ?? null;
            $columns     = $ss['columns'] ?? 3;

            // ── Build per-element typography styles ──────────────────────
            $buildTypoStyle = function (string $prefix) use ($ss): string {
                $style = '';
                if (!empty($ss["{$prefix}_color"]))  $style .= "color:{$ss["{$prefix}_color"]};";
                if (!empty($ss["{$prefix}_size"]))   $style .= "font-size:{$ss["{$prefix}_size"]};";
                if (!empty($ss["{$prefix}_weight"])) $style .= "font-weight:{$ss["{$prefix}_weight"]};";
                if (!empty($ss["{$prefix}_align"]))  $style .= "text-align:{$ss["{$prefix}_align"]};";
                return $style;
            };

            $titleStyle       = $buildTypoStyle('title');
            $subtitleStyle    = $buildTypoStyle('subtitle');
            $descriptionStyle = $buildTypoStyle('description');

            // Default align for description if not set
            if (empty($ss['description_align'])) {
                $descriptionStyle .= 'text-align:right;';
            }

            // ── Section-wide styles ──────────────────────────────────────
            $sectionStyle = '';
            if ($bgColor && $bgColor !== '#ffffff') {
                $sectionStyle .= "background-color:{$bgColor};";
            }
            if ($txtColor && $txtColor !== '#333333') {
                $sectionStyle .= "color:{$txtColor};";
            }
            if ($padTop !== null) {
                $sectionStyle .= "padding-top:{$padTop}px;";
            }
            if ($padBottom !== null) {
                $sectionStyle .= "padding-bottom:{$padBottom}px;";
            }

            $colsMap = [
                1 => 'grid-cols-1',
                2 => 'grid-cols-1 sm:grid-cols-2',
                3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
                4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
                6 => 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-6',
            ];
            $colsClass = $colsMap[$columns] ?? $colsMap[3];

            $layoutStyle = $ss['layout_style'] ?? 'grid';
            $isGrid = in_array($section->section_type, ['gallery', 'cards', 'stats']);
            $isTwoCol = in_array($section->section_type, ['text_with_image']);

            $hasSourceItems =
                isset($section->content_source_items) &&
                $section->content_source_items &&
                $section->content_source_items->count() > 0;
            $hasManualItems = $section->items->count() > 0;

            // حساب كلاس الـ layout بناءً على نمط العرض المختار
            if ($layoutStyle === 'horizontal') {
                $layoutClass = 'flex flex-row gap-3 md:gap-4 overflow-x-auto pb-4';
            } elseif ($layoutStyle === 'vertical') {
                $layoutClass = 'flex flex-col gap-3 md:gap-4';
            } else {
                $layoutClass = $isGrid
                    ? 'grid ' . $colsClass . ' gap-3 md:gap-4'
                    : ($isTwoCol
                        ? 'grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 items-center'
                        : 'space-y-4');
            }
        @endphp

        @if ($hasSourceItems || $hasManualItems)
            <section class="py-3 md:py-6 lg:py-8 {{ $section->css_classes ?? '' }} relative overflow-hidden"
                @if ($sectionStyle) style="{{ $sectionStyle }}" @endif>
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

                    @if ($showTitle && $section->title)
                        <div class="mb-3 md:mb-5" style="{{ $titleStyle ? '' : 'text-align:right;' }}">
                            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold mb-2 {{ $titleStyle ? '' : 'text-gradient section-title' }}"
                                @if ($titleStyle) style="{{ $titleStyle }}" @endif>
                                @if ($icon)
                                    <i class="{{ $icon }} mr-2"></i>
                                @endif
                                {{ $section->title }}
                            </h2>
                            @if ($subtitle)
                                <p class="text-sm md:text-base mt-1 {{ $subtitleStyle ? '' : 'text-gray-500' }}"
                                   @if ($subtitleStyle) style="{{ $subtitleStyle }}" @endif>{{ $subtitle }}</p>
                            @endif
                            @if ($description)
                                <p class="text-xs md:text-sm mt-1 max-w-2xl {{ $descriptionStyle ? '' : 'text-gray-400' }}"
                                   style="{{ $descriptionStyle }}">
                                    {{ $description }}
                                </p>
                            @endif
                        </div>
                    @endif

                    <div class="dynamic-section-content {{ $layoutClass }}">
                        @if ($hasSourceItems)
                            @foreach ($section->content_source_items as $entity)
                                @include('partials.home.section-entity-card', [
                                    'entity' => $entity,
                                    'sourceType' => $section->content_source_type,
                                    'layoutStyle' => $layoutStyle,
                                ])
                            @endforeach
                        @else
                            @foreach ($section->items as $item)
                                @if ($item->item_type === 'video' && !$item->youtube_url && $item->video_url)
                                    @php
                                        $itemContent = $item->content ?? [];
                                        $itemSettings = $item->settings ?? [];
                                        $videoMediaSize = $itemSettings['media_size'] ?? 'full';
                                        $videoSizeClass = match ($videoMediaSize) {
                                            'small' => 'max-w-sm',
                                            'medium' => 'max-w-xl',
                                            'large' => 'max-w-4xl',
                                            default => 'w-full',
                                        };
                                        $videoWrapperStyle = '';
                                        if (!empty($itemSettings['media_max_width'])) {
                                            $videoWrapperStyle .= 'max-width:' . (int) $itemSettings['media_max_width'] . 'px;';
                                        }
                                        $videoMaxHeight = !empty($itemSettings['media_max_height'])
                                            ? (int) $itemSettings['media_max_height'] . 'px'
                                            : '500px';
                                    @endphp
                                    <div class="{{ $videoSizeClass }} mx-auto"
                                        @if ($videoWrapperStyle) style="{{ $videoWrapperStyle }}" @endif>
                                        @if (isset($itemContent['title']) && $itemContent['title'])
                                            <h4 class="font-bold text-lg mb-3 text-right">{{ $itemContent['title'] }}</h4>
                                        @endif
                                        <video controls preload="metadata" playsinline
                                            class="w-full rounded-2xl shadow-lg" style="max-height: {{ $videoMaxHeight }};"
                                            @if (!empty($itemSettings['autoplay'])) autoplay muted @endif>
                                            <source src="{{ $item->video_url }}#t=0.001" type="video/mp4">
                                        </video>
                                    </div>
                                @else
                                    @include('partials.home.section-item', ['item' => $item])
                                @endif
                            @endforeach
                        @endif
                    </div>

                </div>
            </section>
        @endif
    @endforeach
@endif
