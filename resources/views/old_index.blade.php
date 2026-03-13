<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تواصل عائلة السريِّع</title>

    {{-- 🎨 Stylesheets --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #37a05c;
            --primary-light: #4ade80;
            --primary-dark: #166534;
            --light-green: #DCF2DD;
            --dark-green: #145147;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;
            --mourning: #1b1b1b;
            --ease-smooth: cubic-bezier(0.22, 1, 0.36, 1);
            --ease-bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);
            --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.08);
            --shadow-strong: 0 16px 48px rgba(0, 0, 0, 0.15);
            --shadow-glow: 0 0 40px rgba(55, 160, 92, 0.3);
            --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-green) 100%);
            --gradient-light: linear-gradient(180deg, var(--light-green) 0%, #fff 100%);
            --card-radius: 20px;
            --card-width: 150px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--gradient-light);
            font-family: 'Alexandria', sans-serif;
            min-height: 100vh;
        }

        /* ===== خلفية متحركة ===== */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(55, 160, 92, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(20, 81, 71, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(220, 242, 221, 0.3) 0%, transparent 30%);
        }

        /* ===== القسم الرئيسي ===== */
        .tree-section {
            position: relative;
            padding-top: 100px;
            padding-bottom: 60px;
            min-height: 100vh;
            z-index: 1;
        }

        /* ===== العنوان الرئيسي ===== */
        .hero-header {
            text-align: center;
            padding: 2rem 1rem 3rem;
            position: relative;
        }

        .hero-title {
            font-size: clamp(1.75rem, 5vw, 3rem);
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.75rem;
            position: relative;
            display: inline-block;
        }

        .hero-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }
        /* ===== حاوية الشجرة ===== */
        .tree-container {
            padding: 1rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* ===== الشجرة الأفقية (أعمدة) ===== */
        .tree-horizontal {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 20px;
            padding: 1rem;
            min-width: max-content;
        }

        /* ===== العمود (يحتوي البطاقات بالطول) ===== */
        .tree-column {
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
        }

        /* ===== عنصر الشجرة (البطاقة + الأبناء) ===== */
        .tree-item {
            position: relative;
        }

        /* ===== بطاقة الشخص ===== */
        .person-card {
            position: relative;
            width: var(--card-width);
            background: #fff;
            border-radius: var(--card-radius);
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            transition: all 400ms var(--ease-smooth);
            border: 2px solid transparent;
            flex-shrink: 0;
        }

        .person-card::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: calc(var(--card-radius) + 2px);
            opacity: 0;
            z-index: -1;
            transition: opacity 300ms var(--ease-smooth);
        }

        .person-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-strong), var(--shadow-glow);
        }

        .person-card:hover::before {
            opacity: 0;
        }

        .person-card.active {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-strong), var(--shadow-glow);
        }

        .person-card.active::before {
            opacity: 1;
        }

        /* ===== بطاقة ابن الرضاعة ===== */
        .person-card.is-breastfeeding {
            border-color: #ec4899;
            background: linear-gradient(135deg, #fce4ec 0%, #fff 100%);
        }

        .person-card.is-breastfeeding::before {
            background: linear-gradient(90deg, #ec4899, #fce4ec);
        }

        .person-card.is-breastfeeding:hover {
            box-shadow: 0 12px 30px rgba(236, 72, 153, 0.25), 0 0 20px rgba(236, 72, 153, 0.15);
        }

        .breastfeeding-badge {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: linear-gradient(135deg, #ec4899, #be185d);
            color: #fff;
            font-size: 0.6rem;
            font-weight: 600;
            padding: 0.2rem 0.4rem;
            border-radius: 12px;
            z-index: 5;
            box-shadow: 0 2px 8px rgba(236, 72, 153, 0.3);
        }

        /* ===== محتوى البطاقة ===== */
        .card-header-section {
            position: relative;
            padding: 1rem 0.5rem 0.75rem;
            text-align: center;
            cursor: pointer;
            background: linear-gradient(180deg, rgba(220, 242, 221, 0.5) 0%, transparent 100%);
        }

        .card-header-section.has-photo {
            padding: 0;
            min-height: 160px;
            display: block;
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        .person-name-container {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 0.5rem 0.5rem;
            text-align: center;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 50%, transparent 100%);
            z-index: 2;
        }

        /* ===== صورة الشخص ===== */
        .person-avatar {
            position: relative;
            width: 85px;
            height: 85px;
            margin: 0 auto 0.5rem;
            border-radius: 50%;
            background: var(--light-green);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid #fff;
            box-shadow: 0 4px 20px rgba(55, 160, 92, 0.25);
            overflow: hidden;
            transition: all 300ms var(--ease-smooth);
        }

        .person-card:hover .person-avatar {
            transform: scale(1.05);
            box-shadow: 0 8px 30px rgba(55, 160, 92, 0.35);
        }

        .person-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .person-avatar .avatar-icon {
            font-size: 2.25rem;
            color: var(--primary-color);
        }

        /* ===== اسم الشخص ===== */
        .person-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
            margin: 0;
        }

        .card-header-section:not(.has-photo) .person-name {
            color: var(--dark-green);
            text-shadow: none;
        }

        /* ===== شارة الحداد ===== */
        .mourning-badge {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: linear-gradient(135deg, #1b1b1b, #3a3a3a);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.3rem 0.5rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            white-space: nowrap;
        }

        .is-deceased .person-avatar {
            box-shadow: 0 0 0 3px var(--mourning), 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        /* ===== زر التفاصيل ===== */
        .card-actions {
            display: flex;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        .action-btn {
            flex: 1;
            padding: 0.6rem 0.4rem;
            background: transparent;
            border: none;
            color: var(--dark-green);
            font-size: 0.75rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 200ms var(--ease-smooth);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }


        .action-btn:active {
            transform: scale(0.95);
        }

        .action-btn i {
            font-size: 0.8rem;
        }

        /* ===== خط الاتصال الأفقي ===== */
        .connection-line {
            position: absolute;
            right: 100%;
            top: 50%;
            width: 20px;
            height: 3px;
            background: var(--gradient-primary);
            border-radius: 2px;
            transform: translateY(-50%) scaleX(0);
            transform-origin: left center;
            transition: transform 400ms var(--ease-smooth);
            z-index: 1;
        }

        .tree-item.expanded > .person-card .connection-line {
            transform: translateY(-50%) scaleX(1);
        }

        /* ===== حاوية الأبناء ===== */
        .children-container {
            position: absolute;
            right: calc(100% + 20px);
            top: 0;
            display: none;
            flex-direction: column;
            gap: 12px;
            z-index: 10;
        }

        .children-container.show {
            display: flex;
        }

        /* ===== مؤشر التحميل ===== */
        .loading-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            gap: 1rem;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--light-green);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            color: var(--dark-green);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* ===== حالة فارغة ===== */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--dark-green);
            opacity: 0.7;
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        /* ===== أنيميشن الظهور ===== */
        .fade-in {
            animation: fadeIn 500ms var(--ease-smooth) forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .stagger-1 { animation-delay: 0ms; }
        .stagger-2 { animation-delay: 60ms; }
        .stagger-3 { animation-delay: 120ms; }
        .stagger-4 { animation-delay: 180ms; }
        .stagger-5 { animation-delay: 240ms; }

        /* ===== المودال ===== */
        .modal-content {
            border: 0;
            border-radius: 24px;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.2);
            background: linear-gradient(180deg, #ffffff 0%, #f9fbfa 100%);
            overflow: hidden;
        }

        .modal-header {
            background: var(--gradient-primary);
            color: #fff;
            padding: 1.25rem 1.5rem;
            border: 0;
        }

        .modal-header .modal-title {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
            opacity: 0.8;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 1.5rem;
        }

        /* ===== صفوف التفاصيل ===== */
        .detail-card {
            background: linear-gradient(180deg, #fcfcfc 0%, #f4f6f5 100%);
            padding: 1rem;
            border-radius: 16px;
            margin-bottom: 1rem;
            border: 1px solid #eef2f1;
            transition: all 200ms var(--ease-smooth);
        }

        .detail-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .detail-label {
            font-size: 0.75rem;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-weight: 600;
            color: var(--dark-green);
            font-size: 0.95rem;
        }

        .detail-value a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 200ms var(--ease-smooth);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .detail-value a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .detail-value a svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        /* ===== زر CTA ===== */
        .btn-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-primary);
            color: #fff !important;
            border: 0 !important;
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            gap: 8px;
            box-shadow: 0 8px 24px rgba(55, 160, 92, 0.3);
            transition: all 200ms var(--ease-smooth);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-cta:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 32px rgba(55, 160, 92, 0.4);
        }

        .btn-cta:active {
            transform: translateY(0) scale(1);
        }

        /* ===== بطاقات العلاقات ===== */
        .relation-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            padding: 12px;
            border-radius: 16px;
            border: 2px solid #eef2f1;
            cursor: pointer;
            transition: all 200ms var(--ease-smooth);
            min-height: 72px;
            height: 100%;
        }

        .relation-card:hover {
            border-color: var(--primary-color);
            background: var(--light-green);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(55, 160, 92, 0.15);
        }

        .relation-card img,
        .relation-card .avatar-placeholder {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .relation-card .avatar-placeholder {
            background: var(--light-green);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .relation-info {
            flex: 1;
            min-width: 0;
        }

        .relation-info strong {
            display: block;
            color: var(--dark-green);
            font-size: 0.9rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .relation-info small {
            color: #666;
            font-size: 0.75rem;
            display: block;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .relation-card.is-deceased .relation-info small {
            color: #dc3545;
            font-weight: 600;
        }

        .relation-card.is-breastfeeding {
            border-color: #ec4899;
            background: linear-gradient(135deg, #fce4ec 0%, #fff 100%);
        }

        .relation-card.is-breastfeeding:hover {
            border-color: #be185d;
            background: linear-gradient(135deg, #fce4ec 0%, #fff 100%);
            box-shadow: 0 8px 24px rgba(236, 72, 153, 0.2);
        }

        /* ===== حسابات التواصل ===== */
        .contact-grid {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem;
            background: #fff;
            border: 2px solid #eef2f1;
            border-radius: 12px;
            text-decoration: none;
            transition: all 200ms var(--ease-smooth);
            width: 44px;
            height: 44px;
        }

        .contact-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .contact-item i {
            font-size: 1.25rem;
        }

        .contact-item.whatsapp { border-color: #25D366; }
        .contact-item.whatsapp i { color: #25D366; }
        .contact-item.whatsapp span { color: #128C7E; }

        .contact-item.facebook { border-color: #1877F2; }
        .contact-item.facebook i, .contact-item.facebook span { color: #1877F2; }

        .contact-item.instagram { border-color: #E4405F; }
        .contact-item.instagram i {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .contact-item.instagram span { color: #E4405F; }

        .contact-item.twitter { border-color: #1DA1F2; }
        .contact-item.twitter i, .contact-item.twitter span { color: #1DA1F2; }

        .contact-item.telegram { border-color: #0088cc; }
        .contact-item.telegram i, .contact-item.telegram span { color: #0088cc; }

        .contact-item.linkedin { border-color: #0077B5; }
        .contact-item.linkedin i, .contact-item.linkedin span { color: #0077B5; }

        /* ===== المواقع (Google Maps) ===== */
        .location-item {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem;
            background: #fff;
            border: 2px solid #eef2f1;
            border-radius: 12px;
            text-decoration: none;
            transition: all 200ms var(--ease-smooth);
            width: 44px;
            height: 44px;
        }

        .location-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
            border-color: #ea4335;
        }

        .location-item i {
            font-size: 1.25rem;
            color: #ea4335;
        }

        /* ===== السيرة الذاتية ===== */
        .biography-wrapper {
            position: relative;
        }

        .biography-text {
            white-space: pre-wrap;
            transition: max-height 400ms var(--ease-smooth);
            overflow: hidden;
        }

        .biography-text.collapsed {
            max-height: 100px;
            -webkit-mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
            mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
        }

        .read-more-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            font-weight: 700;
            cursor: pointer;
            padding: 0.5rem 0;
            font-family: inherit;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            :root {
                --card-width: 120px;
                --card-radius: 16px;
            }

            .tree-section {
                padding-top: 80px;
            }

            .hero-header {
                padding: 1.5rem 0.5rem 2rem;
            }

            .hero-title {
                font-size: 1.5rem;
            }
            .tree-container {
                padding: 0.5rem;
            }

            .tree-horizontal {
                gap: 12px;
                padding: 0.5rem;
            }

            .tree-column {
                gap: 10px;
            }

            .tree-item {
                gap: 12px;
            }

            .card-header-section {
                padding: 0.75rem 0.4rem 0.5rem;
            }

            .card-header-section.has-photo {
                min-height: 130px;
            }

            .person-avatar {
                width: 65px;
                height: 65px;
            }

            .person-avatar .avatar-icon {
                font-size: 1.75rem;
            }

            .person-name {
                font-size: 0.75rem;
            }

            .mourning-badge {
                font-size: 0.55rem;
                padding: 0.25rem 0.4rem;
            }

            .action-btn {
                padding: 0.5rem 0.3rem;
                font-size: 0.65rem;
            }

            .action-btn i {
                font-size: 0.7rem;
            }

            .connection-line {
                width: 12px;
                right: 100%;
            }

            .children-container {
                right: calc(100% + 12px);
            }

            .modal-body {
                padding: 1rem;
            }

            .detail-card {
                padding: 0.75rem;
            }

            .btn-cta {
                padding: 0.6rem 1rem;
                font-size: 0.8rem;
            }

            .relation-card {
                padding: 10px;
                gap: 10px;
            }

            .relation-card img,
            .relation-card .avatar-placeholder {
                width: 40px;
                height: 40px;
            }

            .contact-grid {
                gap: 6px;
            }

            .contact-item {
                padding: 0.5rem;
                width: 40px;
                height: 40px;
            }

            .contact-item i {
                font-size: 1.1rem;
            }

            .location-item {
                padding: 0.5rem;
                width: 40px;
                height: 40px;
            }

            .location-item i {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            :root {
                --card-width: 100px;
            }

            .hero-title {
                font-size: 1.25rem;
            }

            .tree-horizontal {
                gap: 8px;
            }

            .tree-item {
                gap: 8px;
            }

            .tree-column {
                gap: 8px;
            }

            .person-avatar {
                width: 55px;
                height: 55px;
            }

            .person-avatar .avatar-icon {
                font-size: 1.5rem;
            }

            .person-name {
                font-size: 0.7rem;
            }

            .card-header-section.has-photo {
                min-height: 110px;
            }

            .action-btn {
                font-size: 0.6rem;
                padding: 0.4rem 0.2rem;
            }

            .connection-line {
                width: 8px;
                right: 100%;
            }

            .children-container {
                right: calc(100% + 8px);
            }
        }

        /* ===== دعم تقليل الحركة ===== */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* ===== Scrollbar ===== */
        ::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-green);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--dark-green);
        }
    </style>
</head>

<body>
    <div class="bg-pattern"></div>

    {{-- تضمين الهيدر من الملف المنفصل --}}
    @include('partials.main-header')

    <main>
        <section class="tree-section">
            <div class="container-fluid">
                <div class="hero-header">
                    <h1 class="hero-title">تواصل عائلة السريِّع</h1>
                </div>

                <div class="tree-container">
                    <div class="tree-horizontal" id="tree_root">
                        <div class="tree-column" id="tree_level_0">
                            <div class="loading-state">
                                <div class="loading-spinner"></div>
                                <p class="loading-text">جاري تحميل شجرة العائلة...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Modal --}}
    <div class="modal fade" id="personDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="modalBackBtn" class="btn btn-light btn-sm me-2 d-none">
                        <i class="fa-solid fa-arrow-right"></i> رجوع
                    </button>
                    <h5 class="modal-title">تفاصيل العضو</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBodyContent"></div>
            </div>
        </div>
    </div>

    {{-- Modal ملاحظات الرضاعة --}}
    <div class="modal fade" id="breastfeedingNotesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #ec4899, #be185d); color: #fff;">
                    <h5 class="modal-title"><i class="fas fa-baby me-2"></i>ملاحظات الرضاعة</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="breastfeedingNotesContent">
                    <!-- سيتم ملؤها بواسطة JavaScript -->
                </div>
            </div>
        </div>
    </div>

    {{-- مكون زر WhatsApp العائم --}}
    {{-- <x-whatsapp-group-button /> --}}

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const API_BASE_URL = '/api';
            const treeRoot = document.getElementById('tree_root');
            const treeContainer = document.getElementById('tree_level_0');
            const personDetailModalEl = document.getElementById('personDetailModal');
            const personModal = new bootstrap.Modal(personDetailModalEl);
            const modalBackBtn = document.getElementById('modalBackBtn');
            const modalHistory = [];

            function updateBackBtn() {
                modalBackBtn.classList.toggle('d-none', modalHistory.length <= 1);
            }

            async function fetchAPI(endpoint) {
                try {
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 15000);

                    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
                        signal: controller.signal,
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    });

                    clearTimeout(timeoutId);
                    if (!response.ok) throw new Error(`API Error: ${response.status}`);
                    return await response.json();
                } catch (error) {
                    console.error('API Fetch Error:', error);
                    return null;
                }
            }

            function createPersonCard(person, level = 0, staggerIndex = 0) {
                const hasChildren = person.children_count > 0;
                const isDeceased = !!person.death_date;
                const hasPhoto = !!person.photo_url;
                const isBreastfeeding = person.is_breastfeeding_child || false;
                const iconClass = person.gender === 'female' ? 'fa-female' : 'fa-male';
                const mourningText = isDeceased ? (person.gender === 'female' ? 'رحمها الله' : 'رحمه الله') : '';

                const photoSection = hasPhoto
                    ? `<div class="card-header-section has-photo" style="background-image: url('${person.photo_url}');" data-person-id="${person.id}" data-level="${level}" data-is-breastfeeding="${isBreastfeeding}" data-breastfeeding-notes="${(person.breastfeeding_notes || '').replace(/"/g, '&quot;')}" data-breastfeeding-start="${person.breastfeeding_start_date || ''}" data-breastfeeding-end="${person.breastfeeding_end_date || ''}">
                            ${isDeceased ? `<span class="mourning-badge">${mourningText}</span>` : ''}
                            <div class="person-name-container">
                                <span class="person-name">${person.first_name}</span>
                            </div>
                       </div>`
                    : `<div class="card-header-section" data-person-id="${person.id}" data-level="${level}" data-is-breastfeeding="${isBreastfeeding}" data-breastfeeding-notes="${(person.breastfeeding_notes || '').replace(/"/g, '&quot;')}" data-breastfeeding-start="${person.breastfeeding_start_date || ''}" data-breastfeeding-end="${person.breastfeeding_end_date || ''}">
                            ${isDeceased ? `<span class="mourning-badge">${mourningText}</span>` : ''}
                            <div class="person-avatar">
                                <i class="fas ${iconClass} avatar-icon"></i>
                            </div>
                            <span class="person-name">${person.first_name}</span>
                       </div>`;

                const staggerClass = staggerIndex <= 5 ? `stagger-${staggerIndex}` : '';

                return `
                    <div class="tree-item fade-in ${staggerClass}" data-person-id="${person.id}">
                        <div class="person-card ${isDeceased ? 'is-deceased' : ''}">
                            ${photoSection}
                            <div class="card-actions">
                                <button class="action-btn" onclick="showPersonDetails(${person.id})">
                                    <i class="fas fa-info-circle"></i>
                                    <span>التفاصيل</span>
                                </button>
                            </div>
                            ${hasChildren ? `<div class="connection-line"></div>` : ''}
                        </div>
                        ${hasChildren ? `<div class="children-container" id="children_${person.id}"></div>` : ''}
                    </div>
                `;
            }

            async function loadInitialTree() {
                const data = await fetchAPI('/family-tree');

                if (data && data.tree && data.tree.length > 0) {
                    treeContainer.innerHTML = data.tree.map((person, index) =>
                        createPersonCard(person, 0, index + 1)
                    ).join('');

                    initCardClicks();
                } else {
                    treeContainer.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-tree"></i>
                            <p>لا توجد بيانات لعرضها</p>
                        </div>
                    `;
                }
            }

            function initCardClicks() {
                document.querySelectorAll('.card-header-section').forEach(header => {
                    // Remove old listeners by cloning
                    const newHeader = header.cloneNode(true);
                    header.parentNode.replaceChild(newHeader, header);
                });

                document.querySelectorAll('.card-header-section').forEach(header => {
                    header.addEventListener('click', async function(e) {
                        // إذا كان ابن رضاعة، اعرض ملاحظات الرضاعة بدلاً من فتح الأبناء
                        const isBreastfeeding = this.dataset.isBreastfeeding === 'true';
                        if (isBreastfeeding) {
                            e.stopPropagation();
                            const notes = this.dataset.breastfeedingNotes || '';
                            const startDate = this.dataset.breastfeedingStart || '';
                            const endDate = this.dataset.breastfeedingEnd || '';
                            showBreastfeedingNotes(this.dataset.personId, notes, startDate, endDate);
                            return;
                        }

                        const personId = this.dataset.personId;
                        const level = parseInt(this.dataset.level, 10);
                        const treeItem = this.closest('.tree-item');
                        const card = this.closest('.person-card');
                        const childrenContainer = document.getElementById(`children_${personId}`);

                        if (!childrenContainer) return;

                        // Toggle - إذا كان مفتوح اغلقه
                        if (treeItem.classList.contains('expanded')) {
                            treeItem.classList.remove('expanded');
                            card.classList.remove('active');
                            childrenContainer.classList.remove('show');
                            return;
                        }

                        // إغلاق الأشقاء في نفس المستوى
                        const parentColumn = treeItem.parentElement;
                        parentColumn.querySelectorAll(':scope > .tree-item.expanded').forEach(item => {
                            item.classList.remove('expanded');
                            item.querySelector('.person-card')?.classList.remove('active');
                            item.querySelector('.children-container')?.classList.remove('show');
                        });

                        treeItem.classList.add('expanded');
                        card.classList.add('active');

                        // تحميل الأبناء إذا لم يتم تحميلهم
                        if (!childrenContainer.dataset.loaded) {
                            childrenContainer.innerHTML = `
                                <div class="loading-state" style="padding: 1rem;">
                                    <div class="loading-spinner" style="width: 30px; height: 30px;"></div>
                                </div>
                            `;
                            childrenContainer.classList.add('show');

                            const data = await fetchAPI(`/person/${personId}/children`);

                            if (data && data.children && data.children.length > 0) {
                                childrenContainer.innerHTML = data.children.map((child, index) =>
                                    createPersonCard(child, level + 1, index + 1)
                                ).join('');
                                childrenContainer.dataset.loaded = 'true';
                                initCardClicks();
                            } else {
                                childrenContainer.innerHTML = `
                                    <div class="empty-state" style="padding: 1rem;">
                                        <p style="font-size: 0.75rem;">لا يوجد أبناء</p>
                                    </div>
                                `;
                                childrenContainer.dataset.loaded = 'true';
                            }
                        } else {
                            childrenContainer.classList.add('show');
                        }
                    });
                });
            }

            function createPhoto(person, size = 'md') {
                const sizes = {
                    sm: { container: '48px', icon: '1.25rem' },
                    lg: { container: '120px', icon: '3rem' }
                };
                const currentSize = sizes[size] || sizes['lg'];
                const iconClass = person.gender === 'female' ? 'fa-female' : 'fa-male';

                if (person.photo_url) {
                    return `<img src="${person.photo_url}" alt="${person.first_name}"
                            style="width: ${currentSize.container}; height: ${currentSize.container}; border-radius: 50%; object-fit: cover;">`;
                }
                return `<div class="avatar-placeholder" style="width: ${currentSize.container}; height: ${currentSize.container}; font-size: ${currentSize.icon};">
                            <i class="fas ${iconClass}"></i>
                        </div>`;
            }

            window.showPersonDetails = async (personId, { push = true } = {}) => {
                const modalBody = document.getElementById('modalBodyContent');

                if (push) {
                    modalHistory.push(personId);
                    history.pushState({ personId }, '', `#person-${personId}`);
                }

                personModal.show();
                updateBackBtn();

                modalBody.innerHTML = `
                    <div class="loading-state">
                        <div class="loading-spinner"></div>
                        <p class="loading-text">جاري تحميل التفاصيل...</p>
                    </div>
                `;

                const data = await fetchAPI(`/person/${personId}`);
                if (!data || !data.person) {
                    modalBody.innerHTML = `<div class="alert alert-danger text-center">فشل تحميل البيانات</div>`;
                    return;
                }

                const person = data.person;

                // دالة تحويل التاريخ الميلادي للهجري (Intl - تقويم أم القرى)
                const gregorianToHijri = (gregorianDate) => {
                    if (!gregorianDate) return '';

                    try {
                        let trimmed = String(gregorianDate).trim().replace(/\//g, '-');
                        if (!trimmed || trimmed.startsWith('0000')) return '';
                        const normalized = trimmed.includes('T') ? trimmed : trimmed + 'T12:00:00Z';
                        const date = new Date(normalized);
                        if (isNaN(date.getTime())) return '';
                        const formatter = new Intl.DateTimeFormat('ar-SA-u-ca-islamic-umalqura', {
                            timeZone: 'UTC',
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                        const parts = formatter.formatToParts(date);
                        const day   = parts.find(p => p.type === 'day').value;
                        const month = parts.find(p => p.type === 'month').value;
                        const year  = parts.find(p => p.type === 'year').value;
                        return `${day} ${month} ${year}هـ`;
                    } catch (e) {
                        console.error('Error converting date:', e);
                        return '';
                    }
                };

                // دالة لعرض التاريخ بالميلادي والهجري
                const formatDateWithHijri = (dateString) => {
                    if (!dateString) return '';

                    try {
                        const iso = String(dateString).trim().replace(/\//g, '-');
                        const date = new Date(iso + (iso.includes('T') ? '' : 'T12:00:00Z'));
                        if (isNaN(date.getTime())) return dateString;

                        const arabicMonths = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
                                            'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];

                        const year = date.getUTCFullYear();
                        const month = date.getUTCMonth();
                        const day = date.getUTCDate();
                        const gregorianFormatted = `${day} ${arabicMonths[month]} ${year}`;

                        const hijri = gregorianToHijri(dateString);

                        return hijri ? `${gregorianFormatted}<br><small style="color: #666; font-size: 0.85em;">${hijri}</small>` : gregorianFormatted;
                    } catch (e) {
                        return dateString;
                    }
                };

                const createDetailCard = (label, value, url = null) => {
                    if (!value) return '';

                    // إذا كان الحقل تاريخ، اعرضه بالميلادي والهجري
                    const isDateField = label.includes('تاريخ');
                    const displayValue = isDateField ? formatDateWithHijri(value) : value;

                    // إذا كان هناك رابط، اجعل النص قابل للضغط
                    const valueContent = url
                        ? `<a href="${url}" target="_blank" rel="noopener noreferrer">
                                ${displayValue}
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                           </a>`
                        : displayValue;

                    return `<div class="col-6 col-md-4">
                        <div class="detail-card">
                            <div class="detail-label">${label}</div>
                            <div class="detail-value">${valueContent}</div>
                        </div>
                    </div>`;
                };

                let parentsHtml = '';
                if (person.parent || person.mother) {
                    parentsHtml = '<h6 class="mb-3"><i class="fas fa-users me-2"></i>الوالدين</h6><div class="row g-2 mb-4">';
                    if (person.parent) {
                        const statusText = person.parent.death_date ? '(رحمه الله)' : 'الأب';
                        parentsHtml += `
                            <div class="col-6">
                                <div class="relation-card ${person.parent.death_date ? 'is-deceased' : ''}" onclick="showPersonDetails(${person.parent.id})">
                                    ${createPhoto(person.parent, 'sm')}
                                    <div class="relation-info">
                                        <strong>${person.parent.first_name}</strong>
                                        <small>${statusText}</small>
                                    </div>
                                </div>
                            </div>`;
                    }
                    if (person.mother) {
                        const statusText = person.mother.death_date ? '(رحمها الله)' : 'الأم';
                        parentsHtml += `
                            <div class="col-6">
                                <div class="relation-card ${person.mother.death_date ? 'is-deceased' : ''}" onclick="showPersonDetails(${person.mother.id})">
                                    ${createPhoto(person.mother, 'sm')}
                                    <div class="relation-info">
                                        <strong>${person.mother.first_name}</strong>
                                        <small>${statusText}</small>
                                    </div>
                                </div>
                            </div>`;
                    }
                    parentsHtml += '</div>';

                    // إضافة معلومات "أم من الرضاعة" إذا كانت موجودة
                    if (person.breastfeeding_mothers && person.breastfeeding_mothers.length > 0) {
                        parentsHtml += '<h6 class="mb-3 mt-3"><i class="fas fa-baby me-2"></i>أم من الرضاعة</h6><div class="row g-2 mb-4">';
                        person.breastfeeding_mothers.forEach(breastfeedingMother => {
                            const statusText = breastfeedingMother.death_date ? '(رحمها الله)' : 'أم من الرضاعة';
                            const notes = (breastfeedingMother.notes || '').replace(/"/g, '&quot;');
                            const startDate = breastfeedingMother.start_date || '';
                            const endDate = breastfeedingMother.end_date || '';
                            parentsHtml += `
                                <div class="col-6">
                                    <div class="relation-card is-breastfeeding"
                                         data-person-id="${breastfeedingMother.id}"
                                         data-breastfeeding-notes="${notes}"
                                         data-breastfeeding-start="${startDate}"
                                         data-breastfeeding-end="${endDate}"
                                         style="cursor: pointer;">
                                        ${createPhoto(breastfeedingMother, 'sm')}
                                        <div class="relation-info">
                                            <strong>${breastfeedingMother.name}</strong>
                                            <small style="color: #ec4899;">${statusText}</small>
                                        </div>
                                    </div>
                                </div>`;
                        });

                        // إضافة event listeners لأمهات الرضاعة
                        setTimeout(() => {
                            document.querySelectorAll('.relation-card[data-breastfeeding-notes]').forEach(card => {
                                if (!card.dataset.personId) return;
                                card.addEventListener('click', function() {
                                    const notes = this.dataset.breastfeedingNotes || '';
                                    const startDate = this.dataset.breastfeedingStart || '';
                                    const endDate = this.dataset.breastfeedingEnd || '';
                                    showBreastfeedingNotes(this.dataset.personId, notes, startDate, endDate);
                                });
                            });
                        }, 100);
                        parentsHtml += '</div>';
                    }

                    // إضافة معلومات "أب من الرضاعة" إذا كان موجودًا
                    if (person.breastfeeding_fathers && person.breastfeeding_fathers.length > 0) {
                        parentsHtml += '<h6 class="mb-3 mt-3"><i class="fas fa-baby me-2"></i>أب من الرضاعة</h6><div class="row g-2 mb-4">';
                        person.breastfeeding_fathers.forEach(breastfeedingFather => {
                            const statusText = breastfeedingFather.death_date ? '(رحمه الله)' : 'أب من الرضاعة';
                            const notes = (breastfeedingFather.notes || '').replace(/"/g, '&quot;');
                            const startDate = breastfeedingFather.start_date || '';
                            const endDate = breastfeedingFather.end_date || '';
                            parentsHtml += `
                                <div class="col-6">
                                    <div class="relation-card is-breastfeeding"
                                         data-person-id="${breastfeedingFather.id}"
                                         data-breastfeeding-notes="${notes}"
                                         data-breastfeeding-start="${startDate}"
                                         data-breastfeeding-end="${endDate}"
                                         style="cursor: pointer;">
                                        ${createPhoto(breastfeedingFather, 'sm')}
                                        <div class="relation-info">
                                            <strong>${breastfeedingFather.name}</strong>
                                            <small style="color: #ec4899;">${statusText}</small>
                                        </div>
                                    </div>
                                </div>`;
                        });

                        // إضافة event listeners لآباء الرضاعة
                        setTimeout(() => {
                            document.querySelectorAll('.relation-card.is-breastfeeding[data-breastfeeding-notes]').forEach(card => {
                                if (!card.dataset.personId) return;
                                const existingListener = card.getAttribute('data-listener-added');
                                if (!existingListener) {
                                    card.setAttribute('data-listener-added', 'true');
                                    card.addEventListener('click', function() {
                                        const notes = this.dataset.breastfeedingNotes || '';
                                        const startDate = this.dataset.breastfeedingStart || '';
                                        const endDate = this.dataset.breastfeedingEnd || '';
                                        showBreastfeedingNotes(this.dataset.personId, notes, startDate, endDate);
                                    });
                                }
                            });
                        }, 100);

                        parentsHtml += '</div>';
                    }
                }

                let spousesHtml = '';
                if (person.spouses && person.spouses.length > 0) {
                    const label = person.gender === 'female' ? 'الزوج' : 'الزوجات';
                    spousesHtml = `<h6 class="mb-3"><i class="fas fa-heart me-2"></i>${label}</h6><div class="row g-2 mb-4">`;
                    person.spouses.forEach(spouse => {
                        const statusText = spouse.death_date
                            ? (spouse.gender === 'female' ? '(رحمها الله)' : '(رحمه الله)')
                            : (spouse.gender === 'female' ? 'زوجة' : 'زوج');
                        const fullName = spouse.full_name || spouse.first_name || 'غير معروف';
                        spousesHtml += `
                            <div class="col-6">
                                <div class="relation-card ${spouse.death_date ? 'is-deceased' : ''}" onclick="showPersonDetails(${spouse.id})">
                                    ${createPhoto(spouse, 'sm')}
                                    <div class="relation-info">
                                        <strong>${fullName}</strong>
                                        <small>${statusText}</small>
                                    </div>
                                </div>
                            </div>`;
                    });
                    spousesHtml += '</div>';
                }

                let childrenHtml = person.children_count > 0
                    ? `<h6 class="mb-3"><i class="fas fa-child me-2"></i>الأبناء (${person.children_count})</h6>
                       <div class="row g-2 mb-4" id="modalChildrenList">
                           <div class="col-12 text-center text-muted py-3">جاري التحميل...</div>
                       </div>`
                    : '';

                let biographyHtml = '';
                if (person.biography && person.biography.trim()) {
                    biographyHtml = `
                        <h6 class="mb-3"><i class="fas fa-book me-2"></i>نبذة</h6>
                        <div class="biography-wrapper mb-4">
                            <p id="biographyText" class="biography-text">${person.biography}</p>
                            <button id="readMoreBtn" class="read-more-btn d-none" onclick="toggleBiography(this)">عرض المزيد</button>
                        </div>`;
                }

                // دمج وسائل التواصل والمواقع في نفس الـ grid
                let contactsAndLocationsHtml = '';
                const hasContacts = person.contact_accounts && person.contact_accounts.length > 0;
                const hasLocations = person.locations && person.locations.length > 0;

                if (hasContacts || hasLocations) {
                    contactsAndLocationsHtml = `<div class="contact-grid">`;

                    // إضافة وسائل التواصل
                    if (hasContacts) {
                        person.contact_accounts.forEach(account => {
                            const brandIcons = ['whatsapp', 'facebook', 'instagram', 'twitter', 'linkedin', 'telegram'];
                            const iconClass = brandIcons.includes(account.type) ? 'fab' : 'fas';
                            const typeClass = account.type ? account.type.toLowerCase() : '';
                            contactsAndLocationsHtml += `
                                <a href="${account.url}" target="_blank" class="contact-item ${typeClass}" title="${account.label || account.value || account.type}">
                                    <i class="${iconClass} ${account.icon || 'fa-link'}"></i>
                                </a>`;
                        });
                    }

                    // إضافة المواقع (Google Maps) - فقط إذا كان هناك رابط محفوظ
                    if (hasLocations) {
                        person.locations.forEach(location => {
                            // جلب الرابط مباشرة من location
                            const savedUrl = location.url;

                            // عرض الأيقونة فقط إذا كان هناك رابط محفوظ وليس فارغاً
                            if (savedUrl !== null && savedUrl !== undefined && typeof savedUrl === 'string' && savedUrl.trim() !== '') {
                                let locationUrl = savedUrl.trim();
                                // إذا كان الرابط لا يبدأ بـ http أو https، أضف https://
                                if (!locationUrl.match(/^https?:\/\//i)) {
                                    locationUrl = 'https://' + locationUrl;
                                }

                                const locationTitle = location.label ? `${location.label} - ${location.name}` : location.name;
                                contactsAndLocationsHtml += `
                                    <a href="${locationUrl}" target="_blank" class="location-item" title="${locationTitle}">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </a>`;
                            }
                            // إذا لم يكن هناك رابط، لا نعرض الأيقونة
                        });
                    }

                    contactsAndLocationsHtml += '</div>';
                }

                let galleryBtn = person.images_count > 0
                    ? `<a class="btn-cta" onclick="openPersonGallery(${person.id})" href="javascript:void(0)">
                            <i class="fas fa-images"></i> الصور
                       </a>` : '';

                modalBody.innerHTML = `
                    <div class="row g-4">
                        <div class="col-lg-4 text-center">
                            <div class="mb-3 ${person.death_date ? 'is-deceased' : ''}">
                                ${createPhoto(person, 'lg')}
                            </div>
                            <h4 class="mb-1">${person.full_name || person.first_name}</h4>
                            ${person.death_date ? `<p class="text-muted small mb-3">${person.gender === 'female' ? 'رحمها الله' : 'رحمه الله'}</p>` : ''}
                            ${contactsAndLocationsHtml}
                            <div class="d-flex justify-content-center gap-2 flex-wrap mb-4">
                                ${galleryBtn}
                                <div id="personStoriesButton"></div>
                                <div id="personFriendshipsButton"></div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row g-2 mb-4">
                                ${person.gender === 'male' ? createDetailCard('تاريخ الميلاد', person.birth_date) : ''}
                                ${person.gender === 'male' && person.age ? createDetailCard('العمر', `${person.age} سنة`) : ''}
                                ${createDetailCard('مكان الميلاد', person.birth_place)}
                                ${createDetailCard('مكان الإقامة', person.location?.name || person.location, person.location?.url)}
                                ${createDetailCard('المهنة', person.occupation)}
                                ${person.death_date ? createDetailCard('تاريخ الوفاة', person.death_date) : ''}
                                ${createDetailCard('مكان الوفاة', person.death_place)}
                                ${createDetailCard('المقبرة', person.cemetery)}
                            </div>
                            ${parentsHtml}
                            ${spousesHtml}
                            ${biographyHtml}
                            ${childrenHtml}
                        </div>
                    </div>
                `;

                setupBiography();
                if (person.children_count > 0) loadModalChildren(person.id);
                insertStoriesButton(person.id);
                insertFriendshipsButton(person.id);
            };

            function setupBiography() {
                const textEl = document.getElementById('biographyText');
                const btnEl = document.getElementById('readMoreBtn');
                if (!textEl || !btnEl) return;
                if (textEl.scrollHeight > 100) {
                    textEl.classList.add('collapsed');
                    btnEl.classList.remove('d-none');
                }
            }

            window.toggleBiography = (btn) => {
                const text = document.getElementById('biographyText');
                text.classList.toggle('collapsed');
                btn.textContent = text.classList.contains('collapsed') ? 'عرض المزيد' : 'عرض أقل';
            };

            async function loadModalChildren(personId) {
                const container = document.getElementById('modalChildrenList');
                if (!container) return;

                const data = await fetchAPI(`/person/${personId}/children-details`);
                container.innerHTML = '';

                if (data && data.children && data.children.length > 0) {
                    data.children.forEach(child => {
                        const statusText = child.death_date
                            ? (child.gender === 'male' ? '(رحمه الله)' : '(رحمها الله)')
                            : (child.gender === 'female' ? 'ابنة' : 'ابن');
                        const isBreastfeeding = child.is_breastfeeding_child || false;
                        const breastfeedingClass = isBreastfeeding ? 'is-breastfeeding' : '';
                        const notes = (child.breastfeeding_notes || '').replace(/"/g, '&quot;');
                        const startDate = child.breastfeeding_start_date || '';
                        const endDate = child.breastfeeding_end_date || '';
                        container.innerHTML += `
                            <div class="col-6 col-md-4 d-flex">
                                <div class="relation-card ${child.death_date ? 'is-deceased' : ''} ${breastfeedingClass} w-100"
                                     data-person-id="${child.id}"
                                     data-is-breastfeeding="${isBreastfeeding}"
                                     data-breastfeeding-notes="${notes}"
                                     data-breastfeeding-start="${startDate}"
                                     data-breastfeeding-end="${endDate}"
                                     style="cursor: pointer;">
                                    ${createPhoto(child, 'sm')}
                                    <div class="relation-info flex-grow-1">
                                        <strong>${child.first_name}</strong>
                                        <small>${statusText}${isBreastfeeding ? ' <span style="color: #ec4899;">(من الرضاعة)</span>' : ''}</small>
                                    </div>
                                </div>
                            </div>`;
                    });

                    // إضافة event listeners للأبناء في الـ modal
                    container.querySelectorAll('.relation-card').forEach(card => {
                        card.addEventListener('click', function() {
                            const isBreastfeeding = this.dataset.isBreastfeeding === 'true';
                            if (isBreastfeeding) {
                                const notes = this.dataset.breastfeedingNotes || '';
                                const startDate = this.dataset.breastfeedingStart || '';
                                const endDate = this.dataset.breastfeedingEnd || '';
                                showBreastfeedingNotes(this.dataset.personId, notes, startDate, endDate);
                            } else {
                                showPersonDetails(this.dataset.personId);
                            }
                        });
                    });
                } else {
                    container.innerHTML = `<div class="col-12 text-center text-muted py-2">لا يوجد أبناء مسجلين</div>`;
                }
            }

            async function insertStoriesButton(personId) {
                try {
                    const res = await fetch(`/api/person/${personId}/stories/count`, { headers: { 'Accept': 'application/json' }});
                    if (!res.ok) return;
                    const data = await res.json();
                    if (data && data.count > 0) {
                        const holder = document.getElementById('personStoriesButton');
                        if (holder) {
                            holder.innerHTML = `
                                <a class="btn-cta" href="/stories/person/${personId}">
                                    <i class="fas fa-book-open"></i> أحداث وقصص
                                </a>`;
                        }
                    }
                } catch (e) { console.warn('Stories count failed', e); }
            }

            async function insertFriendshipsButton(personId) {
                try {
                    const res = await fetch(`/api/person/${personId}/friendships/count`, { headers: { 'Accept': 'application/json' }});
                    if (!res.ok) return;
                    const data = await res.json();
                    if (data && data.count > 0) {
                        const holder = document.getElementById('personFriendshipsButton');
                        if (holder) {
                            holder.innerHTML = `
                                <a class="btn-cta" href="javascript:void(0)" onclick="showFriendships(${personId})">
                                    <i class="fas fa-user-friends"></i> الأصدقاء
                                </a>`;
                        }
                    }
                } catch (e) { console.warn('Friendships count failed', e); }
            }

            window.showFriendships = (personId) => window.open(`/person/${personId}/friends`, '_blank');
            window.openPersonGallery = (personId) => window.open(`/person-gallery/${personId}`, '_blank');

            // دالة عرض ملاحظات الرضاعة
            window.showBreastfeedingNotes = (personId, notes, startDate, endDate) => {
                const modal = new bootstrap.Modal(document.getElementById('breastfeedingNotesModal'));
                const content = document.getElementById('breastfeedingNotesContent');

                let notesHtml = '';
                if (notes && notes.trim()) {
                    notesHtml = `<div class="mb-3">
                        <h6 class="mb-2"><i class="fas fa-sticky-note me-2"></i>الملاحظات:</h6>
                        <p style="white-space: pre-wrap; line-height: 1.8; color: #555;">${notes}</p>
                    </div>`;
                } else {
                    notesHtml = `<div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>لا توجد ملاحظات مسجلة عن هذه الرضاعة
                    </div>`;
                }

                let datesHtml = '';
                if (startDate || endDate) {
                    datesHtml = `<div class="mt-3 p-3" style="background: #f8f9fa; border-radius: 12px;">
                        <h6 class="mb-2"><i class="fas fa-calendar me-2"></i>تواريخ الرضاعة:</h6>
                        <div class="row">
                            ${startDate ? `<div class="col-6"><strong>تاريخ البداية:</strong><br>${startDate}</div>` : ''}
                            ${endDate ? `<div class="col-6"><strong>تاريخ النهاية:</strong><br>${endDate}</div>` : ''}
                        </div>
                    </div>`;
                }

                content.innerHTML = notesHtml + datesHtml;
                modal.show();
            };

            // Modal back button
            modalBackBtn.addEventListener('click', () => {
                if (modalHistory.length > 1) {
                    modalHistory.pop();
                    const prevId = modalHistory[modalHistory.length - 1];
                    history.back();
                    window.showPersonDetails(prevId, { push: false });
                    updateBackBtn();
                } else {
                    personModal.hide();
                }
            });

            // Clean up on modal close
            personDetailModalEl.addEventListener('hidden.bs.modal', () => {
                modalHistory.length = 0;
                updateBackBtn();
                if (location.hash.startsWith('#person-')) {
                    history.replaceState(null, '', location.pathname + location.search);
                }
            });

            // Handle browser back/forward
            window.addEventListener('popstate', (event) => {
                const state = event.state;
                if (state && state.personId) {
                    if (modalHistory.length === 0 || modalHistory[modalHistory.length - 1] !== state.personId) {
                        personModal.show();
                        window.showPersonDetails(state.personId, { push: false });
                    }
                    const idx = modalHistory.lastIndexOf(state.personId);
                    if (idx !== -1) modalHistory.splice(idx + 1);
                    updateBackBtn();
                } else if (document.body.classList.contains('modal-open')) {
                    personModal.hide();
                }
            });

            // Load tree
            loadInitialTree();

            // Handle hash on load
            if (location.hash.startsWith('#person-')) {
                const match = location.hash.match(/#person-(\d+)/);
                if (match) {
                    setTimeout(() => window.showPersonDetails(match[1], { push: false }), 1000);
                }
            }
        });
    </script>
</body>

</html>
