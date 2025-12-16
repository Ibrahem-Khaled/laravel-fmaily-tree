<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات الاستعارة الخاصة بي</title>
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

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-completed { background: #dbeafe; color: #1e40af; }
    </style>
</head>

<body class="text-gray-800">
    @include('partials.main-header')

    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-black mb-2" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 50%, #ea580c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <i class="fas fa-list mr-3"></i>طلبات الاستعارة الخاصة بي
            </h1>
            <p class="text-gray-600">عرض جميع طلبات الاستعارة التي قدمتها</p>
        </div>

        @if($requests->count() > 0)
            <div class="space-y-4">
                @foreach($requests as $request)
                    <div class="glass-effect rounded-2xl p-6 hover:shadow-lg transition-all">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-2">
                                    <h3 class="text-xl font-bold text-gray-800">{{ $request->product->name }}</h3>
                                    <span class="status-badge status-{{ $request->status }}">
                                        {{ $request->status_label }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p><i class="fas fa-calendar-alt mr-2"></i>من {{ $request->start_date->format('Y/m/d') }} إلى {{ $request->end_date->format('Y/m/d') }}</p>
                                    <p><i class="fas fa-clock mr-2"></i>تم الإرسال: {{ $request->created_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('my-rentals.show', $request) }}"
                                   class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all">
                                    <i class="fas fa-eye mr-2"></i>عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $requests->links() }}
            </div>
        @else
            <div class="glass-effect rounded-3xl p-12 text-center">
                <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-2xl font-bold mb-3">لا توجد طلبات حالياً</h3>
                <p class="text-gray-600 mb-6">لم تقم بتقديم أي طلبات استعارة بعد</p>
                <a href="{{ route('rental.index') }}"
                   class="inline-block bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-3 rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all">
                    <i class="fas fa-dumbbell mr-2"></i>تصفح الأدوات المتاحة
                </a>
            </div>
        @endif
    </div>
</body>

</html>
