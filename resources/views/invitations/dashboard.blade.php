<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - الدعوات</title>
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

        h1, h2, h3, h4 {
            font-family: 'Amiri', serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
            transform: translateY(-5px);
        }

        .stat-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .action-btn {
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
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

        .animate-fade-in {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>

<body class="antialiased">
    @include('partials.main-header')

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            {{-- Header --}}
            <div class="text-center mb-8 animate-fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-green-400 to-green-600 shadow-2xl mb-4">
                    <i class="fab fa-whatsapp text-4xl text-white"></i>
                </div>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2">
                    لوحة تحكم الدعوات
                </h1>
                <p class="text-lg text-gray-600">
                    مرحباً <strong>{{ Auth::user()->name }}</strong>
                </p>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-50 border-l-4 border-green-400 p-4 animate-fade-in">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="glass-card rounded-2xl p-6 animate-fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">إجمالي المجموعات</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $total_groups }}</p>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-users text-3xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-6 animate-fade-in" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">رسائل مرسلة</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $total_sent }}</p>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-paper-plane text-3xl text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-6 stat-card animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-100 mb-1">حالة الحساب</p>
                            <p class="text-2xl font-bold">نشط</p>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                            <i class="fas fa-check-circle text-3xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('invitations.send') }}" class="glass-card rounded-2xl p-6 action-btn animate-fade-in text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center mx-auto mb-4">
                        <i class="fab fa-whatsapp text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">إرسال دعوة</h3>
                    <p class="text-sm text-gray-600">أرسل رسائل واتساب مخصصة</p>
                </a>

                <a href="{{ route('invitations.groups.index') }}" class="glass-card rounded-2xl p-6 action-btn animate-fade-in text-center" style="animation-delay: 0.1s;">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">إدارة المجموعات</h3>
                    <p class="text-sm text-gray-600">أنشئ وعدّل مجموعاتك</p>
                </a>

                <a href="{{ route('invitations.logs') }}" class="glass-card rounded-2xl p-6 action-btn animate-fade-in text-center" style="animation-delay: 0.2s;">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-history text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">سجل الإرسال</h3>
                    <p class="text-sm text-gray-600">تتبع رسائلك المرسلة</p>
                </a>
            </div>

            {{-- Recent Activity --}}
            @if($recent_notifications->count() > 0)
            <div class="glass-card rounded-2xl p-6 lg:p-8 animate-fade-in" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-clock text-green-600 mr-2"></i>
                        النشاط الأخير
                    </h2>
                    <a href="{{ route('invitations.logs') }}" class="text-green-600 hover:text-green-700 font-medium text-sm">
                        عرض الكل
                        <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach($recent_notifications as $notification)
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <i class="fab fa-whatsapp text-xl text-green-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $notification->title ?: 'رسالة واتساب' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $notification->recipients->count() }} مستلم
                                · 
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div>
                            @if($notification->status === 'sent')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <i class="fas fa-check mr-1"></i> تم الإرسال
                                </span>
                            @elseif($notification->status === 'sending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> جاري الإرسال
                                </span>
                            @elseif($notification->status === 'failed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <i class="fas fa-times mr-1"></i> فشل
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $notification->status }}
                                </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="glass-card rounded-2xl p-12 text-center animate-fade-in" style="animation-delay: 0.3s;">
                <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-5xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">لم ترسل أي رسائل بعد</h3>
                <p class="text-gray-600 mb-6">ابدأ الآن بإرسال أول دعوة لك</p>
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
