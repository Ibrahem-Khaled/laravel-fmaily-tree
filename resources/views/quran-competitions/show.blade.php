<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $competition->title }} - {{ $competition->hijri_year }} هـ - عائلة السريع</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#37a05c',
                            600: '#16a34a',
                            700: '#145147',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: {
                        'alexandria': ['Alexandria', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'gradient': 'gradient 8s ease infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-15px)' },
                        },
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                    },
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Alexandria', sans-serif;
        }

        .bg-mesh {
            background-color: #f0fdf4;
            background-image:
                radial-gradient(at 40% 20%, rgba(55, 160, 92, 0.1) 0px, transparent 50%),
                radial-gradient(at 80% 0%, rgba(20, 81, 71, 0.1) 0px, transparent 50%),
                radial-gradient(at 0% 50%, rgba(74, 222, 128, 0.1) 0px, transparent 50%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #145147 0%, #37a05c 50%, #4ade80 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #145147 0%, #1a6b5a 25%, #37a05c 50%, #4ade80 75%, #86efac 100%);
            background-size: 300% 300%;
            animation: gradient 8s ease infinite;
        }

        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M0 0h80v80H0V0zm20 20v40h40V20H20zm20 35a15 15 0 1 1 0-30 15 15 0 0 1 0 30z' fill-rule='evenodd'/%3E%3C/g%3E%3C/svg%3E");
        }

        .winner-card-hover:hover .winner-avatar {
            transform: scale(1.1) rotate(5deg);
        }

        .media-item:hover img,
        .media-item:hover video {
            transform: scale(1.1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-up {
            animation: slideUp 0.6s ease-out forwards;
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }
        .stagger-6 { animation-delay: 0.6s; }
    </style>

    @include('partials.main-header')
</head>

<body class="font-alexandria bg-mesh min-h-screen">

    <!-- Hero Section -->
    <section class="relative min-h-[50vh] md:min-h-[70vh] overflow-hidden">
        <!-- Background Image -->
        @if($competition->cover_image)
            <img src="{{ $competition->cover_image_url }}" alt="{{ $competition->title }}" class="absolute inset-0 w-full h-full object-cover">
        @endif

        <!-- Dark Overlay for Text Readability -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/50"></div>

        <!-- Gradient Overlay -->
        <div class="absolute inset-0 hero-gradient opacity-80 islamic-pattern"></div>

        <!-- Floating Shapes -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-20 left-20 w-96 h-96 bg-white/5 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        </div>

        <!-- Back Button -->
        <a href="{{ route('quran-competitions.index') }}" class="absolute top-4 right-4 md:top-8 md:right-8 z-30 group">
            <div class="flex items-center gap-2 md:gap-3 bg-white/15 backdrop-blur-xl border-2 border-white/30 text-white px-3 py-2 md:px-6 md:py-3 rounded-full text-sm md:text-base font-bold shadow-2xl hover:bg-white/25 transition-all hover:scale-105">
                <i class="fas fa-arrow-right text-xs md:text-base group-hover:translate-x-1 transition-transform"></i>
                <span>العودة</span>
            </div>
        </a>

        <!-- Hero Content -->
        <div class="relative z-20 container mx-auto px-4 h-full min-h-[50vh] md:min-h-[70vh] flex flex-col items-center justify-center text-center py-8 md:py-0">
            <!-- Title -->
            <h1 class="text-2xl md:text-5xl lg:text-6xl font-black text-white mb-4 md:mb-6 drop-shadow-2xl tracking-tight max-w-4xl px-2">
                {{ $competition->title }}
            </h1>

            <!-- Year Badge -->
            <div class="bg-white/20 backdrop-blur-xl border-2 border-white/30 text-white px-6 py-2 md:px-10 md:py-4 rounded-full text-lg md:text-3xl font-bold shadow-2xl">
                {{ $competition->hijri_year }} هـ
            </div>

            <!-- Quick Stats -->
            <div class="flex flex-wrap justify-center gap-3 md:gap-6 mt-6 md:mt-12">
                <div class="glass-card rounded-xl md:rounded-2xl px-4 py-2 md:px-6 md:py-4 border border-white/20 shadow-xl">
                    <div class="text-xl md:text-3xl font-black text-primary-700">{{ $competition->winners->count() }}</div>
                    <div class="text-primary-600 font-medium text-xs md:text-sm">فائز</div>
                </div>
                <div class="glass-card rounded-xl md:rounded-2xl px-4 py-2 md:px-6 md:py-4 border border-white/20 shadow-xl">
                    <div class="text-xl md:text-3xl font-black text-primary-700">{{ $competition->media->count() }}</div>
                    <div class="text-primary-600 font-medium text-xs md:text-sm">صورة وفيديو</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="relative z-10 -mt-8 md:-mt-16">
        <div class="container mx-auto px-4 pb-8 md:pb-16">

            <!-- Competition Info Section -->
            <section class="glass-card rounded-2xl md:rounded-3xl p-4 md:p-12 shadow-2xl border border-gray-100 mb-6 md:mb-10 slide-up">
                <!-- Section Header -->
                <div class="flex items-center gap-3 md:gap-5 mb-4 md:mb-8 pb-4 md:pb-6 border-b-2 border-primary-100">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                        <i class="fas fa-info-circle text-lg md:text-2xl text-white"></i>
                    </div>
                    <h2 class="text-xl md:text-4xl font-black gradient-text">معلومات المسابقة</h2>
                </div>

                <!-- Description -->
                @if($competition->description)
                    <div class="text-gray-600 text-sm md:text-lg leading-loose mb-6 md:mb-10 text-justify">
                        {!! nl2br(e($competition->description)) !!}
                    </div>
                @endif

                <!-- Meta Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                    @if($competition->start_date)
                        <div class="bg-gradient-to-br from-primary-50 to-green-50 rounded-xl md:rounded-2xl p-3 md:p-6 text-center border-2 border-primary-100 hover:border-primary-300 hover:-translate-y-1 md:hover:-translate-y-2 transition-all group">
                            <div class="text-primary-500 text-2xl md:text-4xl mb-2 md:mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="text-gray-500 text-xs md:text-sm font-medium mb-1 md:mb-2">تاريخ البداية</div>
                            <div class="text-primary-700 text-sm md:text-lg font-bold start-date" data-date="{{ $competition->start_date->format('Y-m-d') }}">
                                <span class="hidden md:inline">{{ $competition->start_date->format('Y-m-d') }}</span>
                                <span class="md:hidden">{{ $competition->start_date->format('Y-m-d') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($competition->end_date)
                        <div class="bg-gradient-to-br from-primary-50 to-green-50 rounded-xl md:rounded-2xl p-3 md:p-6 text-center border-2 border-primary-100 hover:border-primary-300 hover:-translate-y-1 md:hover:-translate-y-2 transition-all group">
                            <div class="text-primary-500 text-2xl md:text-4xl mb-2 md:mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="text-gray-500 text-xs md:text-sm font-medium mb-1 md:mb-2">تاريخ النهاية</div>
                            <div class="text-primary-700 text-sm md:text-lg font-bold end-date" data-date="{{ $competition->end_date->format('Y-m-d') }}">
                                <span class="hidden md:inline">{{ $competition->end_date->format('Y-m-d') }}</span>
                                <span class="md:hidden">{{ $competition->end_date->format('Y-m-d') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="bg-gradient-to-br from-primary-50 to-green-50 rounded-xl md:rounded-2xl p-3 md:p-6 text-center border-2 border-primary-100 hover:border-primary-300 hover:-translate-y-1 md:hover:-translate-y-2 transition-all group">
                        <div class="text-primary-500 text-2xl md:text-4xl mb-2 md:mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="text-gray-500 text-xs md:text-sm font-medium mb-1 md:mb-2">عدد الفائزين</div>
                        <div class="text-primary-700 text-xl md:text-3xl font-black">{{ $competition->winners->count() }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-primary-50 to-green-50 rounded-xl md:rounded-2xl p-3 md:p-6 text-center border-2 border-primary-100 hover:border-primary-300 hover:-translate-y-1 md:hover:-translate-y-2 transition-all group">
                        <div class="text-primary-500 text-2xl md:text-4xl mb-2 md:mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-images"></i>
                        </div>
                        <div class="text-gray-500 text-xs md:text-sm font-medium mb-1 md:mb-2">عدد الوسائط</div>
                        <div class="text-primary-700 text-xl md:text-3xl font-black">{{ $competition->media->count() }}</div>
                    </div>
                </div>
            </section>

            <!-- Winners Section -->
            @if($competition->winners->count() > 0)
                <section class="glass-card rounded-2xl md:rounded-3xl p-4 md:p-12 shadow-2xl border border-gray-100 mb-6 md:mb-10 slide-up stagger-2">
                    <!-- Section Header -->
                    <div class="flex items-center gap-3 md:gap-5 mb-4 md:mb-10 pb-4 md:pb-6 border-b-2 border-primary-100">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg shadow-yellow-500/30">
                            <i class="fas fa-trophy text-lg md:text-2xl text-white"></i>
                        </div>
                        <h2 class="text-xl md:text-4xl font-black gradient-text">الفائزون</h2>
                    </div>

                    <!-- Winners Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-8">
                        @foreach($competition->winners->sortBy('position') as $index => $winner)
                            <div class="group winner-card-hover">
                                <div class="bg-gradient-to-br from-white to-primary-50 rounded-xl md:rounded-3xl p-3 md:p-8 text-center border-2 border-primary-100 hover:border-primary-400 shadow-lg md:shadow-xl hover:shadow-xl md:hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 md:hover:-translate-y-4 relative overflow-hidden">
                                    <!-- Top Border Gradient -->
                                    <div class="absolute top-0 left-0 right-0 h-1 md:h-1.5 bg-gradient-to-r from-primary-400 via-primary-500 to-primary-600"></div>

                                    <!-- Position Badge -->
                                    @if($winner->position <= 3)
                                        <div class="absolute top-2 left-2 md:top-4 md:left-4">
                                            @if($winner->position == 1)
                                                <div class="w-6 h-6 md:w-10 md:h-10 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-full flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-crown text-white text-xs md:text-base"></i>
                                                </div>
                                            @elseif($winner->position == 2)
                                                <div class="w-6 h-6 md:w-10 md:h-10 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-medal text-white text-xs md:text-base"></i>
                                                </div>
                                            @else
                                                <div class="w-6 h-6 md:w-10 md:h-10 bg-gradient-to-br from-amber-600 to-amber-700 rounded-full flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-award text-white text-xs md:text-base"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Avatar -->
                                    <div class="relative inline-block mb-3 md:mb-6">
                                        <div class="absolute inset-0 bg-primary-400 blur-xl rounded-full scale-110 opacity-30 group-hover:opacity-50 transition-opacity"></div>
                                        <img src="{{ $winner->person->avatar }}" alt="{{ $winner->person->full_name }}" class="winner-avatar relative w-16 h-16 md:w-32 md:h-32 rounded-full object-cover border-2 md:border-4 border-primary-500 shadow-xl transition-all duration-500">
                                    </div>

                                    <!-- Name -->
                                    <h3 class="text-sm md:text-xl font-bold text-gray-800 mb-2 md:mb-4 group-hover:text-primary-600 transition-colors line-clamp-2">
                                        {{ $winner->person->full_name }}
                                    </h3>

                                    <!-- Position -->
                                    <div class="inline-block bg-gradient-to-r from-primary-500 to-primary-600 text-white px-3 py-1 md:px-6 md:py-2 rounded-full text-xs md:text-base font-bold shadow-lg shadow-primary-500/30 mb-2 md:mb-4">
                                        {{ $winner->position_name }}
                                    </div>

                                    <!-- Category -->
                                    @if($winner->category)
                                        <div class="flex items-center justify-center gap-1 md:gap-2 text-primary-600 font-medium mt-1 md:mt-2 text-xs md:text-sm">
                                            <i class="fas fa-star text-yellow-500 text-xs"></i>
                                            <span class="line-clamp-1">{{ $winner->category }}</span>
                                        </div>
                                    @endif

                                    <!-- Notes -->
                                    @if($winner->notes)
                                        <div class="mt-2 md:mt-4 pt-2 md:pt-4 border-t-2 border-primary-100 text-gray-500 text-xs md:text-sm italic leading-relaxed line-clamp-2 hidden md:block">
                                            {{ $winner->notes }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Media Section -->
            @if($competition->media->count() > 0)
                <section class="glass-card rounded-2xl md:rounded-3xl p-4 md:p-12 shadow-2xl border border-gray-100 slide-up stagger-3">
                    <!-- Section Header -->
                    <div class="flex items-center gap-3 md:gap-5 mb-4 md:mb-10 pb-4 md:pb-6 border-b-2 border-primary-100">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                            <i class="fas fa-images text-lg md:text-2xl text-white"></i>
                        </div>
                        <h2 class="text-xl md:text-4xl font-black gradient-text">الصور والفيديوهات</h2>
                    </div>

                    <!-- Media Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-8">
                        @foreach($competition->media->sortBy('sort_order') as $media)
                            <div class="media-item group cursor-pointer" onclick="openMedia('{{ $media->id }}', '{{ $media->media_type }}', '{{ $media->file_url ?? $media->youtube_url }}')">
                                <div class="relative rounded-xl md:rounded-2xl overflow-hidden shadow-lg md:shadow-xl hover:shadow-xl md:hover:shadow-2xl transition-all duration-500 border-2 border-transparent hover:border-primary-400 hover:-translate-y-1 md:hover:-translate-y-3">
                                    @if($media->media_type === 'youtube')
                                        <div class="relative h-32 md:h-64 bg-black">
                                            <img src="{{ $media->youtube_thumbnail }}" alt="{{ $media->caption }}" class="w-full h-full object-cover transition-transform duration-700">
                                            <!-- Play Button -->
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="w-10 h-10 md:w-20 md:h-20 bg-red-600 rounded-full flex items-center justify-center shadow-2xl shadow-red-600/50 group-hover:scale-110 transition-transform border-2 md:border-4 border-white/30">
                                                    <i class="fab fa-youtube text-white text-lg md:text-4xl"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($media->media_type === 'video')
                                        <div class="relative h-32 md:h-64 bg-black">
                                            <video src="{{ $media->file_url }}" class="w-full h-full object-cover transition-transform duration-700" preload="metadata"></video>
                                            <!-- Play Button -->
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="w-10 h-10 md:w-20 md:h-20 bg-primary-600 rounded-full flex items-center justify-center shadow-2xl shadow-primary-600/50 group-hover:scale-110 transition-transform border-2 md:border-4 border-white/30">
                                                    <i class="fas fa-play text-white text-sm md:text-2xl mr-[-2px] md:mr-[-4px]"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="relative h-32 md:h-64">
                                            <img src="{{ $media->file_url }}" alt="{{ $media->caption }}" class="w-full h-full object-cover transition-transform duration-700">
                                            <!-- Zoom Icon -->
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/30">
                                                <div class="w-8 h-8 md:w-14 md:h-14 bg-white/90 rounded-full flex items-center justify-center shadow-xl">
                                                    <i class="fas fa-search-plus text-primary-600 text-xs md:text-xl"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Caption -->
                                    @if($media->caption)
                                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/95 via-black/80 to-black/50 p-2 md:p-6">
                                            <p class="text-white font-bold text-xs md:text-lg drop-shadow-lg line-clamp-2">{{ $media->caption }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

        </div>
    </main>

    <!-- Footer Decoration -->
    <div class="h-20 bg-gradient-to-t from-primary-50 to-transparent"></div>

    <!-- Media Modal -->
    <div id="mediaModal" class="fixed inset-0 z-50 hidden items-center justify-center p-2 md:p-4 bg-black/90 backdrop-blur-sm">
        <button onclick="closeModal()" class="absolute top-4 right-4 md:top-6 md:right-6 w-10 h-10 md:w-12 md:h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white text-xl md:text-2xl transition-colors">
            <i class="fas fa-times"></i>
        </button>
        <div id="mediaContent" class="max-w-6xl w-full max-h-[90vh] overflow-auto px-2"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hijri Date Conversion
        const gregorianToHijri = (gregorianDate) => {
            if (!gregorianDate) return '';
            try {
                const date = new Date(gregorianDate);
                let year = date.getFullYear();
                let month = date.getMonth() + 1;
                let day = date.getDate();
                let jd = Math.floor((1461 * (year + 4800 + Math.floor((month - 14) / 12))) / 4) +
                         Math.floor((367 * (month - 2 - 12 * Math.floor((month - 14) / 12))) / 12) -
                         Math.floor((3 * Math.floor((year + 4900 + Math.floor((month - 14) / 12)) / 100)) / 4) +
                         day - 32075;
                let l = jd - 1948440 + 10632;
                let n = Math.floor((l - 1) / 10631);
                l = l - 10631 * n + 354;
                let j = Math.floor((10985 - l) / 5316) * Math.floor((50 * l) / 17719) +
                       Math.floor(l / 5670) * Math.floor((43 * l) / 15238);
                l = l - Math.floor((30 - j) / 15) * Math.floor((17719 * j) / 50) -
                    Math.floor(j / 16) * Math.floor((15238 * j) / 43) + 29;
                let m = Math.floor((24 * l) / 709);
                let d = l - Math.floor((709 * m) / 24);
                let y = 30 * n + j - 30;
                const hijriMonths = ['محرم', 'صفر', 'ربيع الأول', 'ربيع الثاني', 'جمادى الأولى', 'جمادى الثانية',
                                   'رجب', 'شعبان', 'رمضان', 'شوال', 'ذو القعدة', 'ذو الحجة'];
                return `${d} ${hijriMonths[m - 1]} ${y}هـ`;
            } catch (e) {
                return '';
            }
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.start-date, .end-date').forEach(function(element) {
                const dateStr = element.getAttribute('data-date');
                const hijriDate = gregorianToHijri(dateStr);
                if (hijriDate) {
                    element.textContent = hijriDate;
                }
            });
        });

        // Media Modal Functions
        function openMedia(id, type, url) {
            const modal = document.getElementById('mediaModal');
            const content = document.getElementById('mediaContent');
            content.innerHTML = '';

            if (type === 'youtube') {
                const videoId = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
                if (videoId) {
                    content.innerHTML = `<div class="relative rounded-2xl overflow-hidden" style="padding-bottom: 56.25%;"><iframe class="absolute inset-0 w-full h-full" src="https://www.youtube.com/embed/${videoId[1]}?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>`;
                }
            } else if (type === 'video') {
                content.innerHTML = `<video class="w-full rounded-2xl shadow-2xl" controls autoplay><source src="${url}" type="video/mp4">Your browser does not support the video tag.</video>`;
            } else {
                content.innerHTML = `<img src="${url}" class="max-h-[85vh] mx-auto rounded-2xl shadow-2xl" alt="صورة">`;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('mediaModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('mediaContent').innerHTML = '';
            document.body.style.overflow = '';
        }

        // Close on backdrop click
        document.getElementById('mediaModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>

</html>
