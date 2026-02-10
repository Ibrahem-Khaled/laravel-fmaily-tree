@php
    $typeConfig = [
        'rich_text' => ['name' => 'نص غني', 'icon' => 'fa-align-right', 'badge' => 'primary'],
        'text' => ['name' => 'نص', 'icon' => 'fa-font', 'badge' => 'secondary'],
        'image' => ['name' => 'صورة', 'icon' => 'fa-image', 'badge' => 'success'],
        'video' => ['name' => 'فيديو', 'icon' => 'fa-play-circle', 'badge' => 'danger'],
        'table' => ['name' => 'جدول', 'icon' => 'fa-table', 'badge' => 'warning'],
        'card' => ['name' => 'بطاقة', 'icon' => 'fa-id-card', 'badge' => 'info'],
        'button' => ['name' => 'زر', 'icon' => 'fa-mouse-pointer', 'badge' => 'dark'],
        'html' => ['name' => 'HTML', 'icon' => 'fa-code', 'badge' => 'dark'],
        'spacer' => ['name' => 'فاصل', 'icon' => 'fa-arrows-alt-v', 'badge' => 'secondary'],
    ];
    $config = $typeConfig[$item->item_type] ?? ['name' => $item->item_type, 'icon' => 'fa-cube', 'badge' => 'secondary'];
    $content = $item->content ?? [];
    $settings = $item->settings ?? [];
@endphp

