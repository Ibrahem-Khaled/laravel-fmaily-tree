<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مواقع مهتمة بالصحة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 50%, #93c5fd 100%);
            min-height: 100vh;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="text-gray-800">
    @include('partials.main-header')

    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl lg:text-5xl font-black gradient-text mb-4">
                <i class="fas fa-heartbeat mr-3"></i>مواقع مهتمة بالصحة
            </h1>
            <p class="text-lg text-gray-600">مجموعة من المواقع المفيدة للصحة واللياقة</p>
        </div>

        @if($categories->count() > 0)
            <div class="glass-effect rounded-3xl p-6 mb-6">
                <h3 class="text-xl font-bold mb-4">الفئات</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('health-websites.index') }}"
                       class="px-4 py-2 rounded-full {{ !$category ? 'bg-blue-500 text-white' : 'bg-white text-blue-600 hover:bg-blue-50' }}">
                        الكل
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('health-websites.index', ['category' => $cat]) }}"
                           class="px-4 py-2 rounded-full {{ $category == $cat ? 'bg-blue-500 text-white' : 'bg-white text-blue-600 hover:bg-blue-50' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($websites->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($websites as $website)
                    <div class="glass-effect rounded-3xl p-6 hover:shadow-xl transition-all">
                        @if($website->logo)
                            <img src="{{ asset('storage/' . $website->logo) }}"
                                 alt="{{ $website->name }}"
                                 class="w-16 h-16 object-contain mb-4 mx-auto">
                        @else
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-globe text-blue-600 text-2xl"></i>
                            </div>
                        @endif

                        <h3 class="text-xl font-bold mb-2 text-center">{{ $website->name }}</h3>

                        @if($website->description)
                            <p class="text-gray-600 text-sm mb-4 text-center line-clamp-2">{{ $website->description }}</p>
                        @endif

                        @if($website->category)
                            <div class="text-center mb-4">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">{{ $website->category }}</span>
                            </div>
                        @endif

                        <a href="{{ $website->url }}" target="_blank" rel="noopener noreferrer"
                           class="block w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white text-center py-3 px-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all">
                            <i class="fas fa-external-link-alt mr-2"></i>زيارة الموقع
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $websites->links() }}
            </div>
        @else
            <div class="glass-effect rounded-3xl p-12 text-center">
                <i class="fas fa-heartbeat text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-2xl font-bold mb-3">لا توجد مواقع متاحة حالياً</h3>
            </div>
        @endif
    </div>
</body>

</html>
