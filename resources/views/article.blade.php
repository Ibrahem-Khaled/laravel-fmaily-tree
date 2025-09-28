<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - معرض صور العائلة</title>

    {{-- قم بتضمين ملف Tailwind CSS الخاص بمشروعك --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- إضافة إعدادات Tailwind المخصصة (مهم جدًا) --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Tajawal', 'sans-serif'],
                        'serif': ['Amiri', 'serif'],
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px) rotate(0deg)'
                            },
                            '50%': {
                                transform: 'translateY(-20px) rotate(5deg)'
                            },
                        },
                        'pulse-soft': {
                            '0%, 100%': {
                                opacity: '0.3'
                            },
                            '50%': {
                                opacity: '0.6'
                            },
                        },
                        fadeIn: {
                            'from': {
                                opacity: '0',
                                transform: 'scale(0.95)'
                            },
                            'to': {
                                opacity: '1',
                                transform: 'scale(1)'
                            },
                        }
                    },
                    animation: {
                        'float': 'float 8s ease-in-out infinite',
                        'pulse-soft': 'pulse-soft 4s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.3s ease-out',
                    },
                    boxShadow: {
                        'green-glow': '0 0 40px rgba(34, 197, 94, 0.3)',
                    }
                }
            }
        }
    </script>

    {{-- استيراد خطوط جميلة من Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
        /*
            أكواد CSS المتبقية هي التي يصعب تحقيقها بـ Tailwind مباشرة
            أو تحتاج plugins، مثل شريط التمرير (scrollbar).
        */
        body {
            font-family: 'Tajawal', sans-serif;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Amiri', serif;
        }

        /* شريط التمرير المخصص */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f0fdf4;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e, #16a34a);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #16a34a, #15803d);
        }

        /* لعمل تأثير الـ Lightbox */
        .lightbox {
            display: none;
        }

        .lightbox.active {
            display: flex;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-50 to-emerald-50 text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    <div id="readingProgress"
        class="fixed top-0 right-0 h-1 bg-gradient-to-r from-green-500 to-emerald-400 z-50 transition-all duration-300">
    </div>

    <div class="fixed top-10 left-10 w-96 h-96 opacity-5 z-0 pointer-events-none animate-float hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#22c55e"
                d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3C52.5,-73.2,69.7,-61.8,44.9,-76.6Z"
                transform="translate(100 100)" />
        </svg>
    </div>
    <div
        class="fixed bottom-10 right-10 w-96 h-96 opacity-5 z-0 pointer-events-none animate-pulse-soft hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#4ade80"
                d="M37.5,-65.2C48.7,-57.8,57.8,-47.3,64.3,-35.1C70.8,-22.9,74.7,-9,75.6,5.7C76.5,20.4,74.4,36,67.1,48.6C59.8,61.2,47.3,70.8,33.2,75.7C19.1,80.6,3.4,80.8,-12.1,78.1C-27.6,75.4,-42.9,69.8,-55.3,60.2C-67.7,50.6,-77.2,37,-80.3,21.9C-83.4,6.8,-80.1,-9.8,-74.1,-25.3C-68.1,-40.8,-59.4,-55.2,-47.2,-62.2C-35,-69.2,-19.3,-68.8,-5.4,-60.5C8.5,-52.2,26.3,-72.6,37.5,-65.2Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <main class="container mx-auto px-4 py-8 lg:py-12 relative z-10 max-w-6xl">
        <div class="mb-6">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white/60 backdrop-blur-md border border-white/30 rounded-full hover:bg-white/90 transition-all duration-300 group shadow-sm hover:shadow-lg">
                <svg class="w-5 h-5 text-green-600 transition-transform group-hover:-translate-x-1" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium text-gray-700">العودة للخلف</span>
            </a>
        </div>

        <article
            class="bg-white/80 backdrop-blur-md border border-white/30 rounded-3xl overflow-hidden shadow-green-glow">

            <header class="p-6 lg:p-10 bg-gradient-to-br from-green-50/50 to-emerald-50/20">
                <h1
                    class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 leading-relaxed font-serif bg-gradient-to-r from-green-600 to-emerald-500 text-transparent bg-clip-text">
                    {{ $article->title }}
                </h1>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm lg:text-base text-gray-600">
                    @if ($article->person)
                        <div class="flex items-center gap-2">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-lg font-serif">
                                {{ mb_substr($article->person->full_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $article->person->full_name ?? 'غير معروف' }}</p>
                                <p class="text-xs text-gray-500">المساهم</p>
                            </div>
                        </div>
                    @endif
                    @if ($article->category)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" />
                            </svg>
                            <span
                                class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">{{ $article->category->name }}</span>
                        </div>
                    @endif
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-500">{{ $article->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </header>

            @if ($article->content)
                <div class="p-6 lg:p-10">
                    {{-- استخدام Tailwind Typography plugin لتنسيق النص --}}
                    <div class="prose prose-lg max-w-none prose-p:leading-relaxed prose-headings:text-green-700">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                </div>
            @endif

            @if ($article->videos && $article->videos->isNotEmpty())
                <div class="p-6 pt-0 lg:p-10 lg:pt-0">
                    <div class="border-t border-green-200/60 pt-8">
                        <h2
                            class="text-2xl lg:text-3xl font-bold font-serif mb-6 bg-gradient-to-r from-green-600 to-emerald-500 text-transparent bg-clip-text flex items-center gap-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m-6 2a9 9 0 110-12 9 9 0 010 12z"></path>
                            </svg>
                            مقاطع الفيديو
                            <span class="text-sm font-sans font-normal text-gray-500">({{ $article->videos->count() }}
                                فيديو)</span>
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($article->videos as $video)
                                @php
                                    $embedSrc = null;
                                    if ($video->provider === 'youtube') {
                                        $embedSrc = 'https://www.youtube.com/embed/' . $video->video_id;
                                    }
                                @endphp
                                @if ($embedSrc)
                                    <div class="aspect-video rounded-xl overflow-hidden shadow-md bg-black">
                                        <iframe class="w-full h-full" src="{{ $embedSrc }}" title="YouTube video player"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if ($article->images->isNotEmpty())
                <div class="p-6 pt-0 lg:p-10 lg:pt-0">
                    <div class="border-t border-green-200/60 pt-8">
                        <h2
                            class="text-2xl lg:text-3xl font-bold font-serif mb-6 bg-gradient-to-r from-green-600 to-emerald-500 text-transparent bg-clip-text flex items-center gap-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            معرض الصور
                            <span class="text-sm font-sans font-normal text-gray-500">({{ $article->images->count() }}
                                صورة)</span>
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($article->images as $index => $image)
                                <div class="group relative aspect-square overflow-hidden rounded-xl cursor-pointer shadow-md hover:shadow-xl transition-all duration-300"
                                    onclick="openLightbox({{ $index }})">
                                    <img src="{{ asset('storage/' . $image->path) }}"
                                        alt="{{ $image->name ?? 'صورة من المقال' }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors">
                                    </div>
                                    @if ($image->name)
                                        <p
                                            class="absolute bottom-0 right-0 left-0 p-2 text-white text-xs text-center bg-gradient-to-t from-black/70 to-transparent">
                                            {{ $image->name }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if ($article->attachments->isNotEmpty())
                <div class="p-6 pt-0 lg:p-10 lg:pt-0">
                    <div class="border-t border-green-200/60 pt-8">
                        <h2
                            class="text-2xl lg:text-3xl font-bold font-serif mb-6 bg-gradient-to-r from-green-600 to-emerald-500 text-transparent bg-clip-text flex items-center gap-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                </path>
                            </svg>
                            المرفقات
                            <span
                                class="text-sm font-sans font-normal text-gray-500">({{ $article->attachments->count() }}
                                ملف)</span>
                        </h2>
                        <div class="space-y-3">
                            @foreach ($article->attachments as $attachment)
                                @php
                                    $extension = pathinfo($attachment->path, PATHINFO_EXTENSION);
                                    $icon = match (strtolower($extension)) {
                                        'pdf'
                                            => '<svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>',
                                        'doc',
                                        'docx'
                                            => '<svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>',
                                        'zip',
                                        'rar'
                                            => '<svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>',
                                        default
                                            => '<svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>',
                                    };
                                @endphp
                                <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank"
                                    class="flex items-center gap-4 p-3 bg-gray-50/50 hover:bg-green-50 rounded-lg border border-gray-200/80 hover:border-green-300 transition-all duration-300 group">
                                    <div class="flex-shrink-0">
                                        {!! $icon !!}
                                    </div>
                                    <div class="flex-grow">
                                        <p class="font-medium text-gray-800 group-hover:text-green-700">
                                            {{ $attachment->name ?? basename($attachment->path) }}</p>
                                        <p class="text-xs text-gray-500">{{ strtoupper($extension) }} -
                                            {{ number_format(Storage::size('public/' . $attachment->path) / 1024, 1) }}
                                            KB</p>
                                    </div>
                                    <div
                                        class="flex-shrink-0 text-gray-400 group-hover:text-green-600 transition-transform duration-300 group-hover:translate-x-[-4px]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                            </path>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <footer class="p-6 lg:p-10 bg-gray-50/20 border-t border-green-200/60">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-500">تم نشر هذا المقال في {{ $article->created_at->diffForHumans() }}.
                    </p>
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg shadow-green-500/20 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        <span>العودة</span>
                    </a>
                </div>
            </footer>
        </article>

        @if (isset($relatedArticles) && $relatedArticles->count() > 0)
            <section class="mt-12">
                <h3
                    class="text-2xl lg:text-3xl font-bold font-serif mb-6 text-center bg-gradient-to-r from-green-600 to-emerald-500 text-transparent bg-clip-text">
                    مقالات ذات صلة</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($relatedArticles->take(3) as $related)
                        <a href="{{ url('/article/' . $related->id) }}"
                            class="group bg-white/60 backdrop-blur-md border border-white/40 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 block">
                            <div class="aspect-video overflow-hidden bg-gradient-to-br from-green-100 to-green-200">
                                @php
                                    $relatedImage = $related->images->first();
                                    $fallbackAvatar = $related->person?->avatar;
                                @endphp
                                @if ($relatedImage)
                                    <img src="{{ asset('storage/' . $relatedImage->path) }}"
                                        alt="{{ $related->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <img src="{{ $fallbackAvatar }}"
                                        alt="{{ $related->person?->full_name ?? $related->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @endif
                            </div>
                            <div class="p-4">
                                <h4
                                    class="font-bold font-serif text-lg text-gray-800 mb-2 line-clamp-2 group-hover:text-green-700 transition-colors">
                                    {{ $related->title }}</h4>
                                @if ($related->person)
                                    <p class="text-sm text-gray-600 flex items-center gap-2"><svg class="w-4 h-4"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>{{ $related->person->full_name }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    <div id="lightbox"
        class="lightbox fixed inset-0 bg-black/90 z-[9999] items-center justify-center animate-fade-in">
        <div class="relative max-w-[90vw] max-h-[90vh]">
            <img id="lightboxImage" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl"
                alt="الصور">
            <button onclick="prevImage()" aria-label="السابق"
                class="absolute top-1/2 -translate-y-1/2 -left-4 sm:left-[-60px] bg-white/80 hover:bg-white text-gray-800 w-12 h-12 rounded-full flex items-center justify-center text-2xl transition-transform hover:scale-110">&#8249;</button>
            <button onclick="nextImage()" aria-label="التالي"
                class="absolute top-1/2 -translate-y-1/2 -right-4 sm:right-[-60px] bg-white/80 hover:bg-white text-gray-800 w-12 h-12 rounded-full flex items-center justify-center text-2xl transition-transform hover:scale-110">&#8250;</button>
            <button onclick="closeLightbox()" aria-label="إغلاق"
                class="absolute -top-4 -right-4 sm:top-4 sm:right-4 bg-white/80 hover:bg-white text-gray-800 w-12 h-12 rounded-full flex items-center justify-center text-xl transition-transform hover:rotate-90">✕</button>
            <div id="imageCounter"
                class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/50 text-white px-4 py-1 rounded-full text-sm">
            </div>
        </div>
    </div>

    <script>
        // شريط التقدم للقراءة
        const progress = document.getElementById('readingProgress');
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            progress.style.width = docHeight > 0 ? `${(scrollTop / docHeight) * 100}%` : '0%';
        });

        // Lightbox
        const images = @json($article->images->pluck('path')->map(fn($p) => asset('storage/' . $p)));
        let currentIndex = 0;
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightboxImage');
        const imageCounter = document.getElementById('imageCounter');

        function openLightbox(index) {
            if (!images.length) return;
            currentIndex = index;
            updateLightbox();
            lightbox.classList.add('active');
            // لإغلاق الـ Lightbox عند الضغط على مفتاح Esc
            document.addEventListener('keydown', handleEsc);
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.removeEventListener('keydown', handleEsc);
        }

        function handleEsc(e) {
            if (e.key === 'Escape') closeLightbox();
        }

        const changeImage = (step) => {
            currentIndex = (currentIndex + step + images.length) % images.length;
            lightboxImg.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                updateLightbox();
                lightboxImg.classList.remove('opacity-0', 'scale-95');
            }, 150);
        };
        const prevImage = () => changeImage(-1);
        const nextImage = () => changeImage(1);

        function updateLightbox() {
            lightboxImg.src = images[currentIndex];
            lightboxImg.style.transition = 'transform 0.15s ease, opacity 0.15s ease';
            imageCounter.textContent = `${currentIndex + 1} / ${images.length}`;
        }
    </script>
</body>

</html>
