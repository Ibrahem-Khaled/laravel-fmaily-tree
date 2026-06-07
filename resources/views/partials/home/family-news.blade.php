{{-- ================================================================
FAMILY NEWS – Magazine-style redesign
================================================================ --}}
@if ($familyNews && $familyNews->count() > 0)
    <style>
        .news-card-accent { position: relative; }
        .news-card-accent::before {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0;
            width: 4px;
            border-radius: 0 8px 8px 0;
            background: linear-gradient(180deg, #22c55e 0%, #059669 50%, #0d9488 100%);
            transition: width 0.3s ease;
        }
        .news-card-accent:hover::before { width: 6px; }

        .news-hero-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 30%, #ffffff 100%);
            border: 1px solid rgba(34,197,94,0.15);
        }
        .news-hero-card:hover {
            border-color: rgba(34,197,94,0.35);
            box-shadow: 0 20px 60px -12px rgba(34,197,94,0.15);
        }

        .news-item-card {
            background: #fff;
            border: 1px solid #f3f4f6;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .news-item-card:hover {
            border-color: rgba(34,197,94,0.25);
            transform: translateY(-2px);
            box-shadow: 0 12px 40px -8px rgba(0,0,0,0.1);
        }

        .news-date-badge {
            background: linear-gradient(135deg, #22c55e, #059669);
            color: white;
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
            white-space: nowrap;
        }

        .news-icon-float {
            position: absolute;
            opacity: 0.04;
            font-size: 80px;
            top: -10px;
            left: -10px;
            transform: rotate(-15deg);
            transition: all 0.5s ease;
            pointer-events: none;
        }
        .news-item-card:hover .news-icon-float,
        .news-hero-card:hover .news-icon-float {
            opacity: 0.08;
            transform: rotate(-5deg) scale(1.1);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .news-animate { animation: fadeInUp 0.5s ease forwards; opacity: 0; }

        .news-views-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            color: #6b7280;
        }
    </style>

    <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-b from-white via-gray-50/50 to-white relative overflow-hidden">
        {{-- Decorative background blobs --}}
        <div class="absolute top-0 right-0 w-72 h-72 bg-green-100 rounded-full blur-3xl opacity-15"></div>
        <div class="absolute bottom-0 left-0 w-56 h-56 bg-emerald-100 rounded-full blur-3xl opacity-10"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

            {{-- Section Header --}}
            <div class="text-right mb-4 md:mb-6">
                <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200 rounded-full px-3 py-1 mb-2">
                    <span class="text-green-700 text-[10px] md:text-xs font-semibold">آخر الأخبار</span>
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                </div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-1">
                    أخبار العائلة
                </h2>
                <p class="text-gray-500 text-xs md:text-sm">تابع آخر أخبار وأحداث ومستجدات العائلة</p>
            </div>

            @php
                $firstNews = $familyNews->first();
                $restNews = $familyNews->skip(1);
                $accentColors = ['#22c55e','#0ea5e9','#8b5cf6','#f59e0b','#ef4444','#ec4899','#14b8a6','#6366f1'];
                $newsIcons = ['fa-bullhorn','fa-star','fa-bell','fa-bolt','fa-heart','fa-gem','fa-fire','fa-rocket'];
            @endphp

            {{-- Hero / Featured News Card --}}
            <a href="{{ route('family-news.show', $firstNews->id) }}"
               class="group block news-hero-card rounded-2xl overflow-hidden mb-4 md:mb-5 transition-all duration-500 news-animate {{ !$firstNews->is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}"
               style="animation-delay: 0.05s;">
                <div class="flex flex-col md:flex-row-reverse">
                    {{-- Image or decorative panel --}}
                    @if ($firstNews->main_image_url)
                        <div class="relative w-full md:w-2/5 h-44 md:h-auto md:min-h-[220px] overflow-hidden flex-shrink-0">
                            <img src="{{ $firstNews->main_image_url }}" alt="{{ $firstNews->title }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-l from-transparent to-black/10 md:bg-gradient-to-r"></div>
                            @if ($firstNews->images->count() > 0)
                                <div class="absolute top-2 left-2 z-10">
                                    <span class="bg-black/40 text-white text-[9px] px-2 py-1 rounded-full font-bold backdrop-blur-sm">
                                        <i class="fas fa-images mr-1"></i>{{ $firstNews->images->count() }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="hidden md:flex w-2/5 items-center justify-center relative overflow-hidden" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                            <i class="fas fa-newspaper text-green-300 text-7xl opacity-30 group-hover:opacity-50 transition-opacity duration-500"></i>
                            <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-green-200 rounded-full blur-2xl opacity-40"></div>
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="flex-1 p-4 md:p-6 flex flex-col justify-center relative">
                        <i class="fas fa-bullhorn news-icon-float"></i>

                        @if (!$firstNews->is_active && Auth::check())
                            <span class="absolute top-3 left-3 bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                        @endif

                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            @if ($firstNews->published_at)
                                <span class="news-date-badge">
                                    <i class="fas fa-calendar-alt ml-1"></i>
                                    {{ $firstNews->published_at->format('Y/m/d') }}
                                </span>
                            @endif
                            <span class="news-views-badge">
                                <i class="fas fa-eye"></i>
                                {{ $firstNews->views_count }}
                            </span>
                        </div>

                        <h3 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors duration-300 leading-snug">
                            {{ $firstNews->title }}
                        </h3>

                        <p class="text-gray-600 text-xs md:text-sm mb-3 line-clamp-3 leading-relaxed">
                            {{ $firstNews->summary ?? Str::limit(strip_tags($firstNews->content), 200) }}
                        </p>

                        <div class="flex items-center gap-2 text-green-600 text-xs md:text-sm font-semibold">
                            <span class="group-hover:underline">اقرأ التفاصيل</span>
                            <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform duration-300"></i>
                        </div>
                    </div>
                </div>
            </a>

            {{-- Remaining News Grid --}}
            @if ($restNews->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                    @foreach ($restNews as $idx => $news)
                        @php
                            $colorIdx = $idx % count($accentColors);
                            $accent = $accentColors[$colorIdx];
                            $icon = $newsIcons[$colorIdx];
                        @endphp
                        <a href="{{ route('family-news.show', $news->id) }}"
                           class="group block news-item-card news-card-accent rounded-xl overflow-hidden news-animate {{ !$news->is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}"
                           style="animation-delay: {{ 0.1 + ($idx * 0.07) }}s;">
                           <div class="relative">
                                {{-- Decorative floating icon --}}
                                <i class="fas {{ $icon }} news-icon-float" style="color: {{ $accent }};"></i>

                                {{-- Colored accent bar override per card --}}
                                <style>.news-item-card:nth-child({{ $idx + 1 }})::before { background: linear-gradient(180deg, {{ $accent }}, {{ $accent }}88); }</style>

                                @if ($news->main_image_url)
                                    <div class="relative h-32 md:h-36 overflow-hidden">
                                        <img src="{{ $news->main_image_url }}" alt="{{ $news->title }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                                        @if ($news->images->count() > 0)
                                            <div class="absolute top-2 left-2">
                                                <span class="bg-black/40 text-white text-[8px] px-2 py-0.5 rounded-full backdrop-blur-sm">
                                                    <i class="fas fa-images mr-0.5"></i>{{ $news->images->count() }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="p-3 md:p-4">
                                    @if (!$news->is_active && Auth::check())
                                        <span class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold mb-1 inline-block">معطل</span>
                                    @endif

                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                        @if ($news->published_at)
                                            <span class="news-date-badge" style="background: linear-gradient(135deg, {{ $accent }}, {{ $accent }}cc);">
                                                {{ $news->published_at->format('Y/m/d') }}
                                            </span>
                                        @endif
                                        <span class="news-views-badge">
                                            <i class="fas fa-eye"></i>
                                            {{ $news->views_count }}
                                        </span>
                                    </div>

                                    <h3 class="text-sm md:text-base font-bold text-gray-800 mb-1.5 line-clamp-2 group-hover:text-green-600 transition-colors duration-300 leading-snug">
                                        {{ $news->title }}
                                    </h3>

                                    <p class="text-gray-500 text-[11px] md:text-xs mb-2 line-clamp-2 leading-relaxed">
                                        {{ $news->summary ?? Str::limit(strip_tags($news->content), 100) }}
                                    </p>

                                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                        <span class="text-xs font-semibold group-hover:underline transition-colors duration-300" style="color: {{ $accent }};">
                                            اقرأ المزيد
                                            <i class="fas fa-arrow-left text-[9px] mr-0.5 group-hover:-translate-x-1 inline-block transition-transform duration-300"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </section>
@endif
