<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل طلب الاستعارة</title>
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
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 space-x-reverse text-sm">
                <li><a href="{{ route('home') }}" class="text-orange-600 hover:text-orange-700">الرئيسية</a></li>
                <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                <li><a href="{{ route('my-rentals.index') }}" class="text-orange-600 hover:text-orange-700">طلباتي</a></li>
                <li><i class="fas fa-chevron-left text-gray-400"></i></li>
                <li class="text-gray-600">تفاصيل الطلب</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="glass-effect rounded-3xl p-8">
                <h2 class="text-2xl font-bold mb-6">معلومات المنتج</h2>

                @if($request->product->main_image)
                    <img src="{{ asset('storage/' . $request->product->main_image) }}"
                         alt="{{ $request->product->name }}"
                         class="w-full h-64 object-cover rounded-2xl mb-4">
                @endif

                <h3 class="text-xl font-bold mb-4">{{ $request->product->name }}</h3>

                @if($request->product->description)
                    <p class="text-gray-600 mb-4">{{ $request->product->description }}</p>
                @endif

                <a href="{{ route('rental.show', $request->product) }}"
                   class="inline-block bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all">
                    <i class="fas fa-eye mr-2"></i>عرض المنتج
                </a>
            </div>

            <div class="glass-effect rounded-3xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold">تفاصيل الطلب</h2>
                    <span class="status-badge status-{{ $request->status }}">
                        {{ $request->status_label }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="text-gray-600 text-sm">الاسم</label>
                        <p class="font-medium">{{ $request->name }}</p>
                    </div>

                    <div>
                        <label class="text-gray-600 text-sm">رقم الهاتف</label>
                        <p class="font-medium">{{ $request->phone }}</p>
                    </div>

                    @if($request->email)
                        <div>
                            <label class="text-gray-600 text-sm">البريد الإلكتروني</label>
                            <p class="font-medium">{{ $request->email }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="text-gray-600 text-sm">تاريخ بداية الاستعارة</label>
                        <p class="font-medium">{{ $request->start_date->format('Y/m/d') }}</p>
                    </div>

                    <div>
                        <label class="text-gray-600 text-sm">تاريخ نهاية الاستعارة</label>
                        <p class="font-medium">{{ $request->end_date->format('Y/m/d') }}</p>
                    </div>

                    @if($request->notes)
                        <div>
                            <label class="text-gray-600 text-sm">ملاحظات</label>
                            <p class="font-medium">{{ $request->notes }}</p>
                        </div>
                    @endif

                    @if($request->admin_notes)
                        <div class="mt-6 p-4 bg-gray-100 rounded-xl">
                            <label class="text-gray-600 text-sm font-bold">ملاحظات الإدارة</label>
                            <p class="font-medium mt-2">{{ $request->admin_notes }}</p>
                        </div>
                    @endif

                    <div class="pt-4 border-t">
                        <label class="text-gray-600 text-sm">تاريخ الإرسال</label>
                        <p class="font-medium">{{ $request->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
