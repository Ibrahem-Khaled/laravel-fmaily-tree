<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>علاقات الرضاعة - معرض صور العائلة</title>

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
                        },
                        heartbeat: {
                            '0%, 100%': {
                                transform: 'scale(1)'
                            },
                            '50%': {
                                transform: 'scale(1.1)'
                            },
                        }
                    },
                    animation: {
                        'float': 'float 8s ease-in-out infinite',
                        'pulse-soft': 'pulse-soft 4s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'heartbeat': 'heartbeat 2s ease-in-out infinite',
                    },
                    boxShadow: {
                        'pink-glow': '0 0 40px rgba(236, 72, 153, 0.3)',
                        'purple-glow': '0 0 40px rgba(147, 51, 234, 0.3)',
                    },
                    colors: {
                        'baby-pink': '#FCE4EC',
                        'baby-blue': '#E3F2FD',
                        'soft-purple': '#F3E8FF',
                        'warm-pink': '#FECACA',
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
            background: #fdf2f8;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #ec4899, #be185d);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #be185d, #9d174d);
        }

        /* تأثيرات إضافية للبطاقات */
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .pink-glow-hover {
            transition: all 0.3s ease;
        }

        .pink-glow-hover:hover {
            box-shadow: 0 0 40px rgba(236, 72, 153, 0.3);
        }

        .purple-glow-hover {
            transition: all 0.3s ease;
        }

        .purple-glow-hover:hover {
            box-shadow: 0 0 40px rgba(147, 51, 234, 0.3);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-pink-50 via-purple-50 to-indigo-50 text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    <div id="readingProgress"
        class="fixed top-0 right-0 h-1 bg-gradient-to-r from-pink-500 to-purple-400 z-50 transition-all duration-300">
    </div>

    <!-- خلفية متحركة -->
    <div class="fixed top-10 left-10 w-96 h-96 opacity-5 z-0 pointer-events-none animate-float hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#ec4899"
                d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3C52.5,-73.2,69.7,-61.8,44.9,-76.6Z"
                transform="translate(100 100)" />
        </svg>
    </div>
    <div
        class="fixed bottom-10 right-10 w-96 h-96 opacity-5 z-0 pointer-events-none animate-pulse-soft hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#a855f7"
                d="M37.5,-65.2C48.7,-57.8,57.8,-47.3,64.3,-35.1C70.8,-22.9,74.7,-9,75.6,5.7C76.5,20.4,74.4,36,67.1,48.6C59.8,61.2,47.3,70.8,33.2,75.7C19.1,80.6,3.4,80.8,-12.1,78.1C-27.6,75.4,-42.9,69.8,-55.3,60.2C-67.7,50.6,-77.2,37,-80.3,21.9C-83.4,6.8,-80.1,-9.8,-74.1,-25.3C-68.1,-40.8,-59.4,-55.2,-47.2,-62.2C-35,-69.2,-19.3,-68.8,-5.4,-60.5C8.5,-52.2,26.3,-72.6,37.5,-65.2Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <main class="container mx-auto px-4 py-8 lg:py-12 relative z-10 max-w-7xl">

        <!-- العنوان الرئيسي -->
        <div class="text-center mb-12">
            <h1
                class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-relaxed font-serif bg-gradient-to-r from-pink-600 via-purple-600 to-indigo-600 text-transparent bg-clip-text">
                <i class="fas fa-baby text-pink-500 animate-heartbeat"></i> علاقات الرضاعة
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                تعرف على الأمهات المرضعات والأطفال المرتضعين في العائلة - نسيج الحب والرعاية
            </p>
        </div>

        <!-- شريط البحث -->
        @if(request('search'))
            <div class="mb-8">
                <div class="glass-effect rounded-2xl p-4 border border-pink-200/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700 font-medium">نتائج البحث عن: "<strong class="text-pink-600">{{ request('search') }}</strong>"</span>
                        </div>
                        <a href="{{ route('breastfeeding.public.index') }}" class="text-pink-600 hover:text-pink-700 font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            مسح البحث
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-12">
            <form method="GET" action="{{ route('breastfeeding.public.index') }}" class="max-w-2xl mx-auto">
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-4 pr-10 py-4 text-lg border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent bg-white/80 backdrop-blur-md"
                        placeholder="البحث في أسماء الأمهات أو الأطفال...">
                    <button type="submit"
                        class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-pink-500 to-purple-600 text-white px-6 py-2 rounded-xl hover:from-pink-600 hover:to-purple-700 transition-all duration-300 font-medium">
                        بحث
                    </button>
                </div>
            </form>
        </div>

        <!-- الإحصائيات -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
            <div class="glass-effect rounded-3xl p-6 text-center purple-glow-hover transform hover:scale-105 transition-all duration-300">
                <div class="text-4xl mb-4">
                    <i class="fas fa-link text-purple-500"></i>
                </div>
                <div class="text-3xl font-bold text-gray-800 mb-2">{{ $stats['total_relationships'] }}</div>
                <div class="text-gray-600 font-medium">إجمالي العلاقات</div>
            </div>
            <div class="glass-effect rounded-3xl p-6 text-center pink-glow-hover transform hover:scale-105 transition-all duration-300">
                <div class="text-4xl mb-4">
                    <i class="fas fa-female text-pink-500"></i>
                </div>
                <div class="text-3xl font-bold text-gray-800 mb-2">{{ $stats['total_nursing_mothers'] }}</div>
                <div class="text-gray-600 font-medium">الأمهات المرضعات</div>
            </div>
            <div class="glass-effect rounded-3xl p-6 text-center purple-glow-hover transform hover:scale-105 transition-all duration-300">
                <div class="text-4xl mb-4">
                    <i class="fas fa-child text-indigo-500"></i>
                </div>
                <div class="text-3xl font-bold text-gray-800 mb-2">{{ $stats['total_breastfed_children'] }}</div>
                <div class="text-gray-600 font-medium">الأطفال المرتضعين</div>
            </div>
            <div class="glass-effect rounded-3xl p-6 text-center pink-glow-hover transform hover:scale-105 transition-all duration-300">
                <div class="text-4xl mb-4">
                    <i class="fas fa-clock text-rose-500"></i>
                </div>
                <div class="text-3xl font-bold text-gray-800 mb-2">{{ $stats['active_breastfeeding'] }}</div>
                <div class="text-gray-600 font-medium">رضاعة مستمرة</div>
            </div>
        </div>

        <!-- الأمهات المرضعات -->
        @if($nursingMothers->isNotEmpty())
            <div class="mb-12">
                <h2
                    class="text-3xl lg:text-4xl font-bold font-serif mb-8 text-center bg-gradient-to-r from-pink-600 to-purple-600 text-transparent bg-clip-text">
                    <i class="fas fa-female text-pink-500"></i> الأمهات المرضعات
                </h2>

                <div class="space-y-8">
                    @foreach($nursingMothers as $motherId => $relationships)
                        @php
                            $mother = $relationships->first()->nursingMother;
                        @endphp
                        <div class="glass-effect rounded-3xl overflow-hidden shadow-pink-glow pink-glow-hover transition-all duration-500 transform hover:-translate-y-2">

                            <!-- رأس بطاقة الأم -->
                            <div class="bg-gradient-to-br from-pink-100/50 to-purple-100/50 p-8">
                                <div class="flex flex-col lg:flex-row items-center gap-6">
                                    <div class="relative">
                                        <img src="{{ $mother->avatar }}" alt="{{ $mother->first_name }}"
                                            class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                                        <div class="absolute -bottom-2 -right-2 bg-pink-500 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-lg">
                                            {{ $relationships->count() }}
                                        </div>
                                    </div>
                                    <div class="text-center lg:text-right flex-grow">
                                        <h3 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2 font-serif">{{ $mother->full_name }}</h3>
                                        <p class="text-gray-600 text-lg mb-4">
                                            <i class="fas fa-baby text-pink-500"></i> أرضعت {{ $relationships->count() }} {{ $relationships->count() == 1 ? 'طفل' : 'أطفال' }}
                                        </p>
                                        <a href="{{ route('breastfeeding.public.show', $mother->id) }}"
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-full hover:from-pink-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 font-medium">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            عرض التفاصيل
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- بطاقات الأطفال -->
                            <div class="p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                    @foreach($relationships as $relationship)
                                        <div class="bg-gradient-to-br from-baby-pink to-warm-pink rounded-2xl p-6 border border-pink-200/50 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                            <div class="text-center">
                                                <img src="{{ $relationship->breastfedChild->avatar }}"
                                                    alt="{{ $relationship->breastfedChild->first_name }}"
                                                    class="w-20 h-20 rounded-full object-cover border-3 border-white shadow-md mx-auto mb-4">
                                                <h4 class="font-bold text-gray-800 mb-2 font-serif">{{ $relationship->breastfedChild->full_name }}</h4>

                                                <a href="{{ route('breastfeeding.public.show', $relationship->breastfedChild->id) }}"
                                                    class="inline-flex items-center gap-1 text-sm text-purple-600 hover:text-purple-700 font-medium mb-3">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    عرض التفاصيل
                                                </a>

                                                @if($relationship->duration_in_months)
                                                    <div class="bg-white/70 rounded-full px-3 py-1 text-sm font-medium text-gray-700 mb-3 inline-block">
                                                        <i class="fas fa-calendar-alt text-pink-500"></i>
                                                        {{ $relationship->duration_in_months }} شهر
                                                    </div>
                                                @endif

                                                <div class="flex justify-center">
                                                    @if($relationship->end_date)
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            مكتملة
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium animate-pulse">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            مستمرة
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($relationship->notes)
                                                    <div class="mt-3">
                                                        <p class="text-xs text-gray-600 bg-white/50 rounded-lg px-2 py-1">
                                                            <i class="fas fa-sticky-note text-pink-400"></i>
                                                            {{ Str::limit($relationship->notes, 50) }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- حالة فارغة -->
            <div class="text-center glass-effect p-16 rounded-3xl shadow-pink-glow max-w-2xl mx-auto">
                <div class="text-6xl text-pink-400 mb-6">
                    <i class="fas fa-baby animate-heartbeat"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 font-serif">لا توجد علاقات رضاعة مسجلة</h3>
                <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                    لم يتم تسجيل أي علاقات رضاعة في النظام بعد.
                    <br>كن أول من يوثق هذه العلاقات المهمة في تاريخ العائلة.
                </p>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    إضافة علاقة رضاعة
                </a>
            </div>
        @endif
    </main>

    <script>
        // شريط التقدم للقراءة
        const progress = document.getElementById('readingProgress');
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            progress.style.width = docHeight > 0 ? `${(scrollTop / docHeight) * 100}%` : '0%';
        });

        // تأثير تحرك البطاقات عند ظهورها
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // مراقبة جميع البطاقات
        document.querySelectorAll('.glass-effect').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>

</html>
