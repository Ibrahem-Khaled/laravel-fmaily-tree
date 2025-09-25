<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قريباً - علاقات الرضاعة - معرض صور العائلة</title>

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
                        bounce: {
                            '0%, 20%, 53%, 80%, 100%': {
                                transform: 'translate3d(0,0,0)'
                            },
                            '40%, 43%': {
                                transform: 'translate3d(0, -30px, 0)'
                            },
                            '70%': {
                                transform: 'translate3d(0, -15px, 0)'
                            },
                            '90%': {
                                transform: 'translate3d(0, -4px, 0)'
                            },
                        },
                        shimmer: {
                            '0%': {
                                backgroundPosition: '-200% 0'
                            },
                            '100%': {
                                backgroundPosition: '200% 0'
                            },
                        }
                    },
                    animation: {
                        'float': 'float 8s ease-in-out infinite',
                        'pulse-soft': 'pulse-soft 4s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'heartbeat': 'heartbeat 2s ease-in-out infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'shimmer': 'shimmer 2s linear infinite',
                    },
                    boxShadow: {
                        'pink-glow': '0 0 40px rgba(236, 72, 153, 0.3)',
                        'purple-glow': '0 0 40px rgba(147, 51, 234, 0.3)',
                        'rainbow': '0 0 60px rgba(147, 51, 234, 0.4), 0 0 100px rgba(236, 72, 153, 0.3)',
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

        /* تأثيرات إضافية */
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .shimmer-text {
            background: linear-gradient(90deg, #ec4899, #a855f7, #3b82f6, #a855f7, #ec4899);
            background-size: 200% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s ease-in-out infinite;
        }

        .floating-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            display: block;
            pointer-events: none;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ec4899, #a855f7);
            animation: float-particles 15s infinite linear;
            opacity: 0.7;
        }

        .particle:nth-child(1) { left: 20%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 40%; animation-delay: 2s; }
        .particle:nth-child(3) { left: 60%; animation-delay: 4s; }
        .particle:nth-child(4) { left: 80%; animation-delay: 6s; }
        .particle:nth-child(5) { left: 10%; animation-delay: 8s; }

        @keyframes float-particles {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 0.7;
            }
            90% {
                opacity: 0.7;
            }
            100% {
                transform: translateY(-100vh) scale(1);
                opacity: 0;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-pink-50 via-purple-50 to-indigo-50 text-gray-800 relative overflow-x-hidden min-h-screen">
    @include('partials.main-header')

    <!-- خلفية متحركة مع جسيمات عائمة -->
    <div class="floating-particles">
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
    </div>

    <!-- خلفية متحركة -->
    <div class="fixed top-10 left-10 w-96 h-96 opacity-10 z-0 pointer-events-none animate-float hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#ec4899"
                d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3C52.5,-73.2,69.7,-61.8,44.9,-76.6Z"
                transform="translate(100 100)" />
        </svg>
    </div>
    <div
        class="fixed bottom-10 right-10 w-96 h-96 opacity-10 z-0 pointer-events-none animate-pulse-soft hidden lg:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#a855f7"
                d="M37.5,-65.2C48.7,-57.8,57.8,-47.3,64.3,-35.1C70.8,-22.9,74.7,-9,75.6,5.7C76.5,20.4,74.4,36,67.1,48.6C59.8,61.2,47.3,70.8,33.2,75.7C19.1,80.6,3.4,80.8,-12.1,78.1C-27.6,75.4,-42.9,69.8,-55.3,60.2C-67.7,50.6,-77.2,37,-80.3,21.9C-83.4,6.8,-80.1,-9.8,-74.1,-25.3C-68.1,-40.8,-59.4,-55.2,-47.2,-62.2C-35,-69.2,-19.3,-68.8,-5.4,-60.5C8.5,-52.2,26.3,-72.6,37.5,-65.2Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <main class="min-h-screen flex items-center justify-center relative z-10 px-4 py-12">
        <div class="text-center max-w-4xl mx-auto">
            <!-- الأيقونة الرئيسية -->
            <div class="mb-12">
                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full glass-effect shadow-rainbow mb-8 animate-bounce-slow">
                    <i class="fas fa-baby text-6xl shimmer-text animate-heartbeat"></i>
                </div>
            </div>

            <!-- العنوان الرئيسي -->
            <h1 class="text-6xl sm:text-7xl lg:text-8xl font-bold mb-8 font-serif">
                <span class="shimmer-text">قريباً</span>
            </h1>

            <!-- العنوان الفرعي -->
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-6 leading-relaxed font-serif bg-gradient-to-r from-pink-600 via-purple-600 to-indigo-600 text-transparent bg-clip-text">
                علاقات الرضاعة
            </h2>

            <!-- الوصف -->
            <p class="text-xl sm:text-2xl text-gray-600 mb-12 leading-relaxed max-w-3xl mx-auto">
                نعمل بجد لإنشاء تجربة رائعة لتوثيق وعرض علاقات الرضاعة في العائلة
                <br>
                <span class="text-lg text-gray-500 mt-4 block">ترقبوا إطلاق هذه الميزة قريباً بإذن الله</span>
            </p>

            <!-- مؤشر التقدم -->
            <div class="mb-12">
                <div class="glass-effect rounded-2xl p-8 max-w-2xl mx-auto">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">جاري العمل على...</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">تصميم واجهة المستخدم</span>
                            <div class="w-1/2 bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-pink-500 to-purple-600 h-2 rounded-full w-4/5 animate-pulse"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">قاعدة البيانات</span>
                            <div class="w-1/2 bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-pink-500 to-purple-600 h-2 rounded-full w-3/5 animate-pulse"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">الاختبارات النهائية</span>
                            <div class="w-1/2 bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-pink-500 to-purple-600 h-2 rounded-full w-2/5 animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- أزرار التنقل -->
            <div class="flex flex-col sm:flex-row justify-center gap-6">
                <a href="{{ route('old.family-tree') }}"
                    class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-2xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 group">
                    <svg class="w-6 h-6 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    العودة للرئيسية
                </a>

                <a href="{{ route('gallery.articles') }}"
                    class="inline-flex items-center gap-3 px-8 py-4 glass-effect border border-pink-200/50 text-gray-700 rounded-2xl font-bold text-lg hover:bg-pink-50/50 transition-all duration-300 group">
                    <svg class="w-6 h-6 text-pink-500 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    تصفح المقالات
                </a>
            </div>

            <!-- معلومات الاتصال أو التحديثات -->
            <div class="mt-16">
                <p class="text-gray-500 text-sm">
                    سيتم إشعاركم عند توفر هذه الميزة
                </p>
            </div>
        </div>
    </main>

    <script>
        // تأثير تحرك العناصر عند ظهورها
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

        // مراقبة جميع العناصر
        document.querySelectorAll('h1, h2, p, .glass-effect').forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = `all 0.6s ease ${index * 0.1}s`;
            observer.observe(element);
        });

        // تأثير الجسيمات العشوائية
        function createRandomParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            document.querySelector('.floating-particles').appendChild(particle);

            // إزالة الجسيم بعد انتهاء الحركة
            setTimeout(() => {
                particle.remove();
            }, 20000);
        }

        // إضافة جسيمات عشوائية كل فترة
        setInterval(createRandomParticle, 3000);
    </script>
</body>

</html>
