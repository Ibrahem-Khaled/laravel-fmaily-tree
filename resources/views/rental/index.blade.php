<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعارة الأدوات الرياضية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%, #fcd34d 100%);
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

        .orange-glow {
            box-shadow: 0 0 40px rgba(251, 146, 60, 0.3);
        }

        .gradient-text {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 50%, #ea580c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .product-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(251, 146, 60, 0.4);
        }

        .category-item {
            transition: all 0.3s ease;
        }

        .category-item:hover {
            transform: translateX(-5px);
            background: rgba(251, 146, 60, 0.1);
        }

        .category-item.active {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            box-shadow: 0 4px 12px rgba(251, 146, 60, 0.3);
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
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    <div class="container mx-auto px-4 py-4 lg:py-8 relative z-10">
        <div class="text-center mb-8 fade-in-up">
            <h1 class="text-4xl lg:text-5xl font-black gradient-text mb-4">
                <i class="fas fa-dumbbell mr-3"></i>استعارة الأدوات الرياضية
            </h1>
            <p class="text-lg text-gray-600">استعِر الأدوات الرياضية التي تحتاجها</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <aside class="w-full lg:w-1/4">
                <div class="sticky top-20 space-y-6">
                    <div class="glass-effect p-6 rounded-3xl orange-glow">
                        <h3 class="text-2xl font-bold gradient-text mb-6 border-b border-orange-200 pb-4">
                            <i class="fas fa-tags mr-2"></i>الفئات
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('rental.index', array_filter(['person' => $personId, 'search' => $search])) }}"
                                   class="category-item block px-4 py-3 rounded-2xl transition-all duration-300 font-medium {{ !$categoryId ? 'active' : 'bg-white/70 hover:bg-orange-50' }}">
                                    <span><i class="fas fa-th mr-2"></i>جميع المنتجات</span>
                                </a>
                            </li>
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('rental.index', array_filter(['category' => $category->id, 'person' => $personId, 'search' => $search])) }}"
                                       class="category-item block px-4 py-3 rounded-2xl transition-all duration-300 font-medium {{ $categoryId == $category->id ? 'active' : 'bg-white/70 hover:bg-orange-50' }}">
                                        <span class="flex items-center justify-between">
                                            <span><i class="fas fa-folder mr-2"></i>{{ $category->name }}</span>
                                            <span class="text-xs bg-white/50 px-2 py-1 rounded-full">{{ $category->products_count }}</span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </aside>

            <main class="w-full lg:w-3/4">
                <div class="glass-effect p-6 rounded-3xl mb-6 fade-in-up">
                    <form method="GET" action="{{ route('rental.index') }}" class="flex gap-3">
                        @if($categoryId)
                            <input type="hidden" name="category" value="{{ $categoryId }}">
                        @endif
                        <input type="text" name="search"
                               class="flex-1 px-6 py-4 rounded-2xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none"
                               placeholder="ابحث عن أداة..." value="{{ $search }}">
                        <button type="submit"
                                class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-2xl hover:from-orange-600 hover:to-orange-700 transition-all">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="product-card fade-in-up glass-effect rounded-3xl overflow-hidden">
                                <a href="{{ route('rental.show', $product) }}" class="block relative overflow-hidden">
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/' . $product->main_image) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-64 object-cover">
                                    @else
                                        <div class="w-full h-64 bg-gradient-to-br from-orange-400 to-red-600 flex items-center justify-center">
                                            <i class="fas fa-dumbbell text-white text-5xl opacity-50"></i>
                                        </div>
                                    @endif
                                </a>

                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2 text-gray-800">
                                        <a href="{{ route('rental.show', $product) }}" class="hover:text-orange-600 transition-colors">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    @if($product->description)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                            {{ Str::limit($product->description, 80) }}
                                        </p>
                                    @endif

                                    <a href="{{ route('rental.show', $product) }}"
                                       class="block w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white text-center py-3 px-6 rounded-2xl hover:from-orange-600 hover:to-orange-700 transition-all">
                                        <i class="fas fa-hand-holding mr-2"></i>طلب الاستعارة
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
                        <i class="fas fa-dumbbell text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-2xl font-bold gradient-text mb-3">لا توجد أدوات متاحة حالياً</h3>
                    </div>
                @endif
            </main>
        </div>
    </div>
</body>

</html>
