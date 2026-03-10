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
                            <input type="hidden" id="aqQuestionsVisibleAt" value="{{ $questionsVisibleAt->getTimestamp() * 1000 }}">

                            {{-- Question descriptions show immediately --}}
                            @if ($activeQuizCompetition->questions->filter(fn($q) => !empty($q->description))->isNotEmpty())
                                <div id="activeQuizDescriptionsOnlyBlock" class="space-y-4 mb-3">
                                    @foreach ($activeQuizCompetition->questions as $q)
                                        @if ($q->description)
                                            <div class="rounded-2xl p-3 border-2 border-green-100 bg-white/80 shadow-sm">
                                                <div class="text-gray-600 text-sm quiz-description">
                                                    {!! $q->description !!}
                                                </div>
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
                        <div id="activeQuizQuestionsBlock" class="space-y-4 mb-3" @if (!($showQuestionsOnHome ?? true))
                        style="display:none" @endif>

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
                                        {!! $q->question_text !!}
                                    </div>

                                    @if ($activeQuizCompetition->show_draw_only)
                                        <div
                                            class="rounded-xl p-4 bg-green-50 border border-green-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                            <p class="text-green-800 text-sm font-medium">
                                                <i class="fas fa-info-circle text-green-600 ml-1"></i>
                                                باب الإجابة مغلق حالياً، يمكنك متابعة فرز النتائج والقرعة من هنا.
                                            </p>
                                            <a href="{{ route('quiz-competitions.question', [$activeQuizCompetition, $q]) }}"
                                                class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white transition-all hover:opacity-90"
                                                style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                                                <i class="fas fa-trophy"></i>
                                                متابعة القرعة
                                            </a>
                                        </div>
                                    @elseif ($canAnswerThis)
                                        <form action="{{ route('quiz-competitions.store-answer', [$activeQuizCompetition, $q]) }}"
                                            method="POST" class="space-y-4">
                                            @csrf

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-gray-600 text-xs mb-1 font-medium">
                                                        الاسم <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" name="name" value="{{ old('name') }}" required
                                                        placeholder="الاسم الكامل"
                                                        class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500">
                                                </div>
                                                <div>
                                                    <label class="block text-gray-600 text-xs mb-1 font-medium">
                                                        رقم الهاتف <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" name="phone" value="{{ old('phone') }}" required
                                                        pattern="[0-9]{10}" minlength="10" maxlength="10" placeholder="05xxxxxxxx"
                                                        dir="ltr" style="text-align:right;" title="يجب أن يكون رقم الهاتف 10 أرقام"
                                                        class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-gray-600 text-xs mb-1 font-medium">
                                                    اسم الأم (للمستخدمين من الأنساب)
                                                </label>
                                                <input type="text" name="mother_name" value="{{ old('mother_name') }}"
                                                    placeholder="ينتهي باسم السريع"
                                                    class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500">
                                                <input type="hidden" name="is_from_ancestry" value="1">
                                            </div>

                                            <div>
                                                <label class="block text-gray-600 text-xs mb-2 font-medium">
                                                    الإجابة <span class="text-red-500">*</span>
                                                </label>

                                                @if ($q->answer_type === 'ordering' && $q->choices->count() > 0)
                                                    @php
                                                        $shuffledChoices = clone $q->choices;
                                                        $shuffledChoices = $shuffledChoices->shuffle();
                                                        $hasImages = $q->choices->contains(fn($c) => !empty($c->image));
                                                    @endphp

                                                    @if ($hasImages)
                                                        {{-- IMAGE-BASED ORDERING: two-zone drag & drop --}}
                                                        <p class="text-xs text-green-700 font-medium mb-2">
                                                            <i class="fas fa-hand-pointer ml-1"></i>
                                                            اسحب الصور من الأعلى وأسقطها على المربع المناسب بالترتيب الصحيح
                                                        </p>

                                                        {{-- SOURCE: wrapping grid of images --}}
                                                        <div class="ordering-source-zone mb-3" data-question-id="{{ $q->id }}">
                                                            <div class="flex items-center gap-1.5 mb-1.5">
                                                                <i class="fas fa-images text-green-500 text-xs"></i>
                                                                <span class="text-[11px] text-gray-500 font-medium">الصور المتاحة</span>
                                                            </div>
                                                            <div class="ordering-source-grid" id="orderSource-{{ $q->id }}">
                                                                @foreach ($shuffledChoices as $choice)
                                                                    <div data-id="{{ $choice->id }}" class="ordering-img-item" title="{{ $choice->choice_text }}">
                                                                        <img src="{{ asset('storage/' . $choice->image) }}"
                                                                            alt="{{ $choice->choice_text }}"
                                                                            draggable="false">
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        {{-- ARROW --}}
                                                        <div class="flex justify-center mb-2">
                                                            <i class="fas fa-arrow-down text-green-400 text-lg animate-bounce"></i>
                                                        </div>

                                                        {{-- TARGET: individual numbered slots --}}
                                                        <div class="ordering-target-zone" data-question-id="{{ $q->id }}">
                                                            <div class="flex items-center gap-1.5 mb-1.5">
                                                                <i class="fas fa-sort-numeric-down text-green-500 text-xs"></i>
                                                                <span class="text-[11px] text-gray-500 font-medium">الترتيب الصحيح (اسقط الصور هنا)</span>
                                                            </div>
                                                            <div class="ordering-slots-grid" id="orderTarget-{{ $q->id }}">
                                                                @foreach ($q->choices as $s => $choice)
                                                                    <div class="ordering-slot flex-col gap-1" data-slot="{{ $s + 1 }}">
                                                                        @if(!empty($choice->choice_text))
                                                                            <span class="ordering-slot-text text-xs sm:text-sm text-green-600 font-bold text-center px-1 leading-tight pointer-events-none">{{ $choice->choice_text }}</span>
                                                                        @else
                                                                            <span class="ordering-slot-num">{{ $s + 1 }}</span>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <input type="hidden" name="answer_order_q{{ $q->id }}" value=""
                                                            class="ordering-final-input" data-question-id="{{ $q->id }}"
                                                            id="orderInput-{{ $q->id }}">
                                                        <input type="hidden" class="ordering-total-count" value="{{ $q->choices->count() }}">
                                                    @else
                                                        {{-- TEXT-BASED ORDERING: classic list --}}
                                                        <p class="text-xs text-green-700 font-medium mb-2">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            قم بسحب وإفلات الخيارات لترتيبها بشكل صحيح
                                                        </p>
                                                        <div class="space-y-2 sortable-list" data-question-id="{{ $q->id }}">
                                                            @foreach ($shuffledChoices as $choice)
                                                                <div data-id="{{ $choice->id }}"
                                                                    class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 bg-white hover:border-green-300 cursor-move transition-all sortable-item select-none shadow-sm">
                                                                    <i class="fas fa-grip-lines text-gray-400"></i>
                                                                    <span
                                                                        class="text-gray-800 text-sm font-medium flex-grow">{{ $choice->choice_text }}</span>
                                                                    <input type="hidden" name="answer[]" value="{{ $choice->id }}"
                                                                        class="ordering-input-hidden">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @elseif ($q->answer_type === 'multiple_choice' && $q->choices->count() > 0)
                                                    @if(!$q->is_multiple_selections && $q->choices->count() === 1)
                                                        {{-- Single answer: hidden input + button replaces submit --}}
                                                        @php $singleChoice = $q->choices->first(); @endphp
                                                        <input type="hidden" name="answer" value="{{ $singleChoice->id }}">
                                                        <p class="text-xs text-green-700 font-medium mb-2">
                                                            <i class="fas fa-hand-pointer ml-1"></i> اضغط الزر أدناه للإجابة
                                                        </p>
                                                    @else
                                                        @if($q->is_multiple_selections)
                                                            @php
                                                                $requiredCount = $q->getRequiredCorrectAnswersCount();
                                                            @endphp
                                                            <p class="text-xs text-green-700 font-medium mb-2">
                                                                يجب اختيار {{ $requiredCount }} إجابات
                                                                <input type="hidden" class="required-choices-count" value="{{ $requiredCount }}">
                                                            </p>
                                                        @endif
                                                        <div class="space-y-2 choice-group">
                                                            @foreach ($q->choices as $choice)
                                                                <label
                                                                    class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 bg-white hover:border-green-300 hover:bg-green-50/50 cursor-pointer transition-all has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                                                    @if($q->is_multiple_selections)
                                                                        <input type="checkbox" name="answer[]" value="{{ $choice->id }}"
                                                                            class="w-4 h-4 text-green-600 home-quiz-checkbox">
                                                                    @else
                                                                        <input type="radio" name="answer" value="{{ $choice->id }}"
                                                                            class="w-4 h-4 text-green-600" required>
                                                                    @endif
                                                                    <span
                                                                        class="text-gray-800 text-sm font-medium">{{ $choice->choice_text }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @else
                                                    <textarea name="answer" rows="3" required placeholder="اكتب إجابتك..."
                                                        class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500 resize-none">{{ old('answer') }}</textarea>
                                                @endif
                                            </div>

                                            @if($q->answer_type === 'multiple_choice' && !$q->is_multiple_selections && $q->choices->count() === 1)
                                                <button type="submit" onclick="validateHomeQuiz(event, this)"
                                                    class="w-full px-6 py-4 rounded-xl text-white font-bold text-base inline-flex items-center justify-center gap-2 transition-all hover:opacity-90 hover:scale-[1.02] active:scale-[0.98] mt-4"
                                                    style="background: linear-gradient(135deg, #22c55e, #16a34a); animation: pulse-soft 2s infinite;">
                                                    <i class="fas fa-hand-pointer"></i>
                                                    {{ $q->choices->first()->choice_text }}
                                                </button>
                                            @else
                                                <button type="submit" onclick="validateHomeQuiz(event, this)"
                                                    class="w-full sm:w-auto px-6 py-3 rounded-xl text-white font-bold text-sm inline-flex items-center justify-center gap-2 transition-all hover:opacity-90 mt-4"
                                                    style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                                                    <i class="fas fa-paper-plane"></i>
                                                    إرسال الإجابة
                                                </button>
                                            @endif
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
@endif
{{-- /quiz section --}}

@push('styles')
<style>
    /* ── Grids ── */
    .ordering-source-grid,
    .ordering-slots-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
    }
    .ordering-source-grid { min-height: 50px; }

    /* ── Individual numbered slot ── */
    .ordering-slot {
        aspect-ratio: 1;
        border: 2px dashed #86efac;
        border-radius: 12px;
        background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    }
    .ordering-slot-num {
        font-size: 18px;
        font-weight: 700;
        color: #bbf7d0;
        pointer-events: none;
    }
    .ordering-slot.has-image {
        border-style: solid;
        border-color: #22c55e;
        background: #fff;
        padding: 0;
    }
    .ordering-slot.has-image .ordering-slot-num,
    .ordering-slot.has-image .ordering-slot-text { display: none; }
    .ordering-slot.drag-hover {
        border-color: #22c55e;
        background: #dcfce7;
        box-shadow: 0 0 12px rgba(34,197,94,0.3);
    }

    /* image inside a slot */
    .ordering-slot .ordering-img-item {
        width: 100%;
        aspect-ratio: 1;
    }
    .ordering-slot .ordering-img-item img {
        border: none;
        border-radius: 10px;
    }

    /* ── Image items (shared) ── */
    .ordering-img-item {
        cursor: grab;
        touch-action: none;
        user-select: none;
        -webkit-user-select: none;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 1;
    }
    .ordering-img-item:active { cursor: grabbing; }
    .ordering-img-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        pointer-events: none;
        display: block;
        border-radius: 12px;
        border: 2px solid #d1d5db;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .ordering-source-grid .ordering-img-item:hover img {
        border-color: #22c55e;
        box-shadow: 0 2px 10px rgba(34,197,94,0.25);
    }
    .ordering-img-item.sortable-chosen {
        opacity: 0.6;
    }
    .ordering-img-item.sortable-ghost {
        opacity: 0.15;
    }

    /* ── Text-based ordering ── */
    .sortable-item.sortable-chosen {
        background: #f0fdf4;
        border-color: #22c55e;
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.2);
    }
    .sortable-item.sortable-ghost { opacity: 0.3; }

    @media (max-width: 374px) {
        .ordering-source-grid,
        .ordering-slots-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>
@endpush
