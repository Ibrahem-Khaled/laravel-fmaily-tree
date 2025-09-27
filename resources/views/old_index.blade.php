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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            background-color: #fff;
            overflow: visible; /* للسماح بتموضع العناصر الداخلية */
        }

        .accordion-group-item + .accordion-group-item,
        .accordion-item + .accordion-item {
            margin-top: 10px;
        }

        .accordion-group-item:hover,
        .accordion-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

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
            transition: background 0.3s ease;
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
            background: var(--light-gray);
            border-radius: 0 0 12px 12px;
        }
        .actions-bar .btn {
            flex: 1; font-size: 13px; padding: 8px 4px; color: var(--dark-green); border-radius: 0;
        }
        .actions-bar .btn:hover { background-color: #e9ecef; }
        .actions-bar .btn:first-child { border-radius: 0 0 11px 0; }
        .actions-bar .btn:last-child { border-radius: 0 0 0 11px; }

        .modal-header { background-color: var(--dark-green); color: #fff; }
        .modal-header .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        #personDetailModal .person-photo-container {
            border-radius: 12px;
            cursor: zoom-in;
            border: 4px solid var(--light-green);
        }

        .modal-body .icon-placeholder-lg { font-size: 5rem; color: var(--primary-color); }

        .detail-row {
            display: flex; align-items: center;
            background-color: var(--light-gray);
            padding: 12px; border-radius: 8px; margin-bottom: 12px;
        }
        .detail-row i {
            color: var(--primary-color);
            width: 30px; text-align: center; font-size: 1.2rem;
        }

        .spouse-card, .child-card, .parent-card {
            display: flex; align-items: center; gap: 12px;
            background-color: #fff; padding: 10px; border-radius: 8px;
            border: 1px solid var(--border-color); transition: all 0.2s ease-in-out;
        }
        .child-card.clickable, .parent-card.clickable { cursor: pointer; }
        .child-card.clickable:hover, .parent-card.clickable:hover {
            background-color: var(--light-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border-color: var(--primary-color);
        }
        .spouse-card img, .child-card img, .parent-card img {
            width: 45px; height: 45px; border-radius: 50%; object-fit: cover;
        }
        .spouse-card .icon-placeholder-sm, .child-card .icon-placeholder-sm, .parent-card .icon-placeholder-sm {
            font-size: 1.5rem; color: var(--primary-color);
            width: 45px; height: 45px; background-color: var(--light-gray);
            border-radius: 50%; display: flex; justify-content: center; align-items: center;
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

            async function fetchAPI(endpoint) {
                try {
                    const response = await fetch(`${API_BASE_URL}${endpoint}`);
                    if (!response.ok) throw new Error(`API Error: ${response.status}`);
                    return await response.json();
                } catch (error) {
                    console.error('API Fetch Error:', error);
                    treeContainer.innerHTML =
                        `<div class="alert alert-danger text-center">حدث خطأ أثناء تحميل البيانات. يرجى المحاولة مرة أخرى.</div>`;
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

                childrenContainer.innerHTML = `<div class="p-2 text-center text-muted small">جارٍ التحميل...</div>`;
                const data = await fetchAPI(`/person/${personId}/children`);
                childrenContainer.innerHTML = '';

                if (data && data.children && data.children.length > 0) {
                    data.children.forEach(child => {
                        childrenContainer.innerHTML += createPersonNode(child, level, groupKey);
                    });
                    initTooltips(childrenContainer);
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

                modalBody.innerHTML = `
                    <div class="text-center p-5">
                        <div class="spinner-border text-success" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-3">جاري تحميل التفاصيل...</p>
                    </div>`;

                const data = await fetchAPI(`/person/${personId}`);
                if (!data || !data.person) {
                    modalBody.innerHTML = `<div class="alert alert-danger">فشل تحميل البيانات.</div>`;
                    return;
                }
                const person = data.person;

                const createDetailRow = (icon, label, value) => !value ? '' :
                    `<div class="detail-row">
                        <i class="fas ${icon} fa-fw mx-2"></i>
                        <div><small class="text-muted">${label}</small><p class="mb-0 fw-bold">${value}</p></div>
                    </div>`;

                /* 🚫 لا نظهر "في ذمة الله" كنص داخل المودال */
                /* نُبقي فقط الإطار الأسود حول الصورة عبر كلاس is-deceased */

                let articlesHtml = '';
                if (person.articles && person.articles.length > 0) {
                    articlesHtml = `<h5>المقالات</h5>`;
                    person.articles.forEach(article => {
                        const articleUrl = `/articles/${article.id}`;
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
                        parentsHtml += `
                            <div class="col-md-6">
                                <div class="parent-card clickable" onclick="showPersonDetails(${person.parent.id})">
                                    ${createPhoto(person.parent, 'sm', false)}
                                    <div><strong>${person.parent.first_name}</strong><small class="d-block text-muted">الأب</small></div>
                                </div>
                            </div>`;
                    }
                    if (person.mother) {
                        parentsHtml += `
                            <div class="col-md-6">
                                <div class="parent-card clickable" onclick="showPersonDetails(${person.mother.id})">
                                    ${createPhoto(person.mother, 'sm', false)}
                                    <div><strong>${person.mother.first_name}</strong><small class="d-block text-muted">الأم</small></div>
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
                        const spouseLabelText = spouse.gender === 'female' ? 'زوجة' : 'زوج';
                        spousesHtml += `
                            <div class="col-md-6">
                                <div class="spouse-card clickable" onclick="showPersonDetails(${spouse.id})">
                                    ${createPhoto(spouse, 'sm', false)}
                                    <div>
                                        <strong>${spouse.name || spouse.full_name}</strong>
                                        <small class="d-block text-muted">${spouseLabelText}</small>
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
                    ? `<h5>الأبناء (${person.children_count})</h5><div id="modalChildrenList" class="row g-2"></div><hr class="my-4">`
                    : '';

                document.getElementById('modalBodyContent').innerHTML = `
                    <div class="row g-4">
                        <div class="col-lg-4 text-center">
                            <div class="d-inline-block ${person.death_date ? 'is-deceased' : ''}">${createPhoto(person, 'lg', false)}</div>
                            <h4 class="mt-3 mb-1">${person.full_name}</h4>
                            <!-- 🚫 لا نص "في ذمة الله" ولا "على قيد الحياة" هنا -->
                        </div>
                        <div class="col-lg-8">
                            ${createDetailRow('fa-briefcase', 'المهنة', person.occupation)}
                            ${createDetailRow('fa-map-marker-alt', 'مكان الإقامة', person.location)}
                            ${person.death_date ? createDetailRow('fa-dove', 'تاريخ الوفاة', person.death_date) : ''}
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
            };

            async function loadModalChildren(personId) {
                const childrenContainer = document.getElementById('modalChildrenList');
                if (!childrenContainer) return;
                childrenContainer.innerHTML =
                    `<div class="col-12 text-center text-muted p-3">جاري تحميل الأبناء...</div>`;
                const data = await fetchAPI(`/person/${personId}/children`);
                childrenContainer.innerHTML = '';
                if (data && data.children && data.children.length > 0) {
                    data.children.forEach(child => {
                        const relationText = child.gender === 'female' ? 'ابنة' : 'ابن';
                        const deceasedText = child.gender === 'male' ? 'متوفى (رحمه الله)' : 'متوفاة (رحمها الله)';
                        const statusText = child.death_date ? deceasedText : relationText;

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
                const data = await fetchAPI('/family-tree');
                if (data && data.tree && data.tree.length > 0) {
                    treeContainer.innerHTML = '';
                    data.tree.forEach(person => {
                        treeContainer.innerHTML += createPersonNode(person, 0, 'root');
                    });
                    initTooltips(treeContainer);
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

            loadInitialTree();
        });
    </script>
</body>

</html>