<div class="item-card" data-id="{{ $item->id }}" data-type="{{ $item->item_type }}">
    <!-- Item Header -->
    <div class="item-header" onclick="toggleItem(this)">
        <div class="item-header-left">
            <i class="fas fa-grip-vertical item-drag-handle" onclick="event.stopPropagation()"></i>
            <span class="item-type-badge badge badge-{{ $config['badge'] }}">
                <i class="fas {{ $config['icon'] }} mr-1"></i>{{ $config['name'] }}
            </span>
            <span class="text-muted small">
                @if($item->item_type === 'rich_text' && isset($content['html']))
                    {{ Str::limit(strip_tags($content['html']), 50) }}
                @elseif($item->item_type === 'text' && isset($content['text']))
                    {{ Str::limit($content['text'], 50) }}
                @elseif($item->item_type === 'image')
                    {{ $content['alt'] ?? ($content['caption'] ?? 'صورة') }}
                @elseif($item->item_type === 'video')
                    {{ $content['title'] ?? ($item->youtube_url ? 'فيديو يوتيوب' : 'فيديو') }}
                @elseif($item->item_type === 'table')
                    {{ $content['title'] ?? 'جدول بيانات' }}
                @elseif($item->item_type === 'card')
                    {{ $content['title'] ?? 'بطاقة' }}
                @elseif($item->item_type === 'button')
                    {{ $content['text'] ?? 'زر' }}
                @elseif($item->item_type === 'spacer')
                    {{ ($settings['height'] ?? 40) }}px
                @else
                    عنصر #{{ $item->display_order }}
                @endif
            </span>
        </div>
        <div class="item-header-actions" onclick="event.stopPropagation()">
            <button type="button" class="btn btn-sm btn-outline-info" title="تعديل"
                    onclick="editItem({{ $item->id }}, '{{ $item->item_type }}', {!! json_encode($content, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG) !!}, {!! json_encode($settings, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG) !!}, '{{ addslashes($item->image_url ?? '') }}', '{{ addslashes($item->youtube_url ?? '') }}')">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" title="حذف"
                    onclick="deleteItem({{ $item->id }})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    <!-- Item Body (Preview) -->
    <div class="item-body">
        <div class="item-preview-content">
            @switch($item->item_type)
                @case('rich_text')
                    <div style="direction: rtl; max-height: 200px; overflow: hidden; position: relative;">
                        {!! $content['html'] ?? '<span class="text-muted">لا يوجد محتوى</span>' !!}
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 40px; background: linear-gradient(transparent, white);"></div>
                    </div>
                    @break
                    
                @case('text')
                    <p class="text-muted mb-0">{{ Str::limit($content['text'] ?? '', 200) }}</p>
                    @break
                    
                @case('image')
                    @if($item->image_url)
                        <div class="text-center">
                            <img src="{{ $item->image_url }}" alt="{{ $content['alt'] ?? '' }}" 
                                 style="max-height: 180px; max-width: 100%; border-radius: 10px; object-fit: cover;">
                        </div>
                        @if(isset($content['caption']) && $content['caption'])
                            <p class="text-center text-muted small mt-2">{{ $content['caption'] }}</p>
                        @endif
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-image fa-2x mb-2" style="opacity: 0.3;"></i>
                            <p class="small">لم يتم رفع صورة</p>
                        </div>
                    @endif
                    @break
                    
                @case('video')
                    @if($item->youtube_url)
                        @php
                            $videoId = '';
                            if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w-]+)/', $item->youtube_url, $m)) {
                                $videoId = $m[1];
                            }
                        @endphp
                        @if($videoId)
                            <div class="text-center">
                                <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg" 
                                     alt="{{ $content['title'] ?? 'فيديو' }}"
                                     style="max-width: 100%; border-radius: 10px;">
                                <div class="mt-2">
                                    <span class="badge badge-danger"><i class="fab fa-youtube mr-1"></i>يوتيوب</span>
                                </div>
                            </div>
                        @endif
                    @elseif($item->image_url)
                        <div class="text-center">
                            <span class="badge badge-dark"><i class="fas fa-video mr-1"></i>فيديو مرفوع</span>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-play-circle fa-2x" style="opacity: 0.3;"></i>
                        </div>
                    @endif
                    @break
                    
                @case('table')
                    @if(isset($content['table_data']) && is_array($content['table_data']))
                        <div style="overflow-x: auto;">
                            <table class="table table-sm table-bordered mb-0" style="font-size: 0.8rem;">
                                @foreach($content['table_data'] as $ri => $row)
                                    <tr>
                                        @foreach($row as $cell)
                                            @if($ri === 0)
                                                <th style="background: #f0f2f8; padding: 6px 8px;">{{ $cell }}</th>
                                            @else
                                                <td style="padding: 6px 8px;">{{ $cell }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @else
                        <span class="text-muted">جدول فارغ</span>
                    @endif
                    @break
                    
                @case('card')
                    <div class="d-flex align-items-start" style="gap: 16px;">
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $content['title'] ?? '' }}" 
                                 style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px;">
                        @endif
                        <div>
                            <strong>{{ $content['title'] ?? 'بطاقة بدون عنوان' }}</strong>
                            @if(isset($content['description']))
                                <p class="text-muted small mb-0">{{ Str::limit($content['description'], 100) }}</p>
                            @endif
                        </div>
                    </div>
                    @break
                    
                @case('button')
                    <div>
                        <a href="javascript:void(0)" class="btn btn-{{ $settings['btn_color'] ?? 'primary' }} btn-{{ $settings['btn_size'] ?? 'md' }} {{ ($settings['btn_block'] ?? false) ? 'btn-block' : '' }}">
                            @if(isset($content['icon']) && $content['icon'])
                                <i class="{{ $content['icon'] }} mr-1"></i>
                            @endif
                            {{ $content['text'] ?? 'زر' }}
                        </a>
                        @if(isset($content['url']) && $content['url'])
                            <small class="text-muted d-block mt-1"><i class="fas fa-link mr-1"></i>{{ Str::limit($content['url'], 40) }}</small>
                        @endif
                    </div>
                    @break
                    
                @case('html')
                    <div style="max-height: 120px; overflow: hidden; position: relative;">
                        <pre style="font-size: 0.75rem; background: #f8f9fc; padding: 10px; border-radius: 8px; margin: 0; direction: ltr; text-align: left;"><code>{{ Str::limit($content['html'] ?? '', 300) }}</code></pre>
                    </div>
                    @break
                    
                @case('spacer')
                    <div class="text-center py-2">
                        <div style="height: {{ min(($settings['height'] ?? 40), 60) }}px; display: flex; align-items: center; justify-content: center;">
                            @if($settings['show_line'] ?? false)
                                <hr style="width: 100%; margin: 0;">
                            @else
                                <span class="text-muted small">{{ ($settings['height'] ?? 40) }}px فاصل</span>
                            @endif
                        </div>
                    </div>
                    @break
                    
                @default
                    <span class="text-muted">عنصر {{ $item->item_type }}</span>
            @endswitch
        </div>
    </div>
</div>
