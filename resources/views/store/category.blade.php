<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - متجر الأسر المنتجة</title>
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

        .subcategory-card {
            transition: all 0.3s ease;
        }

        .subcategory-card:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 30px rgba(34, 197, 94, 0.3);
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
                <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                <li class="text-gray-600">{{ $category->name }}</li>
            </ol>
        </nav>

        <!-- Category Header -->
        <div class="text-center mb-8 fade-in-up">
            @if($category->image)
                <div class="glass-effect rounded-3xl overflow-hidden mb-6 inline-block green-glow">
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         alt="{{ $category->name }}" 
                         class="max-h-64 object-cover">
                </div>
            @endif
            <h1 class="text-4xl lg:text-5xl font-black gradient-text mb-4">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $category->description }}</p>
            @endif
        </div>

        <!-- Subcategories -->
        @if($category->subcategories->count() > 0)
            <div class="mb-12 fade-in-up">
                <h2 class="text-2xl font-bold gradient-text mb-6 text-center">
                    <i class="fas fa-layer-group mr-2"></i>الفئات الفرعية
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($category->subcategories as $subcategory)
                        <a href="{{ route('store.subcategory', $subcategory) }}" 
                           class="subcategory-card glass-effect rounded-3xl overflow-hidden p-6 text-center green-glow-hover">
                            @if($subcategory->image)
                                <img src="{{ asset('storage/' . $subcategory->image) }}" 
                                     alt="{{ $subcategory->name }}" 
                                     class="w-full h-32 object-cover rounded-2xl mb-4">
                            @else
                                <div class="w-full h-32 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl mb-4 flex items-center justify-center">
                                    <i class="fas fa-folder text-white text-3xl opacity-50"></i>
                                </div>
                            @endif
                            <h3 class="font-bold text-gray-800">{{ $subcategory->name }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Products -->
        <div class="fade-in-up">
            <h2 class="text-2xl font-bold gradient-text mb-6 text-center">
                المنتجات <span class="text-gray-600">({{ $products->total() }})</span>
            </h2>

            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="product-card glass-effect rounded-3xl overflow-hidden">
                            <a href="{{ route('store.show', $product) }}" class="block relative overflow-hidden">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-64 object-cover transition-all duration-700 hover:scale-110">
                                @else
                                    <div class="w-full h-64 bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                                        <i class="fas fa-image text-white text-5xl opacity-50"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 hover:opacity-100 transition-all duration-500"></div>
                            </a>
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2 text-gray-800 line-clamp-2">
                                    <a href="{{ route('store.show', $product) }}" class="hover:text-green-600 transition-colors">
                                        {{ $product->name }}
                                    </a>
                                </h3>
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
                    <h3 class="text-2xl font-bold gradient-text mb-3">لا توجد منتجات في هذه الفئة حالياً</h3>
                </div>
            @endif
        </div>
    </div>
</body>

</html>
