<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل الإرسال - الدعوات</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 50%, #a7f3d0 100%);
            min-height: 100vh;
        }

        h1, h2, h3 {
            font-family: 'Amiri', serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .log-item {
            transition: all 0.3s ease;
        }

        .log-item:hover {
            background-color: #f0fdf4;
            transform: translateX(-5px);
        }
    </style>
</head>

<body class="antialiased">
    @include('partials.main-header')

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                        <i class="fas fa-history text-purple-600 mr-2"></i>
                        سجل الإرسال
                    </h1>
                    <p class="text-gray-600">تتبع جميع الرسائل المرسلة</p>
                </div>
                <a href="{{ route('invitations.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-right"></i>
                    العودة
                </a>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Logs List --}}
            @if($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                    <div class="glass-card rounded-2xl p-6 log-item">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex items-start gap-4 flex-1">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0
                                    {{ $notification->status === 'sent' ? 'bg-green-100' : ($notification->status === 'failed' ? 'bg-red-100' : 'bg-blue-100') }}">
                                    <i class="fab fa-whatsapp text-xl
                                        {{ $notification->status === 'sent' ? 'text-green-600' : ($notification->status === 'failed' ? 'text-red-600' : 'text-blue-600') }}"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                                        {{ $notification->title ?: 'رسالة واتساب' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                        {{ Str::limit($notification->body, 120) }}
                                    </p>
                                    <div class="flex items-center gap-4 text-xs text-gray-500">
                                        <span>
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $notification->recipients->count() }} مستلم
                                        </span>
                                        <span>
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $notification->created_at->format('Y-m-d H:i') }}
                                        </span>
                                        @if($notification->media_type)
                                            <span>
                                                @if($notification->media_type === 'image')
                                                    <i class="fas fa-image mr-1 text-blue-500"></i> صورة
                                                @elseif($notification->media_type === 'video')
                                                    <i class="fas fa-video mr-1 text-red-500"></i> فيديو
                                                @elseif($notification->media_type === 'voice')
                                                    <i class="fas fa-microphone mr-1 text-yellow-500"></i> صوت
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                @if($notification->status === 'sent')
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                        <i class="fas fa-check mr-2"></i> تم الإرسال
                                    </span>
                                @elseif($notification->status === 'sending')
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> جاري الإرسال
                                    </span>
                                @elseif($notification->status === 'partial')
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> جزئي
                                    </span>
                                @elseif($notification->status === 'failed')
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-700">
                                        <i class="fas fa-times mr-2"></i> فشل
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                                        {{ $notification->status }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Recipients List --}}
                        @if($notification->recipients->count() > 0)
                            <div class="border-t border-gray-100 pt-4 mt-4">
                                <h4 class="text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-user-friends text-green-600 mr-2"></i>
                                    المستلمون ({{ $notification->recipients->count() }})
                                </h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($notification->recipients->take(10) as $recipient)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                            <i class="fas fa-user text-xs mr-1"></i>
                                            {{ $recipient->person->first_name ?? 'غير معروف' }}
                                        </span>
                                    @endforeach
                                    @if($notification->recipients->count() > 10)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-green-100 text-green-700 font-medium">
                                            +{{ $notification->recipients->count() - 10 }} آخرون
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="glass-card rounded-2xl p-12 text-center">
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-5xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد رسائل مرسلة</h3>
                    <p class="text-gray-600 mb-6">ابدأ بإرسال أول دعوة لك</p>
                    <a href="{{ route('invitations.send') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl hover:shadow-lg transition">
                        <i class="fab fa-whatsapp"></i>
                        إرسال دعوة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
