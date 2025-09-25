<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>علاقات الرضاعة - {{ $person->full_name }} - معرض صور العائلة</title>

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
                        },
                        slideIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateX(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateX(0)'
                            },
                        }
                    },
                    animation: {
                        'float': 'float 8s ease-in-out infinite',
                        'pulse-soft': 'pulse-soft 4s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'heartbeat': 'heartbeat 2s ease-in-out infinite',
                        'slide-in': 'slideIn 0.5s ease-out',
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

    <main class="container mx-auto px-4 py-8 lg:py-12 relative z-10 max-w-6xl">

        <!-- زر العودة -->
        <div class="mb-8">
            <a href="{{ route('breastfeeding.public.index') }}"
                class="inline-flex items-center gap-3 px-6 py-3 glass-effect rounded-2xl border border-pink-200/50 hover:bg-pink-50/50 transition-all duration-300 group">
                <svg class="w-5 h-5 text-pink-600 transition-transform group-hover:-translate-x-1" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium text-gray-700">العودة لصفحة الرضاعة</span>
            </a>
        </div>

        <!-- رأس الصفحة - معلومات الشخص -->
        <div class="glass-effect rounded-3xl overflow-hidden shadow-pink-glow mb-12">
            <div class="bg-gradient-to-br from-pink-100/50 to-purple-100/50 p-8 lg:p-12">
                <div class="flex flex-col lg:flex-row items-center gap-8">
                    <div class="relative">
                        <img src="{{ $person->avatar }}" alt="{{ $person->first_name }}"
                            class="w-40 h-40 rounded-full object-cover border-6 border-white shadow-xl">
                        <div class="absolute -bottom-4 -right-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg">
                            <i class="fas fa-baby text-2xl animate-heartbeat"></i>
                        </div>
                    </div>
                    <div class="text-center lg:text-right flex-grow">
                        <h1 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-4 font-serif">{{ $person->full_name }}</h1>
                        <div class="flex flex-wrap justify-center lg:justify-start gap-4 text-lg">
                            @if($nursingRelationships->isNotEmpty() && $breastfedRelationships->isNotEmpty())
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-pink-100 text-pink-800 rounded-full font-medium">
                                    <i class="fas fa-female"></i>
                                    أم مرضعة
                                </span>
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-purple-100 text-purple-800 rounded-full font-medium">
                                    <i class="fas fa-child"></i>
                                    طفل مرتضع
                                </span>
                            @elseif($nursingRelationships->isNotEmpty())
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-pink-100 text-pink-800 rounded-full font-medium">
                                    <i class="fas fa-female"></i>
                                    أم مرضعة
                                </span>
                            @elseif($breastfedRelationships->isNotEmpty())
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-purple-100 text-purple-800 rounded-full font-medium">
                                    <i class="fas fa-child"></i>
                                    طفل مرتضع
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-full font-medium">
                                    <i class="fas fa-user"></i>
                                    عضو في العائلة
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- علاقات الرضاعة كأم مرضعة -->
        @if($nursingRelationships->isNotEmpty())
            <div class="mb-16">
                <h2
                    class="text-3xl lg:text-4xl font-bold font-serif mb-8 text-center bg-gradient-to-r from-pink-600 to-purple-600 text-transparent bg-clip-text">
                    <i class="fas fa-female text-pink-500"></i> كأم مرضعة
                </h2>

                <div class="space-y-6">
                    @foreach($nursingRelationships as $index => $relationship)
                        <div class="glass-effect rounded-3xl overflow-hidden pink-glow-hover transition-all duration-500 transform hover:-translate-y-2 animate-slide-in"
                             style="animation-delay: {{ $index * 0.1 }}s">

                            <!-- رأس بطاقة العلاقة -->
                            <div class="bg-gradient-to-br from-pink-50/50 to-warm-pink/30 p-6 lg:p-8">
                                <div class="flex flex-col lg:flex-row items-center gap-6">
                                    <img src="{{ $relationship->breastfedChild->avatar }}"
                                         alt="{{ $relationship->breastfedChild->first_name }}"
                                         class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                                    <div class="text-center lg:text-right flex-grow">
                                        <h3 class="text-2xl font-bold text-gray-800 mb-2 font-serif">{{ $relationship->breastfedChild->full_name }}</h3>
                                        <p class="text-gray-600 text-lg">
                                            <i class="fas fa-child text-purple-500"></i> الطفل المرتضع
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- تفاصيل العلاقة -->
                            <div class="p-6 lg:p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    @if($relationship->start_date)
                                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-4 text-center border border-purple-200/50">
                                            <div class="text-sm text-gray-600 mb-1">تاريخ البداية</div>
                                            <div class="font-bold text-gray-800 text-lg">{{ $relationship->start_date->format('Y/m/d') }}</div>
                                        </div>
                                    @endif

                                    @if($relationship->end_date)
                                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-4 text-center border border-indigo-200/50">
                                            <div class="text-sm text-gray-600 mb-1">تاريخ النهاية</div>
                                            <div class="font-bold text-gray-800 text-lg">{{ $relationship->end_date->format('Y/m/d') }}</div>
                                        </div>
                                    @endif

                                    @if($relationship->duration_in_months)
                                        <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl p-4 text-center border border-pink-200/50">
                                            <div class="text-sm text-gray-600 mb-1">مدة الرضاعة</div>
                                            <div class="font-bold text-gray-800 text-lg">{{ $relationship->duration_in_months }} شهر</div>
                                        </div>
                                    @endif

                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-4 text-center border border-gray-200/50">
                                        <div class="text-sm text-gray-600 mb-1">الحالة</div>
                                        <div class="font-bold">
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
                                    </div>
                                </div>

                                @if($relationship->notes)
                                    <div class="mt-6 p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl border border-yellow-200/50">
                                        <h6 class="font-bold text-gray-700 mb-2 flex items-center gap-2">
                                            <i class="fas fa-sticky-note text-yellow-500"></i>
                                            ملاحظات:
                                        </h6>
                                        <p class="text-gray-700 leading-relaxed">{{ $relationship->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- علاقات الرضاعة كطفل مرتضع -->
        @if($breastfedRelationships->isNotEmpty())
            <div class="mb-16">
                <h2
                    class="text-3xl lg:text-4xl font-bold font-serif mb-8 text-center bg-gradient-to-r from-purple-600 to-indigo-600 text-transparent bg-clip-text">
                    <i class="fas fa-child text-purple-500"></i> كطفل مرتضع
                </h2>

                <div class="space-y-6">
                    @foreach($breastfedRelationships as $index => $relationship)
                        <div class="glass-effect rounded-3xl overflow-hidden purple-glow-hover transition-all duration-500 transform hover:-translate-y-2 animate-slide-in"
                             style="animation-delay: {{ $index * 0.1 }}s">

                            <!-- رأس بطاقة العلاقة -->
                            <div class="bg-gradient-to-br from-purple-50/50 to-indigo-50/30 p-6 lg:p-8">
                                <div class="flex flex-col lg:flex-row items-center gap-6">
                                    <img src="{{ $relationship->nursingMother->avatar }}"
                                         alt="{{ $relationship->nursingMother->first_name }}"
                                         class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                                    <div class="text-center lg:text-right flex-grow">
                                        <h3 class="text-2xl font-bold text-gray-800 mb-2 font-serif">{{ $relationship->nursingMother->full_name }}</h3>
                                        <p class="text-gray-600 text-lg">
                                            <i class="fas fa-female text-pink-500"></i> الأم المرضعة
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- تفاصيل العلاقة -->
                            <div class="p-6 lg:p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    @if($relationship->start_date)
                                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-4 text-center border border-purple-200/50">
                                            <div class="text-sm text-gray-600 mb-1">تاريخ البداية</div>
                                            <div class="font-bold text-gray-800 text-lg">{{ $relationship->start_date->format('Y/m/d') }}</div>
                                        </div>
                                    @endif

                                    @if($relationship->end_date)
                                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-4 text-center border border-indigo-200/50">
                                            <div class="text-sm text-gray-600 mb-1">تاريخ النهاية</div>
                                            <div class="font-bold text-gray-800 text-lg">{{ $relationship->end_date->format('Y/m/d') }}</div>
                                        </div>
                                    @endif

                                    @if($relationship->duration_in_months)
                                        <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl p-4 text-center border border-pink-200/50">
                                            <div class="text-sm text-gray-600 mb-1">مدة الرضاعة</div>
                                            <div class="font-bold text-gray-800 text-lg">{{ $relationship->duration_in_months }} شهر</div>
                                        </div>
                                    @endif

                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-4 text-center border border-gray-200/50">
                                        <div class="text-sm text-gray-600 mb-1">الحالة</div>
                                        <div class="font-bold">
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
                                    </div>
                                </div>

                                @if($relationship->notes)
                                    <div class="mt-6 p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl border border-yellow-200/50">
                                        <h6 class="font-bold text-gray-700 mb-2 flex items-center gap-2">
                                            <i class="fas fa-sticky-note text-yellow-500"></i>
                                            ملاحظات:
                                        </h6>
                                        <p class="text-gray-700 leading-relaxed">{{ $relationship->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- حالة فارغة -->
        @if($nursingRelationships->isEmpty() && $breastfedRelationships->isEmpty())
            <div class="text-center glass-effect p-16 rounded-3xl shadow-pink-glow max-w-2xl mx-auto">
                <div class="text-6xl text-pink-400 mb-6">
                    <i class="fas fa-baby animate-heartbeat"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 font-serif">لا توجد علاقات رضاعة مسجلة</h3>
                <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                    لم يتم تسجيل أي علاقات رضاعة لهذا الشخص في النظام.
                    <br>يمكنك إضافة علاقات الرضاعة من لوحة التحكم.
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
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        // مراقبة جميع البطاقات
        document.querySelectorAll('.glass-effect').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `all 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });
    </script>
</body>

</html>
