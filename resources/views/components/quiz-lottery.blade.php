@props(['quizCompetition', 'quizQuestion', 'status', 'candidateNames' => [], 'selectionAt' => null, 'stats' => []])

<style>
    :root {
        --lt-green: #22c55e;
        --lt-green-dark: #16a34a;
        --lt-gold: #f59e0b;
        --lt-gold-light: #fbbf24;
        --lt-gold-glow: rgba(245, 158, 11, 0.5);
        --lt-green-glow: rgba(34, 197, 94, 0.4);
    }

    #selectionOverlay {
        position: fixed;
        inset: 0;
        z-index: 9990;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        background: radial-gradient(ellipse at center, rgba(8, 8, 20, 0.95) 0%, rgba(0, 0, 0, 0.99) 100%);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.8s ease;
        overflow: hidden;
    }
    #selectionOverlay.active { opacity: 1; pointer-events: all; }

    #flashBang {
        position: fixed; inset: 0; z-index: 9995;
        background: white; opacity: 0; pointer-events: none;
    }
    #flashBang.flash { animation: ltFlash 0.6s ease-out; }

    #confettiCanvas { position: fixed; inset: 0; z-index: 9998; pointer-events: none; }

    @keyframes ltFlash { 0% { opacity: 0; } 8% { opacity: 1; } 100% { opacity: 0; } }

    @keyframes ltShake {
        0%, 100% { transform: translate(0); }
        10% { transform: translate(-10px, 6px) rotate(-1deg); }
        20% { transform: translate(10px, -6px) rotate(1deg); }
        30% { transform: translate(-6px, 4px); }
        40% { transform: translate(6px, -4px); }
        50% { transform: translate(-3px, 2px); }
    }
    .lt-shake { animation: ltShake 0.5s ease; }

    @keyframes ltPop {
        0% { transform: scale(0.3); opacity: 0; }
        60% { transform: scale(1.2); }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes ltRingPulse {
        0% { transform: scale(0.6); opacity: 0.4; }
        100% { transform: scale(2.2); opacity: 0; }
    }

    @keyframes ltShimmerGold {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }

    @keyframes ltCrownDrop {
        0% { transform: translateY(-80px) rotate(-30deg) scale(0); opacity: 0; }
        50% { transform: translateY(10px) rotate(6deg) scale(1.2); opacity: 1; }
        75% { transform: translateY(-4px) rotate(-2deg) scale(0.95); }
        100% { transform: translateY(0) rotate(0) scale(1); opacity: 1; }
    }

    @keyframes ltWinnerPop {
        0% { transform: scale(0) translateY(50px); opacity: 0; filter: blur(8px); }
        55% { transform: scale(1.05) translateY(-5px); opacity: 1; filter: blur(0); }
        100% { transform: scale(1) translateY(0); opacity: 1; }
    }

    @keyframes ltGlowBreath {
        0%, 100% { box-shadow: 0 0 20px rgba(245,158,11,0.12), 0 0 60px rgba(245,158,11,0.06); }
        50% { box-shadow: 0 0 50px rgba(245,158,11,0.3), 0 0 120px rgba(245,158,11,0.12); }
    }

    @keyframes ltParticleFly {
        0% { opacity: 0; transform: translateY(0) scale(0); }
        20% { opacity: 0.7; transform: scale(1); }
        100% { opacity: 0; transform: translateY(-120px) scale(0.4); }
    }

    @keyframes ltScanLine {
        0% { top: 0; } 100% { top: calc(100% - 2px); }
    }

    .lt-overlay-countdown {
        font-size: clamp(4rem, 16vw, 9rem);
        font-weight: 900;
        line-height: 1;
        color: var(--lt-green);
        text-shadow: 0 0 60px var(--lt-green-glow), 0 0 120px rgba(34,197,94,0.15);
        font-family: 'Tajawal', sans-serif;
    }
    .lt-count-pop { animation: ltPop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); }

    .lt-pulse-ring {
        position: absolute; width: 180px; height: 180px;
        border: 3px solid rgba(34,197,94,0.25); border-radius: 50%;
        animation: ltRingPulse 1.2s ease-out infinite;
        top: 50%; left: 50%; margin: -90px 0 0 -90px;
    }

    .lt-shimmer-gold {
        background: linear-gradient(90deg, #92400e, #f59e0b 25%, #fef3c7 50%, #f59e0b 75%, #92400e);
        background-size: 200% auto;
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: ltShimmerGold 2.5s linear infinite;
    }

    .lt-crown-anim { opacity: 0; animation: ltCrownDrop 0.9s cubic-bezier(0.34,1.56,0.64,1) forwards; }
    .lt-winner-card-anim { opacity: 0; animation: ltWinnerPop 0.9s cubic-bezier(0.34,1.56,0.64,1) forwards; }
    .lt-glow-breath { animation: ltGlowBreath 2.5s ease-in-out infinite; }

    .lt-particle-field { position: absolute; inset: 0; overflow: hidden; pointer-events: none; }
    .lt-particle-field span {
        position: absolute; width: 2px; height: 2px; border-radius: 50%;
        background: var(--lt-green); opacity: 0;
    }

    /* ===== SLOT MACHINE SCROLLER ===== */
    .lt-slot-container {
        position: relative;
        width: clamp(280px, 80vw, 520px);
        height: clamp(220px, 45vw, 340px);
        border-radius: 1.25rem;
        overflow: hidden;
        border: 2px solid rgba(34,197,94,0.35);
        background: rgba(0,0,0,0.7);
        box-shadow: 0 0 50px rgba(34,197,94,0.15), inset 0 0 40px rgba(34,197,94,0.04);
        margin: 0 auto;
    }
    .lt-slot-container::before,
    .lt-slot-container::after {
        content: ''; position: absolute; left: 0; right: 0;
        height: 35%; z-index: 3; pointer-events: none;
    }
    .lt-slot-container::before {
        top: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.95), rgba(0,0,0,0.4), transparent);
    }
    .lt-slot-container::after {
        bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.95), rgba(0,0,0,0.4), transparent);
    }

    .lt-slot-pointer {
        position: absolute; top: 50%; left: 0; right: 0;
        z-index: 4; pointer-events: none;
        transform: translateY(-50%);
    }
    .lt-slot-pointer-line {
        height: clamp(44px, 8vw, 56px);
        border-top: 2px solid var(--lt-green);
        border-bottom: 2px solid var(--lt-green);
        background: rgba(34,197,94,0.06);
        box-shadow: 0 0 30px rgba(34,197,94,0.15);
        transition: all 0.5s ease;
    }
    .lt-slot-container.winner-found .lt-slot-pointer-line {
        border-color: var(--lt-gold);
        background: rgba(245,158,11,0.08);
        box-shadow: 0 0 40px var(--lt-gold-glow);
    }

    .lt-slot-track {
        position: absolute; left: 0; right: 0;
        display: flex; flex-direction: column; align-items: center;
        will-change: transform;
        transition: none;
    }
    .lt-slot-name {
        display: flex; align-items: center; justify-content: center;
        height: clamp(44px, 8vw, 56px);
        font-size: clamp(1rem, 3.5vw, 1.5rem);
        font-weight: 700;
        color: rgba(255,255,255,0.9);
        white-space: nowrap;
        filter: blur(8px);
        opacity: 0.45;
        transition: filter 0.6s ease, opacity 0.6s ease, color 0.6s ease, text-shadow 0.6s ease;
        direction: rtl;
    }
    .lt-slot-name.lt-revealed {
        filter: blur(0) !important;
        opacity: 1 !important;
        color: var(--lt-gold-light) !important;
        font-size: clamp(1.2rem, 4.5vw, 1.8rem);
        text-shadow: 0 0 25px var(--lt-gold-glow);
    }

    .lt-slot-container.winner-found {
        border-color: var(--lt-gold);
        box-shadow: 0 0 60px var(--lt-gold-glow), 0 0 120px rgba(245,158,11,0.15);
    }

    .lt-scan-line {
        position: absolute; left: 0; right: 0; height: 2px; z-index: 5;
        background: linear-gradient(90deg, transparent, var(--lt-green), transparent);
        animation: ltScanLine 0.7s ease-in-out infinite alternate;
    }

    /* ===== ANSWER RESULT OVERLAY ===== */
    #answerResultOverlay {
        position: fixed; inset: 0; z-index: 9999;
        display: flex; align-items: center; justify-content: center;
        background: rgba(0,0,0,0.6);
        opacity: 0; pointer-events: none; transition: opacity 0.35s ease;
    }
    #answerResultOverlay.show { opacity: 1; pointer-events: all; }
    #answerResultOverlay .result-card {
        transform: scale(0.7); opacity: 0;
        transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1), opacity 0.3s ease;
    }
    #answerResultOverlay.show .result-card { transform: scale(1); opacity: 1; }
    #answerResultOverlay.result-correct .result-card {
        box-shadow: 0 0 60px rgba(34,197,94,0.5);
        animation: ltCorrectPulse 1.5s ease-in-out 2;
    }
    #answerResultOverlay.result-wrong .result-card { animation: ltWrongShake 0.6s ease; }

    @keyframes ltCorrectPulse {
        0%, 100% { box-shadow: 0 0 40px rgba(34,197,94,0.4); }
        50% { box-shadow: 0 0 80px rgba(34,197,94,0.7), 0 0 0 20px rgba(34,197,94,0.1); }
    }
    @keyframes ltWrongShake {
        0%, 100% { transform: translateX(0); }
        15% { transform: translateX(-12px); }
        30% { transform: translateX(12px); }
        45% { transform: translateX(-8px); }
        60% { transform: translateX(8px); }
    }
