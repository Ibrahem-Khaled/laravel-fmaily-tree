<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تفاصيل الزيارة - لوحة التحكم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bg-base: #080c14;
            --bg-surface: #0d1424;
            --bg-card: #111827;
            --bg-card-hover: #162033;
            --border: #1e2d45;
            --accent-blue: #3b82f6;
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --accent-violet: #8b5cf6;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --accent-teal: #14b8a6;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #475569;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            background-color: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(59, 130, 246, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            top: -40%;
            right: -20%;
            width: 70%;
            height: 80%;
            background: radial-gradient(ellipse, rgba(59, 130, 246, 0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }

        /* NAV */
        .top-nav {
            background: rgba(13, 20, 36, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-logo-dot {
            width: 8px;
            height: 8px;
            background: var(--accent-blue);
            border-radius: 50%;
            box-shadow: 0 0 12px var(--accent-blue);
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
                box-shadow: 0 0 12px var(--accent-blue);
            }

            50% {
                opacity: 0.4;
                box-shadow: 0 0 4px var(--accent-blue);
            }
        }

        /* BUTTONS */
        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
            border-radius: 10px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            text-decoration: none;
        }

        .btn-ghost:hover {
            border-color: var(--accent-blue);
            color: var(--accent-blue);
            background: rgba(59, 130, 246, 0.05);
        }

        /* SECTION CARDS */
        .section-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .section-card:hover {
            border-color: rgba(59, 130, 246, 0.25);
        }

        .section-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            background: rgba(30, 45, 69, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        /* INFO GRID ITEMS */
        .info-item {
            background: rgba(13, 20, 36, 0.7);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-item:hover {
            border-color: var(--item-color, var(--accent-blue));
            background: rgba(13, 20, 36, 0.9);
        }

        .info-item::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 3px;
            height: 100%;
            background: var(--item-color, var(--accent-blue));
            opacity: 0.6;
        }

        .info-item.blue {
            --item-color: var(--accent-blue);
        }

        .info-item.emerald {
            --item-color: var(--accent-emerald);
        }

        .info-item.violet {
            --item-color: var(--accent-violet);
        }

        .info-item.cyan {
            --item-color: var(--accent-cyan);
        }

        .info-item.teal {
            --item-color: var(--accent-teal);
        }

        .info-item.amber {
            --item-color: var(--accent-amber);
        }

        .info-item.rose {
            --item-color: var(--accent-rose);
        }

        .info-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .info-sub {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* BADGES */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-emerald {
            background: rgba(16, 185, 129, 0.15);
            color: var(--accent-emerald);
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .badge-gray {
            background: rgba(100, 116, 139, 0.15);
            color: var(--text-secondary);
            border: 1px solid rgba(100, 116, 139, 0.2);
        }

        .badge-blue {
            background: rgba(59, 130, 246, 0.15);
            color: var(--accent-blue);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .badge-violet {
            background: rgba(139, 92, 246, 0.15);
            color: var(--accent-violet);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        .badge-amber {
            background: rgba(245, 158, 11, 0.15);
            color: var(--accent-amber);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-rose {
            background: rgba(244, 63, 94, 0.15);
            color: var(--accent-rose);
            border: 1px solid rgba(244, 63, 94, 0.2);
        }

        .badge-teal {
            background: rgba(20, 184, 166, 0.15);
            color: var(--accent-teal);
            border: 1px solid rgba(20, 184, 166, 0.2);
        }

        .badge-cyan {
            background: rgba(6, 182, 212, 0.15);
            color: var(--accent-cyan);
            border: 1px solid rgba(6, 182, 212, 0.2);
        }

        /* STATUS */
        .status-2xx {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .status-3xx {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.25);
        }

        .status-4xx {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        .status-5xx {
            background: rgba(244, 63, 94, 0.15);
            color: #f43f5e;
            border: 1px solid rgba(244, 63, 94, 0.25);
        }

        /* METHOD COLORS */
        .method-get {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .method-post {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.25);
        }

        .method-put {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        .method-patch {
            background: rgba(6, 182, 212, 0.15);
            color: #06b6d4;
            border: 1px solid rgba(6, 182, 212, 0.25);
        }

        .method-delete {
            background: rgba(244, 63, 94, 0.15);
            color: #f43f5e;
            border: 1px solid rgba(244, 63, 94, 0.25);
        }

        /* IP TAG */
        .ip-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1rem;
            font-weight: 600;
            color: var(--accent-cyan);
            background: rgba(6, 182, 212, 0.08);
            border: 1px solid rgba(6, 182, 212, 0.2);
            padding: 6px 14px;
            border-radius: 8px;
            display: inline-block;
        }

        /* CODE BLOCKS */
        .code-block {
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--text-secondary);
            overflow-x: auto;
            word-break: break-all;
            direction: ltr;
            text-align: left;
        }

        /* URL BLOCK */
        .url-block {
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 18px;
            transition: all 0.3s ease;
        }

        .url-block:hover {
            border-color: rgba(59, 130, 246, 0.3);
        }

        .url-block a {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: var(--accent-blue);
            text-decoration: none;
            word-break: break-all;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            direction: ltr;
            text-align: left;
        }

        .url-block a:hover {
            color: #60a5fa;
        }

        /* JSON PRE */
        .json-pre {
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--accent-emerald);
            overflow: auto;
            max-height: 320px;
            direction: ltr;
            text-align: left;
            line-height: 1.6;
        }

        /* ANIMATE IN */
        .animate-in {
            opacity: 0;
            transform: translateY(18px);
            animation: slideUp 0.5s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-1 {
            animation-delay: 0.05s;
        }

        .delay-2 {
            animation-delay: 0.10s;
        }

        .delay-3 {
            animation-delay: 0.15s;
        }

        .delay-4 {
            animation-delay: 0.20s;
        }

        .delay-5 {
            animation-delay: 0.25s;
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-base);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2e4166;
        }

        /* RT COLORS */
        .rt-fast {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .rt-medium {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .rt-slow {
            background: rgba(244, 63, 94, 0.15);
            color: #f43f5e;
            border: 1px solid rgba(244, 63, 94, 0.2);
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>

<body>
    <div class="content-wrapper">

        {{-- TOP NAV --}}
        <nav class="top-nav">
            <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a href="{{ route('dashboard.visit-logs.index') }}" class="btn-ghost"
                        style="border-color: transparent; padding: 8px 14px;">
                        <i class="fas fa-arrow-right text-sm"></i>
                        <span class="hidden sm:inline text-sm">سجل الزيارات</span>
                    </a>

                    <div class="flex items-center gap-3">
                        <div class="nav-logo-dot"></div>
                        <span style="font-size: 15px; font-weight: 600; color: var(--text-primary);">تفاصيل
                            الزيارة</span>
                    </div>

                    <a href="{{ route('dashboard') }}" class="btn-ghost" style="padding: 8px 14px;">
                        <i class="fas fa-gauge-high text-sm"></i>
                        <span class="hidden sm:inline text-sm">لوحة التحكم</span>
                    </a>
                </div>
            </div>
        </nav>

        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- PAGE HEADER --}}
            <div class="mb-8 animate-in">
                <p
                    style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: var(--accent-blue); margin-bottom: 8px;">
                    <i class="fas fa-circle text-xs ml-2" style="animation: blink 2s infinite;"></i>تفاصيل الزيارة
                </p>
                <h1
                    style="font-size: 2.2rem; font-weight: 700; color: var(--text-primary); line-height: 1.1; margin-bottom: 8px;">
                    سجل الزيارة #{{ $visitLog->id }}
                </h1>
                <p style="color: var(--text-muted); font-size: 15px;">معلومات تفصيلية كاملة عن هذه الزيارة</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- MAIN COL --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- BASIC INFO --}}
                    <div class="section-card animate-in delay-1">
                        <div class="section-header">
                            <div class="section-title">
                                <div class="section-icon"
                                    style="background: rgba(59,130,246,0.1); color: var(--accent-blue);">
                                    <i class="fas fa-circle-info"></i>
                                </div>
                                معلومات الزيارة
                            </div>
                        </div>
                        <div style="padding: 24px;">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div class="info-item blue">
                                    <div class="info-label">
                                        <i class="fas fa-calendar-alt" style="color: var(--accent-blue);"></i>
                                        التاريخ والوقت
                                    </div>
                                    <div class="info-value mono" style="font-size: 1rem;">
                                        {{ $visitLog->created_at->format('Y-m-d H:i:s') }}</div>
                                    <div class="info-sub">{{ $visitLog->created_at->diffForHumans() }}</div>
                                </div>

                                <div class="info-item emerald">
                                    <div class="info-label">
                                        <i class="fas fa-tag" style="color: var(--accent-emerald);"></i>
                                        نوع الزيارة
                                    </div>
                                    <div style="margin-top: 4px;">
                                        @if ($visitLog->is_unique_visit)
                                            <span class="badge badge-emerald"><i
                                                    class="fas fa-check-circle text-xs"></i>زيارة فريدة</span>
                                        @else
                                            <span class="badge badge-gray"><i class="fas fa-rotate text-xs"></i>تحديث
                                                صفحة</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="info-item violet">
                                    <div class="info-label">
                                        <i class="fas fa-user" style="color: var(--accent-violet);"></i>
                                        المستخدم
                                    </div>
                                    @if ($visitLog->user)
                                        <div class="info-value" style="font-size: 1rem;">{{ $visitLog->user->name }}
                                        </div>
                                        <div class="info-sub">{{ $visitLog->user->email }}</div>
                                    @else
                                        <span class="badge badge-gray" style="margin-top: 4px;"><i
                                                class="fas fa-user-secret text-xs"></i>زائر غير مسجل</span>
                                    @endif
                                </div>

                                <div class="info-item cyan">
                                    <div class="info-label">
                                        <i class="fas fa-network-wired" style="color: var(--accent-cyan);"></i>
                                        عنوان IP
                                    </div>
                                    <div style="margin-top: 6px;">
                                        <span class="ip-tag">{{ $visitLog->ip_address }}</span>
                                    </div>
                                </div>

                                <div class="info-item teal">
                                    <div class="info-label">
                                        <i class="fas fa-stopwatch" style="color: var(--accent-teal);"></i>
                                        المدة في الصفحة
                                    </div>
                                    @if ($visitLog->duration)
                                        <div class="info-value">{{ $visitLog->duration }} <span
                                                style="font-size: 0.9rem; color: var(--text-muted);">ثانية</span></div>
                                    @else
                                        <span
                                            style="color: var(--text-muted); font-size: 14px; margin-top: 4px; display: block;">غير
                                            محسوبة</span>
                                    @endif
                                </div>

                                <div class="info-item amber">
                                    <div class="info-label">
                                        <i class="fas fa-gauge-high" style="color: var(--accent-amber);"></i>
                                        وقت الاستجابة
                                    </div>
                                    @if ($visitLog->response_time)
                                        @php
                                            $rtClass =
                                                $visitLog->response_time > 1000
                                                    ? 'rt-slow'
                                                    : ($visitLog->response_time > 500
                                                        ? 'rt-medium'
                                                        : 'rt-fast');
                                        @endphp
                                        <div style="margin-top: 6px;">
                                            <span class="badge {{ $rtClass }} mono"
                                                style="font-size: 15px; padding: 6px 14px;">{{ number_format($visitLog->response_time, 2) }}
                                                <span style="font-size: 11px; opacity: 0.8;">ms</span></span>
                                        </div>
                                    @else
                                        <span
                                            style="color: var(--text-muted); font-size: 14px; margin-top: 4px; display: block;">—</span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- PAGE INFO --}}
                    <div class="section-card animate-in delay-2">
                        <div class="section-header">
                            <div class="section-title">
                                <div class="section-icon"
                                    style="background: rgba(6,182,212,0.1); color: var(--accent-cyan);">
                                    <i class="fas fa-link"></i>
                                </div>
                                معلومات الصفحة
                            </div>
                        </div>
                        <div style="padding: 24px;" class="space-y-5">

                            <div>
                                <div class="info-label" style="margin-bottom: 10px;">
                                    <i class="fas fa-globe" style="color: var(--accent-blue);"></i>
                                    الرابط (URL)
                                </div>
                                <div class="url-block">
                                    <a href="{{ $visitLog->url }}" target="_blank">
                                        <i class="fas fa-arrow-up-right-from-square"
                                            style="flex-shrink: 0; margin-top: 2px; font-size: 11px;"></i>
                                        {{ $visitLog->url }}
                                    </a>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div>
                                    <div class="info-label" style="margin-bottom: 10px;">
                                        <i class="fas fa-route" style="color: var(--accent-violet);"></i>
                                        اسم المسار
                                    </div>
                                    @if ($visitLog->route_name)
                                        <span class="badge badge-violet mono">{{ $visitLog->route_name }}</span>
                                    @else
                                        <span style="color: var(--text-muted);">—</span>
                                    @endif
                                </div>

                                <div>
                                    <div class="info-label" style="margin-bottom: 10px;">
                                        <i class="fas fa-code" style="color: var(--accent-emerald);"></i>
                                        الطريقة (Method)
                                    </div>
                                    @php
                                        $methodClass = match ($visitLog->method ?? '') {
                                            'GET' => 'method-get',
                                            'POST' => 'method-post',
                                            'PUT' => 'method-put',
                                            'PATCH' => 'method-patch',
                                            'DELETE' => 'method-delete',
                                            default => 'badge-gray',
                                        };
                                    @endphp
                                    <span class="badge {{ $methodClass }} mono"
                                        style="font-size: 15px; padding: 6px 14px;">{{ $visitLog->method }}</span>
                                </div>

                            </div>

                            @if ($visitLog->referer)
                                <div>
                                    <div class="info-label" style="margin-bottom: 10px;">
                                        <i class="fas fa-arrow-turn-up" style="color: var(--accent-amber);"></i>
                                        المرجع (Referer)
                                    </div>
                                    <div class="url-block">
                                        <a href="{{ $visitLog->referer }}" target="_blank"
                                            style="color: var(--accent-amber);">
                                            <i class="fas fa-arrow-up-right-from-square"
                                                style="flex-shrink: 0; margin-top: 2px; font-size: 11px;"></i>
                                            {{ $visitLog->referer }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <div>
                                <div class="info-label" style="margin-bottom: 10px;">
                                    <i class="fas fa-shield-check" style="color: var(--accent-emerald);"></i>
                                    رمز الحالة (Status Code)
                                </div>
                                @if ($visitLog->status_code)
                                    @php
                                        $sc = $visitLog->status_code;
                                        $scClass =
                                            $sc >= 200 && $sc < 300
                                                ? 'status-2xx'
                                                : ($sc >= 300 && $sc < 400
                                                    ? 'status-3xx'
                                                    : ($sc >= 400 && $sc < 500
                                                        ? 'status-4xx'
                                                        : 'status-5xx'));
                                    @endphp
                                    <span class="badge {{ $scClass }} mono"
                                        style="font-size: 18px; padding: 8px 18px;">{{ $visitLog->status_code }}</span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </div>

                        </div>
                    </div>

                    {{-- TECHNICAL INFO --}}
                    <div class="section-card animate-in delay-3">
                        <div class="section-header">
                            <div class="section-title">
                                <div class="section-icon"
                                    style="background: rgba(139,92,246,0.1); color: var(--accent-violet);">
                                    <i class="fas fa-microchip"></i>
                                </div>
                                معلومات تقنية
                            </div>
                        </div>
                        <div style="padding: 24px;" class="space-y-5">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div>
                                    <div class="info-label" style="margin-bottom: 10px;">
                                        <i class="fas fa-id-card" style="color: var(--accent-blue);"></i>
                                        Session ID
                                    </div>
                                    <div class="code-block">{{ $visitLog->session_id ?? '—' }}</div>
                                </div>

                                <div>
                                    <div class="info-label" style="margin-bottom: 10px;">
                                        <i class="fas fa-fingerprint" style="color: var(--accent-violet);"></i>
                                        Request ID
                                    </div>
                                    <div class="code-block">{{ $visitLog->request_id ?? '—' }}</div>
                                </div>

                            </div>

                            <div>
                                <div class="info-label" style="margin-bottom: 10px;">
                                    <i class="fas fa-laptop-code" style="color: var(--accent-cyan);"></i>
                                    User Agent
                                </div>
                                <div class="code-block" style="line-height: 1.6;">{{ $visitLog->user_agent ?? '—' }}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- SIDEBAR --}}
                <div class="space-y-6">

                    {{-- QUICK SUMMARY --}}
                    <div class="section-card animate-in delay-2">
                        <div class="section-header">
                            <div class="section-title">
                                <div class="section-icon"
                                    style="background: rgba(16,185,129,0.1); color: var(--accent-emerald);">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                ملخص سريع
                            </div>
                        </div>
                        <div style="padding: 20px;" class="space-y-3">

                            <div
                                style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                                <span style="font-size: 13px; color: var(--text-muted);">الرقم التعريفي</span>
                                <span class="mono"
                                    style="font-size: 13px; color: var(--accent-cyan);">#{{ $visitLog->id }}</span>
                            </div>

                            <div
                                style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                                <span style="font-size: 13px; color: var(--text-muted);">نوع الزيارة</span>
                                @if ($visitLog->is_unique_visit)
                                    <span class="badge badge-emerald"
                                        style="font-size: 11px; padding: 3px 8px;">فريدة</span>
                                @else
                                    <span class="badge badge-gray"
                                        style="font-size: 11px; padding: 3px 8px;">تحديث</span>
                                @endif
                            </div>

                            @if ($visitLog->status_code)
                                <div
                                    style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                                    <span style="font-size: 13px; color: var(--text-muted);">الحالة</span>
                                    @php
                                        $sc = $visitLog->status_code;
                                        $scClass =
                                            $sc >= 200 && $sc < 300
                                                ? 'status-2xx'
                                                : ($sc >= 300 && $sc < 400
                                                    ? 'status-3xx'
                                                    : ($sc >= 400 && $sc < 500
                                                        ? 'status-4xx'
                                                        : 'status-5xx'));
                                    @endphp
                                    <span class="badge {{ $scClass }} mono"
                                        style="font-size: 12px; padding: 3px 8px;">{{ $visitLog->status_code }}</span>
                                </div>
                            @endif

                            @if ($visitLog->method)
                                <div
                                    style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                                    <span style="font-size: 13px; color: var(--text-muted);">الطريقة</span>
                                    <span class="badge {{ $methodClass ?? 'badge-gray' }} mono"
                                        style="font-size: 12px; padding: 3px 8px;">{{ $visitLog->method }}</span>
                                </div>
                            @endif

                            @if ($visitLog->duration)
                                <div
                                    style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                                    <span style="font-size: 13px; color: var(--text-muted);">المدة</span>
                                    <span class="badge badge-teal mono"
                                        style="font-size: 12px; padding: 3px 8px;">{{ $visitLog->duration }}ث</span>
                                </div>
                            @endif

                            @if ($visitLog->response_time)
                                <div
                                    style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0;">
                                    <span style="font-size: 13px; color: var(--text-muted);">الاستجابة</span>
                                    <span class="badge {{ $rtClass ?? 'badge-gray' }} mono"
                                        style="font-size: 12px; padding: 3px 8px;">{{ number_format($visitLog->response_time, 0) }}ms</span>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- METADATA --}}
                    <div class="section-card animate-in delay-3">
                        <div class="section-header">
                            <div class="section-title">
                                <div class="section-icon"
                                    style="background: rgba(245,158,11,0.1); color: var(--accent-amber);">
                                    <i class="fas fa-map-location-dot"></i>
                                </div>
                                الميتاداتا
                            </div>
                        </div>
                        <div style="padding: 20px;" class="space-y-4">

                            @if ($visitLog->country)
                                <div class="info-item emerald" style="padding: 14px;">
                                    <div class="info-label"><i class="fas fa-map-marker-alt"
                                            style="color: var(--accent-emerald);"></i>الموقع</div>
                                    <div
                                        style="font-size: 15px; font-weight: 600; color: var(--text-primary); margin-top: 4px;">
                                        {{ $visitLog->city }}</div>
                                    <div class="info-sub">{{ $visitLog->country }}</div>
                                    @if ($visitLog->region)
                                        <div class="info-sub" style="margin-top: 2px;">{{ $visitLog->region }}</div>
                                    @endif
                                </div>
                            @endif

                            @if ($visitLog->browser)
                                <div class="info-item blue" style="padding: 14px;">
                                    <div class="info-label"><i class="fas fa-globe"
                                            style="color: var(--accent-blue);"></i>المتصفح</div>
                                    <div
                                        style="font-size: 15px; font-weight: 600; color: var(--text-primary); margin-top: 4px;">
                                        {{ $visitLog->browser }}</div>
                                    @if ($visitLog->platform)
                                        <div class="info-sub">{{ $visitLog->platform }}</div>
                                    @endif
                                </div>
                            @endif

                            @if ($visitLog->device)
                                <div class="info-item violet" style="padding: 14px;">
                                    <div class="info-label"><i class="fas fa-mobile-screen"
                                            style="color: var(--accent-violet);"></i>الجهاز</div>
                                    <div
                                        style="font-size: 15px; font-weight: 600; color: var(--text-primary); margin-top: 4px;">
                                        {{ $visitLog->device }}</div>
                                </div>
                            @endif

                            @if (isset($visitLog->metadata['isp']))
                                <div class="info-item amber" style="padding: 14px;">
                                    <div class="info-label"><i class="fas fa-network-wired"
                                            style="color: var(--accent-amber);"></i>مزود الخدمة (ISP)</div>
                                    <div style="font-size: 14px; color: var(--text-secondary); margin-top: 4px;">
                                        {{ $visitLog->metadata['isp'] }}</div>
                                </div>
                            @endif

                            @if ($visitLog->metadata)
                                <div style="padding-top: 16px; border-top: 1px solid var(--border);">
                                    <div class="info-label" style="margin-bottom: 10px;">
                                        <i class="fas fa-code" style="color: var(--text-muted);"></i>
                                        Raw JSON
                                    </div>
                                    <pre class="json-pre"><code>{{ json_encode($visitLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>

            {{-- FOOTER --}}
            <div
                style="text-align: center; padding: 24px 0; color: var(--text-muted); font-size: 13px; margin-top: 8px;">
                <p style="margin-bottom: 4px;">تفاصيل الزيارة — نظام إدارة عائلة السريع</p>
                <p style="font-size: 12px;">© {{ date('Y') }} جميع الحقوق محفوظة</p>
            </div>

        </div>
    </div>
</body>

</html>
