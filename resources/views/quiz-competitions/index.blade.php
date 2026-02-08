<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مسابقات الأسئلة - تواصل عائلة السريع</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
            min-height: 100vh;
        }
        h1, h2, h3 { font-family: 'Amiri', serif; }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-in { animation: slideIn 0.6s ease-out forwards; }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .green-glow { box-shadow: 0 0 40px rgba(34, 197, 94, 0.3); }
        .green-glow-hover:hover {
            box-shadow: 0 0 60px rgba(34, 197, 94, 0.5);
            transform: translateY(-5px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #4ade80 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .competition-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(20px);
        }
        .competition-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .competition-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 0 60px rgba(34, 197, 94, 0.4);
        }

        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #f0fdf4; }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e, #16a34a);
            border-radius: 5px;
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    {{-- زخارف الخلفية --}}
    <div class="fixed top-10 right-10 w-96 h-96 opacity-5 pointer-events-none hidden lg:block" style="animation: float 6s ease-in-out infinite;">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#22c55e" d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3Z" transform="translate(100 100)" />
        </svg>
    </div>
    <div class="fixed bottom-10 left-10 w-96 h-96 opacity-5 pointer-events-none hidden lg:block" style="animation: float 5s ease-in-out infinite 1s;">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#4ade80" d="M37.5,-65.2C48.7,-57.8,57.8,-47.3,64.3,-35.1C70.8,-22.9,74.7,-9,75.6,5.7C76.5,20.4,74.4,36,67.1,48.6C59.8,61.2,47.3,70.8,33.2,75.7C19.1,80.6,3.4,80.8,-12.1,78.1C-27.6,75.4,-42.9,69.8,-55.3,60.2C-67.7,50.6,-77.2,37,-80.3,21.9C-83.4,6.8,-80.1,-9.8,-74.1,-25.3C-68.1,-40.8,-59.4,-55.2,-47.2,-62.2C-35,-69.2,-19.3,-68.8,-5.4,-60.5Z" transform="translate(100 100)" />
        </svg>
    </div>

    <div class="container mx-auto px-4 py-6 lg:py-10 relative z-10 max-w-5xl">
        {{-- رابط العودة --}}
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 transition-colors text-sm font-medium">
                <i class="fas fa-arrow-right"></i>
                <span>العودة للصفحة الرئيسية</span>
            </a>
        </div>

        {{-- العنوان --}}
        <div class="glass-effect rounded-3xl green-glow p-5 md:p-8 mb-8 text-center">
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl mx-auto mb-4 flex items-center justify-center bg-gradient-to-br from-green-500 to-green-600 shadow-lg" style="box-shadow: 0 8px 25px rgba(34, 197, 94, 0.4);">
                <i class="fas fa-trophy text-white text-2xl md:text-3xl"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold gradient-text mb-2">مسابقات الأسئلة</h1>
            <p class="text-gray-500 text-sm md:text-base">اختر مسابقة وشارك بالإجابة على الأسئلة واربح جوائز قيمة</p>
        </div>

        {{-- قائمة المسابقات --}}
        @if($competitions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
                @foreach($competitions as $competition)
                    @php
                        $now = now();
                        $activeCount = ($competition->start_at && $competition->end_at && $now->gte($competition->start_at) && $now->lte($competition->end_at)) ? $competition->questions->count() : 0;
                        $upcomingCount = ($competition->start_at && $now->lt($competition->start_at)) ? $competition->questions->count() : 0;
                        $totalQuestions = $competition->questions->count();
                    @endphp
                    <a href="{{ route('quiz-competitions.show', $competition) }}"
                       class="competition-card glass-effect rounded-2xl lg:rounded-3xl overflow-hidden green-glow-hover block relative group">
                        @if($activeCount > 0)
                            <div class="absolute -top-2 -right-2 z-10">
                                <span class="flex h-6 w-6">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-6 w-6 bg-gradient-to-br from-green-500 to-green-600 items-center justify-center text-white text-xs font-bold">{{ $activeCount }}</span>
                                </span>
                            </div>
                        @endif

                        {{-- صورة علوية --}}
                        <div class="h-32 md:h-40 bg-gradient-to-br from-green-100 to-green-200 relative overflow-hidden flex items-center justify-center">
                            <i class="fas fa-trophy text-green-400 text-5xl md:text-6xl opacity-40 group-hover:scale-110 transition-transform duration-500"></i>
                            @if($activeCount > 0)
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center gap-1.5 bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-lg">
                                        <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                        مباشر
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="p-4 md:p-5">
                            <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-2 group-hover:text-green-600 transition-colors">{{ $competition->title }}</h2>
                            @if($competition->description)
                                <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ Str::limit($competition->description, 120) }}</p>
                            @endif

                            <div class="flex flex-wrap items-center gap-3 text-xs mb-4">
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-full border border-green-200">
                                    <i class="fas fa-question-circle"></i>
                                    {{ $totalQuestions }} سؤال
                                </span>
                                @if($activeCount > 0)
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2.5 py-1 rounded-full font-bold border border-green-300">
                                        <i class="fas fa-play-circle"></i>
                                        {{ $activeCount }} نشط الآن
                                    </span>
                                @endif
                                @if($upcomingCount > 0)
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full border border-amber-200">
                                        <i class="fas fa-clock"></i>
                                        {{ $upcomingCount }} قادم
                                    </span>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-green-100">
                                <span class="inline-flex items-center gap-2 text-sm font-bold text-green-600 group-hover:text-green-700">
                                    @if($activeCount > 0)
                                        شارك الآن
                                    @else
                                        عرض المسابقة
                                    @endif
                                    <i class="fas fa-arrow-left transition-transform duration-300 group-hover:-translate-x-1"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center glass-effect p-8 lg:p-16 rounded-3xl green-glow">
                <div class="w-20 h-20 rounded-full mx-auto mb-5 flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100">
                    <i class="fas fa-trophy text-green-300 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">لا توجد مسابقات متاحة حالياً</h3>
                <p class="text-gray-500">ترقبوا المسابقات القادمة</p>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.competition-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => { entry.target.classList.add('visible'); }, index * 100);
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '0px 0px -50px 0px' });
            cards.forEach(card => observer.observe(card));
        });
    </script>
</body>

</html>
