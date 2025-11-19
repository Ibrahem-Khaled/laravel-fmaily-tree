<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر الأسر المنتجة</title>
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
            animation: gradientShift 3s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .product-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s ease;
            z-index: 1;
        }

        .product-card:hover::before {
            left: 100%;
        }

        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(34, 197, 94, 0.4);
        }

        .category-item {
            transition: all 0.3s ease;
        }

        .category-item:hover {
            transform: translateX(-5px);
            background: rgba(34, 197, 94, 0.1);
        }

        .category-item.active {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
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

        .lazy-image {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .lazy-image.loaded {
            opacity: 1;
        }

        .lazy-placeholder {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
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

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #16a34a, #15803d);
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    <div class="container mx-auto px-4 py-4 lg:py-8 relative z-10">
        <!-- Header -->
        <div class="text-center mb-8 fade-in-up">
            <h1 class="text-4xl lg:text-5xl font-black gradient-text mb-4">
                <i class="fas fa-store mr-3"></i>متجر الأسر المنتجة
            </h1>
            <p class="text-lg text-gray-600">اكتشف منتجات عالية الجودة من أسر منتجة محلية</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="w-full lg:w-1/4 space-y-6">
                <!-- Categories Section -->
                <div class="glass-effect p-6 rounded-3xl green-glow sticky top-20">
                    <div class="flex items-center justify-between mb-6 border-b border-green-200 pb-4">
                        <h3 class="text-2xl font-bold gradient-text">
                            <i class="fas fa-tags mr-2"></i>الفئات
                        </h3>
                    </div>

                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('store.index', array_filter(['person' => $personId, 'search' => $search])) }}" 
                               class="category-item block px-4 py-3 rounded-2xl transition-all duration-300 font-medium {{ !$categoryId ? 'active' : 'bg-white/70 hover:bg-green-50' }}">
                                <span class="flex items-center justify-between">
                                    <span><i class="fas fa-th mr-2"></i>جميع المنتجات</span>
                                </span>
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('store.index', array_filter(['category' => $category->id, 'person' => $personId, 'search' => $search])) }}" 
                                   class="category-item block px-4 py-3 rounded-2xl transition-all duration-300 font-medium {{ $categoryId == $category->id ? 'active' : 'bg-white/70 hover:bg-green-50' }}">
                                    <span class="flex items-center justify-between">
                                        <span><i class="fas fa-folder mr-2"></i>{{ $category->name }}</span>
                                        <span class="text-xs bg-white/50 px-2 py-1 rounded-full">{{ $category->products_count }}</span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Product Owners Section -->
                @if($productOwners && $productOwners->count() > 0)
                <div class="glass-effect p-6 rounded-3xl green-glow sticky top-20">
                    <div class="flex items-center justify-between mb-6 border-b border-green-200 pb-4">
                        <h3 class="text-2xl font-bold gradient-text">
                            <i class="fas fa-users mr-2"></i>أصحاب المنتجات
                        </h3>
                    </div>

                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('store.index', array_filter(['category' => $categoryId, 'search' => $search])) }}" 
                               class="category-item block px-4 py-3 rounded-2xl transition-all duration-300 font-medium {{ !$personId ? 'active' : 'bg-white/70 hover:bg-green-50' }}">
                                <span class="flex items-center justify-between">
                                    <span><i class="fas fa-user-friends mr-2"></i>جميع الأشخاص</span>
                                </span>
                            </a>
                        </li>
                        @foreach($productOwners as $owner)
                            <li>
                                <a href="{{ route('store.index', array_filter(['person' => $owner->id, 'category' => $categoryId, 'search' => $search])) }}" 
                                   class="category-item block px-4 py-3 rounded-2xl transition-all duration-300 font-medium {{ $personId == $owner->id ? 'active' : 'bg-white/70 hover:bg-green-50' }}">
                                    <span class="flex items-center justify-between">
                                        <span><i class="fas fa-user mr-2"></i>{{ $owner->full_name }}</span>
                                        <span class="text-xs bg-white/50 px-2 py-1 rounded-full">{{ $owner->products_count }}</span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </aside>

            <!-- Products Section -->
            <main class="w-full lg:w-3/4">
                <!-- Search Bar -->
                <div class="glass-effect p-6 rounded-3xl mb-6 fade-in-up">
                    <form method="GET" action="{{ route('store.index') }}" class="flex gap-3">
                        @if($categoryId)
                            <input type="hidden" name="category" value="{{ $categoryId }}">
                        @endif
                        @if($personId)
                            <input type="hidden" name="person" value="{{ $personId }}">
                        @endif
                        <input type="text" name="search" 
                               class="flex-1 px-6 py-4 rounded-2xl border-2 border-green-200 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-300 transition-all"
                               placeholder="ابحث عن منتج..." value="{{ $search }}">
                        <button type="submit" 
                                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-4 rounded-2xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg transform hover:scale-105 active:scale-95">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Active Filters -->
                @if($categoryId || $personId)
                <div class="glass-effect p-4 rounded-2xl mb-6 fade-in-up">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-medium text-gray-600">الفلترة الحالية:</span>
                        @if($categoryId)
                            @php $selectedCategory = $categories->firstWhere('id', $categoryId); @endphp
                            @if($selectedCategory)
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm flex items-center gap-2">
                                    <i class="fas fa-folder"></i>
                                    {{ $selectedCategory->name }}
                                    <a href="{{ route('store.index', array_filter(['person' => $personId, 'search' => $search])) }}" class="hover:bg-green-600 rounded-full p-1">
                                        <i class="fas fa-times text-xs"></i>
                                    </a>
                                </span>
                            @endif
                        @endif
                        @if($personId && $productOwners)
                            @php $selectedOwner = $productOwners->firstWhere('id', $personId); @endphp
                            @if($selectedOwner)
                                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm flex items-center gap-2">
                                    <i class="fas fa-user"></i>
                                    {{ $selectedOwner->full_name }}
                                    <a href="{{ route('store.index', array_filter(['category' => $categoryId, 'search' => $search])) }}" class="hover:bg-blue-600 rounded-full p-1">
                                        <i class="fas fa-times text-xs"></i>
                                    </a>
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
                @endif

                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="product-card fade-in-up glass-effect rounded-3xl overflow-hidden" style="animation-delay: {{ $loop->index * 0.1 }}s">
                                <a href="{{ route('store.show', $product) }}" class="block relative overflow-hidden">
                                    @if($product->main_image)
                                        <img data-src="{{ asset('storage/' . $product->main_image) }}" 
                                             alt="{{ $product->name }}"
                                             class="lazy-image w-full h-64 object-cover transition-all duration-700 hover:scale-110">
                                        <div class="lazy-placeholder absolute inset-0"></div>
                                    @else
                                        <div class="w-full h-64 bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                                            <i class="fas fa-image text-white text-5xl opacity-50"></i>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 hover:opacity-100 transition-all duration-500"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-full hover:translate-y-0 transition-all duration-500">
                                        <div class="flex gap-2 mb-2">
                                            @if($product->category)
                                                <span class="bg-green-500/80 px-3 py-1 rounded-full text-xs font-medium">{{ $product->category->name }}</span>
                                            @endif
                                            @if($product->subcategory)
                                                <span class="bg-white/20 px-3 py-1 rounded-full text-xs">{{ $product->subcategory->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                
                                <div class="p-6">
                                    <div class="flex gap-2 mb-3">
                                        @if($product->category)
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">{{ $product->category->name }}</span>
                                        @endif
                                        @if($product->subcategory)
                                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">{{ $product->subcategory->name }}</span>
                                        @endif
                                    </div>
                                    
                                    <h3 class="text-xl font-bold mb-2 text-gray-800 line-clamp-2">
                                        <a href="{{ route('store.show', $product) }}" class="hover:text-green-600 transition-colors">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    
                                    @if($product->owner || $product->location)
                                        <div class="flex flex-wrap gap-2 mb-2 text-xs text-gray-600">
                                            @if($product->owner)
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-user text-green-600"></i>
                                                    {{ $product->owner->first_name }} {{ $product->owner->last_name }}
                                                </span>
                                            @endif
                                            @if($product->location)
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-map-marker-alt text-green-600"></i>
                                                    {{ $product->location->name }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if($product->description)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                            {{ Str::limit($product->description, 80) }}
                                        </p>
                                    @endif
                                    
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-2xl font-black text-green-600">
                                            {{ number_format($product->price, 2) }} <span class="text-sm">ر.س</span>
                                        </span>
                                    </div>
                                    
                                    <a href="{{ route('store.show', $product) }}" 
                                       class="block w-full bg-gradient-to-r from-green-500 to-green-600 text-white text-center py-3 px-6 rounded-2xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg transform hover:scale-105 active:scale-95">
                                        <i class="fas fa-eye mr-2"></i>عرض التفاصيل
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="glass-effect rounded-3xl p-12 text-center">
                        <i class="fas fa-shopping-bag text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-2xl font-bold gradient-text mb-3">لا توجد منتجات متاحة حالياً</h3>
                        <p class="text-gray-600">جرب البحث بكلمات مختلفة أو تصفح فئات أخرى</p>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <script>
        // Lazy Loading
        document.addEventListener('DOMContentLoaded', function() {
            const lazyImages = document.querySelectorAll('.lazy-image');
            
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const placeholder = img.nextElementSibling;
                            
                            img.src = img.dataset.src;
                            img.onload = () => {
                                img.classList.add('loaded');
                                if (placeholder) {
                                    placeholder.style.display = 'none';
                                }
                            };
                            
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '100px 0px',
                    threshold: 0.01
                });
                
                lazyImages.forEach(img => imageObserver.observe(img));
            } else {
                lazyImages.forEach(img => {
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                });
            }
        });
    </script>
</body>

</html>
