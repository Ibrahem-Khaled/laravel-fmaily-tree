@php
    use Illuminate\Support\Str;
    $galleryPaths = $galleryMedia->map(fn($media) => asset('storage/' . $media->path))->values();
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if($program->is_proud_of){{ $program->proud_of_title ?? ($program->name ?? 'عنصر') }}@else{{ $program->program_title ?? ($program->name ?? 'برنامج') }}@endif - منصة برامج عائلة السريع</title>

    <script src="https://cdn.tailwindcss.com"></script>
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
                                transform: 'translateY(0) rotate(0deg)'
                            },
                            '50%': {
                                transform: 'translateY(-18px) rotate(3deg)'
                            },
                        },
                        'pulse-soft': {
                            '0%, 100%': {
                                opacity: '0.25'
                            },
                            '50%': {
                                opacity: '0.55'
                            },
                        },
                        fadeIn: {
                            from: {
                                opacity: '0',
                                transform: 'scale(0.97)'
                            },
                            to: {
                                opacity: '1',
                                transform: 'scale(1)'
                            },
                        },
                        slideUp: {
                            from: {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            to: {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                        shimmer: {
                            '0%': {
                                backgroundPosition: '-1000px 0'
                            },
                            '100%': {
                                backgroundPosition: '1000px 0'
                            },
                        },
                    },
                    animation: {
                        'float': 'float 9s ease-in-out infinite',
                        'pulse-soft': 'pulse-soft 6s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'shimmer': 'shimmer 2s linear infinite',
                    },
                    boxShadow: {
                        'emerald-glow': '0 0 35px rgba(16, 185, 129, 0.25)',
                    }
                }
            }
        };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 45%, #f8fafc 100%);
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Amiri', serif;
            letter-spacing: -0.015em;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #ecfdf5;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #10b981, #059669);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #059669, #047857);
        }

        .lightbox {
            display: none;
        }

        .lightbox.active {
            display: flex;
        }

        .program-description {
            direction: rtl;
            text-align: right;
        }

        .program-description p {
            margin-bottom: 1rem;
        }

        .program-description strong,
        .program-description b {
            font-weight: 700;
            color: #059669;
        }

        .program-description em,
        .program-description i {
            font-style: italic;
        }

        .program-description ul,
        .program-description ol {
            margin-right: 1.5rem;
            margin-bottom: 1rem;
        }

        .program-description li {
            margin-bottom: 0.5rem;
        }

        .program-description a {
            color: #10b981;
            text-decoration: underline;
            transition: color 0.2s;
        }

        .program-description a:hover {
            color: #059669;
        }

        .program-description table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }

        .program-description table td,
        .program-description table th {
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .program-description img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }

        /* تحسينات إضافية للجداول */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        table tbody tr {
            transition: all 0.2s ease;
        }

        table tbody tr:hover {
            background-color: rgba(16, 185, 129, 0.05);
        }

        /* تأثيرات الكروت */
        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: right 0.5s;
        }

        .stat-card:hover::before {
            right: 100%;
        }

        /* تحسينات للصور */
        .image-card {
            position: relative;
            overflow: hidden;
        }

        .image-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .image-card:hover::after {
            opacity: 1;
        }
    </style>
</head>

