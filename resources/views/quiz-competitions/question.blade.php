<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Str::limit(strip_tags($quizQuestion->question_text), 50) }} - {{ strip_tags($quizCompetition->title) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Tajawal', sans-serif; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%); min-height: 100vh; }
        h1, h2, h3 { font-family: 'Amiri', serif; }

        @keyframes slideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse-soft { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        @keyframes float { 0% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-20px) rotate(180deg); } 100% { transform: translateY(0) rotate(360deg); } }

        .slide-in { animation: slideIn 0.6s ease-out forwards; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .green-glow { box-shadow: 0 0 40px rgba(34, 197, 94, 0.3); }

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
        .choice-option:has(input:checked) .choice-number { background: linear-gradient(135deg, #22c55e, #16a34a); color: white; }
        .choice-option:has(input[type="checkbox"]:checked) .custom-checkbox { background-color: #22c55e; border-color: #22c55e; }
        .choice-option:has(input[type="checkbox"]:checked) .custom-checkbox i { opacity: 1; }

        .question-text { direction: rtl; text-align: right; }
        .question-text p { margin-bottom: 0.5rem; }
        .question-text strong, .question-text b { font-weight: 700; }
        .question-text em, .question-text i { font-style: italic; }
        .question-text ul, .question-text ol { margin-right: 1.5rem; margin-bottom: 0.5rem; }
        .question-text li { margin-bottom: 0.25rem; }
        .question-text a { color: #22c55e; text-decoration: underline; }
        .question-text img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 0.5rem 0; }
        .quiz-description iframe, .quiz-description video,
        .question-text iframe, .question-text video {
            max-width: 100%; height: auto; aspect-ratio: 16 / 9; border-radius: 0.5rem; margin: 0.5rem 0;
        }

        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #f0fdf4; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #22c55e, #16a34a); border-radius: 5px; }
    </style>

    <x-quiz-lottery
        :quizCompetition="$quizCompetition"
        :quizQuestion="$quizQuestion"
        :status="$status"
        :candidateNames="$candidateNames ?? []"
        :selectionAt="$selectionAt ?? null"
        :stats="$stats ?? []"
    />
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    @include('partials.main-header')

    <div class="fixed top-10 right-10 w-96 h-96 opacity-5 pointer-events-none hidden lg:block" style="animation: float 6s ease-in-out infinite;">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#22c55e" d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3Z" transform="translate(100 100)" />
        </svg>
    </div>

    <div class="container mx-auto px-4 py-6 lg:py-10 relative z-10 max-w-3xl">
        {{-- Breadcrumb --}}
        <div class="mb-6 flex flex-wrap items-center gap-2 text-xs md:text-sm">
            <a href="{{ route('home') }}#activeQuizSection" class="text-green-600 hover:text-green-700 transition-colors font-medium">الرئيسية - المسابقات</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('quiz-competitions.show', $quizCompetition) }}" class="text-green-600 hover:text-green-700 transition-colors font-medium">{{ Str::limit($quizCompetition->title, 30) }}</a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-600">السؤال</span>
        </div>

        {{-- ==================== لم يبدأ ==================== --}}
        @if($status === 'not_started')
            <div class="glass-effect rounded-3xl green-glow p-6 md:p-10 text-center slide-in">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-3xl mx-auto mb-6 flex items-center justify-center bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
                    <i class="fas fa-hourglass-start text-amber-500 text-3xl md:text-4xl" style="animation:pulse-soft 2s infinite;"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">السؤال لم يبدأ بعد</h2>
                <p class="text-gray-500 text-sm mb-6">سيتم فتح السؤال في الموعد المحدد</p>
                <div class="rounded-2xl p-5 mb-8 text-right bg-gradient-to-br from-green-50 to-green-100 border border-green-200">
                    <p class="text-green-600 text-xs mb-2 font-medium"><i class="fas fa-question-circle ml-1"></i> السؤال:</p>
                    <div class="text-gray-800 font-bold text-lg question-text">{!! $quizQuestion->question_text !!}</div>
                    @if($quizQuestion->description)
                        <div class="text-gray-600 text-sm mt-2 quiz-description">{!! $quizQuestion->description !!}</div>
                    @endif
                </div>
                @if($quizCompetition->start_at)
                    <p class="text-gray-500 text-sm mb-4">يبدأ خلال:</p>
                    <div class="flex justify-center gap-3 md:gap-4">
                        @foreach(['ns-days' => 'يوم', 'ns-hours' => 'ساعة', 'ns-minutes' => 'دقيقة', 'ns-seconds' => 'ثانية'] as $id => $label)
                            <div class="text-center">
                                <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mb-2 bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
                                    <span class="text-2xl md:text-3xl font-bold text-amber-600" id="{{ $id }}">0</span>
                                </div>
                                <p class="text-gray-500 text-xs">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">سيتم تحديد موعد بدء المسابقة لاحقاً</p>
                @endif
            </div>

        {{-- ==================== السؤال مقفل ==================== --}}
        @elseif($status === 'question_locked')
            <div class="glass-effect rounded-3xl green-glow p-6 md:p-10 text-center slide-in">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-3xl mx-auto mb-6 flex items-center justify-center bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
                    <i class="fas fa-lock text-amber-500 text-3xl md:text-4xl" style="animation:pulse-soft 2s infinite;"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">السؤال يظهر قريباً</h2>
                <p class="text-gray-500 text-sm mb-6">سيظهر نص السؤال ونموذج الإجابة بعد مرور الوقت المحدد من بدء المسابقة</p>
                @if(isset($questionsVisibleAt) && $questionsVisibleAt)
                    <p class="text-gray-500 text-sm mb-4">السؤال يظهر بعد:</p>
                    <div class="flex justify-center mb-6">
                        <div class="text-center">
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mb-2 bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200">
                                <span class="text-2xl md:text-3xl font-bold text-green-600" id="ql-seconds">0</span>
                            </div>
                            <p class="text-gray-500 text-xs">ثانية</p>
                        </div>
                    </div>
                @endif
            </div>

        {{-- ==================== انتهى ==================== --}}
        @elseif($status === 'ended')
            @php
                $hideWinnersForAnim = false;
                if (isset($selectionAt) && $selectionAt) {
                    $hideWinnersForAnim = now()->timestamp < ($selectionAt->timestamp + 20) && !request()->has('animation_done');
                }
            @endphp

            <div class="space-y-6 slide-in">
                {{-- Question Card --}}
                <div class="glass-effect rounded-3xl green-glow p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1.5" style="background:linear-gradient(90deg,#22c55e,#16a34a,#22c55e);"></div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-xs px-3 py-1.5 rounded-full border border-gray-200">
                            <i class="fas fa-flag-checkered"></i> انتهت المسابقة
                        </span>
                    </div>
                    <div class="text-xl md:text-2xl font-bold text-gray-800 mb-2 leading-relaxed question-text">{!! $quizQuestion->question_text !!}</div>
                    @if($quizQuestion->description)
                        <div class="text-gray-600 text-sm mb-2 quiz-description">{!! $quizQuestion->description !!}</div>
                    @endif
                    <p class="text-gray-400 text-xs">
                        <i class="fas fa-calendar ml-1"></i>
                        @if($quizCompetition->start_at && $quizCompetition->end_at)
                            {{ $quizCompetition->start_at->translatedFormat('d M') }} - {{ $quizCompetition->end_at->translatedFormat('d M Y') }}
                        @else — @endif
                    </p>
                </div>

                {{-- Stats --}}
                @include('quiz-competitions._stats-grid', ['stats' => $stats])

                @if($stats['total'] > 0)
                    <div class="glass-effect rounded-2xl p-5">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                            <span>نسبة الإجابات الصحيحة</span>
                            <span class="text-green-600 font-bold text-sm">{{ round(($stats['correct'] / $stats['total']) * 100) }}%</span>
                        </div>
                        <div class="w-full h-3 rounded-full overflow-hidden bg-gray-200">
                            <div class="h-full rounded-full" style="width:{{ ($stats['correct'] / $stats['total']) * 100 }}%;background:linear-gradient(90deg,#22c55e,#16a34a);transition:width 1s;"></div>
                        </div>
                    </div>
                @endif

                {{-- Winners --}}
                @if($quizQuestion->winners->count() > 0)
                    <div id="winnersSection" class="relative" style="{{ $hideWinnersForAnim ? 'display:none;' : '' }}">
                        <div class="glass-effect rounded-3xl p-6 md:p-8 relative overflow-hidden lt-glow-breath" style="border:2px solid rgba(245,158,11,0.3);">
                            <div class="absolute top-0 right-0 left-0 h-2" style="background:linear-gradient(90deg,#f59e0b,#fbbf24,#f59e0b,#fbbf24,#f59e0b);"></div>

                            {{-- الراعي يمين ويسار كلمة الفائزون --}}
                            <div class="flex flex-row items-center justify-between gap-3 sm:gap-6 mb-8 relative z-10 w-full max-w-4xl mx-auto">
                                @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
                                    @php
                                        $sponsorRight = $quizCompetition->sponsors->first();
                                        $sponsorLeft = $quizCompetition->sponsors->count() > 1 ? $quizCompetition->sponsors->last() : $quizCompetition->sponsors->first();
                                    @endphp
                                    {{-- راعي على اليمين --}}
                                    <div class="flex-shrink-0 order-1 rounded-2xl p-1 md:p-2 bg-white/60 backdrop-blur-sm border border-amber-200 shadow-md flex items-center justify-center relative w-14 h-14 sm:w-16 sm:h-16 md:w-24 md:h-24">
                                        <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-amber-400 text-amber-900 text-[7px] md:text-[10px] font-bold px-1.5 py-0.5 rounded shadow whitespace-nowrap">برعاية</span>
                                        @if($sponsorRight->image)
                                            <img src="{{ asset('storage/' . $sponsorRight->image) }}" class="max-h-full max-w-full object-contain rounded-xl" alt="">
                                        @else
                                            <span class="text-[8px] md:text-xs font-bold text-gray-800 text-center leading-tight">{{ $sponsorRight->name }}</span>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex-1 text-center min-w-0 order-2 px-2">
                                    <div class="inline-block lt-crown-anim" style="animation-delay:0.4s;">
                                        <i class="fas fa-crown text-amber-400 text-3xl sm:text-4xl md:text-5xl" style="filter:drop-shadow(0 0 20px rgba(245,158,11,0.5));"></i>
                                    </div>
                                    <h3 class="text-xl sm:text-2xl md:text-3xl font-bold mt-1 md:mt-3 leading-tight">
                                        <span class="lt-shimmer-gold">الفائزون</span>
                                    </h3>
                                </div>

                                @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
                                    {{-- راعي على اليسار --}}
                                    <div class="flex-shrink-0 order-3 rounded-2xl p-1 md:p-2 bg-white/60 backdrop-blur-sm border border-amber-200 shadow-md flex items-center justify-center relative w-14 h-14 sm:w-16 sm:h-16 md:w-24 md:h-24">
                                        <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-amber-400 text-amber-900 text-[7px] md:text-[10px] font-bold px-1.5 py-0.5 rounded shadow whitespace-nowrap">برعاية</span>
                                        @if($sponsorLeft->image)
                                            <img src="{{ asset('storage/' . $sponsorLeft->image) }}" class="max-h-full max-w-full object-contain rounded-xl" alt="">
                                        @else
                                            <span class="text-[8px] md:text-xs font-bold text-gray-800 text-center leading-tight">{{ $sponsorLeft->name }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- Winner Cards --}}
                            <div class="space-y-4">
                                @foreach($quizQuestion->winners as $winner)
                                    @php
                                        $isFirst = $winner->position == 1;
                                        $isTop3 = $winner->position <= 3;
                                        $bgGrad = $isFirst ? 'rgba(245,158,11,0.08),rgba(251,191,36,0.15)' : 'rgba(255,255,255,0.6),rgba(249,250,251,0.8)';
                                        $borderCol = $isFirst ? 'rgba(245,158,11,0.3)' : 'rgba(229,231,235,0.5)';
                                        $posGrad = $isFirst ? '#f59e0b,#d97706' : ($winner->position == 2 ? '#9ca3af,#6b7280' : '#cd7f32,#a0522d');
                                        $prize = '-';
                                        if (!empty($quizQuestion->prize)) {
                                            $prize = is_array($quizQuestion->prize)
                                                ? ($quizQuestion->prize[$winner->position - 1] ?? $quizQuestion->prize[0] ?? '-')
                                                : $quizQuestion->prize;
                                        }
                                    @endphp
                                    <div class="lt-winner-card-anim flex items-center gap-4 rounded-2xl p-5 relative overflow-hidden"
                                         style="animation-delay:{{ 0.7 + ($loop->index * 0.3) }}s; background:linear-gradient(135deg,{{ $bgGrad }}); border:2px solid {{ $borderCol }};">
                                        @if($isFirst)
                                            <div class="absolute inset-0 opacity-20" style="background:radial-gradient(ellipse at 30% 50%,rgba(245,158,11,0.3),transparent 70%);"></div>
                                        @endif
                                        <div class="flex-shrink-0 relative z-10">
                                            @if($isTop3)
                                                <span class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-xl text-white shadow-xl" style="background:linear-gradient(135deg,{{ $posGrad }});">{{ $winner->position }}</span>
                                            @else
                                                <span class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-xl bg-gray-100 text-gray-500 border-2 border-gray-200">{{ $winner->position }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 relative z-10">
                                            <p class="font-bold text-lg md:text-xl {{ $isFirst ? 'text-amber-800' : 'text-gray-800' }}">{{ $winner->user->name ?? '-' }}</p>
                                            <p class="font-bold text-green-600 text-sm md:text-base mt-2">مبروك عليك {{ $prize }}</p>
                                        </div>
                                        @if($isFirst)
                                            <i class="fas fa-crown text-amber-400 text-2xl relative z-10" style="filter:drop-shadow(0 0 10px rgba(245,158,11,0.4));"></i>
                                        @elseif($isTop3)
                                            <i class="fas fa-medal text-{{ $winner->position == 2 ? 'gray-400' : 'amber-700/60' }} text-xl relative z-10"></i>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @php $correctAnswers = $quizQuestion->answers->where('is_correct', true); @endphp
                @if($correctAnswers->count() > 0)
                    <div id="correctAnswersSection" class="glass-effect rounded-2xl p-5" style="{{ $hideWinnersForAnim ? 'display:none;' : '' }}">
                        <h4 class="text-sm font-bold text-green-600 mb-3 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> من جاوب صح ({{ $correctAnswers->count() }})
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($correctAnswers as $ans)
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1.5 rounded-lg text-sm border border-green-200">{{ $ans->user->name ?? '-' }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($quizQuestion->winners->count() === 0 && !(isset($selectionAt) && $selectionAt && now()->lt($selectionAt)))
                    <div class="glass-effect rounded-2xl p-6 text-center" style="{{ $hideWinnersForAnim ? 'display:none;' : '' }}">
                        <i class="fas fa-hourglass-end text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500 font-medium">لم يتم اختيار فائزين بعد</p>
                    </div>
                @endif
            </div>

        {{-- ==================== نشط ==================== --}}
        @else
            <div class="space-y-6 slide-in">
                {{-- Question Card --}}
                <div class="glass-effect rounded-3xl green-glow p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1.5" style="background:linear-gradient(90deg,#22c55e,#16a34a,#22c55e);"></div>
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                        <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-bold px-3 py-1.5 rounded-full border border-red-200">
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span> مباشر الآن
                        </span>
                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <i class="fas fa-hourglass-half text-amber-500 text-xs"></i>
                            <span>متبقي:</span>
                            <div class="flex gap-1 flex-row-reverse">
                                @foreach(['at-hours', 'at-minutes', 'at-seconds'] as $tid)
                                    @if(!$loop->first)<span class="text-gray-400 font-bold">:</span>@endif
                                    <span class="bg-green-50 border border-green-200 rounded-lg px-2 py-1 text-green-700 font-bold text-sm min-w-[2rem] text-center" id="{{ $tid }}">00</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <p class="text-green-600 text-xs font-medium mb-3"><i class="fas fa-trophy ml-1"></i> {{ $quizCompetition->title }}</p>
                    <div class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 leading-relaxed question-text">{!! $quizQuestion->question_text !!}</div>
                    @if($quizQuestion->description)
                        <div class="text-gray-600 text-sm mt-2 quiz-description">{!! $quizQuestion->description !!}</div>
                    @endif

                    @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
                        <div class="mt-6 pt-5 border-t border-green-100 flex flex-wrap items-center gap-3">
                            <span class="text-sm font-bold text-gray-500"><i class="fas fa-handshake ml-1"></i> برعاية:</span>
                            @foreach($quizCompetition->sponsors as $sponsor)
                                <div class="bg-white/60 border border-green-50 rounded-lg px-3 py-1.5 flex items-center gap-2 justify-center shadow-sm" title="{{ $sponsor->name }}">
                                    @if($sponsor->image)
                                        <img src="{{ asset('storage/' . $sponsor->image) }}" alt="{{ $sponsor->name }}" class="h-6 md:h-8 object-contain rounded">
                                    @endif
                                    <span class="text-sm font-bold text-green-700">{{ $sponsor->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Stats --}}
                @include('quiz-competitions._stats-grid', ['stats' => $stats])

                {{-- Alerts --}}
                @if(session('error'))
                    <div class="rounded-2xl p-4 flex items-center gap-3 bg-red-50 border border-red-200 slide-in">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-red-400 to-red-500">
                            <i class="fas fa-exclamation-circle text-white text-lg"></i>
                        </div>
                        <p class="text-red-700 font-bold">{{ session('error') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded-2xl p-4 bg-red-50 border border-red-200">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-red-600 text-sm flex items-center gap-2"><i class="fas fa-circle text-[6px] text-red-400"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('answer_submitted'))
                    <div class="rounded-2xl p-5 flex items-center gap-3 bg-amber-50 border border-amber-200">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-amber-100">
                            <i class="fas fa-check-circle text-amber-600"></i>
                        </div>
                        <p class="text-amber-800 font-medium">لقد أجبت على هذا السؤال. سيتم اختيار الفائز تلقائياً بعد انتهاء وقت المسابقة.</p>
                    </div>
                @endif

                @if(!session('answer_submitted') && !($canAnswer ?? true))
                    <div class="rounded-2xl p-5 flex items-center gap-3 bg-amber-50 border border-amber-200">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-amber-100">
                            <i class="fas fa-check-circle text-amber-600"></i>
                        </div>
                        <p class="text-amber-800 font-medium">لقد أجبت على هذا السؤال</p>
                    </div>
                @endif

                {{-- Form / Draw-Only --}}
                @if(!session('answer_submitted') && ($canAnswer ?? true))
                    @if($quizCompetition->show_draw_only)
                        <div class="rounded-3xl p-8 bg-green-50 border-2 border-green-200 text-center space-y-4">
                            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-info-circle text-green-600 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-green-800">باب الإجابة مغلق حالياً</h3>
                            <p class="text-green-700">يمكنك الآن متابعة فرز النتائج والقرعة لاختيار الفائزين مباشرة.</p>
                            <div class="pt-4">
                                <span class="px-6 py-3 rounded-2xl font-bold bg-green-600 text-white inline-flex items-center gap-2">
                                    <i class="fas fa-sync fa-spin"></i> يرجى الانتظار لبدء السحب...
                                </span>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('quiz-competitions.store-answer', [$quizCompetition, $quizQuestion]) }}" method="POST" class="space-y-5" id="quizForm">
                    @endif

                    @csrf
                    {{-- User Data --}}
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
                                       class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all"
                                       placeholder="أدخل اسمك الكامل">
                            </div>
                            <div>
                                <label class="block text-gray-600 text-sm mb-2 font-medium">رقم الهاتف <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                       pattern="[0-9]{10}" minlength="10" maxlength="10"
                                       class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all"
                                       placeholder="05xxxxxxxx" dir="ltr" style="text-align:right;" title="يجب أن يكون رقم الهاتف 10 أرقام">
                            </div>
                        </div>
                        <div class="space-y-3 mt-4">
                            <div class="flex items-center gap-2 p-3 rounded-xl border-2 border-gray-200 bg-white/80 hover:border-green-300 hover:bg-green-50/50 cursor-pointer transition-all">
                                <input type="checkbox" name="is_from_ancestry" value="1" id="is_from_ancestry_question"
                                       class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500" {{ old('is_from_ancestry') ? 'checked' : '' }}
                                       onchange="toggleMotherNameFieldQuestion(this.checked)">
                                <label for="is_from_ancestry_question" class="text-gray-800 text-sm font-medium cursor-pointer">أنا من الأنساب</label>
                            </div>
                            <div id="mother_name_field_question" class="hidden">
                                <label class="block text-gray-600 text-sm mb-2 font-medium">اسم الأم <span class="text-red-500">*</span></label>
                                <input type="text" name="mother_name" value="{{ old('mother_name') }}"
                                       class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all"
                                       placeholder="اسم الأم الكامل">
                            </div>
                        </div>
                    </div>

                    {{-- Answer Choices --}}
                    <div class="glass-effect rounded-2xl p-5 md:p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gradient-to-br from-green-100 to-green-200 border border-green-300">
                                <i class="fas fa-pen text-green-600 text-sm"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">إجابتك</h3>
                        </div>
                        <div class="space-y-3">
                            @if($quizQuestion->answer_type === 'ordering' && $quizQuestion->choices->count() > 0)
                                <p class="text-sm text-green-700 font-medium mb-2 pr-2">
                                    <i class="fas fa-info-circle ml-1"></i> قم بسحب وإفلات الخيارات لترتيبها بشكل صحيح
                                </p>
                                @php $shuffledChoices = $quizQuestion->choices->shuffle(); @endphp
                                <div class="space-y-2 sortable-list" data-question-id="{{ $quizQuestion->id }}">
                                    @foreach($shuffledChoices as $choice)
                                        <div data-id="{{ $choice->id }}" class="flex flex-col gap-2 p-4 rounded-xl border-2 border-gray-200 bg-white hover:border-green-300 cursor-move transition-all sortable-item select-none shadow-sm text-gray-700">
                                            <div class="flex items-center gap-4">
                                                <i class="fas fa-grip-lines text-gray-400"></i>
                                                @if($choice->image)
                                                    <img src="{{ asset('storage/' . $choice->image) }}" class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                                @endif
                                                <span class="font-medium text-sm md:text-base flex-grow">{{ $choice->choice_text }}</span>
                                                <input type="hidden" name="answer[]" value="{{ $choice->id }}" class="ordering-input-hidden">
                                            </div>
                                            @if($choice->video)
                                                <video src="{{ asset('storage/' . $choice->video) }}" class="w-full max-h-48 rounded-lg mt-2" controls></video>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($quizQuestion->answer_type === 'custom_text')
                                <textarea name="answer" rows="4" required
                                          class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm resize-none focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all"
                                          placeholder="اكتب إجابتك هنا...">{{ old('answer') }}</textarea>
                            @else
                                @if(!$quizQuestion->is_multiple_selections && $quizQuestion->choices->count() === 1)
                                    {{-- Single answer: one prominent button --}}
                                    @php $singleChoice = $quizQuestion->choices->first(); @endphp
                                    <input type="hidden" name="answer" value="{{ $singleChoice->id }}">
                                    @if($singleChoice->image)
                                        <div class="flex justify-center mb-3">
                                            <img src="{{ asset('storage/' . $singleChoice->image) }}" class="w-24 h-24 object-cover rounded-xl shadow-sm">
                                        </div>
                                    @endif
                                    @if($singleChoice->video)
                                        <video src="{{ asset('storage/' . $singleChoice->video) }}" class="w-full max-h-64 rounded-xl mb-3 border border-gray-100 shadow-inner" controls></video>
                                    @endif
                                @else
                                    @if($quizQuestion->is_multiple_selections)
                                        @php $requiredCount = $quizQuestion->getRequiredCorrectAnswersCount(); @endphp
                                        <p class="text-sm text-green-700 font-medium mb-2 pr-2">
                                            <i class="fas fa-info-circle ml-1"></i> بجب اختيار عدد ({{ $requiredCount }}) إجابات صحيحة
                                            <input type="hidden" id="requiredChoicesCount" value="{{ $requiredCount }}">
                                        </p>
                                    @endif

                                    @foreach($quizQuestion->choices as $choice)
                                        <label class="choice-option flex items-center gap-4 p-4 rounded-xl cursor-pointer">
                                            @if($quizQuestion->is_multiple_selections)
                                                <input type="checkbox" name="answer[]" value="{{ $choice->id }}" class="hidden quiz-checkbox-input">
                                                <div class="relative w-6 h-6 rounded flex items-center justify-center border-2 border-gray-300 bg-gray-100 flex-shrink-0 transition-all custom-checkbox">
                                                    <i class="fas fa-check text-white text-xs opacity-0 transition-opacity"></i>
                                                </div>
                                            @else
                                                <input type="radio" name="answer" value="{{ $choice->id }}" class="hidden" required>
                                                <span class="choice-number w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm flex-shrink-0 transition-all">{{ $loop->iteration }}</span>
                                            @endif
                                            <div class="flex-grow flex flex-col gap-2">
                                                <div class="flex items-center gap-3">
                                                    @if($choice->image)
                                                        <img src="{{ asset('storage/' . $choice->image) }}" class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg shadow-sm">
                                                    @endif
                                                    <span class="text-gray-700 font-medium text-sm md:text-base">{{ $choice->choice_text }}</span>
                                                </div>
                                                @if($choice->video)
                                                    <video src="{{ asset('storage/' . $choice->video) }}" class="w-full max-h-64 rounded-xl mt-2 border border-gray-100 shadow-inner" controls></video>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                @endif
                            @endif
                        </div>

                        @if(!$quizCompetition->show_draw_only)
                            <div class="pt-4">
                                @if($quizQuestion->answer_type === 'multiple_choice' && !$quizQuestion->is_multiple_selections && $quizQuestion->choices->count() === 1)
                                    <button type="button" onclick="submitQuizForm()"
                                            class="w-full py-5 rounded-2xl font-bold text-white text-lg shadow-lg shadow-green-200 transition-all hover:scale-[1.02] active:scale-[0.98] bg-gradient-to-r from-green-600 to-green-500 hover:from-green-500 hover:to-green-400"
                                            style="animation: pulse-soft 2s infinite;">
                                        <i class="fas fa-hand-pointer ml-2"></i> {{ $quizQuestion->choices->first()->choice_text }}
                                    </button>
                                @else
                                    <button type="button" onclick="submitQuizForm()"
                                            class="w-full py-4 rounded-2xl font-bold text-white shadow-lg shadow-green-200 transition-all hover:scale-[1.02] active:scale-[0.98] bg-gradient-to-r from-green-600 to-green-500 hover:from-green-500 hover:to-green-400">
                                        <i class="fas fa-paper-plane ml-2"></i> إرسال الإجابة
                                    </button>
                                @endif
                            </div>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('quiz-competitions.show', $quizCompetition) }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 transition-colors text-sm font-medium">
                    <i class="fas fa-arrow-right"></i><span>العودة للمسابقة</span>
                </a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        function submitQuizForm() {
            var form = document.getElementById('quizForm');
            var reqEl = document.getElementById('requiredChoicesCount');
            if (reqEl) {
                var req = parseInt(reqEl.value);
                var checked = document.querySelectorAll('.quiz-checkbox-input:checked');
                if (checked.length !== req) {
                    Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'الرجاء اختيار عدد ' + req + ' إجابات كما هو مطلوب بالسؤال.', confirmButtonColor: '#22c55e', confirmButtonText: 'حسناً' });
                    return;
                }
            }
            if (!form.reportValidity()) return;
            form.submit();
        }

        window.toggleMotherNameFieldQuestion = function(isChecked) {
            var field = document.getElementById('mother_name_field_question');
            var input = field ? field.querySelector('input[name="mother_name"]') : null;
            if (field && isChecked) {
                field.classList.remove('hidden');
                if (input) input.setAttribute('required', 'required');
            } else if (field) {
                field.classList.add('hidden');
                if (input) { input.removeAttribute('required'); input.value = ''; }
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            var reqEl = document.getElementById('requiredChoicesCount');
            if (reqEl) {
                var req = parseInt(reqEl.value);
                document.querySelectorAll('.quiz-checkbox-input').forEach(function(cb) {
                    cb.addEventListener('change', function() {
                        if (document.querySelectorAll('.quiz-checkbox-input:checked').length > req) {
                            this.checked = false;
                            Swal.fire({ icon: 'info', title: 'تم تجاوز الحد الأقصى', text: 'لا يمكنك اختيار أكثر من ' + req + ' إجابات.', confirmButtonColor: '#22c55e', confirmButtonText: 'حسناً', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        }
                    });
                });
            }

            document.querySelectorAll('.sortable-list').forEach(function(list) {
                new Sortable(list, { animation: 150, ghostClass: 'bg-green-50' });
            });

            @if(old('is_from_ancestry'))
                toggleMotherNameFieldQuestion(true);
            @endif
        });
    </script>
</body>

</html>
