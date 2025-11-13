@php
    use Illuminate\Support\Str;
    $galleryPaths = $galleryMedia->map(fn($media) => asset('storage/' . $media->path))->values();
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $program->program_title ?? $program->name ?? 'برنامج' }} - منصة برامج عائلة السريع</title>

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
                            '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
                            '50%': { transform: 'translateY(-18px) rotate(3deg)' },
                        },
                        'pulse-soft': {
                            '0%, 100%': { opacity: '0.25' },
                            '50%': { opacity: '0.55' },
                        },
                        fadeIn: {
                            from: { opacity: '0', transform: 'scale(0.97)' },
                            to: { opacity: '1', transform: 'scale(1)' },
                        },
                    },
                    animation: {
                        'float': 'float 9s ease-in-out infinite',
                        'pulse-soft': 'pulse-soft 6s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.3s ease-out',
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
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

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
    </style>
</head>

<body class="text-gray-800 overflow-x-hidden relative">
    @include('partials.main-header')

    <div id="readingProgress" class="fixed top-0 right-0 h-1 bg-gradient-to-r from-emerald-400 via-green-500 to-emerald-400 z-50 transition-all duration-300"></div>

    <div class="fixed top-16 left-12 w-96 h-96 opacity-20 z-0 pointer-events-none animate-float hidden xl:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#34d399" d="M43.8,-75.6C58.8,-69.4,74.4,-59.1,79.4,-44.8C84.4,-30.4,78.8,-12.1,74.9,5.4C71,22.9,69,39.7,59.7,50.3C50.5,60.9,41.3,61.8,28.1,66.8C14.9,71.9,0.7,73,-13.5,72.2C-27.7,71.5,-41.9,68.8,-54.7,61.4C-67.5,54,-79,42,-84.6,27.8C-90.2,13.5,-90,-2.9,-83.6,-17.1C-77.1,-31.4,-64.5,-43.5,-50.9,-52.3C-37.4,-61,-22.9,-66.4,-8.4,-65.7C6.2,-65.1,12.3,-58.6,33.9,-58.2Z" transform="translate(100 100)" />
        </svg>
    </div>
    <div class="fixed bottom-16 right-16 w-96 h-96 opacity-10 z-0 pointer-events-none animate-pulse-soft hidden xl:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#22d3ee" d="M33.9,-58.2C46.8,-50.5,61.6,-46.1,68.9,-36.5C76.3,-26.8,76.3,-12,74.6,5.7C72.9,22.9,69.4,39.7,61.5,48.6C53.6,57.8,41.3,61.8,28.1,66.8C14.9,71.9,0.7,73,-13.5,72.2C-27.7,71.5,-41.9,68.8,-54.7,61.4C-67.5,54,-79,42,-84.6,27.8C-90.2,13.5,-90,-2.9,-83.6,-17.1C-77.1,-31.4,-64.5,-43.5,-50.9,-52.3C-37.4,-61,-22.9,-66.4,-8.4,-65.7C6.2,-65.1,12.3,-58.6,33.9,-58.2Z" transform="translate(100 100)" />
        </svg>
    </div>

    <main class="relative z-10 max-w-6xl mx-auto px-4 lg:px-6 py-10 lg:py-14 space-y-10 lg:space-y-12">
        <div class="mb-6">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/70 backdrop-blur-md border border-white/40 rounded-full hover:bg-white/95 transition-all duration-300 group shadow-sm hover:shadow-lg">
                <svg class="w-5 h-5 text-emerald-600 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium text-gray-700">العودة للصفحة الرئيسية</span>
            </a>
        </div>

        <article class="bg-white/85 backdrop-blur-md border border-white/50 rounded-3xl overflow-hidden shadow-emerald-glow animate-fade-in">
            <header class="relative">
                @php
                    $coverImage = $program->cover_image_path ?? $program->path;
                @endphp
                @if ($coverImage)
                    <div class="relative h-60 sm:h-72 lg:h-80 overflow-hidden">
                        <img src="{{ asset('storage/' . $coverImage) }}" alt="{{ $program->program_title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/80 via-emerald-900/35 to-transparent"></div>
                    </div>
                @else
                    <div class="h-40 bg-gradient-to-br from-emerald-500/20 to-teal-500/20"></div>
                @endif

                <div class="p-6 lg:p-10 -mt-24 relative z-10">
                    <div class="bg-white/95 backdrop-blur rounded-3xl p-6 lg:p-8 border border-white/60 shadow-lg">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                            <div>
                                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 leading-relaxed font-serif bg-gradient-to-r from-emerald-600 to-teal-500 text-transparent bg-clip-text">
                                    {{ $program->program_title ?? $program->name ?? 'برنامج' }}
                                </h1>
                                @if ($program->program_description)
                                    <div class="text-gray-600 leading-relaxed text-lg program-description">
                                        {!! $program->program_description !!}
                                    </div>
                                @endif
                            </div>
                            {{-- <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 w-full md:w-64">
                                <h2 class="font-semibold text-emerald-600 flex items-center gap-2 mb-3">
                                    <i class="fas fa-info-circle"></i>
                                    معلومات سريعة
                                </h2>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex items-center gap-2"><i class="fas fa-calendar-alt text-emerald-500"></i><span>تاريخ الإنشاء:</span><span class="font-medium">{{ $program->created_at->format('d/m/Y') }}</span></div>
                                    <div class="flex items-center gap-2"><i class="fas fa-clock text-emerald-500"></i><span>آخر تحديث:</span><span class="font-medium">{{ $program->updated_at->diffForHumans() }}</span></div>
                                    <div class="flex items-center gap-2"><i class="fas fa-images text-emerald-500"></i><span>عدد الصور:</span><span class="font-medium">{{ $galleryMedia->count() }}</span></div>
                                    <div class="flex items-center gap-2"><i class="fab fa-youtube text-emerald-500"></i><span>عدد الفيديوهات:</span><span class="font-medium">{{ $videoMedia->count() }}</span></div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6 lg:p-10 space-y-14">
                @if($galleryMedia->isNotEmpty())
                    <section>
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                            <div>
                                <h2 class="text-2xl lg:text-3xl font-bold font-serif bg-gradient-to-r from-emerald-600 to-teal-500 text-transparent bg-clip-text">{{ $program->name ?? $program->program_title ?? 'معرض الصور' }}</h2>
                            </div>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-sm rounded-full border border-emerald-100">
                                {{ $galleryMedia->count() }} صورة
                            </span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
                            @foreach($galleryMedia as $index => $media)
                                <div class="group relative overflow-hidden rounded-2xl border border-white/60 shadow-md hover:shadow-xl transition-all duration-300">
                                    <div class="cursor-pointer" onclick="openLightbox({{ $index }})">
                                        <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->name ?? 'صورة' }}" class="w-full h-48 md:h-52 object-cover transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        <div class="absolute bottom-3 right-3 left-3 text-white">
                                            <h3 class="font-semibold text-base line-clamp-1">{{ $media->name ?? 'صورة من فعاليات البرنامج' }}</h3>
                                            @if($media->description)
                                                <p class="text-xs text-white/80 mt-1 line-clamp-2">{{ $media->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if(Auth::check())
                                        <div class="absolute top-2 left-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                            <a href="{{ route('dashboard.programs.manage', $program) }}"
                                               class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg shadow-lg transition-colors"
                                               title="تعديل الصورة">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <form action="{{ route('dashboard.programs.media.destroy', [$program, $media]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg shadow-lg transition-colors"
                                                        title="حذف الصورة">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($videoMedia->isNotEmpty())
                    <section>
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                            <div>
                                <h2 class="text-2xl lg:text-3xl font-bold font-serif bg-gradient-to-r from-emerald-600 to-teal-500 text-transparent bg-clip-text">مقتطفات الفيديو</h2>
                                <p class="text-sm text-gray-500 mt-1">لقطات مختارة تسلط الضوء على البرنامج</p>
                            </div>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-sm rounded-full border border-emerald-100">
                                {{ $videoMedia->count() }} فيديو
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                            @foreach($videoMedia as $video)
                                <div class="bg-white rounded-2xl overflow-hidden border border-white/60 shadow-md hover:shadow-xl transition-shadow duration-300">
                                    <div class="relative w-full overflow-hidden" style="padding-top: 56.25%;">
                                        <iframe src="{{ $video->getYouTubeEmbedUrl() }}" class="absolute inset-0 w-full h-full" allowfullscreen></iframe>
                                    </div>
                                    <div class="p-5 space-y-3">
                                        <h3 class="text-xl font-semibold text-gray-800">{{ $video->name ?? 'مقطع فيديو' }}</h3>
                                        @if($video->description)
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $video->description }}</p>
                                        @endif
                                        <a href="{{ $video->youtube_url }}" target="_blank" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 text-sm font-medium transition-colors">
                                            <i class="fas fa-external-link-alt"></i>
                                            مشاهدة على يوتيوب
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($programLinks->isNotEmpty())
                    <section>
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                            <div>
                                <h2 class="text-2xl lg:text-3xl font-bold font-serif bg-gradient-to-r from-emerald-600 to-teal-500 text-transparent bg-clip-text">روابط مفيدة</h2>
                                <p class="text-sm text-gray-500 mt-1">مصادر ومراجع تعزز محتوى البرنامج</p>
                            </div>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-sm rounded-full border border-emerald-100">
                                {{ $programLinks->count() }} رابط
                            </span>
                        </div>
                        <div class="space-y-3">
                            @foreach($programLinks as $link)
                                <a href="{{ $link->url }}" target="_blank"
                                   class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-4 lg:p-5 bg-white border border-emerald-100 rounded-2xl hover:border-emerald-300 transition-shadow shadow-sm hover:shadow-lg">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                            <i class="fas fa-link text-emerald-500"></i>
                                            {{ $link->title }}
                                        </h3>
                                        @if($link->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $link->description }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-2 break-all">{{ $link->url }}</p>
                                    </div>
                                    <span class="flex items-center gap-2 text-emerald-600 text-sm font-semibold">
                                        الانتقال
                                        <i class="fas fa-arrow-left"></i>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($galleryMedia->isEmpty() && $videoMedia->isEmpty() && $programLinks->isEmpty())
                    <div class="bg-white/90 backdrop-blur-md border border-white/60 rounded-3xl p-10 text-center shadow-lg">
                        <i class="fas fa-hourglass-half text-4xl text-emerald-500 mb-4"></i>
                        <p class="text-lg text-gray-600">لم يتم إضافة محتوى تفصيلي لهذا البرنامج بعد. ترقبوا تحديثات قادمة قريباً.</p>
                    </div>
                @endif
            </div>
        </article>
    </main>

    <div id="lightbox" class="lightbox fixed inset-0 bg-black/90 z-[9999] items-center justify-center">
        <div class="relative max-w-[92vw] max-h-[90vh]">
            <img id="lightboxImage" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl transition-all duration-200" alt="معرض الصور">
            <button onclick="prevImage()" aria-label="السابق"
                    class="absolute top-1/2 -translate-y-1/2 -left-4 sm:left-[-60px] bg-white/85 hover:bg-white text-gray-800 w-12 h-12 rounded-full flex items-center justify-center text-2xl transition-transform hover:scale-110">&#8249;</button>
            <button onclick="nextImage()" aria-label="التالي"
                    class="absolute top-1/2 -translate-y-1/2 -right-4 sm:right-[-60px] bg-white/85 hover:bg-white text-gray-800 w-12 h-12 rounded-full flex items-center justify-center text-2xl transition-transform hover:scale-110">&#8250;</button>
            <button onclick="closeLightbox()" aria-label="إغلاق"
                    class="absolute -top-4 -right-4 sm:top-4 sm:right-4 bg-white/90 hover:bg-white text-gray-800 w-12 h-12 rounded-full flex items-center justify-center text-xl transition-transform hover:rotate-90">✕</button>
            <div id="imageCounter" class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/60 text-white px-4 py-1 rounded-full text-sm tracking-wide"></div>
        </div>
    </div>

    <script>
        const progressBar = document.getElementById('readingProgress');
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            progressBar.style.width = docHeight > 0 ? `${(scrollTop / docHeight) * 100}%` : '0%';
        });

        const galleryImages = @json($galleryPaths);
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

