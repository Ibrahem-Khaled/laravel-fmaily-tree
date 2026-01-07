<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - استعارة الأدوات الرياضية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Changa:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Changa', 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 50%, #fed7aa 100%);
            min-height: 100vh;
        }

        h1, h2, h3 {
            font-family: 'Changa', sans-serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .orange-glow {
            box-shadow: 0 0 40px rgba(249, 115, 22, 0.2);
        }

        .gradient-text {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 50%, #ea580c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .product-image-container {
            position: relative;
            overflow: hidden;
            cursor: zoom-in;
            background-color: #f8fafc;
            aspect-ratio: 1/1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image-zoom {
            transition: transform 0.5s ease;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .product-image-container:hover .product-image-zoom {
            transform: scale(1.1);
        }

        .contact-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .contact-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .lightbox {
            display: none;
            position: fixed;
            z-index: 2000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .lightbox.active {
            display: flex;
            opacity: 1;
        }

        .lightbox-img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            box-shadow: 0 0 50px rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .close-lightbox {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            z-index: 2001;
        }

        .rental-tag {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 99px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
        }

        .feature-card {
            background: white;
            padding: 1rem;
            border-radius: 1rem;
            border-right: 4px solid #f97316;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateX(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
        }

        .product-card {
            transition: all 0.4s ease;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(249, 115, 22, 0.15);
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    <!-- Lightbox -->
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <span class="close-lightbox">&times;</span>
        <img id="lightbox-img" class="lightbox-img" src="" alt="">
    </div>

    <div class="container mx-auto px-4 py-8 relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-8 fade-in-up" style="animation-delay: 0.1s">
            <ol class="flex items-center space-x-2 space-x-reverse text-sm bg-white/50 w-fit px-4 py-2 rounded-full backdrop-blur-sm">
                <li><a href="{{ route('home') }}" class="text-orange-600 hover:text-orange-700 font-medium">الرئيسية</a></li>
                <li><i class="fas fa-chevron-left text-gray-400 text-[10px]"></i></li>
                <li><a href="{{ route('rental.index') }}" class="text-orange-600 hover:text-orange-700 font-medium">استعارة الأدوات</a></li>
                @if($product->category)
                    <li><i class="fas fa-chevron-left text-gray-400 text-[10px]"></i></li>
                    <li><a href="{{ route('rental.index', ['category' => $product->category->id]) }}" class="text-orange-600 hover:text-orange-700 font-medium">{{ $product->category->name }}</a></li>
                @endif
                <li><i class="fas fa-chevron-left text-gray-400 text-[10px]"></i></li>
                <li class="text-gray-500">{{ Str::limit($product->name, 20) }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <!-- Left: Product Image & Gallery -->
            <div class="lg:col-span-5 fade-in-up" style="animation-delay: 0.2s">
                <div class="glass-effect rounded-[2.5rem] overflow-hidden orange-glow p-4">
                    <div class="product-image-container rounded-3xl" onclick="openLightbox(document.getElementById('mainProductImage').src)">
                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}"
                                 id="mainProductImage"
                                 alt="{{ $product->name }}"
                                 class="product-image-zoom">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center">
                                <i class="fas fa-image text-orange-300 text-8xl opacity-30"></i>
                            </div>
                        @endif
                        <div class="absolute bottom-4 right-4 bg-black/50 text-white p-2 rounded-full backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                </div>

                @php
                    $additionalImages = $product->media->where('media_type', 'image');
                @endphp

                @if($additionalImages->count() > 0)
                    <div class="mt-6 flex flex-wrap gap-3">
                        <!-- Main Image Thumbnail -->
                        <div class="w-20 h-20 rounded-2xl overflow-hidden cursor-pointer border-2 border-orange-500 shadow-sm transition-all hover:border-orange-600"
                             onclick="showImage('{{ asset('storage/' . $product->main_image) }}', this)">
                            <img src="{{ asset('storage/' . $product->main_image) }}" class="w-full h-full object-cover">
                        </div>

                        @foreach($additionalImages as $img)
                            <div class="w-20 h-20 rounded-2xl overflow-hidden cursor-pointer border-2 border-transparent shadow-sm transition-all hover:border-orange-400"
                                 onclick="showImage('{{ asset('storage/' . $img->file_path) }}', this)">
                                <img src="{{ asset('storage/' . $img->file_path) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-500 italic"><i class="fas fa-info-circle ml-1"></i>انقر على الصورة للتكبير</p>
                </div>

                <!-- Video Section -->
                @php
                    $video = $product->media->where('media_type', 'video')->first();
                    $youtube = $product->media->where('media_type', 'youtube')->first();
                @endphp

                @if($video || $youtube)
                    <div class="mt-6">
                        <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">
                            <span class="w-6 h-0.5 bg-orange-500 rounded-full"></span>
                            فيديو توضيحي
                        </h3>
                        <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/50 bg-black/5 aspect-video flex items-center justify-center">
                            @if($video)
                                <video controls class="w-full h-full object-contain bg-black">
                                    <source src="{{ asset('storage/' . $video->file_path) }}" type="video/mp4">
                                    المتصفح لا يدعم تشغيل الفيديو.
                                </video>
                            @elseif($youtube)
                                @php
                                    $videoId = $youtube->extractVideoId($youtube->youtube_url);
                                @endphp
                                @if($videoId)
                                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @else
                                    <a href="{{ $youtube->youtube_url }}" target="_blank" class="text-orange-600 font-bold underline">مشاهدة الفيديو على يوتيوب</a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right: Product Details -->
            <div class="lg:col-span-7 fade-in-up" style="animation-delay: 0.3s">
                <div class="glass-effect p-8 lg:p-12 rounded-[2.5rem] shadow-xl relative overflow-hidden">
                    <!-- Decorative Background element -->
                    <div class="absolute -top-24 -left-24 w-64 h-64 bg-orange-200/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-amber-200/20 rounded-full blur-3xl"></div>

                    <div class="relative z-10">
                        <div class="flex flex-wrap gap-2 mb-6">
                            @if($product->category)
                                <span class="bg-orange-100/80 text-orange-700 px-4 py-1.5 rounded-full text-xs font-bold border border-orange-200">{{ $product->category->name }}</span>
                            @endif
                            @if($product->subcategory)
                                <span class="bg-white/80 text-gray-600 px-4 py-1.5 rounded-full text-xs font-medium border border-gray-100">{{ $product->subcategory->name }}</span>
                            @endif
                        </div>

                        <h1 class="text-4xl lg:text-5xl font-black mb-6 text-gray-800 leading-tight">
                            {{ $product->name }}
                        </h1>

                        <div class="flex items-center gap-6 mb-8">
                            <div class="rental-tag">
                                <i class="fas fa-hand-holding"></i>
                                <span class="text-lg font-bold">متاح للاستعارة</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4 py-6 border-y border-orange-100/50 mb-8">
                            @if($product->owner)
                                <div class="flex items-center gap-3 bg-white/50 px-4 py-2 rounded-2xl border border-white/30">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-orange-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-500">صاحب الأداة</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $product->owner->first_name }} {{ $product->owner->last_name }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($product->location)
                                <div class="flex items-center gap-3 bg-white/50 px-4 py-2 rounded-2xl border border-white/30">
                                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-amber-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-500">الموقع</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $product->location->name }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($product->description)
                            <div class="mb-10">
                                <h3 class="text-2xl font-bold mb-4 text-gray-800 flex items-center gap-3">
                                    <span class="w-8 h-1 bg-orange-500 rounded-full"></span>
                                    عن الأداة
                                </h3>
                                <p class="text-gray-600 leading-relaxed text-lg">{{ $product->description }}</p>
                            </div>
                        @endif

                        @if($product->features && count($product->features) > 0)
                            <div class="mb-10">
                                <h3 class="text-2xl font-bold mb-4 text-gray-800 flex items-center gap-3">
                                    <span class="w-8 h-1 bg-orange-500 rounded-full"></span>
                                    المميزات
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($product->features as $feature)
                                        @if($feature)
                                            <div class="feature-card">
                                                <div class="flex items-center gap-3">
                                                    <i class="fas fa-check-circle text-orange-500"></i>
                                                    <span class="text-gray-700 font-medium">{{ $feature }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- المواعيد المتاحة -->
                        <div class="mb-10">
                            <h3 class="text-2xl font-bold mb-4 text-gray-800 flex items-center gap-3">
                                <span class="w-8 h-1 bg-orange-500 rounded-full"></span>
                                <i class="fas fa-clock text-orange-500"></i>
                                المواعيد المتاحة
                            </h3>

                            @if($product->available_all_week)
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 mb-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-calendar-check text-white text-2xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-800 mb-1">متاح طوال الأسبوع</h4>
                                            @if($product->all_week_start_time && $product->all_week_end_time)
                                                <p class="text-gray-600">
                                                    من <span class="font-bold text-green-700">{{ \Carbon\Carbon::parse($product->all_week_start_time)->format('g:i A') }}</span>
                                                    إلى <span class="font-bold text-green-700">{{ \Carbon\Carbon::parse($product->all_week_end_time)->format('g:i A') }}</span>
                                                </p>
                                            @else
                                                <p class="text-gray-600">متاح في جميع الأوقات</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @elseif($product->availabilitySlots && $product->availabilitySlots->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @php
                                        $daysOrder = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                                        $daysNames = [
                                            'saturday' => 'السبت',
                                            'sunday' => 'الأحد',
                                            'monday' => 'الإثنين',
                                            'tuesday' => 'الثلاثاء',
                                            'wednesday' => 'الأربعاء',
                                            'thursday' => 'الخميس',
                                            'friday' => 'الجمعة'
                                        ];
                                        $daysIcons = [
                                            'saturday' => 'fas fa-calendar-day',
                                            'sunday' => 'fas fa-calendar-day',
                                            'monday' => 'fas fa-calendar-day',
                                            'tuesday' => 'fas fa-calendar-day',
                                            'wednesday' => 'fas fa-calendar-day',
                                            'thursday' => 'fas fa-calendar-day',
                                            'friday' => 'fas fa-calendar-day'
                                        ];
                                        $sortedSlots = $product->availabilitySlots->sortBy(function($slot) use ($daysOrder) {
                                            return array_search($slot->day_of_week, $daysOrder);
                                        });
                                    @endphp
                                    @foreach($sortedSlots->groupBy('day_of_week') as $day => $slots)
                                        @if($slots->where('is_active', true)->count() > 0)
                                            <div class="feature-card">
                                                <div class="flex items-start gap-3">
                                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                        <i class="{{ $daysIcons[$day] ?? 'fas fa-calendar' }} text-orange-600"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="font-bold text-gray-800 mb-2">{{ $daysNames[$day] ?? $day }}</h4>
                                                        @foreach($slots->where('is_active', true) as $slot)
                                                            <p class="text-sm text-gray-600 mb-1">
                                                                <i class="fas fa-clock text-orange-500 ml-1"></i>
                                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} -
                                                                {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                                            </p>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-calendar-check text-white text-2xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-800 mb-1">متاح طوال الأسبوع</h4>
                                            <p class="text-gray-600">يمكنك التواصل في أي وقت</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Contact Grid -->
                        @if($product->contact_phone || $product->contact_whatsapp || $product->contact_email || $product->contact_instagram || $product->contact_facebook)
                        <div class="mt-8 mb-10">
                             <h3 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-3">
                                <span class="w-8 h-1 bg-orange-500 rounded-full"></span>
                                تواصل الآن
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                @if($product->contact_phone)
                                    <a href="tel:{{ $product->contact_phone }}"
                                       class="contact-btn bg-white border-2 border-blue-500/20 text-blue-600 px-6 py-4 rounded-[1.5rem] flex items-center gap-4 group">
                                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">إتصال هاتف</p>
                                            <p class="font-black ltr">{{ $product->contact_phone }}</p>
                                        </div>
                                    </a>
                                @endif

                                @if($product->contact_whatsapp)
                                    <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $product->contact_whatsapp) }}"
                                       target="_blank"
                                       class="contact-btn bg-white border-2 border-green-500/20 text-green-600 px-6 py-4 rounded-[1.5rem] flex items-center gap-4 group">
                                        <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-all">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">واتساب</p>
                                            <p class="font-black">مراسلة فورية</p>
                                        </div>
                                    </a>
                                @endif

                                @if($product->contact_email)
                                    <a href="mailto:{{ $product->contact_email }}"
                                       class="contact-btn bg-white border-2 border-indigo-500/20 text-indigo-600 px-6 py-4 rounded-[1.5rem] flex items-center gap-4 group">
                                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">البريد</p>
                                            <p class="font-black">ارسل إيميل</p>
                                        </div>
                                    </a>
                                @endif

                                @if($product->contact_instagram)
                                    <a href="https://instagram.com/{{ ltrim($product->contact_instagram, '@') }}"
                                       target="_blank"
                                       class="contact-btn bg-white border-2 border-pink-500/20 text-pink-600 px-6 py-4 rounded-[1.5rem] flex items-center gap-4 group">
                                        <div class="w-12 h-12 bg-pink-50 rounded-2xl flex items-center justify-center group-hover:bg-pink-600 group-hover:text-white transition-all">
                                            <i class="fab fa-instagram"></i>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">إنستقرام</p>
                                            <p class="font-black">@ {{ ltrim($product->contact_instagram, '@') }}</p>
                                        </div>
                                    </a>
                                @endif

                                @if($product->contact_facebook)
                                    <a href="{{ $product->contact_facebook }}"
                                       target="_blank"
                                       class="contact-btn bg-white border-2 border-blue-800/20 text-blue-800 px-6 py-4 rounded-[1.5rem] flex items-center gap-4 group">
                                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:bg-blue-800 group-hover:text-white transition-all">
                                            <i class="fab fa-facebook-f"></i>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">فيسبوك</p>
                                            <p class="font-black">صفحة الفيسبوك</p>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Rental Request Form -->
        <div class="mt-12 fade-in-up" style="animation-delay: 0.4s">
            <div class="glass-effect p-8 lg:p-12 rounded-[2.5rem] shadow-xl relative overflow-hidden orange-glow">
                <!-- Decorative Background element -->
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange-200/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-amber-200/20 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <h2 class="text-3xl font-black gradient-text mb-6 flex items-center gap-3">
                        <span class="w-8 h-1 bg-orange-500 rounded-full"></span>
                        <i class="fas fa-hand-holding"></i>
                        طلب استعارة الأداة
                    </h2>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('rental.request', $product) }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">الاسم <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">رقم الهاتف <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">تاريخ بداية الاستعارة <span class="text-red-500">*</span></label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">تاريخ نهاية الاستعارة <span class="text-red-500">*</span></label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all" required>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-gray-700 font-bold mb-2">ملاحظات إضافية</label>
                            <textarea name="notes" rows="4"
                                      class="w-full px-4 py-3 rounded-xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all">{{ old('notes') }}</textarea>
                        </div>

                        <div class="mt-8">
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 px-8 rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all text-lg font-bold shadow-lg shadow-orange-200 hover:shadow-orange-300 transform hover:-translate-y-1">
                                <i class="fas fa-paper-plane mr-2"></i>إرسال طلب الاستعارة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-20 fade-in-up" style="animation-delay: 0.5s">
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-black gradient-text mb-4">أيضاً قد يعجبك</h2>
                    <p class="text-gray-500">أدوات مشابهة من نفس الفئة</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $related)
                        <div class="product-card glass-effect rounded-[2rem] overflow-hidden flex flex-col h-full">
                            <a href="{{ route('rental.show', $related) }}" class="block relative group overflow-hidden bg-gray-50 flex items-center justify-center aspect-square">
                                @if($related->main_image)
                                    <img src="{{ asset('storage/' . $related->main_image) }}"
                                         alt="{{ $related->name }}"
                                         class="max-w-full max-h-full object-contain transition-all duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center">
                                        <i class="fas fa-image text-orange-300 text-3xl opacity-50"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-end p-4">
                                     <span class="bg-white/20 backdrop-blur-md text-white px-4 py-2 rounded-full text-xs border border-white/30">عرض التفاصيل</span>
                                </div>
                            </a>
                            <div class="p-6 flex flex-col flex-1">
                                <h4 class="text-lg font-bold mb-3 text-gray-800 line-clamp-2 min-h-[3.5rem]">
                                    <a href="{{ route('rental.show', $related) }}" class="hover:text-orange-600 transition-colors">
                                        {{ $related->name }}
                                    </a>
                                </h4>
                                <div class="mt-auto flex items-center justify-between">
                                    <span class="text-sm font-bold text-orange-600">
                                        متاح للاستعارة
                                    </span>
                                    <a href="{{ route('rental.show', $related) }}"
                                       class="w-10 h-10 bg-orange-500 text-white rounded-full flex items-center justify-center hover:bg-orange-600 transition-colors shadow-lg shadow-orange-200">
                                        <i class="fas fa-chevron-left text-sm"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function showImage(src, thumbElement) {
            const mainImg = document.getElementById('mainProductImage');
            mainImg.style.opacity = '0';

            setTimeout(() => {
                mainImg.src = src;
                mainImg.style.opacity = '1';
            }, 200);

            // Update border colors
            const thumbnails = thumbElement.parentElement.children;
            for (let thumb of thumbnails) {
                thumb.classList.remove('border-orange-500');
                thumb.classList.add('border-transparent');
            }
            thumbElement.classList.add('border-orange-500');
            thumbElement.classList.remove('border-transparent');
        }

        function openLightbox(src) {
            const lightbox = document.getElementById('lightbox');
            const img = document.getElementById('lightbox-img');
            img.src = src;
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") closeLightbox();
        });

        // Set minimum end_date based on start_date
        document.querySelector('input[name="start_date"]')?.addEventListener('change', function() {
            const startDate = this.value;
            const endDateInput = document.querySelector('input[name="end_date"]');
            if (startDate && endDateInput) {
                const nextDay = new Date(startDate);
                nextDay.setDate(nextDay.getDate() + 1);
                endDateInput.min = nextDay.toISOString().split('T')[0];
            }
        });
    </script>
</body>

</html>
