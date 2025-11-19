<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تواصل عائلة السريِّع </title>

    {{-- 🎨 Stylesheets --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-color: #37a05c;
            --light-green: #DCF2DD;
            --dark-green: #145147;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;

            /* ألوان/خصائص الحداد */
            --mourning: #1b1b1b;

            /* تحسينات حركة وتوهّج */
            --ease-smooth: cubic-bezier(0.22, 1, 0.36, 1);
            --shadow-soft: 0 6px 18px rgba(0, 0, 0, 0.08);
            --shadow-strong: 0 14px 36px rgba(0, 0, 0, 0.12);
        }

        body {
            background: var(--light-gray);
            font-family: 'Alexandria', sans-serif;
        }

        /* --- START: Tree View Styles --- */
        .tree-section {
            background: linear-gradient(180deg, var(--light-green) 0%, #FFF 100%);
            padding-top: 120px; /* مساحة كافية للهيدر الثابت */
            padding-bottom: 50px;
            min-height: 100vh;
            overflow-x: auto;
        }

        .tree-title-sec {
            margin-bottom: 2rem;
            text-align: center;
        }

        .tree-title-sec h3 {
            color: var(--dark-green);
            font-weight: 700;
        }

        .accordion-group-item,
        .accordion-item {
            position: relative;
            width: 200px;
            border: 1px solid var(--border-color) !important;
            border-radius: 12px;
            box-shadow: var(--shadow-soft);
            transition: transform 300ms var(--ease-smooth), box-shadow 300ms var(--ease-smooth), background-color 300ms var(--ease-smooth);
            background-color: #fff;
            overflow: visible; /* للسماح بتموضع العناصر الداخلية */
        }

        .accordion-group-item + .accordion-group-item,
        .accordion-item + .accordion-item {
            margin-top: 10px;
        }

        .accordion-group-item:hover,
        .accordion-item:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-strong);
        }

        /* ظهور انسيابي عند دخول العناصر إلى الشاشة */
        .reveal { opacity: 0; transform: translateY(16px); }
        .reveal.in-view { opacity: 1; transform: translateY(0); transition: opacity 500ms var(--ease-smooth), transform 500ms var(--ease-smooth); }

        .accordion-collapse {
            position: absolute;
            right: 100%;
            width: 200px;
            top: 0;
            padding-right: 25px;
            z-index: 10;
        }

        .accordion-button {
            border-radius: 11px 11px 0 0 !important;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 10px;
            flex-direction: column;
            transition: background-color 250ms var(--ease-smooth), color 250ms var(--ease-smooth), box-shadow 250ms var(--ease-smooth);
        }

        .accordion-button::after { display: none; }
        .accordion-button:focus { box-shadow: none; }

        .accordion-button.photo-bg {
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            min-height: 180px;
            padding: 0.75rem;
            color: #fff !important;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }
        .accordion-button.photo-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.12) 60%);
            border-radius: inherit;
            z-index: 1;
            transition: background 300ms var(--ease-smooth);
        }
        .accordion-button.photo-bg .person-name {
            font-weight: 600;
            z-index: 2;
            color: #fff !important;
            margin-top: auto;
        }
        .accordion-button.photo-bg .person-photo-container { display: none; }

        .accordion-button:not(.photo-bg) { gap: 10px; background-color: #fff; }

        .accordion-button .person-photo-container {
            width: 120px !important;
            height: 120px !important;
            margin-bottom: 10px;
        }
        .accordion-button .person-photo-container .icon-placeholder { font-size: 5rem !important; }

        .accordion-button:not(.photo-bg) .person-name { color: #333; font-weight: 600; }

        .accordion-button:not(.collapsed) { color: white !important; }
        .accordion-button.photo-bg:not(.collapsed) {
            box-shadow: inset 0 0 0 3px var(--dark-green);
            background-color: transparent !important;
        }
        .accordion-button:not(.photo-bg):not(.collapsed) { background-color: var(--dark-green) !important; }
        .accordion-button:not(.photo-bg):not(.collapsed) .person-name { color: #fff; }

        .person-photo-container {
            position: relative; /* مهم لوضع البادج داخل الصورة */
            background-color: var(--light-green);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border-radius: 50%;
        }
        .person-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .person-photo-container .icon-placeholder { color: var(--primary-color); }

        /* أيقونة قديمة للوفاة — نخفيها عندما نستخدم شارة الحداد */
        .deceased-icon {
            position: absolute;
            bottom: 0; left: 0;
            width: 24px; height: 24px;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; border: 1px solid #fff;
        }

        .actions-bar {
            width: 100%;
            display: flex;
            border-top: 1px solid var(--border-color);
            background: linear-gradient(180deg, #fafafa 0%, #f3f4f6 100%);
            border-radius: 0 0 12px 12px;
        }
        .actions-bar .btn {
            flex: 1; font-size: 13px; padding: 10px 6px; color: var(--dark-green); border-radius: 0;
            transition: background-color 200ms var(--ease-smooth), color 200ms var(--ease-smooth), transform 150ms var(--ease-smooth);
            position: relative; overflow: hidden;
        }
        .actions-bar .btn:hover { background-color: #e9ecef; transform: translateY(-1px); }
        .actions-bar .btn:first-child { border-radius: 0 0 11px 0; }
        .actions-bar .btn:last-child { border-radius: 0 0 0 11px; }

        /* تأثير تموّج بسيط لزر الإجراءات (بدون JS إضافي) */
        .actions-bar .btn::after {
            content: '';
            position: absolute;
            inset: auto 50% 50% auto;
            width: 0; height: 0;
            background: rgba(20, 81, 71, 0.12);
            border-radius: 50%;
            transform: translate(50%, 50%);
            transition: width 400ms var(--ease-smooth), height 400ms var(--ease-smooth), opacity 400ms var(--ease-smooth);
            opacity: 0;
        }
        .actions-bar .btn:hover::after { width: 220px; height: 220px; opacity: 1; }

        .modal-header { background-color: var(--dark-green); color: #fff; }
        .modal-header .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        /* تحسين مظهر المودال بالكامل */
        .modal-content {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.18);
            background: linear-gradient(180deg, #ffffff 0%, #f9fbfa 100%);
        }
        .modal-body { padding: 1.25rem 1.25rem 1.5rem 1.25rem; }

        /* زر نداء الإجراء (لأزرار مثل معرض الصور والقصص) */
        .btn-cta {
            display: inline-flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #2fb76e 0%, #1f9a57 100%);
            color: #fff !important; border: 0 !important;
            padding: 12px 20px; border-radius: 25px;
            gap: 8px;
            box-shadow: 0 12px 30px rgba(47, 183, 110, 0.28);
            transition: transform 160ms var(--ease-smooth), box-shadow 200ms var(--ease-smooth), filter 200ms var(--ease-smooth);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .btn-cta:hover { transform: translateY(-2px) scale(1.05); box-shadow: 0 18px 38px rgba(31, 154, 87, 0.32); filter: brightness(1.02); }
        .btn-cta:active { transform: translateY(0) scale(1); box-shadow: 0 10px 22px rgba(31, 154, 87, 0.28); }
        .btn-cta i { font-size: 1.1rem; }

        #personDetailModal .person-photo-container {
            border-radius: 12px;
            cursor: zoom-in;
            border: 4px solid var(--light-green);
        }

        .modal-body .icon-placeholder-lg { font-size: 5rem; color: var(--primary-color); }

        .detail-row {
            display: flex; align-items: flex-start;
            background: linear-gradient(180deg, #fcfcfc 0%, #f4f6f5 100%);
            padding: 12px; border-radius: 10px; margin-bottom: 12px;
            border: 1px solid #eef2f1;
            transition: transform 200ms var(--ease-smooth), box-shadow 200ms var(--ease-smooth);
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .detail-row:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(0,0,0,0.08); }

        /* تصميم عصري لحسابات التواصل */
        .contact-accounts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
            gap: 12px;
            margin-top: 12px;
        }

        .contact-account-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 16px 12px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid #e9ecef;
            border-radius: 16px;
            transition: all 300ms var(--ease-smooth);
            text-decoration: none;
            color: var(--dark-green);
            position: relative;
            overflow: hidden;
        }

        .contact-account-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-green) 100%);
            opacity: 0;
            transition: opacity 300ms var(--ease-smooth);
        }

        .contact-account-item:hover {
            transform: translateY(-4px) scale(1.05);
            border-color: var(--primary-color);
            box-shadow: 0 12px 24px rgba(55, 160, 92, 0.25);
        }

        .contact-account-item:hover::before {
            opacity: 0.1;
        }

        .contact-account-item:hover .contact-icon,
        .contact-account-item:hover .contact-label {
            color: var(--primary-color);
            transform: scale(1.1);
        }

        .contact-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 8px;
            transition: all 300ms var(--ease-smooth);
            z-index: 1;
            position: relative;
        }

        .contact-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--dark-green);
            text-align: center;
            line-height: 1.2;
            z-index: 1;
            position: relative;
            transition: all 300ms var(--ease-smooth);
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .contact-account-item:hover .contact-label {
            white-space: normal;
            word-break: break-word;
        }

        @media (max-width: 768px) {
            .contact-accounts-grid {
                grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
                gap: 8px;
            }
            .contact-icon {
                font-size: 1.5rem;
            }
            .contact-label {
                font-size: 0.65rem;
            }
        }

        /* Desktop: Single column layout */
        .detail-row-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        /* Mobile: 2 columns layout for small details */
        @media (max-width: 768px) {
            .detail-row {
                gap: 4px;
                padding: 10px;
            }
            .detail-row-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
            .detail-row-container > .detail-row {
                margin-bottom: 0;
                font-size: 0.85rem;
            }
        }

        .spouse-card, .child-card, .parent-card, .friend-card {
            display: flex; align-items: center; gap: 12px;
            background-color: #fff; padding: 12px; border-radius: 10px;
            border: 1px solid var(--border-color);
            transition: transform 180ms var(--ease-smooth), box-shadow 200ms var(--ease-smooth), border-color 200ms var(--ease-smooth);
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .child-card.clickable, .parent-card.clickable, .friend-card.clickable { cursor: pointer; }
        .child-card.clickable:hover, .parent-card.clickable:hover, .friend-card.clickable:hover {
            background-color: var(--light-green);
            transform: translateY(-3px);
            box-shadow: 0 14px 28px rgba(0,0,0,0.10);
            border-color: var(--primary-color);
        }
        .spouse-card img, .child-card img, .parent-card img, .friend-card img {
            width: 45px; height: 45px; border-radius: 50%; object-fit: cover;
        }
        .spouse-card .icon-placeholder-sm, .child-card .icon-placeholder-sm, .parent-card .icon-placeholder-sm, .friend-card .icon-placeholder-sm {
            font-size: 1.5rem; color: var(--primary-color);
            width: 45px; height: 45px; background-color: var(--light-gray);
            border-radius: 50%; display: flex; justify-content: center; align-items: center;
        }

        /* تحسين عرض حالة الوفاة */
        .spouse-card.is-deceased small, .child-card.is-deceased small, .parent-card.is-deceased small, .friend-card.is-deceased small {
            color: #dc3545 !important;
            font-weight: 600;
        }

        .article-card {
            display: flex; align-items: center; gap: 15px;
            background-color: var(--light-gray);
            padding: 12px; border-radius: 8px; border: 1px solid var(--border-color);
            transition: all 0.2s; text-decoration: none; color: var(--dark-green);
            margin-bottom: 10px;
        }
        .article-card:hover { background-color: var(--light-green); border-color: var(--primary-color); transform: translateY(-2px); color: var(--dark-green); }
        .article-card i { font-size: 1.5rem; color: var(--primary-color); }

        .biography-wrapper { position: relative; }
        .biography-text { white-space: pre-wrap; margin-bottom: 0; transition: max-height 0.4s ease-out; overflow: hidden; }
        .biography-text.collapsed {
            max-height: 88px;
            -webkit-mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
            mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
        }
        .read-more-btn {
            background: none; border: none; color: var(--primary-color); font-weight: bold; cursor: pointer;
            padding: 5px 0; margin-top: 5px; display: none;
        }

        /* ====== تمييز ذكي للمتوفّين (بدون تظليل الصور) ====== */
        .is-deceased .deceased-icon { display: none !important; } /* أخفي الأيقونة القديمة */

        /* إطار/هالة حداد حول الصورة */
        .is-deceased .person-photo-container {
            box-shadow: 0 0 0 3px var(--mourning), 0 0 0 6px #fff;
        }
        /* شريط أسود صغير مائل على الصورة */
        .is-deceased .person-photo-container::after {
            content: "";
            position: absolute;
            inset: auto 8px 8px auto;
            width: 38%;
            height: 6px;
            background: var(--mourning);
            transform: rotate(-20deg);
            opacity: 0.9;
            border-radius: 4px;
        }

        /* ====== بادج حداد داخل حدود الصورة (للكروت خارج المودال) ====== */
        .mourning-badge {
            position: absolute;
            top: 8px;
            inset-inline-end: 8px; /* يمين في RTL */
            background: linear-gradient(135deg, #000, #2e2e2e);
            color: #fff;
            font-weight: 700;
            font-size: .75rem;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            z-index: 3;
            box-shadow: 0 2px 8px rgba(0,0,0,.25);
        }
        .mourning-badge i { font-size: .85rem; }

        /* عند صور الخلفية (photo-bg) — نضعها داخل الزر نفسه */
        .accordion-button.photo-bg { position: relative; }
        .accordion-button.photo-bg .mourning-badge {
            position: absolute;
            top: 8px;
            inset-inline-end: 8px;
            z-index: 3; /* فوق التدرج ::before */
        }

        /* تعطيل إطار/شارة الحداد داخل مودال التفاصيل فقط */
        #personDetailModal .is-deceased .person-photo-container {
            box-shadow: none;
        }
        #personDetailModal .is-deceased .person-photo-container::after {
            display: none;
        }

        /* احترام تفضيل تقليل الحركة */
        @media (prefers-reduced-motion: reduce) {
            * { animation-duration: 0.001ms !important; animation-iteration-count: 1 !important; transition-duration: 0.001ms !important; scroll-behavior: auto !important; }
            .reveal { opacity: 1 !important; transform: none !important; }
        }

        /* --- START: Mobile Responsive Styles --- */
        @media (max-width: 768px) {
            .tree-section { padding-top: 90px; padding-left: 2px; padding-right: 2px; }

            .accordion-group-item, .accordion-item { width: 110px; }
            .accordion-collapse { width: 110px; padding-right: 10px; }

            .accordion-button:not(.photo-bg) .person-photo-container {
                width: 60px !important; height: 60px !important; margin-bottom: 5px;
            }
            .accordion-button .person-photo-container .icon-placeholder { font-size: 2.2rem !important; }
            .accordion-button.photo-bg { min-height: 120px; }

            .accordion-button .person-name { font-size: 0.75rem; line-height: 1.2; }

            .actions-bar .btn { font-size: 9px; padding: 4px 2px; }

            .deceased-icon { width: 18px; height: 18px; font-size: 10px; bottom: 2px; left: 2px; }

            .mourning-badge {
                inset-inline-end: 6px;
                top: 6px;
                font-size: .70rem;
                padding: 3px 6px;
            }
        }
        /* --- END: Mobile Responsive Styles --- */
    </style>
