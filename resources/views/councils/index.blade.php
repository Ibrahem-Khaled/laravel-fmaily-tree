<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مجالس العائلة - منصة عائلة السريع</title>
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
        .council-card {
            transition: all 0.3s ease;
        }
        .council-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('partials.main-header')

    <div class="gradient-bg text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold text-center mb-4">
                <i class="fas fa-building mr-3"></i>مجالس العائلة
            </h1>
            <p class="text-center text-lg opacity-90">اكتشف أماكن اجتماعات عائلة السريع</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        @if($councils->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($councils as $council)
                    <div class="council-card bg-white rounded-lg shadow-lg overflow-hidden">
                        @if($council->images->count() > 0)
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ $council->images->first()->image_url }}" 
                                     alt="{{ $council->name }}"
                                     class="w-full h-full object-cover">
                                <div class="absolute top-4 right-4">
                                    <span class="bg-white text-gray-800 px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                        <i class="fas fa-images mr-1"></i>{{ $council->images->count() }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                                <i class="fas fa-building text-white text-5xl opacity-50"></i>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">
                                <i class="fas fa-building text-green-600 mr-2"></i>{{ $council->name }}
                            </h3>
                            
                            @if($council->description)
                                <p class="text-gray-600 mb-3 line-clamp-3">
                                    {{ Str::limit($council->description, 120) }}
                                </p>
                            @endif

                            @if($council->address)
                                <p class="text-gray-500 text-sm mb-3">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                    {{ Str::limit($council->address, 80) }}
                                </p>
                            @endif

                            <div class="flex justify-between items-center mt-4">
                                <a href="{{ route('councils.show', $council) }}" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-1"></i>عرض التفاصيل
                                </a>
                                @if($council->google_map_url)
                                    <a href="{{ $council->google_map_url }}" 
                                       target="_blank"
                                       class="text-green-600 hover:text-green-700">
                                        <i class="fas fa-map-marked-alt text-xl"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-building text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">لا توجد مجالس حالياً</h3>
                <p class="text-gray-500">سيتم إضافة مجالس العائلة قريباً</p>
            </div>
        @endif
    </div>

    @include('partials.main-footer')
</body>
</html>

