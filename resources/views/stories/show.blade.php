<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $story->title }}</title>
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
            <h1 class="text-3xl md:text-4xl font-bold gradient-text">{{ $story->title }}</h1>
            @if($story->storyOwner)
                <p class="text-gray-600 mt-2">صاحب القصة: <span class="font-semibold">{{ $story->storyOwner->full_name }}</span></p>
            @endif
            <div class="mt-2 text-sm text-gray-500">
                الرواة:
                @if($story->narrators && $story->narrators->count())
                    @foreach($story->narrators as $n)
                        <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs ml-1">{{ $n->full_name }}</span>
                    @endforeach
                @else
                    <span class="text-gray-400">غير محدد</span>
                @endif
            </div>
        </div>

        <div class="glass rounded-2xl p-6 md:p-8 shadow-md">
            @if($story->content)
                <h2 class="text-2xl font-bold mb-3">النص</h2>
                <p class="text-gray-800 whitespace-pre-line leading-8">{{ $story->content }}</p>
                <hr class="my-6">
            @endif

            @if($story->audio_path)
                <h2 class="text-2xl font-bold mb-3">الصوت</h2>
                <audio controls class="w-full">
                    <source src="{{ $story->getAudioUrl() }}" type="audio/mpeg">
                    المتصفح لا يدعم تشغيل الصوت.
                </audio>
                <hr class="my-6">
            @endif

            @if($story->hasExternalVideo())
                <h2 class="text-2xl font-bold mb-3">الفيديو</h2>
                @php $vid = $story->getYoutubeVideoId(); @endphp
                @if($vid)
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe class="w-full h-[360px] md:h-[520px] rounded-xl" src="https://www.youtube.com/embed/{{ $vid }}" title="YouTube video" allowfullscreen></iframe>
                    </div>
                @else
                    <a href="{{ $story->video_url }}" target="_blank" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                        مشاهدة على المنصة
                    </a>
                @endif
            @elseif($story->video_path)
                <h2 class="text-2xl font-bold mb-3">الفيديو</h2>
                <video controls class="w-full max-h-[520px] rounded-xl">
                    <source src="{{ $story->getUploadedVideoUrl() }}" type="video/mp4">
                    المتصفح لا يدعم تشغيل الفيديو.
                </video>
            @endif
        </div>

        @if($story->storyOwner)
            <div class="text-center mt-8">
                <a href="{{ route('public.stories.person', $story->storyOwner) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/70 hover:bg-white transition border border-green-200">
                    العودة إلى قصص {{ $story->storyOwner->full_name }}
                </a>
            </div>
        @endif
    </main>

    @stack('scripts')
</body>
</html>


