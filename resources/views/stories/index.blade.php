<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قصص {{ $person->full_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Tajawal',sans-serif;background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 50%,#bbf7d0 100%);min-height:100vh}
        h1,h2,h3{font-family:'Amiri',serif}
        .glass{background:rgba(255,255,255,.85);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.3)}
        .gradient-text{background:linear-gradient(135deg,#16a34a 0%,#22c55e 50%,#4ade80 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    </style>
    @vite(["resources/css/app.css","resources/js/app.js"])
    @stack('head')
    @stack('styles')
</head>
<body class="text-gray-800">
    @include('partials.main-header')

    <main class="container mx-auto px-4 py-8">
        <div class="mb-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold gradient-text">قصص {{ $person->full_name }}</h1>
            <p class="text-gray-600 mt-2">مجموعة من القصص والروايات المرتبطة بـ {{ $person->full_name }}</p>
        </div>

        @if($stories->count() === 0)
            <div class="glass rounded-2xl p-6 text-center text-gray-600">لا توجد قصص مسجلة لهذا الشخص.</div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($stories as $story)
                    <div class="glass rounded-2xl p-5 shadow-md hover:shadow-xl transition-all">
                        <h3 class="text-xl font-bold mb-2">{{ $story->title }}</h3>
                        <div class="text-sm text-gray-500 mb-3">
                            الراوي:
                            @if($story->narrators && $story->narrators->count())
                                @foreach($story->narrators as $n)
                                    <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs ml-1 mb-1">{{ $n->full_name }}</span>
                                @endforeach
                            @else
                                <span class="text-gray-400">غير محدد</span>
                            @endif
                        </div>
                        @if($story->content)
                            <p class="text-gray-700 mb-4">{{ Str::limit($story->content, 140, '...') }}</p>
                        @endif
                        <a href="{{ route('public.stories.show', $story) }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 10a.75.75 0 00-1.5 0v3.5a.75.75 0 001.5 0V10z"/><path d="M10 4a2 2 0 00-2 2v1h4V6a2 2 0 00-2-2z"/><path fill-rule="evenodd" d="M3 8a4 4 0 014-4h6a4 4 0 014 4v6a4 4 0 01-4 4H7a4 4 0 01-4-4V8zm4-2a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V8a2 2 0 00-2-2H7z" clip-rule="evenodd"/></svg>
                            عرض التفاصيل
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">
                {{ $stories->links() }}
            </div>
        @endif
    </main>

    @stack('scripts')
</body>
</html>


