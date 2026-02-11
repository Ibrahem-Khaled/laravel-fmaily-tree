<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء مجموعة جديدة - الدعوات</title>
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
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body class="antialiased">
    @include('partials.main-header')

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 shadow-2xl mb-4">
                    <i class="fas fa-users text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                    إنشاء مجموعة جديدة
                </h1>
                <p class="text-gray-600">أنشئ مجموعة لحفظ المستلمين المفضلين</p>
            </div>

            <div class="glass-card rounded-2xl p-8">
                @if($errors->any())
                    <div class="mb-6 rounded-xl bg-red-50 border-l-4 border-red-400 p-4">
                        <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('invitations.groups.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-tag text-green-600 mr-2"></i>
                            اسم المجموعة
                        </label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="مثال: العائلة، الأصدقاء، الزملاء">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-align-right text-green-600 mr-2"></i>
                            الوصف
                            <span class="text-xs text-gray-500 font-normal mr-2">(اختياري)</span>
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="وصف قصير عن المجموعة...">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="flex-1 inline-flex justify-center items-center gap-2 px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl hover:shadow-lg transition">
                            <i class="fas fa-check"></i>
                            إنشاء المجموعة
                        </button>
                        <a href="{{ route('invitations.groups.index') }}" class="px-6 py-4 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">
                            إلغاء
                        </a>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-sm text-gray-600 flex items-start gap-2">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                        <span>بعد إنشاء المجموعة، يمكنك إضافة الأشخاص إليها من صفحة تعديل المجموعة.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
