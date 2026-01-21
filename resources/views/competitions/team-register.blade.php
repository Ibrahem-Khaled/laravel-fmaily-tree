<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التسجيل في الفريق - {{ $team->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-users text-blue-500 mr-2"></i>
                {{ $team->name }}
            </h1>
            <p class="text-gray-600 mb-4">{{ $competition->title }}</p>
            <div class="mt-4 flex justify-center gap-4 text-sm">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                    <i class="fas fa-gamepad mr-1"></i>{{ $competition->game_type }}
                </span>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full">
                    <i class="fas fa-users mr-1"></i>{{ $team->members->count() }} / {{ $competition->team_size }} عضو
                </span>
                @if($team->is_complete)
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full">
                        <i class="fas fa-check-circle mr-1"></i>مكتمل
                    </span>
                @else
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">
                        <i class="fas fa-clock mr-1"></i>يحتاج {{ $team->available_slots }} عضو
                    </span>
                @endif
            </div>
        </div>

        @if($team->is_complete)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                الفريق مكتمل ولا يمكن إضافة أعضاء جدد
            </div>
        @else
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

            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <h3 class="font-bold text-gray-800 mb-3">أعضاء الفريق الحاليون:</h3>
                <div class="space-y-2">
                    @foreach($team->members as $member)
                        <div class="flex items-center justify-between bg-white p-2 rounded">
                            <div>
                                <span class="font-semibold">{{ $member->name }}</span>
                                @if($member->pivot->role === 'captain')
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-2">قائد</span>
                                @endif
                            </div>
                            @if($member->phone)
                                <span class="text-gray-600 text-sm">{{ $member->phone }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <form action="{{ route('competitions.team.register.store', $team->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">
                        الاسم <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">
                        رقم الهاتف <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">
                        البريد الإلكتروني (اختياري)
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-user-plus mr-2"></i>الانضمام للفريق
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600 mb-2">شارك رابط الفريق مع الأعضاء الآخرين:</p>
                <div class="flex items-center justify-center gap-2">
                    <input type="text" id="teamLink" value="{{ $team->registration_url }}" readonly
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <button onclick="copyTeamLink()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-copy"></i> نسخ
                    </button>
                </div>
            </div>
        @endif
    </div>

    <script>
        function copyTeamLink() {
            const linkInput = document.getElementById('teamLink');
            linkInput.select();
            document.execCommand('copy');
            alert('تم نسخ الرابط بنجاح!');
        }
    </script>
</body>
</html>
