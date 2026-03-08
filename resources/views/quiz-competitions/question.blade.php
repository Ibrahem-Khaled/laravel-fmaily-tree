<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Str::limit(strip_tags($quizQuestion->question_text), 50) }} - {{ strip_tags($quizCompetition->title) }}
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Amiri:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
            min-height: 100vh;
        }

        h1,
        h2,
        h3 {
            font-family: 'Amiri', serif;
        }

        @keyframes float {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }

            100% {
                transform: translateY(0px) rotate(360deg);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse-soft {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .slide-in {
            animation: slideIn 0.6s ease-out forwards;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .green-glow {
            box-shadow: 0 0 40px rgba(34, 197, 94, 0.3);
        }

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

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f0fdf4;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e, #16a34a);
            border-radius: 5px;
        }

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

        .quiz-description iframe,
        .quiz-description video,
        .question-text iframe,
        .question-text video {
            max-width: 100%;
            height: auto;
            aspect-ratio: 16 / 9;
            border-radius: 0.5rem;
            margin: 0.5rem 0;
        }

        /* ====== SELECTION OVERLAY ====== */
        #selectionOverlay {
            position: fixed;
            inset: 0;
            z-index: 9990;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: radial-gradient(ellipse at center, rgba(8, 8, 20, 0.94) 0%, rgba(0, 0, 0, 0.99) 100%);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.8s ease;
            overflow: hidden;
        }

        #selectionOverlay.active {
            opacity: 1;
            pointer-events: all;
        }

        #flashBang {
            position: fixed;
            inset: 0;
            z-index: 9995;
            background: white;
            opacity: 0;
            pointer-events: none;
        }

        @keyframes flashAnim {
            0% {
                opacity: 0;
            }

            8% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        #flashBang.flash {
            animation: flashAnim 0.6s ease-out;
        }

        #confettiCanvas {
            position: fixed;
            inset: 0;
            z-index: 9998;
            pointer-events: none;
        }

        @keyframes shakeHard {

            0%,
            100% {
                transform: translate(0);
            }

            10% {
                transform: translate(-10px, 6px) rotate(-1deg);
            }

            20% {
                transform: translate(10px, -6px) rotate(1deg);
            }

            30% {
                transform: translate(-6px, 4px);
            }

            40% {
                transform: translate(6px, -4px);
            }

            50% {
                transform: translate(-3px, 2px);
            }
        }

        .shake-it {
            animation: shakeHard 0.5s ease;
        }

        /* Countdown inside overlay */
        .overlay-countdown {
            font-size: clamp(5rem, 18vw, 10rem);
            font-weight: 900;
            line-height: 1;
            color: #22c55e;
            text-shadow: 0 0 80px rgba(34, 197, 94, 0.5), 0 0 160px rgba(34, 197, 94, 0.2);
            font-family: 'Tajawal', sans-serif;
        }

        .overlay-countdown.danger {
            color: #ef4444;
            text-shadow: 0 0 80px rgba(239, 68, 68, 0.7), 0 0 160px rgba(239, 68, 68, 0.3);
        }

        @keyframes countPop {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            60% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .count-pop {
            animation: countPop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes ringPulse {
            0% {
                transform: scale(0.6);
                opacity: 0.4;
            }

            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .pulse-ring-anim {
            position: absolute;
            width: 200px;
            height: 200px;
            border: 3px solid rgba(34, 197, 94, 0.3);
            border-radius: 50%;
            animation: ringPulse 1.2s ease-out infinite;
        }

        /* Digital Name Scroller */
        .name-scroller-box {
            position: relative;
            width: 340px;
            max-width: 90vw;
            height: 80px;
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid rgba(34, 197, 94, 0.4);
            background: rgba(0, 0, 0, 0.6);
            box-shadow: 0 0 40px rgba(34, 197, 94, 0.2), inset 0 0 30px rgba(34, 197, 94, 0.05);
        }

        @media(min-width:640px) {
            .name-scroller-box {
                width: 440px;
                height: 90px;
            }
        }

        .name-scroller-box::before,
        .name-scroller-box::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            height: 30px;
            z-index: 2;
            pointer-events: none;
        }

        .name-scroller-box::before {
            top: 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.9), transparent);
        }

        .name-scroller-box::after {
            bottom: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent);
        }

        .name-scroller-track {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: absolute;
            inset: 0;
        }

        .name-scroller-track .scroller-name {
            font-size: 1.6rem;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            text-shadow: 0 0 20px rgba(34, 197, 94, 0.4);
            transition: all 0.06s linear;
        }

        .name-scroller-track .scroller-name.blurred {
            filter: blur(12px);
            opacity: 0.3;
            color: transparent;
            text-shadow: 0 0 15px rgba(34, 197, 94, 0.8);
        }

        .name-scroller-track .scroller-name.clear {
            filter: blur(0);
            opacity: 1;
            transition: filter 0.5s ease-out, opacity 0.5s ease-out;
        }

        .name-scroller-box.winner-found {
            border-color: #f59e0b;
            box-shadow: 0 0 60px rgba(245, 158, 11, 0.5), 0 0 120px rgba(245, 158, 11, 0.2);
        }

        .name-scroller-box.winner-found .scroller-name {
            color: #fbbf24;
            font-size: 2rem;
            text-shadow: 0 0 30px rgba(245, 158, 11, 0.6);
        }

        /* Scanning ring around the box */
        @keyframes scanLine {
            0% {
                top: -2px;
            }

            100% {
                top: calc(100% - 2px);
            }
        }

        .scan-line {
            position: absolute;
            left: 0;
            right: 0;
            height: 2px;
            z-index: 3;
            background: linear-gradient(90deg, transparent, #22c55e, transparent);
            animation: scanLine 0.8s ease-in-out infinite alternate;
        }

        /* Particle field behind scroller */
        .particle-field {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .particle-field span {
            position: absolute;
            width: 2px;
            height: 2px;
            border-radius: 50%;
            background: #22c55e;
            opacity: 0;
        }

        @keyframes particleFly {
            0% {
                opacity: 0;
                transform: translateY(0) scale(0);
            }

            20% {
                opacity: 0.8;
                transform: scale(1);
            }

            100% {
                opacity: 0;
                transform: translateY(-100px) scale(0.5);
            }
        }

        /* Shimmer gold text */
        @keyframes shimmerGold {
            0% {
                background-position: -200% center;
            }

            100% {
                background-position: 200% center;
            }
        }

        .text-shimmer-gold {
            background: linear-gradient(90deg, #92400e 0%, #f59e0b 25%, #fef3c7 50%, #f59e0b 75%, #92400e 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmerGold 2.5s linear infinite;
        }

        /* Winner Reveal */
        @keyframes winnerCardPop {
            0% {
                transform: scale(0) translateY(60px) rotate(-6deg);
                opacity: 0;
                filter: blur(8px);
            }

            55% {
                transform: scale(1.06) translateY(-6px) rotate(1deg);
                opacity: 1;
                filter: blur(0);
            }

            100% {
                transform: scale(1) translateY(0) rotate(0);
                opacity: 1;
            }
        }

        @keyframes crownDrop {
            0% {
                transform: translateY(-100px) rotate(-35deg) scale(0);
                opacity: 0;
            }

            45% {
                transform: translateY(12px) rotate(8deg) scale(1.3);
                opacity: 1;
            }

            70% {
                transform: translateY(-6px) rotate(-3deg) scale(0.9);
            }

            100% {
                transform: translateY(0) rotate(0) scale(1);
                opacity: 1;
            }
        }

        @keyframes glowBreath {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(245, 158, 11, 0.12), 0 0 60px rgba(245, 158, 11, 0.06);
            }

            50% {
                box-shadow: 0 0 50px rgba(245, 158, 11, 0.3), 0 0 120px rgba(245, 158, 11, 0.12), 0 0 200px rgba(245, 158, 11, 0.06);
            }
        }

        .winner-card-anim {
            opacity: 0;
            animation: winnerCardPop 0.9s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .crown-anim {
            opacity: 0;
            animation: crownDrop 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .glow-breath {
            animation: glowBreath 2.5s ease-in-out infinite;
        }

        @keyframes sparkleAnim {
            0% {
                transform: scale(0) rotate(0);
                opacity: 0;
            }

            50% {
                transform: scale(1) rotate(180deg);
                opacity: 1;
            }

            100% {
                transform: scale(0) rotate(360deg);
                opacity: 0;
            }
        }

        .sparkle-dot {
            position: absolute;
            pointer-events: none;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #fbbf24;
            box-shadow: 0 0 6px #fbbf24;
            animation: sparkleAnim 1.5s ease-in-out infinite;
        }

        /* Answer result modal */
        #answerResultOverlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.6);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.35s ease;
        }

        #answerResultOverlay.show {
            opacity: 1;
            pointer-events: all;
        }

        #answerResultOverlay .result-card {
            transform: scale(0.7);
            opacity: 0;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
        }

        #answerResultOverlay.show .result-card {
            transform: scale(1);
            opacity: 1;
        }

        #answerResultOverlay.result-correct .result-card {
            box-shadow: 0 0 60px rgba(34, 197, 94, 0.5), 0 0 120px rgba(34, 197, 94, 0.2);
            animation: resultCorrectPulse 1.5s ease-in-out 2;
        }

        #answerResultOverlay.result-wrong .result-card {
            animation: resultWrongShake 0.6s ease;
        }

        @keyframes resultCorrectPulse {

            0%,
            100% {
                box-shadow: 0 0 40px rgba(34, 197, 94, 0.4);
            }

            50% {
                box-shadow: 0 0 80px rgba(34, 197, 94, 0.7), 0 0 0 20px rgba(34, 197, 94, 0.1);
            }
        }

        @keyframes resultWrongShake {

            0%,
            100% {
                transform: translateX(0);
            }

            15% {
                transform: translateX(-12px);
            }

            30% {
                transform: translateX(12px);
            }

            45% {
                transform: translateX(-8px);
            }

            60% {
                transform: translateX(8px);
            }

            75% {
                transform: translateX(-4px);
            }
        }
    </style>
