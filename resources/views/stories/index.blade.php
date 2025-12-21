<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قصص {{ $person->full_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Tajawal',sans-serif;background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 50%,#bbf7d0 100%);min-height:100vh;text-rendering:optimizeLegibility;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;font-feature-settings:"liga" 1,"kern" 1}
        *{font-feature-settings:"liga" 1,"kern" 1;text-rendering:optimizeLegibility}
        body,body *{unicode-bidi:embed;direction:rtl}
        [dir="rtl"]{unicode-bidi:embed}
        h1,h2,h3{font-family:'Amiri',serif}
        .glass{background:rgba(255,255,255,.85);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.3)}
        .gradient-text{background:linear-gradient(135deg,#16a34a 0%,#22c55e 50%,#4ade80 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}

        .story-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .story-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #16a34a, #22c55e, #4ade80);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.4s ease;
        }

        .story-card:hover::before {
            transform: scaleX(1);
        }

        .story-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(34, 197, 94, 0.2);
        }

        .story-title {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.4;
        }

        .narrator-badge {
            transition: all 0.3s ease;
        }

        .narrator-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .story-content {
            line-height: 1.8;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            text-rendering: optimizeLegibility;
            font-feature-settings: "liga" 1, "kern" 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .read-more-btn {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            position: relative;
            overflow: hidden;
        }

        .read-more-btn::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #22c55e 0%, #4ade80 100%);
            transition: right 0.4s ease;
        }

        .read-more-btn:hover::before {
            right: 0;
        }

        .read-more-btn span {
            position: relative;
            z-index: 1;
        }

        @media (max-width: 640px) {
            .stories-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.75rem;
            }

            .story-card {
                padding: 1rem;
            }

            .story-title {
                font-size: 0.95rem;
                margin-bottom: 0.5rem;
            }

            .story-content {
                font-size: 0.8rem;
                margin-bottom: 0.75rem;
            }

            .read-more-btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
            }
        }
    </style>
    @vite(["resources/css/app.css","resources/js/app.js"])
    @stack('head')
    @stack('styles')
</head>
<body class="text-gray-800">
    @include('partials.main-header')

    <main class="container mx-auto px-4 py-8">
        <div class="mb-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold gradient-text">قصص {{ $person->full_name }}</h1>
            {{-- <p class="text-gray-600 mt-2"> أحداث وروايات مرتبطة بـ {{ $person->full_name }}</p> --}}
        </div>

        @if($stories->count() === 0)
            <div class="glass rounded-2xl p-6 text-center text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <p class="text-lg">لا توجد قصص مسجلة لهذا الشخص.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 stories-grid gap-6">
                @foreach($stories as $story)
                    <div class="glass rounded-2xl p-5 shadow-md story-card">
                        <h3 class="story-title text-xl font-bold mb-3">{{ $story->title }}</h3>
                        <div class="text-sm text-gray-500 mb-3">
                            <span class="font-semibold text-gray-600">الراوي:</span>
                            @if($story->narrators && $story->narrators->count())
                                @foreach($story->narrators as $n)
                                    <span class="inline-block bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 px-2.5 py-1 rounded-full text-xs ml-1 mb-1 narrator-badge font-medium">{{ $n->full_name }}</span>
                                @endforeach
                            @else
                                <span class="text-gray-400 italic">غير محدد</span>
                            @endif
                        </div>
                        @if($story->content)
                            <p class="story-content text-gray-700 mb-4">{{ Str::limit($story->content, 80, '...') }}</p>
                        @endif
                        <a href="{{ route('public.stories.show', $story) }}" class="read-more-btn inline-flex items-center gap-2 text-white px-4 py-2.5 rounded-lg transition-all shadow-md hover:shadow-lg">
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 10a.75.75 0 00-1.5 0v3.5a.75.75 0 001.5 0V10z"/>
                                    <path d="M10 4a2 2 0 00-2 2v1h4V6a2 2 0 00-2-2z"/>
                                    <path fill-rule="evenodd" d="M3 8a4 4 0 014-4h6a4 4 0 014 4v6a4 4 0 01-4 4H7a4 4 0 01-4-4V8zm4-2a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V8a2 2 0 00-2-2H7z" clip-rule="evenodd"/>
                                </svg>
                                <span>عرض التفاصيل</span>
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">
                {{ $stories->links() }}
            </div>
        @endif
    </main>

    @stack('scripts')
</body>
</html>