</style>

<div id="flashBang"></div>
<canvas id="confettiCanvas"></canvas>

{{-- ====== Selection Overlay ====== --}}
<div id="selectionOverlay">
    <div class="lt-particle-field" id="overlayParticles"></div>

    @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
        @php $firstSponsor = $quizCompetition->sponsors->first(); @endphp
        @if($firstSponsor->image)
            <div class="absolute top-3 md:top-6 left-4 right-4 flex flex-col items-center z-50 pointer-events-none">
                <img src="{{ asset('storage/' . $firstSponsor->image) }}"
                     class="max-h-[25vh] w-auto object-contain drop-shadow-2xl max-w-full"
                     style="filter: drop-shadow(0 0 30px rgba(255,255,255,0.7));">
                <p class="mt-2 text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-white text-center"
                   style="text-shadow: 0 0 15px rgba(0,0,0,0.8), 0 0 8px var(--lt-green-glow);">
                    برعاية: {{ $firstSponsor->name }}
                </p>
            </div>
        @endif
    @endif

    {{-- Phase 1: Countdown --}}
    <div id="phaseCountdown" class="text-center relative">
        <div class="lt-pulse-ring" style="animation-delay:0s;"></div>
        <div class="lt-pulse-ring" style="animation-delay:0.4s;"></div>
        <div class="lt-pulse-ring" style="animation-delay:0.8s;"></div>
        <p class="text-green-400/60 text-xs sm:text-sm mb-3 font-bold tracking-widest" style="letter-spacing:0.2em;">اختيار الفائز خلال</p>
        <div class="lt-overlay-countdown lt-count-pop" id="bigCountNum">3</div>
        <p class="text-white/25 text-xs mt-3">استعد...</p>
    </div>

    {{-- Phase 2: Slot Machine Selector --}}
    <div id="phaseSelector" class="text-center w-full px-4" style="display:none;">
        <p class="lt-shimmer-gold text-base sm:text-lg md:text-xl font-bold mb-5">جاري الفرز من بين جميع المشاركين...</p>
        <div class="lt-slot-container" id="slotBox">
            <div class="lt-scan-line" id="slotScanLine"></div>
            <div class="lt-slot-pointer"><div class="lt-slot-pointer-line"></div></div>
            <div class="lt-slot-track" id="slotTrack"></div>
        </div>
        <p class="text-white/25 text-xs mt-4" id="selectorSubtext">يتم الآن الفرز بين المشاركين...</p>
    </div>

    {{-- Phase 3: Winner Announcement --}}
    <div id="phaseAnnounce" class="text-center w-full px-4" style="display:none;">
        <div class="lt-crown-anim inline-block mb-3" style="animation-delay:0.2s;">
            <i class="fas fa-crown text-amber-400" style="font-size:clamp(2.5rem,8vw,3.5rem);filter:drop-shadow(0 0 25px var(--lt-gold-glow));"></i>
        </div>
        <p class="lt-shimmer-gold text-xl sm:text-2xl md:text-3xl font-bold mb-4" id="announceTitle">مبروك عليك!</p>

        {{-- الراعي يمين ويسار اسم الفائز --}}
        <div class="flex flex-row items-center justify-between gap-4 md:gap-8 my-6 relative z-10 w-full max-w-4xl mx-auto">
            @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
                @php
                    $sponsorRight = $quizCompetition->sponsors->first();
                    $sponsorLeft = $quizCompetition->sponsors->count() > 1 ? $quizCompetition->sponsors->last() : $quizCompetition->sponsors->first();
                @endphp
                {{-- راعي على اليمين --}}
                <div class="sponsor-reveal flex-shrink-0 opacity-0 scale-50 rounded-2xl p-2 md:p-3 bg-white/10 backdrop-blur-sm border border-white/20 shadow-2xl flex items-center justify-center relative w-16 h-16 sm:w-20 sm:h-20 md:w-28 md:h-28"
                     style="transition: all 0.8s cubic-bezier(0.34,1.56,0.64,1);">
                    <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-amber-400 text-amber-900 text-[8px] md:text-[10px] font-bold px-1.5 py-0.5 rounded shadow whitespace-nowrap">برعاية</span>
                    @if($sponsorRight->image)
                        <img src="{{ asset('storage/' . $sponsorRight->image) }}" class="max-h-full max-w-full object-contain rounded-xl" alt="">
                    @else
                        <span class="text-xs md:text-base font-bold text-white text-center leading-tight">{{ $sponsorRight->name }}</span>
                    @endif
                </div>
            @endif

            <div class="flex-1 text-center min-w-0 px-2">
                <div id="announceName" class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white break-words leading-tight"
                     style="text-shadow:0 0 35px rgba(255,255,255,0.35);"></div>
                <div id="announcePrize" class="mt-2 md:mt-3 text-sm sm:text-lg md:text-2xl font-bold text-amber-300 break-words {{ !empty($quizQuestion->prize) ? '' : 'hidden' }}"
                     style="text-shadow:0 0 15px rgba(245,158,11,0.5);">
                    @if(!empty($quizQuestion->prize) && is_array($quizQuestion->prize) && count($quizQuestion->prize) > 0)
                        مبروك عليك {{ $quizQuestion->prize[0] }}
                    @elseif(!empty($quizQuestion->prize) && is_string($quizQuestion->prize))
                        مبروك عليك {{ $quizQuestion->prize }}
                    @endif
                </div>
            </div>

            @if($quizCompetition->sponsors && $quizCompetition->sponsors->count() > 0)
                {{-- راعي على اليسار --}}
                <div class="sponsor-reveal flex-shrink-0 opacity-0 scale-50 rounded-2xl p-2 md:p-3 bg-white/10 backdrop-blur-sm border border-white/20 shadow-2xl flex items-center justify-center relative w-16 h-16 sm:w-20 sm:h-20 md:w-28 md:h-28"
                     style="transition: all 0.8s cubic-bezier(0.34,1.56,0.64,1); transition-delay: 0.2s;">
                    <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-amber-400 text-amber-900 text-[8px] md:text-[10px] font-bold px-1.5 py-0.5 rounded shadow whitespace-nowrap">برعاية</span>
                    @if($sponsorLeft->image)
                        <img src="{{ asset('storage/' . $sponsorLeft->image) }}" class="max-h-full max-w-full object-contain rounded-xl" alt="">
                    @else
                        <span class="text-xs md:text-base font-bold text-white text-center leading-tight">{{ $sponsorLeft->name }}</span>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex justify-center gap-2 mt-3" id="announceDots">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0s"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0.15s"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0.3s"></span>
        </div>
    </div>
