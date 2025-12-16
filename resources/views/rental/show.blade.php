<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - استعارة الأدوات الرياضية</title>
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
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    <div class="container mx-auto px-4 py-4 lg:py-8 relative z-10">
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 space-x-reverse text-sm">
                <li><a href="{{ route('home') }}" class="text-orange-600 hover:text-orange-700">الرئيسية</a></li>
                <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                <li><a href="{{ route('rental.index') }}" class="text-orange-600 hover:text-orange-700">استعارة الأدوات</a></li>
                <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                <li class="text-gray-600">{{ Str::limit($product->name, 30) }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div class="fade-in-up">
                <div class="glass-effect rounded-3xl overflow-hidden orange-glow">
                    @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-auto object-cover">
                    @else
                        <div class="w-full h-96 bg-gradient-to-br from-orange-400 to-red-600 flex items-center justify-center">
                            <i class="fas fa-dumbbell text-white text-6xl opacity-50"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="fade-in-up">
                <div class="glass-effect p-8 rounded-3xl orange-glow">
                    <h1 class="text-4xl font-black gradient-text mb-4">{{ $product->name }}</h1>

                    @if($product->description)
                        <div class="mb-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-800">الوصف</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                        </div>
                    @endif

                    @if($product->features && count($product->features) > 0)
                        <div class="mb-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-800">المميزات</h3>
                            <ul class="space-y-2">
                                @foreach($product->features as $feature)
                                    @if($feature)
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle text-orange-500 mr-3 mt-1"></i>
                                            <span class="text-gray-700">{{ $feature }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rental Request Form -->
        <div class="glass-effect p-8 rounded-3xl orange-glow mb-8">
            <h2 class="text-3xl font-bold gradient-text mb-6">
                <i class="fas fa-hand-holding mr-2"></i>طلب استعارة الأداة
            </h2>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('rental.request', $product) }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">الاسم <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">رقم الهاتف <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-3 rounded-xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">تاريخ بداية الاستعارة <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 rounded-xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">تاريخ نهاية الاستعارة <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 rounded-xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none" required>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-gray-700 font-medium mb-2">ملاحظات إضافية</label>
                    <textarea name="notes" rows="4"
                              class="w-full px-4 py-3 rounded-xl border-2 border-orange-200 focus:border-orange-500 focus:outline-none">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 px-8 rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all text-lg font-bold">
                        <i class="fas fa-paper-plane mr-2"></i>إرسال طلب الاستعارة
                    </button>
                </div>
            </form>
        </div>

        @if($relatedProducts->count() > 0)
            <div class="mt-12">
                <h2 class="text-3xl font-bold gradient-text mb-6">أدوات مشابهة</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <div class="glass-effect rounded-3xl overflow-hidden orange-glow">
                            <a href="{{ route('rental.show', $related) }}" class="block">
                                @if($related->main_image)
                                    <img src="{{ asset('storage/' . $related->main_image) }}"
                                         alt="{{ $related->name }}"
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-orange-400 to-red-600 flex items-center justify-center">
                                        <i class="fas fa-dumbbell text-white text-3xl opacity-50"></i>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-800 mb-2">{{ $related->name }}</h3>
                                    <span class="text-orange-600 font-bold">عرض التفاصيل</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        // Set minimum end_date based on start_date
        document.querySelector('input[name="start_date"]').addEventListener('change', function() {
            const startDate = this.value;
            const endDateInput = document.querySelector('input[name="end_date"]');
            if (startDate) {
                const nextDay = new Date(startDate);
                nextDay.setDate(nextDay.getDate() + 1);
                endDateInput.min = nextDay.toISOString().split('T')[0];
            }
        });
    </script>
</body>

</html>