</head>

<body>

    {{-- تضمين الهيدر من الملف المنفصل --}}
    @include('partials.main-header')

    <main>
        <section class="tree-section">
            <div class="container-fluid">
                <div class="tree-title-sec">
                    <h3>تواصل عائلة السريِّع </h3>
                </div>

                <div class="p-3">
                    <div class="accordion" id="tree_level_0">
                        <div class="text-center py-5">
                            <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">جاري تحميل تواصل العائلة...</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Modals --}}
    <div class="modal fade" id="personDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- زر رجوع داخل نفس المودال --}}
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

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const API_BASE_URL = '/api';
            const treeContainer = document.getElementById('tree_level_0');
            const personDetailModalEl = document.getElementById('personDetailModal');
            const personModal = new bootstrap.Modal(personDetailModalEl);
            const modalBackBtn = document.getElementById('modalBackBtn');

            // ====== ستاك للتاريخ داخل نفس المودال ======
            const modalHistory = [];

            function updateBackBtn() {
                if (modalHistory.length > 1) {
                    modalBackBtn.classList.remove('d-none');
                } else {
                    modalBackBtn.classList.add('d-none');
                }
            }

            function initTooltips(root = document) {
                const tooltipTriggerList = [].slice.call(root.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(el => {
                    if (!bootstrap.Tooltip.getInstance(el)) {
                        new bootstrap.Tooltip(el);
                    }
                });
            }

            // ====== ظهور تدريجي للعناصر عند التمرير ======
            function initRevealAnimations(root = document) {
                try {
                    const elements = root.querySelectorAll('.accordion-group-item, .accordion-item');
                    if (!elements || elements.length === 0) return;

                    // ضع كلاس reveal كبداية
                    elements.forEach(el => el.classList.add('reveal'));

                    if ('IntersectionObserver' in window) {
                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    entry.target.classList.add('in-view');
                                    observer.unobserve(entry.target);
                                }
                            });
                        }, { rootMargin: '60px 0px', threshold: 0.1 });

                        elements.forEach(el => observer.observe(el));
                    } else {
                        // متصفحات قديمة: أظهر مباشرة
                        elements.forEach(el => el.classList.add('in-view'));
                    }
                } catch (e) {
                    console.warn('Reveal animations init failed', e);
                }
            }

            async function fetchAPI(endpoint) {
                try {
                    // إضافة timeout للاستعلامات لتجنب الانتظار الطويل
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 15000); // 15 ثانية timeout

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
                    if (error.name === 'AbortError') {
                        treeContainer.innerHTML =
                            `<div class="alert alert-warning text-center">استغرق التحميل وقتاً طويلاً. يرجى المحاولة مرة أخرى.</div>`;
                    } else {
                        treeContainer.innerHTML =
                            `<div class="alert alert-danger text-center">حدث خطأ أثناء تحميل البيانات. يرجى المحاولة مرة أخرى.</div>`;
                    }
                    return null;
                }
            }

            /**
             * إنشاء صورة الشخص
             * @param {object} person
             * @param {'sm'|'md'|'lg'} size
             * @param {boolean} showBadge إظهار بادج "في ذمة الله" أم لا
             */
            function createPhoto(person, size = 'md', showBadge = true) {
                const sizes = {
                    sm: { container: '45px', icon: '1.5rem' },
                    md: { container: '120px', icon: '5rem' },
                    lg: { container: '150px', icon: '6rem' }
                };
                const currentSize = sizes[size] || sizes['md'];
                const iconClass = person.gender === 'female' ? 'fa-female' : 'fa-male';
                const iconContainerClass = size === 'sm' ? 'icon-placeholder-sm' : (size === 'lg' ? 'icon-placeholder-lg' : 'icon-placeholder');

                const photoHtml = person.photo_url
                    ? `<img src="${person.photo_url}" alt="${person.first_name}"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">`
                    : '';

                const iconHtml = `
                    <div class="${iconContainerClass}" style="display:${person.photo_url ? 'none' : 'flex'};">
                        <i class="fas ${iconClass}"></i>
                    </div>`;

                // لا نظهر البادج إن كان showBadge=false (المودال)
                const badgeHtml = (person.death_date && showBadge)
                    ? `<span class="mourning-badge" role="note" aria-label="في ذمّة الله${person.death_date ? ' - توفي في: ' + person.death_date : ''}">
                            في ذمّة الله <i class="fa-solid fa-dove"></i>
                       </span>`
                    : '';

                // الأيقونة القديمة تبقى موجودة لكن مخفية عبر CSS
                const deceasedIconHtml = person.death_date
                    ? `<div class="deceased-icon"><i class="fas fa-dove"></i></div>`
                    : '';

                return `
                    <div class="person-photo-container" style="width:${currentSize.container}; height:${currentSize.container};">
                        ${photoHtml}
                        ${iconHtml}
                        ${badgeHtml}
                        ${deceasedIconHtml}
                    </div>`;
            }

            function createPersonNode(person, level = 0, groupKey = 'root') {
                const hasChildren = person.children_count > 0;
                const isDeceased = !!person.death_date;

                const parentSelector = (level === 0) ? `#tree_level_0` : `#tree_level_${level}_${groupKey}`;
                const uniqueId = `person_${person.id}_level_${level}`;
                const itemClass = (level === 0) ? 'accordion-group-item' : 'accordion-item';
                const hasPhoto = !!person.photo_url;

                const bgClass = hasPhoto ? 'photo-bg' : '';
                const bgStyle = hasPhoto
                    ? `style="background-image: url('${person.photo_url}');"`
                    : '';

                const btnLabel = isDeceased
                    ? `${person.first_name}`  /* لا نكتب "في ذمة الله" */
                    : person.first_name;

                const buttonContent = `
                    ${hasPhoto ? `
                        <!-- خارج المودال: نظهر البادج -->
                        ${isDeceased ? `
                            <span class="mourning-badge" role="note" aria-label="في ذمّة الله${person.death_date ? ' - توفي في: ' + person.death_date : ''}">
                                في ذمّة الله <i class="fa-solid fa-dove"></i>
                            </span>
                        ` : ''}
                    ` : createPhoto(person, 'md', true)}
                    <span class="person-name">${person.first_name}</span>
                `;

                const childrenAccordionId = `tree_level_${level + 1}_${person.id}`;

                const buttonOrDiv = hasChildren
                    ? `<button class="accordion-button collapsed ${bgClass}" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse_${uniqueId}"
                            onclick="loadChildren(this)"
                            data-person-id="${person.id}"
                            data-level="${level + 1}"
                            data-group-key="${person.id}"
                            aria-label="${btnLabel}"
                            ${bgStyle}>
                        ${buttonContent}
                    </button>`
                    : `<div class="accordion-button collapsed ${bgClass}"
                            aria-label="${btnLabel}"
                            ${bgStyle}>
                        ${buttonContent}
                    </div>`;

                return `
                    <div class="${itemClass} ${isDeceased ? 'is-deceased' : ''}">
                        <h2 class="accordion-header">${buttonOrDiv}</h2>
                        <div class="actions-bar">
                            <button class="btn" onclick="showPersonDetails(${person.id})">
                                <i class="fas fa-info-circle me-1"></i> التفاصيل
                            </button>
                        </div>
                        ${hasChildren ? `
                            <div id="collapse_${uniqueId}" class="accordion-collapse collapse" data-bs-parent="${parentSelector}">
                                <div class="accordion-body p-0">
                                    <div class="accordion" id="${childrenAccordionId}"></div>
                                </div>
                            </div>` : ''}
                    </div>`;
            }

            window.loadChildren = async (buttonElement) => {
                if (buttonElement.dataset.loaded === 'true') return;

                const personId = buttonElement.dataset.personId;
                const level = parseInt(buttonElement.dataset.level, 10);
                const groupKey = buttonElement.dataset.groupKey;

                const childrenAccordionId = `tree_level_${level}_${groupKey}`;
                const childrenContainer = document.getElementById(childrenAccordionId);
                if (!childrenContainer) return;

                // إظهار مؤشر تحميل محسن
                childrenContainer.innerHTML = `
                    <div class="p-2 text-center text-muted small">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        جارٍ التحميل...
                    </div>`;

                const data = await fetchAPI(`/person/${personId}/children`);
                childrenContainer.innerHTML = '';

                if (data && data.children && data.children.length > 0) {
                    // استخدام DocumentFragment لتحسين الأداء
                    const fragment = document.createDocumentFragment();

                    data.children.forEach(child => {
                        const childDiv = document.createElement('div');
                        childDiv.innerHTML = createPersonNode(child, level, groupKey);
                        fragment.appendChild(childDiv);
                    });

                    childrenContainer.appendChild(fragment);
                    initTooltips(childrenContainer);

                    // تفعيل تأثير الظهور للعناصر المضافة
                    initRevealAnimations(childrenContainer);

                    // تطبيق Stagger على العناصر المُضافة
                    try {
                        const newItems = childrenContainer.querySelectorAll('.accordion-item');
                        let delay = 0;
                        newItems.forEach(item => {
                            item.classList.add('reveal');
                            setTimeout(() => item.classList.add('in-view'), delay);
                            delay += 60; // 60ms بين كل عنصر لظهور سلس
                        });
                    } catch (_) {}

                    // إظهار رسالة نجاح إذا كانت البيانات من cache
                    if (data.cached) {
                        console.log(`تم تحميل أبناء الشخص ${personId} من الذاكرة المؤقتة`);
                    }
                } else {
                    childrenContainer.innerHTML = `<div class="p-2 text-center text-muted small">لا يوجد أبناء.</div>`;
                }
                buttonElement.dataset.loaded = 'true';
            };

            window.toggleBiography = (btn) => {
                const wrapper = btn.closest('.biography-wrapper');
                const textElement = wrapper.querySelector('.biography-text');
                textElement.classList.toggle('collapsed');
                btn.textContent = textElement.classList.contains('collapsed') ? 'عرض المزيد' : 'عرض أقل';
            };

            function setupBiography() {
                const textElement = document.getElementById('biographyText');
                if (!textElement) return;
                const btnElement = document.getElementById('readMoreBtn');
                const collapsedHeight = 88;
                if (textElement.scrollHeight > collapsedHeight) {
                    textElement.classList.add('collapsed');
                    btnElement.style.display = 'inline-block';
                }
            }

            // ====== نسخة تدعم Stack + History API ======
            window.showPersonDetails = async (personId, { push = true } = {}) => {
                const modalBody = document.getElementById('modalBodyContent');

                if (push) {
                    modalHistory.push(personId);
                    history.pushState({ personId }, '', `#person-${personId}`);
                }

                personModal.show();
                updateBackBtn();

                // إظهار مؤشر تحميل محسن
                modalBody.innerHTML = `
                    <div class="text-center p-5">
                        <div class="spinner-border text-success" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-3">جاري تحميل التفاصيل...</p>
                        <small class="text-muted">قد يستغرق هذا بضع ثوانٍ</small>
                    </div>`;

                const data = await fetchAPI(`/person/${personId}`);
                if (!data || !data.person) {
                    modalBody.innerHTML = `<div class="alert alert-danger">فشل تحميل البيانات.</div>`;
                    return;
                }

                // إظهار رسالة نجاح إذا كانت البيانات من cache
                if (data.cached) {
                    console.log(`تم تحميل تفاصيل الشخص ${personId} من الذاكرة المؤقتة`);
                }

                const person = data.person;

                const createDetailRow = (icon, label, value) => !value ? '' :
                    `<div class="detail-row">
                        <div><small class="text-muted">${label}</small><p class="mb-0 fw-bold">${value}</p></div>
                    </div>`;

                const createDetailRowWithLink = (icon, label, value, link) => !value ? '' :
                    `<div class="detail-row">
                        <div><small class="text-muted">${label}</small><p class="mb-0 fw-bold"><a href="${link}" target="_blank">${value}</a></p></div>
                    </div>`;

                /* 🚫 لا نظهر "في ذمة الله" كنص داخل المودال */
                /* نُبقي فقط الإطار الأسود حول الصورة عبر كلاس is-deceased */

                let articlesHtml = '';
                if (person.articles && person.articles.length > 0) {
                    articlesHtml = `<h5>المقالات</h5>`;
                    person.articles.forEach(article => {
                        const articleUrl = `/article/${article.id}`;
                        articlesHtml += `
                            <a href="${articleUrl}" target="_blank" class="article-card">
                                <i class="fas fa-book-open"></i>
                                <div>
                                    <strong>${article.title}</strong>
                                    <small class="d-block text-muted">اضغط لعرض المقال</small>
                                </div>
                            </a>`;
                    });
                }

                let parentsHtml = '';
                if (person.parent || person.mother) {
                    parentsHtml = '<h5>الوالدين</h5><div class="row g-2">';
                    if (person.parent) {
                        const parentStatusText = person.parent.death_date ? '(رحمه الله)' : 'الأب';
                        parentsHtml += `
                            <div class="col-md-6">
                                <div class="parent-card clickable ${person.parent.death_date ? 'is-deceased' : ''}" onclick="showPersonDetails(${person.parent.id})">
                                    ${createPhoto(person.parent, 'sm', false)}
                                    <div><strong>${person.parent.first_name}</strong><small class="d-block text-muted">${parentStatusText}</small></div>
                                </div>
                            </div>`;
                    }
                    if (person.mother) {
                        const motherStatusText = person.mother.death_date ? '(رحمها الله)' : 'الأم';
                        parentsHtml += `
                            <div class="col-md-6">
                                <div class="parent-card clickable ${person.mother.death_date ? 'is-deceased' : ''}" onclick="showPersonDetails(${person.mother.id})">
                                    ${createPhoto(person.mother, 'sm', false)}
                                    <div><strong>${person.mother.first_name}</strong><small class="d-block text-muted">${motherStatusText}</small></div>
                                </div>
                            </div>`;
                    }
                    parentsHtml += '</div><hr class="my-4">';
                }

                let spousesHtml = '';
                if (person.spouses && person.spouses.length > 0) {
                    const spouseLabel = person.gender === 'female' ? 'الزوج' : 'الزوجات';
                    spousesHtml = `
                        <h5>${spouseLabel}</h5>
                        <div class="row g-2">`;
                    person.spouses.forEach(spouse => {
                        let spouseStatusText;
                        if (spouse.death_date) {
                            spouseStatusText = spouse.gender === 'female' ? '(رحمها الله)' : '(رحمه الله)';
                        } else {
                            spouseStatusText = spouse.gender === 'female' ? 'زوجة' : 'زوج';
                        }

                        // بناء full_name يدوياً إذا لم يكن موجوداً
                        let spouseFullName = spouse.full_name || '';
                        if (!spouseFullName && spouse.first_name) {
                            spouseFullName = spouse.first_name;
                            if (spouse.last_name) {
                                spouseFullName += ' ' + spouse.last_name;
                            }
                        }

                        spousesHtml += `
                            <div class="col-md-6">
                                <div class="spouse-card clickable ${spouse.death_date ? 'is-deceased' : ''}" onclick="showPersonDetails(${spouse.id})">
                                    ${createPhoto(spouse, 'sm', false)}
                                    <div>
                                        <strong>${spouseFullName || spouse.first_name || 'غير معروف'}</strong>
                                        <small class="d-block text-muted">${spouseStatusText}</small>
                                    </div>
                                </div>
                            </div>`;
                    });
                    spousesHtml += '</div><hr class="my-4">';
                }

                let biographyHtml = '';
                if (person.biography && person.biography.trim() !== '') {
                    biographyHtml = `
                        <div class="biography-wrapper">
                            <h5>نبذة عن</h5>
                            <p id="biographyText" class="biography-text">${person.biography}</p>
                            <button id="readMoreBtn" class="read-more-btn" onclick="toggleBiography(this)">عرض المزيد</button>
                        </div>
                        <hr class="my-4">`;
                }

                let childrenHtml = (person.children_count > 0)
                    ? `<h5>الأبناء (${person.children_count})</h5><div id="modalChildrenList" class="row g-2"></div>`
                    : '';

                // زر معرض الصور
                let galleryButtonHtml = (person.images_count > 0)
                    ? `<a class="btn-cta" onclick="openPersonGallery(${person.id})" role="button" href="javascript:void(0)" title="معرض الصور">
                            <i class="fas fa-images"></i>
                            <span>الصور</span>
                        </a>`
                    : '';

                // Placeholder زر القصص (سيتم إظهاره بعد فحص العداد)
                const storiesBtnPlaceholder = `<div id="personStoriesButton"></div>`;

                // Placeholder زر الأصدقاء (سيتم إظهاره بعد فحص العداد)
                const friendshipsBtnPlaceholder = `<div id="personFriendshipsButton"></div>`;

                document.getElementById('modalBodyContent').innerHTML = `
                    <div class="row g-4">
                        <div class="col-lg-4 text-center">
                            <div class="d-inline-block ${person.death_date ? 'is-deceased' : ''}">${createPhoto(person, 'lg', false)}</div>
                            <h4 class="mt-3 mb-1">${person.full_name || person.first_name}</h4>
                            <!-- 🚫 لا نص "في ذمة الله" ولا "على قيد الحياة" هنا -->
                            <div class="d-flex justify-content-center gap-2 mb-4 flex-wrap">
                                ${galleryButtonHtml}
                                ${storiesBtnPlaceholder}
                                ${friendshipsBtnPlaceholder}
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="detail-row-container">
                                ${(person.gender === 'male' || (person.gender === 'female' && person.birth_date && new Date(person.birth_date).getFullYear() >= 2005)) ? createDetailRow('fa-birthday-cake', 'تاريخ الميلاد', person.birth_date) : ''}
                                ${(person.gender === 'male' || (person.gender === 'female' && person.birth_date && new Date(person.birth_date).getFullYear() >= 2005)) && person.age ? createDetailRow('fa-calendar-alt', 'العمر', `${person.age} سنة`) : ''}
                                ${createDetailRow('fa-map-marked-alt', 'مكان الميلاد', person.birth_place)}
                                ${createDetailRow('fa-map-marker-alt', 'مكان الإقامة', person.location)}
                                ${createDetailRow('fa-tombstone-alt', 'مكان الوفاة', person.death_place)}
                                ${createDetailRow('fa-building', 'المقبرة', person.cemetery)}
                                ${createDetailRow('fa-hashtag', 'رقم القبر', person.grave_number)}
                                ${person.cemetery_location ? (() => {
                                    const locationUrl = person.cemetery_location.startsWith('http://') || person.cemetery_location.startsWith('https://')
                                        ? person.cemetery_location
                                        : `https://${person.cemetery_location}`;
                                    return createDetailRowWithLink('fa-map-pin', 'لوكيشن القبر', person.cemetery_location, locationUrl);
                                })() : ''}
                                ${person.death_date ? createDetailRow('fa-dove', 'تاريخ الوفاة', person.death_date) : ''}
                                ${createDetailRow('fa-briefcase', 'المهنة', person.occupation)}
                            </div>
                            ${person.locations && person.locations.length > 0 ? `
                                <hr class="my-4">
                                <h5>المواقع</h5>
                                <div class="row g-2">
                                    ${person.locations.map(loc => `
                                        <div class="col-md-6">
                                            <div class="detail-row">
                                                <div>
                                                    <small class="text-muted">${loc.label || 'موقع'}</small>
                                                    <p class="mb-0 fw-bold">${loc.name}</p>
                                                    ${loc.is_primary ? '<span class="badge bg-success">أساسي</span>' : ''}
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            ` : ''}
                            ${person.contact_accounts && person.contact_accounts.length > 0 ? `
                                <hr class="my-4">
                                <h5>حسابات التواصل</h5>
                                <div class="contact-accounts-grid">
                                    ${person.contact_accounts.map(account => `
                                        <a href="${account.url}" target="_blank" class="contact-account-item"
                                           title="${account.value}${account.label ? ' - ' + account.label : ''}">
                                            <i class="fas ${account.icon} contact-icon"></i>
                                            ${account.label ? `<span class="contact-label">${account.label}</span>` : ''}
                                        </a>
                                    `).join('')}
                                </div>
                            ` : ''}
                            <hr class="my-4">
                            ${parentsHtml}
                            ${spousesHtml}
                            ${biographyHtml}
                            ${childrenHtml}
                            ${articlesHtml}
                        </div>
                    </div>`;

                setupBiography();

                if (person.children_count > 0) loadModalChildren(person.id);

                // جلب عداد القصص لهذا الشخص لإظهار زر القصص عند الحاجة
                insertStoriesButton(person.id, person.full_name);

                // جلب عداد الأصدقاء لهذا الشخص لإظهار زر الأصدقاء عند الحاجة
                insertFriendshipsButton(person.id);
            };

            async function insertStoriesButton(personId, personFullName) {
                try {
                    const res = await fetch(`/api/person/${personId}/stories/count`, { headers: { 'Accept': 'application/json' }});
                    if (!res.ok) return;
                    const data = await res.json();
                    if (data && data.count && data.count > 0) {
                        const holder = document.getElementById('personStoriesButton');
                        if (holder) {
                            holder.innerHTML = `
                                <a class="btn-cta" href="/stories/person/${personId}" title="أحداث وقصص">
                                    <i class="fas fa-book-open"></i>
                                    <span>كتب وابحاث</span>
                                </a>
                            `;
                        }
                    }
                } catch (e) {
                    console.warn('Failed to fetch stories count', e);
                }
            }

            async function insertFriendshipsButton(personId) {
                try {
                    const res = await fetch(`/api/person/${personId}/friendships/count`, { headers: { 'Accept': 'application/json' }});
                    if (!res.ok) return;
                    const data = await res.json();
                    if (data && data.count && data.count > 0) {
                        const holder = document.getElementById('personFriendshipsButton');
                        if (holder) {
                            holder.innerHTML = `
                                <a class="btn-cta" href="javascript:void(0)" onclick="showFriendships(${personId})" title="الأصدقاء">
                                    <i class="fas fa-user-friends"></i>
                                    <span>الأصدقاء</span>
                                </a>
                            `;
                        }
                    }
                } catch (e) {
                    console.warn('Failed to fetch friendships count', e);
                }
            }

            window.showFriendships = (personId) => {
                window.open(`/person/${personId}/friends`, '_blank');
            };

            // تم إزالة openFriendshipDetails - الآن يتم عرض التفاصيل في مودال في صفحة الأصدقاء

            async function loadModalChildren(personId) {
                const childrenContainer = document.getElementById('modalChildrenList');
                if (!childrenContainer) return;
                childrenContainer.innerHTML =
                    `<div class="col-12 text-center text-muted p-3">جاري تحميل الأبناء...</div>`;
                const data = await fetchAPI(`/person/${personId}/children-details`);
                childrenContainer.innerHTML = '';
                if (data && data.children && data.children.length > 0) {
                    data.children.forEach(child => {
                        let statusText;
                        if (child.death_date) {
                            statusText = child.gender === 'male' ? '(رحمه الله)' : '(رحمها الله)';
                        } else {
                            statusText = child.gender === 'female' ? 'ابنة' : 'ابن';
                        }

                        childrenContainer.innerHTML += `
                            <div class="col-md-6">
                                <div class="child-card clickable ${child.death_date ? 'is-deceased' : ''}" onclick="showPersonDetails(${child.id})">
                                    ${createPhoto(child, 'sm', false)}
                                    <div>
                                        <strong>${child.first_name}</strong>
                                        <small class="d-block text-muted">${statusText}</small>
                                    </div>
                                </div>
                            </div>`;
                    });
                } else {
                    childrenContainer.innerHTML =
                        `<div class="col-12 text-center text-muted p-3">لا يوجد أبناء مسجلين.</div>`;
                }
            }

            async function loadInitialTree() {
                // إظهار مؤشر تحميل محسن
                treeContainer.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">جاري تحميل تواصل العائلة...</p>
                        <small class="text-muted">قد يستغرق هذا بضع ثوانٍ</small>
                    </div>`;

                const data = await fetchAPI('/family-tree');
                if (data && data.tree && data.tree.length > 0) {
                    // استخدام DocumentFragment لتحسين الأداء
                    const fragment = document.createDocumentFragment();

                    data.tree.forEach(person => {
                        const personDiv = document.createElement('div');
                        personDiv.innerHTML = createPersonNode(person, 0, 'root');
                        fragment.appendChild(personDiv);
                    });

                    treeContainer.innerHTML = '';
                    treeContainer.appendChild(fragment);
                    initTooltips(treeContainer);

                    // تفعيل تأثير الظهور للعناصر الأساسية
                    initRevealAnimations(treeContainer);

                    // تطبيق Stagger على المستوى الأول
                    try {
                        const topItems = treeContainer.querySelectorAll('.accordion-group-item');
                        let delay = 0;
                        topItems.forEach(item => {
                            item.classList.add('reveal');
                            setTimeout(() => item.classList.add('in-view'), delay);
                            delay += 80; // إيقاع أبطأ على المستوى الأعلى
                        });
                    } catch (_) {}

                    // إظهار رسالة نجاح إذا كانت البيانات من cache
                    if (data.cached) {
                        console.log('تم تحميل البيانات من الذاكرة المؤقتة');
                    }
                } else {
                    treeContainer.innerHTML =
                        '<div class="alert alert-warning text-center">لا توجد بيانات لعرضها في تواصل العائلة.</div>';
                }
            }

            // إغلاق أي collapse مفتوح في نفس المستوى عند فتح آخر (حفاظًا على الاتساق)
            document.addEventListener('show.bs.collapse', function(event) {
                const collapseElement = event.target;
                const parentSelector = collapseElement.getAttribute('data-bs-parent');
                if (!parentSelector) return;
                const parentAccordion = document.querySelector(parentSelector);
                if (!parentAccordion) return;
                const openCollapses = parentAccordion.querySelectorAll('.accordion-collapse.show');
                openCollapses.forEach(openCollapse => {
                    if (openCollapse !== collapseElement) {
                        const bsCollapseInstance = bootstrap.Collapse.getInstance(openCollapse);
                        if (bsCollapseInstance) bsCollapseInstance.hide();
                    }
                });
            });

            // ====== زر الرجوع داخل المودال ======
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

            // ====== تنظيف التاريخ عند غلق المودال ======
            personDetailModalEl.addEventListener('hidden.bs.modal', () => {
                modalHistory.length = 0;
                updateBackBtn();
                if (location.hash.startsWith('#person-')) {
                    history.replaceState(null, '', location.pathname + location.search);
                }
            });

            // ====== دعم زر Back/Forward في المتصفح/الموبايل للتنقل داخل المودال ======
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
                } else {
                    if (document.body.classList.contains('modal-open')) {
                        personModal.hide();
                    }
                }
            });

            // ====== دالة فتح معرض الصور ======
            window.openPersonGallery = (personId) => {
                window.open(`/person-gallery/${personId}`, '_blank');
            };

            loadInitialTree();
        });
    </script>
</body>

</html>