</div>

{{-- Answer Result Modal --}}
@if(session('answer_submitted') && isset($quizQuestion))
    @php $isCorrect = session('answer_correct'); @endphp
    <div id="answerResultOverlay" class="{{ $isCorrect ? 'result-correct' : 'result-wrong' }}"
         role="dialog" aria-modal="true" onclick="if(event.target===this) closeAnswerResultModal();">
        <div class="result-card rounded-3xl p-8 md:p-10 max-w-md w-full mx-4 text-center relative overflow-hidden
             {{ $isCorrect ? 'bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-300' : 'bg-gradient-to-br from-red-50 to-red-100 border-2 border-red-300' }}"
             onclick="event.stopPropagation()">
            <div class="absolute inset-0 opacity-20 pointer-events-none"
                 style="background: radial-gradient(circle at 50% 30%, {{ $isCorrect ? 'rgba(34,197,94,0.4)' : 'rgba(239,68,68,0.3)' }}, transparent 60%);"></div>
            <div class="relative z-10">
                <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg
                     bg-gradient-to-br {{ $isCorrect ? 'from-green-400 to-green-600' : 'from-red-400 to-red-600' }}">
                    <i class="fas {{ $isCorrect ? 'fa-check-circle' : 'fa-times-circle' }} text-white text-4xl"></i>
                </div>
                <h2 class="text-xl md:text-2xl font-bold {{ $isCorrect ? 'text-green-800' : 'text-red-800' }} mb-2">
                    {{ $isCorrect ? 'أحسنت!' : 'للأسف' }}
                </h2>
                <p class="{{ $isCorrect ? 'text-green-700' : 'text-red-700' }} font-medium">
                    {{ $isCorrect ? 'إجابتك صحيحة' : 'إجابتك غير صحيحة' }}
                </p>
            </div>
            <button type="button" onclick="closeAnswerResultModal()"
                    class="mt-6 px-6 py-3 rounded-xl font-bold text-sm text-white transition-all
                    {{ $isCorrect ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }}">
                حسناً
            </button>
        </div>
    </div>
