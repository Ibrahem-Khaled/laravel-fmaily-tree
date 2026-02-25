@extends('layouts.web-site.web')

@section('title', 'الرئيسية - تواصل عائلة السريع')

@push('styles')
    <style>
        /* ============================================================
       SWIPER – Hero
       ============================================================ */
        .heroSwiper {
            width: 100%;
            height: 100%;
        }

        .heroSwiper .swiper-slide {
            opacity: 0 !important;
            transition: opacity 0.8s ease-in-out;
        }

        .heroSwiper .swiper-slide-active {
            opacity: 1 !important;
        }

        .heroSwiper .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .heroSwiper .swiper-button-next,
        .heroSwiper .swiper-button-prev {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .heroSwiper .swiper-button-next:hover,
        .heroSwiper .swiper-button-prev:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: scale(1.15);
        }

        .heroSwiper .swiper-button-next::after,
        .heroSwiper .swiper-button-prev::after {
            font-size: 20px;
            font-weight: bold;
        }

        .heroSwiper .swiper-button-next {
            left: 20px;
            right: auto;
        }

        .heroSwiper .swiper-button-prev {
            right: 20px;
            left: auto;
        }

        .heroSwiper .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.6);
            opacity: 1;
            width: 10px;
            height: 10px;
            transition: all 0.3s ease;
        }

        .heroSwiper .swiper-pagination-bullet-active {
            background: white;
            width: 30px;
            border-radius: 5px;
        }

        /* ============================================================
       SWIPER – Gallery & Courses (shared)
       ============================================================ */
        .gallerySwiper,
        .coursesSwiper {
            padding: 10px 35px 30px !important;
        }

        .gallerySwiper .swiper-button-next,
        .gallerySwiper .swiper-button-prev,
        .coursesSwiper .swiper-button-next,
        .coursesSwiper .swiper-button-prev {
            color: #37a05c;
            background: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .gallerySwiper .swiper-button-next:hover,
        .gallerySwiper .swiper-button-prev:hover,
        .coursesSwiper .swiper-button-next:hover,
        .coursesSwiper .swiper-button-prev:hover {
            background: #37a05c;
            color: white;
            box-shadow: 0 6px 20px rgba(55, 160, 92, 0.4);
            transform: scale(1.1);
        }

        .gallerySwiper .swiper-button-next::after,
        .gallerySwiper .swiper-button-prev::after,
        .coursesSwiper .swiper-button-next::after,
        .coursesSwiper .swiper-button-prev::after {
            font-size: 20px;
            font-weight: bold;
        }

        .gallerySwiper .swiper-button-next,
        .coursesSwiper .swiper-button-next {
            left: 0;
            right: auto;
        }

        .gallerySwiper .swiper-button-prev,
        .coursesSwiper .swiper-button-prev {
            right: 0;
            left: auto;
        }

        .gallerySwiper .swiper-pagination-bullet,
        .coursesSwiper .swiper-pagination-bullet {
            background: #d1d5db;
            opacity: 1;
            width: 10px;
            height: 10px;
            transition: all 0.3s ease;
        }

        .gallerySwiper .swiper-pagination-bullet-active,
        .coursesSwiper .swiper-pagination-bullet-active {
            background: #37a05c;
            width: 30px;
            border-radius: 5px;
        }

        /* ============================================================
       Gallery Modal Animations
       ============================================================ */
        #galleryModal {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        #galleryModalImageContainer,
        #galleryModalVideoContainer {
            animation: zoomIn 0.3s ease-out;
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        #galleryModalInfo {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================================
       Quiz – Rich-text content (description & question)
       ============================================================ */
        .quiz-description,
        .question-text {
            direction: rtl;
            text-align: right;
        }

        .quiz-description p,
        .question-text p {
            margin-bottom: 0.75rem;
        }

        .quiz-description p:last-child,
        .question-text p:last-child {
            margin-bottom: 0;
        }

        .quiz-description strong,
        .quiz-description b {
            font-weight: 700;
            color: #16a34a;
        }

        .question-text strong,
        .question-text b {
            font-weight: 700;
        }

        .quiz-description em,
        .quiz-description i,
        .question-text em,
        .question-text i {
            font-style: italic;
        }

        .quiz-description ul,
        .quiz-description ol,
        .question-text ul,
        .question-text ol {
            margin-right: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .quiz-description li,
        .question-text li {
            margin-bottom: 0.5rem;
        }

        .quiz-description a,
        .question-text a {
            color: #22c55e;
            text-decoration: underline;
            transition: color 0.2s;
        }

        .quiz-description a:hover,
        .question-text a:hover {
            color: #16a34a;
        }

        .quiz-description img,
        .question-text img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 0.5rem 0;
        }

        .quiz-description table {
            width: 100%;
            margin-bottom: 0.75rem;
            border-collapse: collapse;
        }

        .quiz-description table td,
        .quiz-description table th {
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        /* ============================================================
       Responsive overrides
       ============================================================ */
        @media (max-width: 768px) {

            .heroSwiper .swiper-button-next,
            .heroSwiper .swiper-button-prev {
                width: 35px;
                height: 35px;
            }

            .heroSwiper .swiper-button-next::after,
            .heroSwiper .swiper-button-prev::after {
                font-size: 16px;
            }

            .heroSwiper .swiper-button-next {
                left: 10px;
            }

            .heroSwiper .swiper-button-prev {
                right: 10px;
            }

            .person-name-overlay {
                opacity: 1 !important;
            }

            .gallerySwiper,
            .coursesSwiper {
                padding: 8px 30px 25px !important;
            }

            .gallerySwiper .swiper-button-next,
            .gallerySwiper .swiper-button-prev,
            .coursesSwiper .swiper-button-next,
            .coursesSwiper .swiper-button-prev {
                width: 35px;
                height: 35px;
            }

            .gallerySwiper .swiper-button-next::after,
            .gallerySwiper .swiper-button-prev::after,
            .coursesSwiper .swiper-button-next::after,
            .coursesSwiper .swiper-button-prev::after {
                font-size: 16px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ================================================================
     HERO – Slideshow
     ================================================================ --}}
    <section class="relative h-[180px] sm:h-[220px] md:h-[280px] lg:h-[320px] overflow-hidden">

        @if ($latestImages->count() > 0)
            <div class="swiper heroSwiper">
                <div class="swiper-wrapper">
                    @foreach ($latestImages->take(10) as $slide)
                        <div class="swiper-slide">
                            @if ($slide->link)
                                <a href="{{ $slide->link }}" target="_blank" class="block relative w-full h-full">
                                @else
                                    <div class="relative w-full h-full">
                            @endif

                            @if ($slide->image_url)
                                <img src="{{ $slide->image_url }}" alt="{{ $slide->title ?? 'صورة' }}"
                                    class="w-full h-full object-cover">
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                            @if ($slide->title || $slide->description)
                                <div class="absolute bottom-4 md:bottom-6 right-4 left-4 md:right-6 md:left-6 z-10">
                                    <div class="glass-effect rounded-xl p-3 md:p-4 max-w-2xl animate-fade-in-up">
                                        @if ($slide->title)
                                            <h2
                                                class="text-white text-base md:text-lg lg:text-xl font-bold mb-1 md:mb-2 drop-shadow-lg">
                                                {{ $slide->title }}
                                            </h2>
                                        @endif
                                        @if ($slide->description)
                                            <p class="text-white/95 text-xs md:text-sm line-clamp-2 mb-2 drop-shadow">
                                                {{ $slide->description }}
                                            </p>
                                        @endif
                                        @if ($slide->link)
                                            <span
                                                class="inline-flex items-center gap-1.5 text-white text-xs md:text-sm bg-white/20 px-3 py-1.5 rounded-lg hover:bg-white/30 transition-all">
                                                <span>اكتشف المزيد</span>
                                                <i class="fas fa-arrow-left text-xs"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if ($slide->link)
                                </a>
                            @else
                        </div>
                    @endif
                </div>
        @endforeach
        </div>
        <div class="swiper-button-next hero-next"></div>
        <div class="swiper-button-prev hero-prev"></div>
        <div class="swiper-pagination hero-pagination"></div>
        </div>
    @else
        <div class="absolute inset-0 gradient-primary flex items-center justify-center">
            <div class="text-center text-white px-4">
                <i class="fas fa-images text-4xl md:text-5xl mb-4 opacity-40 animate-float"></i>
                <p class="text-base md:text-lg font-semibold">لا توجد صور في السلايدشو حالياً</p>
            </div>
        </div>
        @endif

    </section>

    {{-- ================================================================
     FAMILY BRIEF
     ================================================================ --}}
    @if ($familyBrief)
        <section class="py-3 md:py-6 lg:py-8 bg-white relative overflow-hidden">
            <div class="absolute top-0 left-0 w-48 h-48 bg-green-100 rounded-full blur-3xl opacity-20"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
                <div class="text-right mb-3 md:mb-5">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">نبذة عن العائلة
                    </h2>
                </div>
                <div class="glass-card rounded-2xl p-3 md:p-4 lg:p-6 shadow-lg">
                    <div class="text-gray-700 text-sm md:text-base leading-relaxed whitespace-pre-line">
                        {!! nl2br(e($familyBrief)) !!}
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ================================================================
     RAMADAN QUIZ – Active competition OR next event countdown
     ================================================================ --}}
    @if ((isset($activeQuizCompetition) && $activeQuizCompetition) || (isset($nextQuizEvent) && $nextQuizEvent))
        <section class="py-3 md:py-6 lg:py-8 relative overflow-hidden"
            style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);">

            {{-- Decorative blobs --}}
            <div class="absolute top-0 right-0 w-72 h-72 opacity-5 pointer-events-none"
                style="animation: float 6s ease-in-out infinite;">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#22c55e"
                        d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3Z"
                        transform="translate(100 100)" />
                </svg>
            </div>
            <div class="absolute bottom-0 left-0 w-64 h-64 opacity-5 pointer-events-none"
                style="animation: float 5s ease-in-out infinite 1s;">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#4ade80"
                        d="M37.5,-65.2C48.7,-57.8,57.8,-47.3,64.3,-35.1C70.8,-22.9,74.7,-9,75.6,5.7C76.5,20.4,74.4,36,67.1,48.6C59.8,61.2,47.3,70.8,33.2,75.7C19.1,80.6,3.4,80.8,-12.1,78.1C-27.6,75.4,-42.9,69.8,-55.3,60.2C-67.7,50.6,-77.2,37,-80.3,21.9C-83.4,6.8,-80.1,-9.8,-74.1,-25.3C-68.1,-40.8,-59.4,-55.2,-47.2,-62.2C-35,-69.2,-19.3,-68.8,-5.4,-60.5Z"
                        transform="translate(100 100)" />
                </svg>
            </div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

                <div class="text-right mb-3 md:mb-5">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">
                        المسابقة الرمضانية
                    </h2>
                </div>

                {{-- ── ACTIVE COMPETITION ── --}}
                @if (isset($activeQuizCompetition) && $activeQuizCompetition)
                    <div class="mb-3 md:mb-5" id="activeQuizSection">
                        <div class="glass-card rounded-3xl p-3 md:p-6 shadow-lg relative overflow-hidden"
                            style="box-shadow: 0 0 40px rgba(34,197,94,0.2);">

                            {{-- Top accent bar --}}
                            <div class="absolute top-0 right-0 left-0 h-1.5"
                                style="background: linear-gradient(90deg, #22c55e, #16a34a, #22c55e);"></div>

                            {{-- Status + question count --}}
                            <div class="flex items-center justify-between mb-2">
                                <span
                                    class="inline-flex items-center gap-2 bg-red-50 text-red-600 text-xs font-bold px-3 py-1.5 rounded-full border border-red-200">
                                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                    مسابقة جارية الآن
                                </span>
                                <span class="text-gray-500 text-xs">
                                    <i class="fas fa-question-circle text-green-500 ml-1"></i>
                                    {{ $activeQuizCompetition->questions->count() }} سؤال
                                </span>
                            </div>

                            {{-- Countdown to competition END --}}
                            <div class="flex flex-wrap items-center gap-2 mb-3 text-gray-500 text-sm">
                                <i class="fas fa-hourglass-half text-amber-500"></i>
                                <span>ينتهي بعد:</span>
                                <div class="flex gap-1 flex-row" id="activeQuestionTimer">
                                    <span
                                        class="bg-gray-100 rounded-lg px-2 py-1 text-gray-800 font-bold text-sm min-w-[2rem] text-center"
                                        id="aq-seconds">00</span>
                                    <span class="text-gray-400 font-bold">:</span>
                                    <span
                                        class="bg-gray-100 rounded-lg px-2 py-1 text-gray-800 font-bold text-sm min-w-[2rem] text-center"
                                        id="aq-minutes">00</span>
                                    <span class="text-gray-400 font-bold">:</span>
                                    <span
                                        class="bg-gray-100 rounded-lg px-2 py-1 text-gray-800 font-bold text-sm min-w-[2rem] text-center"
                                        id="aq-hours">00</span>
                                </div>
                                {{-- End timestamp passed to JS --}}
                                <input type="hidden" id="aqEndTime"
                                    value="{{ $activeQuizCompetition->end_at->getTimestamp() * 1000 }}">
                            </div>

                            {{-- Session / validation errors --}}
                            @if (session('error'))
                                <div class="rounded-2xl p-3 mb-3 flex items-center gap-3 bg-red-50 border border-red-200">
                                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="rounded-2xl p-3 mb-3 bg-red-50 border border-red-200">
                                    <ul class="space-y-1 text-red-600 text-sm">
                                        @foreach ($errors->all() as $err)
                                            <li><i class="fas fa-circle text-[6px] ml-1"></i>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- ── PRE-REVEAL: descriptions + countdown to show questions ── --}}
                            @if (!($showQuestionsOnHome ?? true) && isset($questionsVisibleAt) && $questionsVisibleAt)
                                {{-- Timestamps for JS --}}
                                <input type="hidden" id="aqQuestionsVisibleAt"
                                    value="{{ $questionsVisibleAt->getTimestamp() * 1000 }}">

                                {{-- Question descriptions show immediately --}}
                                @if ($activeQuizCompetition->questions->filter(fn($q) => !empty($q->description))->isNotEmpty())
                                    <div id="activeQuizDescriptionsOnlyBlock" class="space-y-4 mb-3">
                                        @foreach ($activeQuizCompetition->questions as $q)
                                            @if ($q->description)
                                                <div
                                                    class="rounded-2xl p-3 border-2 border-green-100 bg-white/80 shadow-sm">
                                                    <div class="text-gray-600 text-sm quiz-description">
                                                        {!! $q->description !!}</div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Countdown banner until question text & answers are revealed --}}
                                <div id="activeQuizQuestionsCountdown"
                                    class="rounded-2xl p-4 mb-3 bg-amber-50 border-2 border-amber-200 flex flex-wrap items-center justify-center gap-2">
                                    <i class="fas fa-clock text-amber-600"></i>
                                    <span class="text-amber-800 font-medium text-sm">نص السؤال والإجابة تظهران بعد:</span>
                                    <span id="aqQuestionsSeconds"
                                        class="bg-amber-200 text-amber-900 font-bold text-lg min-w-[3rem] text-center rounded-lg px-2 py-1">0</span>
                                    <span class="text-amber-700 text-sm">ثانية</span>
                                </div>
                            @endif

                            {{-- ── QUESTIONS BLOCK ── --}}
                            <div id="activeQuizQuestionsBlock" class="space-y-4 mb-3"
                                @if (!($showQuestionsOnHome ?? true)) style="display:none" @endif>

                                <h4 class="text-sm font-bold text-gray-600 mb-2">أسئلة المسابقة — أجب هنا:</h4>

                                @foreach ($activeQuizCompetition->questions as $q)
                                    @php
                                        $cooldownHours = 2;
                                        $lastAnsweredAt = session('quiz_answered_' . $q->id);
                                        $canAnswerThis =
                                            !$lastAnsweredAt ||
                                            now()->diffInHours(\Carbon\Carbon::parse($lastAnsweredAt)) >=
                                                $cooldownHours;
                                    @endphp

                                    <div class="rounded-2xl p-3 border-2 border-green-100 bg-white/80 shadow-sm">

                                        @if ($q->description)
                                            <div class="text-gray-600 text-sm mb-2 quiz-description">{!! $q->description !!}
                                            </div>
                                        @endif

                                        <div class="text-gray-800 font-bold text-base mb-2 question-text">
                                            {!! $q->question_text !!}</div>

                                        @if ($canAnswerThis)
                                            <form
                                                action="{{ route('quiz-competitions.store-answer', [$activeQuizCompetition, $q]) }}"
                                                method="POST" class="space-y-4">
                                                @csrf

                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-gray-600 text-xs mb-1 font-medium">
                                                            الاسم <span class="text-red-500">*</span>
                                                        </label>
                                                        <input type="text" name="name" value="{{ old('name') }}"
                                                            required placeholder="الاسم الكامل"
                                                            class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-600 text-xs mb-1 font-medium">
                                                            رقم الهاتف <span class="text-red-500">*</span>
                                                        </label>
                                                        <input type="text" name="phone" value="{{ old('phone') }}"
                                                            required pattern="[0-9]{10}" minlength="10" maxlength="10"
                                                            placeholder="05xxxxxxxx" dir="ltr"
                                                            style="text-align:right;"
                                                            title="يجب أن يكون رقم الهاتف 10 أرقام"
                                                            class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="block text-gray-600 text-xs mb-1 font-medium">
                                                        اسم الأم (للمستخدمين من الأنساب)
                                                    </label>
                                                    <input type="text" name="mother_name"
                                                        value="{{ old('mother_name') }}" placeholder="ينتهي باسم السريع"
                                                        class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500">
                                                    <input type="hidden" name="is_from_ancestry" value="1">
                                                </div>

                                                <div>
                                                    <label class="block text-gray-600 text-xs mb-2 font-medium">
                                                        الإجابة <span class="text-red-500">*</span>
                                                    </label>

                                                    @if ($q->answer_type === 'multiple_choice' && $q->choices->count() > 0)
                                                        <div class="space-y-2">
                                                            @foreach ($q->choices as $choice)
                                                                <label
                                                                    class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 bg-white hover:border-green-300 hover:bg-green-50/50 cursor-pointer transition-all has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                                                    <input type="radio" name="answer"
                                                                        value="{{ $choice->id }}"
                                                                        class="w-4 h-4 text-green-600" required>
                                                                    <span
                                                                        class="text-gray-800 text-sm font-medium">{{ $choice->choice_text }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <textarea name="answer" rows="3" required placeholder="اكتب إجابتك..."
                                                            class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500 resize-none">{{ old('answer') }}</textarea>
                                                    @endif
                                                </div>

                                                <button type="submit"
                                                    class="w-full sm:w-auto px-6 py-3 rounded-xl text-white font-bold text-sm inline-flex items-center justify-center gap-2 transition-all hover:opacity-90"
                                                    style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                                                    <i class="fas fa-paper-plane"></i>
                                                    إرسال الإجابة
                                                </button>
                                            </form>
                                        @else
                                            <div
                                                class="rounded-xl p-4 bg-amber-50 border border-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                                <p class="text-amber-800 text-sm font-medium">
                                                    <i class="fas fa-check-circle text-amber-600 ml-1"></i>
                                                    لقد أجبت على هذا السؤال.
                                                </p>
                                                <a href="{{ route('quiz-competitions.question', [$activeQuizCompetition, $q]) }}"
                                                    class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white transition-all hover:opacity-90"
                                                    style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                                                    <i class="fas fa-trophy"></i>
                                                    متابعة القرعة
                                                </a>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach

                            </div>{{-- /activeQuizQuestionsBlock --}}

                        </div>
                    </div>
                @endif {{-- /activeQuizCompetition --}}

                {{-- ── NEXT EVENT COUNTDOWN (only when no active competition) ── --}}
                @if (isset($nextQuizEvent) && $nextQuizEvent && !(isset($activeQuizCompetition) && $activeQuizCompetition))
                    <div class="mb-3 md:mb-5" id="quizCountdownSection">
                        <div class="glass-card rounded-3xl p-3 md:p-6 text-center shadow-lg"
                            style="box-shadow: 0 0 30px rgba(34,197,94,0.15);">

                            <div
                                class="inline-flex items-center gap-2 bg-amber-50 text-amber-700 rounded-full px-4 py-1.5 mb-4 border border-amber-200">
                                <i class="fas fa-clock text-amber-500 text-sm"></i>
                                <span class="text-xs font-medium">المسابقة تبدأ قريباً</span>
                            </div>

                            <p class="text-gray-800 font-bold text-base md:text-lg mb-2">{{ $nextQuizEvent['title'] }}</p>

                            @if (!empty($nextQuizEvent['description']))
                                <div class="text-gray-600 text-sm mb-3 quiz-description text-right">
                                    {!! $nextQuizEvent['description'] !!}
                                </div>
                            @endif

                            {{-- Days / Hours / Minutes / Seconds – RTL order (يوم ← ساعة ← دقيقة ← ثانية) --}}
                            <div class="flex justify-center flex-row-reverse gap-2 md:gap-4 mb-3" id="quizCountdown">
                                @foreach ([['days', 'يوم'], ['hours', 'ساعة'], ['minutes', 'دقيقة'], ['seconds', 'ثانية']] as [$unit, $label])
                                    <div class="text-center">
                                        <div
                                            class="w-14 h-14 md:w-18 md:h-18 rounded-2xl flex items-center justify-center mb-1 bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200">
                                            <span class="text-xl md:text-2xl font-bold text-green-600"
                                                id="countdown-{{ $unit }}">0</span>
                                        </div>
                                        <p class="text-gray-500 text-[10px] md:text-xs">{{ $label }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <input type="hidden" id="quizCountdownTarget"
                                value="{{ $nextQuizEvent['target_at']->getTimestamp() * 1000 }}">
                        </div>
                    </div>
                @endif

            </div>
        </section>
    @endif {{-- /quiz section --}}

    {{-- ================================================================
     DYNAMIC SECTIONS – Universal renderer
     ================================================================ --}}
    @if (isset($dynamicSections) && $dynamicSections->count() > 0)
        @foreach ($dynamicSections as $section)
            @php
                $ss = $section->settings ?? [];
                $bgColor = $ss['background_color'] ?? null;
                $txtColor = $ss['text_color'] ?? null;
                $padTop = $ss['padding_top'] ?? null;
                $padBottom = $ss['padding_bottom'] ?? null;
                $showTitle = $ss['show_title'] ?? true;
                $subtitle = $ss['subtitle'] ?? null;
                $description = $ss['description'] ?? null;
                $icon = $ss['icon'] ?? null;
                $columns = $ss['columns'] ?? 3;

                $sectionStyle = '';
                if ($bgColor && $bgColor !== '#ffffff') {
                    $sectionStyle .= "background-color:{$bgColor};";
                }
                if ($txtColor && $txtColor !== '#333333') {
                    $sectionStyle .= "color:{$txtColor};";
                }
                if ($padTop !== null) {
                    $sectionStyle .= "padding-top:{$padTop}px;";
                }
                if ($padBottom !== null) {
                    $sectionStyle .= "padding-bottom:{$padBottom}px;";
                }

                $colsMap = [
                    1 => 'grid-cols-1',
                    2 => 'grid-cols-1 sm:grid-cols-2',
                    3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
                    4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
                    6 => 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-6',
                ];
                $colsClass = $colsMap[$columns] ?? $colsMap[3];

                $isGrid = in_array($section->section_type, ['gallery', 'cards', 'stats']);
                $isTwoCol = in_array($section->section_type, ['text_with_image']);
            @endphp

            @if ($section->items->count() > 0)
                <section class="py-3 md:py-6 lg:py-8 {{ $section->css_classes ?? '' }} relative overflow-hidden"
                    @if ($sectionStyle) style="{{ $sectionStyle }}" @endif>
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

                        @if ($showTitle && $section->title)
                            <div class="text-right mb-3 md:mb-5">
                                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">
                                    @if ($icon)
                                        <i class="{{ $icon }} mr-2"></i>
                                    @endif
                                    {{ $section->title }}
                                </h2>
                                @if ($subtitle)
                                    <p class="text-sm md:text-base text-gray-500 mt-1">{{ $subtitle }}</p>
                                @endif
                                @if ($description)
                                    <p class="text-xs md:text-sm text-gray-400 mt-1 max-w-2xl" style="text-align:right;">
                                        {{ $description }}</p>
                                @endif
                            </div>
                        @endif

                        <div
                            class="dynamic-section-content {{ $isGrid ? 'grid ' . $colsClass . ' gap-3 md:gap-4' : ($isTwoCol ? 'grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 items-center' : 'space-y-4') }}">
                            @foreach ($section->items as $item)
                                @include('partials.home-section-item', ['item' => $item])
                            @endforeach
                        </div>

                    </div>
                </section>
            @endif
        @endforeach
    @endif

    {{-- ================================================================
     GALLERY – "اخترنا لك"
     ================================================================ --}}
    {{-- <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-br from-gray-50 to-green-50/50 relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-emerald-100 rounded-full blur-3xl opacity-30"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

            <div class="text-right mb-3 md:mb-5">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">اخترنا لك</h2>
            </div>

            @if ($latestGalleryImages->count() > 0)
                <div class="swiper gallerySwiper">
                    <div class="swiper-wrapper">
                        @foreach ($latestGalleryImages as $img)
                            @php
                                $imgSrc =
                                    $img->image_url ??
                                    ((isset($img->image_path) ? asset('storage/' . $img->image_path) : null) ??
                                        (isset($img->path) ? asset('storage/' . $img->path) : ''));
                            @endphp
                            <div class="swiper-slide">
                                <div class="relative group overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer gallery-item
                                        {{ isset($img->is_active) && !$img->is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}"
                                    data-media-type="{{ $img->media_type ?? 'image' }}"
                                    data-image-url="{{ $imgSrc }}"
                                    data-youtube-url="{{ $img->youtube_url ?? '' }}"
                                    data-image-name="{{ $img->name ?? 'صورة' }}"
                                    data-category-name="{{ $img->category->name ?? '' }}">

                                    @if ($imgSrc)
                                        <img src="{{ $imgSrc }}" alt="{{ $img->name ?? 'صورة' }}"
                                            class="w-full h-32 md:h-40 lg:h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                    @endif

                                    @if (isset($img->is_active) && !$img->is_active && Auth::check())
                                        <div class="absolute top-2 right-2 z-10">
                                            <span
                                                class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                        </div>
                                    @endif

                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-2 right-2 left-2">
                                            <p class="text-white text-xs md:text-sm font-bold truncate drop-shadow-lg">
                                                {{ $img->name ?? 'صورة' }}</p>
                                            @if ($img->category)
                                                <p class="text-white/90 text-xs mt-0.5">{{ $img->category->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next gallery-next"></div>
                    <div class="swiper-button-prev gallery-prev"></div>
                    <div class="swiper-pagination gallery-pagination"></div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-images text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm md:text-base">لا توجد صور متاحة حالياً</p>
                </div>
            @endif

        </div>
    </section> --}}

    {{-- ================================================================
     FAMILY NEWS
     ================================================================ --}}
    @if ($familyNews && $familyNews->count() > 0)
        <section class="py-3 md:py-6 lg:py-8 bg-white relative overflow-hidden">
            <div class="absolute top-0 left-0 w-48 h-48 bg-blue-100 rounded-full blur-3xl opacity-20"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

                <div class="text-right mb-3 md:mb-5">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">📰 أخبار العائلة
                    </h2>
                    <p class="text-gray-600 text-xs md:text-sm mt-1">آخر أخبار وأحداث العائلة</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                    @foreach ($familyNews as $news)
                        <a href="{{ route('family-news.show', $news->id) }}"
                            class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 bg-white card-hover
                          {{ !$news->is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}">

                            @if ($news->main_image_url)
                                <div class="relative h-36 md:h-48 overflow-hidden">
                                    <img src="{{ $news->main_image_url }}" alt="{{ $news->title }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @if (!$news->is_active && Auth::check())
                                        <div class="absolute top-2 right-2 z-10">
                                            <span
                                                class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                        </div>
                                    @endif
                                    @if ($news->images->count() > 0)
                                        <div class="absolute top-2 left-2 z-10">
                                            <span
                                                class="bg-black/50 text-white text-[8px] px-2 py-1 rounded-full font-bold backdrop-blur-sm">
                                                <i class="fas fa-images mr-1"></i>{{ $news->images->count() }} صور
                                            </span>
                                        </div>
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent">
                                    </div>
                                </div>
                            @else
                                <div class="relative h-36 md:h-48 gradient-primary flex items-center justify-center">
                                    <i class="fas fa-newspaper text-white text-4xl opacity-40"></i>
                                </div>
                            @endif

                            <div class="p-3 md:p-4">
                                <h3
                                    class="text-base md:text-lg font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors">
                                    {{ $news->title }}
                                </h3>
                                <p class="text-gray-600 text-xs md:text-sm mb-3 line-clamp-3">
                                    {{ $news->summary ?? Str::limit(strip_tags($news->content), 120) }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    @if ($news->published_at)
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-calendar"></i>
                                            {{ $news->published_at->format('Y-m-d') }}
                                        </span>
                                    @endif
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-eye"></i>
                                        {{ $news->views_count }} مشاهدة
                                    </span>
                                </div>
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <span class="text-green-600 text-xs md:text-sm font-semibold group-hover:underline">
                                        اقرأ المزيد <i class="fas fa-arrow-left mr-1"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    {{-- ================================================================
     FAMILY COUNCILS
     ================================================================ --}}
    <section class="py-2 md:py-4 lg:py-6 bg-white mobile-section">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

            <div class="text-right mb-3 md:mb-4">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gradient mb-1">مجالس العائلة</h2>
                <div
                    class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0 mb-1 md:mb-2">
                </div>
            </div>

            @if ($councils && $councils->count() > 0)
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden shadow-lg rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 bg-white">
                                <thead class="gradient-primary text-white">
                                    <tr>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            المجلس</th>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            المدينة</th>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            الموقع</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($councils as $council)
                                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer {{ !$council->is_active && Auth::check() ? 'opacity-60' : '' }}"
                                            onclick="toggleCouncilDescription({{ $council->id }})">
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 text-right" dir="ltr">
                                                <div class="flex items-center justify-end gap-1">
                                                    <span
                                                        class="text-xs font-semibold text-gray-900">{{ $council->name }}</span>
                                                    <i class="fas fa-building text-green-600 ml-1.5 text-xs"></i>
                                                    @if ($council->description)
                                                        <i
                                                            class="fas fa-chevron-down text-green-500 text-xs transition-transform duration-300 council-chevron-{{ $council->id }}"></i>
                                                    @endif
                                                    @if (!$council->is_active && Auth::check())
                                                        <span
                                                            class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 text-right">
                                                <span class="text-xs text-gray-700">{{ $council->address ?? '-' }}</span>
                                            </td>
                                            <td
                                                class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-xs font-medium text-right">
                                                @if ($council->google_map_url)
                                                    <a href="{{ $council->google_map_url }}" target="_blank"
                                                        class="text-blue-600 hover:text-blue-900 inline-flex items-center"
                                                        onclick="event.stopPropagation();" title="فتح في جوجل ماب">
                                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"
                                                                fill="#EA4335" stroke="#fff" stroke-width="1.5" />
                                                        </svg>
                                                        <span class="mr-1 hidden lg:inline text-xs">الخريطة</span>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($council->description)
                                            <tr
                                                class="council-description-row council-description-{{ $council->id }} hidden">
                                                <td colspan="3" class="px-2 py-0 md:px-3">
                                                    <div
                                                        class="council-description-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                                                        <div
                                                            class="bg-gradient-to-r from-green-50 to-emerald-50 border-r-4 border-green-500 rounded-lg p-3 md:p-4 my-1.5 shadow-md">
                                                            <div class="flex items-start gap-2">
                                                                <i
                                                                    class="fas fa-info-circle text-green-600 text-sm flex-shrink-0 mt-0.5"></i>
                                                                <div>
                                                                    <h4
                                                                        class="text-xs md:text-sm font-semibold text-gray-800 mb-1.5">
                                                                        نبذة عن {{ $council->name }}</h4>
                                                                    <p
                                                                        class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                                                                        {{ $council->description }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <i class="fas fa-building text-gray-400 text-3xl md:text-4xl mb-3"></i>
                    <p class="text-gray-600 text-sm md:text-base">لا توجد مجالس متاحة حالياً</p>
                </div>
            @endif

        </div>
    </section>

    {{-- ================================================================
     FAMILY EVENTS – مناسبات العائلة
     ================================================================ --}}
    <section class="py-2 md:py-4 lg:py-6 bg-white mobile-section">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

            <div class="text-right mb-3 md:mb-4">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gradient mb-1">مناسبات العائلة</h2>
                <div
                    class="w-16 md:w-24 h-0.5 md:h-1 bg-gradient-to-r from-transparent via-green-500 to-transparent mr-0 mb-1 md:mb-2">
                </div>
            </div>

            @if ($events && $events->count() > 0)
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden shadow-lg rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 bg-white">
                                <thead class="gradient-primary text-white">
                                    <tr>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            المناسبة</th>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            المدينة</th>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            الموقع</th>
                                        <th
                                            class="px-2 py-1.5 md:px-3 md:py-2 text-xs font-bold uppercase tracking-wider text-right">
                                            التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($events as $event)
                                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer {{ !$event->is_active && Auth::check() ? 'opacity-60' : '' }}"
                                            onclick="toggleEventDescription({{ $event->id }})">
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 text-right" dir="ltr">
                                                <div class="flex items-start justify-end gap-1.5">
                                                    <span
                                                        class="text-xs font-semibold text-gray-900 break-words flex-1 text-right">{{ $event->title }}</span>
                                                    <div class="flex items-center gap-1 flex-shrink-0">
                                                        <i class="fas fa-calendar-alt text-green-600 text-xs"></i>
                                                        @if ($event->description)
                                                            <i
                                                                class="fas fa-chevron-down text-green-500 text-xs transition-transform duration-300 event-chevron-{{ $event->id }}"></i>
                                                        @endif
                                                        @if (!$event->is_active && Auth::check())
                                                            <span
                                                                class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 text-right">
                                                <span class="text-xs text-gray-700">{{ $event->city ?? '-' }}</span>
                                            </td>
                                            <td
                                                class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-xs font-medium text-right">
                                                @if ($event->location)
                                                    <a href="{{ $event->location }}" target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="inline-flex items-center text-blue-600 hover:text-blue-800 group"
                                                        onclick="event.stopPropagation();"
                                                        title="{{ $event->location_name ?? 'فتح الموقع على الخريطة' }}">
                                                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"
                                                                fill="#EA4335" stroke="#fff" stroke-width="1.5" />
                                                        </svg>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-2 py-1.5 md:px-3 md:py-2 whitespace-nowrap text-right">
                                                <div class="text-xs font-medium text-gray-900 mb-1">
                                                    {{ $event->event_date->format('Y-m-d') }}
                                                </div>
                                                @if ($event->show_countdown && $event->event_date->isFuture())
                                                    <div class="event-countdown-{{ $event->id }} text-xs text-green-600 font-semibold text-right"
                                                        data-event-date="{{ $event->event_date->format('Y-m-d H:i:s') }}">
                                                        <span class="countdown-days"></span> يوم
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($event->description)
                                            <tr
                                                class="event-description-row event-description-{{ $event->id }} hidden">
                                                <td colspan="4" class="px-2 py-0 md:px-3">
                                                    <div
                                                        class="event-description-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                                                        <div
                                                            class="bg-gradient-to-r from-green-50 to-emerald-50 border-r-4 border-green-500 rounded-lg p-3 md:p-4 my-1.5 shadow-md">
                                                            <div class="flex items-start gap-2">
                                                                <i
                                                                    class="fas fa-info-circle text-green-600 text-sm flex-shrink-0 mt-0.5"></i>
                                                                <div>
                                                                    <h4
                                                                        class="text-xs md:text-sm font-semibold text-gray-800 mb-1.5">
                                                                        نبذة عن {{ $event->title }}</h4>
                                                                    <p
                                                                        class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                                                                        {{ $event->description }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <i class="fas fa-calendar-alt text-gray-400 text-3xl md:text-4xl mb-3"></i>
                    <p class="text-gray-600 text-sm md:text-base">لا توجد مناسبات متاحة حالياً</p>
                </div>
            @endif

        </div>
    </section>

    {{-- ================================================================
     FAMILY PROGRAMS
     ================================================================ --}}
    <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-br from-green-50 to-emerald-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-200 rounded-full blur-3xl opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

            <div class="text-right mb-3 md:mb-5">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">برامج العائلة</h2>
                <p class="text-gray-600 text-xs md:text-sm mt-1">فعاليات وأنشطة متنوعة</p>
            </div>

            @if ($programs && $programs->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1.5 md:gap-2.5">
                    @foreach ($programs as $program)
                        <a href="{{ route('programs.show', $program) }}"
                            class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 bg-white card-hover
                              {{ !$program->program_is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}">
                            <div class="aspect-square p-1.5 md:p-2">
                                <img src="{{ asset('storage/' . $program->path) }}"
                                    alt="{{ $program->program_title ?? ($program->name ?? 'برنامج') }}"
                                    class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                            </div>
                            @if (!$program->program_is_active && Auth::check())
                                <div class="absolute top-2 right-2 z-10">
                                    <span
                                        class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-2 right-2 left-2 text-center">
                                    <p class="text-white text-xs md:text-sm font-bold line-clamp-2 drop-shadow-lg">
                                        {{ $program->program_title ?? ($program->name ?? 'برنامج') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm md:text-base">لا توجد برامج متاحة حالياً</p>
                </div>
            @endif

        </div>
    </section>

    {{-- ================================================================
     PROUD OF
     ================================================================ --}}
    @if ($proudOf && $proudOf->count() > 0)
        <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-br from-green-50 to-emerald-50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-200 rounded-full blur-3xl opacity-20"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

                <div class="text-right mb-3 md:mb-5">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">نفخر بهم</h2>
                    <p class="text-gray-600 text-xs md:text-sm mt-1">إنجازات وإبداعات مميزة</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1.5 md:gap-2.5">
                    @foreach ($proudOf as $item)
                        <a href="{{ route('programs.show', $item) }}"
                            class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 bg-white card-hover
                          {{ !$item->proud_of_is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}">
                            <div class="aspect-square p-1.5 md:p-2">
                                <img src="{{ asset('storage/' . $item->path) }}"
                                    alt="{{ $item->proud_of_title ?? ($item->name ?? 'عنصر') }}"
                                    class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                            </div>
                            @if (!$item->proud_of_is_active && Auth::check())
                                <div class="absolute top-2 right-2 z-10">
                                    <span
                                        class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-2 right-2 left-2 text-center">
                                    <p class="text-white text-xs md:text-sm font-bold line-clamp-2 drop-shadow-lg">
                                        {{ $item->proud_of_title ?? ($item->name ?? 'عنصر') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    {{-- ================================================================
     ACADEMIC DEGREES – Graduates
     ================================================================ --}}
    @php
        $bachelorGraduates = $latestGraduates->where('degree_type', 'bachelor')->take(10);
        $masterGraduates = $latestGraduates->where('degree_type', 'master')->take(10);
        $phdGraduates = $latestGraduates->where('degree_type', 'phd')->take(10);
        $bachelorCategoryId = $bachelorGraduates->first()?->category_id;
        $masterCategoryId = $masterGraduates->first()?->category_id;
        $phdCategoryId = $phdGraduates->first()?->category_id;
    @endphp

    @if (
        (isset($bachelorTotalCount) && $bachelorTotalCount > 0) ||
            (isset($masterTotalCount) && $masterTotalCount > 0) ||
            (isset($phdTotalCount) && $phdTotalCount > 0))
        <section class="py-3 md:py-6 lg:py-8 bg-white relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-yellow-100 rounded-full blur-3xl opacity-20"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

                <div class="text-right mb-3 md:mb-5">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">الشهادات العلمية
                    </h2>
                    <p class="text-gray-600 text-xs md:text-sm mt-1">نفخر بإنجازاتهم الأكاديمية</p>
                </div>

                <div class="grid grid-cols-3 gap-2 md:gap-3 mb-4">
                    @if (isset($phdTotalCount) && $phdTotalCount > 0 && $phdCategoryId)
                        <a href="{{ route('gallery.articles', ['category' => $phdCategoryId]) }}"
                            class="group relative overflow-hidden rounded-xl shadow-lg bg-gradient-to-br from-yellow-100 via-yellow-50 to-amber-100 p-1.5 md:p-4 card-hover border-2 border-yellow-200">
                            <div class="text-center">
                                <div
                                    class="w-10 h-10 md:w-16 md:h-16 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-1 md:mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-graduation-cap text-white text-base md:text-2xl"></i>
                                </div>
                                <h3 class="text-xs md:text-xl font-bold text-yellow-800 mb-0.5">الدكتوراه</h3>
                                <div class="text-lg md:text-4xl font-bold text-yellow-600 mb-0.5">{{ $phdTotalCount }}
                                </div>
                                <p class="text-yellow-700 text-[10px] md:text-xs">خريج</p>
                            </div>
                        </a>
                    @endif

                    @if (isset($masterTotalCount) && $masterTotalCount > 0 && $masterCategoryId)
                        <a href="{{ route('gallery.articles', ['category' => $masterCategoryId]) }}"
                            class="group relative overflow-hidden rounded-xl shadow-lg bg-gradient-to-br from-indigo-100 via-indigo-50 to-purple-100 p-1.5 md:p-4 card-hover border-2 border-indigo-200">
                            <div class="text-center">
                                <div
                                    class="w-10 h-10 md:w-16 md:h-16 bg-indigo-400 rounded-full flex items-center justify-center mx-auto mb-1 md:mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-user-graduate text-white text-base md:text-2xl"></i>
                                </div>
                                <h3 class="text-xs md:text-xl font-bold text-indigo-800 mb-0.5">الماجستير</h3>
                                <div class="text-lg md:text-4xl font-bold text-indigo-600 mb-0.5">{{ $masterTotalCount }}
                                </div>
                                <p class="text-indigo-700 text-[10px] md:text-xs">خريج</p>
                            </div>
                        </a>
                    @endif

                    @if (isset($bachelorTotalCount) && $bachelorTotalCount > 0 && $bachelorCategoryId)
                        <a href="{{ route('gallery.articles', ['category' => $bachelorCategoryId]) }}"
                            class="group relative overflow-hidden rounded-xl shadow-lg bg-gradient-to-br from-green-100 via-green-50 to-emerald-100 p-1.5 md:p-4 card-hover border-2 border-green-200">
                            <div class="text-center">
                                <div
                                    class="w-10 h-10 md:w-16 md:h-16 bg-green-400 rounded-full flex items-center justify-center mx-auto mb-1 md:mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-award text-white text-base md:text-2xl"></i>
                                </div>
                                <h3 class="text-xs md:text-xl font-bold text-green-800 mb-0.5">البكالوريوس</h3>
                                <div class="text-lg md:text-4xl font-bold text-green-600 mb-0.5">{{ $bachelorTotalCount }}
                                </div>
                                <p class="text-green-700 text-[10px] md:text-xs">خريج</p>
                            </div>
                        </a>
                    @endif
                </div>

            </div>
        </section>
    @endif

    {{-- ================================================================
     COURSES – أكاديمية السريع
     ================================================================ --}}
    <section class="py-3 md:py-6 lg:py-8 bg-gradient-to-br from-gray-50 to-green-50/50 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-green-200 rounded-full blur-3xl opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

            <div class="text-right mb-3 md:mb-5">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">دورات أكاديمية
                    السريع</h2>
                <p class="text-gray-600 text-xs md:text-sm mt-1">تعلم وتطور مع دوراتنا المتميزة</p>
            </div>

            @if ($courses->count() > 0)
                <div class="swiper coursesSwiper">
                    <div class="swiper-wrapper">
                        @foreach ($courses as $course)
                            <div class="swiper-slide">
                                <div
                                    class="glass-card rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 h-full flex flex-col
                                        {{ !$course->is_active && Auth::check() ? 'opacity-60 grayscale' : '' }}">

                                    <div class="relative h-32 md:h-36 gradient-primary overflow-hidden">
                                        @if ($course->image_url)
                                            <img src="{{ $course->image_url }}" alt="{{ $course->title }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <i
                                                    class="fas fa-book-open text-white text-4xl opacity-30 animate-float"></i>
                                            </div>
                                        @endif
                                        @if (!$course->is_active && Auth::check())
                                            <div class="absolute top-2 right-2 z-10">
                                                <span
                                                    class="bg-yellow-500 text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold">معطل</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-2.5 md:p-3 flex-1 flex flex-col">
                                        <h3 class="text-sm md:text-base font-bold text-gray-800 mb-1 line-clamp-2">
                                            {{ $course->title }}</h3>
                                        <p class="text-gray-600 text-xs mb-2 line-clamp-2 flex-1">
                                            {{ $course->description ?? 'دورة تدريبية متميزة' }}</p>

                                        <div class="space-y-0.5 mb-2">
                                            @if ($course->instructor)
                                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                    <i class="fas fa-user text-green-500"></i>
                                                    <span>{{ $course->instructor }}</span>
                                                </div>
                                            @endif
                                            @if ($course->duration)
                                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                    <i class="fas fa-clock text-green-500"></i>
                                                    <span>{{ $course->duration }}</span>
                                                </div>
                                            @endif
                                            @if ($course->students > 0)
                                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                    <i class="fas fa-users text-green-500"></i>
                                                    <span>{{ $course->students }} طالب</span>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($course->link)
                                            <a href="{{ $course->link }}" target="_blank"
                                                class="w-full py-2 px-3 gradient-secondary text-white rounded-lg text-xs md:text-sm font-semibold hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                                <span>سجل الآن</span>
                                                <i class="fas fa-arrow-left"></i>
                                            </a>
                                        @else
                                            <button
                                                class="w-full py-2 px-3 gradient-secondary text-white rounded-lg text-xs md:text-sm font-semibold hover:shadow-lg transition-all">
                                                قريباً
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next courses-next"></div>
                    <div class="swiper-button-prev courses-prev"></div>
                    <div class="swiper-pagination courses-pagination"></div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-book-open text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm md:text-base">لا توجد دورات متاحة حالياً</p>
                </div>
            @endif

        </div>
    </section>

    {{-- ================================================================
     GALLERY MODAL
     ================================================================ --}}
    <div id="galleryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/95 backdrop-blur-sm"
        style="display:none;">
        <div class="relative w-full h-full flex items-center justify-center p-2 md:p-4">

            {{-- Close button --}}
            <button id="closeGalleryModal"
                class="absolute top-2 right-2 md:top-4 md:right-4 z-50 text-white bg-red-600/90 hover:bg-red-600 rounded-full p-2.5 md:p-3 shadow-2xl hover:scale-110 active:scale-95 transition-all">
                <i class="fas fa-times text-lg md:text-2xl"></i>
            </button>

            <div
                class="md:hidden absolute top-2 left-2 z-40 bg-black/60 text-white text-[10px] px-2 py-1 rounded-lg backdrop-blur-sm">
                <i class="fas fa-hand-pointer mr-1"></i> اضغط للخروج
            </div>

            <div class="relative max-w-7xl w-full h-full flex items-center justify-center">
                <div id="galleryModalImageContainer" class="hidden w-full h-full flex items-center justify-center">
                    <img id="galleryModalImage" src="" alt=""
                        class="max-w-full max-h-full object-contain rounded-lg cursor-pointer" onclick="Gallery.close()">
                </div>
                <div id="galleryModalVideoContainer" class="hidden w-full h-full flex items-center justify-center">
                    <div class="w-full" style="max-width:90vw; aspect-ratio:16/9;">
                        <iframe id="galleryModalVideo" src="" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen class="w-full h-full rounded-lg"></iframe>
                    </div>
                </div>
            </div>

            <div id="galleryModalInfo"
                class="absolute bottom-2 md:bottom-4 right-2 left-2 md:right-4 md:left-4 text-white text-center bg-black/70 backdrop-blur-md rounded-lg p-3 md:p-4">
                <h3 id="galleryModalTitle" class="text-base md:text-xl font-bold mb-1"></h3>
                <p id="galleryModalCategory" class="text-xs md:text-sm text-gray-300 mb-2"></p>
                <button onclick="Gallery.close()"
                    class="md:hidden mt-2 bg-red-600 hover:bg-red-700 text-white text-xs px-4 py-2 rounded-lg font-semibold">
                    <i class="fas fa-times mr-1"></i> إغلاق
                </button>
                <p class="hidden md:block text-xs text-gray-400 mt-2">
                    <i class="fas fa-info-circle mr-1"></i> اضغط على X أو اضغط ESC للخروج
                </p>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        /* ================================================================
       SWIPERS
       ================================================================ */
        document.addEventListener('DOMContentLoaded', function() {
            var totalSlides = {{ $latestImages->count() ?? 0 }};
            var totalImages = {{ $latestGalleryImages->count() ?? 0 }};
            var totalCourses = {{ $courses->count() ?? 0 }};

            // Hero
            if (totalSlides > 0 && typeof Swiper !== 'undefined') {
                new Swiper('.heroSwiper', {
                    slidesPerView: 1,
                    loop: totalSlides > 1,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    speed: 1000,
                    pagination: {
                        el: '.hero-pagination',
                        clickable: true,
                        dynamicBullets: true
                    },
                    navigation: {
                        nextEl: '.hero-next',
                        prevEl: '.hero-prev'
                    },
                    keyboard: {
                        enabled: true
                    },
                });
            }

            // Gallery & Courses – wait one frame so Swiper gets correct dimensions
            var initSwipers = function() {
                if (totalImages > 0 && typeof Swiper !== 'undefined' && document.querySelector(
                    '.gallerySwiper')) {
                    new Swiper('.gallerySwiper', {
                        slidesPerView: 2,
                        spaceBetween: 15,
                        loop: totalImages > 4,
                        autoplay: {
                            delay: 4000,
                            disableOnInteraction: false
                        },
                        pagination: {
                            el: '.gallery-pagination',
                            clickable: true,
                            dynamicBullets: true
                        },
                        navigation: {
                            nextEl: '.gallery-next',
                            prevEl: '.gallery-prev'
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 3,
                                spaceBetween: 20
                            },
                            1024: {
                                slidesPerView: 4,
                                spaceBetween: 24
                            },
                            1280: {
                                slidesPerView: 5,
                                spaceBetween: 24
                            },
                        },
                    });
                }
                if (totalCourses > 0 && typeof Swiper !== 'undefined' && document.querySelector(
                        '.coursesSwiper')) {
                    new Swiper('.coursesSwiper', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: totalCourses > 3,
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false
                        },
                        pagination: {
                            el: '.courses-pagination',
                            clickable: true,
                            dynamicBullets: true
                        },
                        navigation: {
                            nextEl: '.courses-next',
                            prevEl: '.courses-prev'
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 2,
                                spaceBetween: 20
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 24
                            },
                        },
                    });
                }
            };

            requestAnimationFrame
                ?
                requestAnimationFrame(function() {
                    setTimeout(initSwipers, 50);
                }) :
                setTimeout(initSwipers, 100);
        });

        /* ================================================================
           ACCORDION – Councils & Events
           ================================================================ */
        function toggleAccordion(type, id) {
            var row = document.querySelector('.' + type + '-description-' + id);
            var content = row && row.querySelector('.' + type + '-description-content');
            var chevron = document.querySelector('.' + type + '-chevron-' + id);
            if (!row || !content) return;

            var isHidden = row.classList.contains('hidden');
            if (isHidden) {
                row.classList.remove('hidden');
                setTimeout(function() {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }, 10);
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            } else {
                content.style.maxHeight = '0';
                if (chevron) chevron.style.transform = 'rotate(0deg)';
                setTimeout(function() {
                    row.classList.add('hidden');
                }, 500);
            }
        }

        function toggleCouncilDescription(id) {
            toggleAccordion('council', id);
        }

        function toggleEventDescription(id) {
            toggleAccordion('event', id);
        }

        /* ================================================================
           EVENTS – Day countdown
           ================================================================ */
        function updateEventCountdowns() {
            document.querySelectorAll('[class*="event-countdown-"]').forEach(function(el) {
                var dateStr = el.getAttribute('data-event-date');
                if (!dateStr) return;
                var diff = new Date(dateStr) - new Date();
                if (diff <= 0) {
                    el.innerHTML = '<span class="text-red-600">انتهت المناسبة</span>';
                    return;
                }
                var daysEl = el.querySelector('.countdown-days');
                if (daysEl) daysEl.textContent = Math.floor(diff / 86400000);
            });
        }
        updateEventCountdowns();
        setInterval(updateEventCountdowns, 60000);

        /* ================================================================
           GALLERY MODAL
           ================================================================ */
        var Gallery = (function() {
            var modal = document.getElementById('galleryModal');
            var imageWrapper = document.getElementById('galleryModalImageContainer');
            var videoWrapper = document.getElementById('galleryModalVideoContainer');
            var imgEl = document.getElementById('galleryModalImage');
            var iframeEl = document.getElementById('galleryModalVideo');
            var titleEl = document.getElementById('galleryModalTitle');
            var catEl = document.getElementById('galleryModalCategory');

            function extractYoutubeId(url) {
                var patterns = [
                    /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
                    /youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/,
                ];
                for (var i = 0; i < patterns.length; i++) {
                    var m = url.match(patterns[i]);
                    if (m) return m[1];
                }
                return null;
            }

            function open(item) {
                var type = item.getAttribute('data-media-type');
                var imgUrl = item.getAttribute('data-image-url');
                var ytUrl = item.getAttribute('data-youtube-url');
                var name = item.getAttribute('data-image-name');
                var category = item.getAttribute('data-category-name');

                titleEl.textContent = name;
                catEl.textContent = category || '';

                if (type === 'youtube' && ytUrl) {
                    var vid = extractYoutubeId(ytUrl);
                    if (vid) {
                        iframeEl.src = 'https://www.youtube.com/embed/' + vid + '?autoplay=1';
                        imageWrapper.classList.add('hidden');
                        videoWrapper.classList.remove('hidden');
                    } else {
                        imgEl.src = imgUrl;
                        videoWrapper.classList.add('hidden');
                        imageWrapper.classList.remove('hidden');
                    }
                } else {
                    imgEl.src = imgUrl;
                    videoWrapper.classList.add('hidden');
                    iframeEl.src = '';
                    imageWrapper.classList.remove('hidden');
                }

                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function close() {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = '';
                iframeEl.src = '';
                imageWrapper.classList.add('hidden');
                videoWrapper.classList.add('hidden');
            }

            // Bind events after DOM ready
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.gallery-item').forEach(function(item) {
                    item.addEventListener('click', function() {
                        open(this);
                    });
                });

                document.getElementById('closeGalleryModal')
                    .addEventListener('click', close);

                modal.addEventListener('click', function(e) {
                    if (e.target === modal) close();
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') close();
                });

                // Swipe-down to close
                var sy = 0;
                modal.addEventListener('touchstart', function(e) {
                    sy = e.changedTouches[0].screenY;
                }, {
                    passive: true
                });
                modal.addEventListener('touchend', function(e) {
                    if (sy - e.changedTouches[0].screenY > 100) close();
                }, {
                    passive: true
                });
            });

            return {
                open: open,
                close: close
            };
        })();

        /* ================================================================
           QUIZ – Next event countdown  (بدون reload – AJAX عند الانتهاء)
           ================================================================ */
        @if (isset($nextQuizEvent) && $nextQuizEvent)
            (function() {
                var el = document.getElementById('quizCountdownTarget');
                if (!el) return;

                var target = parseInt(el.value, 10);
                var ids = ['days', 'hours', 'minutes', 'seconds'];
                var finished = false;
                var timer;

                function zeroAll() {
                    ids.forEach(function(id) {
                        var node = document.getElementById('countdown-' + id);
                        if (node) node.textContent = '0';
                    });
                }

                /* ── جلب المسابقة النشطة بـ AJAX وحقنها بدون reload ── */
                function fetchActiveQuiz() {
                    var wrapper = document.getElementById('quizCountdownSection');

                    /* حالة تحميل مؤقتة */
                    if (wrapper) {
                        wrapper.innerHTML =
                            '<div class="glass-card rounded-3xl p-6 text-center shadow-lg" style="box-shadow:0 0 30px rgba(34,197,94,.15);">' +
                            '<div class="inline-flex items-center gap-2 text-green-600 text-sm font-medium">' +
                            '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">' +
                            '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                            '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>' +
                            '</svg><span>جاري تحميل المسابقة...</span></div></div>';
                    }

                    fetch(window.location.href, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html'
                            }
                        })
                        .then(function(res) {
                            return res.text();
                        })
                        .then(function(html) {
                            var parser = new DOMParser();
                            var doc = parser.parseFromString(html, 'text/html');
                            var newActive = doc.getElementById('activeQuizSection');

                            if (newActive) {
                                /* المسابقة بدأت → استبدل قسم العداد بكتلة المسابقة */
                                if (wrapper) wrapper.outerHTML = newActive.outerHTML;

                                /* شغّل عداد نهاية المسابقة */
                                var endInput = document.getElementById('aqEndTime');
                                if (endInput) startActiveQuizTimer(parseInt(endInput.value, 10));

                                /* شغّل عداد كشف الأسئلة */
                                var visInput = document.getElementById('aqQuestionsVisibleAt');
                                if (visInput) startRevealCountdown(parseInt(visInput.value, 10));
                            } else {
                                /* لم تبدأ بعد → إعادة المحاولة بعد 10 ثوانٍ */
                                var current = document.getElementById('quizCountdownSection');
                                if (current) {
                                    current.innerHTML =
                                        '<div class="glass-card rounded-3xl p-4 text-center shadow-lg border-2 border-amber-200 bg-amber-50">' +
                                        '<i class="fas fa-clock text-amber-500 text-2xl mb-2 block"></i>' +
                                        '<p class="text-amber-800 font-medium text-sm">جاري تجهيز المسابقة، سيتم التحديث تلقائياً...</p>' +
                                        '</div>';
                                }
                                setTimeout(fetchActiveQuiz, 10000);
                            }
                        })
                        .catch(function() {
                            setTimeout(fetchActiveQuiz, 15000);
                        });
                }

                /* ── العداد الرئيسي ── */
                function tick() {
                    var diff = target - Date.now();

                    if (diff <= 0 && !finished) {
                        finished = true;
                        clearInterval(timer);
                        zeroAll();
                        setTimeout(fetchActiveQuiz, 500);
                        return;
                    }

                    var d = Math.floor(diff / 86400000);
                    var h = Math.floor((diff % 86400000) / 3600000);
                    var m = Math.floor((diff % 3600000) / 60000);
                    var s = Math.floor((diff % 60000) / 1000);

                    [d, h, m, s].forEach(function(val, i) {
                        var node = document.getElementById('countdown-' + ids[i]);
                        if (node) node.textContent = val;
                    });
                }

                tick();
                timer = setInterval(tick, 1000);
            })();
        @endif

        /* ================================================================
           QUIZ – Active competition helpers
           (دوال global عشان AJAX يقدر يستدعيها بعد حقن HTML جديد)
           ================================================================ */

        /* عداد h:m:s لنهاية المسابقة الفعلية */
        function startActiveQuizTimer(endTimestamp) {
            function pad(n) {
                return n.toString().padStart(2, '0');
            }

            function tick() {
                var diff = endTimestamp - Date.now();
                var hEl = document.getElementById('aq-hours');
                var mEl = document.getElementById('aq-minutes');
                var sEl = document.getElementById('aq-seconds');
                if (!hEl) return; // العنصر اتشال من DOM
                if (diff <= 0) {
                    hEl.textContent = '00';
                    mEl.textContent = '00';
                    sEl.textContent = '00';
                    window.location.reload(); // انتهاء المسابقة الفعلية → reload
                    return;
                }
                hEl.textContent = pad(Math.floor(diff / 3600000));
                mEl.textContent = pad(Math.floor((diff % 3600000) / 60000));
                sEl.textContent = pad(Math.floor((diff % 60000) / 1000));
            }
            tick();
            setInterval(tick, 1000);
        }

        /* عداد الثواني حتى ظهور نص الأسئلة */
        function startRevealCountdown(visibleAtTimestamp) {
            var banner = document.getElementById('activeQuizQuestionsCountdown');
            var descs = document.getElementById('activeQuizDescriptionsOnlyBlock');
            var block = document.getElementById('activeQuizQuestionsBlock');
            var display = document.getElementById('aqQuestionsSeconds');
            if (!banner || !block || !display) return;

            function reveal() {
                banner.style.display = 'none';
                if (descs) descs.style.display = 'none';
                block.style.display = '';
            }

            var iv = setInterval(function() {
                var remaining = Math.ceil((visibleAtTimestamp - Date.now()) / 1000);
                if (remaining <= 0) {
                    clearInterval(iv);
                    display.textContent = '0';
                    reveal(); // فقط كشف الأسئلة – بدون reload
                } else {
                    display.textContent = remaining;
                }
            }, 1000);

            /* تشغيل فوري قبل أول tick */
            var init = Math.ceil((visibleAtTimestamp - Date.now()) / 1000);
            display.textContent = init > 0 ? init : '0';
            if (init <= 0) {
                clearInterval(iv);
                reveal();
            }
        }

        /* تشغيل عند تحميل الصفحة لو المسابقة موجودة أصلاً من السيرفر */
        @if (isset($activeQuizCompetition) && $activeQuizCompetition)
            (function() {
                var endInput = document.getElementById('aqEndTime');
                if (endInput) startActiveQuizTimer(parseInt(endInput.value, 10));

                var visInput = document.getElementById('aqQuestionsVisibleAt');
                if (visInput) startRevealCountdown(parseInt(visInput.value, 10));
            })();
        @endif
    </script>
@endpush
