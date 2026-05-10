@extends('layouts.web-site.web')

@section('title', ($settings->page_title ?? 'الدعم الفني').' - تواصل عائلة السريع')

@push('styles')
    <style>
        .support-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0f3d36 0%, #145147 28%, #37a05c 72%, #2d8a4e 100%);
        }

        .support-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 20%, rgba(255, 255, 255, 0.12) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 75%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
            pointer-events: none;
        }

        .support-hero-grid {
            position: absolute;
            inset: 0;
            opacity: 0.15;
            background-image: linear-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        .support-channel-card {
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.35s ease;
        }

        .support-channel-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 44px rgba(20, 81, 71, 0.18);
        }

        .support-faq details {
            border-radius: 1rem;
            border: 1px solid rgba(20, 81, 71, 0.12);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            margin-bottom: 0.75rem;
            overflow: hidden;
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .support-faq details[open] {
            border-color: rgba(55, 160, 92, 0.45);
            box-shadow: 0 12px 32px rgba(20, 81, 71, 0.1);
        }

        .support-faq summary {
            cursor: pointer;
            list-style: none;
            padding: 1.1rem 1.25rem;
            font-weight: 700;
            color: #145147;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            user-select: none;
        }

        .support-faq summary::-webkit-details-marker {
            display: none;
        }

        .support-faq summary .chev {
            transition: transform 0.3s ease;
            color: #37a05c;
            flex-shrink: 0;
        }

        .support-faq details[open] summary .chev {
            transform: rotate(-180deg);
        }

        .support-faq .faq-body {
            padding: 0 1.25rem 1.15rem;
            color: #374151;
            line-height: 1.85;
            border-top: 1px solid rgba(20, 81, 71, 0.06);
        }
    </style>
@endpush

@section('content')
    @php
        $iconFor = function (\App\Models\SupportChannel $ch) {
            if ($ch->icon) {
                return $ch->icon;
            }

            return match ($ch->type) {
                \App\Models\SupportChannel::TYPE_EMAIL => 'fas fa-envelope',
                \App\Models\SupportChannel::TYPE_PHONE => 'fas fa-phone-alt',
                \App\Models\SupportChannel::TYPE_WHATSAPP => 'fab fa-whatsapp',
                \App\Models\SupportChannel::TYPE_URL => 'fas fa-link',
                default => 'fas fa-headset',
            };
        };
    @endphp

    <section class="support-hero relative text-white">
        <div class="support-hero-grid" aria-hidden="true"></div>
        <div class="relative max-w-6xl mx-auto px-4 py-16 md:py-24 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-md border border-white/25 mb-6 shadow-lg animate-fade-in-scale">
                <i class="fas fa-life-ring text-3xl text-white/95"></i>
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4 drop-shadow-md animate-fade-in-up">
                {{ $settings->page_title }}
            </h1>
            @if ($settings->page_subtitle)
                <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto font-medium leading-relaxed animate-fade-in-up"
                    style="animation-delay: 0.08s">
                    {{ $settings->page_subtitle }}
                </p>
            @endif
        </div>
        <div class="h-px bg-gradient-to-r from-transparent via-white/35 to-transparent"></div>
    </section>

    <div class="max-w-6xl mx-auto px-4 -mt-8 relative z-10 pb-16">
        @if ($settings->intro_html)
            <div
                class="glass-card rounded-2xl p-6 md:p-8 shadow-xl mb-12 border border-white/60 dynamic-rich-text text-gray-700 leading-relaxed">
                {!! $settings->intro_html !!}
            </div>
        @endif

        @if ($channels->isNotEmpty())
            <div class="mb-14">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 section-title">
                    <span class="text-gradient">طرق التواصل</span>
                </h2>
                <p class="text-gray-600 mt-6 mb-8 max-w-2xl">اختر القناة المناسبة للتواصل مع فريق الدعم.</p>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
                    @foreach ($channels as $ch)
                        @php
                            $href = $ch->resolvedHref();
                            $faIcon = $iconFor($ch);
                        @endphp
                        <div
                            class="support-channel-card glass-card rounded-2xl p-6 border border-emerald-100/80 shadow-lg flex flex-col h-full">
                            <div
                                class="w-14 h-14 rounded-xl gradient-primary flex items-center justify-center text-white text-2xl mb-4 shadow-md">
                                <i class="{{ $faIcon }}"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $ch->label }}</h3>
                            <p class="text-sm text-gray-500 mb-4 flex-grow">
                                {{ \App\Models\SupportChannel::typeLabels()[$ch->type] ?? $ch->type }}
                            </p>
                            <div class="mt-auto pt-2">
                                @if ($href)
                                    <a href="{{ $href }}" target="_blank" rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 text-emerald-700 font-bold hover:text-emerald-900 transition-colors">
                                        <span class="break-all">{{ $ch->value }}</span>
                                        <i class="fas fa-arrow-left text-sm opacity-70"></i>
                                    </a>
                                @else
                                    <span class="text-gray-700 font-medium break-all">{{ $ch->value }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($faqs->isNotEmpty())
            <div class="support-faq">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 section-title">
                    <span class="text-gradient">الأسئلة الشائعة</span>
                </h2>
                <p class="text-gray-600 mt-6 mb-8 max-w-2xl">إجابات سريعة على أكثر الاستفسارات شيوعاً.</p>
                <div class="space-y-3 max-w-4xl">
                    @foreach ($faqs as $faq)
                        <details class="group">
                            <summary>
                                <span>{{ $faq->question }}</span>
                                <i class="fas fa-chevron-down chev"></i>
                            </summary>
                            <div class="faq-body dynamic-rich-text">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($channels->isEmpty() && $faqs->isEmpty() && ! $settings->intro_html)
            <div class="text-center py-12 glass-card rounded-2xl border border-dashed border-emerald-200/80">
                <i class="fas fa-info-circle text-4xl text-emerald-600/70 mb-4"></i>
                <p class="text-gray-600 text-lg">جاري إعداد محتوى الدعم. يمكن للمسؤول إضافة القنوات والأسئلة من لوحة التحكم.</p>
            </div>
        @endif
    </div>
@endsection
