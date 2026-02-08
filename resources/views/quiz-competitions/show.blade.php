<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quizCompetition->title }} - مسابقات الأسئلة</title>
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

        .slide-in { animation: slideIn 0.6s ease-out forwards; }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .green-glow { box-shadow: 0 0 40px rgba(34, 197, 94, 0.3); }
        .green-glow-hover:hover {
            box-shadow: 0 0 60px rgba(34, 197, 94, 0.5);
            transform: translateY(-5px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #4ade80 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .question-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(20px);
        }
        .question-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .question-card:hover {
            transform: translateY(-5px);
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

    <div class="container mx-auto px-4 py-6 lg:py-10 relative z-10 max-w-5xl">
        {{-- التنقل --}}
        <div class="mb-6 flex flex-wrap items-center gap-2 text-sm">
            <a href="{{ route('quiz-competitions.index') }}" class="text-green-600 hover:text-green-700 transition-colors font-medium">
                <i class="fas fa-arrow-right ml-1"></i> المسابقات
            </a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-600">{{ Str::limit($quizCompetition->title, 40) }}</span>
        </div>

        {{-- رأس المسابقة --}}
        <div class="glass-effect rounded-3xl green-glow p-5 md:p-8 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 left-0 h-1.5" style="background: linear-gradient(90deg, #22c55e, #16a34a, #22c55e);"></div>
            <div class="flex items-start gap-5">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-green-500 to-green-600 shadow-lg" style="box-shadow: 0 8px 25px rgba(34, 197, 94, 0.4);">
                    <i class="fas fa-trophy text-white text-2xl md:text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold gradient-text mb-2">{{ $quizCompetition->title }}</h1>
                    @if($quizCompetition->description)
                        <p class="text-gray-500 text-sm md:text-base leading-relaxed mb-3">{{ $quizCompetition->description }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-3 text-xs">
                        <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-full border border-green-200">
                            <i class="fas fa-question-circle"></i>
                            {{ $quizCompetition->questions->count() }} سؤال
                        </span>
                        @if($questions->count() > 0)
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2.5 py-1 rounded-full font-bold border border-green-300">
                                <i class="fas fa-play-circle"></i>
                                {{ $questions->count() }} نشط
                            </span>
                        @endif
                        @if($upcomingQuestions->count() > 0)
                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full border border-amber-200">
                                <i class="fas fa-clock"></i>
                                {{ $upcomingQuestions->count() }} قادم
                            </span>
                        @endif
                        @if($endedQuestions->count() > 0)
                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full border border-gray-200">
                                <i class="fas fa-check-circle"></i>
                                {{ $endedQuestions->count() }} منتهي
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- الأسئلة النشطة --}}
        @if($questions->count() > 0)
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-3 h-3 bg-green-500 rounded-full relative">
                        <span class="absolute inset-0 bg-green-400 rounded-full animate-ping"></span>
                    </span>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">أسئلة نشطة الآن</h2>
                    <span class="bg-gradient-to-r from-green-500 to-green-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $questions->count() }}</span>
                </div>
                <div class="space-y-4">
                    @foreach($questions as $question)
                        <a href="{{ route('quiz-competitions.question', [$quizCompetition, $question]) }}"
                           class="question-card visible block glass-effect rounded-2xl p-5 md:p-6 green-glow-hover relative overflow-hidden group">
                            <div class="absolute top-0 right-0 left-0 h-1" style="background: linear-gradient(90deg, #22c55e, #4ade80);"></div>

                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-[11px] font-bold px-2.5 py-1 rounded-full border border-red-200">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                                    مباشر الآن
                                </span>
                                <span class="text-gray-400 text-xs">
                                    <i class="fas fa-hourglass-half text-amber-500 ml-1"></i>
                                    ينتهي: {{ $quizCompetition->end_at?->format('H:i') ?? '—' }}
                                </span>
                            </div>

                            <p class="text-lg md:text-xl font-bold text-gray-800 mb-4 group-hover:text-green-600 transition-colors leading-relaxed">
                                {{ $question->question_text }}
                            </p>

                            <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold transition-all group-hover:-translate-y-0.5"
                                  style="background: linear-gradient(135deg, #22c55e, #16a34a); box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);">
                                <i class="fas fa-pen-fancy"></i>
                                أجب الآن
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- الأسئلة القادمة --}}
        @if($upcomingQuestions->count() > 0)
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-5">
                    <i class="fas fa-clock text-amber-500"></i>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">أسئلة قادمة</h2>
                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full border border-amber-200">{{ $upcomingQuestions->count() }}</span>
                </div>
                <div class="space-y-3">
                    @foreach($upcomingQuestions as $question)
                        <div class="question-card visible glass-effect rounded-2xl p-5 opacity-80">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-700 mb-2">{{ $question->question_text }}</p>
                                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs px-3 py-1.5 rounded-full border border-amber-200">
                                        <i class="fas fa-calendar-alt"></i>
                                        يبدأ: {{ $quizCompetition->start_at?->format('H:i') ?? '—' }} - {{ $quizCompetition->start_at?->translatedFormat('d M Y') ?? '—' }}
                                    </span>
                                </div>
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-amber-50 border border-amber-200">
                                    <i class="fas fa-lock text-amber-400"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- الأسئلة المنتهية --}}
        @if($endedQuestions->count() > 0)
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-5">
                    <i class="fas fa-check-circle text-gray-400"></i>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-600">أسئلة منتهية</h2>
                    <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-full border border-gray-200">{{ $endedQuestions->count() }}</span>
                </div>
                <div class="space-y-6">
                    @foreach($endedQuestions as $question)
                        <div class="question-card visible glass-effect rounded-2xl p-5 md:p-6">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div class="flex-1">
                                    <a href="{{ route('quiz-competitions.question', [$quizCompetition, $question]) }}" class="block group">
                                        <p class="font-bold text-gray-700 mb-2 group-hover:text-green-600 transition-colors">{{ $question->question_text }}</p>
                                    </a>
                                    <div class="flex flex-wrap items-center gap-3 text-xs mb-4">
                                        <span class="text-gray-400">
                                            <i class="fas fa-calendar-check ml-1"></i>
                                            انتهى: {{ $quizCompetition->end_at?->translatedFormat('d M Y - H:i') ?? '—' }}
                                        </span>
                                        <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full border border-blue-200">
                                            {{ $question->answers->count() }} إجابة
                                        </span>
                                        <span class="bg-green-50 text-green-600 px-2.5 py-1 rounded-full border border-green-200">
                                            {{ $question->answers->where('is_correct', true)->count() }} صحيح
                                        </span>
                                        <span class="bg-red-50 text-red-600 px-2.5 py-1 rounded-full border border-red-200">
                                            {{ $question->answers->where('is_correct', false)->count() }} خاطئ
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('quiz-competitions.question', [$quizCompetition, $question]) }}" class="text-green-600 text-sm font-medium hover:text-green-700 flex-shrink-0">
                                    تفاصيل كاملة <i class="fas fa-arrow-left mr-1"></i>
                                </a>
                            </div>

                            {{-- من جاوب صح --}}
                            @php $correctAnswers = $question->answers->where('is_correct', true); @endphp
                            @if($correctAnswers->count() > 0)
                                <div class="mb-4">
                                    <h4 class="text-sm font-bold text-green-600 mb-2 flex items-center gap-2">
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
                            @if($question->winners->count() > 0)
                                <div class="pt-4 border-t border-amber-200">
                                    <h4 class="text-sm font-bold text-amber-600 mb-2 flex items-center gap-2">
                                        <i class="fas fa-trophy"></i>
                                        الفائزون ({{ $question->winners->count() }})
                                    </h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($question->winners as $winner)
                                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-3 py-1.5 rounded-lg text-sm font-bold border border-amber-200">
                                                @if($winner->position == 1)
                                                    <i class="fas fa-crown text-amber-500"></i>
                                                @endif
                                                {{ $winner->position }}. {{ $winner->user->name ?? '-' }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- لا توجد أسئلة --}}
        @if($questions->count() == 0 && $upcomingQuestions->count() == 0 && $endedQuestions->count() == 0)
            <div class="text-center glass-effect p-8 lg:p-16 rounded-3xl green-glow">
                <div class="w-20 h-20 rounded-full mx-auto mb-5 flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100">
                    <i class="fas fa-question-circle text-green-300 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">لا توجد أسئلة في هذه المسابقة بعد</h3>
                <p class="text-gray-500">ترقبوا الأسئلة القادمة</p>
            </div>
        @endif
    </div>
</body>

</html>