<body class="text-gray-800 overflow-x-hidden relative">
    @include('partials.main-header')

    <div id="readingProgress"
        class="fixed top-0 right-0 h-1 bg-gradient-to-r from-emerald-400 via-green-500 to-emerald-400 z-50 transition-all duration-300">
    </div>

    <div class="fixed top-16 left-12 w-96 h-96 opacity-20 z-0 pointer-events-none animate-float hidden xl:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#34d399"
                d="M43.8,-75.6C58.8,-69.4,74.4,-59.1,79.4,-44.8C84.4,-30.4,78.8,-12.1,74.9,5.4C71,22.9,69,39.7,59.7,50.3C50.5,60.9,41.3,61.8,28.1,66.8C14.9,71.9,0.7,73,-13.5,72.2C-27.7,71.5,-41.9,68.8,-54.7,61.4C-67.5,54,-79,42,-84.6,27.8C-90.2,13.5,-90,-2.9,-83.6,-17.1C-77.1,-31.4,-64.5,-43.5,-50.9,-52.3C-37.4,-61,-22.9,-66.4,-8.4,-65.7C6.2,-65.1,12.3,-58.6,33.9,-58.2Z"
                transform="translate(100 100)" />
        </svg>
    </div>
    <div
        class="fixed bottom-16 right-16 w-96 h-96 opacity-10 z-0 pointer-events-none animate-pulse-soft hidden xl:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#22d3ee"
                d="M33.9,-58.2C46.8,-50.5,61.6,-46.1,68.9,-36.5C76.3,-26.8,76.3,-12,74.6,5.7C72.9,22.9,69.4,39.7,61.5,48.6C53.6,57.8,41.3,61.8,28.1,66.8C14.9,71.9,0.7,73,-13.5,72.2C-27.7,71.5,-41.9,68.8,-54.7,61.4C-67.5,54,-79,42,-84.6,27.8C-90.2,13.5,-90,-2.9,-83.6,-17.1C-77.1,-31.4,-64.5,-43.5,-50.9,-52.3C-37.4,-61,-22.9,-66.4,-8.4,-65.7C6.2,-65.1,12.3,-58.6,33.9,-58.2Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <main class="relative z-10 max-w-6xl mx-auto px-4 lg:px-6 py-10 lg:py-14 space-y-10 lg:space-y-12">
        <div class="mb-6">
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/70 backdrop-blur-md border border-white/40 rounded-full hover:bg-white/95 transition-all duration-300 group shadow-sm hover:shadow-lg">
                <svg class="w-5 h-5 text-emerald-600 transition-transform group-hover:-translate-x-1" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium text-gray-700">العودة للصفحة الرئيسية</span>
            </a>
        </div>

        <article
            class="bg-white/85 backdrop-blur-md border border-white/50 rounded-3xl overflow-hidden shadow-emerald-glow animate-fade-in">
            <header class="relative">
                @php
                    $coverImage = $program->cover_image_path ?? $program->path;
                @endphp
                @if ($coverImage)
                    <div class="relative h-60 sm:h-72 lg:h-80 overflow-hidden">
                        <img src="{{ asset('storage/' . $coverImage) }}" alt="{{ $program->program_title }}"
                            class="w-full h-full object-cover">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-emerald-900/80 via-emerald-900/35 to-transparent">
                        </div>
                    </div>
                @else
                    <div class="h-40 bg-gradient-to-br from-emerald-500/20 to-teal-500/20"></div>
                @endif

                <div class="p-6 lg:p-10 -mt-24 relative z-10">
                    <div class="bg-white/95 backdrop-blur-md rounded-3xl p-6 lg:p-8 border border-white/60 shadow-2xl relative overflow-hidden">
                        {{-- خلفية زخرفية --}}
                        <div class="absolute top-0 left-0 w-64 h-64 bg-gradient-to-br from-emerald-100/30 to-teal-100/30 rounded-full blur-3xl -z-0"></div>
                        <div class="absolute bottom-0 right-0 w-64 h-64 bg-gradient-to-tl from-emerald-100/30 to-teal-100/30 rounded-full blur-3xl -z-0"></div>

                        <div class="relative z-10 flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                            <div class="flex-1">
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-full text-xs font-bold mb-4 shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                    @if($program->is_proud_of)
                                        نفتخر بهم
                                    @else
                                        برنامج
                                    @endif
                                </div>
                                <h1
                                    class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 leading-relaxed font-serif bg-gradient-to-r from-emerald-600 via-teal-500 to-emerald-600 text-transparent bg-clip-text">
                                    @if($program->is_proud_of)
                                        {{ $program->proud_of_title ?? ($program->name ?? 'عنصر') }}
                                    @else
                                        {{ $program->program_title ?? ($program->name ?? 'برنامج') }}
                                    @endif
                                </h1>
                                @if ($program->is_proud_of && $program->proud_of_description)
                                    <div class="text-gray-600 leading-relaxed text-lg program-description">
                                        {!! $program->proud_of_description !!}
                                    </div>
                                @elseif($program->program_description)
                                    <div class="text-gray-600 leading-relaxed text-lg program-description">
                                        {!! $program->program_description !!}
                                    </div>
                                @endif

                                {{-- معلومات سريعة محسّنة --}}
                                <div class="mt-6 flex flex-wrap items-center gap-4 text-sm">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium">{{ $program->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium">{{ $program->updated_at->diffForHumans() }}</span>
                            </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6 lg:p-10 space-y-14">
                {{-- البرامج الفرعية --}}
                @if ($subPrograms->isNotEmpty())
                    <section class="relative">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-1 h-12 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full"></div>
                            <div>
                                <h2
                                    class="text-2xl lg:text-3xl font-bold font-serif bg-gradient-to-r from-emerald-600 to-teal-500 text-transparent bg-clip-text">
                                    البرامج الفرعية</h2>
                                <p class="text-sm text-gray-500 mt-1">انقر على أي برنامج لعرض التفاصيل</p>
                                </div>
                            </div>
                            <span
                                class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm rounded-full font-semibold shadow-lg">
                                {{ $subPrograms->count() }} برنامج
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                            @foreach ($subPrograms as $subProgram)
                                <a href="{{ route('programs.show', $subProgram) }}"
                                    class="group block bg-white/90 backdrop-blur-sm rounded-3xl overflow-hidden border border-emerald-100/50 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 sub-program-card">
                                    @php
                                        $subProgramImage = $subProgram->cover_image_path ?? $subProgram->path;
                                    @endphp
                                    @if ($subProgramImage)
                                        <div class="relative h-56 overflow-hidden">
                                            <img src="{{ asset('storage/' . $subProgramImage) }}"
                                                alt="{{ $subProgram->program_title ?? $subProgram->name }}"
                                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-125">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-900/40 to-transparent group-hover:from-emerald-900/95 transition-all duration-500">
                                            </div>
                                            <div class="absolute top-4 left-4">
                                                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-emerald-700 text-xs font-bold rounded-full shadow-lg">
                                                    برنامج فرعي
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="h-56 bg-gradient-to-br from-emerald-100 via-teal-100 to-emerald-50 flex items-center justify-center group-hover:from-emerald-200 group-hover:via-teal-200 transition-all duration-500">
                                            <div class="text-center">
                                                <svg class="w-16 h-16 text-emerald-400 mx-auto mb-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                <p class="text-emerald-600 font-semibold">برنامج فرعي</p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-emerald-600 transition-colors duration-300">
                                            {{ $subProgram->program_title ?? ($subProgram->name ?? 'برنامج') }}
                                        </h3>
                                        @if ($subProgram->program_description)
                                            <p class="text-sm text-gray-600 line-clamp-3 leading-relaxed mb-4">
                                                {{ Str::limit(strip_tags($subProgram->program_description), 120) }}
                                            </p>
                                        @endif
                                        <div class="flex items-center gap-2 text-emerald-600 text-sm font-semibold group-hover:gap-4 transition-all duration-300">
                                            <span>عرض التفاصيل</span>
                                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- معارض الصور --}}
                @if ($programGalleries->isNotEmpty())
                    @foreach ($programGalleries as $gallery)
                        <section class="relative">
                            <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-1 h-12 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full"></div>
                                <div>
                                    <h2
                                        class="text-2xl lg:text-3xl font-bold font-serif bg-gradient-to-r from-emerald-600 to-teal-500 text-transparent bg-clip-text">
                                        {{ $gallery->title }}
                                    </h2>
                                    @if ($gallery->description)
                                        <p class="text-gray-600 text-sm mt-2">{{ $gallery->description }}</p>
                                    @endif
                                    </div>
                                </div>
                                <span
                                    class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm rounded-full font-semibold shadow-lg">
                                    {{ $gallery->images->count() }} صورة
                                </span>
                            </div>
                            @if ($gallery->images->isNotEmpty())
                                @php
                                    $galleryImagePaths = $gallery->images
                                        ->map(fn($img) => asset('storage/' . $img->path))
                                        ->values();
                                    $galleryStartIndex = ($galleryMedia->count() ?? 0) + $loop->index * 1000;
                                @endphp
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
                                    @foreach ($gallery->images as $index => $image)
                                        <div
                                            class="group relative overflow-hidden rounded-2xl border border-emerald-100/50 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 bg-white/90 backdrop-blur-sm">
                                            <div class="cursor-pointer relative overflow-hidden"
                                                onclick="openLightbox({{ $galleryStartIndex + $index }})">
                                                <img src="{{ asset('storage/' . $image->path) }}"
                                                    alt="{{ $image->name ?? '' }}"
                                                    class="w-full h-48 md:h-56 object-cover transition-transform duration-700 group-hover:scale-125">
                                                <div
                                                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                                </div>
                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    <div class="w-12 h-12 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-xl">
                                                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($image->name || $image->description)
                                                <div class="p-4 bg-white">
                                                    @if ($image->name)
                                                        <h3 class="font-bold text-base line-clamp-1 text-gray-800 mb-2 group-hover:text-emerald-600 transition-colors">
                                                            {{ $image->name }}</h3>
                                                    @endif
                                                    @if ($image->description)
                                                        <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">
                                                            {{ $image->description }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                            @if (Auth::check())
                                                <div
                                                    class="absolute top-3 left-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                                    <a href="{{ route('dashboard.programs.manage', $program) }}"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white p-2.5 rounded-xl shadow-xl transition-all duration-300 hover:scale-110"
                                                        title="تعديل الصورة">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 bg-white/50 rounded-2xl">
                                    <i class="fas fa-image text-gray-400 text-4xl mb-3"></i>
                                    <p class="text-gray-600">لا توجد صور في هذا المعرض حالياً</p>
                                </div>
                            @endif
                        </section>
                    @endforeach
                @endif


                @if ($galleryMedia->isNotEmpty())
                    <section class="relative">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-1 h-12 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full"></div>
                            <div>
                                <h2
                                    class="text-2xl lg:text-3xl font-bold font-serif bg-gradient-to-r from-emerald-600 to-teal-500 text-transparent bg-clip-text">
                                    {{ $program->name ?? ($program->program_title ?? 'معرض الصور') }}</h2>
                                    <p class="text-sm text-gray-500 mt-1">مجموعة مختارة من الصور</p>
                                </div>
                            </div>
                            <span
                                class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm rounded-full font-semibold shadow-lg">
                                {{ $galleryMedia->count() }} صورة
                            </span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
                            @foreach ($galleryMedia as $index => $media)
                                <div
                                    class="group relative overflow-hidden rounded-2xl border border-emerald-100/50 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 bg-white/90 backdrop-blur-sm">
                                    <div class="cursor-pointer relative overflow-hidden" onclick="openLightbox({{ $index }})">
                                        <img src="{{ asset('storage/' . $media->path) }}"
                                            alt="{{ $media->name ?? '' }}"
                                            class="w-full h-48 md:h-56 object-cover transition-transform duration-700 group-hover:scale-125">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        </div>
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="w-12 h-12 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-xl">
                                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($media->name || $media->description)
                                        <div class="p-4 bg-white">
                                            @if ($media->name)
                                                <h3 class="font-bold text-base line-clamp-1 text-gray-800 mb-2 group-hover:text-emerald-600 transition-colors">
                                                    {{ $media->name }}</h3>
                                            @endif
                                            @if ($media->description)
                                                <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">
                                                    {{ $media->description }}</p>
                                            @endif
                                        </div>
                                    @endif
                                    @if (Auth::check())
                                        <div
                                            class="absolute top-3 left-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                            <a href="{{ route('dashboard.programs.manage', $program) }}"
                                                class="bg-blue-500 hover:bg-blue-600 text-white p-2.5 rounded-xl shadow-xl transition-all duration-300 hover:scale-110"
                                                title="تعديل الصورة">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form
                                                action="{{ route('dashboard.programs.media.destroy', [$program, $media]) }}"
                                                method="POST"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white p-2.5 rounded-xl shadow-xl transition-all duration-300 hover:scale-110"
                                                    title="حذف الصورة">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($videoMedia->isNotEmpty())
                    <section class="relative">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-1 h-12 bg-gradient-to-b from-red-500 to-red-600 rounded-full"></div>
                            <div>
                                <h2
                                        class="text-2xl lg:text-3xl font-bold font-serif bg-gradient-to-r from-red-600 to-red-500 text-transparent bg-clip-text">
                                    مقتطفات الفيديو</h2>
                                <p class="text-sm text-gray-500 mt-1">لقطات مختارة تسلط الضوء على البرنامج</p>
                                </div>
                            </div>
                            <span
                                class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm rounded-full font-semibold shadow-lg">
                                {{ $videoMedia->count() }} فيديو
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                            @foreach ($videoMedia as $video)
                                <div
                                    class="group bg-white/90 backdrop-blur-sm rounded-3xl overflow-hidden border border-red-100/50 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                                    <div class="relative w-full overflow-hidden rounded-t-3xl" style="padding-top: 56.25%;">
                                        <iframe src="{{ $video->getYouTubeEmbedUrl() }}"
                                            class="absolute inset-0 w-full h-full group-hover:scale-105 transition-transform duration-700" allowfullscreen></iframe>
                                        <div class="absolute top-4 left-4">
                                            <span class="px-3 py-1 bg-red-500/90 backdrop-blur-sm text-white text-xs font-bold rounded-full shadow-lg">
                                                <svg class="w-3 h-3 inline ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                </svg>
                                                يوتيوب
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-red-600 transition-colors">
                                            {{ $video->name ?? 'مقطع فيديو' }}</h3>
                                        @if ($video->description)
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $video->description }}
                                            </p>
                                        @endif
                                        <a href="{{ $video->youtube_url }}" target="_blank"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl text-sm font-semibold hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            مشاهدة على يوتيوب
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($programLinks->isNotEmpty())
                    <section class="relative">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-1 h-12 bg-gradient-to-b from-blue-500 to-blue-600 rounded-full"></div>
                            <div>
                                <h2
                                        class="text-2xl lg:text-3xl font-bold font-serif bg-gradient-to-r from-blue-600 to-blue-500 text-transparent bg-clip-text">
                                    روابط مفيدة</h2>
                                <p class="text-sm text-gray-500 mt-1">مصادر ومراجع تعزز محتوى البرنامج</p>
                                </div>
                            </div>
                            <span
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm rounded-full font-semibold shadow-lg">
                                {{ $programLinks->count() }} رابط
                            </span>
                        </div>
                        <div class="space-y-4">
                            @foreach ($programLinks as $link)
                                <a href="{{ $link->url }}" target="_blank"
                                    class="group flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-5 lg:p-6 bg-white/90 backdrop-blur-sm border border-blue-100/50 rounded-2xl hover:border-blue-300 transition-all duration-300 shadow-lg hover:shadow-2xl hover:-translate-y-1">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                                            {{ $link->title }}
                                        </h3>
                                        </div>
                                        @if ($link->description)
                                            <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $link->description }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-3 break-all font-mono bg-gray-50 px-3 py-2 rounded-lg">{{ $link->url }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl text-sm font-semibold shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
                                        <span>الانتقال</span>
                                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($galleryMedia->isEmpty() && $videoMedia->isEmpty() && $programLinks->isEmpty() && $programGalleries->isEmpty() && $subPrograms->isEmpty())
                    <div
                        class="bg-gradient-to-br from-white/90 via-emerald-50/50 to-white/90 backdrop-blur-md border border-emerald-100/50 rounded-3xl p-12 text-center shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-full opacity-10">
                            <div class="absolute top-10 left-10 w-32 h-32 bg-emerald-400 rounded-full blur-3xl"></div>
                            <div class="absolute bottom-10 right-10 w-32 h-32 bg-teal-400 rounded-full blur-3xl"></div>
                        </div>
                        <div class="relative z-10">
                            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-emerald-400 to-teal-400 rounded-full flex items-center justify-center shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">قريباً</h3>
                            <p class="text-lg text-gray-600 leading-relaxed">لم يتم إضافة محتوى تفصيلي لهذا البرنامج بعد.<br>ترقبوا تحديثات قادمة قريباً.</p>
                        </div>
                    </div>
                @endif
            </div>
        </article>
    </main>

    <div id="lightbox" class="lightbox fixed inset-0 bg-black/90 z-[9999] items-center justify-center">
        <div class="relative max-w-[92vw] max-h-[90vh]">
            <img id="lightboxImage"
                class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl transition-all duration-200"
                alt="معرض الصور">
            <button onclick="prevImage()" aria-label="السابق"
                class="absolute top-1/2 -translate-y-1/2 -left-4 sm:left-[-60px] bg-white/85 hover:bg-white text-gray-800 w-12 h-12 rounded-full flex items-center justify-center text-2xl transition-transform hover:scale-110">&#8249;</button>
            <button onclick="nextImage()" aria-label="التالي"
                class="absolute top-1/2 -translate-y-1/2 -right-4 sm:right-[-60px] bg-white/85 hover:bg-white text-gray-800 w-12 h-12 rounded-full flex items-center justify-center text-2xl transition-transform hover:scale-110">&#8250;</button>
            <button onclick="closeLightbox()" aria-label="إغلاق"
                class="absolute -top-4 -right-4 sm:top-4 sm:right-4 bg-white/90 hover:bg-white text-gray-800 w-12 h-12 rounded-full flex items-center justify-center text-xl transition-transform hover:rotate-90">✕</button>
            <div id="imageCounter"
                class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/60 text-white px-4 py-1 rounded-full text-sm tracking-wide">
            </div>
        </div>
    </div>

    <script>
        const progressBar = document.getElementById('readingProgress');
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            progressBar.style.width = docHeight > 0 ? `${(scrollTop / docHeight) * 100}%` : '0%';
        });

        // دمج جميع الصور (الصور العادية + صور المعارض)
        const galleryImages = @json($galleryPaths);
        @if ($programGalleries->isNotEmpty())
            @foreach ($programGalleries as $gallery)
                @if ($gallery->images->isNotEmpty())
                    galleryImages.push(...@json($gallery->images->map(fn($img) => asset('storage/' . $img->path))->values()));
                @endif
            @endforeach
        @endif

        let currentIndex = 0;
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        const imageCounter = document.getElementById('imageCounter');

        function openLightbox(index) {
            if (!galleryImages.length) return;
            currentIndex = index;
            updateLightbox();
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
            document.addEventListener('keydown', handleEsc);
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
            document.removeEventListener('keydown', handleEsc);
        }

        function handleEsc(event) {
            if (event.key === 'Escape') closeLightbox();
            if (event.key === 'ArrowRight') nextImage();
            if (event.key === 'ArrowLeft') prevImage();
        }

        function changeImage(step) {
            currentIndex = (currentIndex + step + galleryImages.length) % galleryImages.length;
            lightboxImage.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                updateLightbox();
                lightboxImage.classList.remove('opacity-0', 'scale-95');
            }, 150);
        }

        function prevImage() {
            changeImage(-1);
        }

        function nextImage() {
            changeImage(1);
        }

        function updateLightbox() {
            lightboxImage.src = galleryImages[currentIndex];
            imageCounter.textContent = `${currentIndex + 1} / ${galleryImages.length}`;
        }
    </script>
</body>

</html>
