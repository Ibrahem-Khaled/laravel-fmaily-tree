<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $competition->title }} - {{ $competition->hijri_year }} هـ - عائلة السريع</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">

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
                        },
                        gold: {
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#d4a856',
                            600: '#b8860b',
                        }
                    },
                    fontFamily: {
                        'alexandria': ['Alexandria', 'sans-serif'],
                        'amiri': ['Amiri', 'serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Alexandria', sans-serif;
        }

        .font-amiri {
            font-family: 'Amiri', serif;
        }

        /* Elegant Background */
        .bg-elegant {
            background: linear-gradient(135deg, #145147 0%, #0d3d35 50%, #0a2f29 100%);
            position: relative;
        }

        .bg-elegant::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .bg-light {
            background: linear-gradient(180deg, #f8fdf9 0%, #f0fdf4 50%, #e8f5e9 100%);
            background-image: 
                radial-gradient(at 20% 30%, rgba(55, 160, 92, 0.08) 0px, transparent 50%),
                radial-gradient(at 80% 20%, rgba(20, 81, 71, 0.06) 0px, transparent 50%),
                radial-gradient(at 50% 70%, rgba(74, 222, 128, 0.05) 0px, transparent 50%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #145147 0%, #37a05c 50%, #4ade80 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gold-text {
            background: linear-gradient(135deg, #d4a856 0%, #fbbf24 50%, #d4a856 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Ornament */
        .ornament {
            background: linear-gradient(90deg, transparent 0%, #d4a856 50%, transparent 100%);
            height: 2px;
            width: 150px;
            margin: 0 auto;
            position: relative;
        }

        .ornament::before {
            content: '✦';
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            color: #d4a856;
            font-size: 1rem;
            background: inherit;
            padding: 0 0.5rem;
        }

        /* Card Effects */
        .card-elegant {
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-elegant::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.7s ease;
            z-index: 10;
            pointer-events: none;
        }

        .card-elegant:hover::before {
            left: 100%;
        }

        .card-elegant:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(20, 81, 71, 0.25);
        }

        .image-zoom {
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .image-zoom:hover {
            transform: scale(1.1);
        }

        /* Winner Card */
        .winner-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .winner-card:hover {
            transform: translateY(-10px) scale(1.02);
        }

        .winner-card:hover .winner-avatar {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 20px 40px rgba(212, 168, 86, 0.4);
        }

        .winner-avatar {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Medal Colors */
        .medal-gold {
            background: linear-gradient(135deg, #fbbf24 0%, #d4a856 50%, #fbbf24 100%);
            box-shadow: 0 8px 25px rgba(251, 191, 36, 0.5);
        }

        .medal-silver {
            background: linear-gradient(135deg, #e5e7eb 0%, #9ca3af 50%, #e5e7eb 100%);
            box-shadow: 0 8px 25px rgba(156, 163, 175, 0.5);
        }

        .medal-bronze {
            background: linear-gradient(135deg, #d97706 0%, #b45309 50%, #d97706 100%);
            box-shadow: 0 8px 25px rgba(217, 119, 6, 0.5);
        }

        /* Media Item */
        .media-item {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .media-item:hover {
            transform: translateY(-8px) scale(1.02);
        }

        .media-item:hover img,
        .media-item:hover video {
            transform: scale(1.1);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(212, 168, 86, 0.3); }
            50% { box-shadow: 0 0 40px rgba(212, 168, 86, 0.6); }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }
        .stagger-6 { animation-delay: 0.6s; }

        /* Section Title */
        .section-title {
            position: relative;
            display: inline-block;
            padding-bottom: 1rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 60%;
            height: 3px;
            background: linear-gradient(90deg, #37a05c, transparent);
            border-radius: 3px;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f0fdf4;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #37a05c, #145147);
            border-radius: 4px;
        }
    </style>

    @include('partials.main-header')
</head>

<body class="font-alexandria min-h-screen">

    <!-- Hero Section -->
    <section class="relative min-h-[60vh] md:min-h-[80vh] overflow-hidden">
        <!-- Background Image -->
        @if($competition->cover_image)
            <img src="{{ $competition->cover_image_url }}" alt="{{ $competition->title }}" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/80"></div>
        @endif

        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-elegant opacity-90"></div>

        <!-- Floating Decorations -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 right-20 w-72 h-72 bg-gold-400/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-20 left-20 w-96 h-96 bg-primary-400/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Back Button -->
        <a href="{{ url()->previous() }}" class="absolute top-4 right-4 md:top-8 md:right-8 z-30 group animate-fadeIn">
            <div class="flex items-center gap-2 md:gap-3 bg-white/10 backdrop-blur-xl border border-white/20 text-white px-4 py-2 md:px-6 md:py-3 rounded-full text-sm md:text-base font-bold shadow-2xl hover:bg-white/20 transition-all hover:scale-105">
                <i class="fas fa-arrow-right text-xs md:text-base group-hover:translate-x-1 transition-transform"></i>
                <span>العودة</span>
            </div>
        </a>

        <!-- Hero Content -->
        <div class="relative z-20 container mx-auto px-4 h-full min-h-[60vh] md:min-h-[80vh] flex flex-col items-center justify-center text-center py-12 md:py-0">
            <!-- Category Badge -->
            @if($competition->category)
                <div class="animate-fadeInUp stagger-1 mb-4">
                    <a href="{{ route('quran-competitions.category', $competition->category->id) }}" class="inline-flex items-center gap-2 bg-gold-500/20 backdrop-blur-sm border border-gold-500/30 text-gold-400 px-4 py-2 rounded-full text-sm font-bold hover:bg-gold-500/30 transition-all">
                        <i class="fas fa-folder"></i>
                        <span>{{ $competition->category->name }}</span>
                    </a>
                </div>
            @endif

            <!-- Year Badge -->
            <div class="animate-fadeInUp stagger-2 mb-6">
                <div class="inline-block bg-gold-500 text-primary-900 px-8 py-3 md:px-12 md:py-4 rounded-2xl text-xl md:text-3xl font-black shadow-2xl animate-pulse-glow">
                    <i class="fas fa-star ml-2"></i>
                    {{ $competition->hijri_year }} هـ
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-5xl lg:text-7xl font-black text-white mb-6 md:mb-8 drop-shadow-2xl tracking-tight max-w-5xl px-2 animate-fadeInUp stagger-3 leading-tight">
                {{ $competition->title }}
            </h1>

            <!-- Description Preview -->
            @if($competition->description)
                <p class="text-white/80 text-base md:text-xl leading-relaxed max-w-3xl mx-auto mb-8 animate-fadeInUp stagger-4 line-clamp-3">
                    {{ Str::limit($competition->description, 200) }}
                </p>
            @endif

            <!-- Quick Stats -->
            <div class="flex flex-wrap justify-center gap-4 md:gap-8 animate-fadeInUp stagger-5">
                <div class="glass-card rounded-2xl px-6 py-4 md:px-8 md:py-5 border border-white/20 shadow-2xl text-center min-w-[100px]">
                    <div class="text-2xl md:text-4xl font-black text-gold-600 mb-1">{{ $competition->winners->count() }}</div>
                    <div class="text-primary-700 font-semibold text-sm md:text-base">
                        <i class="fas fa-trophy ml-1"></i>فائز
                    </div>
                </div>
                <div class="glass-card rounded-2xl px-6 py-4 md:px-8 md:py-5 border border-white/20 shadow-2xl text-center min-w-[100px]">
                    <div class="text-2xl md:text-4xl font-black text-primary-600 mb-1">{{ $competition->media->count() }}</div>
                    <div class="text-primary-700 font-semibold text-sm md:text-base">
                        <i class="fas fa-images ml-1"></i>وسائط
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 120L48 110C96 100 192 80 288 75C384 70 480 80 576 85C672 90 768 90 864 85C960 80 1056 70 1152 70C1248 70 1344 80 1392 85L1440 90V120H1392C1344 120 1248 120 1152 120C1056 120 960 120 864 120C768 120 672 120 576 120C480 120 384 120 288 120C192 120 96 120 48 120H0Z" fill="#f8fdf9"/>
            </svg>
        </div>
    </section>

    <!-- Main Content -->
    <main class="bg-light py-12 md:py-20">
        <div class="container mx-auto px-4">

            <!-- Competition Info Section -->
            <section class="glass-card rounded-3xl p-6 md:p-12 shadow-2xl border border-primary-100 mb-10 md:mb-16 animate-fadeInUp">
                <!-- Section Header -->
                <div class="flex items-center gap-4 md:gap-5 mb-6 md:mb-10 pb-4 md:pb-6 border-b-2 border-primary-100">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                        <i class="fas fa-info-circle text-xl md:text-2xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-4xl font-black gradient-text">معلومات المسابقة</h2>
                        <p class="text-gray-500 text-sm md:text-base mt-1">تفاصيل ومعلومات المسابقة</p>
                    </div>
                </div>

                <!-- Description -->
                @if($competition->description)
                    <div class="bg-gradient-to-br from-primary-50 to-white rounded-2xl p-6 md:p-8 mb-8 md:mb-10 border border-primary-100">
                        <div class="text-gray-700 text-base md:text-lg leading-loose text-justify">
                            {!! nl2br(e($competition->description)) !!}
                        </div>
                    </div>
                @endif

                <!-- Meta Grid -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                    @if($competition->start_date)
                        <div class="bg-gradient-to-br from-white to-primary-50 rounded-2xl p-5 md:p-8 text-center border-2 border-primary-100 hover:border-gold-400 transition-all duration-300 hover:-translate-y-2 group card-elegant">
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl md:rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-calendar-alt text-xl md:text-2xl text-white"></i>
                            </div>
                            <div class="text-gray-500 text-xs md:text-sm font-medium mb-2">تاريخ البداية</div>
                            <div class="text-primary-700 text-sm md:text-lg font-bold start-date" data-date="{{ $competition->start_date->format('Y-m-d') }}">
                                {{ $competition->start_date->format('Y-m-d') }}
                            </div>
                        </div>
                    @endif

                    @if($competition->end_date)
                        <div class="bg-gradient-to-br from-white to-primary-50 rounded-2xl p-5 md:p-8 text-center border-2 border-primary-100 hover:border-gold-400 transition-all duration-300 hover:-translate-y-2 group card-elegant">
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl md:rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-calendar-check text-xl md:text-2xl text-white"></i>
                            </div>
                            <div class="text-gray-500 text-xs md:text-sm font-medium mb-2">تاريخ النهاية</div>
                            <div class="text-primary-700 text-sm md:text-lg font-bold end-date" data-date="{{ $competition->end_date->format('Y-m-d') }}">
                                {{ $competition->end_date->format('Y-m-d') }}
                            </div>
                        </div>
                    @endif

                    <div class="bg-gradient-to-br from-white to-gold-50 rounded-2xl p-5 md:p-8 text-center border-2 border-gold-200 hover:border-gold-400 transition-all duration-300 hover:-translate-y-2 group card-elegant">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-gold-500 to-gold-600 rounded-xl md:rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-trophy text-xl md:text-2xl text-white"></i>
                        </div>
                        <div class="text-gray-500 text-xs md:text-sm font-medium mb-2">عدد الفائزين</div>
                        <div class="text-gold-600 text-2xl md:text-4xl font-black">{{ $competition->winners->count() }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-white to-purple-50 rounded-2xl p-5 md:p-8 text-center border-2 border-purple-200 hover:border-purple-400 transition-all duration-300 hover:-translate-y-2 group card-elegant">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl md:rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-images text-xl md:text-2xl text-white"></i>
                        </div>
                        <div class="text-gray-500 text-xs md:text-sm font-medium mb-2">عدد الوسائط</div>
                        <div class="text-purple-600 text-2xl md:text-4xl font-black">{{ $competition->media->count() }}</div>
                    </div>
                </div>
            </section>

            <!-- Winners Section -->
            @if($competition->winners->count() > 0)
                <section class="glass-card rounded-3xl p-6 md:p-12 shadow-2xl border border-gold-200 mb-10 md:mb-16 animate-fadeInUp stagger-2">
                    <!-- Section Header -->
                    <div class="text-center mb-10 md:mb-14">
                        <div class="inline-block bg-gradient-to-br from-gold-500 to-gold-600 p-4 md:p-6 rounded-2xl md:rounded-3xl shadow-lg shadow-gold-500/30 mb-4 md:mb-6">
                            <i class="fas fa-trophy text-white text-3xl md:text-4xl"></i>
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black gold-text mb-3">الفائزون</h2>
                        <div class="ornament"></div>
                        <p class="text-gray-500 mt-4 text-base md:text-lg">نفخر بتكريم الفائزين المتميزين</p>
                    </div>

                    <!-- Winners Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
                        @foreach($competition->winners->sortBy('position') as $index => $winner)
                            <div class="winner-card group animate-fadeInUp stagger-{{ ($index % 4) + 1 }}">
                                <div class="bg-gradient-to-br from-white to-gold-50 rounded-2xl md:rounded-3xl p-4 md:p-8 text-center border-2 border-gold-100 hover:border-gold-400 shadow-xl hover:shadow-2xl transition-all duration-500 relative overflow-hidden">
                                    <!-- Top Accent -->
                                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-gold-400 via-gold-500 to-gold-400"></div>

                                    <!-- Position Badge -->
                                    @if($winner->position <= 3)
                                        <div class="absolute top-3 left-3 md:top-4 md:left-4">
                                            @if($winner->position == 1)
                                                <div class="w-10 h-10 md:w-12 md:h-12 medal-gold rounded-full flex items-center justify-center border-2 border-white">
                                                    <i class="fas fa-crown text-white text-sm md:text-lg"></i>
                                                </div>
                                            @elseif($winner->position == 2)
                                                <div class="w-10 h-10 md:w-12 md:h-12 medal-silver rounded-full flex items-center justify-center border-2 border-white">
                                                    <i class="fas fa-medal text-white text-sm md:text-lg"></i>
                                                </div>
                                            @else
                                                <div class="w-10 h-10 md:w-12 md:h-12 medal-bronze rounded-full flex items-center justify-center border-2 border-white">
                                                    <i class="fas fa-award text-white text-sm md:text-lg"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Avatar -->
                                    <div class="relative inline-block mb-4 md:mb-6 mt-4 md:mt-2">
                                        <div class="absolute inset-0 bg-gold-400 blur-xl rounded-full scale-125 opacity-30 group-hover:opacity-50 transition-opacity"></div>
                                        <img src="{{ $winner->person->avatar }}" alt="{{ $winner->person->full_name }}" class="winner-avatar relative w-20 h-20 md:w-32 md:h-32 rounded-full object-cover border-4 border-gold-400 shadow-xl">
                                    </div>

                                    <!-- Name -->
                                    <h3 class="text-sm md:text-xl font-bold text-gray-800 mb-3 md:mb-4 group-hover:text-gold-600 transition-colors line-clamp-2">
                                        {{ $winner->person->full_name }}
                                    </h3>

                                    <!-- Position Badge -->
                                    <div class="inline-block bg-gradient-to-r from-gold-500 to-gold-600 text-white px-4 py-1.5 md:px-6 md:py-2 rounded-full text-xs md:text-sm font-bold shadow-lg shadow-gold-500/30">
                                        {{ $winner->position_name }}
                                    </div>

                                    <!-- Category -->
                                    @if($winner->category)
                                        <div class="flex items-center justify-center gap-2 text-primary-600 font-medium mt-3 md:mt-4 text-xs md:text-sm">
                                            <i class="fas fa-star text-gold-500"></i>
                                            <span class="line-clamp-1">{{ $winner->category }}</span>
                                        </div>
                                    @endif

                                    <!-- Notes -->
                                    @if($winner->notes)
                                        <div class="mt-4 pt-4 border-t border-gold-100 text-gray-500 text-xs md:text-sm leading-relaxed line-clamp-2 hidden md:block">
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
                <section class="glass-card rounded-3xl p-6 md:p-12 shadow-2xl border border-purple-200 animate-fadeInUp stagger-3">
                    <!-- Section Header -->
                    <div class="text-center mb-10 md:mb-14">
                        <div class="inline-block bg-gradient-to-br from-purple-500 to-pink-600 p-4 md:p-6 rounded-2xl md:rounded-3xl shadow-lg shadow-purple-500/30 mb-4 md:mb-6">
                            <i class="fas fa-images text-white text-3xl md:text-4xl"></i>
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black gradient-text mb-3">معرض الصور والفيديوهات</h2>
                        <div class="ornament" style="background: linear-gradient(90deg, transparent 0%, #a855f7 50%, transparent 100%);"></div>
                        <p class="text-gray-500 mt-4 text-base md:text-lg">لحظات من ذكريات المسابقة</p>
                    </div>

                    <!-- Media Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                        @foreach($competition->media->sortBy('sort_order') as $index => $media)
                            <div class="media-item group cursor-pointer animate-fadeInUp stagger-{{ ($index % 4) + 1 }}" onclick="openMedia('{{ $media->id }}', '{{ $media->media_type }}', '{{ $media->file_url ?? $media->youtube_url }}')">
                                <div class="relative rounded-xl md:rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border-2 border-transparent hover:border-purple-400">
                                    @if($media->media_type === 'youtube')
                                        <div class="relative h-36 md:h-56 bg-black">
                                            <img src="{{ $media->youtube_thumbnail }}" alt="{{ $media->caption }}" class="w-full h-full object-cover transition-transform duration-700">
                                            <!-- Play Button -->
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition-colors">
                                                <div class="w-12 h-12 md:w-16 md:h-16 bg-red-600 rounded-full flex items-center justify-center shadow-2xl shadow-red-600/50 group-hover:scale-110 transition-transform border-4 border-white/30">
                                                    <i class="fab fa-youtube text-white text-xl md:text-3xl"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($media->media_type === 'video')
                                        <div class="relative h-36 md:h-56 bg-black">
                                            <video src="{{ $media->file_url }}" class="w-full h-full object-cover transition-transform duration-700" preload="metadata"></video>
                                            <!-- Play Button -->
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition-colors">
                                                <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform border-4 border-white/30">
                                                    <i class="fas fa-play text-white text-sm md:text-xl mr-[-2px]"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="relative h-36 md:h-56">
                                            <img src="{{ $media->file_url }}" alt="{{ $media->caption }}" class="w-full h-full object-cover transition-transform duration-700">
                                            <!-- Zoom Icon -->
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/40">
                                                <div class="w-12 h-12 md:w-14 md:h-14 bg-white/90 rounded-full flex items-center justify-center shadow-xl">
                                                    <i class="fas fa-search-plus text-purple-600 text-lg md:text-xl"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Caption -->
                                    @if($media->caption)
                                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/70 to-transparent p-3 md:p-5">
                                            <p class="text-white font-bold text-xs md:text-base drop-shadow-lg line-clamp-2">{{ $media->caption }}</p>
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

    <!-- Back to Top Button -->
    <a href="#" id="backToTop" class="fixed bottom-8 left-8 w-14 h-14 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-full flex items-center justify-center shadow-xl hover:shadow-2xl transition-all hover:scale-110 opacity-0 invisible z-40">
        <i class="fas fa-chevron-up text-xl"></i>
    </a>

    <!-- Media Modal -->
    <div id="mediaModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/95 backdrop-blur-sm">
        <button onclick="closeModal()" class="absolute top-4 right-4 md:top-6 md:right-6 w-12 h-12 md:w-14 md:h-14 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white text-xl md:text-2xl transition-colors z-50">
            <i class="fas fa-times"></i>
        </button>
        <div id="mediaContent" class="max-w-6xl w-full max-h-[90vh] overflow-auto"></div>
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
            // Convert dates
            document.querySelectorAll('.start-date, .end-date').forEach(function(element) {
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

            document.querySelectorAll('.animate-fadeInUp, .animate-fadeIn').forEach(el => {
                el.style.animationPlayState = 'paused';
                observer.observe(el);
            });

            // Back to Top Button
            const backToTop = document.getElementById('backToTop');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 500) {
                    backToTop.classList.remove('opacity-0', 'invisible');
                    backToTop.classList.add('opacity-100', 'visible');
                } else {
                    backToTop.classList.add('opacity-0', 'invisible');
                    backToTop.classList.remove('opacity-100', 'visible');
                }
            });

            backToTop.addEventListener('click', (e) => {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
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
                    content.innerHTML = `<div class="relative rounded-2xl overflow-hidden shadow-2xl" style="padding-bottom: 56.25%;"><iframe class="absolute inset-0 w-full h-full" src="https://www.youtube.com/embed/${videoId[1]}?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>`;
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
