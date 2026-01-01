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
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Changa:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Changa', 'Tajawal', sans-serif;
            background: #f8fdfb;
            min-height: 100vh;
            overflow-x: hidden;
            color: #1f2937;
        }

        /* الخلفية المتحركة - النسخة الفاتحة */
        .animated-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(16, 185, 129, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(52, 211, 153, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(110, 231, 183, 0.05) 0%, transparent 40%),
                linear-gradient(180deg, #f0fdf9 0%, #ecfdf5 50%, #f8fdfb 100%);
        }

        .floating-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: float 20s ease-in-out infinite;
        }

        .orb-1 {
            width: 500px;
            height: 500px;
            background: rgba(16, 185, 129, 0.08);
            top: 5%;
            right: 5%;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 400px;
            height: 400px;
            background: rgba(52, 211, 153, 0.06);
            bottom: 15%;
            left: 10%;
            animation-delay: -7s;
        }

        .orb-3 {
            width: 350px;
            height: 350px;
            background: rgba(110, 231, 183, 0.07);
            top: 45%;
            left: 45%;
            animation-delay: -14s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(20px, 10px) scale(1.02); }
        }

        /* شبكة الخطوط */
        .grid-pattern {
            position: fixed;
            inset: 0;
            z-index: 1;
            background-image:
                linear-gradient(rgba(16, 185, 129, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16, 185, 129, 0.04) 1px, transparent 1px);
            background-size: 50px 50px;
            mask-image: radial-gradient(ellipse at center, black 20%, transparent 70%);
        }

        /* العنوان الرئيسي */
        .main-title {
            font-family: 'Changa', sans-serif;
            font-weight: 800;
            background: linear-gradient(135deg, #047857 0%, #059669 30%, #10b981 50%, #059669 70%, #047857 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 4s linear infinite;
        }

        @keyframes shimmer {
            0% { background-position: 200% center; }
            100% { background-position: -200% center; }
        }

        /* بطاقات الفئات */
        .category-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: white;
            border: 1px solid rgba(16, 185, 129, 0.15);
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            cursor: pointer;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .category-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, transparent 50%, rgba(16, 185, 129, 0.03) 100%);
            opacity: 0;
            transition: opacity 0.5s ease;
            z-index: 1;
        }

        .category-card:hover::before {
            opacity: 1;
        }

        .category-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: rgba(16, 185, 129, 0.4);
            box-shadow:
                0 25px 50px -12px rgba(16, 185, 129, 0.2),
                0 0 0 1px rgba(16, 185, 129, 0.1);
        }

        .category-card.active {
            border-color: #10b981;
            background: linear-gradient(145deg, rgba(16, 185, 129, 0.08) 0%, rgba(255, 255, 255, 1) 100%);
            box-shadow:
                0 10px 40px rgba(16, 185, 129, 0.2),
                inset 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .category-image-wrapper {
            position: relative;
            height: 100px;
            overflow: hidden;
        }

        .category-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .category-card:hover .category-image {
            transform: scale(1.1);
        }

        .category-image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 30%, rgba(255, 255, 255, 0.9) 100%);
        }

        .category-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 50%, #6ee7b7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-placeholder i {
            font-size: 2rem;
            color: rgba(5, 150, 105, 0.4);
        }

        .category-content {
            padding: 0.65rem 0.75rem;
            position: relative;
            z-index: 2;
            background: white;
        }

        .category-card.active .category-content {
            background: transparent;
        }

        .category-name {
            font-family: 'Changa', sans-serif;
            font-weight: 700;
            font-size: 0.8rem;
            color: #065f46;
            margin-bottom: 0.25rem;
            transition: color 0.3s ease;
            line-height: 1.4;
        }

        .category-card:hover .category-name {
            color: #059669;
        }

        .category-description {
            font-size: 0.7rem;
            color: #6b7280;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.15rem;
        }

        .category-count {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.6rem;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 16px;
            font-size: 0.65rem;
            color: #059669;
            font-weight: 600;
            margin-top: 0.35rem;
        }

        .category-card.active .category-count {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-color: transparent;
        }

        /* بطاقات المنتجات */
        .product-card {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.08), transparent);
            transition: left 0.7s ease;
            z-index: 1;
        }

        .product-card:hover::before {
            left: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            border-color: rgba(16, 185, 129, 0.3);
            box-shadow:
                0 30px 60px -15px rgba(16, 185, 129, 0.15),
                0 0 0 1px rgba(16, 185, 129, 0.1);
        }

        .product-image-wrapper {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 1rem;
            transition: transform 0.7s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .product-card:hover .product-image {
            transform: scale(1.08);
        }

        .product-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 60%, rgba(255, 255, 255, 0.95) 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .product-content {
            padding: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .product-category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.85rem;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid rgba(16, 185, 129, 0.15);
            border-radius: 20px;
            font-size: 0.7rem;
            color: #059669;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .product-name {
            font-family: 'Changa', sans-serif;
            font-weight: 600;
            font-size: 1.15rem;
            color: #1f2937;
            margin-bottom: 0.5rem;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.3s ease;
        }

        .product-card:hover .product-name {
            color: #059669;
        }

        .product-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.8rem;
            color: #6b7280;
        }

        .product-meta-item {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .product-meta-item i {
            color: #10b981;
        }

        .product-description {
            font-size: 0.85rem;
            color: #9ca3af;
            line-height: 1.6;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-family: 'Changa', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: #059669;
            margin-bottom: 1rem;
        }

        .product-price span {
            font-size: 0.85rem;
            font-weight: 500;
            color: #10b981;
        }

        /* زر عرض التفاصيل */
        .view-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 14px;
            color: white;
            font-family: 'Changa', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
        }

        .view-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .view-btn:hover::before {
            opacity: 1;
        }

        .view-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -5px rgba(16, 185, 129, 0.4);
        }

        .view-btn span,
        .view-btn i {
            position: relative;
            z-index: 1;
        }

        /* الفلاتر النشطة */
        .active-filter {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid rgba(16, 185, 129, 0.25);
            border-radius: 30px;
            color: #059669;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .active-filter:hover {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        }

        .active-filter .remove-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background: rgba(5, 150, 105, 0.15);
            border-radius: 50%;
            color: #059669;
            transition: all 0.3s ease;
        }

        .active-filter .remove-btn:hover {
            background: #ef4444;
            color: white;
        }

        /* رسالة لا توجد منتجات */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 2px solid rgba(16, 185, 129, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-icon i {
            font-size: 3rem;
            color: #10b981;
        }

        /* التمرير */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f0fdf9;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #10b981, #059669);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #059669, #047857);
        }

        /* الأنيميشن */
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }
        .stagger-6 { animation-delay: 0.6s; }

        /* Lazy loading */
        .lazy-image {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .lazy-image.loaded {
            opacity: 1;
        }

        .lazy-placeholder {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: shimmerLoading 1.5s infinite;
        }

        @keyframes shimmerLoading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Section titles */
        .section-title {
            font-family: 'Changa', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: #065f46;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: linear-gradient(180deg, #10b981, #059669);
            border-radius: 2px;
        }

        /* رسالة الترحيب */
        .welcome-message {
            text-align: center;
            padding: 3rem;
            background: white;
            border: 1px solid rgba(16, 185, 129, 0.15);
            border-radius: 24px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 30px rgba(16, 185, 129, 0.08);
        }

        .welcome-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
        }

        .welcome-icon i {
            font-size: 2rem;
            color: white;
        }

        .welcome-title {
            font-family: 'Changa', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: #065f46;
            margin-bottom: 0.75rem;
        }

        .welcome-text {
            color: #6b7280;
            font-size: 1rem;
            max-width: 400px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* نص الوصف */
        .subtitle-text {
            color: #6b7280;
        }

        /* عنوان الفلاتر */
        .filter-label {
            color: #6b7280;
        }
    </style>
</head>

<body class="relative">
    <!-- الخلفية المتحركة -->
    <div class="animated-bg">
        <div class="floating-orb orb-1"></div>
        <div class="floating-orb orb-2"></div>
        <div class="floating-orb orb-3"></div>
    </div>
    <div class="grid-pattern"></div>

    @include('partials.main-header')

    <div class="container mx-auto px-4 py-8 lg:py-12 relative z-10">
        <!-- العنوان الرئيسي -->
        <div class="text-center mb-12 fade-in">
            <h1 class="main-title text-4xl lg:text-6xl mb-4">
                متاجر الأسر المنتجة
            </h1>
            <p class="text-lg lg:text-xl subtitle-text font-medium max-w-2xl mx-auto leading-relaxed">
                ( مجموعة من متاجر الأسر المنتجة لعائلة السريع )
            </p>
        </div>

        <!-- قسم الفئات -->
        <div class="mb-12">
            <h2 class="section-title fade-in">
                <i class="fas fa-th-large text-emerald-500"></i>
                تصفح حسب الفئة
            </h2>

            <div class="grid grid-cols-2 gap-3 lg:gap-4">
                @foreach($categories as $index => $category)
                    <a href="{{ route('store.index', array_filter(['category' => $category->id, 'person' => $personId, 'search' => $search])) }}"
                       class="category-card fade-in stagger-{{ ($index % 6) + 1 }} {{ $categoryId == $category->id ? 'active' : '' }}">

                        <div class="category-image-wrapper">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}"
                                     alt="{{ $category->name }}"
                                     class="category-image">
                            @else
                                <div class="category-placeholder">
                                    <i class="fas fa-store"></i>
                                </div>
                            @endif
                            <div class="category-image-overlay"></div>

                            @if($categoryId == $category->id)
                                <div class="absolute top-2 left-2 w-5 h-5 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center z-10 shadow-lg">
                                    <i class="fas fa-check text-white text-[8px]"></i>
                                </div>
                            @endif
                        </div>

                        <div class="category-content">
                            <h3 class="category-name">{{ $category->name }}</h3>
                            @if($category->description)
                                <p class="category-description">{{ $category->description }}</p>
                            @endif
                            <div class="category-count">
                                <i class="fas fa-box-open text-xs"></i>
                                <span>{{ $category->products_count }} منتج</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- الفلاتر النشطة -->
        @if($categoryId || $personId)
        <div class="flex items-center gap-3 flex-wrap mb-8 fade-in">
            <span class="filter-label text-sm font-medium">
                <i class="fas fa-filter ml-2 text-emerald-500"></i>الفلترة الحالية:
            </span>
            @if($categoryId)
                @php $selectedCategory = $categories->firstWhere('id', $categoryId); @endphp
                @if($selectedCategory)
                    <div class="active-filter">
                        <i class="fas fa-folder"></i>
                        <span>{{ $selectedCategory->name }}</span>
                        <a href="{{ route('store.index', array_filter(['person' => $personId, 'search' => $search])) }}"
                           class="remove-btn">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </div>
                @endif
            @endif
            @if($personId && $productOwners)
                @php $selectedOwner = $productOwners->firstWhere('id', $personId); @endphp
                @if($selectedOwner)
                    <div class="active-filter">
                        <i class="fas fa-user"></i>
                        <span>{{ $selectedOwner->full_name }}</span>
                        <a href="{{ route('store.index', array_filter(['category' => $categoryId, 'search' => $search])) }}"
                           class="remove-btn">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </div>
                @endif
            @endif
        </div>
        @endif

        <!-- قسم المنتجات -->
        @if($products->count() > 0)
            <div class="mb-8">
                <h2 class="section-title fade-in">
                    <i class="fas fa-shopping-bag text-emerald-500"></i>
                    المنتجات المتاحة
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $index => $product)
                    <div class="product-card fade-in" style="animation-delay: {{ ($index % 8) * 0.1 }}s">
                        <a href="{{ route('store.show', $product) }}" class="block">
                            <div class="product-image-wrapper">
                                @if($product->main_image)
                                    <img data-src="{{ asset('storage/' . $product->main_image) }}"
                                         alt="{{ $product->name }}"
                                         class="product-image lazy-image">
                                    <div class="lazy-placeholder absolute inset-0"></div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-emerald-300 text-5xl"></i>
                                    </div>
                                @endif
                                <div class="product-overlay"></div>
                            </div>
                        </a>

                        <div class="product-content">
                            @if($product->category)
                                <span class="product-category-badge">
                                    <i class="fas fa-tag text-xs"></i>
                                    {{ $product->category->name }}
                                </span>
                            @endif

                            <h3 class="product-name">
                                <a href="{{ route('store.show', $product) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            @if($product->owner || $product->location)
                                <div class="product-meta">
                                    @if($product->owner)
                                        <span class="product-meta-item">
                                            <i class="fas fa-user"></i>
                                            {{ $product->owner->first_name }} {{ $product->owner->last_name }}
                                        </span>
                                    @endif
                                    @if($product->location)
                                        <span class="product-meta-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $product->location->name }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            @if($product->description)
                                <p class="product-description">
                                    {{ Str::limit($product->description, 80) }}
                                </p>
                            @endif

                            <div class="product-price">
                                {{ number_format($product->price, 2) }} <span>ر.س</span>
                            </div>

                            <a href="{{ route('store.show', $product) }}" class="view-btn">
                                <span>عرض التفاصيل</span>
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            @if($categoryId || $personId || $search)
                <!-- لا توجد منتجات مع فلتر -->
                <div class="empty-state fade-in">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-emerald-800 mb-3">لا توجد منتجات</h3>
                    <p class="text-gray-500 max-w-md mx-auto">
                        لم نجد منتجات تطابق معايير البحث الحالية. جرب تغيير الفئة أو البحث بكلمات مختلفة.
                    </p>
                </div>
            @else
                <!-- رسالة الترحيب - اختر فئة -->
                <div class="welcome-message fade-in">
                    {{-- <div class="welcome-icon">
                        <i class="fas fa-hand-pointer"></i>
                    </div>
                    <h3 class="welcome-title">اختر فئة للبدء</h3>
                    <p class="welcome-text">
                        اختر إحدى الفئات أعلاه لاستعراض المنتجات المتاحة من أسر العائلة المنتجة
                    </p> --}}
                </div>
            @endif
        @endif
    </div>

    <script>
        // Lazy Loading للصور
        document.addEventListener('DOMContentLoaded', function() {
            const lazyImages = document.querySelectorAll('.lazy-image');

            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const placeholder = img.closest('.product-image-wrapper').querySelector('.lazy-placeholder');

                            const handleLoad = () => {
                                img.classList.add('loaded');
                                if (placeholder) {
                                    placeholder.style.display = 'none';
                                }
                            };

                            img.onload = handleLoad;
                            img.src = img.dataset.src;

                            if (img.complete) {
                                handleLoad();
                            }

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

            // إضافة تأثير الظهور التدريجي للعناصر
            const fadeElements = document.querySelectorAll('.fade-in');
            const fadeObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.1
            });

            fadeElements.forEach(el => fadeObserver.observe(el));
        });
    </script>
</body>

</html>
