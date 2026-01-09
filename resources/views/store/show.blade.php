<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - متجر الأسر المنتجة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
            min-height: 100vh;
        }

        h1, h2, h3 {
            font-family: 'Amiri', serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .green-glow {
            box-shadow: 0 0 40px rgba(34, 197, 94, 0.2);
        }

        .gradient-text {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #4ade80 100%);
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

        .price-tag {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 99px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3);
        }

        .feature-card {
            background: white;
            padding: 1rem;
            border-radius: 1rem;
            border-right: 4px solid #22c55e;
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
            box-shadow: 0 20px 40px rgba(34, 197, 94, 0.15);
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
                <li><a href="{{ route('home') }}" class="text-green-600 hover:text-green-700 font-medium">الرئيسية</a></li>
                <li><i class="fas fa-chevron-left text-gray-400 text-[10px]"></i></li>
                <li><a href="{{ route('store.index') }}" class="text-green-600 hover:text-green-700 font-medium">المتجر</a></li>
                @if($product->category)
                    <li><i class="fas fa-chevron-left text-gray-400 text-[10px]"></i></li>
                    <li><a href="{{ route('store.index', ['category' => $product->category->id]) }}" class="text-green-600 hover:text-green-700 font-medium">{{ $product->category->name }}</a></li>
                @endif
                <li><i class="fas fa-chevron-left text-gray-400 text-[10px]"></i></li>
                <li class="text-gray-500">{{ Str::limit($product->name, 20) }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <!-- Left: Product Image & Gallery -->
            <div class="lg:col-span-5 fade-in-up" style="animation-delay: 0.2s">
                <div class="glass-effect rounded-[2.5rem] overflow-hidden green-glow p-4">
                    <div class="product-image-container rounded-3xl" onclick="openLightbox(document.getElementById('mainProductImage').src)">
                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}" 
                                 id="mainProductImage"
                                 alt="{{ $product->name }}"
                                 class="product-image-zoom">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                                <i class="fas fa-image text-green-300 text-8xl opacity-30"></i>
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
                        <div class="w-20 h-20 rounded-2xl overflow-hidden cursor-pointer border-2 border-green-500 shadow-sm transition-all hover:border-green-600"
                             onclick="showImage('{{ asset('storage/' . $product->main_image) }}', this)">
                            <img src="{{ asset('storage/' . $product->main_image) }}" class="w-full h-full object-cover">
                        </div>
                        
                        @foreach($additionalImages as $img)
                            <div class="w-20 h-20 rounded-2xl overflow-hidden cursor-pointer border-2 border-transparent shadow-sm transition-all hover:border-green-400"
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
                            <span class="w-6 h-0.5 bg-green-500 rounded-full"></span>
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
                                    <a href="{{ $youtube->youtube_url }}" target="_blank" class="text-green-600 font-bold underline">مشاهدة الفيديو على يوتيوب</a>
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
                    <div class="absolute -top-24 -left-24 w-64 h-64 bg-green-200/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-emerald-200/20 rounded-full blur-3xl"></div>

                    <div class="relative z-10">
                        <div class="flex flex-wrap gap-2 mb-6">
                            @if($product->category)
                                <span class="bg-green-100/80 text-green-700 px-4 py-1.5 rounded-full text-xs font-bold border border-green-200">{{ $product->category->name }}</span>
                            @endif
                            @if($product->subcategory)
                                <span class="bg-white/80 text-gray-600 px-4 py-1.5 rounded-full text-xs font-medium border border-gray-100">{{ $product->subcategory->name }}</span>
                            @endif
                        </div>

                        <h1 class="text-4xl lg:text-5xl font-black mb-6 text-gray-800 leading-tight">
                            {{ $product->name }}
                        </h1>

                        <div class="flex items-center gap-6 mb-8">
                            <div class="price-tag">
                                <span class="text-3xl font-black">{{ number_format($product->price, 2) }}</span>
                                <span class="text-sm font-bold opacity-80 uppercase tracking-widest">ر.س</span>
                            </div>
                            
                            @if($product->is_rental)
                                <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-xl text-sm font-bold border border-blue-200">
                                    <i class="fas fa-clock ml-2"></i>للايجار
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-4 py-6 border-y border-green-100/50 mb-8">
                            @if($product->owner)
                                <div class="flex items-center gap-3 bg-white/50 px-4 py-2 rounded-2xl border border-white/30">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-500">صاحب المنتج</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $product->owner->first_name }} {{ $product->owner->last_name }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($product->location)
                                <div class="flex items-center gap-3 bg-white/50 px-4 py-2 rounded-2xl border border-white/30">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-orange-600"></i>
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
                                    <span class="w-8 h-1 bg-green-500 rounded-full"></span>
                                    عن المنتج
                                </h3>
                                <p class="text-gray-600 leading-relaxed text-lg">{{ $product->description }}</p>
                            </div>
                        @endif

                        @if($product->features && count($product->features) > 0)
                            <div class="mb-10">
                                <h3 class="text-2xl font-bold mb-4 text-gray-800 flex items-center gap-3">
                                    <span class="w-8 h-1 bg-green-500 rounded-full"></span>
                                    المميزات
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($product->features as $feature)
                                        @if($feature)
                                            <div class="feature-card">
                                                <div class="flex items-center gap-3">
                                                    <i class="fas fa-check-circle text-green-500"></i>
                                                    <span class="text-gray-700 font-medium">{{ $feature }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Contact Grid -->
                        <div class="mt-8">
                             <h3 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-3">
                                <span class="w-8 h-1 bg-green-500 rounded-full"></span>
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

                                @if($product->website_url)
                                    <a href="{{ $product->website_url }}" 
                                       target="_blank"
                                       class="contact-btn bg-white border-2 border-purple-500/20 text-purple-600 px-6 py-4 rounded-[1.5rem] flex items-center gap-4 group">
                                        <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-all">
                                            <i class="fas fa-globe"></i>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">الموقع الشخصي</p>
                                            <p class="font-black">زيارة الموقع</p>
                                        </div>
                                    </a>
                                @endif

                                @if($product->location_url)
                                    <a href="{{ $product->location_url }}" 
                                       target="_blank"
                                       class="contact-btn bg-white border-2 border-red-500/20 text-red-600 px-6 py-4 rounded-[1.5rem] flex items-center gap-4 group">
                                        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-all">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">الموقع</p>
                                            <p class="font-black">عرض على الخريطة</p>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-20 fade-in-up" style="animation-delay: 0.5s">
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-black gradient-text mb-4">أيضاً قد يعجبك</h2>
                    <p class="text-gray-500">منتجات مشابهة من نفس الفئة</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $related)
                        <div class="product-card glass-effect rounded-[2rem] overflow-hidden flex flex-col h-full">
                            <a href="{{ route('store.show', $related) }}" class="block relative group overflow-hidden bg-gray-50 flex items-center justify-center aspect-square">
                                @if($related->main_image)
                                    <img src="{{ asset('storage/' . $related->main_image) }}" 
                                         alt="{{ $related->name }}"
                                         class="max-w-full max-h-full object-contain transition-all duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                                        <i class="fas fa-image text-green-300 text-3xl opacity-50"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-end p-4">
                                     <span class="bg-white/20 backdrop-blur-md text-white px-4 py-2 rounded-full text-xs border border-white/30">عرض التفاصيل</span>
                                </div>
                            </a>
                            <div class="p-6 flex flex-col flex-1">
                                <h4 class="text-lg font-bold mb-3 text-gray-800 line-clamp-2 min-h-[3.5rem]">
                                    <a href="{{ route('store.show', $related) }}" class="hover:text-green-600 transition-colors">
                                        {{ $related->name }}
                                    </a>
                                </h4>
                                <div class="mt-auto flex items-center justify-between">
                                    <span class="text-xl font-black text-green-600">
                                        {{ number_format($related->price, 2) }} <span class="text-xs">ر.س</span>
                                    </span>
                                    <a href="{{ route('store.show', $related) }}" 
                                       class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition-colors shadow-lg shadow-green-200">
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
                thumb.classList.remove('border-green-500');
                thumb.classList.add('border-transparent');
            }
            thumbElement.classList.add('border-green-500');
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
    </script>
</body>

</html>

