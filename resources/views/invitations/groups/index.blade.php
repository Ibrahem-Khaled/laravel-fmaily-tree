<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المجموعات - الدعوات</title>
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
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
            transform: translateY(-5px);
        }

        .btn-delete:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body class="antialiased">
    @include('partials.main-header')

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        إدارة المجموعات
                    </h1>
                    <p class="text-gray-600">أنشئ وعدّل مجموعات المستلمين</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('invitations.groups.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl hover:shadow-lg transition">
                        <i class="fas fa-plus"></i>
                        إنشاء مجموعة جديدة
                    </a>
                    <a href="{{ route('invitations.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-3 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-right"></i>
                        العودة
                    </a>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-xl bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl mr-3"></i>
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Groups Grid --}}
            @if($groups->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($groups as $group)
                    <div class="glass-card rounded-2xl p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-users text-2xl text-white"></i>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('invitations.groups.edit', $group) }}" class="w-8 h-8 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center transition">
                                    <i class="fas fa-edit text-blue-600 text-sm"></i>
                                </a>
                                <form action="{{ route('invitations.groups.destroy', $group) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المجموعة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full bg-red-100 hover:bg-red-200 flex items-center justify-center transition btn-delete">
                                        <i class="fas fa-trash text-red-600 text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $group->name }}</h3>
                        @if($group->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $group->description }}</p>
                        @endif

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-user-friends text-green-600"></i>
                                <span><strong class="text-gray-900">{{ $group->persons_count }}</strong> شخص</span>
                            </div>
                            <span class="text-xs text-gray-500">
                                {{ $group->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="glass-card rounded-2xl p-12 text-center">
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-5xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد مجموعات</h3>
                    <p class="text-gray-600 mb-6">أنشئ أول مجموعة لك الآن</p>
                    <a href="{{ route('invitations.groups.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl hover:shadow-lg transition">
                        <i class="fas fa-plus"></i>
                        إنشاء مجموعة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
