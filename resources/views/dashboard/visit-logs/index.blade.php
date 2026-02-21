@php
    // Helper function للترجمة
    $translateRouteFunc = function ($routeName) use ($translatedRouteNames) {
        return $translatedRouteNames[$routeName] ?? $routeName;
    };
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سجل الزيارات - لوحة التحكم</title>
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
            --border-glow: #2563eb;
            --accent-blue: #3b82f6;
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --accent-violet: #8b5cf6;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
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

        /* Animated background grid */
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

        /* STAT CARDS */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 16px;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            border-color: var(--glow-color, var(--accent-blue));
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.12), 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card.blue {
            --glow-color: var(--accent-blue);
        }

        .stat-card.emerald {
            --glow-color: var(--accent-emerald);
        }

        .stat-card.violet {
            --glow-color: var(--accent-violet);
        }

        .stat-card.amber {
            --glow-color: var(--accent-amber);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: rgba(59, 130, 246, 0.15);
            color: var(--accent-blue);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .stat-icon.emerald {
            background: rgba(16, 185, 129, 0.15);
            color: var(--accent-emerald);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .stat-icon.violet {
            background: rgba(139, 92, 246, 0.15);
            color: var(--accent-violet);
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .stat-icon.amber {
            background: rgba(245, 158, 11, 0.15);
            color: var(--accent-amber);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -1px;
        }

        .stat-bar {
            height: 3px;
            border-radius: 2px;
            margin-top: 16px;
            background: var(--border);
            overflow: hidden;
        }

        .stat-bar-fill {
            height: 100%;
            border-radius: 2px;
            transition: width 1.5s cubic-bezier(0.23, 1, 0.32, 1);
        }

        /* SECTION CARDS */
        .section-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
        }

        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            background: rgba(30, 45, 69, 0.3);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* FILTER FORM */
        .filter-input {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        .filter-input:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-input option {
            background: var(--bg-surface);
        }

        .filter-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* BUTTONS */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--accent-blue);
            color: white;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'IBM Plex Sans Arabic', sans-serif;
        }

        .btn-primary:hover {
            background: #2563eb;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
            transform: translateY(-1px);
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
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

        /* TABLE */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead tr {
            border-bottom: 1px solid var(--border);
        }

        .data-table thead th {
            padding: 14px 20px;
            text-align: right;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
            background: rgba(30, 45, 69, 0.2);
        }

        .data-table tbody tr {
            border-bottom: 1px solid rgba(30, 45, 69, 0.6);
            transition: all 0.2s ease;
        }

        .data-table tbody tr:hover {
            background: var(--bg-card-hover);
        }

        .data-table tbody td {
            padding: 16px 20px;
            font-size: 14px;
            color: var(--text-secondary);
            vertical-align: middle;
        }

        .data-table tbody tr:last-child {
            border-bottom: none;
        }

        /* BADGES */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-blue {
            background: rgba(59, 130, 246, 0.15);
            color: var(--accent-blue);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .badge-emerald {
            background: rgba(16, 185, 129, 0.15);
            color: var(--accent-emerald);
            border: 1px solid rgba(16, 185, 129, 0.2);
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

        .badge-gray {
            background: rgba(100, 116, 139, 0.15);
            color: var(--text-secondary);
            border: 1px solid rgba(100, 116, 139, 0.2);
        }

        .badge-cyan {
            background: rgba(6, 182, 212, 0.15);
            color: var(--accent-cyan);
            border: 1px solid rgba(6, 182, 212, 0.2);
        }

        /* MONO TEXT */
        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* IP BADGE */
        .ip-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 500;
            color: var(--accent-cyan);
            background: rgba(6, 182, 212, 0.08);
            border: 1px solid rgba(6, 182, 212, 0.2);
            padding: 3px 8px;
            border-radius: 6px;
        }

        /* PAGE CARDS */
        .page-card {
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .page-card:hover {
            border-color: rgba(59, 130, 246, 0.4);
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .page-card-stat {
            text-align: center;
            padding: 12px 8px;
            background: rgba(30, 45, 69, 0.4);
            border-radius: 10px;
        }

        .page-card-stat-val {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
        }

        .page-card-stat-label {
            font-size: 10px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 500;
        }

        /* PAGINATION OVERRIDE */
        .pagination-wrap nav span,
        .pagination-wrap nav a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            margin: 0 2px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination-wrap nav a {
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .pagination-wrap nav a:hover {
            border-color: var(--accent-blue);
            color: var(--accent-blue);
        }

        .pagination-wrap nav span[aria-current] {
            background: var(--accent-blue);
            color: white;
            border: 1px solid var(--accent-blue);
        }

        /* ANIMATE IN */
        .animate-in {
            opacity: 0;
            transform: translateY(20px);
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

        .delay-6 {
            animation-delay: 0.30s;
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

        /* DIVIDER */
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
            margin: 0;
        }

        /* STATUS CODE COLORS */
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

        /* RESPONSE TIME COLORS */
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

        /* COUNT TAG */
        .count-tag {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-blue);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        @media (max-width: 768px) {
            .stat-value {
                font-size: 1.8rem;
            }

            .hide-mobile {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="content-wrapper">

        {{-- TOP NAV --}}
        <nav class="top-nav">
            <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a href="{{ route('dashboard') }}" class="btn-ghost"
                        style="border-color: transparent; padding: 8px 14px;">
                        <i class="fas fa-arrow-right text-sm"></i>
                        <span class="hidden sm:inline text-sm">لوحة التحكم</span>
                    </a>

                    <div class="flex items-center gap-3">
                        <div class="nav-logo-dot"></div>
                        <span style="font-size: 15px; font-weight: 600; color: var(--text-primary);">سجل الزيارات</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <div
                            style="padding: 6px 14px; background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2); border-radius: 8px; font-size: 13px; color: var(--accent-blue);">
                            <i class="fas fa-eye ml-2 text-xs"></i>مراقبة مباشرة
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- PAGE HEADER --}}
            <div class="mb-8 animate-in">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div>
                        <p
                            style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: var(--accent-blue); margin-bottom: 8px;">
                            <i class="fas fa-circle text-xs ml-2" style="animation: blink 2s infinite;"></i>تحليلات
                            الزيارات
                        </p>
                        <h1
                            style="font-size: 2.2rem; font-weight: 700; color: var(--text-primary); line-height: 1.1; margin-bottom: 8px;">
                            سجل الزيارات
                        </h1>
                        <p style="color: var(--text-muted); font-size: 15px;">مراقبة تفصيلية لكل زيارة وإحصائياتها</p>
                    </div>
                </div>
            </div>

            {{-- STAT CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

                <div class="stat-card blue animate-in delay-1">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p
                                style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                                إجمالي الزيارات</p>
                            <div class="stat-value" style="color: var(--text-primary);">
                                {{ number_format($stats['total_visits']) }}</div>
                        </div>
                        <div class="stat-icon blue">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill" style="width: 100%; background: var(--accent-blue);"></div>
                    </div>
                    <p style="font-size: 12px; color: var(--text-muted); margin-top: 10px;">جميع الزيارات المسجلة</p>
                </div>

                <div class="stat-card emerald animate-in delay-2">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p
                                style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                                الزيارات الفعلية</p>
                            <div class="stat-value" style="color: var(--text-primary);">
                                {{ number_format($stats['actual_visits']) }}</div>
                        </div>
                        <div class="stat-icon emerald">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill"
                            style="width: {{ round(($stats['actual_visits'] / max($stats['total_visits'], 1)) * 100, 1) }}%; background: var(--accent-emerald);">
                        </div>
                    </div>
                    <p style="font-size: 12px; color: var(--text-muted); margin-top: 10px;">
                        <span
                            style="color: var(--accent-emerald); font-weight: 600;">{{ round(($stats['actual_visits'] / max($stats['total_visits'], 1)) * 100, 1) }}%</span>
                        من الإجمالي
                    </p>
                </div>

                <div class="stat-card violet animate-in delay-3">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p
                                style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                                زوار فريدون</p>
                            <div class="stat-value" style="color: var(--text-primary);">
                                {{ number_format($stats['unique_actual_visitors']) }}</div>
                        </div>
                        <div class="stat-icon violet">
                            <i class="fas fa-fingerprint"></i>
                        </div>
                    </div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill" style="width: 75%; background: var(--accent-violet);"></div>
                    </div>
                    <p style="font-size: 12px; color: var(--text-muted); margin-top: 10px;">الزيارات الفريدة فقط</p>
                </div>

                <div class="stat-card amber animate-in delay-4">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p
                                style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                                متوسط المدة</p>
                            <div class="stat-value" style="color: var(--text-primary);">
                                {{ number_format($stats['avg_duration']) }}<span
                                    style="font-size: 1.2rem; color: var(--text-muted);">ث</span></div>
                        </div>
                        <div class="stat-icon amber">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                    </div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill" style="width: 60%; background: var(--accent-amber);"></div>
                    </div>
                    <p style="font-size: 12px; color: var(--text-muted); margin-top: 10px;">ثانية لكل صفحة</p>
                </div>
            </div>

            {{-- FILTERS --}}
            <div class="section-card mb-8 animate-in delay-5">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-icon" style="background: rgba(59,130,246,0.1); color: var(--accent-blue);">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        فلاتر البحث
                    </div>
                    <button onclick="document.querySelector('form').reset()" type="button" class="btn-ghost"
                        style="padding: 6px 12px; font-size: 13px;">
                        <i class="fas fa-rotate-left text-xs"></i>
                        <span class="hidden sm:inline">إعادة تعيين</span>
                    </button>
                </div>

                <div style="padding: 24px;">
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

                        <div>
                            <label class="filter-label"><i class="fas fa-calendar-week ml-1"
                                    style="color: var(--accent-blue);"></i>الأسبوع</label>
                            <select name="week" class="filter-input">
                                <option value="">— الكل —</option>
                                @foreach ($weekOptions as $key => $label)
                                    <option value="{{ $key }}" @selected(request('week') === $key)>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="filter-label"><i class="fas fa-calendar-plus ml-1"
                                    style="color: var(--accent-emerald);"></i>من تاريخ</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                min="{{ $firstVisitDate ?? '' }}" max="{{ $lastVisitDate ?? '' }}"
                                class="filter-input">
                        </div>

                        <div>
                            <label class="filter-label"><i class="fas fa-calendar-minus ml-1"
                                    style="color: var(--accent-rose);"></i>إلى تاريخ</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                min="{{ $firstVisitDate ?? '' }}" max="{{ $lastVisitDate ?? '' }}"
                                class="filter-input">
                        </div>

                        <div>
                            <label class="filter-label"><i class="fas fa-network-wired ml-1"
                                    style="color: var(--accent-cyan);"></i>عنوان IP</label>
                            <input type="text" name="ip_address" value="{{ request('ip_address') }}"
                                placeholder="192.168.1.1" class="filter-input mono">
                        </div>

                        <div>
                            <label class="filter-label"><i class="fas fa-route ml-1"
                                    style="color: var(--accent-violet);"></i>اسم المسار</label>
                            <select name="route_name" class="filter-input">
                                <option value="">— الكل —</option>
                                @foreach ($routeNames as $route)
                                    <option value="{{ $route }}" @selected(request('route_name') === $route)>
                                        {{ $translatedRouteNames[$route] ?? $route }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="filter-label"><i class="fas fa-code ml-1"
                                    style="color: var(--accent-amber);"></i>الطريقة (Method)</label>
                            <select name="method" class="filter-input">
                                <option value="">— الكل —</option>
                                @foreach ($methods as $method)
                                    <option value="{{ $method }}" @selected(request('method') === $method)>
                                        {{ $method }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="filter-label"><i class="fas fa-magnifying-glass ml-1"
                                    style="color: var(--accent-blue);"></i>البحث</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="URL، شخص، مسار..." class="filter-input">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="btn-primary flex-1 justify-center">
                                <i class="fas fa-search text-xs"></i>
                                تطبيق الفلاتر
                            </button>
                            <a href="{{ route('dashboard.visit-logs.index') }}" class="btn-ghost"
                                style="padding: 10px 14px;" title="مسح الكل">
                                <i class="fas fa-xmark"></i>
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            {{-- VISITORS BY IP --}}
            @if ($stats['visitors_by_ip']->isNotEmpty())
                <div class="section-card mb-8 animate-in delay-6">
                    <div class="section-header">
                        <div class="section-title">
                            <div class="section-icon"
                                style="background: rgba(6,182,212,0.1); color: var(--accent-cyan);">
                                <i class="fas fa-users"></i>
                            </div>
                            الزوار حسب IP
                        </div>
                        <span class="count-tag">{{ $stats['visitors_by_ip']->count() }} شخص</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>عنوان IP</th>
                                    <th>إجمالي</th>
                                    <th>فريدة</th>
                                    <th>الصفحات</th>
                                    <th>متوسط المدة</th>
                                    <th class="hide-mobile">أول زيارة</th>
                                    <th class="hide-mobile">آخر زيارة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stats['visitors_by_ip'] as $visitor)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <span class="ip-tag">{{ $visitor['ip'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-gray mono">{{ $visitor['visit_count'] }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-emerald mono">{{ $visitor['unique_visit_count'] }}</span>
                                        </td>
                                        <td>
                                            <div class="flex flex-wrap gap-1">
                                                @if (isset($visitor['routes_translated']))
                                                    @foreach (array_slice($visitor['routes_translated'], 0, 3) as $index => $translatedRoute)
                                                        <span class="badge badge-blue"
                                                            title="{{ $visitor['routes'][$index] ?? '' }}">{{ $translatedRoute }}</span>
                                                    @endforeach
                                                    @if (count($visitor['routes']) > 3)
                                                        <span
                                                            class="badge badge-gray">+{{ count($visitor['routes']) - 3 }}</span>
                                                    @endif
                                                @else
                                                    @foreach (array_slice($visitor['routes'], 0, 3) as $route)
                                                        <span
                                                            class="badge badge-blue">{{ $translateRouteFunc($route) }}</span>
                                                    @endforeach
                                                    @if (count($visitor['routes']) > 3)
                                                        <span
                                                            class="badge badge-gray">+{{ count($visitor['routes']) - 3 }}</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-violet">{{ $visitor['avg_duration'] }} ث</span>
                                        </td>
                                        <td class="hide-mobile">
                                            <span class="mono" style="font-size: 12px; color: var(--text-muted);">
                                                {{ $visitor['first_visit'] ? $visitor['first_visit']->format('Y-m-d H:i') : '—' }}
                                            </span>
                                        </td>
                                        <td class="hide-mobile">
                                            <span class="mono" style="font-size: 12px; color: var(--text-muted);">
                                                {{ $visitor['last_visit'] ? $visitor['last_visit']->format('Y-m-d H:i') : '—' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- PAGES WITH DURATION --}}
            @if ($stats['pages_with_duration']->isNotEmpty())
                <div class="section-card mb-8 animate-in">
                    <div class="section-header">
                        <div class="section-title">
                            <div class="section-icon"
                                style="background: rgba(139,92,246,0.1); color: var(--accent-violet);">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            الصفحات الأكثر زيارة
                        </div>
                    </div>
                    <div style="padding: 24px;">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                            @foreach ($stats['pages_with_duration'] as $page)
                                <div class="page-card">
                                    <div
                                        style="margin-bottom: 14px; padding-bottom: 14px; border-bottom: 1px solid var(--border);">
                                        <h3
                                            style="font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">
                                            {{ $translateRouteFunc($page->route_name) }}
                                        </h3>
                                        <p class="mono"
                                            style="font-size: 11px; color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                            title="{{ $page->url }}">
                                            {{ \Illuminate\Support\Str::limit($page->url, 55) }}
                                        </p>
                                    </div>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div class="page-card-stat">
                                            <div class="page-card-stat-val" style="color: var(--accent-blue);">
                                                {{ $page->unique_count }}</div>
                                            <div class="page-card-stat-label">فريدة</div>
                                        </div>
                                        <div class="page-card-stat">
                                            <div class="page-card-stat-val" style="color: var(--accent-emerald);">
                                                {{ round($page->avg_duration ?? 0) }}</div>
                                            <div class="page-card-stat-label">متوسط (ث)</div>
                                        </div>
                                        <div class="page-card-stat">
                                            <div class="page-card-stat-val" style="color: var(--text-secondary);">
                                                {{ $page->visit_count }}</div>
                                            <div class="page-card-stat-label">إجمالي</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- VISITS TABLE --}}
            <div class="section-card mb-8 animate-in">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-icon"
                            style="background: rgba(59,130,246,0.1); color: var(--accent-blue);">
                            <i class="fas fa-table-list"></i>
                        </div>
                        قائمة الزيارات
                    </div>
                    <span class="count-tag">{{ $visitLogs->total() }} زيارة</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>شخص / مستخدم</th>
                                <th>الصفحة</th>
                                <th class="hide-mobile">المسار</th>
                                <th>المدة</th>
                                <th class="hide-mobile">الاستجابة</th>
                                <th>الحالة</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($visitLogs as $visit)
                                <tr>
                                    <td>
                                        <div style="display: flex; flex-direction: column; gap: 3px;">
                                            <span
                                                style="font-size: 13px; font-weight: 600; color: var(--text-primary);"
                                                class="mono">{{ $visit->created_at->format('Y-m-d') }}</span>
                                            <span style="font-size: 11px; color: var(--accent-cyan);"
                                                class="mono">{{ $visit->created_at->format('H:i:s') }}</span>
                                            <span
                                                style="font-size: 11px; color: var(--text-muted);">{{ $visit->created_at->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; flex-direction: column; gap: 5px;">
                                            @if ($visit->is_unique_visit)
                                                <span class="badge badge-emerald"><i
                                                        class="fas fa-circle text-xs"></i>فريدة</span>
                                            @else
                                                <span class="badge badge-gray"><i
                                                        class="fas fa-rotate text-xs"></i>تحديث</span>
                                            @endif
                                            <span class="ip-tag">{{ $visit->ip_address }}</span>
                                            @if ($visit->user)
                                                <span style="font-size: 11px; color: var(--text-muted);">
                                                    <i class="fas fa-user text-xs ml-1"></i>{{ $visit->user->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div style="max-width: 220px;">
                                            <div class="mono"
                                                style="font-size: 12px; color: var(--text-secondary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                title="{{ $visit->url }}">
                                                {{ \Illuminate\Support\Str::limit($visit->url, 45) }}
                                            </div>
                                            @if ($visit->referer)
                                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 3px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                    title="{{ $visit->referer }}">
                                                    <i class="fas fa-arrow-turn-up text-xs ml-1"
                                                        style="opacity: 0.5;"></i>{{ \Illuminate\Support\Str::limit($visit->referer, 35) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="hide-mobile">
                                        @if ($visit->route_name)
                                            <span class="badge badge-gray" style="font-size: 11px;"
                                                title="{{ $visit->route_name }}">{{ $translateRouteFunc($visit->route_name) }}</span>
                                        @else
                                            <span style="color: var(--text-muted);">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($visit->duration)
                                            <span class="badge badge-blue mono">{{ $visit->duration }}ث</span>
                                        @else
                                            <span style="color: var(--text-muted);">—</span>
                                        @endif
                                    </td>
                                    <td class="hide-mobile">
                                        @if ($visit->response_time)
                                            @php
                                                $rtClass =
                                                    $visit->response_time > 1000
                                                        ? 'rt-slow'
                                                        : ($visit->response_time > 500
                                                            ? 'rt-medium'
                                                            : 'rt-fast');
                                            @endphp
                                            <span
                                                class="badge {{ $rtClass }} mono">{{ number_format($visit->response_time, 0) }}ms</span>
                                        @else
                                            <span style="color: var(--text-muted);">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($visit->status_code)
                                            @php
                                                $sc = $visit->status_code;
                                                $scClass =
                                                    $sc >= 200 && $sc < 300
                                                        ? 'status-2xx'
                                                        : ($sc >= 300 && $sc < 400
                                                            ? 'status-3xx'
                                                            : ($sc >= 400 && $sc < 500
                                                                ? 'status-4xx'
                                                                : 'status-5xx'));
                                            @endphp
                                            <span
                                                class="badge {{ $scClass }} mono">{{ $visit->status_code }}</span>
                                        @else
                                            <span style="color: var(--text-muted);">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard.visit-logs.show', $visit) }}"
                                            style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: rgba(59,130,246,0.1); color: var(--accent-blue); border: 1px solid rgba(59,130,246,0.2); transition: all 0.2s ease; font-size: 13px;"
                                            onmouseover="this.style.background='rgba(59,130,246,0.2)'; this.style.boxShadow='0 0 10px rgba(59,130,246,0.3)'"
                                            onmouseout="this.style.background='rgba(59,130,246,0.1)'; this.style.boxShadow='none'"
                                            title="عرض التفاصيل">
                                            <i class="fas fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="padding: 60px 20px; text-align: center;">
                                        <div
                                            style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                                            <div
                                                style="width: 64px; height: 64px; background: rgba(30,45,69,0.6); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-database"
                                                    style="font-size: 24px; color: var(--text-muted);"></i>
                                            </div>
                                            <p
                                                style="font-size: 16px; font-weight: 600; color: var(--text-secondary);">
                                                لا توجد زيارات مسجلة</p>
                                            <p style="font-size: 13px; color: var(--text-muted);">جرب تغيير الفلاتر
                                                للعثور على نتائج</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="padding: 16px 20px; border-top: 1px solid var(--border); background: rgba(30,45,69,0.1);">
                    <div class="pagination-wrap">
                        {{ $visitLogs->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div style="text-align: center; padding: 24px 0; color: var(--text-muted); font-size: 13px;">
                <p style="margin-bottom: 4px;">سجل الزيارات — نظام إدارة عائلة السريع</p>
                <p style="font-size: 12px;">© {{ date('Y') }} جميع الحقوق محفوظة</p>
            </div>

        </div>
    </div>

    <script>
        // Animate stat bars on load
        window.addEventListener('load', () => {
            document.querySelectorAll('.stat-bar-fill').forEach(bar => {
                const w = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = w;
                }, 200);
            });
        });
    </script>
</body>

</html>
