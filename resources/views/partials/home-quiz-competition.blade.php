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

                            @php
                                $hasNonVote = $activeQuizCompetition->questions->contains(fn($q) => $q->answer_type !== 'vote');
                            @endphp
                            @if($hasNonVote)
                                <h4 class="text-sm font-bold text-gray-600 mb-2">أسئلة المسابقة — أجب هنا:</h4>
                            @endif

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

                                    <div class="text-gray-800 font-bold text-base mb-2 question-text" @if($q->answer_type === 'fill_blank') style="display:none" @endif>
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
                                            <input type="hidden" name="source" value="home">

                                            @if($q->answer_type === 'vote' && $q->require_prior_registration)
                                                <div class="mb-4">
                                                    <p class="text-xs text-blue-600 mb-2 font-medium">
                                                        <i class="fas fa-info-circle mx-1"></i>
                                                        هذا التصويت للمشاركين السابقين فقط. أدخل رقم هاتفك للتحقق.
                                                    </p>
                                                    <label class="block text-gray-600 text-xs mb-1 font-medium">
                                                        رقم الهاتف <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" name="phone" value="{{ old('phone') }}" required
                                                        pattern="[0-9]{10}" minlength="10" maxlength="10" placeholder="05xxxxxxxx"
                                                        dir="ltr" style="text-align:right;" title="يجب أن يكون رقم الهاتف 10 أرقام للمشارك السابق"
                                                        class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500" autocomplete="off">
                                                </div>
                                            @else
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-gray-600 text-xs mb-1 font-medium">
                                                            الاسم @if($q->answer_type !== 'vote')<span class="text-red-500">*</span>@endif
                                                        </label>
                                                        <input type="text" name="name" value="{{ old('name') }}" {{ $q->answer_type !== 'vote' ? 'required' : '' }}
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
                                            @endif

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

                                                            @php
                                                                $groups = $q->choices->filter(fn($c) => !empty($c->group_name))->groupBy('group_name');
                                                            @endphp
                                                            @if($q->groups_count && $q->groups_count > 0 && $groups->count() > 0)
                                                                <div class="grid grid-cols-1 md:grid-cols-{{ min($groups->count(), 4) }} gap-4 ordering-groups-container">
                                                                    @foreach($groups as $groupName => $groupChoices)
                                                                        @if($groupChoices->count() === 1)
                                                                            {{-- Single choice per group: display slot next to group name --}}
                                                                            @php $singleChoice = $groupChoices->first(); @endphp
                                                                            <div class="p-3 bg-white border border-green-200 rounded-xl shadow-sm">
                                                                                <div class="flex items-center justify-between gap-3">
                                                                                    <h5 class="text-sm font-bold text-green-700">{{ $groupName }}</h5>
                                                                                    <div class="ordering-slot flex-col gap-1 flex-shrink-0" data-slot="{{ $singleChoice->id }}" data-group="{{ $groupName }}" style="width: 60px; height: 60px; aspect-ratio: 1;">
                                                                                        @if(!empty($singleChoice->choice_text))
                                                                                            <span class="ordering-slot-text text-xs sm:text-sm text-green-600 font-bold text-center px-1 leading-tight pointer-events-none">{{ $singleChoice->choice_text }}</span>
                                                                                        @else
                                                                                            <span class="ordering-slot-num">1</span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            {{-- Multiple choices per group: display in grid --}}
                                                                            <div class="p-3 bg-white border border-green-200 rounded-xl shadow-sm">
                                                                                <h5 class="text-sm font-bold text-green-700 text-center mb-2">{{ $groupName }}</h5>
                                                                                <div class="ordering-slots-grid ordering-slots-grouped" id="orderTarget-{{ $q->id }}-{{ Str::slug($groupName) }}" data-group="{{ $groupName }}">
                                                                                    @foreach ($groupChoices as $s => $choice)
                                                                                        <div class="ordering-slot flex-col gap-1" data-slot="{{ $choice->id }}" data-group="{{ $groupName }}">
                                                                                            @if(!empty($choice->choice_text))
                                                                                                <span class="ordering-slot-text text-xs sm:text-sm text-green-600 font-bold text-center px-1 leading-tight pointer-events-none">{{ $choice->choice_text }}</span>
                                                                                            @else
                                                                                                <span class="ordering-slot-num">{{ $s + 1 }}</span>
                                                                                            @endif
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @else
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
                                                            @endif
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
                                                @elseif ($q->answer_type === 'true_false' && $q->choices->count() > 0)
                                                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-green-100">
                                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 text-white text-xs shadow-md">
                                                            <i class="fas fa-tasks"></i>
                                                        </span>
                                                        <span class="text-sm text-green-800 font-bold">حدد هل كل عبارة صحيحة أم خاطئة</span>
                                                        <span class="text-[10px] text-gray-400 font-medium mr-auto">({{ $q->choices->count() }} عبارات)</span>
                                                    </div>
                                                    <div class="space-y-3">
                                                        @foreach ($q->choices as $idx => $choice)
                                                            <div class="group relative rounded-xl border border-gray-100 bg-gradient-to-br from-white to-gray-50/80 hover:border-green-200 hover:shadow-md transition-all duration-300 overflow-hidden">

                                                                <div class="p-3">
                                                                    <div class="flex flex-row gap-3 items-center">
                                                                        @if(!empty($choice->image))
                                                                            <div class="relative flex-shrink-0 overflow-hidden rounded-lg shadow-sm border border-gray-100 group-hover:shadow-md transition-shadow cursor-pointer" onclick="openTfLightbox('{{ asset('storage/' . $choice->image) }}')">
                                                                                <img src="{{ asset('storage/' . $choice->image) }}" alt="صورة توضيحية" class="w-20 h-20 object-cover rounded-lg transition-transform duration-300 group-hover:scale-105">
                                                                                <div class="absolute inset-0 flex items-center justify-center bg-black/0 hover:bg-black/30 transition-all duration-200 rounded-lg">
                                                                                    <i class="fas fa-search-plus text-white text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 drop-shadow"></i>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                        @if(!empty($choice->video))
                                                                            <div class="relative flex-shrink-0 overflow-hidden rounded-lg shadow-sm border border-gray-100 cursor-pointer" onclick="openTfVideoLightbox('{{ asset('storage/' . $choice->video) }}')">
                                                                                <video class="w-28 h-20 object-cover rounded-lg" muted>
                                                                                    <source src="{{ asset('storage/' . $choice->video) }}" type="video/mp4">
                                                                                </video>
                                                                                <div class="absolute inset-0 flex items-center justify-center bg-black/20 hover:bg-black/40 transition-all duration-200 rounded-lg">
                                                                                    <i class="fas fa-play-circle text-white text-xl drop-shadow"></i>
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        <div class="flex-grow flex items-center">
                                                                            <p class="text-gray-800 text-[13px] font-semibold leading-snug">{{ $choice->choice_text }}</p>
                                                                        </div>

                                                                        {{-- True / False toggle icons --}}
                                                                        <div class="flex flex-col gap-1.5 flex-shrink-0">
                                                                            <label class="cursor-pointer">
                                                                                <input type="radio" name="answer[{{ $choice->id }}]" value="1" class="hidden peer" required>
                                                                                <div class="w-9 h-9 flex items-center justify-center rounded-full border-2 border-green-200 bg-green-50/50 text-green-500 transition-all duration-200
                                                                                            peer-checked:bg-gradient-to-br peer-checked:from-green-500 peer-checked:to-emerald-500 peer-checked:text-white peer-checked:border-green-500 peer-checked:shadow-lg peer-checked:shadow-green-200/50 peer-checked:scale-110
                                                                                            hover:bg-green-100 hover:border-green-300 active:scale-90">
                                                                                    <i class="fas fa-check text-sm"></i>
                                                                                </div>
                                                                            </label>
                                                                            <label class="cursor-pointer">
                                                                                <input type="radio" name="answer[{{ $choice->id }}]" value="0" class="hidden peer" required>
                                                                                <div class="w-9 h-9 flex items-center justify-center rounded-full border-2 border-red-200 bg-red-50/50 text-red-400 transition-all duration-200
                                                                                            peer-checked:bg-gradient-to-br peer-checked:from-red-500 peer-checked:to-rose-500 peer-checked:text-white peer-checked:border-red-500 peer-checked:shadow-lg peer-checked:shadow-red-200/50 peer-checked:scale-110
                                                                                            hover:bg-red-100 hover:border-red-300 active:scale-90">
                                                                                    <i class="fas fa-times text-sm"></i>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif ($q->answer_type === 'vote' && $q->choices->count() > 0)
                                                    @php $voteMax = $q->vote_max_selections ?? 1; @endphp
                                                    @if($voteMax > 1)
                                                        <p class="text-xs text-green-700 font-medium mb-2">
                                                            <i class="fas fa-poll ml-1"></i> يمكنك اختيار حتى <strong>{{ $voteMax }}</strong> خيارات
                                                            <input type="hidden" class="home-vote-max" value="{{ $voteMax }}">
                                                        </p>
                                                    @endif
                                                    <div class="space-y-3">
                                                        @foreach ($q->choices as $choice)
                                                            <label class="group flex items-center gap-3 p-3 rounded-2xl border-2 border-gray-100 bg-white hover:border-green-300 hover:shadow-md cursor-pointer transition-all has-[:checked]:border-green-500 has-[:checked]:bg-gradient-to-r has-[:checked]:from-green-50 has-[:checked]:to-emerald-50 relative overflow-hidden">
                                                                @if($voteMax > 1)
                                                                    <input type="checkbox" name="answer[]" value="{{ $choice->id }}" class="hidden home-vote-checkbox" {{ $voteMax > 1 ? '' : 'required' }}>
                                                                    <div class="w-6 h-6 flex-shrink-0 rounded flex items-center justify-center border-2 border-gray-300 bg-gray-50 transition-all group-has-[:checked]:border-green-500 group-has-[:checked]:bg-green-500 z-10">
                                                                        <i class="fas fa-check text-white text-xs opacity-0 group-has-[:checked]:opacity-100 transition-opacity"></i>
                                                                    </div>
                                                                @else
                                                                    <input type="radio" name="answer" value="{{ $choice->id }}" class="hidden peer" required>
                                                                    <div class="w-6 h-6 flex-shrink-0 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-green-500 peer-checked:bg-green-500 transition-all z-10">
                                                                        <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                                    </div>
                                                                @endif
                                                                <div class="flex-grow flex items-center gap-3 z-10">
                                                                    @if($choice->image)
                                                                        <img src="{{ asset('storage/' . $choice->image) }}" class="w-12 h-12 md:w-14 md:h-14 object-cover rounded-xl shadow-sm border border-gray-100">
                                                                    @endif
                                                                    <div class="flex flex-col">
                                                                        <span class="text-gray-800 text-[13px] md:text-sm font-bold leading-snug">{{ $choice->choice_text }}</span>
                                                                    </div>
                                                                </div>
                                                                @if($choice->video)
                                                                    <div class="z-10 ml-auto flex-shrink-0" onclick="openTfVideoLightbox('{{ asset('storage/' . $choice->video) }}')">
                                                                        <div class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-gray-50 flex items-center justify-center border border-gray-200 hover:bg-green-50 hover:border-green-200 hover:text-green-600 transition-all shadow-sm">
                                                                            <i class="fas fa-play ml-0.5 text-gray-400 group-hover:text-green-500 transition-colors"></i>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @elseif ($q->answer_type === 'fill_blank' && $q->choices->count() > 0)
                                                    @php
                                                        $parts = preg_split('/___/', $q->question_text, 2);
                                                        $beforeBlank = $parts[0] ?? '';
                                                        $afterBlank  = $parts[1] ?? '';
                                                        $shuffledFillChoices = $q->choices->shuffle();
                                                    @endphp
                                                    <div class="fill-blank-wrapper" data-question-id="{{ $q->id }}">

                                                        {{-- Sentence with blank --}}
                                                        <div class="fill-blank-sentence" dir="rtl">
                                                            @if($beforeBlank)
                                                                <span class="fill-blank-text">{{ $beforeBlank }}</span>
                                                            @endif
                                                            <span class="fill-blank-drop" id="fillDrop-{{ $q->id }}"
                                                                onclick="fbClearDrop({{ $q->id }})">
                                                                <span class="fill-blank-placeholder">اسحب أو اضغط كلمة</span>
                                                            </span>
                                                            @if($afterBlank)
                                                                <span class="fill-blank-text">{{ $afterBlank }}</span>
                                                            @endif
                                                        </div>

                                                        {{-- Word chips --}}
                                                        <div class="fill-blank-chips" id="fillChips-{{ $q->id }}">
                                                            @foreach($shuffledFillChoices as $choice)
                                                                <button type="button"
                                                                    class="fill-blank-chip"
                                                                    data-choice-id="{{ $choice->id }}"
                                                                    data-question-id="{{ $q->id }}"
                                                                    onclick="fbSelectChip(this)">
                                                                    {{ $choice->choice_text }}
                                                                </button>
                                                            @endforeach
                                                        </div>

                                                        {{-- Hidden input carries selected choice id --}}
                                                        <input type="hidden" name="answer"
                                                            id="fillAnswer-{{ $q->id }}" value=""
                                                            class="fill-blank-answer-input">

                                                        <p class="fill-blank-hint" id="fillHint-{{ $q->id }}">
                                                            <i class="fas fa-hand-point-up ml-1"></i>
                                                            اختر الكلمة المناسبة لإتمام الجملة
                                                        </p>
                                                    </div>
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
                                            @elseif($q->answer_type === 'vote')
                                                <button type="submit" onclick="validateHomeQuiz(event, this)"
                                                    class="w-full sm:w-auto min-w-[150px] px-6 py-3.5 rounded-xl text-white font-bold text-sm inline-flex items-center justify-center gap-2 transition-all hover:opacity-90 hover:scale-[1.02] active:scale-[0.98] mt-4 shadow-lg shadow-blue-200"
                                                    style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                                                    <i class="fas fa-poll"></i>
                                                    إرسال التصويت
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
                                    @elseif (session('vote_submitted') && session('answered_question_id') == $q->id)
                                        <div class="rounded-2xl p-4 md:p-5 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 shadow-sm relative overflow-hidden mt-2">
                                            <div class="absolute top-0 right-0 left-0 h-1.5" style="background: linear-gradient(90deg, #22c55e, #16a34a);"></div>
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-green-100 flex-shrink-0 border border-green-200">
                                                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-green-800 text-sm">تم تسجيل صوتك بنجاح</h4>
                                                    <p class="text-xs text-green-600 mt-0.5">إليك نتائج التصويت الحالية:</p>
                                                </div>
                                            </div>
                                            
                                            <div class="home-vote-results space-y-3" data-url="{{ route('quiz-competitions.question.vote-results', [$activeQuizCompetition, $q]) }}">
                                                <p class="text-gray-400 text-xs text-center py-4"><i class="fas fa-spinner fa-spin mx-1"></i> تحميل النتائج...</p>
                                            </div>
                                        </div>
                                    @else
                                        @if($q->answer_type === 'vote')
                                            <div class="rounded-2xl p-4 md:p-5 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 shadow-sm relative overflow-hidden mt-2">
                                                <div class="absolute top-0 right-0 left-0 h-1.5" style="background: linear-gradient(90deg, #22c55e, #16a34a);"></div>
                                                <div class="flex items-center gap-3 mb-4">
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center bg-green-100 flex-shrink-0 border border-green-200">
                                                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold text-green-800 text-sm">تفضيلات التصويت الحالية</h4>
                                                        <p class="text-xs text-green-600 mt-0.5">لقد قمت بالتصويت مسبقاً، وإليك النتائج:</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="home-vote-results space-y-3" data-url="{{ route('quiz-competitions.question.vote-results', [$activeQuizCompetition, $q]) }}">
                                                    <p class="text-gray-400 text-xs text-center py-4"><i class="fas fa-spinner fa-spin mx-1"></i> تحميل النتائج...</p>
                                                </div>
                                            </div>
                                        @else
                                            <div
                                                class="rounded-xl p-4 bg-amber-50 border border-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                                <p class="text-amber-800 text-sm font-medium">
                                                    <i class="fas fa-check-circle text-amber-600 ml-1"></i>
                                                    لقد أجبت على هذا السؤال مسبقاً، يمكنك متابعة القرعة من هنا.
                                                </p>
                                                <a href="{{ route('quiz-competitions.question', [$activeQuizCompetition, $q]) }}"
                                                    class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white transition-all hover:opacity-90"
                                                    style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                                                    <i class="fas fa-trophy"></i>
                                                    متابعة القرعة
                                                </a>
                                            </div>
                                        @endif
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

    /* ────────────────────────────────────────────
       FILL IN THE BLANK – styles
    ──────────────────────────────────────────── */
    .fill-blank-wrapper {
        direction: rtl;
    }
    .fill-blank-sentence {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        font-size: 1.05rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.8;
        margin-bottom: 14px;
        padding: 12px 14px;
        background: linear-gradient(135deg, #f8fffe, #f0fdf4);
        border-radius: 16px;
        border: 2px solid #bbf7d0;
    }
    .fill-blank-text {
        color: #374151;
        font-weight: 600;
    }
    /* The blank drop zone */
    .fill-blank-drop {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 110px;
        min-height: 40px;
        padding: 4px 14px;
        border-radius: 12px;
        border: 2.5px dashed #6ee7b7;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }
    .fill-blank-drop::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, rgba(34,197,94,0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .fill-blank-drop.has-word {
        border-color: #22c55e;
        border-style: solid;
        background: linear-gradient(135deg, #dcfce7, #f0fdf4);
        box-shadow: 0 4px 16px rgba(34,197,94,0.25);
        animation: fillPopIn 0.35s cubic-bezier(0.34,1.56,0.64,1);
    }
    .fill-blank-drop.has-word::before { opacity: 1; }
    .fill-blank-drop.drag-over {
        border-color: #059669;
        background: #d1fae5;
        box-shadow: 0 0 20px rgba(34,197,94,0.4);
        transform: scale(1.05);
    }
    .fill-blank-placeholder {
        font-size: 0.72rem;
        color: #9ca3af;
        font-weight: 500;
        font-style: italic;
        pointer-events: none;
        transition: opacity 0.2s;
    }
    .fill-blank-drop.has-word .fill-blank-placeholder { display: none; }
    .fill-blank-word-inside {
        font-size: 0.95rem;
        font-weight: 800;
        color: #16a34a;
        pointer-events: none;
        animation: fillWordFadeIn 0.3s ease-out;
    }
    /* Chips container */
    .fill-blank-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
        margin-bottom: 10px;
    }
    .fill-blank-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 0.92rem;
        font-weight: 700;
        color: #1e40af;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border: 2px solid #bfdbfe;
        cursor: grab;
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        user-select: none;
        -webkit-user-select: none;
        position: relative;
        overflow: hidden;
    }
    .fill-blank-chip::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, rgba(59,130,246,0.15) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.2s;
    }
    .fill-blank-chip:hover {
        transform: translateY(-3px) scale(1.06);
        box-shadow: 0 8px 20px rgba(59,130,246,0.3);
        border-color: #3b82f6;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    }
    .fill-blank-chip:hover::after { opacity: 1; }
    .fill-blank-chip:active { transform: scale(0.94); cursor: grabbing; }
    .fill-blank-chip.selected {
        opacity: 0.38;
        pointer-events: none;
        transform: scale(0.88);
        filter: grayscale(0.4);
    }
    .fill-blank-chip.dragging { opacity: 0.35; }
    /* Hint */
    .fill-blank-hint {
        font-size: 0.72rem;
        color: #9ca3af;
        text-align: center;
        margin: 4px 0 0;
        transition: opacity 0.3s;
    }
    .fill-blank-hint.hidden-hint { opacity: 0; }
    /* Animations */
    @keyframes fillPopIn {
        0%   { transform: scale(0.7); opacity: 0; }
        70%  { transform: scale(1.1); }
        100% { transform: scale(1);   opacity: 1; }
    }
    @keyframes fillWordFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes chipShake {
        0%,100% { transform: translateX(0); }
        20%     { transform: translateX(-6px); }
        40%     { transform: translateX(6px); }
        60%     { transform: translateX(-4px); }
        80%     { transform: translateX(4px); }
    }
</style>

{{-- Lightbox Modal for True/False images & videos --}}
<div id="tfLightboxModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/70 backdrop-blur-sm" onclick="closeTfLightbox()" style="display:none;">
    <div class="relative max-w-[90vw] max-h-[90vh] animate-[tfZoomIn_0.25s_ease-out]" onclick="event.stopPropagation()">
        <button onclick="closeTfLightbox()" class="absolute -top-3 -right-3 w-8 h-8 flex items-center justify-center rounded-full bg-white text-gray-700 shadow-lg hover:bg-red-50 hover:text-red-500 transition-all z-10">
            <i class="fas fa-times text-sm"></i>
        </button>
        <img id="tfLightboxImg" src="" alt="صورة مكبرة" class="rounded-2xl shadow-2xl max-w-[90vw] max-h-[85vh] object-contain">
        <video id="tfLightboxVideo" controls autoplay class="rounded-2xl shadow-2xl max-w-[90vw] max-h-[85vh]" style="display:none;"></video>
    </div>
</div>

<style>
@keyframes tfZoomIn {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>

<script>
/* ═══════════════════════════════════════════════
   FILL IN THE BLANK – interaction JS
   ═══════════════════════════════════════════════ */

// State: tracks which chip is selected per question
var fbState = {};

/**
 * Called when a word chip is clicked.
 * Puts the word in the blank drop zone.
 */
function fbSelectChip(chip) {
    var qid = chip.getAttribute('data-question-id');
    var choiceId = chip.getAttribute('data-choice-id');
    var word = chip.textContent.trim();

    var drop = document.getElementById('fillDrop-' + qid);
    var answerInput = document.getElementById('fillAnswer-' + qid);
    var hint = document.getElementById('fillHint-' + qid);
    var allChips = document.querySelectorAll('.fill-blank-chip[data-question-id="' + qid + '"]');

    // Reset previous selection
    allChips.forEach(function(c) { c.classList.remove('selected'); });

    // Mark this chip selected
    chip.classList.add('selected');

    // Put word in drop zone
    // Remove old word span if any
    var oldWord = drop.querySelector('.fill-blank-word-inside');
    if (oldWord) oldWord.remove();

    var wordSpan = document.createElement('span');
    wordSpan.className = 'fill-blank-word-inside';
    wordSpan.textContent = word;
    drop.appendChild(wordSpan);

    drop.classList.add('has-word');
    answerInput.value = choiceId;
    fbState[qid] = choiceId;

    // Hide hint
    if (hint) hint.classList.add('hidden-hint');
}

/**
 * Called when the drop zone is clicked.
 * Clears the selected word back to chips.
 */
function fbClearDrop(qid) {
    var drop = document.getElementById('fillDrop-' + qid);
    var answerInput = document.getElementById('fillAnswer-' + qid);
    var hint = document.getElementById('fillHint-' + qid);
    var allChips = document.querySelectorAll('.fill-blank-chip[data-question-id="' + qid + '"]');

    // Only clear if there's a word
    if (!drop.classList.contains('has-word')) return;

    // Remove word span
    var oldWord = drop.querySelector('.fill-blank-word-inside');
    if (oldWord) oldWord.remove();

    drop.classList.remove('has-word');
    answerInput.value = '';
    fbState[qid] = null;

    // Re-enable all chips
    allChips.forEach(function(c) { c.classList.remove('selected'); });

    // Show hint again
    if (hint) hint.classList.remove('hidden-hint');
}

/* ── Interaction relies on click/tap which is fully mobile friendly ── */
// Drag and Drop support removed as it interferes with mobile touch.
// Mobile users simply tap the chip to move it to the blank.

/* ── Form submit validation for fill_blank ── */
function validateHomeQuiz(event, btn) {
    var form = btn.closest('form');
    if (!form) return;

    // Check fill_blank
    var fillInput = form.querySelector('.fill-blank-answer-input');
    if (fillInput !== null) {
        if (!fillInput.value || fillInput.value === '') {
            event.preventDefault();

            // Shake the chips to alert the user
            var qid = null;
            var wrapper = form.querySelector('.fill-blank-wrapper');
            if (wrapper) {
                qid = wrapper.getAttribute('data-question-id');
                var drop = document.getElementById('fillDrop-' + qid);
                if (drop) {
                    drop.style.animation = 'none';
                    drop.style.borderColor = '#f87171';
                    drop.style.background = '#fef2f2';
                    drop.style.boxShadow = '0 0 14px rgba(248,113,113,0.4)';
                    setTimeout(function() {
                        drop.style.borderColor = '';
                        drop.style.background = '';
                        drop.style.boxShadow = '';
                    }, 1400);
                }
                var chips = document.querySelectorAll('.fill-blank-chip[data-question-id="' + qid + '"]');
                chips.forEach(function(c) {
                    c.style.animation = 'chipShake 0.5s ease';
                    setTimeout(function() { c.style.animation = ''; }, 600);
                });
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'انتبه',
                    text: 'يرجى اختيار كلمة لملء الفراغ أولاً!',
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'حسناً',
                    toast: true, position: 'top-end',
                    showConfirmButton: false, timer: 2500
                });
            }
            return false;
        }
    }
    return true;
}

function openTfLightbox(src) {
    var modal = document.getElementById('tfLightboxModal');
    var img = document.getElementById('tfLightboxImg');
    var vid = document.getElementById('tfLightboxVideo');
    img.src = src;
    img.style.display = 'block';
    vid.style.display = 'none';
    vid.pause();
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function openTfVideoLightbox(src) {
    var modal = document.getElementById('tfLightboxModal');
    var img = document.getElementById('tfLightboxImg');
    var vid = document.getElementById('tfLightboxVideo');
    img.style.display = 'none';
    vid.src = src;
    vid.style.display = 'block';
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeTfLightbox() {
    var modal = document.getElementById('tfLightboxModal');
    var vid = document.getElementById('tfLightboxVideo');
    modal.style.display = 'none';
    vid.pause();
    vid.src = '';
    document.body.style.overflow = '';
}
document.addEventListener('DOMContentLoaded', function() {
    // Vote checkboxes max limit
    document.querySelectorAll('.home-vote-max').forEach(function(el) {
        var form = el.closest('form');
        var max = parseInt(el.value);
        if (form && max > 1) {
            var checkboxes = form.querySelectorAll('.home-vote-checkbox');
            checkboxes.forEach(function(cb) {
                cb.addEventListener('change', function() {
                    if (form.querySelectorAll('.home-vote-checkbox:checked').length > max) {
                        this.checked = false;
                        if(typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'info', title: 'تنبيه', text: 'لا يمكنك اختيار أكثر من ' + max + ' خيارات.', confirmButtonColor: '#22c55e', confirmButtonText: 'حسناً', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        } else {
                            alert('لا يمكنك اختيار أكثر من ' + max + ' خيارات.');
                        }
                    }
                });
            });
        }
    });

    // Load vote results
    document.querySelectorAll('.home-vote-results').forEach(function(container) {
        var url = container.getAttribute('data-url');
        if (url) {
            fetch(url)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!data.results || data.results.length === 0) {
                        container.innerHTML = '<p class="text-gray-400 text-xs text-center py-2">لا توجد أصوات بعد.</p>';
                        return;
                    }
                    var html = '';
                    data.results.forEach(function(r) {
                        html += '<div class="flex flex-col gap-1.5">';
                        html += '  <div class="flex items-center justify-between">';
                        html += '    <span class="text-xs font-bold text-gray-700">' + r.text + '</span>';
                        html += '    <span class="text-xs font-bold text-green-600">' + r.percent + '% <span class="text-[10px] text-gray-400 font-normal">(' + r.count + ')</span></span>';
                        html += '  </div>';
                        html += '  <div style="background: rgba(229,231,235,0.6); border-radius: 9999px; height: 8px; overflow: hidden;">';
                        html += '    <div style="height: 100%; border-radius: 9999px; width: 0%; transition: width 1s ease-out; background: linear-gradient(90deg, #22c55e, #16a34a);" data-width="' + r.percent + '%"></div>';
                        html += '  </div>';
                        html += '</div>';
                    });
                    container.innerHTML = html;
                    setTimeout(function() {
                        container.querySelectorAll('[data-width]').forEach(function(bar) {
                            bar.style.width = bar.getAttribute('data-width');
                        });
                    }, 100);
                })
                .catch(function() {
                    container.innerHTML = '<p class="text-red-400 text-xs text-center py-2">تعذر تحميل النتائج.</p>';
                });
        }
    });
});
</script>
@endpush