@endif

<script>
(function() {
    'use strict';

    var FALLBACK_NAMES = ['محمد أحمد','عبدالله سعد','فهد خالد','سارة علي','نورة محمد','خالد عبدالرحمن','أحمد يوسف','عمر حسن','فاطمة إبراهيم','مريم صالح'];
    var PRIZES = @json($quizQuestion->prize ?? []);
    if (typeof PRIZES === 'string' && PRIZES !== '') PRIZES = [PRIZES];

    /* ===== Confetti ===== */
    var Confetti = {
        canvas: null, ctx: null, running: false,
        colors: ['#f59e0b','#22c55e','#ef4444','#3b82f6','#a855f7','#ec4899','#fbbf24','#14b8a6'],
        init: function() {
            this.canvas = document.getElementById('confettiCanvas');
            if (!this.canvas) return;
            this.ctx = this.canvas.getContext('2d');
            this.resize();
            window.addEventListener('resize', this.resize.bind(this));
        },
        resize: function() {
            if (this.canvas) { this.canvas.width = window.innerWidth; this.canvas.height = window.innerHeight; }
        },
        launch: function(count, dur) {
            var particles = [], self = this, start = Date.now(), duration = dur || 5000;
            for (var i = 0; i < (count || 200); i++) particles.push({
                x: Math.random() * this.canvas.width,
                y: -20 - Math.random() * this.canvas.height * 0.5,
                w: 4 + Math.random() * 8, h: 4 + Math.random() * 8,
                color: this.colors[Math.floor(Math.random() * this.colors.length)],
                vx: (Math.random() - 0.5) * 8, vy: 2 + Math.random() * 6,
                rot: Math.random() * 360, rotV: (Math.random() - 0.5) * 15,
                opacity: 1, shape: Math.random() > 0.5 ? 'r' : 'c'
            });
            this.running = true;
            (function frame() {
                if (!self.running) return;
                var p = Math.min((Date.now() - start) / duration, 1);
                self.ctx.clearRect(0, 0, self.canvas.width, self.canvas.height);
                particles.forEach(function(pt) {
                    pt.x += pt.vx; pt.y += pt.vy; pt.vy += 0.1; pt.rot += pt.rotV;
                    if (p > 0.7) pt.opacity = Math.max(0, 1 - (p - 0.7) / 0.3);
                    self.ctx.save(); self.ctx.translate(pt.x, pt.y);
                    self.ctx.rotate(pt.rot * Math.PI / 180);
                    self.ctx.globalAlpha = pt.opacity; self.ctx.fillStyle = pt.color;
                    if (pt.shape === 'r') self.ctx.fillRect(-pt.w/2, -pt.h/2, pt.w, pt.h);
                    else { self.ctx.beginPath(); self.ctx.arc(0, 0, pt.w/2, 0, Math.PI*2); self.ctx.fill(); }
                    self.ctx.restore();
                });
                if (p < 1) requestAnimationFrame(frame);
                else { self.ctx.clearRect(0, 0, self.canvas.width, self.canvas.height); self.running = false; }
            })();
        }
    };

    /* ===== Slot Machine Engine ===== */
    function SlotMachine(pool) {
        var box = document.getElementById('slotBox');
        var track = document.getElementById('slotTrack');
        var scanLine = document.getElementById('slotScanLine');
        var subtext = document.getElementById('selectorSubtext');
        if (!box || !track) return;

        var names = (pool && pool.length > 0) ? pool.slice() : FALLBACK_NAMES.slice();
        var VISIBLE = 7;
        var itemH = box.offsetHeight / VISIBLE;
        var centerIdx = Math.floor(VISIBLE / 2);
        var running = false;
        var rafId = null;

        function shuffle(arr) {
            for (var i = arr.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var t = arr[i]; arr[i] = arr[j]; arr[j] = t;
            }
            return arr;
        }

        var slotItems = [];
        var extendedPool = [];
        while (extendedPool.length < VISIBLE * 4) {
            extendedPool = extendedPool.concat(shuffle(names.slice()));
        }

        function buildTrack() {
            track.innerHTML = '';
            slotItems = [];
            for (var i = 0; i < extendedPool.length; i++) {
                var el = document.createElement('div');
                el.className = 'lt-slot-name';
                el.textContent = extendedPool[i];
                el.style.height = itemH + 'px';
                track.appendChild(el);
                slotItems.push(el);
            }
        }

        function setPosition(offset) {
            track.style.transform = 'translateY(' + (-offset) + 'px)';
        }

        this.start = function() {
            itemH = box.offsetHeight / VISIBLE;
            buildTrack();
            box.classList.remove('winner-found');
            if (scanLine) scanLine.style.display = 'block';
            running = true;

            var offset = 0;
            var speed = 18;
            var maxItems = extendedPool.length;

            (function spin() {
                if (!running) return;
                offset += speed;
                var maxOffset = (maxItems - VISIBLE) * itemH;
                if (offset >= maxOffset) {
                    extendedPool = extendedPool.concat(shuffle(names.slice()));
                    var el = document.createElement('div');
                    for (var n = extendedPool.length - names.length; n < extendedPool.length; n++) {
                        el = document.createElement('div');
                        el.className = 'lt-slot-name';
                        el.textContent = extendedPool[n];
                        el.style.height = itemH + 'px';
                        track.appendChild(el);
                        slotItems.push(el);
                    }
                    maxItems = extendedPool.length;
                }
                setPosition(offset);
                rafId = requestAnimationFrame(spin);
            })();
        };

        this.reveal = function(winnerName, callback) {
            running = false;
            if (rafId) cancelAnimationFrame(rafId);

            var idx = slotItems.length - 1;
            for (var i = centerIdx; i < slotItems.length - VISIBLE; i++) {
                if (extendedPool[i] === winnerName || names.indexOf(winnerName) >= 0) {
                    idx = i; break;
                }
            }

            extendedPool[idx] = winnerName;
            slotItems[idx].textContent = winnerName;

            var currentOffset = parseFloat(track.style.transform.replace(/[^0-9.\-]/g, '')) || 0;
            var targetOffset = (idx - centerIdx) * itemH;
            if (targetOffset < currentOffset) targetOffset = currentOffset + (names.length * itemH);

            var totalSteps = extendedPool.length;
            while (targetOffset < currentOffset) {
                extendedPool = extendedPool.concat(shuffle(names.slice()));
                for (var n = extendedPool.length - names.length; n < extendedPool.length; n++) {
                    var el = document.createElement('div');
                    el.className = 'lt-slot-name';
                    el.textContent = extendedPool[n];
                    el.style.height = itemH + 'px';
                    track.appendChild(el);
                    slotItems.push(el);
                }
                totalSteps = extendedPool.length;
                idx = extendedPool.length - Math.floor(names.length / 2);
                extendedPool[idx] = winnerName;
                slotItems[idx].textContent = winnerName;
                targetOffset = (idx - centerIdx) * itemH;
            }

            var extraSpins = names.length * 3;
            targetOffset += extraSpins * itemH;
            while (slotItems.length < (targetOffset / itemH) + VISIBLE + 5) {
                var batch = shuffle(names.slice());
                extendedPool = extendedPool.concat(batch);
                batch.forEach(function(n) {
                    var el = document.createElement('div');
                    el.className = 'lt-slot-name';
                    el.textContent = n;
                    el.style.height = itemH + 'px';
                    track.appendChild(el);
                    slotItems.push(el);
                });
            }
            var finalIdx = Math.round(targetOffset / itemH) + centerIdx;
            if (finalIdx < extendedPool.length) {
                extendedPool[finalIdx] = winnerName;
                slotItems[finalIdx].textContent = winnerName;
            }
            targetOffset = (finalIdx - centerIdx) * itemH;

            var duration = 7000;
            var startTime = Date.now();
            var startOffset = currentOffset;
            var distance = targetOffset - startOffset;

            function easeOutExpo(t) {
                return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
            }

            (function decel() {
                var elapsed = Date.now() - startTime;
                var progress = Math.min(elapsed / duration, 1);
                var easedProgress = easeOutExpo(progress);
                var pos = startOffset + distance * easedProgress;
                setPosition(pos);

                if (subtext) {
                    if (progress < 0.3) subtext.textContent = 'جاري البحث بين المشاركين...';
                    else if (progress < 0.6) subtext.textContent = 'تضييق نطاق البحث...';
                    else if (progress < 0.85) subtext.textContent = 'اقتربنا من النتيجة...';
                    else subtext.textContent = 'تم تحديد الفائز!';
                }

                if (progress < 1) {
                    requestAnimationFrame(decel);
                } else {
                    setPosition(targetOffset);
                    if (scanLine) scanLine.style.display = 'none';
                    box.classList.add('winner-found');
                    slotItems[finalIdx].classList.add('lt-revealed');
                    setTimeout(function() { if (callback) callback(winnerName); }, 700);
                }
            })();
        };

        this.stop = function() { running = false; if (rafId) cancelAnimationFrame(rafId); };
    }

    /* ===== Particles ===== */
    function initParticles() {
        var c = document.getElementById('overlayParticles');
        if (!c || c.children.length > 0) return;
        for (var i = 0; i < 35; i++) {
            var p = document.createElement('span');
            p.style.left = Math.random() * 100 + '%';
            p.style.bottom = '-10px';
            p.style.animation = 'ltParticleFly ' + (2 + Math.random() * 3) + 's ease-out ' + (Math.random() * 4) + 's infinite';
            c.appendChild(p);
        }
    }

    /* ===== DOM Helpers ===== */
    function $(id) { return document.getElementById(id); }
    function flash() {
        var f = $('flashBang');
        if (f) { f.classList.add('flash'); setTimeout(function() { f.classList.remove('flash'); }, 500); }
    }
    function shake() {
        document.body.classList.add('lt-shake');
        setTimeout(function() { document.body.classList.remove('lt-shake'); }, 500);
    }
    function showSponsors() {
        document.querySelectorAll('.sponsor-reveal').forEach(function(el) {
            el.style.opacity = '1'; el.style.transform = 'scale(1)';
        });
    }
    function hideSponsors() {
        document.querySelectorAll('.sponsor-reveal').forEach(function(el) {
            el.style.opacity = '0'; el.style.transform = 'scale(0.5)';
        });
    }
    function navigateDone() {
        var url = new URL(window.location.href);
        url.searchParams.set('animation_done', '1');
        window.location.href = url.toString();
    }

    /* ===== Orchestrator ===== */
    function runCountdown(cb) {
        var overlay = $('selectionOverlay');
        var countNum = $('bigCountNum');
        if (!overlay) { location.reload(); return; }
        initParticles();
        overlay.classList.add('active');

        var val = 2;
        countNum.textContent = val;
        countNum.classList.add('lt-count-pop');

        var timer = setInterval(function() {
            val--;
            if (val <= 0) {
                clearInterval(timer);
                flash(); shake();
                cb();
                return;
            }
            countNum.textContent = val;
            countNum.classList.remove('lt-count-pop');
            void countNum.offsetWidth;
            countNum.classList.add('lt-count-pop');
        }, 900);
    }

    function revealSequential(winners, pool) {
        var phaseCount = $('phaseCountdown');
        var phaseSel = $('phaseSelector');
        var phaseAnn = $('phaseAnnounce');
        if (phaseCount) phaseCount.style.display = 'none';

        if (!winners || winners.length === 0) { navigateDone(); return; }

        var idx = winners.length - 1;

        function next() {
            if (idx < 0) { setTimeout(navigateDone, 4000); return; }

            if (phaseAnn) phaseAnn.style.display = 'none';
            if (phaseSel) phaseSel.style.display = 'block';

            var slotBox = $('slotBox');
            if (slotBox) slotBox.classList.remove('winner-found');
            var scanLine = $('slotScanLine');
            if (scanLine) scanLine.style.display = 'block';

            var target = winners[idx];
            var slot = new SlotMachine(pool);
            slot.start();

            setTimeout(function() {
                slot.reveal(target, function(name) {
                    flash();
                    Confetti.launch(300, 5000);

                    setTimeout(function() {
                        if (phaseSel) phaseSel.style.display = 'none';
                        if (phaseAnn) phaseAnn.style.display = 'block';
                        hideSponsors();

                        $('announceName').textContent = name;

                        var posText = '';
                        if (idx === 0) posText = 'المركز الأول';
                        else if (idx === 1) posText = 'المركز الثاني';
                        else if (idx === 2) posText = 'المركز الثالث';
                        else posText = 'المركز ' + (idx + 1);
                        $('announceTitle').textContent = 'مبروك عليك الفوز بـ ' + posText + '!';

                        var prizeEl = $('announcePrize');
                        if (prizeEl) {
                            var prizeText = '';
                            if (Array.isArray(PRIZES) && PRIZES.length > idx) prizeText = PRIZES[idx];
                            else if (Array.isArray(PRIZES) && PRIZES.length > 0) prizeText = PRIZES[0];
                            if (prizeText) { prizeEl.textContent = 'مبروك عليك ' + prizeText; prizeEl.classList.remove('hidden'); }
                            else prizeEl.classList.add('hidden');
                        }

                        Confetti.launch(120, 2000);
                        setTimeout(showSponsors, 300);

                        idx--;
                        setTimeout(next, idx < 0 ? 5000 : 4000);
                    }, 700);
                });
            }, 2500);
        }
        next();
    }

    function startWinnerReveal(winners, pool) {
        runCountdown(function() { revealSequential(winners, pool); });
    }

    function startZeroDelayAnimation(pool) {
        var overlay = $('selectionOverlay');
        var phaseCount = $('phaseCountdown');
        var phaseSel = $('phaseSelector');
        if (!overlay) return null;
        initParticles();
        overlay.classList.add('active');
        if (phaseCount) phaseCount.style.display = 'none';
        if (phaseSel) phaseSel.style.display = 'block';
        flash();
        var slot = new SlotMachine(pool);
        slot.start();
        return slot;
    }

    function finalizeZeroDelay(slot, winners, pool) {
        if (slot) slot.stop();
        var phaseSel = $('phaseSelector');
        if (phaseSel) phaseSel.style.display = 'none';
        revealSequential(winners, pool);
    }

    function fetchWinner(url, cb) {
        (function poll() {
            fetch(url).then(function(r) { return r.json(); }).then(function(data) {
                if (data.status === 'done' && data.winners && data.winners.length > 0) {
                    cb(data.winners.sort(function(a,b) { return a.position - b.position; }).map(function(w) { return w.name; }));
                } else if (data.status === 'pending') {
                    setTimeout(poll, 2000);
                } else { navigateDone(); }
            }).catch(function() { setTimeout(poll, 3000); });
        })();
    }

    window.closeAnswerResultModal = function() {
        var el = $('answerResultOverlay');
        if (el) el.classList.remove('show');
    };

    /* ===== Init ===== */
    document.addEventListener('DOMContentLoaded', function() {
        Confetti.init();
        function pad(n) { return n.toString().padStart(2, '0'); }

        @if(session('answer_submitted'))
            setTimeout(function() {
                var overlay = $('answerResultOverlay');
                if (overlay) {
                    overlay.classList.add('show');
                    if (overlay.classList.contains('result-correct')) Confetti.launch(120, 3500);
                }
            }, 300);
        @endif

        @if($status === 'not_started' && $quizCompetition->start_at)
            (function() {
                var target = {{ $quizCompetition->start_at->getTimestamp() * 1000 }};
                function update() {
                    var d = target - Date.now();
                    if (d <= 0) { location.reload(); return; }
                    var el;
                    if (el = $('ns-days')) el.textContent = Math.floor(d / 86400000);
                    if (el = $('ns-hours')) el.textContent = Math.floor((d % 86400000) / 3600000);
                    if (el = $('ns-minutes')) el.textContent = Math.floor((d % 3600000) / 60000);
                    if (el = $('ns-seconds')) el.textContent = Math.floor((d % 60000) / 1000);
                } update(); setInterval(update, 1000);
            })();
        @endif

        @if($status === 'question_locked' && isset($questionsVisibleAt) && $questionsVisibleAt)
            (function() {
                var target = {{ $questionsVisibleAt->getTimestamp() * 1000 }};
                function update() {
                    var d = target - Date.now();
                    if (d <= 0) { location.reload(); return; }
                    var el = $('ql-seconds');
                    if (el) el.textContent = Math.ceil(d / 1000);
                } update(); setInterval(update, 1000);
            })();
        @endif

        @if($status === 'active' && $quizCompetition->end_at)
            (function() {
                var end = {{ $quizCompetition->end_at->getTimestamp() * 1000 }};
                var done = false;
                function update() {
                    var d = end - Date.now();
                    if (d <= 0 && !done) {
                        done = true;
                        var h = $('at-hours'), m = $('at-minutes'), s = $('at-seconds');
                        if (h) h.textContent = '00'; if (m) m.textContent = '00'; if (s) s.textContent = '00';
                        var slot = startZeroDelayAnimation(@json($candidateNames ?? []));
                        setTimeout(function() {
                            fetchWinner("{{ route('quiz-competitions.question.winner', [$quizCompetition, $quizQuestion]) }}", function(list) {
                                finalizeZeroDelay(slot, list, @json($candidateNames ?? []));
                            });
                        }, Math.random() * 3000);
                        return;
                    }
                    if (done) return;
                    var h = $('at-hours'), m = $('at-minutes'), s = $('at-seconds');
                    if (h) h.textContent = pad(Math.floor(d / 3600000));
                    if (m) m.textContent = pad(Math.floor((d % 3600000) / 60000));
                    if (s) s.textContent = pad(Math.floor((d % 60000) / 1000));
                    setTimeout(update, 1000);
                } update();
            })();
        @endif

        @if($status === 'ended' && $selectionAt)
            (function() {
                var selAt = {{ $selectionAt->getTimestamp() * 1000 }};
                var now = Date.now();
                var params = new URLSearchParams(window.location.search);
                var hasWinners = {{ $quizQuestion->winners->count() > 0 ? 'true' : 'false' }};
                if ((hasWinners && now > selAt + 20000) || params.has('animation_done')) {
                    Confetti.launch(200, 5000);
                    return;
                }
                setTimeout(function() {
                    fetchWinner("{{ route('quiz-competitions.question.winner', [$quizCompetition, $quizQuestion]) }}", function(list) {
                        startWinnerReveal(list, @json($candidateNames ?? []));
                    });
                }, Math.max(0, selAt + (Math.random() * 3000) - now));
            })();
        @endif

        @if($status === 'ended' && $quizQuestion->winners->count() > 0 && !($selectionAt && now()->lt($selectionAt)))
            Confetti.launch(200, 5000);
        @endif
    });
})();
</script>
