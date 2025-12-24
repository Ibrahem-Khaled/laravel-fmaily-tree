<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مسابقات {{ $category->name }} - عائلة السريع</title>
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
            background: linear-gradient(180deg, #145147 0%, #0d3d35 100%);
            position: relative;
        }

        .bg-elegant::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .bg-light {
            background: linear-gradient(135deg, #f8fdf9 0%, #e8f5e9 50%, #f0fdf4 100%);
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

        /* Decorative Elements */
        .ornament {
            background: linear-gradient(90deg, transparent 0%, #d4a856 50%, transparent 100%);
            height: 2px;
            width: 200px;
            margin: 0 auto;
            position: relative;
        }

        .ornament::before {
            content: '❋';
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            color: #d4a856;
            font-size: 1.5rem;
            background: inherit;
            padding: 0 0.5rem;
        }

        /* Stats Cards */
        .stat-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
            border: 1px solid rgba(212, 168, 86, 0.3);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            background: linear-gradient(135deg, rgba(212, 168, 86, 0.1) 0%, rgba(212, 168, 86, 0.05) 100%);
            border-color: #d4a856;
            transform: scale(1.05);
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

        @keyframes slideRight {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-slideRight {
            animation: slideRight 0.6s ease-out forwards;
            opacity: 0;
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }
        .stagger-6 { animation-delay: 0.6s; }

        /* Section Titles */
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

        /* Badge Styles */
        .badge-elegant {
            background: linear-gradient(135deg, #145147 0%, #1a6b5a 100%);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 9999px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(20, 81, 71, 0.3);
        }

        .badge-gold {
            background: linear-gradient(135deg, #d4a856 0%, #fbbf24 100%);
            color: #145147;
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
    <section class="bg-elegant py-16 md:py-24 relative overflow-hidden">
        <!-- Decorative Circles -->
        <div class="absolute top-10 right-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-80 h-80 bg-primary-400/10 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-4 relative z-10">
            <!-- Breadcrumb -->
            <nav class="mb-8 animate-fadeIn">
                <ol class="flex items-center justify-center gap-3 text-sm md:text-base text-white/70">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors flex items-center gap-2">
                            <i class="fas fa-home"></i>
                            <span>الرئيسية</span>
                        </a>
                    </li>
                    <li><i class="fas fa-chevron-left text-xs text-gold-500"></i></li>
                    <li>
                        <a href="{{ route('quran-competitions.index') }}" class="hover:text-white transition-colors">المسابقات</a>
                    </li>
                    <li><i class="fas fa-chevron-left text-xs text-gold-500"></i></li>
                    <li class="text-gold-400 font-semibold">{{ $category->name }}</li>
                </ol>
            </nav>

            <!-- Category Title -->
            <div class="text-center max-w-4xl mx-auto">
                <div class="animate-fadeInUp stagger-1">
                    <span class="badge-gold inline-block mb-6 text-sm md:text-base">
                        <i class="fas fa-quran mr-2"></i>
                        مسابقات القرآن الكريم
                    </span>
                </div>

                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white mb-6 animate-fadeInUp stagger-2 leading-tight">
                    {{ $category->name }}
                </h1>

                @if($category->description)
                    <p class="text-white/80 text-lg md:text-xl leading-relaxed mb-8 animate-fadeInUp stagger-3 max-w-3xl mx-auto">
                        {{ $category->description }}
                    </p>
                @endif

                <!-- Category Stats -->
                <div class="flex flex-wrap justify-center gap-4 md:gap-8 animate-fadeInUp stagger-4">
                    <div class="stat-card px-6 py-4 rounded-2xl text-center">
                        <div class="text-3xl md:text-4xl font-black text-primary-700">{{ $currentCompetitions->count() + $previousCompetitions->count() }}</div>
                        <div class="text-sm text-gray-600 font-medium">إجمالي المسابقات</div>
                    </div>
                    <div class="stat-card px-6 py-4 rounded-2xl text-center">
                        <div class="text-3xl md:text-4xl font-black text-gold-600">{{ $currentCompetitions->count() }}</div>
                        <div class="text-sm text-gray-600 font-medium">مسابقات حالية</div>
                    </div>
                    <div class="stat-card px-6 py-4 rounded-2xl text-center">
                        <div class="text-3xl md:text-4xl font-black text-primary-600">{{ $previousCompetitions->count() }}</div>
                        <div class="text-sm text-gray-600 font-medium">مسابقات سابقة</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f8fdf9"/>
            </svg>
        </div>
    </section>

    <!-- Main Content -->
    <main class="bg-light py-16 md:py-20">
        <div class="container mx-auto px-4">

            @if($currentCompetitions->count() > 0)
                <!-- Current Competitions Section -->
                <section class="mb-20">
                    <div class="text-center mb-12 md:mb-16">
                        <h2 class="text-3xl md:text-5xl font-black gradient-text section-title animate-fadeInUp">
                            <i class="fas fa-star text-gold-500 ml-3"></i>
                            المسابقات الجارية
                        </h2>
                        <div class="ornament mt-6"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-10">
                        @foreach($currentCompetitions as $index => $competition)
                            <article class="animate-fadeInUp stagger-{{ ($index % 3) + 1 }} group">
                                <a href="{{ route('quran-competitions.show', $competition->id) }}" class="block h-full">
                                    <div class="glass-card rounded-3xl overflow-hidden shadow-xl card-elegant h-full flex flex-col border border-primary-100">

                                        <!-- Image Container -->
                                        <div class="relative h-56 md:h-72 overflow-hidden">
                                            @if($competition->cover_image)
                                                <img src="{{ $competition->cover_image_url }}" alt="{{ $competition->title }}" class="w-full h-full object-cover image-zoom">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-primary-400 via-primary-500 to-primary-700 flex items-center justify-center">
                                                    <div class="text-center text-white">
                                                        <i class="fas fa-quran text-6xl mb-4 opacity-50"></i>
                                                        <div class="text-3xl font-bold">{{ $competition->hijri_year }} هـ</div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Gradient Overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                                            <!-- Year Badge -->
                                            <div class="absolute top-4 right-4 bg-gold-500 text-primary-900 px-4 py-2 rounded-xl font-bold text-sm shadow-lg">
                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                {{ $competition->hijri_year }} هـ
                                            </div>

                                            <!-- Active Badge -->
                                            <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg animate-pulse">
                                                <i class="fas fa-circle text-[6px] mr-1"></i>
                                                جارية الآن
                                            </div>

                                            <!-- Title on Image -->
                                            <div class="absolute bottom-4 right-4 left-4">
                                                <h3 class="text-xl md:text-2xl font-bold text-white line-clamp-2 drop-shadow-lg">
                                                    {{ $competition->title }}
                                                </h3>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="p-6 md:p-8 flex-1 flex flex-col">
                                            @if($competition->description)
                                                <p class="text-gray-600 text-sm md:text-base leading-relaxed mb-6 line-clamp-3 flex-1">
                                                    {{ Str::limit($competition->description, 150) }}
                                                </p>
                                            @endif

                                            <!-- Stats Row -->
                                            <div class="flex items-center justify-between gap-4 mb-6">
                                                <div class="flex items-center gap-2 text-primary-700">
                                                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                                                        <i class="fas fa-trophy text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-xl font-black">{{ $competition->winners->count() }}</div>
                                                        <div class="text-xs text-gray-500">فائز</div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2 text-gold-600">
                                                    <div class="w-10 h-10 bg-gold-100 rounded-xl flex items-center justify-center">
                                                        <i class="fas fa-images text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-xl font-black">{{ $competition->media->count() }}</div>
                                                        <div class="text-xs text-gray-500">وسائط</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- CTA Button -->
                                            <div class="bg-gradient-to-r from-primary-600 to-primary-700 group-hover:from-primary-700 group-hover:to-primary-800 text-white text-center py-4 px-6 rounded-2xl font-bold text-base shadow-lg shadow-primary-500/30 group-hover:shadow-xl transition-all duration-300">
                                                <span class="flex items-center justify-center gap-3">
                                                    <span>عرض التفاصيل</span>
                                                    <i class="fas fa-arrow-left group-hover:-translate-x-2 transition-transform"></i>
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

            @if($previousCompetitions->count() > 0)
                <!-- Previous Competitions Section -->
                <section class="mb-16">
                    <div class="text-center mb-12 md:mb-16">
                        <h2 class="text-3xl md:text-5xl font-black text-gray-700 section-title animate-fadeInUp">
                            <i class="fas fa-history text-gray-400 ml-3"></i>
                            المسابقات السابقة
                        </h2>
                        <div class="ornament mt-6" style="background: linear-gradient(90deg, transparent 0%, #9ca3af 50%, transparent 100%);"></div>
                        <p class="text-gray-500 mt-4 text-lg">أرشيف المسابقات المنتهية</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                        @foreach($previousCompetitions as $index => $competition)
                            <article class="animate-fadeInUp stagger-{{ ($index % 3) + 1 }} group">
                                <a href="{{ route('quran-competitions.show', $competition->id) }}" class="block h-full">
                                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 h-full flex flex-col border border-gray-200 hover:border-primary-300 card-elegant">

                                        <!-- Image Container -->
                                        <div class="relative h-48 md:h-56 overflow-hidden">
                                            @if($competition->cover_image)
                                                <img src="{{ $competition->cover_image_url }}" alt="{{ $competition->title }}" class="w-full h-full object-cover grayscale-[40%] group-hover:grayscale-0 transition-all duration-700 image-zoom">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-gray-300 via-gray-400 to-gray-500 flex items-center justify-center">
                                                    <div class="text-center text-white">
                                                        <i class="fas fa-quran text-5xl mb-3 opacity-50"></i>
                                                        <div class="text-2xl font-bold">{{ $competition->hijri_year }} هـ</div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Gradient Overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                                            <!-- Year Badge -->
                                            <div class="absolute top-4 right-4 bg-white/90 text-gray-700 px-3 py-1 rounded-lg font-bold text-sm shadow-lg group-hover:bg-gold-500 group-hover:text-primary-900 transition-all">
                                                {{ $competition->hijri_year }} هـ
                                            </div>

                                            <!-- Completed Badge -->
                                            <div class="absolute top-4 left-4 bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                                                <i class="fas fa-check mr-1"></i>
                                                منتهية
                                            </div>

                                            <!-- Title on Image -->
                                            <div class="absolute bottom-4 right-4 left-4">
                                                <h3 class="text-lg md:text-xl font-bold text-white line-clamp-2 drop-shadow-lg">
                                                    {{ $competition->title }}
                                                </h3>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="p-5 md:p-6 flex-1 flex flex-col">
                                            <!-- Stats Row -->
                                            <div class="flex items-center justify-between gap-4 mb-4">
                                                <div class="flex items-center gap-2 text-gray-600 group-hover:text-primary-700 transition-colors">
                                                    <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary-100 rounded-lg flex items-center justify-center transition-colors">
                                                        <i class="fas fa-trophy"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-lg font-bold">{{ $competition->winners->count() }}</div>
                                                        <div class="text-xs text-gray-400">فائز</div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2 text-gray-600 group-hover:text-gold-600 transition-colors">
                                                    <div class="w-9 h-9 bg-gray-100 group-hover:bg-gold-100 rounded-lg flex items-center justify-center transition-colors">
                                                        <i class="fas fa-images"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-lg font-bold">{{ $competition->media->count() }}</div>
                                                        <div class="text-xs text-gray-400">وسائط</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- CTA Button -->
                                            <div class="mt-auto bg-gray-100 group-hover:bg-gradient-to-r group-hover:from-primary-600 group-hover:to-primary-700 text-gray-600 group-hover:text-white text-center py-3 px-5 rounded-xl font-semibold text-sm transition-all duration-300">
                                                <span class="flex items-center justify-center gap-2">
                                                    <span>عرض التفاصيل</span>
                                                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
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
                <section class="py-20">
                    <div class="glass-card rounded-3xl p-12 md:p-20 text-center max-w-2xl mx-auto shadow-2xl border border-primary-100">
                        <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <i class="fas fa-quran text-4xl text-primary-500"></i>
                        </div>
                        <h3 class="text-3xl md:text-4xl font-black gradient-text mb-6">لا توجد مسابقات متاحة</h3>
                        <p class="text-gray-500 text-lg mb-8">سيتم إضافة مسابقات جديدة قريباً بإذن الله</p>
                        <a href="{{ route('quran-competitions.index') }}" class="inline-flex items-center gap-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:shadow-xl transition-all hover:scale-105">
                            <i class="fas fa-arrow-right"></i>
                            <span>العودة للمسابقات</span>
                        </a>
                    </div>
                </section>
            @endif

        </div>
    </main>

    <!-- Back to Top Button -->
    <a href="#" id="backToTop" class="fixed bottom-8 left-8 w-14 h-14 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-full flex items-center justify-center shadow-xl hover:shadow-2xl transition-all hover:scale-110 opacity-0 invisible z-50">
        <i class="fas fa-chevron-up text-xl"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.animate-fadeInUp, .animate-fadeIn, .animate-slideRight').forEach(el => {
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
    </script>
</body>

</html>
