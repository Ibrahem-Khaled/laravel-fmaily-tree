<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مسابقة القرآن الكريم للأطفال - عائلة السريع</title>
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
                        'float-delayed': 'float 6s ease-in-out infinite 2s',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'shimmer': 'shimmer 2s linear infinite',
                        'gradient': 'gradient 8s ease infinite',
                        'bounce-slow': 'bounce 2s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%': { transform: 'translateY(-20px) rotate(5deg)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' },
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
                radial-gradient(at 0% 50%, rgba(74, 222, 128, 0.1) 0px, transparent 50%),
                radial-gradient(at 80% 50%, rgba(55, 160, 92, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(20, 81, 71, 0.1) 0px, transparent 50%),
                radial-gradient(at 80% 100%, rgba(74, 222, 128, 0.05) 0px, transparent 50%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
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

        .card-shine {
            position: relative;
            overflow: hidden;
        }

        .card-shine::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s ease;
            z-index: 10;
            pointer-events: none;
        }

        .card-shine:hover::before {
            left: 100%;
        }

        .pattern-overlay {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M0 0h80v80H0V0zm20 20v40h40V20H20zm20 35a15 15 0 1 1 0-30 15 15 0 0 1 0 30z' fill-rule='evenodd'/%3E%3C/g%3E%3C/svg%3E");
        }

        .floating-shapes span {
            position: absolute;
            display: block;
            animation: float 15s linear infinite;
            opacity: 0.1;
        }

        @keyframes scroll-hint {
            0%, 100% { transform: translateY(0); opacity: 1; }
            50% { transform: translateY(10px); opacity: 0.5; }
        }

        .scroll-hint {
            animation: scroll-hint 2s ease-in-out infinite;
        }

        .image-zoom {
            transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .image-zoom:hover {
            transform: scale(1.1) rotate(1deg);
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }
        .stagger-6 { animation-delay: 0.6s; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-up {
            animation: slideUp 0.8s ease-out forwards;
            opacity: 0;
        }
    </style>

    @include('partials.main-header')
</head>

<body class="font-alexandria bg-mesh min-h-screen">

    <!-- Page Header -->
    <section class="py-6 md:py-12 bg-white border-b border-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-2xl md:text-4xl font-black gradient-text mb-2 md:mb-4">
                    مسابقة حفظ القرآن الكريم
                </h1>
                <p class="text-gray-600 text-sm md:text-lg max-w-2xl mx-auto px-2">
                    نحفز أطفالنا على حفظ كتاب الله وتلاوته بشكل صحيح مع جوائز قيمة وتكريم للفائزين
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="py-12">
        <div class="container mx-auto px-4">

            @if($currentCompetitions->count() > 0)
                <!-- Current Competitions Section -->
                <section class="mb-12">
                    <!-- Cards Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-8">
                        @foreach($currentCompetitions as $index => $competition)
                            <article class="slide-up stagger-{{ $index + 1 }} group">
                                <a href="{{ route('quran-competitions.show', $competition->id) }}" class="block">
                                    <div class="glass-card rounded-xl md:rounded-3xl overflow-hidden shadow-lg md:shadow-xl hover:shadow-xl md:hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-primary-300 hover:-translate-y-1 md:hover:-translate-y-3 card-shine">
                                        
                                        <!-- Image Container -->
                                        <div class="relative h-32 md:h-64 overflow-hidden bg-gradient-to-br from-primary-100 to-primary-50">
                                            @if($competition->cover_image)
                                                <img src="{{ $competition->cover_image_url }}" alt="{{ $competition->title }}" class="w-full h-full object-cover image-zoom">
                                            @else
                                                <div class="flex items-center justify-center h-full bg-gradient-to-br from-primary-200 to-primary-100">
                                                    <div class="text-2xl md:text-6xl text-primary-400/30 font-bold">{{ $competition->hijri_year }} هـ</div>
                                                </div>
                                            @endif
                                            
                                            <!-- Dark Overlay for Text Readability -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                                            
                                            <!-- Year Badge -->
                                            <div class="absolute top-2 left-2 md:top-5 md:left-5 z-20">
                                                <div class="bg-gradient-to-r from-primary-700 to-primary-500 text-white px-2 py-1 md:px-5 md:py-2.5 rounded-full text-xs md:text-base font-bold shadow-lg shadow-primary-700/40 flex items-center gap-1 md:gap-2">
                                                    <i class="fas fa-calendar-alt text-xs"></i>
                                                    <span class="hidden md:inline">{{ $competition->hijri_year }} هـ</span>
                                                    <span class="md:hidden">{{ $competition->hijri_year }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="p-3 md:p-7">
                                            <h3 class="text-sm md:text-xl font-bold text-gray-800 mb-2 md:mb-3 group-hover:text-primary-600 transition-colors line-clamp-2">
                                                {{ $competition->title }}
                                            </h3>
                                            
                                            @if($competition->description)
                                                <p class="text-gray-500 text-xs md:text-sm leading-relaxed mb-3 md:mb-5 line-clamp-2 hidden md:block">
                                                    {{ Str::limit($competition->description, 100) }}
                                                </p>
                                            @endif

                                            <!-- Meta Info -->
                                            <div class="flex flex-wrap gap-2 md:gap-3 mb-3 md:mb-6">
                                                @if($competition->start_date)
                                                    <span class="inline-flex items-center gap-1 md:gap-2 bg-primary-50 text-primary-700 px-2 py-1 md:px-4 md:py-2 rounded-lg md:rounded-xl text-xs md:text-sm font-medium">
                                                        <i class="fas fa-calendar-alt text-xs"></i>
                                                        <span class="start-date hidden md:inline" data-date="{{ $competition->start_date->format('Y-m-d') }}">{{ $competition->start_date->format('Y-m-d') }}</span>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Stats -->
                                            <div class="grid grid-cols-2 gap-2 md:gap-4 mb-3 md:mb-6">
                                                <div class="bg-gradient-to-br from-primary-50 to-green-50 rounded-lg md:rounded-2xl p-2 md:p-4 text-center border border-primary-100 group-hover:border-primary-300 transition-colors">
                                                    <div class="text-lg md:text-3xl font-black text-primary-600">{{ $competition->winners->count() }}</div>
                                                    <div class="text-xs md:text-sm text-gray-500 font-medium mt-1">فائز</div>
                                                </div>
                                                <div class="bg-gradient-to-br from-primary-50 to-green-50 rounded-lg md:rounded-2xl p-2 md:p-4 text-center border border-primary-100 group-hover:border-primary-300 transition-colors">
                                                    <div class="text-lg md:text-3xl font-black text-primary-600">{{ $competition->media->count() }}</div>
                                                    <div class="text-xs md:text-sm text-gray-500 font-medium mt-1">وسائط</div>
                                                </div>
                                            </div>

                                            <!-- CTA Button -->
                                            <div class="relative overflow-hidden bg-gradient-to-r from-primary-500 to-primary-600 text-white text-center py-2 px-3 md:py-4 md:px-6 rounded-lg md:rounded-2xl text-xs md:text-base font-bold shadow-lg shadow-primary-500/30 group-hover:shadow-xl group-hover:shadow-primary-500/40 transition-all">
                                                <span class="relative z-10 flex items-center justify-center gap-1 md:gap-3">
                                                    <span class="hidden md:inline">عرض التفاصيل</span>
                                                    <span class="md:hidden">تفاصيل</span>
                                                    <i class="fas fa-arrow-left text-xs md:text-base group-hover:-translate-x-1 transition-transform"></i>
                                                </span>
                                                <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-700 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($previousCompetitions->count() > 0)
                <!-- Previous Competitions Section -->
                <section class="mb-12 mt-12">
                    <!-- Cards Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-8">
                        @foreach($previousCompetitions as $index => $competition)
                            <article class="slide-up stagger-{{ $index + 1 }} group">
                                <a href="{{ route('quran-competitions.show', $competition->id) }}" class="block">
                                    <div class="glass-card rounded-xl md:rounded-3xl overflow-hidden shadow-lg md:shadow-xl hover:shadow-xl md:hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-primary-300 hover:-translate-y-1 md:hover:-translate-y-3 card-shine">
                                        
                                        <!-- Image Container -->
                                        <div class="relative h-32 md:h-64 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-50">
                                            @if($competition->cover_image)
                                                <img src="{{ $competition->cover_image_url }}" alt="{{ $competition->title }}" class="w-full h-full object-cover image-zoom grayscale-[30%] group-hover:grayscale-0 transition-all duration-500">
                                            @else
                                                <div class="flex items-center justify-center h-full bg-gradient-to-br from-gray-200 to-gray-100">
                                                    <div class="text-2xl md:text-6xl text-gray-400/30 font-bold">{{ $competition->hijri_year }} هـ</div>
                                                </div>
                                            @endif
                                            
                                            <!-- Dark Overlay for Text Readability -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                                            
                                            <!-- Year Badge -->
                                            <div class="absolute top-2 left-2 md:top-5 md:left-5 z-20">
                                                <div class="bg-gradient-to-r from-gray-700 to-gray-600 text-white px-2 py-1 md:px-5 md:py-2.5 rounded-full text-xs md:text-base font-bold shadow-lg flex items-center gap-1 md:gap-2 group-hover:from-primary-700 group-hover:to-primary-500 transition-all">
                                                    <i class="fas fa-calendar-alt text-xs"></i>
                                                    <span class="hidden md:inline">{{ $competition->hijri_year }} هـ</span>
                                                    <span class="md:hidden">{{ $competition->hijri_year }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="p-3 md:p-7">
                                            <h3 class="text-sm md:text-xl font-bold text-gray-800 mb-2 md:mb-3 group-hover:text-primary-600 transition-colors line-clamp-2">
                                                {{ $competition->title }}
                                            </h3>
                                            
                                            @if($competition->description)
                                                <p class="text-gray-500 text-xs md:text-sm leading-relaxed mb-3 md:mb-5 line-clamp-2 hidden md:block">
                                                    {{ Str::limit($competition->description, 100) }}
                                                </p>
                                            @endif

                                            <!-- Meta Info -->
                                            <div class="flex flex-wrap gap-2 md:gap-3 mb-3 md:mb-6">
                                                @if($competition->start_date)
                                                    <span class="inline-flex items-center gap-1 md:gap-2 bg-gray-100 text-gray-600 px-2 py-1 md:px-4 md:py-2 rounded-lg md:rounded-xl text-xs md:text-sm font-medium group-hover:bg-primary-50 group-hover:text-primary-700 transition-colors">
                                                        <i class="fas fa-calendar-alt text-xs"></i>
                                                        <span class="start-date hidden md:inline" data-date="{{ $competition->start_date->format('Y-m-d') }}">{{ $competition->start_date->format('Y-m-d') }}</span>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Stats -->
                                            <div class="grid grid-cols-2 gap-2 md:gap-4 mb-3 md:mb-6">
                                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg md:rounded-2xl p-2 md:p-4 text-center border border-gray-200 group-hover:border-primary-300 group-hover:from-primary-50 group-hover:to-green-50 transition-all">
                                                    <div class="text-lg md:text-3xl font-black text-gray-600 group-hover:text-primary-600 transition-colors">{{ $competition->winners->count() }}</div>
                                                    <div class="text-xs md:text-sm text-gray-500 font-medium mt-1">فائز</div>
                                                </div>
                                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg md:rounded-2xl p-2 md:p-4 text-center border border-gray-200 group-hover:border-primary-300 group-hover:from-primary-50 group-hover:to-green-50 transition-all">
                                                    <div class="text-lg md:text-3xl font-black text-gray-600 group-hover:text-primary-600 transition-colors">{{ $competition->media->count() }}</div>
                                                    <div class="text-xs md:text-sm text-gray-500 font-medium mt-1">وسائط</div>
                                                </div>
                                            </div>

                                            <!-- CTA Button -->
                                            <div class="relative overflow-hidden bg-gradient-to-r from-gray-500 to-gray-600 group-hover:from-primary-500 group-hover:to-primary-600 text-white text-center py-2 px-3 md:py-4 md:px-6 rounded-lg md:rounded-2xl text-xs md:text-base font-bold shadow-lg transition-all">
                                                <span class="relative z-10 flex items-center justify-center gap-1 md:gap-3">
                                                    <span class="hidden md:inline">عرض التفاصيل</span>
                                                    <span class="md:hidden">تفاصيل</span>
                                                    <i class="fas fa-arrow-left text-xs md:text-base group-hover:-translate-x-1 transition-transform"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($currentCompetitions->count() === 0 && $previousCompetitions->count() === 0)
                <!-- Empty State -->
                <section class="py-32">
                    <div class="glass-card rounded-3xl p-16 text-center max-w-2xl mx-auto shadow-2xl border border-gray-100">
                        <h3 class="text-3xl font-black gradient-text mb-4">لا توجد مسابقات متاحة حالياً</h3>
                        <p class="text-gray-500 text-lg">سيتم إضافة المسابقات قريباً</p>
                    </div>
                </section>
            @endif

        </div>
    </main>


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
            // Convert dates
            document.querySelectorAll('.start-date').forEach(function(element) {
                const dateStr = element.getAttribute('data-date');
                const hijriDate = gregorianToHijri(dateStr);
                if (hijriDate) {
                    element.textContent = hijriDate;
                }
            });

            // Intersection Observer for animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.slide-up').forEach(el => {
                el.style.animationPlayState = 'paused';
                observer.observe(el);
            });
        });
    </script>
</body>

</html>
