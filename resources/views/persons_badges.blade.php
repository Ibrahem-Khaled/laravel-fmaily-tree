<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة الحاصلين على أوسمة</title>

    {{-- استخدام نفس إعدادات Tailwind والخطوط --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Tajawal', 'sans-serif'],
                        'serif': ['Amiri', 'serif'],
                    },
                    boxShadow: {
                        'green-glow': '0 0 40px rgba(34, 197, 94, 0.3)',
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Amiri', serif;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f0fdf4;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e, #16a34a);
            border-radius: 5px;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-50 to-emerald-50 text-gray-800">
    @include('partials.main-header')
    <main class="container mx-auto px-4 py-8 lg:py-12 relative z-10 max-w-7xl">
        <div class="text-center mb-10">
            <h1
                class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 leading-relaxed font-serif bg-gradient-to-r from-green-600 to-emerald-500 text-transparent bg-clip-text">
                طلاب طموح
            </h1>
            <p class="text-lg text-gray-600">برنامج “طموح” هو أحد برامج “أكاديمية السريّع”، ويعتبر برنامجًا تقنيًا
                متقدمًا يستهدف جيل المستقبل الذين يسعون إلى تنمية مهاراتهم التقنية والمهنية في مجالات متعددة. تم تصميم
                البرنامج بشكل شامل ليغطي مجموعة واسعة من المهارات التي تساعد المشاركين على الاستعداد لمستقبل رقمي متقدم.
                يعتمد البرنامج على أسلوب تعليمي تفاعلي وحديث يمكّن المشاركين من تحقيق طموحاتهم المهنية والتقنية</p>
        </div>

        @if ($persons->isNotEmpty())
            {{-- ======================= بداية التعديل هنا ======================= --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            {{-- ======================== نهاية التعديل هنا ======================== --}}
                {{-- المرور على كل شخص في القائمة --}}
                @foreach ($persons as $person)
                    <div
                        class="bg-white/80 backdrop-blur-md border border-white/30 rounded-2xl overflow-hidden shadow-md hover:shadow-xl hover:shadow-green-500/20 transition-all duration-300 transform hover:-translate-y-2 flex flex-col">
                        <div class="p-5 text-center border-b border-green-100">
                            <img src="{{ $person->avatar }}" alt="{{ $person->first_name }}"
                                class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-white shadow-md object-cover">
                            <h3 class="font-bold font-serif text-xl text-gray-800">
                                {{-- رابط للملف الشخصي --}}
                                <a href="{{ route('people.profile.show', $person->id) }}"
                                    class="hover:text-green-600 transition-colors">
                                    {{ $person->full_name }}
                                </a>
                            </h3>
                        </div>
                        <div class="p-5 bg-green-50/30 flex-grow">
                            <h4 class="font-semibold text-gray-700 mb-3 text-sm">الأوسمة الحاصل عليها:</h4>
                            <div class="flex flex-wrap gap-2">
                                {{-- عرض الأوسمة الخاصة بكل شخص --}}
                                @foreach ($person->padges as $padge)
                                    <span
                                        class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        {{ $padge->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- رسالة في حالة عدم وجود أي شخص حاصل على أوسمة --}}
            <div class="text-center bg-white/80 backdrop-blur-md p-10 rounded-2xl max-w-2xl mx-auto shadow-md">
                <svg class="w-16 h-16 text-green-300 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M11.998 2.503a9.5 9.5 0 018.164 4.885.75.75 0 001.213-.88A11 11 0 0012 1.003a11 11 0 00-9.375 5.505.75.75 0 001.213.88A9.5 9.5 0 0111.998 2.503zM12 22.997a11 11 0 009.375-5.505.75.75 0 00-1.213-.88 9.5 9.5 0 01-16.328 0 .75.75 0 00-1.213.88A11 11 0 0012 22.997zM17 13.5a.75.75 0 000-1.5h-4.25a.75.75 0 000 1.5H17z" />
                </svg>
                <h2 class="text-2xl font-serif font-bold text-gray-700">لا يوجد نتائج</h2>
                <p class="text-gray-500 mt-2">لا يوجد حاليًا أي أشخاص في القائمة حاصلون على أوسمة.</p>
            </div>
        @endif

        <div class="mt-12 text-center">
            <a href="{{ url('/') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg shadow-green-500/20 transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span>العودة للرئيسية</span>
            </a>
        </div>
    </main>
</body>

</html>
