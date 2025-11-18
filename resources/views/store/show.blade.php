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
            box-shadow: 0 0 40px rgba(34, 197, 94, 0.3);
        }

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

        .product-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(34, 197, 94, 0.4);
        }

        .contact-btn {
            transition: all 0.3s ease;
        }

        .contact-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
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

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f0fdf4;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e, #16a34a);
            border-radius: 5px;
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    <div class="container mx-auto px-4 py-4 lg:py-8 relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-6 fade-in-up">
            <ol class="flex items-center space-x-2 space-x-reverse text-sm">
                <li><a href="{{ route('home') }}" class="text-green-600 hover:text-green-700">الرئيسية</a></li>
                <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                <li><a href="{{ route('store.index') }}" class="text-green-600 hover:text-green-700">المتجر</a></li>
                @if($product->category)
                    <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                    <li><a href="{{ route('store.index', ['category' => $product->category->id]) }}" class="text-green-600 hover:text-green-700">{{ $product->category->name }}</a></li>
                @endif
                @if($product->subcategory)
                    <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                    <li><a href="{{ route('store.subcategory', $product->subcategory) }}" class="text-green-600 hover:text-green-700">{{ $product->subcategory->name }}</a></li>
                @endif
                <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                <li class="text-gray-600">{{ Str::limit($product->name, 30) }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Product Image -->
            <div class="fade-in-up">
                <div class="glass-effect rounded-3xl overflow-hidden green-glow">
                    @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-auto object-cover">
                    @else
                        <div class="w-full h-96 bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                            <i class="fas fa-image text-white text-6xl opacity-50"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="fade-in-up">
                <div class="glass-effect p-8 rounded-3xl green-glow">
                    <div class="flex gap-2 mb-4">
                        @if($product->category)
                            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-medium">{{ $product->category->name }}</span>
                        @endif
                        @if($product->subcategory)
                            <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm">{{ $product->subcategory->name }}</span>
                        @endif
                    </div>

                    <h1 class="text-4xl font-black gradient-text mb-4">{{ $product->name }}</h1>
                    
                    @if($product->owner || $product->location)
                        <div class="flex flex-wrap gap-3 mb-4">
                            @if($product->owner)
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="fas fa-user text-green-600"></i>
                                    <span class="text-sm font-medium">صاحب المنتج: {{ $product->owner->first_name }} {{ $product->owner->last_name }}</span>
                                </div>
                            @endif
                            @if($product->location)
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="fas fa-map-marker-alt text-green-600"></i>
                                    <span class="text-sm font-medium">الموقع: {{ $product->location->name }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    <div class="mb-6">
                        <span class="text-4xl font-black text-green-600">
                            {{ number_format($product->price, 2) }} <span class="text-xl">ر.س</span>
                        </span>
                    </div>

                    @if($product->description)
                        <div class="mb-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-800">الوصف</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                        </div>
                    @endif

                    @if($product->features && count($product->features) > 0)
                        <div class="mb-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-800">المميزات</h3>
                            <ul class="space-y-2">
                                @foreach($product->features as $feature)
                                    @if($feature)
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                                            <span class="text-gray-700">{{ $feature }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Contact Information -->
                    <div class="glass-effect p-6 rounded-2xl border-2 border-green-200">
                        <h3 class="text-xl font-bold mb-4 text-gray-800">
                            <i class="fas fa-phone-alt text-green-600 mr-2"></i>للتواصل والشراء
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @if($product->contact_phone)
                                <a href="tel:{{ $product->contact_phone }}" 
                                   class="contact-btn bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-2xl text-center font-medium flex items-center justify-center gap-2">
                                    <i class="fas fa-phone"></i>
                                    <span>{{ $product->contact_phone }}</span>
                                </a>
                            @endif
                            @if($product->contact_whatsapp)
                                <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $product->contact_whatsapp) }}" 
                                   target="_blank"
                                   class="contact-btn bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-2xl text-center font-medium flex items-center justify-center gap-2">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>واتساب</span>
                                </a>
                            @endif
                            @if($product->contact_email)
                                <a href="mailto:{{ $product->contact_email }}" 
                                   class="contact-btn bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-2xl text-center font-medium flex items-center justify-center gap-2">
                                    <i class="fas fa-envelope"></i>
                                    <span>بريد إلكتروني</span>
                                </a>
                            @endif
                            @if($product->contact_instagram)
                                <a href="https://instagram.com/{{ ltrim($product->contact_instagram, '@') }}" 
                                   target="_blank"
                                   class="contact-btn bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-2xl text-center font-medium flex items-center justify-center gap-2">
                                    <i class="fab fa-instagram"></i>
                                    <span>إنستقرام</span>
                                </a>
                            @endif
                            @if($product->contact_facebook)
                                <a href="{{ $product->contact_facebook }}" 
                                   target="_blank"
                                   class="contact-btn bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl text-center font-medium flex items-center justify-center gap-2">
                                    <i class="fab fa-facebook"></i>
                                    <span>فيسبوك</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-12 fade-in-up">
                <h2 class="text-3xl font-bold gradient-text mb-6 text-center">منتجات مشابهة</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <div class="product-card glass-effect rounded-3xl overflow-hidden">
                            <a href="{{ route('store.show', $related) }}" class="block relative overflow-hidden">
                                @if($related->main_image)
                                    <img src="{{ asset('storage/' . $related->main_image) }}" 
                                         alt="{{ $related->name }}"
                                         class="w-full h-48 object-cover transition-all duration-700 hover:scale-110">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                                        <i class="fas fa-image text-white text-3xl opacity-50"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 hover:opacity-100 transition-all duration-500"></div>
                            </a>
                            <div class="p-4">
                                <h4 class="font-bold mb-2 text-gray-800 line-clamp-2">
                                    <a href="{{ route('store.show', $related) }}" class="hover:text-green-600 transition-colors">
                                        {{ Str::limit($related->name, 40) }}
                                    </a>
                                </h4>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-green-600">
                                        {{ number_format($related->price, 2) }} <span class="text-xs">ر.س</span>
                                    </span>
                                </div>
                                <a href="{{ route('store.show', $related) }}" 
                                   class="block w-full mt-3 bg-gradient-to-r from-green-500 to-green-600 text-white text-center py-2 px-4 rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300">
                                    <i class="fas fa-eye mr-1"></i>عرض
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</body>

</html>
