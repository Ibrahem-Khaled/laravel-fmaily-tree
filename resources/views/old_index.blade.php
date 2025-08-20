<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تواصل عائلة السريع</title>

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
        }

        body {
            background: var(--light-gray);
            font-family: 'Alexandria', sans-serif;
            /* لا حاجة لإضافة padding هنا لأن .tree-section يحتوي على padding-top كافية */
        }

        /* --- START: NEW HEADER STYLES --- */
        .navbar {
            background-color: var(--dark-green);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .nav-link {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8) !important;
            transition: color 0.3s ease;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #fff !important;
            font-weight: 600;
        }

        .dashboard-btn {
            border-color: rgba(255, 255, 255, 0.5);
        }

        .dashboard-btn:hover {
            background-color: #fff;
            color: var(--dark-green) !important;
        }

        /* --- END: NEW HEADER STYLES --- */

        /* --- الكود الأصلي الخاص بك (بدون تغيير) --- */
        .tree-section {
            background: linear-gradient(180deg, var(--light-green) 0%, #FFF 100%);
            padding-top: 120px;
            /* هذه المساحة كافية جداً للهيدر الثابت */
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
        }

        .accordion-group-item+.accordion-group-item,
        .accordion-item+.accordion-item {
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

        .accordion-button::after {
            display: none;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

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
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.1) 60%);
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

        .accordion-button.photo-bg .person-photo-container {
            display: none;
        }

        .accordion-button.photo-bg .deceased-icon {
            position: absolute;
            top: 8px;
            left: 8px;
            z-index: 2;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .accordion-button:not(.photo-bg) {
            gap: 10px;
            background-color: #fff;
        }

        .accordion-button .person-photo-container {
            width: 120px !important;
            height: 120px !important;
            margin-bottom: 10px;
        }

        .accordion-button .person-photo-container .icon-placeholder {
            font-size: 5rem !important;
        }

        .accordion-button:not(.photo-bg) .person-name {
            color: #333;
            font-weight: 600;
        }

        .accordion-button:not(.collapsed) {
            color: white !important;
        }

        .accordion-button.photo-bg:not(.collapsed) {
            box-shadow: inset 0 0 0 3px var(--dark-green);
            background-color: transparent !important;
        }

        .accordion-button:not(.photo-bg):not(.collapsed) {
            background-color: var(--dark-green) !important;
        }

        .accordion-button:not(.photo-bg):not(.collapsed) .person-name {
            color: #fff;
        }

        .person-photo-container {
            background-color: var(--light-green);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
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

        .person-photo-container .icon-placeholder {
            color: var(--primary-color);
        }

        .deceased-icon {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 24px;
            height: 24px;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border: 1px solid #fff;
        }

        .actions-bar {
            width: 100%;
            display: flex;
            border-top: 1px solid var(--border-color);
            background: var(--light-gray);
            border-radius: 0 0 12px 12px;
        }

        .actions-bar .btn {
            flex: 1;
            font-size: 13px;
            padding: 8px 4px;
            color: var(--dark-green);
            border-radius: 0;
        }

        .actions-bar .btn:hover {
            background-color: #e9ecef;
        }

        .actions-bar .btn:first-child {
            border-radius: 0 0 11px 0;
        }

        .actions-bar .btn:last-child {
            border-radius: 0 0 0 11px;
        }

        .modal-header {
            background-color: var(--dark-green);
            color: #fff;
        }

        .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        #personDetailModal .person-photo-container {
            border-radius: 12px;
            cursor: zoom-in;
            border: 4px solid var(--light-green);
        }

        .modal-body .icon-placeholder-lg {
            font-size: 5rem;
            color: var(--primary-color);
        }

        .detail-row {
            display: flex;
            align-items: center;
            background-color: var(--light-gray);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .detail-row i {
            color: var(--primary-color);
            width: 30px;
            text-align: center;
            font-size: 1.2rem;
        }

        .spouse-card,
        .child-card,
        .parent-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease-in-out;
        }

        .child-card.clickable,
        .parent-card.clickable {
            cursor: pointer;
        }

        .child-card.clickable:hover,
        .parent-card.clickable:hover {
            background-color: var(--light-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            border-color: var(--primary-color);
        }

        .spouse-card img,
        .child-card img,
        .parent-card img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
        }

        .spouse-card .icon-placeholder-sm,
        .child-card .icon-placeholder-sm,
        .parent-card .icon-placeholder-sm {
            font-size: 1.5rem;
            color: var(--primary-color);
            width: 45px;
            height: 45px;
            background-color: var(--light-gray);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* --- START: CSS CODE FOR ARTICLE CARDS IN MODAL --- */
        .article-card {
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: var(--light-gray);
            padding: 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease-in-out;
            text-decoration: none;
            color: var(--dark-green);
            margin-bottom: 10px;
            /* لإضافة مسافة بين المقالات إذا كانت متعددة */
        }

        .article-card:hover {
            background-color: var(--light-green);
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            color: var(--dark-green);
        }

        .article-card i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        /* --- END: CSS CODE FOR ARTICLE CARDS IN MODAL --- */
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-tree me-2"></i>
                    تواصل عائلة السريع
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#">الرئيسية</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">تواصل العائلة</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('family-tree') }}">العرض الجديد</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('gallery.index') }}">المعرض</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('gallery.articles') }}">المقالات</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">عن العائلة</a>
                        </li>
                    </ul>

                    <div class="d-flex">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm dashboard-btn">
                            <i class="fas fa-tachometer-alt me-1"></i>
                            لوحة التحكم
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <section class="tree-section">
            <div class="container-fluid">
                <div class="tree-title-sec">
                    <h3>تواصل عائلة السريع</h3>
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

            function createPhoto(person, size = 'md') {
                const sizes = {
                    sm: {
                        container: '45px',
                        icon: '1.5rem'
                    },
                    md: {
                        container: '120px',
                        icon: '5rem'
                    },
                    lg: {
                        container: '150px',
                        icon: '6rem'
                    }
                };
                const currentSize = sizes[size] || sizes['md'];
                const iconClass = person.gender === 'female' ? 'fa-female' : 'fa-male';
                const iconContainerClass = size === 'sm' ? 'icon-placeholder-sm' : (size === 'lg' ?
                    'icon-placeholder-lg' : 'icon-placeholder');
                const deceasedStyle = person.death_date ? `style="filter: grayscale(100%);"` : '';

                let photoHtml = '';
                if (person.photo_url) {
                    photoHtml =
                        `<img src="${person.photo_url}" alt="${person.first_name}" ${deceasedStyle} onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">`;
                }

                const iconHtml = `
                <div class="${iconContainerClass}" style="display:${person.photo_url ? 'none' : 'flex'};">
                    <i class="fas ${iconClass}"></i>
                </div>`;

                const deceasedIconHtml = person.death_date ?
                    `<div class="deceased-icon"><i class="fas fa-dove"></i></div>` : '';

                return `
                <div class="person-photo-container" style="width:${currentSize.container}; height:${currentSize.container};">
                    ${photoHtml}
                    ${iconHtml}
                    ${deceasedIconHtml}
                </div>`;
            }

            function createPersonNode(person, level = 0) {
                const hasChildren = person.children_count > 0;
                const uniqueId = `person_${person.id}_level_${level}`;
                const itemClass = (level === 0) ? 'accordion-group-item' : 'accordion-item';
                const parentSelector = `#tree_level_${level}`;
                const hasPhoto = !!person.photo_url;
                const deceasedBgStyle = person.death_date ? `filter: grayscale(100%);` : '';
                const bgClass = hasPhoto ? 'photo-bg' : '';
                const bgStyle = hasPhoto ?
                    `style="background-image: url('${person.photo_url}'); ${deceasedBgStyle}"` : '';

                const buttonContent = `
                    ${hasPhoto ? '' : createPhoto(person, 'md')}
                    <span class="person-name">${person.first_name}</span>
                `;

                const buttonOrDiv = hasChildren ?
                    `<button class="accordion-button collapsed ${bgClass}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_${uniqueId}" onclick="loadChildren(this)" data-person-id="${person.id}" data-level="${level + 1}" ${bgStyle}>
                        ${buttonContent}
                    </button>` :
                    `<div class="accordion-button collapsed ${bgClass}" ${bgStyle}>
                        ${buttonContent}
                    </div>`;

                return `
                    <div class="${itemClass}">
                        <h2 class="accordion-header">${buttonOrDiv}</h2>
                        <div class="actions-bar">
                            <button class="btn" onclick="showPersonDetails(${person.id})"><i class="fas fa-info-circle me-1"></i> التفاصيل</button>
                        </div>
                        ${hasChildren ? `<div id="collapse_${uniqueId}" class="accordion-collapse collapse" data-bs-parent="${parentSelector}"><div class="accordion-body p-0"><div class="accordion" id="tree_level_${level + 1}"></div></div></div>` : ''}
                    </div>`;
            }

            window.loadChildren = async (buttonElement) => {
                if (buttonElement.dataset.loaded === 'true') {
                    return;
                }

                const personId = buttonElement.dataset.personId;
                const level = parseInt(buttonElement.dataset.level);
                const childrenContainer = document.querySelector(
                    `${buttonElement.dataset.bsTarget} .accordion`);

                if (!childrenContainer) return;

                childrenContainer.innerHTML =
                    `<div class="p-2 text-center text-muted small">جاري التحميل...</div>`;
                const data = await fetchAPI(`/person/${personId}/children`);
                childrenContainer.innerHTML = '';

                if (data && data.children && data.children.length > 0) {
                    data.children.forEach(child => {
                        childrenContainer.innerHTML += createPersonNode(child, level);
                    });
                } else {
                    childrenContainer.innerHTML =
                        `<div class="p-2 text-center text-muted small">لا يوجد أبناء.</div>`;
                }
                buttonElement.dataset.loaded = 'true';
            };

            window.showPersonDetails = async (personId) => {
                const modalBody = document.getElementById('modalBodyContent');
                personModal.show();
                modalBody.innerHTML =
                    `<div class="text-center p-5"><div class="spinner-border text-success" style="width: 3rem; height: 3rem;"></div><p class="mt-3">جاري تحميل التفاصيل...</p></div>`;

                const data = await fetchAPI(`/person/${personId}`);
                if (!data || !data.person) {
                    modalBody.innerHTML = `<div class="alert alert-danger">فشل تحميل البيانات.</div>`;
                    return;
                }
                const person = data.person;
                const createDetailRow = (icon, label, value) => !value ? '' :
                    `<div class="detail-row"><i class="fas ${icon} fa-fw mx-2"></i><div><small class="text-muted">${label}</small><p class="mb-0 fw-bold">${value}</p></div></div>`;

                const deceasedText = person.death_date ?
                    `<p class="text-danger fw-bold"><i class="fas fa-dove"></i> ${person.gender === 'male' ? ' (رحمه الله)' : ' (رحمها الله)'}</p>` :
                    `<p class="text-success fw-bold"><i class="fas fa-heart"></i> على قيد الحياة</p>`;

                let articlesHtml = '';
                if (person.articles && person.articles.length > 0) {
                    // تم إزالة الفاصل العلوي من هنا
                    articlesHtml = `<h5>السيرة الذاتية والمقالات</h5>`;
                    person.articles.forEach(article => {
                        const articleUrl = `/articles/${article.id}`; // تأكد من أن هذا الرابط صحيح
                        articlesHtml += `
                            <a href="${articleUrl}" target="_blank" class="article-card">
                                <i class="fas fa-book-open"></i>
                                <div>
                                    <strong>${article.title}</strong>
                                    <small class="d-block text-muted">اضغط لعرض المقال</small>
                                </div>
                            </a>
                        `;
                    });
                     // لا يوجد فاصل سفلي هنا بعد الآن
                }

                let parentsHtml = '';
                if (person.parent || person.mother) {
                    parentsHtml = '<h5>الوالدين</h5><div class="row g-2">';
                    if (person.parent) {
                        parentsHtml += `
                            <div class="col-md-6">
                                <div class="parent-card clickable" onclick="showPersonDetails(${person.parent.id})">
                                    ${createPhoto(person.parent, 'sm')}
                                    <div><strong>${person.parent.first_name}</strong><small class="d-block text-muted">الأب</small></div>
                                </div>
                            </div>`;
                    }
                    if (person.mother) {
                        parentsHtml += `
                            <div class="col-md-6">
                                <div class="parent-card clickable" onclick="showPersonDetails(${person.mother.id})">
                                    ${createPhoto(person.mother, 'sm')}
                                    <div><strong>${person.mother.first_name}</strong><small class="d-block text-muted">الأم</small></div>
                                </div>
                            </div>`;
                    }
                    parentsHtml += '</div><hr class="my-4">';
                }

                let spousesHtml = '';
                if (person.spouses && person.spouses.length > 0) {
                    spousesHtml = `
                        <h5>${person.gender === 'female' ? 'الزوج' : 'الزوجات'}</h5>
                        <div class="row g-2">
                    `;
                    person.spouses.forEach(spouse => {
                        const spouseLabel = spouse.gender === 'female' ? 'زوجة' : 'زوج';
                        spousesHtml += `
                            <div class="col-md-6">
                               <div class="spouse-card clickable" onclick="showPersonDetails(${spouse.id})">
                                    ${createPhoto(spouse, 'sm')}
                                    <div>
                                        <strong>${spouse.name || spouse.full_name}</strong>
                                        <small class="d-block text-muted">${spouseLabel}</small>
                                    </div>
                                </div>
                            </div>`;
                    });
                    spousesHtml += '</div><hr class="my-4">';
                }

                let childrenHtml = (person.children_count > 0) ?
                    `<h5>الأبناء (${person.children_count})</h5><div id="modalChildrenList" class="row g-2"></div><hr class="my-4">` :
                    '';

                // --- START: MODIFIED MODAL BODY STRUCTURE ---
                modalBody.innerHTML = `
                    <div class="row g-4">
                        <div class="col-lg-4 text-center">
                            <div class="d-inline-block">${createPhoto(person, 'lg')}</div>
                            <h4 class="mt-3 mb-1">${person.full_name}</h4>
                            ${deceasedText}
                        </div>
                        <div class="col-lg-8">
                            ${createDetailRow('fa-birthday-cake', 'تاريخ الميلاد', person.birth_date)}
                            ${person.age ? createDetailRow('fa-calendar-alt', 'العمر', `${person.age} سنة`) : ''}
                            ${createDetailRow('fa-briefcase', 'المهنة', person.occupation)}
                            ${createDetailRow('fa-map-marker-alt', 'مكان الإقامة', person.location)}
                            <hr class="my-4">
                            ${parentsHtml}
                            ${spousesHtml}
                            ${person.biography ? `<h5>نبذة عن</h5><p style="white-space: pre-wrap;">${person.biography}</p><hr class="my-4">` : ''}
                            ${childrenHtml}
                            ${articlesHtml}
                        </div>
                    </div>`;
                // --- END: MODIFIED MODAL BODY STRUCTURE ---

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
                        const deceasedText = child.gender === 'male' ? 'متوفى (رحمه الله)' :
                            'متوفاة (رحمها الله)';
                        const statusText = child.death_date ? deceasedText : relationText;

                        childrenContainer.innerHTML += `
                            <div class="col-md-6">
                                <div class="child-card clickable" onclick="showPersonDetails(${child.id})">
                                    ${createPhoto(child, 'sm')}
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
                        treeContainer.innerHTML += createPersonNode(person, 0);
                    });
                } else {
                    treeContainer.innerHTML =
                        '<div class="alert alert-warning text-center">لا توجد بيانات لعرضها في تواصل العائلة.</div>';
                }
            }

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
                        if (bsCollapseInstance) {
                            bsCollapseInstance.hide();
                        }
                    }
                });
            });

            document.addEventListener('shown.bs.collapse', function(event) {
                if (!event.target.closest('.tree-section')) return;
                const scrollContainer = document.querySelector('.tree-section');
                const newColumn = event.target;
                setTimeout(() => {
                    const newScrollLeft = newColumn.offsetLeft - 30;
                    scrollContainer.scrollTo({
                        left: newScrollLeft,
                        behavior: 'smooth'
                    });
                }, 100);
            });

            loadInitialTree();
        });
    </script>
</body>

</html>
