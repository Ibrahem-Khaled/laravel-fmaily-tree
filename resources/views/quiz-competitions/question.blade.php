<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Str::limit($quizQuestion->question_text, 50) }} - {{ $quizCompetition->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
            min-height: 100vh;
        }
        h1, h2, h3 { font-family: 'Amiri', serif; }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse-soft {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .slide-in { animation: slideIn 0.6s ease-out forwards; }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .green-glow { box-shadow: 0 0 40px rgba(34, 197, 94, 0.3); }

        .gradient-text {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #4ade80 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .choice-option {
            background: rgba(255, 255, 255, 0.7);
            border: 2px solid #e5e7eb;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .choice-option:hover {
            background: rgba(240, 253, 244, 0.9);
            border-color: #86efac;
            transform: scale(1.01);
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.15);
        }
        .choice-option:has(input:checked) {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border-color: #22c55e;
            box-shadow: 0 0 30px rgba(34, 197, 94, 0.2);
        }
        .choice-option:has(input:checked) .choice-number {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #f0fdf4; }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e, #16a34a);
            border-radius: 5px;
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    {{-- زخارف الخلفية --}}
    <div class="fixed top-10 right-10 w-96 h-96 opacity-5 pointer-events-none hidden lg:block" style="animation: float 6s ease-in-out infinite;">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#22c55e" d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3Z" transform="translate(100 100)" />
        </svg>
    </div>

    <div class="container mx-auto px-4 py-6 lg:py-10 relative z-10 max-w-3xl">
        {{-- التنقل --}}
        <div class="mb-6 flex flex-wrap items-center gap-2 text-xs md:text-sm">
            <a href="{{ route('quiz-competitions.index') }}" class="text-green-600 hover:text-green-700 transition-colors font-medium">المسابقات</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('quiz-competitions.show', $quizCompetition) }}" class="text-green-600 hover:text-green-700 transition-colors font-medium">{{ Str::limit($quizCompetition->title, 30) }}</a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-600">السؤال</span>
        </div>

        {{-- ==================== لم يبدأ بعد ==================== --}}
        @if($status === 'not_started')
            <div class="glass-effect rounded-3xl green-glow p-6 md:p-10 text-center slide-in">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-3xl mx-auto mb-6 flex items-center justify-center bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
                    <i class="fas fa-hourglass-start text-amber-500 text-3xl md:text-4xl" style="animation: pulse-soft 2s infinite;"></i>
                </div>

                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">السؤال لم يبدأ بعد</h2>
                <p class="text-gray-500 text-sm mb-6">سيتم فتح السؤال في الموعد المحدد</p>

                {{-- عرض السؤال --}}
                <div class="rounded-2xl p-5 mb-8 text-right bg-gradient-to-br from-green-50 to-green-100 border border-green-200">
                    <p class="text-green-600 text-xs mb-2 font-medium"><i class="fas fa-question-circle ml-1"></i> السؤال:</p>
                    <p class="text-gray-800 font-bold text-lg">{{ $quizQuestion->question_text }}</p>
                </div>

                {{-- العد التنازلي --}}
                <div class="mb-4">
                    @if($quizCompetition->start_at)
                    <p class="text-gray-500 text-sm mb-4">يبدأ خلال:</p>
                    <div class="flex justify-center gap-3 md:gap-4" id="notStartedCountdown">
                        <div class="text-center">
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mb-2 bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
                                <span class="text-2xl md:text-3xl font-bold text-amber-600" id="ns-days">0</span>
                            </div>
                            <p class="text-gray-500 text-xs">يوم</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mb-2 bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
                                <span class="text-2xl md:text-3xl font-bold text-amber-600" id="ns-hours">0</span>
                            </div>
                            <p class="text-gray-500 text-xs">ساعة</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mb-2 bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
                                <span class="text-2xl md:text-3xl font-bold text-amber-600" id="ns-minutes">0</span>
                            </div>
                            <p class="text-gray-500 text-xs">دقيقة</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mb-2 bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
                                <span class="text-2xl md:text-3xl font-bold text-amber-600" id="ns-seconds">0</span>
                            </div>
                            <p class="text-gray-500 text-xs">ثانية</p>
                        </div>
                    </div>
                    <input type="hidden" id="nsTarget" value="{{ $quizCompetition->start_at->getTimestamp() * 1000 }}">
                    @else
                    <p class="text-gray-500 text-sm">سيتم تحديد موعد بدء المسابقة لاحقاً</p>
                    @endif
                </div>
            </div>

        {{-- ==================== انتهى ==================== --}}
        @elseif($status === 'ended')
            <div class="space-y-6 slide-in">
                {{-- بطاقة السؤال --}}
                <div class="glass-effect rounded-3xl green-glow p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1.5" style="background: linear-gradient(90deg, #22c55e, #16a34a, #22c55e);"></div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-xs px-3 py-1.5 rounded-full border border-gray-200">
                            <i class="fas fa-flag-checkered"></i> انتهى
                        </span>
                    </div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2 leading-relaxed">{{ $quizQuestion->question_text }}</h2>
                    <p class="text-gray-400 text-xs">
                        <i class="fas fa-calendar ml-1"></i>
                        @if($quizCompetition->start_at && $quizCompetition->end_at)
                            {{ $quizCompetition->start_at->translatedFormat('d M') }} - {{ $quizCompetition->end_at->translatedFormat('d M Y') }}
                        @else
                            —
                        @endif
                    </p>
                </div>

                {{-- الإحصائيات --}}
                <div class="grid grid-cols-3 gap-3 md:gap-4">
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
                        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200">
                            <i class="fas fa-users text-blue-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجمالي المشاركين</p>
                    </div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center" style="box-shadow: 0 0 20px rgba(34, 197, 94, 0.15);">
                        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 border border-green-200">
                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-green-600">{{ $stats['correct'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجابة صحيحة</p>
                    </div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
                        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100 border border-red-200">
                            <i class="fas fa-times-circle text-red-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-red-500">{{ $stats['wrong'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجابة خاطئة</p>
                    </div>
                </div>

                {{-- شريط التقدم --}}
                @if($stats['total'] > 0)
                    <div class="glass-effect rounded-2xl p-5">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                            <span>نسبة الإجابات الصحيحة</span>
                            <span class="text-green-600 font-bold text-sm">{{ round(($stats['correct'] / $stats['total']) * 100) }}%</span>
                        </div>
                        <div class="w-full h-3 rounded-full overflow-hidden bg-gray-200">
                            <div class="h-full rounded-full transition-all duration-1000" style="width: {{ ($stats['correct'] / $stats['total']) * 100 }}%; background: linear-gradient(90deg, #22c55e, #16a34a);"></div>
                        </div>
                    </div>
                @endif

                {{-- من جاوب صح --}}
                @php $correctAnswers = $quizQuestion->answers->where('is_correct', true); @endphp
                @if($correctAnswers->count() > 0)
                    <div class="glass-effect rounded-2xl p-5">
                        <h4 class="text-sm font-bold text-green-600 mb-3 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            من جاوب صح ({{ $correctAnswers->count() }})
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($correctAnswers as $ans)
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1.5 rounded-lg text-sm border border-green-200">
                                    {{ $ans->user->name ?? '-' }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- الفائزون --}}
                @if($quizQuestion->winners->count() > 0)
                    <div class="glass-effect rounded-3xl p-6 md:p-8 relative overflow-hidden" style="box-shadow: 0 0 30px rgba(251, 191, 36, 0.2);">
                        <div class="absolute top-0 right-0 left-0 h-1.5" style="background: linear-gradient(90deg, #f59e0b, #d97706, #f59e0b);"></div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gradient-to-br from-amber-400 to-amber-500 shadow-lg" style="box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);">
                                <i class="fas fa-trophy text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">الفائزون</h3>
                        </div>
                        <div class="space-y-3">
                            @foreach($quizQuestion->winners as $winner)
                                <div class="flex items-center gap-4 rounded-xl p-4 transition-all bg-gradient-to-l from-amber-50 to-white border border-amber-200/50">
                                    <div class="flex items-center justify-center flex-shrink-0">
                                        @if($winner->position <= 3)
                                            <span class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg text-white shadow-lg"
                                                  style="background: linear-gradient(135deg, {{ $winner->position == 1 ? '#f59e0b, #d97706' : ($winner->position == 2 ? '#9ca3af, #6b7280' : '#cd7f32, #a0522d') }});">
                                                {{ $winner->position }}
                                            </span>
                                        @else
                                            <span class="w-10 h-10 rounded-full flex items-center justify-center font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                                {{ $winner->position }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800">{{ $winner->user->name ?? '-' }}</p>
                                    </div>
                                    @if($winner->position == 1)
                                        <i class="fas fa-crown text-amber-400 text-lg"></i>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        {{-- ==================== نشط ==================== --}}
        @else
            <div class="space-y-6 slide-in">
                {{-- بطاقة السؤال --}}
                <div class="glass-effect rounded-3xl green-glow p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1.5" style="background: linear-gradient(90deg, #22c55e, #16a34a, #22c55e);"></div>

                    {{-- شارة مباشر + المؤقت --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                        <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-bold px-3 py-1.5 rounded-full border border-red-200">
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            مباشر الآن
                        </span>
                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <i class="fas fa-hourglass-half text-amber-500 text-xs"></i>
                            <span>متبقي:</span>
                            <div class="flex gap-1" id="activeTimer">
                                <span class="bg-green-50 border border-green-200 rounded-lg px-2 py-1 text-green-700 font-bold text-sm min-w-[2rem] text-center" id="at-hours">00</span>
                                <span class="text-gray-400 font-bold">:</span>
                                <span class="bg-green-50 border border-green-200 rounded-lg px-2 py-1 text-green-700 font-bold text-sm min-w-[2rem] text-center" id="at-minutes">00</span>
                                <span class="text-gray-400 font-bold">:</span>
                                <span class="bg-green-50 border border-green-200 rounded-lg px-2 py-1 text-green-700 font-bold text-sm min-w-[2rem] text-center" id="at-seconds">00</span>
                            </div>
                        </div>
                        @if($quizCompetition->end_at)
                        <input type="hidden" id="atEndTime" value="{{ $quizCompetition->end_at->getTimestamp() * 1000 }}">
                        @endif
                    </div>

                    {{-- اسم المسابقة --}}
                    <p class="text-green-600 text-xs font-medium mb-3">
                        <i class="fas fa-trophy ml-1"></i> {{ $quizCompetition->title }}
                    </p>

                    {{-- نص السؤال --}}
                    <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 leading-relaxed">
                        {{ $quizQuestion->question_text }}
                    </h1>
                </div>

                {{-- الإحصائيات (نشط) --}}
                <div class="grid grid-cols-3 gap-3 md:gap-4">
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
                        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200">
                            <i class="fas fa-users text-blue-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجمالي الإجابات</p>
                    </div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center" style="box-shadow: 0 0 20px rgba(34, 197, 94, 0.15);">
                        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 border border-green-200">
                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-green-600">{{ $stats['correct'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجابة صحيحة</p>
                    </div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
                        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100 border border-red-200">
                            <i class="fas fa-times-circle text-red-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-red-500">{{ $stats['wrong'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجابة خاطئة</p>
                    </div>
                </div>

                {{-- التنبيهات --}}
                @if(session('success'))
                    <div class="rounded-2xl p-4 md:p-5 flex items-center gap-3 bg-green-50 border border-green-200 slide-in">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-green-400 to-green-500">
                            <i class="fas fa-check-circle text-white text-lg"></i>
                        </div>
                        <p class="text-green-700 font-bold">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="rounded-2xl p-4 md:p-5 flex items-center gap-3 bg-red-50 border border-red-200 slide-in">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-red-400 to-red-500">
                            <i class="fas fa-exclamation-circle text-white text-lg"></i>
                        </div>
                        <p class="text-red-700 font-bold">{{ session('error') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded-2xl p-4 md:p-5 bg-red-50 border border-red-200">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-red-600 text-sm flex items-center gap-2">
                                    <i class="fas fa-circle text-[6px] text-red-400"></i>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- تمت الإجابة في هذه الجلسة --}}
                @if(!session('success') && !($canAnswer ?? true))
                    <div class="rounded-2xl p-5 md:p-6 flex items-center gap-3 bg-amber-50 border border-amber-200">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-amber-100">
                            <i class="fas fa-check-circle text-amber-600"></i>
                        </div>
                        <p class="text-amber-800 font-medium">لقد أجبت على هذا السؤال</p>
                    </div>
                @endif

                {{-- نموذج الإجابة --}}
                @if(!session('success') && ($canAnswer ?? true))
                    <form action="{{ route('quiz-competitions.store-answer', [$quizCompetition, $quizQuestion]) }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- البيانات الشخصية --}}
                        <div class="glass-effect rounded-2xl p-5 md:p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 border border-blue-300">
                                    <i class="fas fa-user text-blue-500 text-sm"></i>
                                </div>
                                <h3 class="font-bold text-gray-800">بياناتك</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2 font-medium">الاسم <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all hover:border-green-300"
                                           placeholder="أدخل اسمك الكامل">
                                </div>
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2 font-medium">رقم الهاتف <span class="text-red-500">*</span></label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" required
                                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all hover:border-green-300"
                                           placeholder="05xxxxxxxx" dir="ltr" style="text-align: right;">
                                </div>
                            </div>
                        </div>

                        {{-- قسم الإجابة --}}
                        <div class="glass-effect rounded-2xl p-5 md:p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gradient-to-br from-green-100 to-green-200 border border-green-300">
                                    <i class="fas fa-pen text-green-600 text-sm"></i>
                                </div>
                                <h3 class="font-bold text-gray-800">إجابتك</h3>
                            </div>

                            @if($quizQuestion->answer_type === 'multiple_choice')
                                <div class="space-y-3">
                                    @foreach($quizQuestion->choices as $choice)
                                        <label class="choice-option flex items-center gap-4 p-4 rounded-xl cursor-pointer">
                                            <input type="radio" name="answer" value="{{ $choice->id }}" class="hidden" required>
                                            <span class="choice-number w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm flex-shrink-0 transition-all">
                                                {{ $loop->iteration }}
                                            </span>
                                            <span class="text-gray-700 font-medium text-sm md:text-base">{{ $choice->choice_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <textarea name="answer" rows="4" required
                                          class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm resize-none focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all hover:border-green-300"
                                          placeholder="اكتب إجابتك هنا...">{{ old('answer') }}</textarea>
                            @endif
                        </div>

                        {{-- زر الإرسال --}}
                        <button type="submit"
                                class="w-full py-4 md:py-5 rounded-2xl text-white font-bold text-lg flex items-center justify-center gap-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl active:scale-[0.98]"
                                style="background: linear-gradient(135deg, #22c55e, #16a34a); box-shadow: 0 8px 25px rgba(34, 197, 94, 0.4);">
                            <i class="fas fa-paper-plane"></i>
                            <span>إرسال الإجابة</span>
                        </button>
                    </form>
                @endif
            </div>
        @endif

        {{-- رابط العودة --}}
        <div class="mt-8 text-center">
            <a href="{{ route('quiz-competitions.show', $quizCompetition) }}"
               class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 transition-colors text-sm font-medium">
                <i class="fas fa-arrow-right"></i>
                <span>العودة للمسابقة</span>
            </a>
        </div>
    </div>

    <script>
        {{-- العد التنازلي - لم يبدأ --}}
        @if($status === 'not_started' && $quizCompetition->start_at)
        (function() {
            var target = new Date(parseInt(document.getElementById('nsTarget').value, 10));
            function update() {
                var diff = target - new Date();
                if (diff <= 0) { location.reload(); return; }
                document.getElementById('ns-days').textContent = Math.floor(diff / 86400000);
                document.getElementById('ns-hours').textContent = Math.floor((diff % 86400000) / 3600000);
                document.getElementById('ns-minutes').textContent = Math.floor((diff % 3600000) / 60000);
                document.getElementById('ns-seconds').textContent = Math.floor((diff % 60000) / 1000);
            }
            update();
            setInterval(update, 1000);
        })();
        @endif

        {{-- المؤقت - نشط --}}
        @if($status === 'active' && $quizCompetition->end_at)
        (function() {
            var end = new Date(parseInt(document.getElementById('atEndTime').value, 10));
            function pad(n) { return n.toString().padStart(2, '0'); }
            function update() {
                var diff = end - new Date();
                if (diff <= 0) { location.reload(); return; }
                document.getElementById('at-hours').textContent = pad(Math.floor(diff / 3600000));
                document.getElementById('at-minutes').textContent = pad(Math.floor((diff % 3600000) / 60000));
                document.getElementById('at-seconds').textContent = pad(Math.floor((diff % 60000) / 1000));
            }
            update();
            setInterval(update, 1000);
        })();
        @endif
    </script>
</body>

</html>