</head>

<body class="text-gray-800 relative overflow-x-hidden">
    <div id="flashBang"></div>
    <canvas id="confettiCanvas"></canvas>

    {{-- ====== Selection Overlay ====== --}}
    <div id="selectionOverlay">
        <div class="particle-field" id="overlayParticles"></div>

        {{-- Big Sponsor logo at top during selection --}}
        @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
            <div
                class="absolute top-4 md:top-8 left-4 right-4 flex flex-col items-center justify-center z-50 pointer-events-none">
                @php $firstSponsor = $quizCompetition->sponsors->first(); @endphp
                @if($firstSponsor->image)
                    <img src="{{ asset('storage/' . $firstSponsor->image) }}"
                        class="h-32 sm:h-44 md:h-72 lg:h-96 w-auto object-contain drop-shadow-2xl max-w-full"
                        style="filter: drop-shadow(0 0 40px rgba(255,255,255,0.8));">
                    <div class="mt-2 md:mt-4 text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-white text-center w-full px-2"
                        style="text-shadow: 0 0 20px rgba(0,0,0,0.8), 0 0 10px rgba(34,197,94,0.6);">
                        برعاية: {{ $firstSponsor->name }}
                    </div>
                @endif
            </div>
        @endif

        {{-- Phase: Countdown --}}
        <div id="phaseCountdown" class="text-center relative">
            <div class="pulse-ring-anim" style="top:50%;left:50%;margin:-100px 0 0 -100px;animation-delay:0s;"></div>
            <div class="pulse-ring-anim" style="top:50%;left:50%;margin:-100px 0 0 -100px;animation-delay:0.4s;"></div>
            <div class="pulse-ring-anim" style="top:50%;left:50%;margin:-100px 0 0 -100px;animation-delay:0.8s;"></div>
            <p class="text-green-400/60 text-sm md:text-base mb-3 font-bold tracking-widest uppercase"
                style="letter-spacing:0.25em;">اختيار الفائز خلال</p>
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
        <div id="phaseAnnounce" class="text-center w-full px-4" style="display:none;">
            <div class="crown-anim inline-block mb-3" style="animation-delay:0.2s;">
                <i class="fas fa-crown text-amber-400 text-5xl md:text-6xl"
                    style="filter:drop-shadow(0 0 30px rgba(245,158,11,0.5));"></i>
            </div>
            <p class="text-shimmer-gold text-2xl md:text-3xl font-bold mb-5" id="announceTitle">مبروك للفائز!</p>

            <div
                class="flex flex-row items-center justify-between gap-2 sm:gap-4 md:gap-8 my-8 relative z-10 w-full max-w-5xl mx-auto px-2">
                <div class="flex-shrink-0 w-20 md:w-auto">
                    @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
                        @php $firstSponsor = $quizCompetition->sponsors->first(); @endphp
                        <div class="flex flex-col items-center gap-1 md:gap-2">
                            <div class="sponsor-reveal opacity-0 transform scale-50 rounded-2xl p-2 md:p-3 bg-white/10 backdrop-blur-sm border border-white/20 shadow-2xl flex items-center justify-center relative w-16 h-16 sm:w-20 sm:h-20 md:w-auto md:h-auto"
                                style="transition: all 0.8s cubic-bezier(0.34,1.56,0.64,1); animation-delay: 0.5s;">
                                <span
                                    class="absolute -top-3 right-1/2 translate-x-1/2 md:translate-x-0 md:right-4 bg-amber-400 text-amber-900 text-[8px] md:text-[10px] font-bold px-1.5 md:px-2 py-0.5 rounded shadow whitespace-nowrap">برعاية</span>
                                @if($firstSponsor->image)
                                    <img src="{{ asset('storage/' . $firstSponsor->image) }}"
                                        class="max-h-full max-w-full object-contain rounded-xl">
                                @else
                                    <span
                                        class="text-[10px] md:text-xl font-bold text-white px-1 md:px-4 text-center leading-tight">{{ $firstSponsor->name }}</span>
                                @endif
                            </div>

                        </div>
                    @endif
                </div>

                <div class="flex-1 text-center flex flex-col justify-center items-center min-w-0 px-2 line-clamp-2">
                    <div id="announceName"
                        class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white px-1 break-words leading-tight"
                        style="text-shadow:0 0 40px rgba(255,255,255,0.4);"></div>
                    <div id="announcePrize"
                        class="mt-2 md:mt-4 text-sm sm:text-xl md:text-3xl font-bold text-amber-300 hidden px-1 break-words"
                        style="text-shadow:0 0 20px rgba(245,158,11,0.6);"></div>
                </div>

                <div class="flex-shrink-0 w-20 md:w-auto">
                    @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
                        @php $lastSponsor = $quizCompetition->sponsors->count() > 1 ? $quizCompetition->sponsors->last() : $quizCompetition->sponsors->first(); @endphp
                        <div class="flex flex-col items-center gap-1 md:gap-2">
                            <div class="sponsor-reveal opacity-0 transform scale-50 rounded-2xl p-2 md:p-3 bg-white/10 backdrop-blur-sm border border-white/20 shadow-2xl flex items-center justify-center relative w-16 h-16 sm:w-20 sm:h-20 md:w-auto md:h-auto"
                                style="transition: all 0.8s cubic-bezier(0.34,1.56,0.64,1); animation-delay: 0.7s;">
                                <span
                                    class="absolute -top-3 right-1/2 translate-x-1/2 md:translate-x-0 md:left-4 bg-amber-400 text-amber-900 text-[8px] md:text-[10px] font-bold px-1.5 md:px-2 py-0.5 rounded shadow whitespace-nowrap">برعاية</span>
                                @if($lastSponsor->image)
                                    <img src="{{ asset('storage/' . $lastSponsor->image) }}"
                                        class="max-h-full max-w-full object-contain rounded-xl">
                                @else
                                    <span
                                        class="text-[10px] md:text-xl font-bold text-white px-1 md:px-4 text-center leading-tight">{{ $lastSponsor->name }}</span>
                                @endif
                            </div>

                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-center gap-2 mt-4" id="announceDots">
                <span class="w-3 h-3 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0s"></span>
                <span class="w-3 h-3 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0.15s"></span>
                <span class="w-3 h-3 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0.3s"></span>
            </div>
        </div>
    </div>

    @include('partials.main-header')

    <div class="fixed top-10 right-10 w-96 h-96 opacity-5 pointer-events-none hidden lg:block"
        style="animation: float 6s ease-in-out infinite;">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#22c55e"
                d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <div class="container mx-auto px-4 py-6 lg:py-10 relative z-10 max-w-3xl">
        <div class="mb-6 flex flex-wrap items-center gap-2 text-xs md:text-sm">
            <a href="{{ route('home') }}#activeQuizSection"
                class="text-green-600 hover:text-green-700 transition-colors font-medium">الرئيسية - المسابقات</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('quiz-competitions.show', $quizCompetition) }}"
                class="text-green-600 hover:text-green-700 transition-colors font-medium">{{ Str::limit($quizCompetition->title, 30) }}</a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-600">السؤال</span>
        </div>

        {{-- ==================== لم يبدأ ==================== --}}
        @if($status === 'not_started')
            <div class="glass-effect rounded-3xl green-glow p-6 md:p-10 text-center slide-in">
                <div
                    class="w-20 h-20 md:w-24 md:h-24 rounded-3xl mx-auto mb-6 flex items-center justify-center bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
                    <i class="fas fa-hourglass-start text-amber-500 text-3xl md:text-4xl"
                        style="animation:pulse-soft 2s infinite;"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">السؤال لم يبدأ بعد</h2>
                <p class="text-gray-500 text-sm mb-6">سيتم فتح السؤال في الموعد المحدد</p>
                <div
                    class="rounded-2xl p-5 mb-8 text-right bg-gradient-to-br from-green-50 to-green-100 border border-green-200">
                    <p class="text-green-600 text-xs mb-2 font-medium"><i class="fas fa-question-circle ml-1"></i> السؤال:
                    </p>
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
                                <div
                                    class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mb-2 bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
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

            {{-- ==================== السؤال مقفل (خلال 60 ثانية من البدء) ==================== --}}
        @elseif($status === 'question_locked')
            <div class="glass-effect rounded-3xl green-glow p-6 md:p-10 text-center slide-in" id="questionLockedContent">
                <div
                    class="w-20 h-20 md:w-24 md:h-24 rounded-3xl mx-auto mb-6 flex items-center justify-center bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200">
                    <i class="fas fa-lock text-amber-500 text-3xl md:text-4xl"
                        style="animation:pulse-soft 2s infinite;"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">السؤال يظهر قريباً</h2>
                <p class="text-gray-500 text-sm mb-6">سيظهر نص السؤال ونموذج الإجابة بعد مرور الوقت المحدد من بدء المسابقة
                </p>
                @if(isset($questionsVisibleAt) && $questionsVisibleAt)
                    <p class="text-gray-500 text-sm mb-4">السؤال يظهر بعد:</p>
                    <div class="flex justify-center gap-3 md:gap-4 mb-6">
                        <div class="text-center">
                            <div
                                class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mb-2 bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200">
                                <span class="text-2xl md:text-3xl font-bold text-green-600" id="ql-seconds">0</span>
                            </div>
                            <p class="text-gray-500 text-xs">ثانية</p>
                        </div>
                    </div>
                    <input type="hidden" id="qlVisibleAt" value="{{ $questionsVisibleAt->getTimestamp() * 1000 }}">
                @endif
            </div>

            {{-- ==================== انتهى ==================== --}}
        @elseif($status === 'ended')
            <div class="space-y-6 slide-in" id="endedContent">
                <div class="glass-effect rounded-3xl green-glow p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1.5"
                        style="background:linear-gradient(90deg,#22c55e,#16a34a,#22c55e);"></div>
                    <div class="flex items-center gap-2 mb-4">
                        <span
                            class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-xs px-3 py-1.5 rounded-full border border-gray-200"><i
                                class="fas fa-flag-checkered"></i> انتهت المسابقة</span>
                    </div>
                    <div class="text-xl md:text-2xl font-bold text-gray-800 mb-2 leading-relaxed question-text">
                        {!! $quizQuestion->question_text !!}
                    </div>
                    @if($quizQuestion->description)
                        <div class="text-gray-600 text-sm mb-2 quiz-description">{!! $quizQuestion->description !!}</div>
                    @endif
                    <p class="text-gray-400 text-xs"><i class="fas fa-calendar ml-1"></i>
                        @if($quizCompetition->start_at && $quizCompetition->end_at)
                            {{ $quizCompetition->start_at->translatedFormat('d M') }} -
                        {{ $quizCompetition->end_at->translatedFormat('d M Y') }} @else — @endif
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 md:gap-4">
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
                        <div
                            class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200">
                            <i class="fas fa-users text-blue-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجمالي المشاركين</p>
                    </div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center"
                        style="box-shadow:0 0 20px rgba(34,197,94,0.15);">
                        <div
                            class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 border border-green-200">
                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-green-600">{{ $stats['correct'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجابة صحيحة</p>
                    </div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
                        <div
                            class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100 border border-red-200">
                            <i class="fas fa-times-circle text-red-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-red-500">{{ $stats['wrong'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجابة خاطئة</p>
                    </div>
                </div>

                @if($stats['total'] > 0)
                    <div class="glass-effect rounded-2xl p-5">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                            <span>نسبة الإجابات الصحيحة</span>
                            <span
                                class="text-green-600 font-bold text-sm">{{ round(($stats['correct'] / $stats['total']) * 100) }}%</span>
                        </div>
                        <div class="w-full h-3 rounded-full overflow-hidden bg-gray-200">
                            <div class="h-full rounded-full"
                                style="width:{{ ($stats['correct'] / $stats['total']) * 100 }}%;background:linear-gradient(90deg,#22c55e,#16a34a);transition:width 1s;">
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ===== الفائزون ===== --}}
                @php
                    $hideWinnersForAnim = false;
                    if (isset($selectionAt) && $selectionAt) {
                        if (now()->timestamp < ($selectionAt->timestamp + 20) && !request()->has('animation_done')) {
                            $hideWinnersForAnim = true;
                        }
                    }
                @endphp
                @if($quizQuestion->winners->count() > 0)
                    <div id="winnersSection" class="relative" style="{{ $hideWinnersForAnim ? 'display:none;' : '' }}">
                        <div class="absolute inset-0 pointer-events-none overflow-hidden" id="sparklesBox"></div>
                        <div class="glass-effect rounded-3xl p-6 md:p-8 relative overflow-hidden glow-breath"
                            style="border:2px solid rgba(245,158,11,0.3);">
                            <div class="absolute top-0 right-0 left-0 h-2"
                                style="background:linear-gradient(90deg,#f59e0b,#fbbf24,#f59e0b,#fbbf24,#f59e0b);"></div>
                            <div
                                class="flex flex-row items-center justify-between gap-2 sm:gap-4 md:gap-8 mb-8 relative z-10 w-full max-w-5xl mx-auto px-1">
                                <div class="flex-shrink-0 w-16 sm:w-20 md:w-auto">
                                    @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
                                        @php $firstSponsor = $quizCompetition->sponsors->first(); @endphp
                                        <div class="flex flex-col items-center gap-1 md:gap-2">
                                            <div
                                                class="rounded-2xl p-1 md:p-2 bg-white/60 backdrop-blur-sm border border-amber-200 shadow-md flex items-center justify-center relative w-14 h-14 sm:w-16 sm:h-16 md:w-28 md:h-28">
                                                <span
                                                    class="absolute -top-2 md:-top-3 right-1/2 translate-x-1/2 md:translate-x-0 md:right-2 bg-amber-400 text-amber-900 text-[7px] md:text-[10px] font-bold px-1.5 md:px-2 py-0.5 rounded shadow whitespace-nowrap">برعاية</span>
                                                @if($firstSponsor->image)
                                                    <img src="{{ asset('storage/' . $firstSponsor->image) }}"
                                                        class="max-h-full max-w-full object-contain rounded-xl">
                                                @else
                                                    <span
                                                        class="text-[8px] md:text-xs font-bold text-gray-800 text-center leading-tight">{{ $firstSponsor->name }}</span>
                                                @endif
                                            </div>
                                            <!-- @if($firstSponsor->image)
                                                                    <span class="text-[8px] sm:text-[10px] md:text-sm font-bold text-gray-800 text-center break-words w-full">{{ $firstSponsor->name }}</span>
                                                                @endif -->
                                        </div>
                                    @endif
                                </div>

                                <div class="text-center flex-1 min-w-0 px-1">
                                    <div class="inline-block crown-anim" style="animation-delay:0.4s;"><i
                                            class="fas fa-crown text-amber-400 text-3xl sm:text-4xl md:text-5xl"
                                            style="filter:drop-shadow(0 0 20px rgba(245,158,11,0.5));"></i></div>
                                    <h3
                                        class="text-xl sm:text-2xl md:text-3xl font-bold mt-1 md:mt-3 break-words leading-tight">
                                        <span class="text-shimmer-gold line-clamp-2">الفائزون</span>
                                    </h3>
                                </div>

                                <div class="flex-shrink-0 w-16 sm:w-20 md:w-auto">
                                    @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
                                        @php $lastSponsor = $quizCompetition->sponsors->count() > 1 ? $quizCompetition->sponsors->last() : $quizCompetition->sponsors->first(); @endphp
                                        <div class="flex flex-col items-center gap-1 md:gap-2">
                                            <div
                                                class="rounded-2xl p-1 md:p-2 bg-white/60 backdrop-blur-sm border border-amber-200 shadow-md flex items-center justify-center relative w-14 h-14 sm:w-16 sm:h-16 md:w-28 md:h-28">
                                                <span
                                                    class="absolute -top-2 md:-top-3 right-1/2 translate-x-1/2 md:translate-x-0 md:left-2 bg-amber-400 text-amber-900 text-[7px] md:text-[10px] font-bold px-1.5 md:px-2 py-0.5 rounded shadow whitespace-nowrap">برعاية</span>
                                                @if($lastSponsor->image)
                                                    <img src="{{ asset('storage/' . $lastSponsor->image) }}"
                                                        class="max-h-full max-w-full object-contain rounded-xl">
                                                @else
                                                    <span
                                                        class="text-[8px] md:text-xs font-bold text-gray-800 text-center leading-tight">{{ $lastSponsor->name }}</span>
                                                @endif
                                            </div>
                                            <!-- @if($lastSponsor->image)
                                                                    <span class="text-[8px] sm:text-[10px] md:text-sm font-bold text-gray-800 text-center break-words w-full">{{ $lastSponsor->name }}</span>
                                                                @endif -->
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-4">
                                @foreach($quizQuestion->winners as $winner)
                                    <div class="winner-card-anim flex items-center gap-4 rounded-2xl p-5 relative overflow-hidden"
                                        style="animation-delay:{{ 0.7 + ($loop->index * 0.3) }}s;
                                                                background:linear-gradient(135deg,{{ $winner->position == 1 ? 'rgba(245,158,11,0.08),rgba(251,191,36,0.15)' : 'rgba(255,255,255,0.6),rgba(249,250,251,0.8)' }});
                                                                border:2px solid {{ $winner->position == 1 ? 'rgba(245,158,11,0.3)' : 'rgba(229,231,235,0.5)' }};">
                                        @if($winner->position == 1)
                                            <div class="absolute inset-0 opacity-20"
                                                style="background:radial-gradient(ellipse at 30% 50%,rgba(245,158,11,0.3),transparent 70%);">
                                        </div>@endif
                                        <div class="flex-shrink-0 relative z-10">
                                            @if($winner->position <= 3)
                                                <span
                                                    class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-xl text-white shadow-xl"
                                                    style="background:linear-gradient(135deg,{{ $winner->position == 1 ? '#f59e0b,#d97706' : ($winner->position == 2 ? '#9ca3af,#6b7280' : '#cd7f32,#a0522d') }});">{{ $winner->position }}</span>
                                            @else
                                                <span
                                                    class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-xl bg-gray-100 text-gray-500 border-2 border-gray-200">{{ $winner->position }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 relative z-10">
                                            <p
                                                class="font-bold text-lg md:text-xl {{ $winner->position == 1 ? 'text-amber-800' : 'text-gray-800' }}">
                                                {{ $winner->user->name ?? '-' }}
                                            </p>
                                            @if($winner->position == 1)
                                                <p class="font-bold text-green-600 text-sm md:text-base mt-2 flex items-center gap-2">
                                                    مبروك عليك 500 ريال</p>
                                            @elseif($winner->position == 2)
                                                <p class="font-bold text-green-600 text-sm md:text-base mt-2 flex items-center gap-2">
                                                    مبروك عليك 300 ريال</p>
                                            @elseif($winner->position == 3)
                                                <p class="font-bold text-green-600 text-sm md:text-base mt-2 flex items-center gap-2">
                                                    مبروك عليك 200 ريال</p>
                                            @endif
                                        </div>
                                        @if($winner->position == 1)<i class="fas fa-crown text-amber-400 text-2xl relative z-10"
                                            style="filter:drop-shadow(0 0 10px rgba(245,158,11,0.4));"></i>
                                        @elseif($winner->position <= 3)<i
                                        class="fas fa-medal text-{{ $winner->position == 2 ? 'gray-400' : 'amber-700/60' }} text-xl relative z-10"></i>@endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @php $correctAnswers = $quizQuestion->answers->where('is_correct', true); @endphp
                @if($correctAnswers->count() > 0)
                    <div id="correctAnswersSection" class="glass-effect rounded-2xl p-5"
                        style="{{ $hideWinnersForAnim ?? false ? 'display:none;' : '' }}">
                        <h4 class="text-sm font-bold text-green-600 mb-3 flex items-center gap-2"><i
                                class="fas fa-check-circle"></i> من جاوب صح ({{ $correctAnswers->count() }})</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($correctAnswers as $ans)
                                <span
                                    class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1.5 rounded-lg text-sm border border-green-200">{{ $ans->user->name ?? '-' }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($quizQuestion->winners->count() === 0 && !(isset($selectionAt) && $selectionAt && now()->lt($selectionAt)))
                    <div class="glass-effect rounded-2xl p-6 text-center"
                        style="{{ $hideWinnersForAnim ?? false ? 'display:none;' : '' }}"><i
                            class="fas fa-hourglass-end text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500 font-medium">لم يتم اختيار فائزين بعد</p>
                    </div>
                @endif
            </div>

            @if(isset($selectionAt) && $selectionAt)
                <input type="hidden" id="selectionAtMs" value="{{ $selectionAt->getTimestamp() * 1000 }}">
                <input type="hidden" id="endAtMs" value="{{ $quizCompetition->end_at->getTimestamp() * 1000 }}">
                <input type="hidden" id="winnerApiUrl"
                    value="{{ route('quiz-competitions.question.winner', [$quizCompetition, $quizQuestion]) }}">
                <input type="hidden" id="hasWinnersAlready" value="{{ $quizQuestion->winners->count() > 0 ? '1' : '0' }}">
                <input type="hidden" id="correctCount" value="{{ $stats['correct'] }}">
                <input type="hidden" id="candidateNamesJsonEnded" value='@json($candidateNames ?? [])'>
            @endif

            {{-- ==================== نشط ==================== --}}
        @else
            {{-- مودال نتيجة الإجابة (صح / خطأ) --}}
            @if(session('answer_submitted') && isset($quizQuestion))
                <div id="answerResultOverlay" class="{{ session('answer_correct') ? 'result-correct' : 'result-wrong' }}"
                    role="dialog" aria-modal="true" aria-labelledby="answerResultTitle"
                    onclick="if(event.target===this) closeAnswerResultModal();">
                    <div class="result-card rounded-3xl p-8 md:p-10 max-w-md w-full mx-4 text-center relative overflow-hidden {{ session('answer_correct') ? 'bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-300' : 'bg-gradient-to-br from-red-50 to-red-100 border-2 border-red-300' }}"
                        onclick="event.stopPropagation()">
                        @if(session('answer_correct'))
                            <div class="absolute inset-0 opacity-20 pointer-events-none"
                                style="background: radial-gradient(circle at 50% 30%, rgba(34,197,94,0.4), transparent 60%);"></div>
                            <div class="relative z-10">
                                <div
                                    class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center bg-gradient-to-br from-green-400 to-green-600 shadow-lg">
                                    <i class="fas fa-check-circle text-white text-4xl"></i>
                                </div>
                                <h2 id="answerResultTitle" class="text-xl md:text-2xl font-bold text-green-800 mb-2">أحسنت!</h2>
                                <p class="text-green-700 font-medium">إجابتك صحيحة</p>
                            </div>
                        @else
                            <div class="absolute inset-0 opacity-20 pointer-events-none"
                                style="background: radial-gradient(circle at 50% 30%, rgba(239,68,68,0.3), transparent 60%);"></div>
                            <div class="relative z-10">
                                <div
                                    class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center bg-gradient-to-br from-red-400 to-red-600 shadow-lg">
                                    <i class="fas fa-times-circle text-white text-4xl"></i>
                                </div>
                                <h2 id="answerResultTitle" class="text-xl md:text-2xl font-bold text-red-800 mb-2">للأسف</h2>
                                <p class="text-red-700 font-medium">إجابتك غير صحيحة</p>
                            </div>
                        @endif
                        <button type="button" onclick="closeAnswerResultModal()"
                            class="mt-6 px-6 py-3 rounded-xl font-bold text-sm transition-all {{ session('answer_correct') ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-red-500 hover:bg-red-600 text-white' }}">
                            حسناً
                        </button>
                    </div>
                </div>
            @endif

            <div class="space-y-6 slide-in">
                <div class="glass-effect rounded-3xl green-glow p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1.5"
                        style="background:linear-gradient(90deg,#22c55e,#16a34a,#22c55e);"></div>
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                        <span
                            class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-bold px-3 py-1.5 rounded-full border border-red-200"><span
                                class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span> مباشر الآن</span>
                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <i class="fas fa-hourglass-half text-amber-500 text-xs"></i><span>متبقي:</span>
                            <div class="flex gap-1 flex-row-reverse">
                                <span
                                    class="bg-green-50 border border-green-200 rounded-lg px-2 py-1 text-green-700 font-bold text-sm min-w-[2rem] text-center"
                                    id="at-hours">00</span>
                                <span class="text-gray-400 font-bold">:</span>
                                <span
                                    class="bg-green-50 border border-green-200 rounded-lg px-2 py-1 text-green-700 font-bold text-sm min-w-[2rem] text-center"
                                    id="at-minutes">00</span>
                                <span class="text-gray-400 font-bold">:</span>
                                <span
                                    class="bg-green-50 border border-green-200 rounded-lg px-2 py-1 text-green-700 font-bold text-sm min-w-[2rem] text-center"
                                    id="at-seconds">00</span>
                            </div>
                        </div>
                        @if($quizCompetition->end_at)<input type="hidden" id="atEndTime"
                                value="{{ $quizCompetition->end_at->getTimestamp() * 1000 }}">
                            <input type="hidden" id="activeWinnerApiUrl"
                                value="{{ route('quiz-competitions.question.winner', [$quizCompetition, $quizQuestion]) }}">
                            <input type="hidden" id="activeCorrectCount"
                                value="{{ $quizQuestion->answers->where('is_correct', true)->count() }}">
                        <input type="hidden" id="candidateNamesJson" value='@json($candidateNames ?? [])'>@endif
                    </div>
                    <p class="text-green-600 text-xs font-medium mb-3"><i class="fas fa-trophy ml-1"></i>
                        {{ $quizCompetition->title }}</p>
                    <div class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 leading-relaxed question-text">
                        {!! $quizQuestion->question_text !!}
                    </div>
                    @if($quizQuestion->description)
                        <div class="text-gray-600 text-sm mt-2 quiz-description">{!! $quizQuestion->description !!}</div>
                    @endif

                    @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
                        <div class="mt-6 pt-5 border-t border-green-100 flex flex-wrap items-center gap-3">
                            <span class="text-sm font-bold text-gray-500"><i class="fas fa-handshake ml-1"></i> برعاية:</span>
                            @foreach($quizCompetition->sponsors as $sponsor)
                                <div class="bg-white/60 border border-green-50 rounded-lg px-3 py-1.5 flex items-center gap-2 justify-center shadow-sm"
                                    title="{{ $sponsor->name }}">
                                    @if($sponsor->image)
                                        <img src="{{ asset('storage/' . $sponsor->image) }}" alt="{{ $sponsor->name }}"
                                            class="h-6 md:h-8 object-contain rounded">
                                    @endif
                                    <span class="text-sm font-bold text-green-700">{{ $sponsor->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="grid grid-cols-3 gap-3 md:gap-4">
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
                        <div
                            class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200">
                            <i class="fas fa-users text-blue-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجمالي الإجابات</p>
                    </div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center"
                        style="box-shadow:0 0 20px rgba(34,197,94,0.15);">
                        <div
                            class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 border border-green-200">
                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-green-600">{{ $stats['correct'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجابة صحيحة</p>
                    </div>
                    <div class="glass-effect rounded-2xl p-4 md:p-5 text-center">
                        <div
                            class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100 border border-red-200">
                            <i class="fas fa-times-circle text-red-500 text-lg"></i>
                        </div>
                        <p class="text-2xl md:text-3xl font-bold text-red-500">{{ $stats['wrong'] }}</p>
                        <p class="text-gray-500 text-xs mt-1">إجابة خاطئة</p>
                    </div>
                </div>

                @if(session('error'))
                    <div class="rounded-2xl p-4 flex items-center gap-3 bg-red-50 border border-red-200 slide-in">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-red-400 to-red-500">
                            <i class="fas fa-exclamation-circle text-white text-lg"></i>
                        </div>
                        <p class="text-red-700 font-bold">{{ session('error') }}</p>
                    </div>
                @endif
                @if($errors->any())
                    <div class="rounded-2xl p-4 bg-red-50 border border-red-200">
                        <ul class="space-y-1">@foreach($errors->all() as $error)<li
                            class="text-red-600 text-sm flex items-center gap-2"><i
                        class="fas fa-circle text-[6px] text-red-400"></i>{{ $error }}</li>@endforeach</ul>
                </div>@endif

                @if(session('answer_submitted'))
                    <div class="rounded-2xl p-5 flex items-center gap-3 bg-amber-50 border border-amber-200">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-amber-100"><i
                                class="fas fa-check-circle text-amber-600"></i></div>
                        <p class="text-amber-800 font-medium">لقد أجبت على هذا السؤال. سيتم اختيار الفائز تلقائياً بعد انتهاء
                            وقت المسابقة.</p>
                    </div>
                @endif

                @if(!session('answer_submitted') && !($canAnswer ?? true))
                    <div class="rounded-2xl p-5 flex items-center gap-3 bg-amber-50 border border-amber-200">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-amber-100"><i
                                class="fas fa-check-circle text-amber-600"></i></div>
                        <p class="text-amber-800 font-medium">لقد أجبت على هذا السؤال</p>
                    </div>
                @endif

                @if(!session('answer_submitted') && ($canAnswer ?? true))
                    @if ($quizCompetition->show_draw_only)
                        <div class="rounded-3xl p-8 bg-green-50 border-2 border-green-200 text-center space-y-4">
                            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-info-circle text-green-600 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-green-800">باب الإجابة مغلق حالياً</h3>
                            <p class="text-green-700">يمكنك الآن متابعة فرز النتائج والقرعة لاختيار الفائزين مباشرة.</p>
                            <div class="pt-4">
                                <span
                                    class="px-6 py-3 rounded-2xl font-bold bg-green-600 text-white inline-flex items-center gap-2">
                                    <i class="fas fa-sync fa-spin"></i>
                                    يرجى الانتظار لبدء السحب...
                                </span>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('quiz-competitions.store-answer', [$quizCompetition, $quizQuestion]) }}"
                            method="POST" class="space-y-5" id="quizForm">
                    @endif
                        @csrf
                        <div class="glass-effect rounded-2xl p-5 md:p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <div
                                    class="w-8 h-8 rounded-lg flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 border border-blue-300">
                                    <i class="fas fa-user text-blue-500 text-sm"></i>
                                </div>
                                <h3 class="font-bold text-gray-800">بياناتك</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><label class="block text-gray-600 text-sm mb-2 font-medium">الاسم <span
                                            class="text-red-500">*</span></label><input type="text" name="name"
                                        value="{{ old('name') }}" required
                                        class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all"
                                        placeholder="أدخل اسمك الكامل"></div>
                                <div><label class="block text-gray-600 text-sm mb-2 font-medium">رقم الهاتف <span
                                            class="text-red-500">*</span></label><input type="text" name="phone"
                                        value="{{ old('phone') }}" required pattern="[0-9]{10}" minlength="10" maxlength="10"
                                        class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all"
                                        placeholder="05xxxxxxxx" dir="ltr" style="text-align:right;"
                                        title="يجب أن يكون رقم الهاتف 10 أرقام"></div>
                            </div>
                            <div class="space-y-3 mt-4">
                                <div
                                    class="flex items-center gap-2 p-3 rounded-xl border-2 border-gray-200 bg-white/80 hover:border-green-300 hover:bg-green-50/50 cursor-pointer transition-all">
                                    <input type="checkbox" name="is_from_ancestry" value="1" id="is_from_ancestry_question"
                                        class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500" {{ old('is_from_ancestry') ? 'checked' : '' }}
                                        onchange="toggleMotherNameFieldQuestion(this.checked)">
                                    <label for="is_from_ancestry_question"
                                        class="text-gray-800 text-sm font-medium cursor-pointer">
                                        أنا من الأنساب
                                    </label>
                                </div>
                                <div id="mother_name_field_question" class="hidden">
                                    <label class="block text-gray-600 text-sm mb-2 font-medium">اسم الأم <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="mother_name" value="{{ old('mother_name') }}"
                                        class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all"
                                        placeholder="اسم الأم الكامل">
                                </div>
                            </div>
                        </div>
                        <div class="glass-effect rounded-2xl p-5 md:p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <div
                                    class="w-8 h-8 rounded-lg flex items-center justify-center bg-gradient-to-br from-green-100 to-green-200 border border-green-300">
                                    <i class="fas fa-pen text-green-600 text-sm"></i>
                                </div>
                                <h3 class="font-bold text-gray-800">إجابتك</h3>
                            </div>
                            <div class="space-y-3">
                                @if($quizQuestion->answer_type === 'ordering' && $quizQuestion->choices->count() > 0)
                                    <p class="text-sm text-green-700 font-medium mb-2 pr-2">
                                        <i class="fas fa-info-circle ml-1"></i> قم بسحب وإفلات الخيارات لترتيبها بشكل صحيح
                                    </p>
                                    @php
                                        $shuffledChoices = clone $quizQuestion->choices;
                                        $shuffledChoices = $shuffledChoices->shuffle();
                                    @endphp
                                    <div class="space-y-2 sortable-list" data-question-id="{{ $quizQuestion->id }}">
                                        @foreach($shuffledChoices as $choice)
                                            <div data-id="{{ $choice->id }}"
                                                class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 bg-white hover:border-green-300 cursor-move transition-all sortable-item select-none shadow-sm text-gray-700">
                                                <i class="fas fa-grip-lines text-gray-400"></i>
                                                <span
                                                    class="font-medium text-sm md:text-base flex-grow">{{ $choice->choice_text }}</span>
                                                <input type="hidden" name="answer[]" value="{{ $choice->id }}"
                                                    class="ordering-input-hidden">
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($quizQuestion->is_multiple_selections)
                                    @php
                                        $requiredCount = $quizQuestion->getRequiredCorrectAnswersCount();
                                    @endphp
                                    <p class="text-sm text-green-700 font-medium mb-2 pr-2">
                                        <i class="fas fa-info-circle ml-1"></i> بجب اختيار عدد ({{ $requiredCount }}) إجابات صحيحة
                                        <input type="hidden" id="requiredChoicesCount" value="{{ $requiredCount }}">
                                    </p>
                                @endif

                                @foreach($quizQuestion->choices as $choice)
                                    <label class="choice-option flex items-center gap-4 p-4 rounded-xl cursor-pointer">
                                        @if($quizQuestion->is_multiple_selections)
                                            <input type="checkbox" name="answer[]" value="{{ $choice->id }}"
                                                class="hidden quiz-checkbox-input">
                                            <div
                                                class="relative w-6 h-6 rounded flex items-center justify-center border-2 border-gray-300 bg-gray-100 flex-shrink-0 transition-all custom-checkbox">
                                                <i class="fas fa-check text-white text-xs opacity-0 transition-opacity"></i>
                                            </div>
                                        @else
                                            <input type="radio" name="answer" value="{{ $choice->id }}" class="hidden" required>
                                            <span
                                                class="choice-number w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm flex-shrink-0 transition-all">{{ $loop->iteration }}</span>
                                        @endif
                                        <span
                                            class="text-gray-700 font-medium text-sm md:text-base">{{ $choice->choice_text }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <style>
                                .choice-option:has(input[type="checkbox"]:checked) .custom-checkbox {
                                    background-color: #22c55e;
                                    border-color: #22c55e;
                                }

                                .choice-option:has(input[type="checkbox"]:checked) .custom-checkbox i {
                                    opacity: 1;
                                }
                            </style>
                            <textarea name="answer" rows="4" required
                                class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white/70 text-gray-800 text-sm resize-none focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all"
                                placeholder="اكتب إجابتك هنا...">{{ old('answer') }}</textarea>
                @endif
                        <!-- <button type="button" onclick="submitQuizForm()" class="w-full py-4 md:py-5 rounded-2xl text-white font-bold text-lg flex items-center justify-center gap-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl active:scale-[0.98]" style="background:linear-gradient(135deg,#22c55e,#16a34a);box-shadow:0 8px 25px rgba(34,197,94,0.4);"><i class="fas fa-paper-plane"></i><span>إرسال الإجابة</span></button> -->
                </form>
        @endif
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- SortableJS for Drag and Drop Ordering -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

        <script>
            function submitQuizForm() {
                const form = document.getElementById('quizForm');
                const requiredCountInput = document.getElementById('requiredChoicesCount');

                if (requiredCountInput) {
                    const requiredCount = parseInt(requiredCountInput.value);
                    const checkedBoxes = document.querySelectorAll('.quiz-checkbox-input:checked');

                    if (checkedBoxes.length !== requiredCount) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'تنبيه',
                            text: `الرجاء اختيار عدد ${requiredCount} إجابات كما هو مطلوب بالسؤال.`,
                            confirmButtonColor: '#22c55e',
                            confirmButtonText: 'حسناً'
                        });
                        return;
                    }
                }

                // Trigger native form submission validation
                if (!form.reportValidity()) {
                    return;
                }

                form.submit();
            }

            // Limit checkbox selections
            document.addEventListener('DOMContentLoaded', function () {
                const requiredCountInput = document.getElementById('requiredChoicesCount');
                if (requiredCountInput) {
                    const requiredCount = parseInt(requiredCountInput.value);
                    const checkboxes = document.querySelectorAll('.quiz-checkbox-input');

                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', function () {
                            const checked = document.querySelectorAll('.quiz-checkbox-input:checked');
                            if (checked.length > requiredCount) {
                                this.checked = false;
                                Swal.fire({
                                    icon: 'info',
                                    title: 'تم تجاوز الحد الأقصى',
                                    text: `لا يمكنك اختيار أكثر من ${requiredCount} إجابات.`,
                                    confirmButtonColor: '#22c55e',
                                    confirmButtonText: 'حسناً',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        });
                    });
                }

                // Initialize SortableJS
                const lists = document.querySelectorAll('.sortable-list');
                lists.forEach(list => {
                    new Sortable(list, {
                        animation: 150,
                        ghostClass: 'bg-green-50',
                    });
                });
            });
        </script>

        <div class="mt-8 text-center">
            <a href="{{ route('quiz-competitions.show', $quizCompetition) }}"
                class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 transition-colors text-sm font-medium"><i
                    class="fas fa-arrow-right"></i><span>العودة للمسابقة</span></a>
        </div>
    </div>

    <script>
        /* ============ CONFETTI ============ */
        var Confetti = {
            canvas: null, ctx: null, particles: [], running: false,
            colors: ['#f59e0b', '#22c55e', '#ef4444', '#3b82f6', '#a855f7', '#ec4899', '#fbbf24', '#14b8a6'],
            init: function () {
                this.canvas = document.getElementById('confettiCanvas');
                if (!this.canvas) return;
                this.ctx = this.canvas.getContext('2d');
                this.resize();
                window.addEventListener('resize', this.resize.bind(this));
            },
            resize: function () { if (this.canvas) { this.canvas.width = window.innerWidth; this.canvas.height = window.innerHeight; } },
            launch: function (count, dur) {
                this.particles = [];
                for (var i = 0; i < (count || 200); i++) this.particles.push({
                    x: Math.random() * this.canvas.width, y: -20 - Math.random() * this.canvas.height * 0.5,
                    w: 4 + Math.random() * 8, h: 4 + Math.random() * 8,
                    color: this.colors[Math.floor(Math.random() * this.colors.length)],
                    vx: (Math.random() - 0.5) * 8, vy: 2 + Math.random() * 6,
                    rot: Math.random() * 360, rotV: (Math.random() - 0.5) * 15, opacity: 1,
                    shape: Math.random() > 0.5 ? 'r' : 'c'
                });
                this.running = true; var self = this, start = Date.now(), duration = dur || 5000;
                (function frame() {
                    if (!self.running) return;
                    var p = Math.min((Date.now() - start) / duration, 1);
                    self.ctx.clearRect(0, 0, self.canvas.width, self.canvas.height);
                    self.particles.forEach(function (pt) {
                        pt.x += pt.vx; pt.y += pt.vy; pt.vy += 0.1; pt.rot += pt.rotV;
                        if (p > 0.7) pt.opacity = Math.max(0, 1 - (p - 0.7) / 0.3);
                        self.ctx.save(); self.ctx.translate(pt.x, pt.y); self.ctx.rotate(pt.rot * Math.PI / 180);
                        self.ctx.globalAlpha = pt.opacity; self.ctx.fillStyle = pt.color;
                        if (pt.shape === 'r') self.ctx.fillRect(-pt.w / 2, -pt.h / 2, pt.w, pt.h);
                        else { self.ctx.beginPath(); self.ctx.arc(0, 0, pt.w / 2, 0, Math.PI * 2); self.ctx.fill(); }
                        self.ctx.restore();
                    });
                    if (p < 1) requestAnimationFrame(frame);
                    else { self.ctx.clearRect(0, 0, self.canvas.width, self.canvas.height); self.running = false; }
                })();
            }
        };

        /* ============ DIGITAL NAME SELECTOR ============ */
        function DigitalSelector(candidateNames) {
            var nameEl = document.getElementById('scrollerName');
            var boxEl = document.getElementById('scrollerBox');
            var subtext = document.getElementById('selectorSubtext');
            if (!nameEl) return;

            // Use real candidate names if available, otherwise fake Arabic names
            var namesPool = (candidateNames && candidateNames.length > 0) ? candidateNames : [
                'محمد أحمد', 'عبدالله سعد', 'فهد خالد', 'سارة علي', 'نورة محمد',
                'خالد عبدالرحمن', 'أحمد يوسف', 'عمر حسن', 'فاطمة إبراهيم', 'مريم صالح',
                'سلطان ناصر', 'عبدالعزيز فيصل', 'هند ماجد', 'لمى عادل', 'ريم طارق',
                'بندر سعود', 'تركي عبدالله', 'نوف سلمان', 'دانة وليد', 'جود حمد',
                'راشد بدر', 'ماجد عايض', 'هيا محمد', 'العنود سعد', 'عبير فهد',
                'صالح حمود', 'ياسر عمر', 'منال خالد', 'أسماء أحمد', 'سلمان ابراهيم'
            ];

            var running = false;
            var stopping = false;
            var winners = [];
            var finalCallback = null;

            this.start = function () {
                running = true;
                stopping = false;
                winners = [];

                // مسح الأنماط المتبقية من الدورة السابقة
                nameEl.classList.remove('blurred', 'clear');
                nameEl.style.filter = 'blur(6px)';
                nameEl.style.opacity = '0.9';
                nameEl.style.color = '#fff';
                boxEl.classList.remove('winner-found');
                var scanLine = boxEl.querySelector('.scan-line');
                if (scanLine) scanLine.style.display = 'block';

                (function frame() {
                    if (!running) return;

                    var interval = 50;

                    // Pick random name
                    var displayName = namesPool[Math.floor(Math.random() * namesPool.length)];
                    nameEl.textContent = displayName;
                    nameEl.style.filter = 'blur(6px)';
                    nameEl.style.opacity = '0.9';
                    nameEl.style.color = '#fff';

                    setTimeout(frame, interval);
                })();
            };

            this.reveal = function (targetWinnerName, callback) {
                stopping = true;
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
                    if (progress < 0.2) interval = 50;
                    else if (progress < 0.4) interval = 100;
                    else if (progress < 0.6) interval = 200;
                    else if (progress < 0.75) interval = 300;
                    else if (progress < 0.85) interval = 400;
                    else if (progress < 0.95) interval = 600;
                    else interval = 800;

                    var displayName;

                    // الاستمرار في الدوران بأسماء عشوائية حتى النهاية
                    displayName = namesPool[Math.floor(Math.random() * namesPool.length)];

                    nameEl.style.filter = 'blur(6px)';
                    nameEl.style.opacity = '0.9';
                    nameEl.style.color = '#fff';

                    nameEl.textContent = displayName;

                    // Subtext update
                    if (subtext) {
                        if (progress < 0.3) subtext.textContent = 'جاري البحث بين المشاركين...';
                        else if (progress < 0.6) subtext.textContent = 'تضييق نطاق البحث...';
                        else if (progress < 0.85) subtext.textContent = 'اقتربنا من النتيجة...';
                        else subtext.textContent = 'تم تحديد الفائز!';
                    }

                    if (progress >= 1) {
                        // Show the last place winner initially before sequence starts
                        nameEl.textContent = targetWinnerName;
                        // إزالة blur نهائياً من اسم الفائز
                        nameEl.style.filter = 'blur(0)';
                        nameEl.style.opacity = '1';
                        nameEl.style.color = '#fbbf24';
                        boxEl.classList.add('winner-found');
                        var scanLine = boxEl.querySelector('.scan-line');
                        if (scanLine) scanLine.style.display = 'none';
                        setTimeout(function () { if (finalCallback) finalCallback(targetWinnerName); }, 800);
                        return;
                    }
                    setTimeout(slowFrame, interval);
                })();
            };
        }

        /* ============ OVERLAY PARTICLES ============ */
        function initOverlayParticles() {
            var container = document.getElementById('overlayParticles');
            if (!container) return;
            for (var i = 0; i < 40; i++) {
                var p = document.createElement('span');
                p.style.left = Math.random() * 100 + '%';
                p.style.bottom = '-10px';
                p.style.animationDelay = (Math.random() * 4) + 's';
                p.style.animationDuration = (2 + Math.random() * 3) + 's';
                p.style.animation = 'particleFly ' + (2 + Math.random() * 3) + 's ease-out ' + (Math.random() * 4) + 's infinite';
                container.appendChild(p);
            }
        }

        /* ============ SPARKLES ============ */
        function addSparkles(el, count) {
            if (!el) return;
            for (var i = 0; i < count; i++) {
                var s = document.createElement('div'); s.className = 'sparkle-dot';
                s.style.top = Math.random() * 100 + '%'; s.style.left = Math.random() * 100 + '%';
                s.style.animationDelay = (Math.random() * 3) + 's';
                s.style.width = (3 + Math.random() * 6) + 'px'; s.style.height = s.style.width;
                el.appendChild(s);
            }
        }

        /* ============ WINNER ANIMATION ORCHESTRATOR ============ */
        function startWinnerReveal(winnerNamesList, correctCount, candidateNames) {
            var overlay = document.getElementById('selectionOverlay');
            var phaseCount = document.getElementById('phaseCountdown');
            var phaseSelector = document.getElementById('phaseSelector');
            var phaseAnnounce = document.getElementById('phaseAnnounce');
            var countNum = document.getElementById('bigCountNum');
            var flashBang = document.getElementById('flashBang');

            if (!overlay) {
                var url = new URL(window.location.href);
                if (!url.searchParams.has('animation_done')) {
                    url.searchParams.set('animation_done', '1');
                    window.location.href = url.toString();
                }
                return;
            }

            initOverlayParticles();
            overlay.classList.add('active');

            // Phase 1: Countdown from 2
            var countVal = 2;
            countNum.textContent = countVal;
            countNum.classList.add('count-pop');

            var countTimer = setInterval(function () {
                countVal--;
                if (countVal <= 0) {
                    clearInterval(countTimer);
                    flashBang.classList.add('flash');
                    document.body.classList.add('shake-it');
                    setTimeout(function () {
                        flashBang.classList.remove('flash');
                        document.body.classList.remove('shake-it');
                    }, 500);

                    // Phase 2: Start the sequential reveal process
                    startSequentialReveal(winnerNamesList, candidateNames);
                    return;
                }

                countNum.textContent = countVal;
                countNum.classList.remove('count-pop');
                void countNum.offsetWidth;
                countNum.classList.add('count-pop');
                if (countVal <= 2) countNum.classList.add('danger');
            }, 900);
        }

        function startSequentialReveal(winnerNamesList, candidateNames) {
            var phaseCount = document.getElementById('phaseCountdown');
            var phaseSelector = document.getElementById('phaseSelector');
            var phaseAnnounce = document.getElementById('phaseAnnounce');
            var flashBang = document.getElementById('flashBang');

            phaseCount.style.display = 'none';

            if (!winnerNamesList || winnerNamesList.length === 0) {
                var url = new URL(window.location.href);
                if (!url.searchParams.has('animation_done')) {
                    url.searchParams.set('animation_done', '1');
                    window.location.href = url.toString();
                }
                return;
            }

            let currentWinnerIndex = winnerNamesList.length - 1; // Start from last (nth) winner

            function revealNextWinner() {
                if (currentWinnerIndex < 0) {
                    // All winners shown, reload after a final delay
                    setTimeout(function () {
                        var url = new URL(window.location.href);
                        url.searchParams.set('animation_done', '1');
                        window.location.href = url.toString();
                    }, 3500);
                    return;
                }

                // Hide announce phase, show selector phase for spinning
                phaseAnnounce.style.display = 'none';
                phaseSelector.style.display = 'block';

                // Reset selector UI
                var boxEl = document.getElementById('scrollerBox');
                if (boxEl) boxEl.classList.remove('winner-found');
                var scanLine = document.querySelector('.scan-line');
                if (scanLine) scanLine.style.display = 'block';

                var currentTargetName = winnerNamesList[currentWinnerIndex];
                var selector = new DigitalSelector(candidateNames);
                selector.start();

                // Spin for 3 seconds, then reveal this specific winner
                setTimeout(function () {
                    selector.reveal(currentTargetName, function (revealedName) {

                        // Flash!
                        flashBang.classList.add('flash');
                        setTimeout(function () { flashBang.classList.remove('flash'); }, 500);

                        Confetti.launch(350, 5000);

                        // Switch to announce phase
                        setTimeout(function () {
                            phaseSelector.style.display = 'none';
                            phaseAnnounce.style.display = 'block';

                            // Reset sponsor animation
                            document.querySelectorAll('.sponsor-reveal').forEach(function (el) {
                                el.style.opacity = '0';
                                el.style.transform = 'scale(0.5)';
                            });

                            // Update UI for current winner
                            document.getElementById('announceName').textContent = revealedName;

                            let positionText = "";
                            if (currentWinnerIndex === 0) positionText = "المركز الأول";
                            else if (currentWinnerIndex === 1) positionText = "المركز الثاني";
                            else if (currentWinnerIndex === 2) positionText = "المركز الثالث";
                            else positionText = "المركز " + (currentWinnerIndex + 1);

                            document.getElementById('announceTitle').textContent = "مبروك للفائز بـ " + positionText + "!";

                            var prizeEl = document.getElementById('announcePrize');
                            if (prizeEl) {
                                let prizeText = "";
                                if (currentWinnerIndex === 0) prizeText = "مبروك عليك 500 ريال";
                                else if (currentWinnerIndex === 1) prizeText = "مبروك عليك 300 ريال";
                                else if (currentWinnerIndex === 2) prizeText = "مبروك عليك 200 ريال";

                                if (prizeText) {
                                    prizeEl.textContent = prizeText;
                                    prizeEl.classList.remove('hidden');
                                } else {
                                    prizeEl.classList.add('hidden');
                                }
                            }

                            Confetti.launch(150, 2000);

                            // Show sponsors after small delay
                            setTimeout(function () {
                                document.querySelectorAll('.sponsor-reveal').forEach(function (el) {
                                    el.style.opacity = '1';
                                    el.style.transform = 'scale(1)';
                                });
                            }, 300);

                            // Flash and shake for each new winner
                            flashBang.classList.add('flash');
                            setTimeout(function () { flashBang.classList.remove('flash'); }, 500);
                            document.body.classList.add('shake-it');
                            setTimeout(function () {
                                document.body.classList.remove('shake-it');
                            }, 500);

                            currentWinnerIndex--;

                            // Wait on the announce screen before spinning again (or finishing)
                            var displayDuration = (currentWinnerIndex < 0) ? 5000 : 4000;

                            setTimeout(function () {
                                if (currentWinnerIndex < 0) {
                                    var dots = document.getElementById('announceDots');
                                    if (dots) dots.style.display = 'none';
                                }
                                revealNextWinner();
                            }, displayDuration);

                        }, 800); // Small delay after selector slows down completely
                    });
                }, 3000); // Spin duration before slowdown
            }

            revealNextWinner();
        }

        function startZeroDelayAnimation(candidateNames, correctCount) {
            var overlay = document.getElementById('selectionOverlay');
            var phaseCount = document.getElementById('phaseCountdown');
            var phaseSelector = document.getElementById('phaseSelector');
            var flashBang = document.getElementById('flashBang');

            if (!overlay) return null; // return selector instance

            initOverlayParticles();
            overlay.classList.add('active');

            // Skip countdown -> Go straight to selector
            phaseCount.style.display = 'none';
            phaseSelector.style.display = 'block';

            // Flash!
            flashBang.classList.add('flash');
            setTimeout(function () { flashBang.classList.remove('flash'); }, 500);

            var selector = new DigitalSelector(candidateNames);
            selector.start();
            return selector;
        }

        function finalizeZeroDelay(selector, winnerNamesList, candidateNames) {
            if (!selector) return;

            // Stop the initial zero-delay selector (it was just spinning while fetching)
            // Note: we're bypassing its reveal and just jumping into the sequential process
            // to maintain the same flow. We'll hide it briefly and restart the process.

            var phaseSelector = document.getElementById('phaseSelector');
            if (phaseSelector) phaseSelector.style.display = 'none';

            startSequentialReveal(winnerNamesList, candidateNames);
        }

        /* ============ AJAX WINNER FETCHER WITH JITTER ============ */
        function fetchWinnerFromServer(apiUrl, callback) {
            function doFetch() {
                fetch(apiUrl)
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data.status === 'done' && data.winners && data.winners.length > 0) {
                            // Extract all winner names ordered by position (1st, 2nd, etc.)
                            var winnerNamesList = data.winners.sort((a, b) => a.position - b.position).map(w => w.name);
                            callback(winnerNamesList);
                        } else if (data.status === 'pending') {
                            // Retry after 2 seconds
                            setTimeout(doFetch, 2000);
                        } else {
                            // no_winners — reload with a query parameter to avoid infinite loops
                            var url = new URL(window.location.href);
                            if (!url.searchParams.has('animation_done')) {
                                url.searchParams.set('animation_done', '1');
                                window.location.href = url.toString();
                            }
                        }
                    })
                    .catch(function () {
                        // Network error — retry after 3 seconds
                        setTimeout(doFetch, 3000);
                    });
            }
            doFetch();
        }

        function closeAnswerResultModal() {
            var el = document.getElementById('answerResultOverlay');
            if (el) { el.classList.remove('show'); }
        }

        /* ============ PAGE INIT ============ */
        document.addEventListener('DOMContentLoaded', function () {
            Confetti.init();

            @if(session('answer_submitted'))
                (function () {
                    var overlay = document.getElementById('answerResultOverlay');
                    if (overlay) {
                        setTimeout(function () {
                            overlay.classList.add('show');
                            if (overlay.classList.contains('result-correct') && typeof Confetti !== 'undefined') {
                                Confetti.launch(120, 3500);
                            }
                        }, 300);
                    }
                })();
            @endif

            @if($status === 'not_started' && $quizCompetition->start_at)
                (function () {
                    var target = parseInt(document.getElementById('nsTarget').value, 10);
                    function update() {
                        var d = target - Date.now(); if (d <= 0) { location.reload(); return; }
                        document.getElementById('ns-days').textContent = Math.floor(d / 86400000);
                        document.getElementById('ns-hours').textContent = Math.floor((d % 86400000) / 3600000);
                        document.getElementById('ns-minutes').textContent = Math.floor((d % 3600000) / 60000);
                        document.getElementById('ns-seconds').textContent = Math.floor((d % 60000) / 1000);
                    } update(); setInterval(update, 1000);
                })();
            @endif

            @if($status === 'question_locked' && isset($questionsVisibleAt) && $questionsVisibleAt)
                (function () {
                    var visibleAtInput = document.getElementById('qlVisibleAt');
                    var secondsEl = document.getElementById('ql-seconds');
                    if (!visibleAtInput || !secondsEl) return;
                    var target = parseInt(visibleAtInput.value, 10);
                    function update() {
                        var d = target - Date.now();
                        if (d <= 0) { location.reload(); return; }
                        secondsEl.textContent = Math.ceil(d / 1000);
                    }
                    update();
                    setInterval(update, 1000);
                })();
            @endif

            @if($status === 'active' && $quizCompetition->end_at)
                (function () {
                    var end = parseInt(document.getElementById('atEndTime').value, 10);
                    var apiUrl = document.getElementById('activeWinnerApiUrl').value;
                    var correctCount = parseInt(document.getElementById('activeCorrectCount').value, 10) || 0;
                    var candidates = JSON.parse(document.getElementById('candidateNamesJson').value || '[]');
                    var timerDone = false;

                    function pad(n) { return n.toString().padStart(2, '0'); }
                    function update() {
                        var d = end - Date.now();
                        if (d <= 0 && !timerDone) {
                            timerDone = true;
                            // Show 00:00:00
                            document.getElementById('at-hours').textContent = '00';
                            document.getElementById('at-minutes').textContent = '00';
                            document.getElementById('at-seconds').textContent = '00';

                            // ZERO DELAY: Start animation IMMEDIATELY while fetching winner
                            var selector = startZeroDelayAnimation(candidates, correctCount);

                            // Fetch winner in parallel
                            // زيادة jitter قليلاً لأن المدة أصبحت أطول
                            var jitter = Math.floor(Math.random() * 3000);
                            setTimeout(function () {
                                fetchWinnerFromServer(apiUrl, function (winnerNamesList) {
                                    finalizeZeroDelay(selector, winnerNamesList, candidates);
                                });
                            }, jitter);
                            return;
                        }
                        if (timerDone) return;
                        document.getElementById('at-hours').textContent = pad(Math.floor(d / 3600000));
                        document.getElementById('at-minutes').textContent = pad(Math.floor((d % 3600000) / 60000));
                        document.getElementById('at-seconds').textContent = pad(Math.floor((d % 60000) / 1000));
                    } update(); setInterval(update, 1000);
                })();
            @endif

            @if($status === 'ended' && isset($selectionAt) && $selectionAt)
                (function () {
                    var selAt = parseInt(document.getElementById('selectionAtMs').value, 10);
                    var endAt = parseInt(document.getElementById('endAtMs').value, 10);
                    var apiUrl = document.getElementById('winnerApiUrl').value;
                    var hasWinners = document.getElementById('hasWinnersAlready').value === '1';
                    var correctCount = parseInt(document.getElementById('correctCount').value, 10) || 0;
                    var candidates = JSON.parse(document.getElementById('candidateNamesJsonEnded').value || '[]');
                    var now = Date.now();
                    var urlParams = new URLSearchParams(window.location.search);

                    // If winners already exist and it's been more than 20 seconds OR we just finished animation, skip
                    if ((hasWinners && now > selAt + 20000) || urlParams.has('animation_done')) {
                        addSparkles(document.getElementById('sparklesBox'), 20);
                        Confetti.launch(200, 5000);
                        return;
                    }

                    // Small jitter to distribute 1000+ requests
                    var jitter = Math.floor(Math.random() * 3000);

                    // Calculate when to fetch the winner
                    var fetchTime = Math.max(selAt + jitter, now);
                    var delay = fetchTime - now;

                    setTimeout(function () {
                        fetchWinnerFromServer(apiUrl, function (winnerNamesList) {
                            startWinnerReveal(winnerNamesList, correctCount, candidates);
                        });
                    }, delay);
                })();
            @endif

            @if($status === 'ended' && $quizQuestion->winners->count() > 0 && !(isset($selectionAt) && $selectionAt && now()->lt($selectionAt)))
                (function () {
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