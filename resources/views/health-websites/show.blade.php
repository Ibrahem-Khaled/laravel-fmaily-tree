<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $website->name }} - مواقع مهتمة بالصحة</title>
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
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 space-x-reverse text-sm">
                <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700">الرئيسية</a></li>
                <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                <li><a href="{{ route('health-websites.index') }}" class="text-blue-600 hover:text-blue-700">المواقع الصحية</a></li>
                <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                <li class="text-gray-600">{{ $website->name }}</li>
            </ol>
        </nav>

        <div class="glass-effect rounded-3xl p-8 mb-8">
            <div class="text-center mb-8">
                @if($website->logo)
                    <img src="{{ asset('storage/' . $website->logo) }}"
                         alt="{{ $website->name }}"
                         class="w-32 h-32 object-contain mx-auto mb-4">
                @else
                    <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-globe text-blue-600 text-5xl"></i>
                    </div>
                @endif

                <h1 class="text-4xl font-black gradient-text mb-4">{{ $website->name }}</h1>

                @if($website->category)
                    <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full">{{ $website->category }}</span>
                @endif
            </div>

            @if($website->description)
                <div class="mb-6">
                    <h3 class="text-xl font-bold mb-3">الوصف</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $website->description }}</p>
                </div>
            @endif

            <div class="text-center">
                <a href="{{ $website->url }}" target="_blank" rel="noopener noreferrer"
                   class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 text-white px-8 py-4 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all text-lg font-bold">
                    <i class="fas fa-external-link-alt mr-2"></i>زيارة الموقع
                </a>
            </div>
        </div>

        @if($relatedWebsites->count() > 0)
            <div>
                <h2 class="text-3xl font-bold gradient-text mb-6">مواقع مشابهة</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedWebsites as $related)
                        <div class="glass-effect rounded-3xl p-6 hover:shadow-xl transition-all">
                            @if($related->logo)
                                <img src="{{ asset('storage/' . $related->logo) }}"
                                     alt="{{ $related->name }}"
                                     class="w-12 h-12 object-contain mb-3 mx-auto">
                            @else
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3 mx-auto">
                                    <i class="fas fa-globe text-blue-600"></i>
                                </div>
                            @endif

                            <h3 class="font-bold mb-2 text-center">{{ $related->name }}</h3>
                            <a href="{{ route('health-websites.show', $related) }}"
                               class="block text-center text-blue-600 hover:text-blue-700 text-sm">
                                عرض التفاصيل
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</body>

</html>
