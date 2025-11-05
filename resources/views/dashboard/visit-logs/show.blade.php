<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تفاصيل الزيارة - لوحة التحكم</title>
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
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 min-h-screen">
    
    {{-- Top Navigation Bar --}}
    <nav class="gradient-primary shadow-xl">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard.visit-logs.index') }}" class="flex items-center gap-2 text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-arrow-right text-xl"></i>
                        <span class="font-semibold">العودة للقائمة</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="font-semibold">لوحة التحكم</span>
                    </a>
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
                <i class="fas fa-info-circle text-blue-600 mr-3"></i>تفاصيل الزيارة
            </h1>
            <p class="text-gray-600 text-lg">معلومات تفصيلية عن الزيارة</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Information --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Info Card --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 card-hover">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b-2 border-gray-200">
                        <i class="fas fa-info-circle text-blue-600 mr-3"></i>معلومات الزيارة
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl">
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                <i class="fas fa-calendar-alt text-blue-500 mr-1"></i>التاريخ والوقت
                            </label>
                            <div class="text-xl font-bold text-gray-800 mb-1">{{ $visitLog->created_at->format('Y-m-d H:i:s') }}</div>
                            <div class="text-sm text-gray-600">{{ $visitLog->created_at->diffForHumans() }}</div>
                        </div>

                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl">
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                <i class="fas fa-tag text-green-500 mr-1"></i>نوع الزيارة
                            </label>
                            <div class="mt-1">
                                @if($visitLog->is_unique_visit)
                                    <span class="px-4 py-2 bg-green-200 text-green-800 rounded-full text-sm font-bold inline-flex items-center gap-2">
                                        <i class="fas fa-check-circle"></i>زيارة فريدة
                                    </span>
                                @else
                                    <span class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full text-sm font-bold inline-flex items-center gap-2">
                                        <i class="fas fa-redo"></i>تحديث صفحة
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl">
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                <i class="fas fa-user text-purple-500 mr-1"></i>المستخدم
                            </label>
                            <div class="mt-1">
                                @if($visitLog->user)
                                    <div class="text-xl font-bold text-gray-800">{{ $visitLog->user->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $visitLog->user->email }}</div>
                                @else
                                    <span class="text-gray-500 text-lg">زائر</span>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-xl">
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                <i class="fas fa-network-wired text-indigo-500 mr-1"></i>عنوان IP
                            </label>
                            <div class="mt-1">
                                <span class="px-4 py-2 bg-indigo-200 text-indigo-800 rounded-lg font-mono font-bold text-lg">{{ $visitLog->ip_address }}</span>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 p-4 rounded-xl">
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                <i class="fas fa-clock text-teal-500 mr-1"></i>المدة في الصفحة
                            </label>
                            <div class="mt-1">
                                @if($visitLog->duration)
                                    <span class="px-4 py-2 bg-teal-200 text-teal-800 rounded-lg text-xl font-bold">{{ $visitLog->duration }} ثانية</span>
                                @else
                                    <span class="text-gray-500">غير محسوبة</span>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-xl">
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                <i class="fas fa-tachometer-alt text-orange-500 mr-1"></i>وقت الاستجابة
                            </label>
                            <div class="mt-1">
                                @if($visitLog->response_time)
                                    @php
                                        $color = $visitLog->response_time > 1000 ? 'bg-red-200 text-red-800' : ($visitLog->response_time > 500 ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800');
                                    @endphp
                                    <span class="px-4 py-2 {{ $color }} rounded-lg text-xl font-bold">{{ number_format($visitLog->response_time, 2) }} ملث</span>
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- URL & Route Info --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 card-hover">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b-2 border-gray-200">
                        <i class="fas fa-link text-blue-600 mr-3"></i>معلومات الصفحة
                    </h2>
                    <div class="space-y-6">
                        <div>
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                <i class="fas fa-globe text-blue-500 mr-1"></i>الرابط (URL)
                            </label>
                            <div class="mt-2 p-4 bg-gray-50 rounded-lg border-2 border-gray-200">
                                <a href="{{ $visitLog->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 break-all font-medium flex items-center gap-2">
                                    {{ $visitLog->url }}
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                    <i class="fas fa-route text-gray-500 mr-1"></i>اسم المسار
                                </label>
                                <div class="mt-2">
                                    @if($visitLog->route_name)
                                        <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold">{{ $visitLog->route_name }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                    <i class="fas fa-code text-gray-500 mr-1"></i>الطريقة
                                </label>
                                <div class="mt-2">
                                    @php
                                        $methodColors = [
                                            'GET' => 'bg-green-100 text-green-700',
                                            'POST' => 'bg-blue-100 text-blue-700',
                                            'PUT' => 'bg-yellow-100 text-yellow-700',
                                            'PATCH' => 'bg-blue-100 text-blue-700',
                                            'DELETE' => 'bg-red-100 text-red-700',
                                        ];
                                        $color = $methodColors[$visitLog->method] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-4 py-2 {{ $color }} rounded-lg font-bold text-lg">{{ $visitLog->method }}</span>
                                </div>
                            </div>
                        </div>

                        @if($visitLog->referer)
                            <div>
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                    <i class="fas fa-external-link-alt text-gray-500 mr-1"></i>المرجع (Referer)
                                </label>
                                <div class="mt-2 p-4 bg-gray-50 rounded-lg border-2 border-gray-200">
                                    <a href="{{ $visitLog->referer }}" target="_blank" class="text-blue-600 hover:text-blue-800 break-all font-medium flex items-center gap-2">
                                        {{ $visitLog->referer }}
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                <i class="fas fa-check-circle text-gray-500 mr-1"></i>رمز الحالة
                            </label>
                            <div class="mt-2">
                                @if($visitLog->status_code)
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
                                        $statusColor = $statusColors[$visitLog->status_code] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-4 py-2 {{ $statusColor }} rounded-lg text-xl font-bold">{{ $visitLog->status_code }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Technical Info --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 card-hover">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b-2 border-gray-200">
                        <i class="fas fa-cog text-blue-600 mr-3"></i>معلومات تقنية
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                <i class="fas fa-id-card text-gray-500 mr-1"></i>Session ID
                            </label>
                            <div class="mt-2">
                                <code class="px-4 py-3 bg-gray-100 text-gray-700 rounded-lg text-sm break-all block font-mono">{{ $visitLog->session_id ?? '—' }}</code>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                <i class="fas fa-fingerprint text-gray-500 mr-1"></i>Request ID
                            </label>
                            <div class="mt-2">
                                <code class="px-4 py-3 bg-gray-100 text-gray-700 rounded-lg text-sm break-all block font-mono">{{ $visitLog->request_id ?? '—' }}</code>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                <i class="fas fa-info text-gray-500 mr-1"></i>User Agent
                            </label>
                            <div class="mt-2">
                                <code class="px-4 py-3 bg-gray-100 text-gray-700 rounded-lg text-sm break-all block font-mono" style="direction: ltr;">{{ $visitLog->user_agent ?? '—' }}</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Metadata Card --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 card-hover">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b-2 border-gray-200">
                        <i class="fas fa-map-marked-alt text-blue-600 mr-3"></i>الميتا داتا
                    </h2>
                    <div class="space-y-6">
                        @if($visitLog->country)
                            <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                    <i class="fas fa-map-marker-alt text-green-600 mr-1"></i>الموقع
                                </label>
                                <div class="mt-2">
                                    <div class="text-lg font-bold text-gray-800">{{ $visitLog->city }}</div>
                                    <div class="text-sm text-gray-600">{{ $visitLog->country }}</div>
                                    @if($visitLog->region)
                                        <div class="text-xs text-gray-500 mt-1">{{ $visitLog->region }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($visitLog->browser)
                            <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                    <i class="fas fa-globe text-blue-600 mr-1"></i>المتصفح
                                </label>
                                <div class="mt-2">
                                    <div class="text-lg font-bold text-gray-800">{{ $visitLog->browser }}</div>
                                    @if($visitLog->platform)
                                        <div class="text-sm text-gray-600">{{ $visitLog->platform }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($visitLog->device)
                            <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                    <i class="fas fa-mobile-alt text-purple-600 mr-1"></i>الجهاز
                                </label>
                                <div class="mt-2">
                                    <span class="text-lg font-bold text-gray-800">{{ $visitLog->device }}</span>
                                </div>
                            </div>
                        @endif

                        @if(isset($visitLog->metadata['isp']))
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">
                                    <i class="fas fa-network-wired text-gray-600 mr-1"></i>مزود الخدمة
                                </label>
                                <div class="mt-2">
                                    <span class="text-gray-800 font-medium">{{ $visitLog->metadata['isp'] }}</span>
                                </div>
                            </div>
                        @endif

                        @if($visitLog->metadata)
                            <div class="pt-6 border-t-2 border-gray-200">
                                <label class="text-sm font-semibold text-gray-600 mb-3 block">
                                    <i class="fas fa-database text-gray-500 mr-1"></i>جميع الميتا داتا
                                </label>
                                <pre class="bg-gray-50 p-4 rounded-lg text-xs overflow-auto max-h-96 border-2 border-gray-200" style="direction: ltr;"><code>{{ json_encode($visitLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center py-6 text-gray-500 text-sm mt-8">
            <p>تفاصيل الزيارة - نظام إدارة عائلة السريع</p>
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
