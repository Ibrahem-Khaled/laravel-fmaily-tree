<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الدعوات - إرسال دعوات واتساب</title>
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
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.3);
        }

        .feature-item {
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateX(-5px);
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

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .icon-bounce {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>

<body class="antialiased">
    @include('partials.main-header')

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 animate-fade-in-up">
            {{-- Logo & Title --}}
            <div class="text-center">
                <div class="mx-auto h-24 w-24 flex items-center justify-center rounded-full gradient-bg shadow-2xl icon-bounce">
                    <i class="fab fa-whatsapp text-5xl text-white"></i>
                </div>
                <h2 class="mt-6 text-4xl font-extrabold text-gray-900">
                    إرسال دعوات واتساب
                </h2>
                <p class="mt-2 text-lg text-gray-600">
                    سجّل بياناتك وابدأ الآن
                </p>
            </div>

            {{-- Main Card --}}
            <div class="glass-card rounded-3xl p-8 space-y-6">
                {{-- Alerts --}}
                @if(session('success'))
                    <div class="rounded-xl bg-green-50 border-l-4 border-green-400 p-4">
                        <div class="flex">
                            <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="rounded-xl bg-red-50 border-l-4 border-red-400 p-4">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle text-red-400 text-xl mr-3"></i>
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded-xl bg-red-50 border-l-4 border-red-400 p-4">
                        <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('invitations.login-or-register') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-user text-green-600 ml-2"></i>
                            الاسم الكامل
                        </label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                            class="input-field appearance-none rounded-xl relative block w-full px-4 py-4 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-base"
                            placeholder="أدخل اسمك الكامل">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-phone text-green-600 ml-2"></i>
                            رقم الهاتف
                        </label>
                        <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}"
                            class="input-field appearance-none rounded-xl relative block w-full px-4 py-4 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-base"
                            placeholder="05xxxxxxxx">
                        <p class="mt-2 text-xs text-gray-500 flex items-start">
                            <i class="fas fa-info-circle ml-2 mt-0.5 flex-shrink-0"></i>
                            <span>إذا كان الرقم مسجلاً، سيتم تسجيل دخولك تلقائياً. وإلا سيتم إنشاء حساب جديد.</span>
                        </p>
                    </div>

                    <button type="submit"
                        class="btn-primary w-full flex justify-center items-center py-4 px-6 border border-transparent text-lg font-bold rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fab fa-whatsapp text-2xl ml-3"></i>
                        ابدأ الآن
                    </button>
                </form>
            </div>

            {{-- Features Section --}}
            <div class="glass-card rounded-3xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-star text-yellow-500 ml-2"></i>
                    مميزات النظام
                </h3>
                <ul class="space-y-3">
                    <li class="feature-item flex items-start text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 ml-3 flex-shrink-0"></i>
                        <span>إرسال رسائل مخصصة باسم كل شخص</span>
                    </li>
                    <li class="feature-item flex items-start text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 ml-3 flex-shrink-0"></i>
                        <span>حفظ المجموعات المفضلة للإرسال السريع</span>
                    </li>
                    <li class="feature-item flex items-start text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 ml-3 flex-shrink-0"></i>
                        <span>إرفاق صور وفيديوهات ورسائل صوتية</span>
                    </li>
                    <li class="feature-item flex items-start text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 ml-3 flex-shrink-0"></i>
                        <span>معاينة الرسائل قبل الإرسال</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
