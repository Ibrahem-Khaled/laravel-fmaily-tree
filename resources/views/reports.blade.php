<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقارير وإحصائيات العائلة</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
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

        .green-glow {
            box-shadow: 0 0 40px rgba(34, 197, 94, 0.3);
        }

        .gradient-text {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #4ade80 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% auto;
            animation: gradientShift 3s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .stat-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s ease;
        }

        .stat-card:hover::before {
            left: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(34, 197, 94, 0.3);
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

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* تفاصيل الأجيال */
        .gen-details-0.hidden,
        .gen-details-1.hidden,
        .gen-details-2.hidden,
        .gen-details-3.hidden,
        .gen-details-4.hidden,
        .gen-details-5.hidden,
        .gen-details-6.hidden,
        .gen-details-7.hidden,
        .gen-details-8.hidden,
        .gen-details-9.hidden,
        .gen-details-10.hidden {
            display: none;
        }

        .gen-arrow {
            transition: transform 0.3s ease;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {

            .stat-card {
                padding: 1rem !important;
            }

            .stat-card h3 {
                font-size: 1rem;
            }
        }

        /* Print styles */
        @media print {
            .glass-effect {
                background: white;
            }
        }
    </style>
</head>

<body class="text-gray-800">
    @include('partials.main-header')

    <div class="container mx-auto px-4 py-4 md:py-8 max-w-7xl">

        <!-- العنوان الرئيسي -->
        <header class="text-center mb-6 md:mb-8 fade-in">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold gradient-text mb-3">
                تقارير وإحصائيات العائلة
            </h1>
            <p class="text-base md:text-lg text-gray-600">
                نظرة شاملة على إحصائيات وبيانات أفراد العائلة
            </p>
        </header>

        <!-- القسم الأول: الإحصائيات الأساسية -->
        <section class="mb-6 md:mb-8">
            <div class="stat-card glass-effect p-4 md:p-8 rounded-2xl green-glow">
                <!-- رأس القسم -->
                <div class="flex flex-col md:flex-row items-center gap-4 mb-6">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="text-center md:text-right">
                        <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-1">إجمالي أبناء العائلة</h3>
                        <p class="text-xs md:text-sm text-gray-500">عدد أبناء العائلة من الذكور والإناث (الأحياء فقط)</p>
                    </div>
                </div>

                <!-- الأرقام -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
                    <!-- إجمالي -->
                    <div class="text-center p-4 md:p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                        <div class="text-4xl md:text-5xl font-bold gradient-text mb-2">{{ number_format($totalFamilyMembers) }}</div>
                        <div class="text-xs md:text-sm font-medium text-gray-600">إجمالي الأبناء</div>
                    </div>

                    <!-- الذكور -->
                    <div class="text-center p-4 md:p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                        <div class="text-4xl md:text-5xl font-bold text-blue-600 mb-2">{{ number_format($maleCount) }}</div>
                        <div class="text-xs md:text-sm font-medium text-gray-600">ذكور</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $totalFamilyMembers > 0 ? number_format(($maleCount / $totalFamilyMembers) * 100, 1) : 0 }}%</div>
                    </div>

                    <!-- الإناث -->
                    <div class="text-center p-4 md:p-6 bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl">
                        <div class="text-4xl md:text-5xl font-bold text-pink-600 mb-2">{{ number_format($femaleCount) }}</div>
                        <div class="text-xs md:text-sm font-medium text-gray-600">إناث</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $totalFamilyMembers > 0 ? number_format(($femaleCount / $totalFamilyMembers) * 100, 1) : 0 }}%</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم الثاني: الإحصائيات التعليمية -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
            <!-- حملة الماجستير -->
            <div class="stat-card glass-effect p-4 md:p-6 rounded-2xl green-glow">
                <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-4">
                    <div class="flex items-center gap-3 md:gap-4 flex-1">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg md:text-2xl font-bold gradient-text">حملة الماجستير</h3>
                            <p class="text-xs md:text-sm text-gray-500">الشهادة العلمية العليا (أحياء)</p>
                        </div>
                    </div>
                    <div class="text-4xl md:text-5xl font-bold text-indigo-600">{{ number_format($masterDegreeCount) }}</div>
                </div>
            </div>

            <!-- حملة الدكتوراه -->
            <div class="stat-card glass-effect p-4 md:p-6 rounded-2xl green-glow">
                <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-4">
                    <div class="flex items-center gap-3 md:gap-4 flex-1">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg md:text-2xl font-bold gradient-text">حملة الدكتوراه</h3>
                            <p class="text-xs md:text-sm text-gray-500">أعلى الشهادات العلمية (أحياء)</p>
                        </div>
                    </div>
                    <div class="text-4xl md:text-5xl font-bold text-yellow-600">{{ number_format($phdCount) }}</div>
                </div>
            </div>
        </section>

        <!-- القسم الثالث: ترتيب الأعمار -->
        <section class="glass-effect p-4 md:p-6 rounded-2xl green-glow mb-6 md:mb-8 fade-in">
            <div class="flex flex-col md:flex-row items-center gap-3 mb-4 md:mb-6">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-center md:text-right">
                    <h3 class="text-xl md:text-2xl font-bold gradient-text">أكبر الأعمار (ذكور)</h3>
                    <p class="text-xs md:text-sm text-gray-500">ترتيب حسب العمر - الأحياء فقط</p>
                </div>
            </div>
            <div class="space-y-2 max-h-64 md:max-h-80 overflow-y-auto">
                @forelse($malesByAge->take(10) as $index => $person)
                    <div class="flex items-center justify-between p-3 bg-white/50 rounded-lg hover:bg-white/70 transition">
                        <div class="flex items-center gap-2 md:gap-3 min-w-0 flex-1">
                            <div class="w-7 h-7 md:w-8 md:h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center text-white font-bold text-xs md:text-sm flex-shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-gray-800 text-sm md:text-base truncate">{{ $person['full_name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $person['birth_date'] ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                        <div class="text-base md:text-lg font-bold text-green-600 flex-shrink-0">{{ $person['age'] }}</div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">لا توجد بيانات</p>
                @endforelse
            </div>
        </section>

        <!-- القسم الرابع: إحصائيات الأبناء والأحفاد حسب الجد -->
        <section class="glass-effect p-4 md:p-6 rounded-2xl green-glow mb-8 fade-in">
            <!-- رأس القسم -->
            <div class="text-center mb-4 md:mb-6">
                <h2 class="text-xl md:text-2xl font-bold gradient-text mb-2">عدد الأبناء والأحفاد حسب الجد</h2>
                <p class="text-xs md:text-sm text-gray-600">
                    العرض يشمل جميع الأجيال: الجيل الأول (الأجداد) → الجيل الثاني (الآباء) → الجيل الثالث (الأبناء) → الجيل الرابع (الأحفاد)
                </p>
            </div>

            <!-- الجدول -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead>
                        <tr class="border-b-2 border-green-200">
                            <th class="text-right py-3 px-3 md:px-4 font-bold text-gray-800 text-sm md:text-base">الجد</th>
                            <th class="text-center py-3 px-3 md:px-4 font-bold text-gray-800 text-sm md:text-base">إجمالي الأحفاد</th>
                            <th class="text-center py-3 px-3 md:px-4 font-bold text-blue-600 text-sm md:text-base">ذكور</th>
                            <th class="text-center py-3 px-3 md:px-4 font-bold text-pink-600 text-sm md:text-base">إناث</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($generationsData as $index => $stat)
                            <tr class="border-b border-gray-100 hover:bg-white/50 transition">
                                <td class="py-3 px-3 md:px-4 font-bold text-gray-800 text-sm md:text-base">
                                    {{ $stat['grandfather_name'] }}
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <span class="inline-block px-2 md:px-3 py-1 bg-green-100 text-green-700 rounded-full font-bold text-xs md:text-sm">
                                        {{ $stat['total_descendants'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <span class="inline-block px-2 md:px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs md:text-sm">
                                        {{ $stat['male_descendants'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <span class="inline-block px-2 md:px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs md:text-sm">
                                        {{ $stat['female_descendants'] }}
                                    </span>
                                </td>
                            </tr>

                            <!-- صف التفاصيل (مفتوح افتراضياً) -->
                            <tr class="generations-detail-{{ $index }}">
                                <td colspan="4" class="py-4 px-4 bg-gray-50">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        @php
                                            $generationLabels = [
                                                1 => 'الجيل الأول (الأبناء)',
                                                2 => 'الجيل الثاني (الأحفاد)',
                                                3 => 'الجيل الثالث',
                                                4 => 'الجيل الرابع',
                                                5 => 'الجيل الخامس',
                                                6 => 'الجيل السادس'
                                            ];
                                        @endphp

                                        @foreach($stat['generations_breakdown'] as $genLevel => $genData)
                                            <div class="bg-white p-3 rounded-lg shadow-sm cursor-pointer hover:shadow-md transition"
                                                 onclick="toggleGenerationDetails({{ $index }}, {{ $genLevel }})">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="text-xs font-bold text-gray-600">
                                                        {{ $generationLabels[$genLevel] ?? "الجيل " . $genLevel }}
                                                    </div>
                                                    <svg class="w-3 h-3 text-gray-400 gen-arrow-{{ $index }}-{{ $genLevel }}"
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                                <div class="text-2xl font-bold text-green-600">{{ $genData['total'] ?? 0 }}</div>
                                                <div class="flex gap-2 mt-2">
                                                    <span class="text-xs text-blue-600">♂ {{ $genData['males'] ?? 0 }}</span>
                                                    <span class="text-xs text-pink-600">♀ {{ $genData['females'] ?? 0 }}</span>
                                                </div>

                                                <!-- التفاصيل الإضافية (مخفية) -->
                                                <div class="gen-details-{{ $index }}-{{ $genLevel }} hidden mt-3 pt-3 border-t">
                                                    <div class="text-xs text-gray-500">
                                                        تفاصيل الجيل {{ $genLevel }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        // دالة لإظهار/إخفاء تفاصيل جيل محدد
        function toggleGenerationDetails(grandfatherIndex, generationLevel) {
            const detailRow = document.querySelector(`.gen-details-${grandfatherIndex}-${generationLevel}`);
            const arrow = document.querySelector(`.gen-arrow-${grandfatherIndex}-${generationLevel}`);

            if (detailRow.classList.contains('hidden')) {
                detailRow.classList.remove('hidden');
                if (arrow) {
                    arrow.style.transform = 'rotate(180deg)';
                }
            } else {
                detailRow.classList.add('hidden');
                if (arrow) {
                    arrow.style.transform = 'rotate(0deg)';
                }
            }
        }

        // تأثيرات التحميل
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.fade-in');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>

</html>
