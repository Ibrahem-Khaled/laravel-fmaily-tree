{{-- ================================================================
    RAMADAN QUIZ – Active competition(s) OR next event countdown
    ================================================================ --}}
    @php
        $activeQuizCompetitionsList = $activeQuizCompetitions ?? collect();

        // Default: quiz (questions) section title.
        $sectionTitle = 'المسابقة الرمضانية';

        if ($activeQuizCompetitionsList->isNotEmpty()) {
            // If all active competitions are "vote-only" => show vote title,
            // if all are "survey-only" => show survey title, otherwise keep quiz title.
            $allAreVote = $activeQuizCompetitionsList->every(
                fn ($c) => $c->questions->isNotEmpty()
                    && $c->questions->every(fn ($q) => $q->answer_type === 'vote')
            );

            $allAreSurvey = $activeQuizCompetitionsList->every(
                fn ($c) => $c->questions->isNotEmpty()
                    && $c->questions->every(fn ($q) => $q->answer_type === 'survey')
            );

            if ($allAreVote) {
                $sectionTitle = 'التصويت';
            } elseif ($allAreSurvey) {
                $sectionTitle = 'الاستبيان';
            }
        } elseif (isset($nextQuizEvent) && is_array($nextQuizEvent) && !empty($nextQuizEvent['section_title'])) {
            $sectionTitle = $nextQuizEvent['section_title'];
        }
    @endphp
    @if ($activeQuizCompetitionsList->isNotEmpty() || (isset($nextQuizEvent) && $nextQuizEvent))

    @push('styles')
    <style>
    /* ═══════════════════════════════════════════
       ORDERING
    ═══════════════════════════════════════════ */
    .ordering-source-grid,.ordering-slots-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:6px}
    .ordering-source-grid{min-height:50px}
    .ordering-slot{aspect-ratio:1;border:2px dashed #86efac;border-radius:12px;background:linear-gradient(135deg,#f0fdf4,#ecfdf5);display:flex;align-items:center;justify-content:center;overflow:hidden;transition:border-color .2s,background .2s,box-shadow .2s}
    .ordering-slot-num{font-size:18px;font-weight:700;color:#bbf7d0;pointer-events:none}
    .ordering-slot.has-image{border-style:solid;border-color:#22c55e;background:#fff;padding:0}
    .ordering-slot.has-image .ordering-slot-num,.ordering-slot.has-image .ordering-slot-text{display:none}
    .ordering-slot.drag-hover{border-color:#22c55e;background:#dcfce7;box-shadow:0 0 12px rgba(34,197,94,.3)}
    .ordering-slot .ordering-img-item{width:100%;aspect-ratio:1}
    .ordering-slot .ordering-img-item img{border:none;border-radius:10px}
    .ordering-img-item{cursor:grab;touch-action:none;user-select:none;-webkit-user-select:none;border-radius:12px;overflow:hidden;aspect-ratio:1}
    .ordering-img-item:active{cursor:grabbing}
    .ordering-img-item img{width:100%;height:100%;object-fit:cover;pointer-events:none;display:block;border-radius:12px;border:2px solid #d1d5db;transition:border-color .2s,box-shadow .2s}
    .ordering-source-grid .ordering-img-item:hover img{border-color:#22c55e;box-shadow:0 2px 10px rgba(34,197,94,.25)}
    .ordering-img-item.sortable-chosen{opacity:.6}
    .ordering-img-item.sortable-ghost{opacity:.15}
    .sortable-item.sortable-chosen{background:#f0fdf4;border-color:#22c55e;box-shadow:0 4px 15px rgba(34,197,94,.2)}
    .sortable-item.sortable-ghost{opacity:.3}
    @media(max-width:374px){.ordering-source-grid,.ordering-slots-grid{grid-template-columns:repeat(3,1fr)}}

    /* ═══════════════════════════════════════════
       FILL IN THE BLANK
    ═══════════════════════════════════════════ */
    .fill-blank-wrapper{direction:rtl}
    .fill-blank-sentence{display:flex;flex-wrap:wrap;align-items:center;gap:6px;font-size:1.05rem;font-weight:700;color:#1f2937;line-height:1.8;margin-bottom:14px;padding:12px 14px;background:linear-gradient(135deg,#f8fffe,#f0fdf4);border-radius:16px;border:2px solid #bbf7d0}
    .fill-blank-text{color:#374151;font-weight:600}
    .fill-blank-drop{display:inline-flex;align-items:center;justify-content:center;min-width:110px;min-height:40px;padding:4px 14px;border-radius:12px;border:2.5px dashed #6ee7b7;background:#fff;cursor:pointer;transition:all .3s cubic-bezier(.34,1.56,.64,1);position:relative;overflow:hidden}
    .fill-blank-drop.has-word{border-color:#22c55e;border-style:solid;background:linear-gradient(135deg,#dcfce7,#f0fdf4);box-shadow:0 4px 16px rgba(34,197,94,.25);animation:fillPopIn .35s cubic-bezier(.34,1.56,.64,1)}
    .fill-blank-drop.drag-over{border-color:#059669;background:#d1fae5;transform:scale(1.05)}
    .fill-blank-placeholder{font-size:.72rem;color:#9ca3af;font-weight:500;font-style:italic;pointer-events:none;transition:opacity .2s}
    .fill-blank-drop.has-word .fill-blank-placeholder{display:none}
    .fill-blank-word-inside{font-size:.95rem;font-weight:800;color:#16a34a;pointer-events:none;animation:fillWordFadeIn .3s ease-out}
    .fill-blank-chips{display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-bottom:10px}
    .fill-blank-chip{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:50px;font-size:.92rem;font-weight:700;color:#1e40af;background:linear-gradient(135deg,#eff6ff,#dbeafe);border:2px solid #bfdbfe;cursor:grab;transition:all .25s cubic-bezier(.34,1.56,.64,1);user-select:none;-webkit-user-select:none}
    .fill-blank-chip:hover{transform:translateY(-3px) scale(1.06);box-shadow:0 8px 20px rgba(59,130,246,.3);border-color:#3b82f6}
    .fill-blank-chip:active{transform:scale(.94);cursor:grabbing}
    .fill-blank-chip.selected{opacity:.38;pointer-events:none;transform:scale(.88);filter:grayscale(.4)}
    .fill-blank-hint{font-size:.72rem;color:#9ca3af;text-align:center;margin:4px 0 0;transition:opacity .3s}
    .fill-blank-hint.hidden-hint{opacity:0}
    @keyframes fillPopIn{0%{transform:scale(.7);opacity:0}70%{transform:scale(1.1)}100%{transform:scale(1);opacity:1}}
    @keyframes fillWordFadeIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
    @keyframes chipShake{0%,100%{transform:translateX(0)}20%{transform:translateX(-6px)}40%{transform:translateX(6px)}60%{transform:translateX(-4px)}80%{transform:translateX(4px)}}

    /* ═══════════════════════════════════════════
       VIDEO / IMAGE THUMBNAIL
    ═══════════════════════════════════════════ */
    .vthumb{position:relative;border-radius:14px;overflow:hidden;cursor:pointer;flex-shrink:0;border:2.5px solid rgba(0,0,0,.1);transition:border-color .2s,transform .2s,box-shadow .2s;display:block;background:#111}
    .vthumb:hover{border-color:#22c55e;transform:scale(1.04);box-shadow:0 6px 22px rgba(0,0,0,.25)}
    .vthumb video,.vthumb img{display:block;width:100%;height:100%;object-fit:cover;pointer-events:none}
    .vthumb-overlay{position:absolute;inset:0;background:rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;transition:background .2s}
    .vthumb:hover .vthumb-overlay{background:rgba(0,0,0,.52)}
    .vthumb-play{width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.95);display:flex;align-items:center;justify-content:center;box-shadow:0 3px 14px rgba(0,0,0,.3);transition:transform .2s}
    .vthumb:hover .vthumb-play{transform:scale(1.13)}
    .vthumb-play i{color:#16a34a;font-size:13px;margin-left:2px}

    /* ═══════════════════════════════════════════
       LIGHTBOX
    ═══════════════════════════════════════════ */
    #quizLightboxModal{position:fixed;inset:0;z-index:9999;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.84);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);padding:16px}
    #quizLightboxModal.lb-open{display:flex}
    .quiz-lb-inner{position:relative;max-width:92vw;max-height:90vh;animation:lbIn .22s cubic-bezier(.22,1,.36,1)}
    .quiz-lb-close{position:absolute;top:-15px;right:-15px;width:36px;height:36px;border-radius:50%;background:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 3px 12px rgba(0,0,0,.3);color:#374151;z-index:10;transition:background .15s,color .15s,transform .15s}
    .quiz-lb-close:hover{background:#fee2e2;color:#ef4444;transform:scale(1.1)}
    #quizLightboxImg{border-radius:18px;box-shadow:0 10px 50px rgba(0,0,0,.55);max-width:92vw;max-height:86vh;object-fit:contain;display:block}
    #quizLightboxVideo{border-radius:18px;box-shadow:0 10px 50px rgba(0,0,0,.55);max-width:92vw;max-height:86vh;display:none;outline:none;background:#000}
    #quizLightboxIframe{border-radius:18px;box-shadow:0 10px 50px rgba(0,0,0,.55);max-width:92vw;max-height:86vh;width:100%;height:100%;display:none;outline:none;background:#000}
    @keyframes lbIn{from{transform:scale(.82);opacity:0}to{transform:scale(1);opacity:1}}

    /* ═══════════════════════════════════════════
       VOTE SECTION – green/white brand identity
    ═══════════════════════════════════════════ */
    .vote-wrap{background:#fff;border-radius:28px;overflow:hidden;position:relative;border:1.5px solid #bbf7d0;box-shadow:0 0 40px rgba(34,197,94,.13)}
    .vote-wrap::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 55% at 15% 0%,rgba(34,197,94,.08) 0%,transparent 55%),radial-gradient(ellipse 55% 45% at 85% 100%,rgba(74,222,128,.07) 0%,transparent 50%);pointer-events:none}
    .vote-grid{position:absolute;inset:0;opacity:.03;background-image:linear-gradient(#16a34a 1px,transparent 1px),linear-gradient(90deg,#16a34a 1px,transparent 1px);background-size:28px 28px;pointer-events:none}
    .vote-header{position:relative;z-index:2;padding:20px 22px 16px;border-bottom:1px solid #dcfce7}
    .vote-live-badge{display:inline-flex;align-items:center;gap:7px;background:#fef2f2;border:1px solid #fecaca;color:#ef4444;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding:5px 12px 5px 8px;border-radius:50px;margin-bottom:11px}
    .vote-live-dot{width:7px;height:7px;border-radius:50%;background:#ef4444;animation:vpulse 1.4s ease-in-out infinite}
    @keyframes vpulse{0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.45)}50%{box-shadow:0 0 0 6px rgba(239,68,68,0)}}
    .vote-title-text{font-size:1.15rem;font-weight:800;color:#14532d;line-height:1.45;margin:0}
    .vote-sub-text{font-size:.78rem;color:#6b7280;margin-top:5px}
    .vote-body{position:relative;z-index:2;padding:18px 20px 22px}

    /* Choices */
    .vote-choice{position:relative;display:flex;align-items:center;gap:14px;padding:13px 15px;border-radius:16px;border:1.5px solid #d1fae5;background:#f0fdf4;cursor:pointer;transition:border-color .2s,background .2s,transform .15s,box-shadow .15s;margin-bottom:10px;overflow:hidden;-webkit-tap-highlight-color:transparent}
    .vote-choice::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(34,197,94,.1),rgba(74,222,128,.06));opacity:0;transition:opacity .25s;border-radius:inherit}
    .vote-choice:hover{border-color:#86efac;background:#ecfdf5;box-shadow:0 3px 14px rgba(34,197,94,.12)}
    .vote-choice:hover::before{opacity:1}
    .vote-choice.vote-sel{border-color:#22c55e;background:#dcfce7;box-shadow:0 4px 18px rgba(34,197,94,.18)}
    .vote-choice.vote-sel::before{opacity:1}
    .vote-choice:last-child{margin-bottom:0}

    /* Indicator */
    .vote-ind{width:22px;height:22px;border-radius:50%;border:2px solid #86efac;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:border-color .2s,background .2s}
    .vote-choice.vote-sel .vote-ind{border-color:#16a34a;background:#22c55e}
    .vote-ind-dot{width:8px;height:8px;border-radius:50%;background:#fff;opacity:0;transform:scale(.4);transition:opacity .2s,transform .2s}
    .vote-choice.vote-sel .vote-ind-dot{opacity:1;transform:scale(1)}
    .vote-ind-sq{border-radius:6px}
    .vote-ind-sq-check{display:none;color:#fff;font-size:11px}
    .vote-choice.vote-sel .vote-ind-sq{background:#22c55e;border-color:#16a34a}
    .vote-choice.vote-sel .vote-ind-sq-check{display:block}

    /* Choice image */
    .vote-cimg{width:54px;height:54px;border-radius:12px;object-fit:cover;flex-shrink:0;border:2px solid #d1fae5;transition:border-color .2s}
    .vote-choice.vote-sel .vote-cimg{border-color:#22c55e}

    /* Choice text */
    .vote-cname{font-size:.9rem;font-weight:700;color:#14532d;line-height:1.3}

    /* Video thumb inside vote card */
    .vote-vthumb{position:relative;width:90px;height:68px;border-radius:11px;overflow:hidden;cursor:pointer;flex-shrink:0;background:#052e16;border:2px solid #bbf7d0;transition:border-color .2s,transform .15s,box-shadow .2s;margin-right:auto}
    .vote-vthumb:hover{border-color:#22c55e;transform:scale(1.06);box-shadow:0 4px 14px rgba(34,197,94,.25)}
    .vote-vthumb video{width:100%;height:100%;object-fit:cover;display:block;pointer-events:none}
    .vote-vthumb-ov{position:absolute;inset:0;background:rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center;transition:background .15s}
    .vote-vthumb:hover .vote-vthumb-ov{background:rgba(0,0,0,.45)}
    .vote-vthumb-play{width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,.95);display:flex;align-items:center;justify-content:center;transition:transform .15s;box-shadow:0 2px 8px rgba(0,0,0,.2)}
    .vote-vthumb:hover .vote-vthumb-play{transform:scale(1.15)}
    .vote-vthumb-play i{color:#16a34a;font-size:8px;margin-left:1px}

    /* Submit */
    .vote-submit{display:flex;align-items:center;justify-content:center;gap:9px;width:100%;padding:14px 24px;border-radius:16px;border:none;cursor:pointer;font-size:.95rem;font-weight:800;color:#fff;background:linear-gradient(135deg,#22c55e,#16a34a);box-shadow:0 4px 22px rgba(34,197,94,.3);transition:opacity .2s,transform .15s,box-shadow .2s;margin-top:18px}
    .vote-submit:hover{opacity:.91;transform:translateY(-1px);box-shadow:0 6px 30px rgba(34,197,94,.4)}
    .vote-submit:active{transform:scale(.97)}

    /* Inputs */
    .vote-lbl{display:block;font-size:.73rem;font-weight:600;color:#4b5563;margin-bottom:4px}
    .vote-inp{width:100%;padding:10px 14px;border-radius:12px;border:1.5px solid #d1fae5;background:#f8fffe;color:#1f2937;font-size:.87rem;outline:none;transition:border-color .2s,background .2s,box-shadow .2s;box-sizing:border-box}
    .vote-inp::placeholder{color:#9ca3af}
    .vote-inp:focus{border-color:#22c55e;background:#fff;box-shadow:0 0 0 3px rgba(34,197,94,.12)}

    /* Results */
    .vote-rrow{margin-bottom:11px}
    .vote-rrow:last-child{margin-bottom:0}
    .vote-rmeta{display:flex;align-items:center;justify-content:space-between;margin-bottom:5px}
    .vote-rname{font-size:.82rem;font-weight:700;color:#14532d}
    .vote-rpct{font-size:.79rem;font-weight:700;color:#16a34a}
    .vote-rcnt{font-size:.69rem;color:#9ca3af;margin-right:3px}
    .vote-rbg{height:8px;border-radius:999px;background:#d1fae5;overflow:hidden}
    .vote-rfill{height:100%;border-radius:999px;background:linear-gradient(90deg,#22c55e,#16a34a);width:0%;transition:width 1.1s cubic-bezier(.22,1,.36,1)}

    /* Voted banner */
    .vote-ok-banner{display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:14px;background:#f0fdf4;border:1px solid #bbf7d0;margin-bottom:15px}
    .vote-ok-icon{width:32px;height:32px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid #86efac}
    .vote-ok-icon i{color:#16a34a;font-size:13px}
    .vote-ok-title{font-size:.81rem;font-weight:700;color:#14532d}
    .vote-ok-sub{font-size:.71rem;color:#6b7280;margin-top:2px}

    /* Max badge */
    .vote-max-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:50px;font-size:.74rem;font-weight:700;color:#16a34a;margin-bottom:13px}

    /* Reg hint */
    .vote-reg-hint{display:flex;align-items:flex-start;gap:8px;padding:9px 12px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:11px;margin-bottom:13px;font-size:.75rem;color:#2563eb}

    /* Countdown units */
    .cd-box{min-width:56px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:16px;padding:10px 6px 8px;text-align:center}
    .cd-num{font-size:1.55rem;font-weight:800;color:#16a34a;line-height:1}
    .cd-lbl{font-size:.67rem;color:#6b7280;margin-top:4px}

    /* ═══════════════════════════════════════════
       SURVEY ITEM ROW (media inline-left + content)
    ═══════════════════════════════════════════ */
    .survey-item-row{display:flex;align-items:flex-start;gap:10px;direction:rtl;flex-direction:row-reverse}
    .survey-item-media{position:relative;flex-shrink:0;width:80px;height:80px;border-radius:12px;overflow:hidden;cursor:pointer;border:2px solid #bbf7d0;background:#052e16;transition:border-color .2s,transform .15s,box-shadow .2s}
    .survey-item-media:hover{border-color:#22c55e;transform:scale(1.06);box-shadow:0 4px 14px rgba(34,197,94,.25)}
    .survey-item-media video,.survey-item-media img{display:block;width:100%;height:100%;object-fit:cover;pointer-events:none}
    .survey-item-media .sim-overlay{position:absolute;inset:0;background:rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center;transition:background .15s}
    .survey-item-media:hover .sim-overlay{background:rgba(0,0,0,.46)}
    .survey-item-media .sim-play{width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,.95);display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.22);transition:transform .15s}
    .survey-item-media:hover .sim-play{transform:scale(1.14)}
    .survey-item-media .sim-play i{color:#16a34a;font-size:10px;margin-left:1px}
    .survey-item-media.sim-img .sim-play i{margin-left:0;font-size:10px}
    .survey-item-content{flex:1;min-width:0}

    /* ═══════════════════════════════════════════
       STAR RATING – responsive
    ═══════════════════════════════════════════ */
    .star-rating{display:flex;flex-direction:row-reverse;flex-wrap:nowrap;justify-content:flex-start;gap:clamp(2px,1vw,6px);margin:6px 0 4px;width:100%}
    .star-rating input{display:none}
    .star-rating label{font-size:clamp(18px,5vw,28px);color:#d1d5db;cursor:pointer;transition:color .15s,transform .15s;line-height:1;flex-shrink:0}
    .star-rating label:hover,.star-rating label:hover~label,.star-rating input:checked~label{color:#f59e0b}
    .star-rating label:hover{transform:scale(1.2)}
    .star-rating input:checked+label{transform:scale(1.15)}
    .star-val-display{font-size:.75rem;color:#6b7280;margin-top:2px}
    </style>
    @endpush

        <section class="py-3 md:py-6 lg:py-8 relative overflow-hidden"
            style="background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 50%,#bbf7d0 100%)">

            {{-- Decorative blobs --}}
            <div class="absolute top-0 right-0 w-72 h-72 opacity-5 pointer-events-none" style="animation:float 6s ease-in-out infinite">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#22c55e" d="M44.9,-76.6C59.3,-69.5,72.8,-59.9,80.3,-46.7C87.8,-33.5,89.3,-16.8,88.3,-0.6C87.3,15.6,83.8,31.2,76.3,44.5C68.8,57.8,57.3,68.8,43.3,75.3C29.3,81.8,14.7,83.8,-0.6,84.8C-15.9,85.8,-31.8,85.8,-45.8,79.3C-59.8,72.8,-71.9,59.8,-79.3,44.5C-86.7,29.2,-89.3,11.6,-88.3,-5.9C-87.3,-23.4,-82.7,-46.8,-71.3,-64.3C-59.9,-81.8,-41.7,-93.4,-22.8,-95.8C-3.9,-98.2,15.7,-91.4,34.1,-82.3Z" transform="translate(100 100)"/>
                </svg>
            </div>
            <div class="absolute bottom-0 left-0 w-64 h-64 opacity-5 pointer-events-none" style="animation:float 5s ease-in-out infinite 1s">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#4ade80" d="M37.5,-65.2C48.7,-57.8,57.8,-47.3,64.3,-35.1C70.8,-22.9,74.7,-9,75.6,5.7C76.5,20.4,74.4,36,67.1,48.6C59.8,61.2,47.3,70.8,33.2,75.7C19.1,80.6,3.4,80.8,-12.1,78.1C-27.6,75.4,-42.9,69.8,-55.3,60.2C-67.7,50.6,-77.2,37,-80.3,21.9C-83.4,6.8,-80.1,-9.8,-74.1,-25.3C-68.1,-40.8,-59.4,-55.2,-47.2,-62.2C-35,-69.2,-19.3,-68.8,-5.4,-60.5Z" transform="translate(100 100)"/>
                </svg>
            </div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">

                {{-- ══════════════════════════════════════
                     ACTIVE COMPETITION(S)
                ══════════════════════════════════════ --}}
                @if ($activeQuizCompetitionsList->isNotEmpty())
                    {{-- Section heading (once) --}}
                    <div class="text-right mb-3 md:mb-5">
                        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">{{ $sectionTitle }}</h2>
                    </div>

                    <div id="activeQuizSection" class="space-y-6">
                    @foreach ($activeQuizCompetitionsList as $activeQuizCompetition)
                        @php
                            $isVoteOnly = $activeQuizCompetition->questions->isNotEmpty()
                                && $activeQuizCompetition->questions->every(fn($q) => $q->answer_type === 'vote');
                            $isSurveyOnly = $activeQuizCompetition->questions->isNotEmpty()
                                && $activeQuizCompetition->questions->every(fn($q) => $q->answer_type === 'survey');
                            $questionsVisibleAt = $activeQuizCompetition->getQuestionsVisibleAt();
                            $showQuestionsOnHome = !$questionsVisibleAt || now()->gte($questionsVisibleAt);
                            $aqSuffix = '-' . $activeQuizCompetition->id;
                        @endphp

                        <div class="mb-3 md:mb-5 js-active-quiz-block" id="activeQuizSection-{{ $activeQuizCompetition->id }}">

                        {{-- ▌VOTE-ONLY → dark card --}}
                        @if ($isVoteOnly)
                            <div class="vote-wrap">
                                @if ($activeQuizCompetitionsList->count() > 1)
                                    <div class="vote-header" style="border-bottom:none;padding-bottom:0">
                                        <p class="vote-title-text mb-0" style="font-size:1rem">{{ $activeQuizCompetition->title }}</p>
                                    </div>
                                @endif
                                <div class="vote-grid"></div>

                                <div class="vote-header">
                                    <div class="vote-live-badge">
                                        <span class="vote-live-dot"></span>
                                        تصويت مباشر
                                    </div>

                                    {{-- Timer --}}
                                    <div class="flex items-center gap-3 mb-3">
                                        <i class="fas fa-hourglass-half" style="color:#f59e0b;font-size:12px"></i>
                                        <span style="color:#6b7280;font-size:.76rem">ينتهي بعد:</span>
                                        <div class="flex gap-1" id="activeQuestionTimer{{ $aqSuffix }}">
                                            <span style="background:#dcfce7;color:#14532d;border-radius:8px;padding:3px 9px;font-weight:700;font-size:.8rem;min-width:2rem;text-align:center" id="aq-hours{{ $aqSuffix }}">00</span>
                                            <span style="color:#86efac;font-weight:700">:</span>
                                            <span style="background:#dcfce7;color:#14532d;border-radius:8px;padding:3px 9px;font-weight:700;font-size:.8rem;min-width:2rem;text-align:center" id="aq-minutes{{ $aqSuffix }}">00</span>
                                            <span style="color:#86efac;font-weight:700">:</span>
                                            <span style="background:#dcfce7;color:#14532d;border-radius:8px;padding:3px 9px;font-weight:700;font-size:.8rem;min-width:2rem;text-align:center" id="aq-seconds{{ $aqSuffix }}">00</span>
                                        </div>
                                        <input type="hidden" id="aqEndTime{{ $aqSuffix }}" value="{{ $activeQuizCompetition->end_at->getTimestamp() * 1000 }}">
                                    </div>

                                    {{-- Errors --}}
                                    @if (session('error'))
                                        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:10px 14px;margin-bottom:10px;display:flex;align-items:center;gap:9px">
                                            <i class="fas fa-exclamation-circle" style="color:#ef4444"></i>
                                            <span style="color:#b91c1c;font-size:.83rem">{{ session('error') }}</span>
                                        </div>
                                    @endif
                                    @if ($errors->any())
                                        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:10px 14px;margin-bottom:10px">
                                            @foreach ($errors->all() as $err)
                                                <p style="color:#b91c1c;font-size:.79rem;margin:2px 0">· {{ $err }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- Questions --}}
                                <div id="activeQuizQuestionsBlock{{ $aqSuffix }}"
                                    @if (!$showQuestionsOnHome) style="display:none" @endif>

                                    @foreach ($activeQuizCompetition->questions->where('answer_type','vote') as $q)
                                        @php
                                            $cooldownHours  = 2;
                                            $lastAnsweredAt = session('quiz_answered_' . $q->id);
                                            $canAnswerThis  = !$lastAnsweredAt || now()->diffInHours(\Carbon\Carbon::parse($lastAnsweredAt)) >= $cooldownHours;
                                            $voteMax        = $q->vote_max_selections ?? 1;
                                        @endphp

                                        <div class="vote-body" @if (!$loop->first) style="border-top:1px solid #dcfce7" @endif>

                                            @if ($q->description)
                                                <div class="text-sm mb-3 quiz-description" style="color:#4b5563">{!! $q->description !!}</div>
                                            @endif
                                            <p class="vote-title-text mb-1">{!! $q->question_text !!}</p>
                                            <p class="vote-sub-text mb-4">
                                                {{ $q->choices->count() }} خيارات متاحة
                                                @if ($voteMax > 1) · اختر حتى {{ $voteMax }} @endif
                                            </p>

                                            {{-- Draw-only --}}
                                            @if ($activeQuizCompetition->show_draw_only)
                                                <div style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:15px;padding:13px 15px">
                                                    <p style="color:#166534;font-size:.83rem">
                                                        <i class="fas fa-info-circle" style="color:#22c55e;margin-left:5px"></i>
                                                        باب التصويت مغلق — تابع نتائج القرعة
                                                    </p>
                                                    <a href="{{ route('quiz-competitions.question', [$activeQuizCompetition, $q]) }}"
                                                        style="display:inline-flex;align-items:center;gap:7px;padding:9px 20px;border-radius:12px;background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;font-size:.83rem;font-weight:700;text-decoration:none">
                                                        <i class="fas fa-trophy"></i> متابعة القرعة
                                                    </a>
                                                </div>

                                            {{-- Can answer --}}
                                            @elseif ($canAnswerThis)
                                                <form action="{{ route('quiz-competitions.store-answer', [$activeQuizCompetition, $q]) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="source" value="home">

                                                    {{-- Participant fields --}}
                                                    @if ($q->require_prior_registration)
                                                        <div class="vote-reg-hint">
                                                            <i class="fas fa-info-circle" style="margin-top:1px;flex-shrink:0"></i>
                                                            <span>هذه المشاركة للمشاركين السابقين فقط — أدخل رقم هاتفك للتحقق</span>
                                                        </div>
                                                        <div style="margin-bottom:13px">
                                                            <label class="vote-lbl">رقم الهاتف <span style="color:#f87171">*</span></label>
                                                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                                                pattern="[0-9]{10}" minlength="10" maxlength="10"
                                                                placeholder="05xxxxxxxx" dir="ltr" style="text-align:right"
                                                                class="vote-inp" autocomplete="off">
                                                        </div>
                                                    @else
                                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px">
                                                            <div>
                                                                <label class="vote-lbl">الاسم</label>
                                                                <input type="text" name="name" value="{{ old('name') }}"
                                                                    placeholder="الاسم الكامل" class="vote-inp">
                                                            </div>
                                                            <div>
                                                                <label class="vote-lbl">رقم الهاتف <span style="color:#f87171">*</span></label>
                                                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                                                    pattern="[0-9]{10}" minlength="10" maxlength="10"
                                                                    placeholder="05xxxxxxxx" dir="ltr" style="text-align:right"
                                                                    class="vote-inp">
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom:12px">
                                                            <label class="vote-lbl">اسم الأم (للمستخدمين من الأنساب)</label>
                                                            <input type="text" name="mother_name" value="{{ old('mother_name') }}"
                                                                placeholder="ينتهي باسم السريع" class="vote-inp">
                                                            <input type="hidden" name="is_from_ancestry" value="1">
                                                        </div>
                                                    @endif

                                                    @if ($voteMax > 1)
                                                        <div class="vote-max-badge">
                                                            <i class="fas fa-poll" style="font-size:11px"></i>
                                                            اختر حتى {{ $voteMax }} خيارات
                                                            <input type="hidden" class="home-vote-max" value="{{ $voteMax }}">
                                                        </div>
                                                    @endif

                                                    {{-- Choices --}}
                                                    @foreach ($q->choices as $choice)
                                                        <label class="vote-choice" id="vc-{{ $q->id }}-{{ $choice->id }}">
                                                            @if ($voteMax > 1)
                                                                <input type="checkbox" name="answer[]" value="{{ $choice->id }}"
                                                                    class="hidden home-vote-checkbox" onchange="voteToggle(this)">
                                                                <div class="vote-ind vote-ind-sq" id="vi-{{ $q->id }}-{{ $choice->id }}">
                                                                    <i class="fas fa-check vote-ind-sq-check"></i>
                                                                </div>
                                                            @else
                                                                <input type="radio" name="answer" value="{{ $choice->id }}"
                                                                    class="hidden" required onchange="voteToggle(this)">
                                                                <div class="vote-ind" id="vi-{{ $q->id }}-{{ $choice->id }}">
                                                                    <div class="vote-ind-dot"></div>
                                                                </div>
                                                            @endif

                                                            @if ($choice->image)
                                                                <img src="{{ asset('storage/' . $choice->image) }}" class="vote-cimg" alt="{{ $choice->choice_text }}">
                                                            @endif

                                                            <div style="flex:1;min-width:0">
                                                                <p class="vote-cname">{{ $choice->choice_text }}</p>
                                                            </div>

                                                            @if ($choice->video)
                                                                <div class="vote-vthumb"
                                                                    onclick="event.preventDefault();event.stopPropagation();quizOpenVideo('{{ asset('storage/' . $choice->video) }}')">
                                                                    <video muted preload="metadata" src="{{ asset('storage/' . $choice->video) }}#t=0.5"></video>
                                                                    <div class="vote-vthumb-ov">
                                                                        <div class="vote-vthumb-play"><i class="fas fa-play"></i></div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </label>
                                                    @endforeach

                                                    <button type="submit" class="vote-submit" onclick="return validateHomeQuiz(event,this)">
                                                        <i class="fas fa-paper-plane" style="font-size:13px"></i>
                                                        إرسال التصويت
                                                    </button>
                                                </form>

                                            {{-- Already voted --}}
                                            @else
                                                @php $isJustVoted = session('vote_submitted') && session('answered_question_id') == $q->id; @endphp
                                                <div class="vote-ok-banner">
                                                    <div class="vote-ok-icon">
                                                        <i class="fas {{ $isJustVoted ? 'fa-check' : 'fa-poll' }}"></i>
                                                    </div>
                                                    <div>
                                                        <p class="vote-ok-title">{{ $isJustVoted ? 'تم تسجيل صوتك بنجاح' : 'لقد صوّتت مسبقاً' }}</p>
                                                        <p class="vote-ok-sub">إليك نتائج التصويت الحالية</p>
                                                    </div>
                                                </div>
                                                <div class="home-vote-results"
                                                    data-url="{{ route('quiz-competitions.question.vote-results', [$activeQuizCompetition, $q]) }}"
                                                    data-theme="green">
                                                    <p style="color:#9ca3af;font-size:.77rem;text-align:center;padding:10px 0">
                                                        <i class="fas fa-spinner fa-spin" style="margin-left:5px"></i> تحميل النتائج...
                                                    </p>
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach

                                </div>{{-- /questions block --}}
                            </div>{{-- /vote-wrap --}}

                        {{-- ▌MIXED / NON-VOTE → standard green card --}}
                        @else
                            <div class="glass-card rounded-3xl p-3 md:p-6 shadow-lg relative overflow-hidden"
                                style="box-shadow:0 0 40px rgba(34,197,94,.2)">
                                <div class="absolute top-0 right-0 left-0 h-1.5" style="background:linear-gradient(90deg,#22c55e,#16a34a,#22c55e)"></div>

                                @if ($activeQuizCompetitionsList->count() > 1)
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-gray-700 font-bold text-sm">{{ $activeQuizCompetition->title }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between mb-2">
                                    @if (!$isSurveyOnly)
                                        <span class="inline-flex items-center gap-2 bg-red-50 text-red-600 text-xs font-bold px-3 py-1.5 rounded-full border border-red-200">
                                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                            مسابقة جارية الآن
                                        </span>
                                    @endif
                                    <span class="text-gray-500 text-xs">
                                        <i class="fas fa-question-circle text-green-500 ml-1"></i>
                                        @php
                                            $aqQuestionsCount = $activeQuizCompetition->questions->count();
                                            $aqQuestionsLabel = $aqQuestionsCount === 1 ? 'سؤال' : 'أسئلة';
                                        @endphp
                                        {{ $aqQuestionsCount }} {{ $aqQuestionsLabel }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 mb-3 text-gray-500 text-sm">
                                    <i class="fas fa-hourglass-half text-amber-500"></i>
                                    <span>ينتهي بعد:</span>
                                    <div class="flex gap-1 flex-row" id="activeQuestionTimer{{ $aqSuffix }}">
                                        <span class="bg-gray-100 rounded-lg px-2 py-1 text-gray-800 font-bold text-sm min-w-[2rem] text-center" id="aq-seconds{{ $aqSuffix }}">00</span>
                                        <span class="text-gray-400 font-bold">:</span>
                                        <span class="bg-gray-100 rounded-lg px-2 py-1 text-gray-800 font-bold text-sm min-w-[2rem] text-center" id="aq-minutes{{ $aqSuffix }}">00</span>
                                        <span class="text-gray-400 font-bold">:</span>
                                        <span class="bg-gray-100 rounded-lg px-2 py-1 text-gray-800 font-bold text-sm min-w-[2rem] text-center" id="aq-hours{{ $aqSuffix }}">00</span>
                                    </div>
                                    <input type="hidden" id="aqEndTime{{ $aqSuffix }}" value="{{ $activeQuizCompetition->end_at->getTimestamp() * 1000 }}">
                                </div>

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

                                {{-- Pre-reveal --}}
                                @if (!$showQuestionsOnHome && $questionsVisibleAt)
                                    <input type="hidden" id="aqQuestionsVisibleAt{{ $aqSuffix }}" value="{{ $questionsVisibleAt->getTimestamp() * 1000 }}">
                                    @if ($activeQuizCompetition->questions->filter(fn($q) => !empty($q->description))->isNotEmpty())
                                        <div id="activeQuizDescriptionsOnlyBlock{{ $aqSuffix }}" class="space-y-4 mb-3">
                                            @foreach ($activeQuizCompetition->questions as $q)
                                                @if ($q->description)
                                                    <div class="rounded-2xl p-3 border-2 border-green-100 bg-white/80 shadow-sm">
                                                        <div class="text-gray-600 text-sm quiz-description">{!! $q->description !!}</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    <div id="activeQuizQuestionsCountdown{{ $aqSuffix }}"
                                        class="rounded-2xl p-4 mb-3 bg-amber-50 border-2 border-amber-200 flex flex-wrap items-center justify-center gap-2">
                                        <i class="fas fa-clock text-amber-600"></i>
                                        <span class="text-amber-800 font-medium text-sm">نص السؤال والإجابة تظهران بعد:</span>
                                        <span id="aqQuestionsSeconds{{ $aqSuffix }}" class="bg-amber-200 text-amber-900 font-bold text-lg min-w-[3rem] text-center rounded-lg px-2 py-1">0</span>
                                        <span class="text-amber-700 text-sm">ثانية</span>
                                    </div>
                                @endif

                                <div id="activeQuizQuestionsBlock{{ $aqSuffix }}" class="space-y-4 mb-3 js-active-quiz-questions-block"
                                    @if (!$showQuestionsOnHome) style="display:none" @endif>

                                    @if ($activeQuizCompetition->questions->contains(fn($q) => $q->answer_type !== 'vote'))
                                        @php
                                            $aqQuestionsCountForTitle = $activeQuizCompetition->questions->count();
                                            $aqTitleNoun = $aqQuestionsCountForTitle === 1 ? 'سؤال' : 'أسئلة';
                                            $aqTitleType = $isSurveyOnly ? 'الاستبيان' : 'المسابقة';
                                        @endphp
                                        <h4 class="text-sm font-bold text-gray-600 mb-2">{{ $aqTitleNoun }} {{ $aqTitleType }} — أجب هنا:</h4>
                                    @endif

                                    @foreach ($activeQuizCompetition->questions as $q)
                                        @php
                                            $cooldownHours  = 2;
                                            $lastAnsweredAt = session('quiz_answered_' . $q->id);
                                            $canAnswerThis  = !$lastAnsweredAt || now()->diffInHours(\Carbon\Carbon::parse($lastAnsweredAt)) >= $cooldownHours;
                                        @endphp

                                        <div class="rounded-2xl p-3 border-2 border-green-100 bg-white/80 shadow-sm">

                                            @if ($q->description)
                                                <div class="text-gray-600 text-sm mb-2 quiz-description">{!! $q->description !!}</div>
                                            @endif
                                            <div class="text-gray-800 font-bold text-base mb-2 question-text"
                                                @if ($q->answer_type === 'fill_blank') style="display:none" @endif>
                                                {!! $q->question_text !!}
                                            </div>

                                            @if ($activeQuizCompetition->show_draw_only)
                                                @if ($q->answer_type === 'survey')
                                                    <div class="rounded-xl p-4 bg-green-50 border border-green-200">
                                                        <p class="text-green-800 text-sm font-medium">
                                                            <i class="fas fa-clipboard-check text-green-600 ml-1"></i>
                                                            باب الاستبيان مغلق حالياً. شكراً لاهتمامك.
                                                        </p>
                                                    </div>
                                                @else
                                                    <div class="rounded-xl p-4 bg-green-50 border border-green-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                                        <p class="text-green-800 text-sm font-medium">
                                                            <i class="fas fa-info-circle text-green-600 ml-1"></i>
                                                            باب الإجابة مغلق حالياً، يمكنك متابعة فرز النتائج والقرعة من هنا.
                                                        </p>
                                                        <a href="{{ route('quiz-competitions.question', [$activeQuizCompetition, $q]) }}"
                                                            class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white transition-all hover:opacity-90"
                                                            style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                                                            <i class="fas fa-trophy"></i> متابعة القرعة
                                                        </a>
                                                    </div>
                                                @endif

                                            @elseif ($canAnswerThis)
                                                <form action="{{ route('quiz-competitions.store-answer', [$activeQuizCompetition, $q]) }}"
                                                    method="POST" class="space-y-4">
                                                    @csrf
                                                    <input type="hidden" name="source" value="home">

                                                    @if ($q->require_prior_registration)
                                                        <div class="mb-4">
                                                            <p class="text-xs text-blue-600 mb-2 font-medium">
                                                                <i class="fas fa-info-circle mx-1"></i>
                                                                هذه المشاركة للمشاركين السابقين فقط. أدخل رقم هاتفك للتحقق.
                                                            </p>
                                                            <label class="block text-gray-600 text-xs mb-1 font-medium">رقم الهاتف <span class="text-red-500">*</span></label>
                                                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                                                pattern="[0-9]{10}" minlength="10" maxlength="10"
                                                                placeholder="05xxxxxxxx" dir="ltr" style="text-align:right"
                                                                class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500"
                                                                autocomplete="off">
                                                        </div>
                                                    @else
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                            <div>
                                                                <label class="block text-gray-600 text-xs mb-1 font-medium">
                                                                    الاسم @if ($q->answer_type !== 'vote')<span class="text-red-500">*</span>@endif
                                                                </label>
                                                                <input type="text" name="name" value="{{ old('name') }}"
                                                                    {{ $q->answer_type !== 'vote' ? 'required' : '' }}
                                                                    placeholder="الاسم الكامل"
                                                                    class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500">
                                                            </div>
                                                            <div>
                                                                <label class="block text-gray-600 text-xs mb-1 font-medium">رقم الهاتف <span class="text-red-500">*</span></label>
                                                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                                                    pattern="[0-9]{10}" minlength="10" maxlength="10"
                                                                    placeholder="05xxxxxxxx" dir="ltr" style="text-align:right"
                                                                    class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500">
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="block text-gray-600 text-xs mb-1 font-medium">اسم الأم (للمستخدمين من الأنساب)</label>
                                                            <input type="text" name="mother_name" value="{{ old('mother_name') }}"
                                                                placeholder="ينتهي باسم السريع"
                                                                class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500">
                                                            <input type="hidden" name="is_from_ancestry" value="1">
                                                        </div>
                                                    @endif

                                                    <div>
                                                        <label class="block text-gray-600 text-xs mb-2 font-medium">الإجابة <span class="text-red-500">*</span></label>

                                                        {{-- ORDERING --}}
                                                        @if ($q->answer_type === 'ordering' && $q->choices->count() > 0)
                                                            @php $shuffledChoices = $q->choices->shuffle(); $hasImages = $q->choices->contains(fn($c) => !empty($c->image)); @endphp
                                                            @if ($hasImages)
                                                                <p class="text-xs text-green-700 font-medium mb-2">
                                                                    <i class="fas fa-hand-pointer ml-1"></i> اسحب الصور من الأعلى وأسقطها على المربع المناسب بالترتيب الصحيح
                                                                </p>
                                                                <div class="ordering-source-zone mb-3" data-question-id="{{ $q->id }}">
                                                                    <div class="flex items-center gap-1.5 mb-1.5">
                                                                        <i class="fas fa-images text-green-500 text-xs"></i>
                                                                        <span class="text-[11px] text-gray-500 font-medium">الصور المتاحة</span>
                                                                    </div>
                                                                    <div class="ordering-source-grid" id="orderSource-{{ $q->id }}">
                                                                        @foreach ($shuffledChoices as $choice)
                                                                            <div data-id="{{ $choice->id }}" class="ordering-img-item" title="{{ $choice->choice_text }}">
                                                                                <img src="{{ asset('storage/' . $choice->image) }}" alt="{{ $choice->choice_text }}" draggable="false">
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="flex justify-center mb-2"><i class="fas fa-arrow-down text-green-400 text-lg animate-bounce"></i></div>
                                                                <div class="ordering-target-zone" data-question-id="{{ $q->id }}">
                                                                    <div class="flex items-center gap-1.5 mb-1.5">
                                                                        <i class="fas fa-sort-numeric-down text-green-500 text-xs"></i>
                                                                        <span class="text-[11px] text-gray-500 font-medium">الترتيب الصحيح (اسقط الصور هنا)</span>
                                                                    </div>
                                                                    @php $groups = $q->choices->filter(fn($c) => !empty($c->group_name))->groupBy('group_name'); @endphp
                                                                    @if ($q->groups_count && $q->groups_count > 0 && $groups->count() > 0)
                                                                        <div class="grid grid-cols-1 md:grid-cols-{{ min($groups->count(), 4) }} gap-4 ordering-groups-container">
                                                                            @foreach ($groups as $groupName => $groupChoices)
                                                                                @if ($groupChoices->count() === 1)
                                                                                    @php $sc = $groupChoices->first(); @endphp
                                                                                    <div class="p-3 bg-white border border-green-200 rounded-xl shadow-sm">
                                                                                        <div class="flex items-center justify-between gap-3">
                                                                                            <h5 class="text-sm font-bold text-green-700">{{ $groupName }}</h5>
                                                                                            <div class="ordering-slot flex-col gap-1 flex-shrink-0" data-slot="{{ $sc->id }}" data-group="{{ $groupName }}" style="width:60px;height:60px;aspect-ratio:1">
                                                                                                @if (!empty($sc->choice_text))<span class="ordering-slot-text text-xs sm:text-sm text-green-600 font-bold text-center px-1 leading-tight pointer-events-none">{{ $sc->choice_text }}</span>@else<span class="ordering-slot-num">1</span>@endif
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="p-3 bg-white border border-green-200 rounded-xl shadow-sm">
                                                                                        <h5 class="text-sm font-bold text-green-700 text-center mb-2">{{ $groupName }}</h5>
                                                                                        <div class="ordering-slots-grid ordering-slots-grouped" id="orderTarget-{{ $q->id }}-{{ Str::slug($groupName) }}" data-group="{{ $groupName }}">
                                                                                            @foreach ($groupChoices as $s => $choice)
                                                                                                <div class="ordering-slot flex-col gap-1" data-slot="{{ $choice->id }}" data-group="{{ $groupName }}">
                                                                                                    @if (!empty($choice->choice_text))<span class="ordering-slot-text text-xs sm:text-sm text-green-600 font-bold text-center px-1 leading-tight pointer-events-none">{{ $choice->choice_text }}</span>@else<span class="ordering-slot-num">{{ $s + 1 }}</span>@endif
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
                                                                                    @if (!empty($choice->choice_text))<span class="ordering-slot-text text-xs sm:text-sm text-green-600 font-bold text-center px-1 leading-tight pointer-events-none">{{ $choice->choice_text }}</span>@else<span class="ordering-slot-num">{{ $s + 1 }}</span>@endif
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <input type="hidden" name="answer_order_q{{ $q->id }}" value="" class="ordering-final-input" data-question-id="{{ $q->id }}" id="orderInput-{{ $q->id }}">
                                                                <input type="hidden" class="ordering-total-count" value="{{ $q->choices->count() }}">
                                                            @else
                                                                <p class="text-xs text-green-700 font-medium mb-2"><i class="fas fa-info-circle mr-1"></i> قم بسحب وإفلات الخيارات لترتيبها بشكل صحيح</p>
                                                                <div class="space-y-2 sortable-list" data-question-id="{{ $q->id }}">
                                                                    @foreach ($shuffledChoices as $choice)
                                                                        <div data-id="{{ $choice->id }}" class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 bg-white hover:border-green-300 cursor-move transition-all sortable-item select-none shadow-sm">
                                                                            <i class="fas fa-grip-lines text-gray-400"></i>
                                                                            <span class="text-gray-800 text-sm font-medium flex-grow">{{ $choice->choice_text }}</span>
                                                                            <input type="hidden" name="answer[]" value="{{ $choice->id }}" class="ordering-input-hidden">
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                        {{-- MULTIPLE CHOICE --}}
                                                        @elseif ($q->answer_type === 'multiple_choice' && $q->choices->count() > 0)
                                                            @if (!$q->is_multiple_selections && $q->choices->count() === 1)
                                                                <input type="hidden" name="answer" value="{{ $q->choices->first()->id }}">
                                                                <p class="text-xs text-green-700 font-medium mb-2"><i class="fas fa-hand-pointer ml-1"></i> اضغط الزر أدناه للإجابة</p>
                                                            @else
                                                                @if ($q->is_multiple_selections)
                                                                    @php $requiredCount = $q->getRequiredCorrectAnswersCount(); @endphp
                                                                    <p class="text-xs text-green-700 font-medium mb-2">يجب اختيار {{ $requiredCount }} إجابات<input type="hidden" class="required-choices-count" value="{{ $requiredCount }}"></p>
                                                                @endif
                                                                <div class="space-y-2 choice-group">
                                                                    @foreach ($q->choices as $choice)
                                                                        <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 bg-white hover:border-green-300 hover:bg-green-50/50 cursor-pointer transition-all has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                                                            @if ($q->is_multiple_selections)
                                                                                <input type="checkbox" name="answer[]" value="{{ $choice->id }}" class="w-4 h-4 text-green-600 home-quiz-checkbox">
                                                                            @else
                                                                                <input type="radio" name="answer" value="{{ $choice->id }}" class="w-4 h-4 text-green-600" required>
                                                                            @endif
                                                                            <span class="text-gray-800 text-sm font-medium">{{ $choice->choice_text }}</span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                        {{-- TRUE / FALSE --}}
                                                        @elseif ($q->answer_type === 'true_false' && $q->choices->count() > 0)
                                                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-green-100">
                                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 text-white text-xs shadow-md"><i class="fas fa-tasks"></i></span>
                                                                <span class="text-sm text-green-800 font-bold">حدد هل كل عبارة صحيحة أم خاطئة</span>
                                                                <span class="text-[10px] text-gray-400 font-medium mr-auto">({{ $q->choices->count() }} عبارات)</span>
                                                            </div>
                                                            <div class="space-y-3">
                                                                @foreach ($q->choices as $choice)
                                                                    <div class="group relative rounded-xl border border-gray-100 bg-gradient-to-br from-white to-gray-50/80 hover:border-green-200 hover:shadow-md transition-all duration-300 overflow-hidden">
                                                                        <div class="p-3">
                                                                            <div class="flex flex-row gap-3 items-center">

                                                                                {{-- Image thumb --}}
                                                                                @if (!empty($choice->image))
                                                                                    <div class="vthumb" style="width:82px;height:82px"
                                                                                        onclick="quizOpenImage('{{ asset('storage/' . $choice->image) }}')">
                                                                                        <img src="{{ asset('storage/' . $choice->image) }}" alt="صورة">
                                                                                        <div class="vthumb-overlay">
                                                                                            <div class="vthumb-play" style="width:36px;height:36px">
                                                                                                <i class="fas fa-search-plus" style="color:#16a34a;font-size:13px;margin-left:0"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif

                                                                                {{-- Video thumb --}}
                                                                                @if (!empty($choice->video))
                                                                                    <div class="vthumb" style="width:118px;height:82px"
                                                                                        onclick="quizOpenVideo('{{ asset('storage/' . $choice->video) }}')">
                                                                                        <video muted preload="metadata"
                                                                                            src="{{ asset('storage/' . $choice->video) }}#t=0.5">
                                                                                        </video>
                                                                                        <div class="vthumb-overlay">
                                                                                            <div class="vthumb-play">
                                                                                                <i class="fas fa-play"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif

                                                                                <div class="flex-grow flex items-center">
                                                                                    <p class="text-gray-800 text-[13px] font-semibold leading-snug">{{ $choice->choice_text }}</p>
                                                                                </div>

                                                                                <div class="flex flex-col gap-1.5 flex-shrink-0">
                                                                                    <label class="cursor-pointer">
                                                                                        <input type="radio" name="answer[{{ $choice->id }}]" value="1" class="hidden peer" required>
                                                                                        <div class="w-9 h-9 flex items-center justify-center rounded-full border-2 border-green-200 bg-green-50/50 text-green-500 transition-all duration-200 peer-checked:bg-gradient-to-br peer-checked:from-green-500 peer-checked:to-emerald-500 peer-checked:text-white peer-checked:border-green-500 peer-checked:shadow-lg peer-checked:shadow-green-200/50 peer-checked:scale-110 hover:bg-green-100 hover:border-green-300 active:scale-90">
                                                                                            <i class="fas fa-check text-sm"></i>
                                                                                        </div>
                                                                                    </label>
                                                                                    <label class="cursor-pointer">
                                                                                        <input type="radio" name="answer[{{ $choice->id }}]" value="0" class="hidden peer" required>
                                                                                        <div class="w-9 h-9 flex items-center justify-center rounded-full border-2 border-red-200 bg-red-50/50 text-red-400 transition-all duration-200 peer-checked:bg-gradient-to-br peer-checked:from-red-500 peer-checked:to-rose-500 peer-checked:text-white peer-checked:border-red-500 peer-checked:shadow-lg peer-checked:shadow-red-200/50 peer-checked:scale-110 hover:bg-red-100 hover:border-red-300 active:scale-90">
                                                                                            <i class="fas fa-times text-sm"></i>
                                                                                        </div>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                        {{-- VOTE inside mixed --}}
                                                        @elseif ($q->answer_type === 'vote' && $q->choices->count() > 0)
                                                            @php $voteMax = $q->vote_max_selections ?? 1; @endphp
                                                            @if ($voteMax > 1)
                                                                <p class="text-xs text-green-700 font-medium mb-2">
                                                                    <i class="fas fa-poll ml-1"></i> يمكنك اختيار حتى <strong>{{ $voteMax }}</strong> خيارات
                                                                    <input type="hidden" class="home-vote-max" value="{{ $voteMax }}">
                                                                </p>
                                                            @endif
                                                            <div class="space-y-3">
                                                                @foreach ($q->choices as $choice)
                                                                    <label class="group flex items-center gap-3 p-3 rounded-2xl border-2 border-gray-100 bg-white hover:border-green-300 hover:shadow-md cursor-pointer transition-all has-[:checked]:border-green-500 has-[:checked]:bg-gradient-to-r has-[:checked]:from-green-50 has-[:checked]:to-emerald-50 relative overflow-hidden">
                                                                        @if ($voteMax > 1)
                                                                            <input type="checkbox" name="answer[]" value="{{ $choice->id }}" class="hidden home-vote-checkbox">
                                                                            <div class="w-6 h-6 flex-shrink-0 rounded flex items-center justify-center border-2 border-gray-300 bg-gray-50 transition-all group-has-[:checked]:border-green-500 group-has-[:checked]:bg-green-500 z-10">
                                                                                <i class="fas fa-check text-white text-xs opacity-0 group-has-[:checked]:opacity-100 transition-opacity"></i>
                                                                            </div>
                                                                        @else
                                                                            <input type="radio" name="answer" value="{{ $choice->id }}" class="hidden peer" required>
                                                                            <div class="w-6 h-6 flex-shrink-0 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-green-500 peer-checked:bg-green-500 transition-all z-10">
                                                                                <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                                            </div>
                                                                        @endif
                                                                        <div class="flex-grow flex items-center gap-3 z-10 min-w-0">
                                                                            @if ($choice->image)
                                                                                <img src="{{ asset('storage/' . $choice->image) }}" class="w-12 h-12 md:w-14 md:h-14 object-cover rounded-xl shadow-sm border border-gray-100 flex-shrink-0">
                                                                            @endif
                                                                            <span class="text-gray-800 text-[13px] md:text-sm font-bold leading-snug">{{ $choice->choice_text }}</span>
                                                                        </div>
                                                                        @if ($choice->video)
                                                                            <div class="z-10 flex-shrink-0"
                                                                                onclick="event.preventDefault();event.stopPropagation();quizOpenVideo('{{ asset('storage/' . $choice->video) }}')">
                                                                                <div class="vthumb" style="width:96px;height:72px;border-radius:11px">
                                                                                    <video muted preload="metadata" src="{{ asset('storage/' . $choice->video) }}#t=0.5"></video>
                                                                                    <div class="vthumb-overlay">
                                                                                        <div class="vthumb-play" style="width:24px;height:24px">
                                                                                            <i class="fas fa-play" style="font-size:9px"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </label>
                                                                @endforeach
                                                            </div>

                                                        {{-- FILL BLANK --}}
                                                        @elseif ($q->answer_type === 'fill_blank' && $q->choices->count() > 0)
                                                            @php $parts = preg_split('/___/', $q->question_text, 2); $beforeBlank = $parts[0] ?? ''; $afterBlank = $parts[1] ?? ''; @endphp
                                                            <div class="fill-blank-wrapper" data-question-id="{{ $q->id }}">
                                                                <div class="fill-blank-sentence" dir="rtl">
                                                                    @if ($beforeBlank)<span class="fill-blank-text">{{ $beforeBlank }}</span>@endif
                                                                    <span class="fill-blank-drop" id="fillDrop-{{ $q->id }}" onclick="fbClearDrop({{ $q->id }})">
                                                                        <span class="fill-blank-placeholder">اسحب أو اضغط كلمة</span>
                                                                    </span>
                                                                    @if ($afterBlank)<span class="fill-blank-text">{{ $afterBlank }}</span>@endif
                                                                </div>
                                                                <div class="fill-blank-chips" id="fillChips-{{ $q->id }}">
                                                                    @foreach ($q->choices->shuffle() as $choice)
                                                                        <button type="button" class="fill-blank-chip"
                                                                            data-choice-id="{{ $choice->id }}"
                                                                            data-question-id="{{ $q->id }}"
                                                                            onclick="fbSelectChip(this)">{{ $choice->choice_text }}</button>
                                                                    @endforeach
                                                                </div>
                                                                <input type="hidden" name="answer" id="fillAnswer-{{ $q->id }}" value="" class="fill-blank-answer-input">
                                                                <p class="fill-blank-hint" id="fillHint-{{ $q->id }}">
                                                                    <i class="fas fa-hand-point-up ml-1"></i> اختر الكلمة المناسبة لإتمام الجملة
                                                                </p>
                                                            </div>

                                                        {{-- ══════════════════════════════════════
                                                             SURVEY
                                                        ══════════════════════════════════════ --}}
                                                        @elseif ($q->answer_type === 'survey' && $q->surveyItems->isNotEmpty())
                                                            <div class="space-y-3">
                                                                @foreach ($q->surveyItems as $si)
                                                                    <div class="rounded-xl border border-green-200 bg-white p-3">
                                                                        <div class="survey-item-row">

                                                                            {{-- ▌ IMAGE – صغيرة على اليسار تفتح lightbox --}}
                                                                            @if ($si->block_type === 'image' && $si->media_path)
                                                                                <div class="survey-item-media sim-img"
                                                                                    onclick="quizOpenImage('{{ asset('storage/' . $si->media_path) }}')">
                                                                                    <img src="{{ asset('storage/' . $si->media_path) }}" alt="صورة">
                                                                                    <div class="sim-overlay">
                                                                                        <div class="sim-play">
                                                                                            <i class="fas fa-search-plus"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            {{-- ▌ VIDEO / YOUTUBE – صغيرة على اليسار تفتح lightbox --}}
                                                                            @elseif ($si->block_type === 'video' && (!empty($si->media_path) || !empty($si->youtube_url)))
                                                                                @php
                                                                                    $ytId = null;
                                                                                    if (!empty($si->youtube_url) && is_string($si->youtube_url)) {
                                                                                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/', $si->youtube_url, $m)) {
                                                                                            $ytId = $m[1] ?? null;
                                                                                        }
                                                                                    }
                                                                                @endphp
                                                                                <div class="survey-item-media"
                                                                                    onclick="quizOpenVideo('{{ !empty($si->media_path) ? asset('storage/' . $si->media_path) : $si->youtube_url }}')">
                                                                                    @if (!empty($si->media_path))
                                                                                        <video muted preload="metadata"
                                                                                            src="{{ asset('storage/' . $si->media_path) }}#t=0.5">
                                                                                        </video>
                                                                                    @else
                                                                                        @if(!empty($ytId))
                                                                                            <img src="https://img.youtube.com/vi/{{ $ytId }}/maxresdefault.jpg"
                                                                                                alt="صورة يوتيوب"
                                                                                                style="width:100%;height:100%;object-fit:cover;">
                                                                                        @else
                                                                                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#000">
                                                                                                <i class="fab fa-youtube text-white" style="font-size:22px"></i>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endif
                                                                                    <div class="sim-overlay">
                                                                                        <div class="sim-play">
                                                                                            <i class="fas fa-play"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            {{-- ▌ CONTENT (نص + إجابة) --}}
                                                                            <div class="survey-item-content">
                                                                                @if ($si->body_text)
                                                                                    <div class="text-gray-700 text-sm mb-2 quiz-description">{!! $si->body_text !!}</div>
                                                                                @endif

                                                                                @if ($si->response_kind === 'rating')
                                                                                    @php
                                                                                        $rm      = max(2, min(10, (int) $si->rating_max));
                                                                                        $oldVal  = old('survey_item.'.$si->id, 0);
                                                                                        $siId    = $si->id;
                                                                                        $qId     = $q->id;
                                                                                    @endphp
                                                                                    <label class="text-xs font-bold text-green-800 block mb-1">
                                                                                        <i class="fas fa-star text-amber-400 ml-1" style="font-size:11px"></i>
                                                                                        اختر تقييمك (1–{{ $rm }})
                                                                                    </label>
                                                                                    {{-- نجوم: نعرض بالعكس (RTL) فـ flex-row-reverse --}}
                                                                                    <div class="star-rating" id="stars-{{ $qId }}-{{ $siId }}">
                                                                                        @for ($star = $rm; $star >= 1; $star--)
                                                                                            <input type="radio"
                                                                                                name="survey_item[{{ $siId }}]"
                                                                                                id="star-{{ $qId }}-{{ $siId }}-{{ $star }}"
                                                                                                value="{{ $star }}"
                                                                                                {{ (int)$oldVal === $star ? 'checked' : '' }}
                                                                                                required>
                                                                                            <label for="star-{{ $qId }}-{{ $siId }}-{{ $star }}"
                                                                                                title="{{ $star }}"
                                                                                                onclick="srvStarPick('{{ $qId }}-{{ $siId }}',{{ $star }},{{ $rm }})">&#9733;</label>
                                                                                        @endfor
                                                                                    </div>
                                                                                    <p class="star-val-display" id="srv-r-{{ $qId }}-{{ $siId }}">
                                                                                        {{ $oldVal ? $oldVal . ' / ' . $rm : 'لم تختر بعد' }}
                                                                                    </p>

                                                                                @elseif ($si->response_kind === 'number')
                                                                                    <label class="text-xs font-bold text-green-800 block mb-1">رقم ({{ $si->number_min }}–{{ $si->number_max }})</label>
                                                                                    <input type="number" name="survey_item[{{ $si->id }}]" required
                                                                                        min="{{ $si->number_min ?? 0 }}" max="{{ $si->number_max ?? 100 }}"
                                                                                        value="{{ old('survey_item.'.$si->id) }}"
                                                                                        class="w-full px-3 py-2 rounded-xl border-2 border-gray-200 text-sm">

                                                                                @else
                                                                                    <label class="text-xs font-bold text-green-800 block mb-1">إجابتك</label>
                                                                                    <textarea name="survey_item[{{ $si->id }}]" rows="4" required maxlength="2000"
                                                                                        class="w-full px-3 py-2 rounded-xl border-2 border-gray-200 text-sm resize-none">{{ old('survey_item.'.$si->id) }}</textarea>
                                                                                @endif
                                                                            </div>{{-- /survey-item-content --}}

                                                                        </div>{{-- /survey-item-row --}}
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                        {{-- TEXT --}}
                                                        @else
                                                            <textarea name="answer" rows="3" required placeholder="اكتب إجابتك..."
                                                                class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 text-sm focus:ring-2 focus:ring-green-200 focus:border-green-500 resize-none">{{ old('answer') }}</textarea>
                                                        @endif
                                                    </div>

                                                    {{-- Submit --}}
                                                    @if ($q->answer_type === 'multiple_choice' && !$q->is_multiple_selections && $q->choices->count() === 1)
                                                        <button type="submit" onclick="validateHomeQuiz(event,this)"
                                                            class="w-full px-6 py-4 rounded-xl text-white font-bold text-base inline-flex items-center justify-center gap-2 transition-all hover:opacity-90 hover:scale-[1.02] active:scale-[0.98] mt-4"
                                                            style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                                                            <i class="fas fa-hand-pointer"></i> {{ $q->choices->first()->choice_text }}
                                                        </button>
                                                    @elseif ($q->answer_type === 'vote')
                                                        <button type="submit" onclick="validateHomeQuiz(event,this)"
                                                            class="w-full sm:w-auto min-w-[150px] px-6 py-3.5 rounded-xl text-white font-bold text-sm inline-flex items-center justify-center gap-2 transition-all hover:opacity-90 hover:scale-[1.02] active:scale-[0.98] mt-4"
                                                            style="background:linear-gradient(135deg,#3b82f6,#2563eb)">
                                                            <i class="fas fa-poll"></i> إرسال التصويت
                                                        </button>
                                                    @else
                                                        <button type="submit" onclick="validateHomeQuiz(event,this)"
                                                            class="w-full sm:w-auto px-6 py-3 rounded-xl text-white font-bold text-sm inline-flex items-center justify-center gap-2 transition-all hover:opacity-90 mt-4"
                                                            style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                                                            <i class="fas fa-paper-plane"></i> إرسال الإجابة
                                                        </button>
                                                    @endif
                                                </form>

                                            @elseif (session('vote_submitted') && session('answered_question_id') == $q->id)
                                                <div class="rounded-2xl p-4 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 shadow-sm relative overflow-hidden mt-2">
                                                    <div class="absolute top-0 right-0 left-0 h-1.5" style="background:linear-gradient(90deg,#22c55e,#16a34a)"></div>
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
                                            @elseif (session('survey_submitted') && session('answered_question_id') == $q->id)
                                                <div class="rounded-2xl p-4 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 shadow-sm relative overflow-hidden mt-2">
                                                    <div class="absolute top-0 right-0 left-0 h-1.5" style="background:linear-gradient(90deg,#22c55e,#16a34a)"></div>
                                                    <div class="flex items-start gap-3">
                                                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-green-100 flex-shrink-0 border border-green-200">
                                                            <i class="fas fa-clipboard-check text-green-600 text-lg"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <h4 class="font-bold text-green-800 text-sm">تم تسجيل إجابتك على الاستبيان</h4>
                                                            <p class="text-xs text-green-700 mt-1">شكراً لمشاركتك، تم استلام إجاباتك بنجاح.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                @if ($q->answer_type === 'vote')
                                                    <div class="rounded-2xl p-4 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 shadow-sm relative overflow-hidden mt-2">
                                                        <div class="absolute top-0 right-0 left-0 h-1.5" style="background:linear-gradient(90deg,#22c55e,#16a34a)"></div>
                                                        <div class="flex items-center gap-3 mb-4">
                                                            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-green-100 flex-shrink-0 border border-green-200">
                                                                <i class="fas fa-check-circle text-green-600 text-lg"></i>
                                                            </div>
                                                            <div>
                                                                <h4 class="font-bold text-green-800 text-sm">تفضيلات التصويت الحالية</h4>
                                                                <p class="text-xs text-green-600 mt-0.5">لقد قمت بالتصويت مسبقاً:</p>
                                                            </div>
                                                        </div>
                                                        <div class="home-vote-results space-y-3" data-url="{{ route('quiz-competitions.question.vote-results', [$activeQuizCompetition, $q]) }}">
                                                            <p class="text-gray-400 text-xs text-center py-4"><i class="fas fa-spinner fa-spin mx-1"></i> تحميل النتائج...</p>
                                                        </div>
                                                    </div>
                                                @elseif ($q->answer_type === 'survey')
                                                    <div class="rounded-xl p-4 bg-green-50 border border-green-200">
                                                        <p class="text-green-800 text-sm font-medium">
                                                            <i class="fas fa-clipboard-check text-green-600 ml-1"></i>
                                                            سبق أن سجّلت إجابتك على هذا الاستبيان. شكراً لمشاركتك.
                                                        </p>
                                                        <p class="text-green-700 text-xs mt-2">يمكنك إعادة الإجابة بعد حوالي ساعتين إن رغبت.</p>
                                                    </div>
                                                @else
                                                    <div class="rounded-xl p-4 bg-amber-50 border border-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                                        <p class="text-amber-800 text-sm font-medium">
                                                            <i class="fas fa-check-circle text-amber-600 ml-1"></i>
                                                            لقد أجبت على هذا السؤال مسبقاً، يمكنك متابعة القرعة من هنا.
                                                        </p>
                                                        <a href="{{ route('quiz-competitions.question', [$activeQuizCompetition, $q]) }}"
                                                            class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-white transition-all hover:opacity-90"
                                                            style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                                                            <i class="fas fa-trophy"></i> متابعة القرعة
                                                        </a>
                                                    </div>
                                                @endif
                                            @endif

                                        </div>
                                    @endforeach
                                </div>{{-- /questions --}}
                            </div>{{-- /glass-card --}}
                        @endif{{-- /isVoteOnly --}}

                        </div>{{-- /activeQuizSection-{{ $activeQuizCompetition->id }} --}}
                    @endforeach
                    </div>{{-- /activeQuizSection --}}
                @endif{{-- /activeQuizCompetitionsList --}}

                {{-- Section heading when no active comp --}}
                @if ($activeQuizCompetitionsList->isEmpty())
                    <div class="text-right mb-3 md:mb-5">
                        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gradient section-title mb-2">{{ $sectionTitle }}</h2>
                    </div>
                @endif

                {{-- ══════════════════════════════════════
                     NEXT EVENT COUNTDOWN
                ══════════════════════════════════════ --}}
                @if (isset($nextQuizEvent) && $nextQuizEvent && $activeQuizCompetitionsList->isEmpty())
                    <div class="mb-3 md:mb-5" id="quizCountdownSection">
                        <div class="vote-wrap">
                            <div class="vote-grid"></div>
                            <div style="position:relative;z-index:2;padding:26px 22px;text-align:center">
                                <div style="display:inline-flex;align-items:center;gap:8px;background:#fefce8;border:1px solid #fde68a;color:#92400e;border-radius:50px;padding:6px 16px;margin-bottom:15px;font-size:.76rem;font-weight:700">
                                    <i class="fas fa-clock" style="font-size:11px"></i>
                                    @php
                                        $countdownNoun = $sectionTitle === 'التصويت'
                                            ? 'التصويت'
                                            : ($sectionTitle === 'الاستبيان' ? 'الاستبيان' : 'المسابقة');
                                    @endphp
                                    {{ $countdownNoun }} تبدأ قريباً
                                </div>
                                <p style="color:#14532d;font-weight:800;font-size:1.08rem;margin-bottom:8px">{{ $nextQuizEvent['title'] }}</p>
                                @if (!empty($nextQuizEvent['description']))
                                    <div style="color:#4b5563;font-size:.82rem;margin-bottom:18px;text-align:right" class="quiz-description">
                                        {!! $nextQuizEvent['description'] !!}
                                    </div>
                                @endif
                                <div class="flex justify-center flex-row-reverse gap-3 mb-2" id="quizCountdown">
                                    @foreach ([['days','يوم'],['hours','ساعة'],['minutes','دقيقة'],['seconds','ثانية']] as [$unit,$label])
                                        <div class="cd-box">
                                            <div class="cd-num" id="countdown-{{ $unit }}">0</div>
                                            <div class="cd-lbl">{{ $label }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" id="quizCountdownTarget" value="{{ $nextQuizEvent['target_at']->getTimestamp() * 1000 }}">
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </section>

        {{-- ══════════════════════════════════════
             UNIFIED LIGHTBOX
        ══════════════════════════════════════ --}}
        <div id="quizLightboxModal" onclick="quizCloseLightbox()">
            <div class="quiz-lb-inner" onclick="event.stopPropagation()">
                <button class="quiz-lb-close" onclick="quizCloseLightbox()" aria-label="إغلاق">
                    <i class="fas fa-times" style="font-size:13px;pointer-events:none"></i>
                </button>
                <img id="quizLightboxImg" src="" alt="صورة">
                <video id="quizLightboxVideo" controls playsinline></video>
                <iframe id="quizLightboxIframe" src="" frameborder="0"
                    allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>

    @push('scripts')
    <script>
    /* ─── Lightbox ─── */
    var _qlb={modal:null,img:null,video:null,iframe:null,init:function(){this.modal=document.getElementById('quizLightboxModal');this.img=document.getElementById('quizLightboxImg');this.video=document.getElementById('quizLightboxVideo');this.iframe=document.getElementById('quizLightboxIframe')}};
    function quizExtractYouTubeId(url){if(!url)return null;var m=(''+url).match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/);return m?m[1]:null}
    function quizOpenImage(s){if(!_qlb.modal)_qlb.init();_qlb.img.src=s;_qlb.img.style.display='block';_qlb.video.style.display='none';_qlb.video.pause();_qlb.video.src='';_qlb.iframe.style.display='none';_qlb.iframe.src='';_qlb.modal.classList.add('lb-open');document.body.style.overflow='hidden'}
    function quizOpenVideo(s){if(!_qlb.modal)_qlb.init();_qlb.img.style.display='none';var ytId=quizExtractYouTubeId(s);_qlb.modal.classList.add('lb-open');document.body.style.overflow='hidden';if(ytId){_qlb.video.style.display='none';_qlb.video.pause();_qlb.video.src='';_qlb.iframe.style.display='block';_qlb.iframe.src='https://www.youtube.com/embed/'+ytId+'?autoplay=1&rel=0'}else{_qlb.iframe.style.display='none';_qlb.iframe.src='';_qlb.video.src=s;_qlb.video.style.display='block';_qlb.video.play().catch(function(){})}}
    function quizCloseLightbox(){if(!_qlb.modal)return;_qlb.modal.classList.remove('lb-open');_qlb.video.pause();_qlb.video.src='';_qlb.img.src='';_qlb.iframe.src='';_qlb.iframe.style.display='none';document.body.style.overflow=''}
    document.addEventListener('keydown',function(e){if(e.key==='Escape')quizCloseLightbox()});

    /* ─── Vote toggle visual ─── */
    function voteToggle(inp){var form=inp.closest('form');if(!form)return;if(inp.type==='radio'){form.querySelectorAll('.vote-choice').forEach(function(c){c.classList.remove('vote-sel')});var p=inp.closest('.vote-choice');if(p)p.classList.add('vote-sel')}else{var p=inp.closest('.vote-choice');if(p)p.classList.toggle('vote-sel',inp.checked)}}

    /* ─── Fill blank ─── */
    var fbState={};
    function fbSelectChip(chip){var qid=chip.getAttribute('data-question-id'),cid=chip.getAttribute('data-choice-id'),drop=document.getElementById('fillDrop-'+qid),inp=document.getElementById('fillAnswer-'+qid),hint=document.getElementById('fillHint-'+qid);document.querySelectorAll('.fill-blank-chip[data-question-id="'+qid+'"]').forEach(function(c){c.classList.remove('selected')});chip.classList.add('selected');var old=drop.querySelector('.fill-blank-word-inside');if(old)old.remove();var span=document.createElement('span');span.className='fill-blank-word-inside';span.textContent=chip.textContent.trim();drop.appendChild(span);drop.classList.add('has-word');inp.value=cid;fbState[qid]=cid;if(hint)hint.classList.add('hidden-hint')}
    function fbClearDrop(qid){var drop=document.getElementById('fillDrop-'+qid),inp=document.getElementById('fillAnswer-'+qid),hint=document.getElementById('fillHint-'+qid);if(!drop.classList.contains('has-word'))return;var old=drop.querySelector('.fill-blank-word-inside');if(old)old.remove();drop.classList.remove('has-word');inp.value='';fbState[qid]=null;document.querySelectorAll('.fill-blank-chip[data-question-id="'+qid+'"]').forEach(function(c){c.classList.remove('selected')});if(hint)hint.classList.remove('hidden-hint')}

    /* ─── Submit validation ─── */
    function validateHomeQuiz(event,btn){var form=btn.closest('form');if(!form)return true;var fi=form.querySelector('.fill-blank-answer-input');if(fi&&!fi.value){event.preventDefault();var w=form.querySelector('.fill-blank-wrapper');if(w){var qid=w.getAttribute('data-question-id'),drop=document.getElementById('fillDrop-'+qid);if(drop){drop.style.borderColor='#f87171';drop.style.background='#fef2f2';setTimeout(function(){drop.style.borderColor='';drop.style.background=''},1400)}document.querySelectorAll('.fill-blank-chip[data-question-id="'+qid+'"]').forEach(function(c){c.style.animation='chipShake .5s ease';setTimeout(function(){c.style.animation=''},600)})}if(typeof Swal!=='undefined')Swal.fire({icon:'warning',title:'انتبه',text:'يرجى اختيار كلمة لملء الفراغ أولاً!',confirmButtonColor:'#22c55e',confirmButtonText:'حسناً',toast:true,position:'top-end',showConfirmButton:false,timer:2500});return false}return true}

    /* ─── Star rating display ─── */
    function srvStarPick(key,val,max){var el=document.getElementById('srv-r-'+key);if(el)el.textContent=val+' / '+max}

    /* ─── DOM Ready ─── */
    document.addEventListener('DOMContentLoaded',function(){
        _qlb.init();

        /* vote max limit */
        document.querySelectorAll('.home-vote-max').forEach(function(el){var form=el.closest('form'),max=parseInt(el.value,10);if(!form||max<=1)return;form.querySelectorAll('.home-vote-checkbox').forEach(function(cb){cb.addEventListener('change',function(){if(form.querySelectorAll('.home-vote-checkbox:checked').length>max){this.checked=false;var p=this.closest('.vote-choice');if(p)p.classList.remove('vote-sel');if(typeof Swal!=='undefined')Swal.fire({icon:'info',title:'تنبيه',text:'لا يمكنك اختيار أكثر من '+max+' خيارات.',confirmButtonColor:'#22c55e',confirmButtonText:'حسناً',toast:true,position:'top-end',showConfirmButton:false,timer:3000})}})})});

        /* vote results */
        document.querySelectorAll('.home-vote-results').forEach(function(c){var url=c.getAttribute('data-url'),dark=c.getAttribute('data-theme')==='dark';if(!url)return;fetch(url).then(function(r){return r.json()}).then(function(data){if(!data.results||!data.results.length){c.innerHTML='<p style="color:#9ca3af;font-size:.77rem;text-align:center;padding:8px 0">لا توجد أصوات بعد.</p>';return}var html=data.results.map(function(r){return'<div class="vote-rrow"><div class="vote-rmeta"><span class="vote-rname">'+r.text+'</span><span class="vote-rpct">'+r.percent+'% <span class="vote-rcnt">('+r.count+')</span></span></div><div class="vote-rbg"><div class="vote-rfill" data-width="'+r.percent+'%"></div></div></div>'}).join('');c.innerHTML=html;setTimeout(function(){c.querySelectorAll('[data-width]').forEach(function(b){b.style.width=b.getAttribute('data-width')})},100)}).catch(function(){c.innerHTML='<p style="color:#ef4444;font-size:.77rem;text-align:center;padding:8px 0">تعذر تحميل النتائج.</p>'})});
    });
    </script>
    @endpush

    @endif{{-- /quiz section --}}
