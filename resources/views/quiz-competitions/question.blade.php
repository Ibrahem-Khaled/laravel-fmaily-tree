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
        @keyframes float { 0% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-20px) rotate(180deg); } 100% { transform: translateY(0px) rotate(360deg); } }
        @keyframes slideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse-soft { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        .slide-in { animation: slideIn 0.6s ease-out forwards; }
        .glass-effect { background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); }
        .green-glow { box-shadow: 0 0 40px rgba(34,197,94,0.3); }
        .gradient-text { background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #4ade80 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .choice-option { background: rgba(255,255,255,0.7); border: 2px solid #e5e7eb; transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
        .choice-option:hover { background: rgba(240,253,244,0.9); border-color: #86efac; transform: scale(1.01); box-shadow: 0 4px 20px rgba(34,197,94,0.15); }
        .choice-option:has(input:checked) { background: linear-gradient(135deg, #f0fdf4, #dcfce7); border-color: #22c55e; box-shadow: 0 0 30px rgba(34,197,94,0.2); }
        .choice-option:has(input:checked) .choice-number { background: linear-gradient(135deg, #22c55e, #16a34a); color: white; }
        ::-webkit-scrollbar { width: 10px; } ::-webkit-scrollbar-track { background: #f0fdf4; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #22c55e, #16a34a); border-radius: 5px; }

        /* Question Text Styles */
        .question-text {
            direction: rtl;
            text-align: right;
        }
        .question-text p {
            margin-bottom: 0.5rem;
        }
        .question-text strong,
        .question-text b {
            font-weight: 700;
        }
        .question-text em,
        .question-text i {
            font-style: italic;
        }
        .question-text ul,
        .question-text ol {
            margin-right: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .question-text li {
            margin-bottom: 0.25rem;
        }
        .question-text a {
            color: #22c55e;
            text-decoration: underline;
        }
        .question-text img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 0.5rem 0;
        }

        /* ====== SELECTION OVERLAY ====== */
        #selectionOverlay {
            position: fixed; inset: 0; z-index: 9990;
            display: flex; align-items: center; justify-content: center; flex-direction: column;
            background: radial-gradient(ellipse at center, rgba(8,8,20,0.94) 0%, rgba(0,0,0,0.99) 100%);
            opacity: 0; pointer-events: none;
            transition: opacity 0.8s ease;
            overflow: hidden;
        }
        #selectionOverlay.active { opacity: 1; pointer-events: all; }
        #flashBang { position: fixed; inset: 0; z-index: 9995; background: white; opacity: 0; pointer-events: none; }
        @keyframes flashAnim { 0% { opacity: 0; } 8% { opacity: 1; } 100% { opacity: 0; } }
        #flashBang.flash { animation: flashAnim 0.6s ease-out; }
        #confettiCanvas { position: fixed; inset: 0; z-index: 9998; pointer-events: none; }
        @keyframes shakeHard {
            0%,100% { transform: translate(0); } 10% { transform: translate(-10px, 6px) rotate(-1deg); }
            20% { transform: translate(10px,-6px) rotate(1deg); } 30% { transform: translate(-6px,4px); }
            40% { transform: translate(6px,-4px); } 50% { transform: translate(-3px,2px); }
        }
        .shake-it { animation: shakeHard 0.5s ease; }

        /* Countdown inside overlay */
        .overlay-countdown {
            font-size: clamp(5rem, 18vw, 10rem); font-weight: 900; line-height: 1;
            color: #22c55e; text-shadow: 0 0 80px rgba(34,197,94,0.5), 0 0 160px rgba(34,197,94,0.2);
            font-family: 'Tajawal', sans-serif;
        }
        .overlay-countdown.danger { color: #ef4444; text-shadow: 0 0 80px rgba(239,68,68,0.7), 0 0 160px rgba(239,68,68,0.3); }
        @keyframes countPop { 0% { transform: scale(0.3); opacity: 0; } 60% { transform: scale(1.2); } 100% { transform: scale(1); opacity: 1; } }
        .count-pop { animation: countPop 0.6s cubic-bezier(0.34,1.56,0.64,1); }
        @keyframes ringPulse { 0% { transform: scale(0.6); opacity: 0.4; } 100% { transform: scale(2); opacity: 0; } }
        .pulse-ring-anim { position: absolute; width: 200px; height: 200px; border: 3px solid rgba(34,197,94,0.3); border-radius: 50%; animation: ringPulse 1.2s ease-out infinite; }

        /* Digital Name Scroller */
        .name-scroller-box {
            position: relative; width: 340px; max-width: 90vw; height: 80px;
            border-radius: 20px; overflow: hidden;
            border: 2px solid rgba(34,197,94,0.4);
            background: rgba(0,0,0,0.6);
            box-shadow: 0 0 40px rgba(34,197,94,0.2), inset 0 0 30px rgba(34,197,94,0.05);
        }
        @media(min-width:640px){ .name-scroller-box { width: 440px; height: 90px; } }
        .name-scroller-box::before, .name-scroller-box::after {
            content:''; position:absolute; left:0; right:0; height:30px; z-index:2; pointer-events:none;
        }
        .name-scroller-box::before { top:0; background:linear-gradient(to bottom, rgba(0,0,0,0.9), transparent); }
        .name-scroller-box::after { bottom:0; background:linear-gradient(to top, rgba(0,0,0,0.9), transparent); }
        .name-scroller-track {
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            position:absolute; inset:0;
        }
        .name-scroller-track .scroller-name {
            font-size: 1.6rem; font-weight:700; color:#fff; white-space:nowrap;
            text-shadow: 0 0 20px rgba(34,197,94,0.4);
            transition: all 0.06s linear;
        }
        .name-scroller-track .scroller-name.blurred {
            filter: blur(8px);
            opacity: 0.6;
        }
        .name-scroller-track .scroller-name.clear {
            filter: blur(0);
            opacity: 1;
            transition: filter 0.5s ease-out, opacity 0.5s ease-out;
        }
        .name-scroller-box.winner-found {
            border-color: #f59e0b;
            box-shadow: 0 0 60px rgba(245,158,11,0.5), 0 0 120px rgba(245,158,11,0.2);
        }
        .name-scroller-box.winner-found .scroller-name {
            color: #fbbf24; font-size:2rem;
            text-shadow: 0 0 30px rgba(245,158,11,0.6);
        }

        /* Scanning ring around the box */
        @keyframes scanLine { 0%{top:-2px;} 100%{top:calc(100% - 2px);} }
        .scan-line {
            position:absolute; left:0; right:0; height:2px; z-index:3;
            background: linear-gradient(90deg, transparent, #22c55e, transparent);
            animation: scanLine 0.8s ease-in-out infinite alternate;
        }

        /* Particle field behind scroller */
        .particle-field { position:absolute; inset:0; overflow:hidden; pointer-events:none; }
        .particle-field span {
            position:absolute; width:2px; height:2px; border-radius:50%;
            background:#22c55e; opacity:0;
        }
        @keyframes particleFly {
            0%{opacity:0; transform:translateY(0) scale(0);}
            20%{opacity:0.8; transform:scale(1);}
            100%{opacity:0; transform:translateY(-100px) scale(0.5);}
        }

        /* Shimmer gold text */
        @keyframes shimmerGold { 0% { background-position: -200% center; } 100% { background-position: 200% center; } }
        .text-shimmer-gold {
            background: linear-gradient(90deg, #92400e 0%, #f59e0b 25%, #fef3c7 50%, #f59e0b 75%, #92400e 100%);
            background-size: 200% auto; -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; animation: shimmerGold 2.5s linear infinite;
        }

        /* Winner Reveal */
        @keyframes winnerCardPop {
            0% { transform: scale(0) translateY(60px) rotate(-6deg); opacity: 0; filter: blur(8px); }
            55% { transform: scale(1.06) translateY(-6px) rotate(1deg); opacity: 1; filter: blur(0); }
            100% { transform: scale(1) translateY(0) rotate(0); opacity: 1; }
        }
        @keyframes crownDrop {
            0% { transform: translateY(-100px) rotate(-35deg) scale(0); opacity: 0; }
            45% { transform: translateY(12px) rotate(8deg) scale(1.3); opacity: 1; }
            70% { transform: translateY(-6px) rotate(-3deg) scale(0.9); }
            100% { transform: translateY(0) rotate(0) scale(1); opacity: 1; }
        }
        @keyframes glowBreath {
            0%,100% { box-shadow: 0 0 20px rgba(245,158,11,0.12), 0 0 60px rgba(245,158,11,0.06); }
            50% { box-shadow: 0 0 50px rgba(245,158,11,0.3), 0 0 120px rgba(245,158,11,0.12), 0 0 200px rgba(245,158,11,0.06); }
        }
        .winner-card-anim { opacity: 0; animation: winnerCardPop 0.9s cubic-bezier(0.34,1.56,0.64,1) forwards; }
        .crown-anim { opacity: 0; animation: crownDrop 1s cubic-bezier(0.34,1.56,0.64,1) forwards; }
        .glow-breath { animation: glowBreath 2.5s ease-in-out infinite; }
        @keyframes sparkleAnim { 0% { transform: scale(0) rotate(0); opacity: 0; } 50% { transform: scale(1) rotate(180deg); opacity: 1; } 100% { transform: scale(0) rotate(360deg); opacity: 0; } }
        .sparkle-dot { position: absolute; pointer-events: none; width: 6px; height: 6px; border-radius: 50%; background: #fbbf24; box-shadow: 0 0 6px #fbbf24; animation: sparkleAnim 1.5s ease-in-out infinite; }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    <div id="flashBang"></div>
    <canvas id="confettiCanvas"></canvas>

    {{-- ====== Selection Overlay ====== --}}
    <div id="selectionOverlay">
        <div class="particle-field" id="overlayParticles"></div>
        {{-- Phase: Countdown --}}
        <div id="phaseCountdown" class="text-center relative">
            <div class="pulse-ring-anim" style="top:50%;left:50%;margin:-100px 0 0 -100px;animation-delay:0s;"></div>
            <div class="pulse-ring-anim" style="top:50%;left:50%;margin:-100px 0 0 -100px;animation-delay:0.4s;"></div>
            <div class="pulse-ring-anim" style="top:50%;left:50%;margin:-100px 0 0 -100px;animation-delay:0.8s;"></div>
            <p class="text-green-400/60 text-sm md:text-base mb-3 font-bold tracking-widest uppercase" style="letter-spacing:0.25em;">اختيار الفائز خلال</p>
            <div class="overlay-countdown" id="bigCountNum">3</div>
            <p class="text-white/30 text-xs mt-4">استعد...</p>
        </div>

        {{-- Phase: Digital Selector --}}
        <div id="phaseSelector" class="text-center" style="display:none;">
            <p class="text-shimmer-gold text-lg md:text-xl font-bold mb-6">جاري الفرز من بين جميع المشاركين...</p>
            <div class="name-scroller-box mx-auto" id="scrollerBox">
                <div class="scan-line"></div>
                <div class="name-scroller-track" id="scrollerTrack">
                    <span class="scroller-name" id="scrollerName">...</span>
                </div>
            </div>
            <p class="text-white/25 text-xs mt-5" id="selectorSubtext">يتم الآن الفرز بين المشاركين...</p>
        </div>

        {{-- Phase: Announce --}}
        <div id="phaseAnnounce" class="text-center" style="display:none;">
            <div class="crown-anim inline-block mb-3" style="animation-delay:0.2s;">
                <i class="fas fa-crown text-amber-400 text-5xl md:text-6xl" style="filter:drop-shadow(0 0 30px rgba(245,158,11,0.5));"></i>
            </div>
            <p class="text-shimmer-gold text-2xl md:text-3xl font-bold mb-5">مبروك للفائز!</p>
            <div id="announceName" class="text-4xl md:text-5xl font-black text-white mb-5" style="text-shadow:0 0 40px rgba(255,255,255,0.3);"></div>
            <div class="flex justify-center gap-2 mt-4">
                <span class="w-3 h-3 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0s"></span>
                <span class="w-3 h-3 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0.15s"></span>
                <span class="w-3 h-3 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0.3s"></span>
            </div>
        </div>
    </div>

    @include('partials.main-header')

    <div class="fixed top-10 right-10 w-96 h-96 opacity-5 pointer-events-none hidden lg:block" style="animation: float 6s ease-in-out infinite;">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><path fill="#22c55e" d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3Z" transform="translate(100 100)" /></svg>
    </div>

    <div class="container mx-auto px-4 py-6 lg:py-10 relative z-10 max-w-3xl">
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
                </div>
                @if($quizCompetition->start_at)
                <p class="text-gray-500 text-sm mb-4">يبدأ خلال:</p>
                <div class="flex justify-center gap-3 md:gap-4">
                    @foreach(['ns-days'=>'يوم','ns-hours'=>'ساعة','ns-minutes'=>'دقيقة','ns-seconds'=>'ثانية'] as $id=>$label)
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mb-2 bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
                            <span class="text-2xl md:text-3xl font-bold text-amber-600" id="{{$id}}">0</span>
                        </div>
                        <p class="text-gray-500 text-xs">{{$label}}</p>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" id="nsTarget" value="{{ $quizCompetition->start_at->getTimestamp() * 1000 }}">
                @else
                <p class="text-gray-500 text-sm">سيتم تحديد موعد بدء المسابقة لاحقاً</p>
                @endif
            </div>

        {{-- ==================== انتهى ==================== --}}
        @elseif($status === 'ended')
            <div class="space-y-6 slide-in" id="endedContent">
                <div class="glass-effect rounded-3xl green-glow p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1.5" style="background:linear-gradient(90deg,#22c55e,#16a34a,#22c55e);"></div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-xs px-3 py-1.5 rounded-full border border-gray-200"><i class="fas fa-flag-checkered"></i> انتهت المسابقة</span>
                    </div>
                    <div class="text-xl md:text-2xl font-bold text-gray-800 mb-2 leading-relaxed question-text">{!! $quizQuestion->question_text !!}</div>
                    <p class="text-gray-400 text-xs"><i class="fas fa-calendar ml-1"></i>
                        @if($quizCompetition->start_at && $quizCompetition->end_at) {{ $quizCompetition->start_at->translatedFormat('d M') }} - {{ $quizCompetition->end_at->translatedFormat('d M Y') }} @else — @endif
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 md:gap-4">
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
                        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200"><i class="fas fa-users text-blue-500 text-lg"></i></div>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجمالي المشاركين</p>
                    </div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center" style="box-shadow:0 0 20px rgba(34,197,94,0.15);">
                        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 border border-green-200"><i class="fas fa-check-circle text-green-500 text-lg"></i></div>
                        <p class="text-2xl md:text-3xl font-bold text-green-600">{{ $stats['correct'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجابة صحيحة</p>
                    </div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
                        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100 border border-red-200"><i class="fas fa-times-circle text-red-500 text-lg"></i></div>
                        <p class="text-2xl md:text-3xl font-bold text-red-500">{{ $stats['wrong'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجابة خاطئة</p>
                    </div>
                </div>

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

                {{-- ===== الفائزون ===== --}}
                @if($quizQuestion->winners->count() > 0)
                <div id="winnersSection" class="relative">
                    <div class="absolute inset-0 pointer-events-none overflow-hidden" id="sparklesBox"></div>
                    <div class="glass-effect rounded-3xl p-6 md:p-8 relative overflow-hidden glow-breath" style="border:2px solid rgba(245,158,11,0.3);">
                        <div class="absolute top-0 right-0 left-0 h-2" style="background:linear-gradient(90deg,#f59e0b,#fbbf24,#f59e0b,#fbbf24,#f59e0b);"></div>
                        <div class="text-center mb-6">
                            <div class="inline-block crown-anim" style="animation-delay:0.4s;"><i class="fas fa-crown text-amber-400 text-4xl md:text-5xl" style="filter:drop-shadow(0 0 20px rgba(245,158,11,0.5));"></i></div>
                            <h3 class="text-2xl md:text-3xl font-bold mt-3"><span class="text-shimmer-gold">الفائزون</span></h3>
                        </div>
                        <div class="space-y-4">
                            @foreach($quizQuestion->winners as $winner)
                            <div class="winner-card-anim flex items-center gap-4 rounded-2xl p-5 relative overflow-hidden"
                                 style="animation-delay:{{ 0.7 + ($loop->index * 0.3) }}s;
                                        background:linear-gradient(135deg,{{ $winner->position==1 ? 'rgba(245,158,11,0.08),rgba(251,191,36,0.15)' : 'rgba(255,255,255,0.6),rgba(249,250,251,0.8)' }});
                                        border:2px solid {{ $winner->position==1 ? 'rgba(245,158,11,0.3)' : 'rgba(229,231,235,0.5)' }};">
                                @if($winner->position==1)<div class="absolute inset-0 opacity-20" style="background:radial-gradient(ellipse at 30% 50%,rgba(245,158,11,0.3),transparent 70%);"></div>@endif
                                <div class="flex-shrink-0 relative z-10">
                                    @if($winner->position <= 3)
                                    <span class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-xl text-white shadow-xl"
                                          style="background:linear-gradient(135deg,{{ $winner->position==1 ? '#f59e0b,#d97706' : ($winner->position==2 ? '#9ca3af,#6b7280' : '#cd7f32,#a0522d') }});">{{ $winner->position }}</span>
                                    @else
                                    <span class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-xl bg-gray-100 text-gray-500 border-2 border-gray-200">{{ $winner->position }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 relative z-10">
                                    <p class="font-bold text-lg md:text-xl {{ $winner->position==1 ? 'text-amber-800' : 'text-gray-800' }}">{{ $winner->user->name ?? '-' }}</p>
                                    @if($winner->position==1)<p class="font-bold text-green-600 text-sm md:text-base mt-2 flex items-center gap-2">مبروك عليك 500 ريال</p>@endif
                                </div>
                                @if($winner->position==1)<i class="fas fa-crown text-amber-400 text-2xl relative z-10" style="filter:drop-shadow(0 0 10px rgba(245,158,11,0.4));"></i>
                                @elseif($winner->position<=3)<i class="fas fa-medal text-{{ $winner->position==2 ? 'gray-400' : 'amber-700/60' }} text-xl relative z-10"></i>@endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @php $correctAnswers = $quizQuestion->answers->where('is_correct', true); @endphp
                @if($correctAnswers->count() > 0)
                <div class="glass-effect rounded-2xl p-5">
                    <h4 class="text-sm font-bold text-green-600 mb-3 flex items-center gap-2"><i class="fas fa-check-circle"></i> من جاوب صح ({{ $correctAnswers->count() }})</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($correctAnswers as $ans)
                        <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1.5 rounded-lg text-sm border border-green-200">{{ $ans->user->name ?? '-' }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($quizQuestion->winners->count() === 0 && !(isset($selectionAt) && $selectionAt && now()->lt($selectionAt)))
                <div class="glass-effect rounded-2xl p-6 text-center"><i class="fas fa-hourglass-end text-gray-300 text-3xl mb-3"></i><p class="text-gray-500 font-medium">لم يتم اختيار فائزين بعد</p></div>
                @endif
            </div>

            @if(isset($selectionAt) && $selectionAt)
                <input type="hidden" id="selectionAtMs" value="{{ $selectionAt->getTimestamp() * 1000 }}">
                <input type="hidden" id="endAtMs" value="{{ $quizCompetition->end_at->getTimestamp() * 1000 }}">
                <input type="hidden" id="winnerApiUrl" value="{{ route('quiz-competitions.question.winner', [$quizCompetition, $quizQuestion]) }}">
                <input type="hidden" id="hasWinnersAlready" value="{{ $quizQuestion->winners->count() > 0 ? '1' : '0' }}">
                <input type="hidden" id="correctCount" value="{{ $stats['correct'] }}">
                <input type="hidden" id="candidateNamesJsonEnded" value='@json($candidateNames ?? [])'>
            @endif

        {{-- ==================== نشط ==================== --}}
        @else
            <div class="space-y-6 slide-in">
                <div class="glass-effect rounded-3xl green-glow p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1.5" style="background:linear-gradient(90deg,#22c55e,#16a34a,#22c55e);"></div>
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                        <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-bold px-3 py-1.5 rounded-full border border-red-200"><span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span> مباشر الآن</span>
                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <i class="fas fa-hourglass-half text-amber-500 text-xs"></i><span>متبقي:</span>
                            <div class="flex gap-1 flex-row-reverse">
                                <span class="bg-green-50 border border-green-200 rounded-lg px-2 py-1 text-green-700 font-bold text-sm min-w-[2rem] text-center" id="at-hours">00</span>
                                <span class="text-gray-400 font-bold">:</span>
                                <span class="bg-green-50 border border-green-200 rounded-lg px-2 py-1 text-green-700 font-bold text-sm min-w-[2rem] text-center" id="at-minutes">00</span>
                                <span class="text-gray-400 font-bold">:</span>
                                <span class="bg-green-50 border border-green-200 rounded-lg px-2 py-1 text-green-700 font-bold text-sm min-w-[2rem] text-center" id="at-seconds">00</span>
                            </div>
                        </div>
                        @if($quizCompetition->end_at)<input type="hidden" id="atEndTime" value="{{ $quizCompetition->end_at->getTimestamp() * 1000 }}">
                        <input type="hidden" id="activeWinnerApiUrl" value="{{ route('quiz-competitions.question.winner', [$quizCompetition, $quizQuestion]) }}">
                        <input type="hidden" id="activeCorrectCount" value="{{ $quizQuestion->answers->where('is_correct', true)->count() }}">
                        <input type="hidden" id="candidateNamesJson" value='@json($candidateNames ?? [])'>@endif
                    </div>
                    <p class="text-green-600 text-xs font-medium mb-3"><i class="fas fa-trophy ml-1"></i> {{ $quizCompetition->title }}</p>
                    <div class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 leading-relaxed question-text">{!! $quizQuestion->question_text !!}</div>
                </div>
                <div class="grid grid-cols-3 gap-3 md:gap-4">
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center"><div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200"><i class="fas fa-users text-blue-500 text-lg"></i></div><p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p><p class="text-gray-500 text-xs mt-1">إجمالي الإجابات</p></div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center" style="box-shadow:0 0 20px rgba(34,197,94,0.15);"><div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 border border-green-200"><i class="fas fa-check-circle text-green-500 text-lg"></i></div><p class="text-2xl md:text-3xl font-bold text-green-600">{{ $stats['correct'] }}</p><p class="text-gray-500 text-xs mt-1">إجابة صحيحة</p></div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center"><div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100 border border-red-200"><i class="fas fa-times-circle text-red-500 text-lg"></i></div><p class="text-2xl md:text-3xl font-bold text-red-500">{{ $stats['wrong'] }}</p><p class="text-gray-500 text-xs mt-1">إجابة خاطئة</p></div>
                </div>

                @if(session('success'))
                <div class="rounded-2xl p-5 md:p-6 bg-green-50 border-2 border-green-200 slide-in">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-green-400 to-green-500"><i class="fas fa-check-circle text-white text-xl"></i></div>
                        <div><p class="text-green-800 font-bold text-lg">{{ session('success') }}</p><p class="text-green-600 text-sm mt-1">سيتم اختيار الفائز تلقائياً بعد انتهاء وقت المسابقة.</p></div>
                    </div>
                </div>
                @endif
                @if(session('error'))
                <div class="rounded-2xl p-4 flex items-center gap-3 bg-red-50 border border-red-200 slide-in"><div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-red-400 to-red-500"><i class="fas fa-exclamation-circle text-white text-lg"></i></div><p class="text-red-700 font-bold">{{ session('error') }}</p></div>
                @endif
                @if($errors->any())<div class="rounded-2xl p-4 bg-red-50 border border-red-200"><ul class="space-y-1">@foreach($errors->all() as $error)<li class="text-red-600 text-sm flex items-center gap-2"><i class="fas fa-circle text-[6px] text-red-400"></i>{{ $error }}</li>@endforeach</ul></div>@endif

                @if(!session('success') && !($canAnswer ?? true))
                <div class="rounded-2xl p-5 flex items-center gap-3 bg-amber-50 border border-amber-200"><div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-amber-100"><i class="fas fa-check-circle text-amber-600"></i></div><p class="text-amber-800 font-medium">لقد أجبت على هذا السؤال</p></div>
                @endif

                @if(!session('success') && ($canAnswer ?? true))
                <form action="{{ route('quiz-competitions.store-answer', [$quizCompetition, $quizQuestion]) }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="glass-effect rounded-2xl p-5 md:p-6">
                        <div class="flex items-center gap-2 mb-4"><div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 border border-blue-300"><i class="fas fa-user text-blue-500 text-sm"></i></div><h3 class="font-bold text-gray-800">بياناتك</h3></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-gray-600 text-sm mb-2 font-medium">الاسم <span class="text-red-500">*</span></label><input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all" placeholder="أدخل اسمك الكامل"></div>
                            <div><label class="block text-gray-600 text-sm mb-2 font-medium">رقم الهاتف <span class="text-red-500">*</span></label><input type="text" name="phone" value="{{ old('phone') }}" required pattern="[0-9]{10}" minlength="10" maxlength="10" class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all" placeholder="05xxxxxxxx" dir="ltr" style="text-align:right;" title="يجب أن يكون رقم الهاتف 10 أرقام"></div>
                        </div>
                        <div class="space-y-3 mt-4">
                            <div class="flex items-center gap-2 p-3 rounded-xl border-2 border-gray-200 bg-white/80 hover:border-green-300 hover:bg-green-50/50 cursor-pointer transition-all">
                                <input type="checkbox" name="is_from_ancestry" value="1" id="is_from_ancestry_question"
                                       class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500"
                                       {{ old('is_from_ancestry') ? 'checked' : '' }}
                                       onchange="toggleMotherNameFieldQuestion(this.checked)">
                                <label for="is_from_ancestry_question" class="text-gray-800 text-sm font-medium cursor-pointer">
                                    أنا من الأنساب
                                </label>
                            </div>
                            <div id="mother_name_field_question" class="hidden">
                                <label class="block text-gray-600 text-sm mb-2 font-medium">اسم الأم <span class="text-red-500">*</span></label>
                                <input type="text" name="mother_name" value="{{ old('mother_name') }}"
                                       class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all"
                                       placeholder="اسم الأم الكامل">
                            </div>
                        </div>
                    </div>
                    <div class="glass-effect rounded-2xl p-5 md:p-6">
                        <div class="flex items-center gap-2 mb-4"><div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gradient-to-br from-green-100 to-green-200 border border-green-300"><i class="fas fa-pen text-green-600 text-sm"></i></div><h3 class="font-bold text-gray-800">إجابتك</h3></div>
                        @if($quizQuestion->answer_type === 'multiple_choice')
                        <div class="space-y-3">@foreach($quizQuestion->choices as $choice)<label class="choice-option flex items-center gap-4 p-4 rounded-xl cursor-pointer"><input type="radio" name="answer" value="{{ $choice->id }}" class="hidden" required><span class="choice-number w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm flex-shrink-0 transition-all">{{ $loop->iteration }}</span><span class="text-gray-700 font-medium text-sm md:text-base">{{ $choice->choice_text }}</span></label>@endforeach</div>
                        @else
                        <textarea name="answer" rows="4" required class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm resize-none focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all" placeholder="اكتب إجابتك هنا...">{{ old('answer') }}</textarea>
                        @endif
                    </div>
                    <button type="submit" class="w-full py-4 md:py-5 rounded-2xl text-white font-bold text-lg flex items-center justify-center gap-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl active:scale-[0.98]" style="background:linear-gradient(135deg,#22c55e,#16a34a);box-shadow:0 8px 25px rgba(34,197,94,0.4);"><i class="fas fa-paper-plane"></i><span>إرسال الإجابة</span></button>
                </form>
                @endif
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('quiz-competitions.show', $quizCompetition) }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 transition-colors text-sm font-medium"><i class="fas fa-arrow-right"></i><span>العودة للمسابقة</span></a>
        </div>
    </div>

    <script>
    /* ============ CONFETTI ============ */
    var Confetti = {
        canvas: null, ctx: null, particles: [], running: false,
        colors: ['#f59e0b','#22c55e','#ef4444','#3b82f6','#a855f7','#ec4899','#fbbf24','#14b8a6'],
        init: function() {
            this.canvas = document.getElementById('confettiCanvas');
            if(!this.canvas) return;
            this.ctx = this.canvas.getContext('2d');
            this.resize();
            window.addEventListener('resize', this.resize.bind(this));
        },
        resize: function() { if(this.canvas){this.canvas.width=window.innerWidth;this.canvas.height=window.innerHeight;} },
        launch: function(count, dur) {
            this.particles = [];
            for(var i=0;i<(count||200);i++) this.particles.push({
                x:Math.random()*this.canvas.width, y:-20-Math.random()*this.canvas.height*0.5,
                w:4+Math.random()*8, h:4+Math.random()*8,
                color:this.colors[Math.floor(Math.random()*this.colors.length)],
                vx:(Math.random()-0.5)*8, vy:2+Math.random()*6,
                rot:Math.random()*360, rotV:(Math.random()-0.5)*15, opacity:1,
                shape:Math.random()>0.5?'r':'c'
            });
            this.running=true; var self=this,start=Date.now(),duration=dur||5000;
            (function frame(){
                if(!self.running) return;
                var p=Math.min((Date.now()-start)/duration,1);
                self.ctx.clearRect(0,0,self.canvas.width,self.canvas.height);
                self.particles.forEach(function(pt){
                    pt.x+=pt.vx; pt.y+=pt.vy; pt.vy+=0.1; pt.rot+=pt.rotV;
                    if(p>0.7) pt.opacity=Math.max(0,1-(p-0.7)/0.3);
                    self.ctx.save(); self.ctx.translate(pt.x,pt.y); self.ctx.rotate(pt.rot*Math.PI/180);
                    self.ctx.globalAlpha=pt.opacity; self.ctx.fillStyle=pt.color;
                    if(pt.shape==='r') self.ctx.fillRect(-pt.w/2,-pt.h/2,pt.w,pt.h);
                    else { self.ctx.beginPath(); self.ctx.arc(0,0,pt.w/2,0,Math.PI*2); self.ctx.fill(); }
                    self.ctx.restore();
                });
                if(p<1) requestAnimationFrame(frame);
                else { self.ctx.clearRect(0,0,self.canvas.width,self.canvas.height); self.running=false; }
            })();
        }
    };

    /* ============ DIGITAL NAME SELECTOR ============ */
    function DigitalSelector(candidateNames) {
        var nameEl = document.getElementById('scrollerName');
        var boxEl = document.getElementById('scrollerBox');
        var subtext = document.getElementById('selectorSubtext');
        if(!nameEl) return;

        // Use real candidate names if available, otherwise fake Arabic names
        var namesPool = (candidateNames && candidateNames.length > 0) ? candidateNames : [
            'محمد أحمد','عبدالله سعد','فهد خالد','سارة علي','نورة محمد',
            'خالد عبدالرحمن','أحمد يوسف','عمر حسن','فاطمة إبراهيم','مريم صالح',
            'سلطان ناصر','عبدالعزيز فيصل','هند ماجد','لمى عادل','ريم طارق',
            'بندر سعود','تركي عبدالله','نوف سلمان','دانة وليد','جود حمد',
            'راشد بدر','ماجد عايض','هيا محمد','العنود سعد','عبير فهد',
            'صالح حمود','ياسر عمر','منال خالد','أسماء أحمد','سلمان ابراهيم'
        ];

        var running = false;
        var stopping = false;
        var winner = null;
        var finalCallback = null;

        this.start = function() {
            running = true;
            stopping = false;
            winner = null;

            // إضافة blur على الأسماء أثناء العرض
            nameEl.classList.add('blurred');
            nameEl.classList.remove('clear');

            (function frame() {
                if(!running) return;

                // If stopping (winner found), slow down
                // If running (searching), stay fast

                var interval = 50; // Fast by default

                if(stopping) {
                    // Gradual slowdown logic would go here, but for now we'll just keep spinning until final stop
                    // or implement a simple counter
                }

                // Pick random name
                var displayName = namesPool[Math.floor(Math.random() * namesPool.length)];
                nameEl.textContent = displayName;
                // الحفاظ على blur أثناء العرض
                nameEl.classList.add('blurred');
                nameEl.classList.remove('clear');

                if(stopping && Math.random() < 0.1) { // Random stop chance if slowing down?
                    // No, let's just keep spinning fast until reveal() handles the stop animation explicitly
                }

                setTimeout(frame, interval);
            })();
        };

        this.reveal = function(winnerName, callback) {
            stopping = true;
            winner = winnerName;
            finalCallback = callback;

            // Start the slowdown sequence
            running = false; // Stop the infinite loop

            // زيادة مدة العرض إلى 8000ms (8 ثواني)
            var duration = 8000;
            var start = Date.now();

            (function slowFrame() {
                var elapsed = Date.now() - start;
                var progress = Math.min(elapsed / duration, 1);

                // Ease out
                var interval;
                if(progress < 0.2) interval = 50;
                else if(progress < 0.4) interval = 100;
                else if(progress < 0.6) interval = 200;
                else if(progress < 0.75) interval = 400;
                else if(progress < 0.9) interval = 600;
                else interval = 800;

                var displayName;
                var isWinner = false;

                // في آخر 5% من المدة، نبدأ بإزالة blur تدريجياً
                if(progress >= 0.95) {
                    displayName = winnerName;
                    isWinner = true;
                    // إزالة blur تدريجياً
                    nameEl.classList.remove('blurred');
                    nameEl.classList.add('clear');
                } else if(progress >= 0.85) {
                    // في آخر 15%، نبدأ بإظهار اسم الفائز أحياناً لكن مع blur خفيف
                    if(Math.random() < 0.3) {
                        displayName = winnerName;
                        nameEl.classList.add('blurred');
                        nameEl.style.filter = 'blur(4px)';
                        nameEl.style.opacity = '0.8';
                    } else {
                        displayName = namesPool[Math.floor(Math.random() * namesPool.length)];
                        nameEl.classList.add('blurred');
                        nameEl.style.filter = 'blur(8px)';
                        nameEl.style.opacity = '0.6';
                    }
                } else {
                    // باقي الأسماء تظهر مع blur كامل
                    displayName = namesPool[Math.floor(Math.random() * namesPool.length)];
                    nameEl.classList.add('blurred');
                    nameEl.classList.remove('clear');
                    nameEl.style.filter = 'blur(8px)';
                    nameEl.style.opacity = '0.6';
                }

                nameEl.textContent = displayName;

                // Subtext update
                if(subtext) {
                     if(progress < 0.3) subtext.textContent = 'جاري البحث بين المشاركين...';
                     else if(progress < 0.6) subtext.textContent = 'تضييق نطاق البحث...';
                     else if(progress < 0.85) subtext.textContent = 'اقتربنا من النتيجة...';
                     else subtext.textContent = 'تم تحديد الفائز!';
                }

                if(progress >= 1) {
                    nameEl.textContent = winnerName;
                    // إزالة blur نهائياً من اسم الفائز
                    nameEl.classList.remove('blurred');
                    nameEl.classList.add('clear');
                    nameEl.style.filter = 'blur(0)';
                    nameEl.style.opacity = '1';
                    boxEl.classList.add('winner-found');
                    var scanLine = boxEl.querySelector('.scan-line');
                    if(scanLine) scanLine.style.display = 'none';
                    setTimeout(function(){ if(finalCallback) finalCallback(winnerName); }, 800);
                    return;
                }
                setTimeout(slowFrame, interval);
            })();
        };
    }

    /* ============ OVERLAY PARTICLES ============ */
    function initOverlayParticles() {
        var container = document.getElementById('overlayParticles');
        if(!container) return;
        for(var i=0; i<40; i++) {
            var p = document.createElement('span');
            p.style.left = Math.random()*100+'%';
            p.style.bottom = '-10px';
            p.style.animationDelay = (Math.random()*4)+'s';
            p.style.animationDuration = (2+Math.random()*3)+'s';
            p.style.animation = 'particleFly '+(2+Math.random()*3)+'s ease-out '+(Math.random()*4)+'s infinite';
            container.appendChild(p);
        }
    }

    /* ============ SPARKLES ============ */
    function addSparkles(el, count) {
        if(!el) return;
        for(var i=0;i<count;i++){
            var s=document.createElement('div'); s.className='sparkle-dot';
            s.style.top=Math.random()*100+'%'; s.style.left=Math.random()*100+'%';
            s.style.animationDelay=(Math.random()*3)+'s';
            s.style.width=(3+Math.random()*6)+'px'; s.style.height=s.style.width;
            el.appendChild(s);
        }
    }

    /* ============ WINNER ANIMATION ORCHESTRATOR ============ */
    function startWinnerReveal(winnerName, correctCount, candidateNames) {
        var overlay = document.getElementById('selectionOverlay');
        var phaseCount = document.getElementById('phaseCountdown');
        var phaseSelector = document.getElementById('phaseSelector');
        var phaseAnnounce = document.getElementById('phaseAnnounce');
        var countNum = document.getElementById('bigCountNum');
        var flashBang = document.getElementById('flashBang');

        if(!overlay) { location.reload(); return; }

        initOverlayParticles();
        overlay.classList.add('active');

        // Phase 1: Countdown from 2
        var countVal = 2;
        countNum.textContent = countVal;
        countNum.classList.add('count-pop');

        var countTimer = setInterval(function() {
            countVal--;
            if(countVal <= 0) {
                clearInterval(countTimer);
                flashBang.classList.add('flash');
                document.body.classList.add('shake-it');
                setTimeout(function(){
                    flashBang.classList.remove('flash');
                    document.body.classList.remove('shake-it');
                }, 500);

                // Phase 2: Digital Selector (run for a bit then reveal)
                phaseCount.style.display = 'none';
                phaseSelector.style.display = 'block';

                var selector = new DigitalSelector(candidateNames);
                selector.start();

                // Spin for 5 seconds then reveal (since we already have the winner)
                // زيادة المدة لتتناسب مع المدة الجديدة
                setTimeout(function() {
                     finalizeZeroDelay(selector, winnerName);
                }, 5000);

                return;
            }

            countNum.textContent = countVal;
            countNum.classList.remove('count-pop');
            void countNum.offsetWidth;
            countNum.classList.add('count-pop');
            if(countVal <= 2) countNum.classList.add('danger');
        }, 900);
    }

    function startZeroDelayAnimation(candidateNames, correctCount) {
        var overlay = document.getElementById('selectionOverlay');
        var phaseCount = document.getElementById('phaseCountdown');
        var phaseSelector = document.getElementById('phaseSelector');
        var flashBang = document.getElementById('flashBang');

        if(!overlay) return null; // return selector instance

        initOverlayParticles();
        overlay.classList.add('active');

        // Skip countdown -> Go straight to selector
        phaseCount.style.display = 'none';
        phaseSelector.style.display = 'block';

        // Flash!
        flashBang.classList.add('flash');
        setTimeout(function(){ flashBang.classList.remove('flash'); }, 500);

        var selector = new DigitalSelector(candidateNames);
        selector.start();
        return selector;
    }

    function finalizeZeroDelay(selector, winnerName) {
        var phaseSelector = document.getElementById('phaseSelector');
        var phaseAnnounce = document.getElementById('phaseAnnounce');
        var flashBang = document.getElementById('flashBang');

        if(!selector) return;

        selector.reveal(winnerName, function(name) {
             // Flash!
            flashBang.classList.add('flash');
            setTimeout(function(){ flashBang.classList.remove('flash'); }, 500);

            Confetti.launch(350, 5000);

            // Phase 3: Announce
            setTimeout(function() {
                phaseSelector.style.display = 'none';
                phaseAnnounce.style.display = 'block';
                document.getElementById('announceName').textContent = name;

                Confetti.launch(200, 4000);

                // Reload after celebration
                // Reload after celebration with flag to skip re-animation
                setTimeout(function() {
                    var url = new URL(window.location.href);
                    url.searchParams.set('animation_done', '1');
                    window.location.href = url.toString();
                }, 3500);
            }, 800);
        });
    }

    /* ============ AJAX WINNER FETCHER WITH JITTER ============ */
    function fetchWinnerFromServer(apiUrl, callback) {
        function doFetch() {
            fetch(apiUrl)
                .then(function(r){ return r.json(); })
                .then(function(data) {
                    if(data.status === 'done' && data.winners && data.winners.length > 0) {
                        callback(data.winners[0].name);
                    } else if(data.status === 'pending') {
                        // Retry after 2 seconds
                        setTimeout(doFetch, 2000);
                    } else {
                        // no_winners — reload
                        location.reload();
                    }
                })
                .catch(function() {
                    // Network error — retry after 3 seconds
                    setTimeout(doFetch, 3000);
                });
        }
        doFetch();
    }

    /* ============ PAGE INIT ============ */
    document.addEventListener('DOMContentLoaded', function() {
        Confetti.init();

        @if($status === 'not_started' && $quizCompetition->start_at)
        (function(){
            var target = parseInt(document.getElementById('nsTarget').value,10);
            function update(){
                var d=target-Date.now(); if(d<=0){location.reload();return;}
                document.getElementById('ns-days').textContent=Math.floor(d/86400000);
                document.getElementById('ns-hours').textContent=Math.floor((d%86400000)/3600000);
                document.getElementById('ns-minutes').textContent=Math.floor((d%3600000)/60000);
                document.getElementById('ns-seconds').textContent=Math.floor((d%60000)/1000);
            } update(); setInterval(update,1000);
        })();
        @endif

        @if($status === 'active' && $quizCompetition->end_at)
        (function(){
            var end=parseInt(document.getElementById('atEndTime').value,10);
            var apiUrl = document.getElementById('activeWinnerApiUrl').value;
            var correctCount = parseInt(document.getElementById('activeCorrectCount').value, 10) || 0;
            var candidates = JSON.parse(document.getElementById('candidateNamesJson').value || '[]');
            var timerDone = false;

            function pad(n){return n.toString().padStart(2,'0');}
            function update(){
                var d=end-Date.now();
                if(d<=0 && !timerDone){
                    timerDone = true;
                    // Show 00:00:00
                    document.getElementById('at-hours').textContent='00';
                    document.getElementById('at-minutes').textContent='00';
                    document.getElementById('at-seconds').textContent='00';

                    // ZERO DELAY: Start animation IMMEDIATELY while fetching winner
                    var selector = startZeroDelayAnimation(candidates, correctCount);

                    // Fetch winner in parallel
                    // زيادة jitter قليلاً لأن المدة أصبحت أطول
                    var jitter = Math.floor(Math.random() * 3000);
                    setTimeout(function() {
                        fetchWinnerFromServer(apiUrl, function(winnerName) {
                            finalizeZeroDelay(selector, winnerName);
                        });
                    }, jitter);
                    return;
                }
                if(timerDone) return;
                document.getElementById('at-hours').textContent=pad(Math.floor(d/3600000));
                document.getElementById('at-minutes').textContent=pad(Math.floor((d%3600000)/60000));
                document.getElementById('at-seconds').textContent=pad(Math.floor((d%60000)/1000));
            } update(); setInterval(update,1000);
        })();
        @endif

        @if($status === 'ended' && isset($selectionAt) && $selectionAt)
        (function(){
            var selAt = parseInt(document.getElementById('selectionAtMs').value, 10);
            var endAt = parseInt(document.getElementById('endAtMs').value, 10);
            var apiUrl = document.getElementById('winnerApiUrl').value;
            var hasWinners = document.getElementById('hasWinnersAlready').value === '1';
            var correctCount = parseInt(document.getElementById('correctCount').value, 10) || 0;
            var candidates = JSON.parse(document.getElementById('candidateNamesJsonEnded').value || '[]');
            var now = Date.now();
            var urlParams = new URLSearchParams(window.location.search);

            // If winners already exist and it's been more than 20 seconds OR we just finished animation, skip
            if((hasWinners && now > selAt + 20000) || urlParams.has('animation_done')) {
                addSparkles(document.getElementById('sparklesBox'), 20);
                Confetti.launch(200, 5000);
                return;
            }

            // Small jitter to distribute 1000+ requests
            var jitter = Math.floor(Math.random() * 3000);

            // Calculate when to fetch the winner
            var fetchTime = Math.max(selAt + jitter, now);
            var delay = fetchTime - now;

            setTimeout(function() {
                fetchWinnerFromServer(apiUrl, function(winnerName) {
                    startWinnerReveal(winnerName, correctCount, candidates);
                });
            }, delay);
        })();
        @endif

        @if($status === 'ended' && $quizQuestion->winners->count() > 0 && !(isset($selectionAt) && $selectionAt && now()->lt($selectionAt)))
        (function(){
            addSparkles(document.getElementById('sparklesBox'), 20);
            Confetti.launch(200, 5000);
        })();
        @endif

        // دالة لإظهار/إخفاء حقل اسم الأم بناءً على checkbox الأنساب
        window.toggleMotherNameFieldQuestion = function(isChecked) {
            const motherNameField = document.getElementById('mother_name_field_question');
            const motherNameInput = motherNameField.querySelector('input[name="mother_name"]');

            if (isChecked) {
                motherNameField.classList.remove('hidden');
                if (motherNameInput) {
                    motherNameInput.setAttribute('required', 'required');
                }
            } else {
                motherNameField.classList.add('hidden');
                if (motherNameInput) {
                    motherNameInput.removeAttribute('required');
                    motherNameInput.value = '';
                }
            }
        };

        // تهيئة الحقل عند تحميل الصفحة (في حالة وجود old values)
        @if(old('is_from_ancestry'))
            toggleMotherNameFieldQuestion(true);
        @endif
    });
    </script>
</body>
</html>
