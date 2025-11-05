@php
    // Helper function للترجمة
    $translateRouteFunc = function($routeName) use ($translatedRouteNames) {
        return $translatedRouteNames[$routeName] ?? $routeName;
    };
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سجل الزيارات - لوحة التحكم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Alexandria', sans-serif;
        }
        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .stat-card {
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }
        .table-row-hover:hover {
            background-color: #f9fafb;
        }
        .badge-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .filter-card {
            transition: all 0.3s ease;
        }
        .filter-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 min-h-screen">

    {{-- Top Navigation Bar --}}
    <nav class="gradient-primary shadow-xl">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-arrow-right text-xl"></i>
                        <span class="font-semibold">العودة للوحة التحكم</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-white">
                        <div class="text-sm opacity-90">سجل الزيارات</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-eye text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-chart-line text-blue-600 mr-3"></i>سجل الزيارات
            </h1>
            <p class="text-gray-600 text-lg">مراقبة وإحصائيات زيارات الموقع بشكل تفصيلي</p>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- إجمالي الزيارات --}}
            <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-r-4 border-blue-500 card-hover">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 text-sm mb-2 font-medium">إجمالي الزيارات</p>
                        <h3 class="text-4xl font-bold text-gray-800 mb-1">{{ number_format($stats['total_visits']) }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <i class="fas fa-chart-line text-blue-500 text-xs"></i>
                            <span class="text-xs text-gray-500">جميع الزيارات المسجلة</span>
                        </div>
                    </div>
                    <div class="w-20 h-20 rounded-full gradient-primary flex items-center justify-center shadow-lg">
                        <i class="fas fa-mouse-pointer text-white text-3xl"></i>
                    </div>
                </div>
            </div>

            {{-- الزيارات الفعلية --}}
            <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-r-4 border-green-500 card-hover">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 text-sm mb-2 font-medium">الزيارات الفعلية</p>
                        <h3 class="text-4xl font-bold text-gray-800 mb-1">{{ number_format($stats['actual_visits']) }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full badge-pulse"></div>
                            <span class="text-xs text-gray-500">{{ round(($stats['actual_visits'] / max($stats['total_visits'], 1)) * 100, 1) }}% من الإجمالي</span>
                        </div>
                    </div>
                    <div class="w-20 h-20 rounded-full gradient-success flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-3xl"></i>
                    </div>
                </div>
            </div>

            {{-- زوار فريدون --}}
            <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-r-4 border-purple-500 card-hover">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 text-sm mb-2 font-medium">زوار فريدون</p>
                        <h3 class="text-4xl font-bold text-gray-800 mb-1">{{ number_format($stats['unique_actual_visitors']) }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <i class="fas fa-users text-purple-500 text-xs"></i>
                            <span class="text-xs text-gray-500">فقط الزيارات الفعلية</span>
                        </div>
                    </div>
                    <div class="w-20 h-20 rounded-full gradient-info flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-3xl"></i>
                    </div>
                </div>
            </div>

            {{-- متوسط المدة --}}
            <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-r-4 border-orange-500 card-hover">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 text-sm mb-2 font-medium">متوسط المدة</p>
                        <h3 class="text-4xl font-bold text-gray-800 mb-1">{{ number_format($stats['avg_duration']) }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <i class="fas fa-clock text-orange-500 text-xs"></i>
                            <span class="text-xs text-gray-500">ثانية لكل صفحة</span>
                        </div>
                    </div>
                    <div class="w-20 h-20 rounded-full gradient-warning flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 filter-card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-filter text-blue-600 mr-3"></i>فلاتر البحث
                </h2>
                <button onclick="document.querySelector('form').reset()" type="button" class="text-sm text-gray-600 hover:text-gray-800">
                    <i class="fas fa-redo mr-1"></i>إعادة تعيين
                </button>
            </div>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-week text-blue-500 mr-1"></i>الأسبوع
                    </label>
                    <select name="week" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        <option value="">— الكل —</option>
                        @foreach ($weekOptions as $key => $label)
                            <option value="{{ $key }}" @selected(request('week') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-green-500 mr-1"></i>من تاريخ
                    </label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           min="{{ $firstVisitDate ?? '' }}"
                           max="{{ $lastVisitDate ?? '' }}"
                           class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-check text-red-500 mr-1"></i>إلى تاريخ
                    </label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           min="{{ $firstVisitDate ?? '' }}"
                           max="{{ $lastVisitDate ?? '' }}"
                           class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-purple-500 mr-1"></i>شخص (IP)
                    </label>
                    <input type="text" name="ip_address" value="{{ request('ip_address') }}" placeholder="مثال: 192.168.1.1" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all font-mono">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-route text-blue-500 mr-1"></i>اسم المسار
                    </label>
                    <select name="route_name" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        <option value="">— الكل —</option>
                        @foreach ($routeNames as $route)
                            <option value="{{ $route }}" @selected(request('route_name') === $route)>
                                {{ $translatedRouteNames[$route] ?? $route }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-code text-orange-500 mr-1"></i>الطريقة
                    </label>
                    <select name="method" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all">
                        <option value="">— الكل —</option>
                        @foreach ($methods as $method)
                            <option value="{{ $method }}" @selected(request('method') === $method)>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-search text-indigo-500 mr-1"></i>البحث
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث في URL, شخص, مسار" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl font-semibold">
                        <i class="fas fa-search mr-2"></i>تطبيق الفلاتر
                    </button>
                    <a href="{{ route('dashboard.visit-logs.index') }}" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all" title="مسح الفلاتر">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Visitors by IP Section --}}
        @if($stats['visitors_by_ip']->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-user-friends text-blue-600 mr-3"></i>الزوار (الأشخاص)
                    </h2>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                        {{ $stats['visitors_by_ip']->count() }} شخص
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">شخص (IP)</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">إجمالي الزيارات</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">زيارات فريدة</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">الصفحات</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">متوسط المدة</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">أول زيارة</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">آخر زيارة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($stats['visitors_by_ip'] as $visitor)
                                <tr class="table-row-hover transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-user text-blue-500"></i>
                                            <span class="font-mono text-blue-600 font-bold text-lg">{{ $visitor['ip'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg font-semibold">{{ $visitor['visit_count'] }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg font-bold">{{ $visitor['unique_visit_count'] }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @if(isset($visitor['routes_translated']))
                                                @foreach(array_slice($visitor['routes_translated'], 0, 3) as $index => $translatedRoute)
                                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs rounded-lg font-medium" title="{{ $visitor['routes'][$index] ?? '' }}">{{ $translatedRoute }}</span>
                                                @endforeach
                                                @if(count($visitor['routes']) > 3)
                                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-lg font-medium">+{{ count($visitor['routes']) - 3 }}</span>
                                                @endif
                                            @else
                                                @foreach(array_slice($visitor['routes'], 0, 3) as $route)
                                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs rounded-lg font-medium">{{ $translateRouteFunc($route) }}</span>
                                                @endforeach
                                                @if(count($visitor['routes']) > 3)
                                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-lg font-medium">+{{ count($visitor['routes']) - 3 }}</span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-purple-100 text-purple-700 rounded-lg font-semibold">{{ $visitor['avg_duration'] }} ث</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar text-xs text-gray-400"></i>
                                            <span>{{ $visitor['first_visit'] ? $visitor['first_visit']->format('Y-m-d H:i') : '—' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar-check text-xs text-gray-400"></i>
                                            <span>{{ $visitor['last_visit'] ? $visitor['last_visit']->format('Y-m-d H:i') : '—' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Pages with Duration Section --}}
        @if($stats['pages_with_duration']->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-file-alt text-blue-600 mr-3"></i>الصفحات الأكثر زيارة مع المدة
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($stats['pages_with_duration'] as $page)
                        <div class="border-2 border-gray-200 rounded-xl p-5 hover:border-blue-300 hover:shadow-lg transition-all card-hover">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 text-lg mb-1">{{ $translateRouteFunc($page->route_name) }}</h3>
                                    <p class="text-sm text-gray-500 truncate" title="{{ $page->url }}">
                                        {{ \Illuminate\Support\Str::limit($page->url, 50) }}
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3 mt-4 pt-4 border-t border-gray-200">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600">{{ $page->unique_count }}</div>
                                    <div class="text-xs text-gray-500 mt-1">زيارات فريدة</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-green-600">{{ round($page->avg_duration ?? 0) }}</div>
                                    <div class="text-xs text-gray-500 mt-1">متوسط المدة (ث)</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-600">{{ $page->visit_count }}</div>
                                    <div class="text-xs text-gray-500 mt-1">إجمالي</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Visits Table --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="px-6 py-4 border-b-2 border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-list text-blue-600 mr-3"></i>قائمة الزيارات
                    </h2>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                        {{ $visitLogs->total() }} زيارة
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">التاريخ</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">شخص / المستخدم</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">الصفحة</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">المسار</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">المدة</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">وقت الاستجابة</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">الحالة</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($visitLogs as $visit)
                            <tr class="table-row-hover transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-sm font-bold text-gray-900">{{ $visit->created_at->format('Y-m-d') }}</div>
                                        <div class="text-xs text-gray-600 font-mono">{{ $visit->created_at->format('H:i:s') }}</div>
                                        <div class="text-xs text-gray-400">{{ $visit->created_at->diffForHumans() }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-2">
                                            @if($visit->is_unique_visit)
                                                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs rounded-full font-semibold">
                                                    <i class="fas fa-check-circle mr-1"></i>فريدة
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-semibold">
                                                    <i class="fas fa-redo mr-1"></i>تحديث
                                                </span>
                                            @endif
                                        </div>
                                        <div class="font-mono text-sm text-blue-600 font-bold">{{ $visit->ip_address }}</div>
                                        @if($visit->user)
                                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                                <i class="fas fa-user text-xs"></i>
                                                {{ $visit->user->name }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <div class="text-sm text-gray-700 truncate font-medium" title="{{ $visit->url }}">
                                            {{ \Illuminate\Support\Str::limit($visit->url, 45) }}
                                        </div>
                                        @if($visit->referer)
                                            <div class="text-xs text-gray-500 truncate mt-1" title="{{ $visit->referer }}">
                                                <i class="fas fa-external-link-alt mr-1"></i>من: {{ \Illuminate\Support\Str::limit($visit->referer, 35) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($visit->route_name)
                                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs rounded-lg font-medium" title="{{ $visit->route_name }}">{{ $translateRouteFunc($visit->route_name) }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($visit->duration)
                                        <span class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg font-bold">{{ $visit->duration }} ث</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($visit->response_time)
                                        @php
                                            $color = $visit->response_time > 1000 ? 'bg-red-100 text-red-700' : ($visit->response_time > 500 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700');
                                        @endphp
                                        <span class="px-3 py-1.5 {{ $color }} rounded-lg text-xs font-semibold">{{ number_format($visit->response_time, 0) }} ملث</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($visit->status_code)
                                        @php
                                            $statusColors = [
                                                200 => 'bg-green-100 text-green-700',
                                                201 => 'bg-green-100 text-green-700',
                                                301 => 'bg-blue-100 text-blue-700',
                                                302 => 'bg-blue-100 text-blue-700',
                                                400 => 'bg-yellow-100 text-yellow-700',
                                                401 => 'bg-yellow-100 text-yellow-700',
                                                403 => 'bg-red-100 text-red-700',
                                                404 => 'bg-yellow-100 text-yellow-700',
                                                500 => 'bg-red-100 text-red-700',
                                            ];
                                            $statusColor = $statusColors[$visit->status_code] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="px-3 py-1.5 {{ $statusColor }} rounded-lg font-bold">{{ $visit->status_code }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('dashboard.visit-logs.show', $visit) }}" class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500 text-lg font-semibold">لا توجد زيارات مسجلة</p>
                                        <p class="text-gray-400 text-sm mt-1">جرب تغيير الفلاتر للعثور على نتائج</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t-2 border-gray-200 bg-gray-50">
                {{ $visitLogs->onEachSide(1)->links() }}
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center py-6 text-gray-500 text-sm">
            <p>سجل الزيارات - نظام إدارة عائلة السريع</p>
            <p class="mt-1">© {{ date('Y') }} جميع الحقوق محفوظة</p>
        </div>
    </div>

    <script>
        // Smooth scroll to top on page load
        window.addEventListener('load', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>
