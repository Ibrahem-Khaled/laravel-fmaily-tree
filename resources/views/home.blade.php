<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرئيسية - تواصل عائلة السريِّع</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Alexandria', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #145147 0%, #37a05c 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .slide-container {
            display: flex;
            transition: transform 1s ease-in-out;
        }
        
        .slide-item {
            min-width: 100%;
            flex-shrink: 0;
        }
        
        .course-card {
            transition: all 0.3s ease;
        }
        
        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #145147 0%, #37a05c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="bg-gray-50">
    @include('partials.main-header')

    {{-- Hero Section with Slideshow --}}
    <section class="relative h-[600px] overflow-hidden gradient-bg">
        <div class="absolute inset-0 slide-container" id="slideContainer">
            @if($latestImages->count() > 0)
                @foreach($latestImages->take(4) as $index => $image)
                    <div class="slide-item h-full relative">
                        <img src="{{ asset('storage/' . $image->path) }}" 
                             alt="{{ $image->name ?? 'صورة' }}" 
                             class="w-full h-full object-cover opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                        @if($image->category)
                            <div class="absolute bottom-20 right-10 left-10">
                                <div class="glass-effect rounded-lg p-6 max-w-2xl">
                                    <h2 class="text-white text-3xl font-bold mb-2">{{ $image->category->name }}</h2>
                                    <p class="text-white/90">{{ $image->name ?? '' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="slide-item h-full flex items-center justify-center">
                    <div class="text-center text-white">
                        <i class="fas fa-images text-6xl mb-4 opacity-50"></i>
                        <p class="text-xl">لا توجد صور في المعرض حالياً</p>
                    </div>
                </div>
            @endif
        </div>
        
        {{-- Navigation Dots --}}
        @if($latestImages->count() > 0)
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
                @foreach($latestImages->take(4) as $index => $image)
                    <button class="w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all slide-dot {{ $index === 0 ? 'bg-white' : '' }}" 
                            onclick="goToSlide({{ $index }})"
                            aria-label="انتقل للشريحة {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Family Brief Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 max-w-6xl">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gradient mb-4">نبذة عن عائلة السريع</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mx-auto"></div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 shadow-lg">
                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! nl2br(e($familyBrief)) !!}
                </div>
            </div>
        </div>
    </section>

    {{-- What's New Section --}}
    <section class="py-20 bg-gray-100">
        <div class="container mx-auto px-6 max-w-6xl">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gradient mb-4">ما الجديد</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mx-auto"></div>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-xl">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 rounded-full gradient-bg flex items-center justify-center">
                            <i class="fas fa-bullhorn text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="prose prose-lg max-w-none text-gray-700">
                            {!! nl2br(e($whatsNew)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Courses Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gradient mb-4">دوراتنا</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mx-auto mb-4"></div>
                <p class="text-gray-600 text-lg">اكتشف مجموعة متنوعة من الدورات التعليمية والثقافية</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($courses as $course)
                    <div class="course-card bg-white rounded-2xl shadow-lg overflow-hidden">
                        {{-- Course Image --}}
                        <div class="relative h-48 bg-gradient-to-br from-green-400 to-emerald-600">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-book-open text-white text-6xl opacity-30"></i>
                            </div>
                            @if(isset($course['image']) && file_exists(public_path(str_replace(asset(''), '', $course['image']))))
                                <img src="{{ $course['image'] }}" alt="{{ $course['title'] }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        
                        {{-- Course Content --}}
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $course['title'] }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $course['description'] }}</p>
                            
                            {{-- Course Info --}}
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-user text-green-500"></i>
                                    <span>{{ $course['instructor'] }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-clock text-green-500"></i>
                                    <span>{{ $course['duration'] }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-users text-green-500"></i>
                                    <span>{{ $course['students'] }} طالب</span>
                                </div>
                            </div>
                            
                            {{-- Enroll Button --}}
                            <button class="w-full py-2 px-4 gradient-bg text-white rounded-lg font-semibold hover:opacity-90 transition-all">
                                <i class="fas fa-plus-circle ml-2"></i>سجل الآن
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="gradient-bg text-white py-12">
        <div class="container mx-auto px-6 max-w-6xl">
            <div class="text-center">
                <h3 class="text-2xl font-bold mb-4">تواصل عائلة السريع</h3>
                <p class="text-white/80 mb-6">صلة القرابة تجمعنا</p>
                <div class="flex justify-center gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/30 transition-all">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/30 transition-all">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/30 transition-all">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Enhanced slideshow functionality
        let currentSlide = 0;
        const slideContainer = document.getElementById('slideContainer');
        const slides = slideContainer?.querySelectorAll('.slide-item') || [];
        const dots = document.querySelectorAll('.slide-dot');
        
        function goToSlide(index) {
            if (slides.length === 0 || index < 0 || index >= slides.length) return;
            
            currentSlide = index;
            const translateValue = -index * 100;
            
            if (slideContainer) {
                slideContainer.style.transform = `translateX(${translateValue}%)`;
            }
            
            // Update active dot
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.remove('bg-white/50');
                    dot.classList.add('bg-white');
                } else {
                    dot.classList.remove('bg-white');
                    dot.classList.add('bg-white/50');
                }
            });
        }
        
        // Auto-advance slides every 5 seconds
        if (slides.length > 0) {
            setInterval(() => {
                currentSlide = (currentSlide + 1) % slides.length;
                goToSlide(currentSlide);
            }, 5000);
            
            // Initialize first slide
            goToSlide(0);
        }
    </script>
</body>

</html>

