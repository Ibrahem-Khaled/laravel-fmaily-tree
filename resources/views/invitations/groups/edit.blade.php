<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تعديل المجموعة - {{ $group->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ar.js"></script>

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

        .select2-container--default .select2-selection--single {
            border: 2px solid #d1d5db !important;
            border-radius: 12px !important;
            height: 50px !important;
            padding: 8px !important;
        }
    </style>
</head>

<body class="antialiased">
    @include('partials.main-header')

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                        <i class="fas fa-edit text-blue-600 mr-2"></i>
                        تعديل المجموعة
                    </h1>
                    <p class="text-gray-600">{{ $group->name }}</p>
                </div>
                <a href="{{ route('invitations.groups.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-right"></i>
                    العودة
                </a>
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

            @if($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border-l-4 border-red-400 p-4">
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Edit Group Info --}}
                <div class="glass-card rounded-2xl p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        معلومات المجموعة
                    </h2>

                    <form action="{{ route('invitations.groups.update', $group) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                                اسم المجموعة
                            </label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $group->name) }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-2">
                                الوصف
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('description', $group->description) }}</textarea>
                        </div>

                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-xl hover:shadow-lg transition">
                            <i class="fas fa-save"></i>
                            حفظ التعديلات
                        </button>
                    </form>
                </div>

                {{-- Add Person --}}
                <div class="glass-card rounded-2xl p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-plus text-green-600"></i>
                        إضافة شخص
                    </h2>

                    <form action="{{ route('invitations.groups.attach-person', $group) }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="person_id" class="block text-sm font-bold text-gray-700 mb-2">
                                ابحث عن شخص
                            </label>
                            <select name="person_id" id="person_id" class="w-full" required></select>
                        </div>

                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl hover:shadow-lg transition">
                            <i class="fas fa-plus"></i>
                            إضافة للمجموعة
                        </button>
                    </form>
                </div>
            </div>

            {{-- Members List --}}
            <div class="glass-card rounded-2xl p-6 mt-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-users text-purple-600"></i>
                    أعضاء المجموعة
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-700">
                        {{ $group->persons->count() }} شخص
                    </span>
                </h2>

                @if($group->persons->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($group->persons as $person)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-user text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $person->first_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $person->full_name }}</p>
                                </div>
                            </div>
                            <form action="{{ route('invitations.groups.detach-person', [$group, $person]) }}" method="POST" onsubmit="return confirm('هل تريد إزالة هذا الشخص من المجموعة؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full bg-red-100 hover:bg-red-200 flex items-center justify-center transition">
                                    <i class="fas fa-times text-red-600 text-sm"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-friends text-4xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-600">لا يوجد أعضاء في هذه المجموعة</p>
                        <p class="text-sm text-gray-500 mt-1">استخدم النموذج أعلاه لإضافة أشخاص</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    $(function() {
        // Init Select2 for person search
        var $personId = $('#person_id');
        if ($personId.data('select2')) $personId.select2('destroy');
        $personId.select2({
            placeholder: 'ابحث بالاسم...',
            dir: 'rtl',
            width: '100%',
            language: 'ar',
            ajax: {
                url: '{{ route("invitations.persons-with-whatsapp") }}',
                dataType: 'json',
                delay: 300,
                data: function(params) { return { q: params.term || '' }; },
                processResults: function(data) {
                    var list = (data && data.persons) ? data.persons : [];
                    return { results: list.map(function(p) { return { id: p.id, text: p.full_name }; }) };
                },
                cache: true
            },
            minimumInputLength: 1
        });
    });
    </script>
</body>
</html>
