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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        .person-clickable {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .person-clickable:hover {
            background-color: rgba(34, 197, 94, 0.1);
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
                        </div>
                    </div>
                    <div class="text-4xl md:text-5xl font-bold text-yellow-600">{{ number_format($phdCount) }}</div>
                </div>
            </div>
        </section>

        <!-- القسم الثالث: إحصائيات الأماكن (عدد سكان المدن) -->
        <section class="glass-effect p-4 md:p-6 rounded-2xl green-glow mb-6 md:mb-8 fade-in">
            <div class="flex flex-col md:flex-row items-center gap-3 mb-4 md:mb-6">
                <div class="text-center md:text-right">
                    <h3 class="text-xl md:text-2xl font-bold gradient-text">عدد سكان المدن</h3>
                    <p class="text-xs md:text-sm text-gray-600 mt-1">توزيع أبناء العائلة على الأماكن حسب الذكور والإناث</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead>
                        <tr class="border-b-2 border-green-200">
                            <th class="text-right py-3 px-3 md:px-4 font-bold text-gray-800 text-sm md:text-base">المدينة/المكان</th>
                            <th class="text-center py-3 px-3 md:px-4 font-bold text-gray-800 text-sm md:text-base">الإجمالي</th>
                            <th class="text-center py-3 px-3 md:px-4 font-bold text-blue-600 text-sm md:text-base">ذكور</th>
                            <th class="text-center py-3 px-3 md:px-4 font-bold text-pink-600 text-sm md:text-base">إناث</th>
                            <th class="text-center py-3 px-3 md:px-4 font-bold text-gray-600 text-sm md:text-base">النسبة المئوية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalInAllLocations = collect($locationsStatistics)->sum('total');
                        @endphp
                        @forelse($locationsStatistics as $stat)
                            <tr class="border-b border-gray-100 hover:bg-white/50 transition">
                                <td class="py-3 px-3 md:px-4 font-bold text-gray-800 text-sm md:text-base">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <span>{{ $stat['location_name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <span class="inline-block px-2 md:px-3 py-1 bg-green-100 text-green-700 rounded-full font-bold text-xs md:text-sm">
                                        {{ number_format($stat['total']) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <span class="inline-block px-2 md:px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs md:text-sm">
                                        <i class="fas fa-mars mr-1"></i>{{ number_format($stat['males']) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <span class="inline-block px-2 md:px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs md:text-sm">
                                        <i class="fas fa-venus mr-1"></i>{{ number_format($stat['females']) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    @if($totalInAllLocations > 0)
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="text-xs md:text-sm font-medium text-gray-600">
                                                {{ number_format(($stat['total'] / $totalInAllLocations) * 100, 1) }}%
                                            </span>
                                            <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full" 
                                                     style="width: {{ ($stat['total'] / $totalInAllLocations) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">لا توجد بيانات عن الأماكن</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($locationsStatistics) > 0)
                        <tfoot>
                            <tr class="border-t-2 border-green-200 bg-green-50 font-bold">
                                <td class="py-3 px-3 md:px-4 text-gray-800 text-sm md:text-base">الإجمالي</td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <span class="inline-block px-2 md:px-3 py-1 bg-green-600 text-white rounded-full text-xs md:text-sm">
                                        {{ number_format($totalInAllLocations) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <span class="inline-block px-2 md:px-3 py-1 bg-blue-600 text-white rounded-full text-xs md:text-sm">
                                        {{ number_format(collect($locationsStatistics)->sum('males')) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <span class="inline-block px-2 md:px-3 py-1 bg-pink-600 text-white rounded-full text-xs md:text-sm">
                                        {{ number_format(collect($locationsStatistics)->sum('females')) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:px-4 text-center">
                                    <span class="text-xs md:text-sm text-gray-600">100%</span>
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </section>

        <!-- القسم الرابع: ترتيب أبناء العائلة حسب العمر -->
        <section class="glass-effect p-4 md:p-6 rounded-2xl green-glow mb-6 md:mb-8 fade-in">
            <div class="flex flex-col md:flex-row items-center gap-3 mb-4 md:mb-6">
                <div class="text-center md:text-right">
                    <h3 class="text-xl md:text-2xl font-bold gradient-text">ترتيب أبناء العائلة حسب العمر</h3>
                </div>
            </div>
            <div class="space-y-2 max-h-96 md:max-h-[600px] overflow-y-auto">
                @forelse($allFamilyMembersByAge as $index => $person)
                    <div class="flex items-center justify-between p-3 bg-white/50 rounded-lg hover:bg-white/70 transition">
                        <div class="flex items-center gap-2 md:gap-3 min-w-0 flex-1">
                            <div class="w-7 h-7 md:w-8 md:h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center text-white font-bold text-xs md:text-sm flex-shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-gray-800 text-sm md:text-base break-words">{{ $person['full_name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $person['birth_date'] ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                        <div class="text-base md:text-lg font-bold text-green-600 flex-shrink-0 ml-3">{{ $person['age'] }} سنة</div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">لا توجد بيانات</p>
                @endforelse
            </div>
        </section>

        <!-- القسم الخامس: إحصائيات الأبناء والأحفاد حسب الجد -->
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
                                    <div class="flex items-center gap-2">
                                        <span>{{ $stat['grandfather_name'] }}</span>
                                        <button onclick="toggleGrandfatherDetails({{ $index }})" 
                                                class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition flex items-center gap-1">
                                            <i class="fas fa-eye"></i>
                                            <span id="view-btn-text-{{ $index }}">استعرض</span>
                                        </button>
                                    </div>
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

                            <!-- صف التفاصيل (مخفى افتراضيًا) -->
                            <tr class="generations-detail-{{ $index }} hidden">
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
                                                    <div class="space-y-2">
                                                        <div class="text-xs font-bold text-gray-700 mb-2">قائمة الأبناء:</div>
                                                        @if(isset($genData['members']) && count($genData['members']) > 0)
                                                            <div class="max-h-40 overflow-y-auto space-y-1">
                                                                @foreach($genData['members'] as $member)
                                                                    <div class="flex items-center justify-between text-xs bg-gray-50 p-2 rounded hover:bg-gray-100 transition cursor-pointer person-clickable" 
                                                                         data-person-id="{{ $member['id'] }}" 
                                                                         onclick="showPersonDetails({{ $member['id'] }})">
                                                                        <span class="text-gray-800 break-words flex-1 hover:text-green-600 transition">{{ $member['full_name'] }}</span>
                                                                        @if($member['gender'] === 'male')
                                                                            <span class="text-blue-600 font-medium mr-2 flex-shrink-0">♂</span>
                                                                        @else
                                                                            <span class="text-pink-600 font-medium mr-2 flex-shrink-0">♀</span>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="text-xs text-gray-400">لا توجد بيانات</div>
                                                        @endif
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

    <!-- Modal لعرض تفاصيل الشخص -->
    <div id="personDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center fade-in" style="display: none;">
        <div class="glass-effect rounded-2xl p-6 md:p-8 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl md:text-3xl font-bold gradient-text" id="modalPersonName">تفاصيل الشخص</h3>
                <button onclick="closePersonModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            
            <div id="modalLoading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                <p class="text-gray-600 mt-2">جاري التحميل...</p>
            </div>
            
            <div id="modalContent" class="hidden">
                <!-- الإحصائيات -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-green-50 p-4 rounded-xl text-center">
                        <div class="text-3xl font-bold text-green-600" id="modalTotal">0</div>
                        <div class="text-sm text-gray-600">إجمالي الأحفاد</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-xl text-center">
                        <div class="text-3xl font-bold text-blue-600" id="modalMales">0</div>
                        <div class="text-sm text-gray-600">ذكور</div>
                    </div>
                    <div class="bg-pink-50 p-4 rounded-xl text-center">
                        <div class="text-3xl font-bold text-pink-600" id="modalFemales">0</div>
                        <div class="text-sm text-gray-600">إناث</div>
                    </div>
                </div>
                
                <!-- تفاصيل الأجيال -->
                <div id="modalGenerations" class="space-y-4"></div>
            </div>
        </div>
    </div>

    <script>
        // دالة لإظهار/إخفاء تفاصيل جد معين
        function toggleGrandfatherDetails(grandfatherIndex) {
            const detailRow = document.querySelector(`.generations-detail-${grandfatherIndex}`);
            const btnText = document.getElementById(`view-btn-text-${grandfatherIndex}`);
            
            if (detailRow.classList.contains('hidden')) {
                detailRow.classList.remove('hidden');
                if (btnText) {
                    btnText.textContent = 'إخفاء';
                }
            } else {
                detailRow.classList.add('hidden');
                if (btnText) {
                    btnText.textContent = 'استعرض';
                }
            }
        }

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

        // دالة لعرض تفاصيل الشخص
        function showPersonDetails(personId) {
            const modal = document.getElementById('personDetailsModal');
            const modalLoading = document.getElementById('modalLoading');
            const modalContent = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            modalLoading.classList.remove('hidden');
            modalContent.classList.add('hidden');
            
            fetch(`/api/reports/person/${personId}/statistics`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // تحديث اسم الشخص
                        document.getElementById('modalPersonName').textContent = data.person.full_name;
                        
                        // تحديث الإحصائيات
                        document.getElementById('modalTotal').textContent = data.statistics.total_descendants;
                        document.getElementById('modalMales').textContent = data.statistics.male_descendants;
                        document.getElementById('modalFemales').textContent = data.statistics.female_descendants;
                        
                        // عرض تفاصيل الأجيال
                        const generationsContainer = document.getElementById('modalGenerations');
                        generationsContainer.innerHTML = '';
                        
                        const generationLabels = {
                            1: 'الجيل الأول (الأبناء)',
                            2: 'الجيل الثاني (الأحفاد)',
                            3: 'الجيل الثالث',
                            4: 'الجيل الرابع',
                            5: 'الجيل الخامس',
                            6: 'الجيل السادس'
                        };
                        
                        if (Object.keys(data.statistics.generations_breakdown).length === 0) {
                            generationsContainer.innerHTML = '<div class="text-center text-gray-500 py-8">لا يوجد أبناء أو أحفاد</div>';
                        } else {
                            Object.keys(data.statistics.generations_breakdown).sort((a, b) => parseInt(a) - parseInt(b)).forEach(genLevel => {
                                const genData = data.statistics.generations_breakdown[genLevel];
                                const genIndex = `modal-${genLevel}`;
                                
                                const genCard = document.createElement('div');
                                genCard.className = 'bg-white p-4 rounded-lg shadow-sm';
                                genCard.innerHTML = `
                                    <div class="flex items-center justify-between mb-3 cursor-pointer" onclick="toggleModalGenerationDetails('${genIndex}')">
                                        <div class="text-sm font-bold text-gray-700">
                                            ${generationLabels[genLevel] || 'الجيل ' + genLevel}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xl font-bold text-green-600">${genData.total || 0}</span>
                                            <svg class="w-4 h-4 text-gray-400 gen-arrow-${genIndex}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 mb-3">
                                        <span class="text-xs text-blue-600">♂ ${genData.males || 0}</span>
                                        <span class="text-xs text-pink-600">♀ ${genData.females || 0}</span>
                                    </div>
                                    <div class="gen-details-${genIndex} hidden mt-3 pt-3 border-t">
                                        <div class="space-y-2">
                                            <div class="text-xs font-bold text-gray-700 mb-2">قائمة الأبناء:</div>
                                            ${genData.members && genData.members.length > 0 ? `
                                                <div class="max-h-40 overflow-y-auto space-y-1">
                                                    ${genData.members.map(member => `
                                                        <div class="flex items-center justify-between text-xs bg-gray-50 p-2 rounded hover:bg-gray-100 transition cursor-pointer" 
                                                             onclick="showPersonDetails(${member.id}); event.stopPropagation();">
                                                            <span class="text-gray-800 break-words flex-1 hover:text-green-600 transition">${member.full_name}</span>
                                                            ${member.gender === 'male' ? 
                                                                '<span class="text-blue-600 font-medium mr-2 flex-shrink-0">♂</span>' : 
                                                                '<span class="text-pink-600 font-medium mr-2 flex-shrink-0">♀</span>'
                                                            }
                                                        </div>
                                                    `).join('')}
                                                </div>
                                            ` : '<div class="text-xs text-gray-400">لا توجد بيانات</div>'}
                                        </div>
                                    </div>
                                `;
                                generationsContainer.appendChild(genCard);
                            });
                        }
                        
                        modalLoading.classList.add('hidden');
                        modalContent.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalLoading.innerHTML = '<div class="text-red-500">حدث خطأ أثناء جلب البيانات</div>';
                });
        }

        // دالة لإغلاق الـ modal
        function closePersonModal() {
            const modal = document.getElementById('personDetailsModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // دالة لإظهار/إخفاء تفاصيل جيل في الـ modal
        function toggleModalGenerationDetails(genIndex) {
            const detailRow = document.querySelector(`.gen-details-${genIndex}`);
            const arrow = document.querySelector(`.gen-arrow-${genIndex}`);

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

        // إغلاق الـ modal عند النقر خارجها
        document.getElementById('personDetailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePersonModal();
            }
        });
    </script>
</body>

</html>
